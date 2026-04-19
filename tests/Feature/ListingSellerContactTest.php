<?php

namespace Tests\Feature;

use App\Livewire\Pages\Listings\FormPage;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ListingSellerContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_listing_with_custom_seller_contact(): void
    {
        $this->actingAs(User::factory()->create([
            'name' => 'Petar Petrović',
            'phone' => '+381 64 000 000',
        ]));

        $this->fillForm(Livewire::test(FormPage::class))
            ->set('sellerName', 'Nikola Jovanović')
            ->set('sellerPhones', ['+381 64 123 456', '064 987 654'])
            ->call('save')
            ->assertHasNoErrors();

        $listing = Listing::query()->firstOrFail();

        $this->assertSame('Nikola Jovanović', $listing->seller_name);
        $this->assertSame(['+381 64 123 456', '064 987 654'], $listing->seller_phones);

        $this->get(route('listings.show', $listing))
            ->assertOk()
            ->assertSee('Kontakt prodavca')
            ->assertSee('Nikola Jovanović')
            ->assertSee('+381 64 123 456')
            ->assertSee('064 987 654');
    }

    public function test_listing_requires_at_least_one_seller_phone(): void
    {
        $this->actingAs(User::factory()->create());

        $this->fillForm(Livewire::test(FormPage::class))
            ->set('sellerPhones', [''])
            ->call('save')
            ->assertHasErrors(['sellerPhones']);

        $this->assertDatabaseCount('listings', 0);
    }

    public function test_new_listing_prefills_seller_contact_from_user_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Petar Petrović',
            'phone' => '+381 64 000 000',
        ]);

        $this->actingAs($user);

        Livewire::test(FormPage::class)
            ->assertSet('sellerName', 'Petar Petrović')
            ->assertSet('sellerPhones', ['+381 64 000 000']);
    }

    protected function fillForm(Testable $component): Testable
    {
        return $component
            ->set('titleInput', 'BMW 320d xDrive M paket, prvi vlasnik')
            ->set('brand', 'BMW')
            ->set('model', '320d')
            ->set('year', '2018')
            ->set('price', '15900')
            ->set('mileage', '164000')
            ->set('fuelType', 'diesel')
            ->set('transmission', 'automatic')
            ->set('city', 'Beograd')
            ->set('description', 'Detaljan opis vozila sa servisnom istorijom, opremom i svim bitnim informacijama za kupca.')
            ->set('sellerType', 'private')
            ->set('sellerName', 'Milan Petrović')
            ->set('sellerPhones', ['+381 64 123 456']);
    }
}
