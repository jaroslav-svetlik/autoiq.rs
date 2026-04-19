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

    public function test_listing_form_uses_prepared_vehicle_brand_dropdown(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('listings.create'))
            ->assertOk()
            ->assertSee('Izaberite marku')
            ->assertSeeHtml('<option value="BMW">BMW</option>')
            ->assertSeeHtml('<option value="Buick">Buick</option>')
            ->assertSeeHtml('<option value="Mercedes Benz">Mercedes Benz</option>')
            ->assertSeeHtml('<option value="Xiaomi">Xiaomi</option>')
            ->assertSeeHtml('<option value="Škoda">Škoda</option>');
    }

    public function test_listing_form_uses_prepared_model_dropdown_for_selected_brand(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(FormPage::class)
            ->assertSee('Prvo izaberite marku')
            ->set('brand', 'BMW')
            ->assertSee('Izaberite model')
            ->assertSeeHtml('<option value="Serija 3">Serija 3</option>')
            ->assertSeeHtml('<option value="320d">320d</option>')
            ->assertSeeHtml('<option value="X5">X5</option>');
    }

    public function test_vehicle_catalog_has_model_choices_for_every_prepared_brand(): void
    {
        $brands = config('vehicle_catalog.brands');
        $modelsByBrand = config('vehicle_catalog.models');

        $this->assertSame([], array_diff($brands, array_keys($modelsByBrand)));

        foreach ($brands as $brand) {
            if ($brand === 'Ostalo') {
                continue;
            }

            $this->assertNotEmpty($modelsByBrand[$brand], "Brand {$brand} has no prepared models.");
            $this->assertContains('Ostalo', $modelsByBrand[$brand], "Brand {$brand} has no fallback model option.");
        }
    }

    public function test_listing_form_rejects_brand_outside_prepared_list(): void
    {
        $this->actingAs(User::factory()->create());

        $this->fillVehicleStep(Livewire::test(FormPage::class))
            ->set('brand', 'Nepoznata Marka')
            ->call('nextStep')
            ->assertSet('currentStep', 1)
            ->assertHasErrors(['brand']);
    }

    public function test_listing_form_rejects_model_outside_selected_brand_list(): void
    {
        $this->actingAs(User::factory()->create());

        $this->fillVehicleStep(Livewire::test(FormPage::class))
            ->set('model', 'A4')
            ->call('nextStep')
            ->assertSet('currentStep', 1)
            ->assertHasErrors(['model']);
    }

    public function test_listing_form_clears_model_when_brand_changes(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(FormPage::class)
            ->set('brand', 'BMW')
            ->set('model', '320d')
            ->set('brand', 'Audi')
            ->assertSet('model', '');
    }

    public function test_listing_edit_keeps_legacy_brand_and_model_values_available(): void
    {
        $user = User::factory()->create();
        $listing = Listing::factory()->for($user)->create([
            'brand' => 'Legacy Motors',
            'model' => 'Roadster X',
        ]);

        $this->actingAs($user);

        Livewire::test(FormPage::class, ['listing' => $listing])
            ->assertSet('brand', 'Legacy Motors')
            ->assertSet('model', 'Roadster X')
            ->assertSeeHtml('<option value="Legacy Motors">Legacy Motors</option>')
            ->assertSeeHtml('<option value="Roadster X">Roadster X</option>');
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
