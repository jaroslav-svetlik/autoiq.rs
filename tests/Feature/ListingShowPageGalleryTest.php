<?php

namespace Tests\Feature;

use App\Enums\ListingStatus;
use App\Livewire\Pages\Listings\ShowPage;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListingShowPageGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_detail_page_renders_gallery_controls_for_multiple_images(): void
    {
        $listing = Listing::factory()->for(User::factory())->create([
            'title' => 'Audi A4 galerija test',
        ]);

        $listing->images()->createMany([
            [
                'path' => 'demo/listings/audi-a4-1.jpg',
                'alt_text' => 'Audi A4 napred',
                'sort_order' => 1,
            ],
            [
                'path' => 'demo/listings/audi-a4-2.jpg',
                'alt_text' => 'Audi A4 enterijer',
                'sort_order' => 2,
            ],
        ]);

        $this->get(route('listings.show', $listing))
            ->assertOk()
            ->assertSee('Galerija vozila')
            ->assertSee('Osnovne informacije')
            ->assertSee('Tražena cena')
            ->assertSee('Otvori fotografiju u uvećanom prikazu')
            ->assertSee('Prethodna fotografija')
            ->assertSee('Sledeća fotografija')
            ->assertSee('Uvećaj fotografiju')
            ->assertSee('Umanji fotografiju')
            ->assertSee('Vrati početnu veličinu')
            ->assertSee('Zatvori uvećani prikaz')
            ->assertSee('Prikaži fotografiju 1')
            ->assertSee('Prikaži fotografiju 2');
    }

    public function test_large_gallery_keeps_all_photos_available_in_the_lightbox(): void
    {
        $listing = Listing::factory()->create();
        $listing->images()->createMany(collect(range(1, 9))->map(fn ($index) => [
            'path' => "demo/photo-{$index}.jpg",
            'sort_order' => $index,
        ])->all());

        $this->get(route('listings.show', $listing))
            ->assertOk()
            ->assertSee('Prikaži još 4 fotografija')
            ->assertSee('Otvori fotografiju 9 u uvećanom prikazu');
    }

    public function test_saving_a_similar_listing_does_not_save_the_current_listing(): void
    {
        $viewer = User::factory()->create();
        $listing = Listing::factory()->create(['brand' => 'Audi', 'model' => 'A4']);
        $similar = Listing::factory()->create(['brand' => 'Audi', 'model' => 'A4']);

        $component = Livewire::actingAs($viewer)->test(ShowPage::class, ['listing' => $listing]);
        $component->call('toggleFavorite', $similar->id)->assertHasNoErrors();

        $this->assertTrue($viewer->hasFavorited($similar));
        $this->assertFalse($viewer->hasFavorited($listing));

        $component->call('toggleFavorite')->assertHasNoErrors();
        $component->call('toggleFavorite', $similar->id)->assertHasNoErrors();
        $this->assertTrue($viewer->hasFavorited($listing));
        $this->assertFalse($viewer->hasFavorited($similar));
    }

    public function test_unpublished_related_listing_cannot_be_favorited(): void
    {
        $this->withoutExceptionHandling();

        $listing = Listing::factory()->create();
        $draft = Listing::factory()->create(['status' => ListingStatus::Draft]);
        $viewer = User::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        Livewire::actingAs($viewer)->test(ShowPage::class, ['listing' => $listing])
            ->call('toggleFavorite', $draft->id);
    }
}
