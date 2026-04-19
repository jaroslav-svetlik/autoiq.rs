<?php

namespace Tests\Feature;

use App\Livewire\Pages\Listings\FormPage;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ListingFormStepsTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_form_starts_on_first_step_and_validates_before_continuing(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(FormPage::class)
            ->assertSet('currentStep', 1)
            ->call('nextStep')
            ->assertSet('currentStep', 1)
            ->assertHasErrors([
                'titleInput',
                'brand',
                'model',
                'year',
                'price',
                'mileage',
                'fuelType',
                'transmission',
                'city',
                'description',
            ]);
    }

    public function test_user_can_move_through_steps_and_publish_listing(): void
    {
        $this->actingAs(User::factory()->create());

        $this->fillVehicleStep(Livewire::test(FormPage::class))
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('equipment', ['navigation'])
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->call('nextStep')
            ->assertSet('currentStep', 4)
            ->set('sellerType', 'private')
            ->set('sellerName', 'Milan Petrović')
            ->set('sellerPhones', ['+381 64 123 456'])
            ->call('save')
            ->assertHasNoErrors();

        $listing = Listing::query()->firstOrFail();

        $this->assertSame('BMW 320d xDrive M paket, prvi vlasnik', $listing->title);
        $this->assertSame(['+381 64 123 456'], $listing->seller_phones);
    }

    public function test_final_save_moves_user_to_first_step_with_validation_error(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(FormPage::class)
            ->set('currentStep', 4)
            ->set('sellerName', 'Milan Petrović')
            ->set('sellerPhones', ['+381 64 123 456'])
            ->call('save')
            ->assertSet('currentStep', 1)
            ->assertHasErrors(['titleInput']);

        $this->assertDatabaseCount('listings', 0);
    }

    public function test_listing_form_clamps_tampered_step_state(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(FormPage::class)
            ->set('currentStep', 99)
            ->assertSet('currentStep', 4);
    }

    protected function fillVehicleStep(Testable $component): Testable
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
            ->set('description', 'Detaljan opis vozila sa servisnom istorijom, opremom i svim bitnim informacijama za kupca.');
    }
}
