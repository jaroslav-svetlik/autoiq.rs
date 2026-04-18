<?php

namespace Tests\Feature;

use App\Livewire\Pages\Listings\FormPage;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListingEquipmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_listing_with_selected_equipment(): void
    {
        $this->actingAs(User::factory()->create());

        $this->fillForm(Livewire::test(FormPage::class))
            ->set('equipment', ['navigation', 'parking_camera', 'heated_seats'])
            ->call('save')
            ->assertHasNoErrors();

        $listing = Listing::query()->firstOrFail()->fresh(['equipmentItems']);

        $this->assertSame(
            ['heated_seats', 'navigation', 'parking_camera'],
            $listing->equipmentKeys()->sort()->values()->all(),
        );
        $this->assertContains('Navigacija', $listing->toSearchableArray()['equipment']);
    }

    public function test_listing_edit_syncs_equipment_selection(): void
    {
        $user = User::factory()->create();
        $listing = Listing::factory()->for($user)->create();
        $listing->syncEquipment(['air_conditioning', 'bluetooth', 'usb']);

        $this->actingAs($user);

        $this->fillForm(Livewire::test(FormPage::class, ['listing' => $listing]))
            ->set('equipment', ['dual_zone_climate', 'parking_sensors', 'android_auto'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(
            ['android_auto', 'dual_zone_climate', 'parking_sensors'],
            $listing->fresh()->equipmentKeys()->sort()->values()->all(),
        );
    }

    public function test_listing_detail_page_shows_selected_equipment_labels(): void
    {
        $listing = Listing::factory()->for(User::factory())->create([
            'title' => 'BMW 320d sa opremom',
        ]);

        $listing->syncEquipment(['dual_zone_climate', 'parking_camera', 'apple_carplay']);

        $this->get(route('listings.show', $listing))
            ->assertOk()
            ->assertSee('Izdvojene stavke')
            ->assertSee('Dvozonska klima')
            ->assertSee('Kamera za rikverc')
            ->assertSee('Apple CarPlay');
    }

    protected function fillForm(\Livewire\Features\SupportTesting\Testable $component): \Livewire\Features\SupportTesting\Testable
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
            ->set('sellerType', 'private');
    }
}
