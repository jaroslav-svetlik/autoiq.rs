<?php

namespace Tests\Feature;

use App\Livewire\Pages\HomePage;
use App\Models\SearchLog;
use App\Services\MarketInsightsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class HomePageLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_rerender_while_typing_without_breaking_popular_models(): void
    {
        Cache::forget(MarketInsightsService::HOME_CACHE_KEY);

        SearchLog::query()->create([
            'query' => 'Golf 7',
            'brand' => 'Volkswagen',
            'model' => 'Golf 7',
            'filters' => [
                'search' => 'Golf 7',
            ],
        ]);

        Livewire::test(HomePage::class)
            ->assertSee('Volkswagen Golf 7')
            ->set('heroSearch', 'BMW')
            ->assertSee('Volkswagen Golf 7');
    }
}
