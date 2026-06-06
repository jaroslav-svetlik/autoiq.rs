<?php

namespace Tests\Feature;

use App\Enums\FuelType;
use App\Enums\TransmissionType;
use App\Models\Listing;
use App\Models\User;
use App\Support\Seo\VehicleLandingPages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoIqMvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_serbian_mvp_content(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('Analiziraj tržište')
            ->assertSee('Najbolje ponude')
            ->assertSee('"@type": "WebSite"', false)
            ->assertDontSee('SearchAction', false)
            ->assertDontSee('search_term_string', false);
    }

    public function test_listing_index_keeps_filter_urls_out_of_the_index(): void
    {
        $canonical = route('listings.index');

        $this->get($canonical)
            ->assertOk()
            ->assertSee('<meta name="robots" content="index,follow">', false)
            ->assertSee('<link rel="canonical" href="'.$canonical.'">', false);

        $this->get(route('listings.index', ['sort' => 'newest']))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,follow">', false)
            ->assertSee('<link rel="canonical" href="'.$canonical.'">', false);

        $this->get(route('listings.index', ['fuel_type' => FuelType::Diesel->value, 'sort' => 'best']))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,follow">', false)
            ->assertSee('<link rel="canonical" href="'.$canonical.'">', false);
    }

    public function test_model_landing_page_is_indexable_and_self_canonical(): void
    {
        Listing::factory()->create([
            'brand' => 'Mazda',
            'model' => 'CX-5',
        ]);

        $canonical = route('listings.model', VehicleLandingPages::routeParameters('Mazda', 'CX-5'));

        $this->get($canonical)
            ->assertOk()
            ->assertSee('Polovni Mazda CX-5: oglasi, cena i provera')
            ->assertSee('<meta name="robots" content="index,follow">', false)
            ->assertSee('<link rel="canonical" href="'.$canonical.'">', false)
            ->assertSee('"@type": "CollectionPage"', false)
            ->assertSee('korozija');

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee($canonical);
    }

    public function test_listing_creation_generates_slug_score_and_price_history(): void
    {
        $user = User::factory()->create();

        $listing = Listing::query()->create([
            'user_id' => $user->id,
            'title' => 'BMW 320d test oglas',
            'brand' => 'BMW',
            'model' => '320d',
            'year' => 2016,
            'price' => 13_500,
            'mileage' => 176_000,
            'fuel_type' => FuelType::Diesel->value,
            'transmission' => TransmissionType::Automatic->value,
            'city' => 'Beograd',
            'description' => 'Detaljan test opis vozila sa dovoljno karaktera za validaciju i observer obradu.',
            'seller_type' => 'private',
        ]);

        $this->assertNotNull($listing->fresh()->slug);
        $this->assertGreaterThanOrEqual(0, $listing->fresh()->autoiq_score);
        $this->assertLessThanOrEqual(100, $listing->fresh()->autoiq_score);
        $this->assertDatabaseHas('price_histories', [
            'listing_id' => $listing->id,
        ]);
    }
}
