<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\SavedSearch;
use App\Models\User;
use App\Services\ListingSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingEquipmentSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_service_filters_only_listings_with_all_selected_equipment(): void
    {
        $matching = Listing::factory()->for(User::factory())->create([
            'title' => 'Audi A4 sa punom opremom',
        ]);
        $matching->syncEquipment(['navigation', 'parking_camera', 'heated_seats']);

        $partial = Listing::factory()->for(User::factory())->create([
            'title' => 'Audi A4 bez kamere',
        ]);
        $partial->syncEquipment(['navigation', 'heated_seats']);

        $service = app(ListingSearchService::class);

        $results = $service->search([
            'equipment' => ['navigation', 'parking_camera'],
            'sort' => 'newest',
        ], 12);

        $this->assertSame(
            [$matching->id],
            $results->getCollection()->pluck('id')->all(),
        );
    }

    public function test_saved_search_matches_listing_only_when_all_equipment_filters_are_present(): void
    {
        $listing = Listing::factory()->for(User::factory())->create();
        $listing->syncEquipment(['navigation', 'parking_camera', 'adaptive_cruise_control']);

        $savedSearch = SavedSearch::query()->create([
            'user_id' => User::factory()->create()->id,
            'name' => 'Test pretraga',
            'filters' => [
                'equipment' => ['navigation', 'parking_camera'],
            ],
        ]);

        $this->assertTrue($savedSearch->matchesListing($listing));

        $listing->syncEquipment(['navigation']);

        $this->assertFalse($savedSearch->fresh()->matchesListing($listing->fresh()));
    }

    public function test_listing_index_route_accepts_equipment_query_string_filters(): void
    {
        $matching = Listing::factory()->for(User::factory())->create([
            'title' => 'BMW 320d sa navigacijom i kamerom',
        ]);
        $matching->syncEquipment(['navigation', 'parking_camera']);

        $other = Listing::factory()->for(User::factory())->create([
            'title' => 'BMW 320d samo navigacija',
        ]);
        $other->syncEquipment(['navigation']);

        $this->get(route('listings.index', [
            'equipment' => ['navigation', 'parking_camera'],
        ]))
            ->assertOk()
            ->assertSee('2 odabrane stavke')
            ->assertSee('BMW 320d sa navigacijom i kamerom')
            ->assertDontSee('BMW 320d samo navigacija');
    }
}
