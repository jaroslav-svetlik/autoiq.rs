<?php

namespace Tests\Feature;

use App\Enums\ListingStatus;
use App\Livewire\Pages\Account\DashboardPage;
use App\Livewire\Pages\HomePage;
use App\Models\Listing;
use App\Models\SavedSearch;
use App\Models\User;
use App\Notifications\NewListingMatchAlert;
use App\Notifications\PriceDropAlert;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class AccountListingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_pause_sell_and_reactivate_without_resetting_publication_date(): void
    {
        $owner = User::factory()->create();
        $listing = Listing::factory()->for($owner)->create();
        $publishedAt = $listing->published_at->toDateTimeString();

        $page = Livewire::actingAs($owner)->test(DashboardPage::class, ['tab' => 'oglasi']);
        $page->assertSee('Pauziraj oglas')->assertSee('Označi kao prodato');

        $page->call('setListingStatus', $listing->id, 'paused')->assertSee('Pauziran')->assertSee('Ponovo aktiviraj');
        $this->assertSame(ListingStatus::Paused, $listing->fresh()->status);
        $this->assertFalse($listing->fresh()->shouldBeSearchable());

        $page->call('setListingStatus', $listing->id, 'sold')->assertSee('Prodato')->assertDontSee('Pauziraj oglas');
        $this->assertSame(ListingStatus::Sold, $listing->fresh()->status);

        $page->call('setListingStatus', $listing->id, 'published')->assertSee('Aktivan')->assertSee('Pauziraj oglas');
        $this->assertSame(ListingStatus::Published, $listing->fresh()->status);
        $this->assertSame($publishedAt, $listing->fresh()->published_at->toDateTimeString());
        $this->assertSame(1, $listing->priceHistories()->count());
    }

    public function test_paused_and_sold_listings_are_private_but_remain_accessible_to_the_owner(): void
    {
        $owner = User::factory()->create();
        foreach ([ListingStatus::Paused, ListingStatus::Sold] as $status) {
            $listing = Listing::factory()->for($owner)->create(['status' => $status]);
            $this->get(route('listings.show', $listing))->assertNotFound();
            $this->get(route('sitemap'))->assertDontSee(route('listings.show', $listing));
            Livewire::test(HomePage::class)->assertViewHas('listings', fn ($items) => $items->total() === 0);

            $this->actingAs($owner)->get(route('listings.show', $listing))->assertOk();
            Livewire::test(DashboardPage::class, ['tab' => 'oglasi'])->assertSee($status->label());
            auth()->logout();
        }
    }

    public function test_other_users_cannot_change_or_delete_an_owners_listing(): void
    {
        $listing = Listing::factory()->create();
        $other = User::factory()->create();

        $this->withoutExceptionHandling();
        foreach ([['setListingStatus', 'paused'], ['setListingStatus', 'sold'], ['setListingStatus', 'published'], ['requestListingDeletion'], ['deleteListing']] as $action) {
            try {
                Livewire::actingAs($other)->test(DashboardPage::class)
                    ->call($action[0], $listing->id, ...array_slice($action, 1));
                $this->fail('An unrelated user must not be able to manage this listing.');
            } catch (ModelNotFoundException $exception) {
                $this->assertSame(Listing::class, $exception->getModel());
            }
        }
        $this->assertSame(ListingStatus::Published, $listing->fresh()->status);
        $this->assertNotSoftDeleted($listing);
    }

    public function test_owner_cannot_bypass_moderation_or_use_unsupported_transitions(): void
    {
        $owner = User::factory()->create();
        foreach ([ListingStatus::Draft, ListingStatus::Rejected] as $current) {
            $listing = Listing::factory()->for($owner)->create(['status' => $current]);
            foreach (['published', 'paused', 'sold'] as $next) {
                Livewire::actingAs($owner)->test(DashboardPage::class)
                    ->call('setListingStatus', $listing->id, $next)->assertStatus(422);
                $this->assertSame($current, $listing->fresh()->status);
            }
        }

        $listing = Listing::factory()->for($owner)->create();
        foreach (['draft', 'rejected', 'not-a-status'] as $next) {
            Livewire::actingAs($owner)->test(DashboardPage::class)
                ->call('setListingStatus', $listing->id, $next)->assertStatus(422);
        }
        $this->assertSame(ListingStatus::Published, $listing->fresh()->status);
    }

    public function test_an_unpublished_sold_listing_cannot_be_activated_by_its_owner(): void
    {
        $owner = User::factory()->create();
        $listing = Listing::factory()->for($owner)->create(['status' => ListingStatus::Sold, 'published_at' => null]);
        Livewire::actingAs($owner)->test(DashboardPage::class, ['tab' => 'oglasi'])
            ->assertDontSee('Ponovo aktiviraj')
            ->call('setListingStatus', $listing->id, 'published')->assertStatus(422);
        $this->assertSame(ListingStatus::Sold, $listing->fresh()->status);
    }

    public function test_deletion_is_soft_and_removes_the_card(): void
    {
        $owner = User::factory()->create();
        $listing = Listing::factory()->for($owner)->create();
        Livewire::actingAs($owner)->test(DashboardPage::class, ['tab' => 'oglasi'])
            ->call('requestListingDeletion', $listing->id)->assertSee('Obriši oglas?')
            ->call('deleteListing', $listing->id)->assertSet('pendingListingDeletion', null)->assertSee('Još nemate oglasa.');

        $this->assertSoftDeleted($listing);
        $this->assertSame(1, $listing->priceHistories()->count());
        $this->get(route('listings.show', $listing))->assertNotFound();
    }

    public function test_requesting_and_canceling_deletion_keeps_the_listing_active(): void
    {
        $owner = User::factory()->create();
        $listing = Listing::factory()->for($owner)->create();
        Livewire::actingAs($owner)->test(DashboardPage::class, ['tab' => 'oglasi'])
            ->call('requestListingDeletion', $listing->id)->assertSet('pendingListingDeletion', $listing->id)
            ->call('cancelListingDeletion')->assertSet('pendingListingDeletion', null)->assertDontSee('Obriši oglas?');
        $this->assertNotSoftDeleted($listing);
        $this->assertSame(ListingStatus::Published, $listing->fresh()->status);
    }

    public function test_reactivation_does_not_send_duplicate_new_listing_alerts(): void
    {
        Notification::fake();
        $watcher = User::factory()->create();
        SavedSearch::query()->create(['user_id' => $watcher->id, 'name' => 'Svi oglasi', 'filters' => [], 'notify_new_matches' => true]);
        $owner = User::factory()->create();
        $listing = Listing::factory()->for($owner)->create();
        Notification::assertSentToTimes($watcher, NewListingMatchAlert::class, 1);

        $page = Livewire::actingAs($owner)->test(DashboardPage::class);
        $page->call('setListingStatus', $listing->id, 'paused')->call('setListingStatus', $listing->id, 'published');
        $page->call('setListingStatus', $listing->id, 'sold')->call('setListingStatus', $listing->id, 'published');
        Notification::assertSentToTimes($watcher, NewListingMatchAlert::class, 1);

        $draft = Listing::factory()->for($owner)->create(['status' => ListingStatus::Draft, 'published_at' => null]);
        $draft->update(['status' => ListingStatus::Published]);
        Notification::assertSentToTimes($watcher, NewListingMatchAlert::class, 2);
    }

    public function test_hidden_listings_do_not_send_price_alerts_or_appear_in_favorites(): void
    {
        Notification::fake();
        $watcher = User::factory()->create();
        foreach ([ListingStatus::Paused, ListingStatus::Sold] as $status) {
            $listing = Listing::factory()->create(['status' => $status, 'price' => 20000]);
            $watcher->favoriteListings()->attach($listing);
            $listing->update(['price' => 19000]);
        }
        Notification::assertNothingSent();

        Livewire::actingAs($watcher)->test(DashboardPage::class, ['tab' => 'favoriti'])->assertSee('Lista favorita je prazna.');
        $this->assertSame(2, $watcher->favoriteListings()->count());

        $listing->update(['status' => ListingStatus::Published]);
        Livewire::test(DashboardPage::class, ['tab' => 'favoriti'])->assertDontSee('Lista favorita je prazna.');
        $listing->update(['price' => 18000]);
        Notification::assertSentToTimes($watcher, PriceDropAlert::class, 1);
    }
}
