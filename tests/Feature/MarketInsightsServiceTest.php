<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\SearchLog;
use App\Models\User;
use App\Services\MarketInsightsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MarketInsightsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_insights_return_fresh_listing_models_after_cache_hit(): void
    {
        Cache::forget(MarketInsightsService::HOME_CACHE_KEY);

        $user = User::factory()->create();

        Listing::factory()->for($user)->create([
            'brand' => 'Volkswagen',
            'model' => 'Golf 7',
            'price' => 10_500,
            'previous_price' => 11_400,
        ]);

        SearchLog::query()->create([
            'query' => 'Golf 7',
            'brand' => 'Volkswagen',
            'model' => 'Golf 7',
            'filters' => ['search' => 'Golf 7'],
        ]);

        $service = app(MarketInsightsService::class);

        $first = $service->home();
        $second = $service->home();

        $this->assertInstanceOf(Listing::class, $first['bestDeals']->first());
        $this->assertInstanceOf(Listing::class, $second['bestDeals']->first());
        $this->assertSame('Volkswagen', $second['popularModels']->first()->brand);
    }
}
