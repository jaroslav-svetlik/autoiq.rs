<?php

namespace Tests\Feature;

use App\Livewire\Pages\Listings\FormPage;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ListingImageUploadLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_listing_cannot_have_more_than_twenty_images(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create());

        $images = collect(range(1, 21))
            ->map(fn (int $index) => UploadedFile::fake()->image("car-{$index}.jpg")->size(900))
            ->all();

        $this->fillForm(Livewire::test(FormPage::class))
            ->set('newImages', $images)
            ->call('save')
            ->assertHasErrors(['newImages']);

        $this->assertDatabaseCount('listings', 0);
    }

    public function test_each_uploaded_image_must_be_at_most_one_megabyte(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create());

        $this->fillForm(Livewire::test(FormPage::class))
            ->set('newImages', [
                UploadedFile::fake()->image('too-large.jpg')->size(1100),
            ])
            ->call('save')
            ->assertHasErrors(['newImages.0']);

        $this->assertDatabaseCount('listings', 0);
    }

    public function test_existing_listing_cannot_exceed_twenty_images_after_edit(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $listing = Listing::factory()->for($user)->create();

        foreach (range(1, 19) as $index) {
            $listing->images()->create([
                'path' => "listings/existing-{$index}.jpg",
                'sort_order' => $index,
            ]);
        }

        $this->actingAs($user);

        $this->fillForm(Livewire::test(FormPage::class, ['listing' => $listing]))
            ->set('newImages', [
                UploadedFile::fake()->image('extra-1.jpg')->size(900),
                UploadedFile::fake()->image('extra-2.jpg')->size(900),
            ])
            ->call('save')
            ->assertHasErrors(['newImages']);

        $this->assertSame(19, $listing->fresh()->images()->count());
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
