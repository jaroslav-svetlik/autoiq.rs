<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('Brza odluka')
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
}
