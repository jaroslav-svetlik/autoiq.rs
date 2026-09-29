<?php

namespace Tests\Feature;

use App\Livewire\Pages\Listings\FormPage;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ListingImageOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    public function test_new_listing_saves_uploaded_images_in_selected_order_and_uses_first_as_cover(): void
    {
        $component = $this->fillForm(Livewire::test(FormPage::class))
            ->set('newImages', $this->uploads(3));
        $keys = $component->get('imageOrder');
        $uploads = $component->get('newImages');

        $component->call('sortImage', $keys[2], 0)
            ->assertSet('imageOrder', [$keys[2], $keys[0], $keys[1]])
            ->call('save')->assertHasNoErrors();

        $listing = Listing::query()->firstOrFail();
        $this->assertSame([1, 2, 3], $listing->images->pluck('sort_order')->all());
        $this->assertSame([
            'listings/'.$uploads[2]->hashName(),
            'listings/'.$uploads[0]->hashName(),
            'listings/'.$uploads[1]->hashName(),
        ], $listing->images->pluck('path')->all());
        $this->assertSame('/storage/'.$listing->images->first()->path, $listing->primaryImageUrl());
        Storage::disk('public')->assertExists($listing->images->pluck('path')->all());
    }

    public function test_edit_can_mix_existing_and_new_photos_and_only_persists_order_on_save(): void
    {
        $listing = $this->listingWithImages();
        [$first, $second, $third] = $listing->images->all();
        $component = $this->fillForm(Livewire::test(FormPage::class, ['listing' => $listing]))
            ->set('newImages', $this->uploads(1));
        $newKey = $component->get('imageOrder')[3];
        $upload = $component->get('newImages')[0];

        $component->call('sortImage', 'existing-'.$third->id, 0)
            ->call('sortImage', $newKey, 1);

        $this->assertSame([$first->id, $second->id, $third->id], $listing->fresh()->images->modelKeys());
        $component->call('save')->assertHasNoErrors();

        $images = $listing->fresh()->images;
        $this->assertSame([$third->path, 'listings/'.$upload->hashName(), $first->path, $second->path], $images->pluck('path')->all());
        $this->assertSame([1, 2, 3, 4], $images->pluck('sort_order')->all());
        Livewire::test(FormPage::class, ['listing' => $listing->fresh()])
            ->assertSet('imageOrder', $images->map(fn ($image) => 'existing-'.$image->id)->all());
    }

    public function test_removing_and_adding_photos_keeps_surviving_upload_identities_and_order(): void
    {
        $listing = $this->listingWithImages();
        $component = Livewire::test(FormPage::class, ['listing' => $listing])
            ->set('newImages', $this->uploads(3));
        $keys = $component->get('imageOrder');
        $component->call('sortImage', $keys[5], 0)
            ->call('removeNewImage', 0)
            ->call('deleteImage', $listing->images[0]->id)
            ->assertSet('imageOrder', [$keys[5], $keys[1], $keys[2], $keys[4]]);

        $component->set('newImages', [UploadedFile::fake()->image('added-later.jpg')]);
        $newOrder = $component->get('imageOrder');
        $this->assertSame([$keys[5], $keys[1], $keys[2], $keys[4]], array_slice($newOrder, 0, 4));
        $this->assertCount(5, $newOrder);
        $component->call('removeNewImage', 0)
            ->assertSet('imageOrder', [$keys[5], $keys[1], $keys[2], $newOrder[4]]);
    }

    public function test_sorting_rejects_images_from_another_listing_and_invalid_positions(): void
    {
        $listing = $this->listingWithImages();
        $other = $this->listingWithImages();
        Livewire::test(FormPage::class, ['listing' => $listing])
            ->call('sortImage', 'existing-'.$other->images->first()->id, 0)->assertStatus(404);
        foreach ([-1, 3] as $position) {
            Livewire::test(FormPage::class, ['listing' => $listing])
                ->call('sortImage', 'existing-'.$listing->images->first()->id, $position)->assertStatus(422);
        }
        $this->assertSame([1, 2, 3], $listing->fresh()->images->pluck('sort_order')->all());
    }

    public function test_client_cannot_replace_image_order_directly(): void
    {
        $listing = $this->listingWithImages();
        $this->expectException(CannotUpdateLockedPropertyException::class);
        Livewire::test(FormPage::class, ['listing' => $listing])->set('imageOrder', ['existing-999999']);
    }

    public function test_photo_step_has_sort_handles_and_accessible_controls(): void
    {
        $listing = $this->listingWithImages();
        Livewire::test(FormPage::class, ['listing' => $listing])->set('currentStep', 3)
            ->assertSeeHtml('wire:sort="sortImage"')
            ->assertSeeHtml('wire:sort:handle')
            ->assertSee('Naslovna')
            ->assertSee('Pomeri fotografiju 2 ranije')
            ->assertSee('Ukloni fotografiju 1');

        $this->actingAs(User::factory()->create());
        Livewire::test(FormPage::class, ['listing' => $listing])->assertForbidden();
    }

    private function listingWithImages(): Listing
    {
        $listing = Listing::factory()->create(['user_id' => auth()->id()]);
        foreach (range(1, 3) as $position) {
            $listing->images()->create(['path' => "listings/{$listing->id}-{$position}.jpg", 'sort_order' => $position]);
        }

        return $listing->load('images');
    }

    private function uploads(int $count): array
    {
        return array_map(fn ($index) => UploadedFile::fake()->image("car-{$index}.jpg"), range(1, $count));
    }

    private function fillForm(Testable $component): Testable
    {
        return $component->set('titleInput', 'BMW 320d xDrive M paket, prvi vlasnik')
            ->set('brand', 'BMW')->set('model', '320d')->set('year', '2018')
            ->set('price', '15900')->set('mileage', '164000')->set('fuelType', 'diesel')
            ->set('transmission', 'automatic')->set('city', 'Beograd')
            ->set('description', 'Detaljan opis vozila sa servisnom istorijom, opremom i svim bitnim informacijama za kupca.')
            ->set('sellerType', 'private')->set('sellerName', 'Milan Petrović')
            ->set('sellerPhones', ['+381 64 123 456']);
    }
}
