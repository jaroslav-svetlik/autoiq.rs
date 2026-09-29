<?php

namespace Tests\Feature;

use App\Enums\ListingStatus;
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\Listings\IndexPage;
use App\Models\Listing;
use App\Models\SavedSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomePageLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_displays_published_listings_and_keeps_its_canonical_url(): void
    {
        $published = Listing::factory()->create(['title' => 'Objavljen automobil']);
        $draft = Listing::factory()->create(['title' => 'Privatni nacrt', 'status' => ListingStatus::Draft]);

        $this->get(route('home'))->assertOk()
            ->assertSee('Auto oglasi')
            ->assertSee($published->title)
            ->assertDontSee($draft->title)
            ->assertSee('<link rel="canonical" href="'.route('home').'">', false)
            ->assertSee('<meta name="color-scheme" content="light">', false);

        $this->get(route('home', ['brand' => $published->brand]))->assertOk()
            ->assertSee('<meta name="robots" content="noindex,follow">', false)
            ->assertSee('<link rel="canonical" href="'.route('home').'">', false);
    }

    public function test_home_and_directory_share_combined_filters_and_clear_them(): void
    {
        $match = Listing::factory()->create([
            'brand' => 'BMW', 'model' => '320d', 'year' => 2020, 'mileage' => 90000,
            'price' => 18000, 'city' => 'Beograd', 'fuel_type' => 'diesel', 'transmission' => 'automatic',
        ]);
        $match->syncEquipment(['navigation']);
        Listing::factory()->create(['brand' => 'Audi', 'model' => 'A4']);
        Listing::factory()->create(['brand' => 'BMW', 'model' => '320d', 'year' => 2023, 'mileage' => 90000]);
        Listing::factory()->create(['brand' => 'BMW', 'model' => '320d', 'year' => 2020, 'mileage' => 10000]);

        foreach ([HomePage::class, IndexPage::class] as $component) {
            Livewire::test($component)
                ->set('brand', 'BMW')->set('model', '320d')
                ->set('minYear', '2019')->set('maxYear', '2021')
                ->set('minMileage', '50000')->set('maxMileage', '100000')
                ->set('minPrice', '17000')->set('maxPrice', '19000')
                ->set('city', 'Beograd')->set('fuelType', 'diesel')->set('transmission', 'automatic')
                ->set('equipment', ['navigation'])
                ->assertViewHas('listings', fn ($items) => $items->pluck('id')->all() === [$match->id])
                ->call('clearFilters')
                ->assertSet('maxYear', null)->assertSet('minMileage', null)->assertSet('equipment', [])
                ->assertViewHas('listings', fn ($items) => $items->total() === 4);
        }
    }

    public function test_home_search_pagination_and_sorting_use_the_existing_catalog(): void
    {
        $user = User::factory()->create();
        Listing::factory()->count(10)->for($user)->create(['brand' => 'BMW', 'model' => '320d', 'title' => 'BMW 320d', 'price' => 20000]);
        $cheapest = Listing::factory()->for($user)->create(['brand' => 'Audi', 'model' => 'A4', 'title' => 'Audi A4', 'price' => 12000]);

        Livewire::test(HomePage::class)
            ->assertViewHas('listings', fn ($items) => $items->perPage() === 9 && $items->total() === 11)
            ->call('gotoPage', 2)
            ->assertViewHas('listings', fn ($items) => $items->currentPage() === 2)
            ->set('sort', 'price_asc')
            ->assertViewHas('listings', fn ($items) => $items->currentPage() === 1 && $items->first()->id === $cheapest->id)
            ->set('search', 'Audi')
            ->assertViewHas('listings', fn ($items) => $items->total() === 1 && $items->first()->id === $cheapest->id)
            ->set('search', 'NePostojiModel999')
            ->assertSee('Nema rezultata');
    }

    public function test_changing_brand_clears_an_incompatible_model(): void
    {
        Listing::factory()->create(['brand' => 'BMW', 'model' => '320d']);
        Listing::factory()->create(['brand' => 'Audi', 'model' => 'A4']);
        Livewire::test(HomePage::class)->set('brand', 'BMW')->set('model', '320d')
            ->set('brand', 'Audi')->assertSet('model', null)
            ->assertViewHas('models', ['A4']);
    }

    public function test_home_favorites_can_be_added_and_removed(): void
    {
        $user = User::factory()->create();
        $listing = Listing::factory()->create();
        $component = Livewire::actingAs($user)->test(HomePage::class)
            ->call('toggleFavorite', $listing->id);
        $this->assertTrue($user->hasFavorited($listing));
        $component->call('toggleFavorite', $listing->id);
        $this->assertFalse($user->hasFavorited($listing));
    }

    public function test_saved_searches_keep_new_range_filters_and_match_them(): void
    {
        $user = User::factory()->create();
        Livewire::actingAs($user)->test(HomePage::class)
            ->set('maxYear', '2021')->set('minMileage', '50000')
            ->set('saveSearchName', 'Moj izbor')->call('saveCurrentSearch');
        $saved = SavedSearch::query()->firstOrFail();
        $this->assertSame('2021', $saved->filters['max_year']);
        $this->assertSame('50000', $saved->filters['min_mileage']);
        $this->assertTrue($saved->matchesListing(Listing::factory()->make(['year' => 2021, 'mileage' => 50000])));
        $this->assertFalse($saved->matchesListing(Listing::factory()->make(['year' => 2022, 'mileage' => 50000])));
        $this->assertFalse($saved->matchesListing(Listing::factory()->make(['year' => 2020, 'mileage' => 49999])));
    }
}
