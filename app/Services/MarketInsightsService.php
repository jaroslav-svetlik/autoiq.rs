<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\SavedSearch;
use App\Models\SearchLog;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MarketInsightsService
{
    public const HOME_CACHE_KEY = 'autoiq:home-insights:v2';

    public function home(): array
    {
        $payload = Cache::remember(self::HOME_CACHE_KEY, now()->addMinutes(10), function () {
            $popularModels = SearchLog::query()
                ->selectRaw('brand, model, COUNT(*) as total')
                ->where(function ($query) {
                    $query->whereNotNull('brand')->orWhereNotNull('model');
                })
                ->groupBy('brand', 'model')
                ->orderByDesc('total')
                ->limit(6)
                ->get();

            if ($popularModels->isEmpty()) {
                $popularModels = Listing::query()
                    ->published()
                    ->selectRaw('brand, model, COUNT(*) as total')
                    ->groupBy('brand', 'model')
                    ->orderByDesc('total')
                    ->limit(6)
                    ->get();
            }

            return [
                'popularModels' => $popularModels
                    ->map(fn ($trend) => [
                        'brand' => $trend->brand,
                        'model' => $trend->model,
                        'total' => (int) $trend->total,
                    ])
                    ->values()
                    ->all(),
                'fallingPrices' => Listing::query()
                    ->published()
                    ->whereNotNull('last_price_drop_at')
                    ->orderByDesc('last_price_drop_at')
                    ->limit(6)
                    ->pluck('id')
                    ->all(),
                'popularListings' => Listing::query()
                    ->published()
                    ->withCount('favoritedByUsers')
                    ->orderByDesc('views_count')
                    ->orderByDesc('favorited_by_users_count')
                    ->limit(6)
                    ->pluck('id')
                    ->all(),
                'bestDeals' => Listing::query()
                    ->published()
                    ->orderByDesc('autoiq_score')
                    ->orderBy('price')
                    ->limit(6)
                    ->pluck('id')
                    ->all(),
                'marketSnapshots' => Listing::query()
                    ->published()
                    ->selectRaw('brand, model, year, AVG(price) as average_price, COUNT(*) as listings_count')
                    ->groupBy('brand', 'model', 'year')
                    ->havingRaw('COUNT(*) >= 2')
                    ->orderByDesc('listings_count')
                    ->limit(6)
                    ->get()
                    ->map(fn ($snapshot) => [
                        'brand' => $snapshot->brand,
                        'model' => $snapshot->model,
                        'year' => (int) $snapshot->year,
                        'average_price' => (float) $snapshot->average_price,
                        'listings_count' => (int) $snapshot->listings_count,
                    ])
                    ->values()
                    ->all(),
            ];
        });

        return [
            'popularModels' => collect($payload['popularModels'] ?? [])
                ->map(fn (array $trend) => (object) $trend),
            'fallingPrices' => $this->hydrateListings($payload['fallingPrices'] ?? []),
            'popularListings' => $this->hydrateListings($payload['popularListings'] ?? [], withFavoriteCount: true),
            'bestDeals' => $this->hydrateListings($payload['bestDeals'] ?? []),
            'marketSnapshots' => collect($payload['marketSnapshots'] ?? [])
                ->map(fn (array $snapshot) => (object) $snapshot),
        ];
    }

    public function recordSearch(?User $user, array $filters): void
    {
        if (
            blank($filters['search'] ?? null)
            && blank($filters['brand'] ?? null)
            && blank($filters['model'] ?? null)
            && blank($filters['city'] ?? null)
        ) {
            return;
        }

        SearchLog::query()->create([
            'user_id' => $user?->getKey(),
            'query' => $filters['search'] ?? null,
            'brand' => $filters['brand'] ?? null,
            'model' => $filters['model'] ?? null,
            'filters' => $filters,
        ]);

        Cache::forget(self::HOME_CACHE_KEY);
    }

    public function trend(Listing $listing): Collection
    {
        return $listing->priceHistories()
            ->latest('recorded_at')
            ->limit(8)
            ->get()
            ->reverse()
            ->values();
    }

    protected function hydrateListings(array $ids, bool $withFavoriteCount = false): Collection
    {
        if ($ids === []) {
            return collect();
        }

        $query = Listing::query()
            ->published()
            ->with(['images', 'priceHistories', 'dealerProfile'])
            ->whereIn('id', $ids);

        if ($withFavoriteCount) {
            $query->withCount('favoritedByUsers');
        }

        $listings = $query->get()->keyBy('id');

        return collect($ids)
            ->map(fn (int $id) => $listings->get($id))
            ->filter()
            ->values();
    }
}
