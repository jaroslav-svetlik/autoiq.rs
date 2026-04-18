<?php

namespace App\Observers;

use App\Enums\ListingStatus;
use App\Enums\SellerType;
use App\Models\Listing;
use App\Models\SavedSearch;
use App\Notifications\NewListingMatchAlert;
use App\Notifications\PriceDropAlert;
use App\Services\AutoIqScoreService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListingObserver
{
    public function __construct(
        protected AutoIqScoreService $scoreService,
    ) {
    }

    public function saving(Listing $listing): void
    {
        if ($listing->seller_type === SellerType::Dealer && ! $listing->dealer_profile_id) {
            $listing->dealer_profile_id = $listing->user?->dealerProfile?->id;
        }

        if (! $listing->slug || $listing->isDirty('title')) {
            $listing->slug = $this->uniqueSlug($listing);
        }

        if ($listing->status === ListingStatus::Published && ! $listing->published_at) {
            $listing->published_at = now();
        }

        $analysis = $this->scoreService->evaluate($listing);

        $listing->autoiq_score = $analysis['score'];
        $listing->market_average_price = $analysis['market_average_price'];
        $listing->price_deviation_percentage = $analysis['price_deviation_percentage'];
    }

    public function created(Listing $listing): void
    {
        $listing->priceHistories()->create([
            'price' => $listing->price,
            'recorded_at' => now(),
            'note' => 'Početna cena',
        ]);

        if ($listing->status === ListingStatus::Published) {
            $this->notifySavedSearches($listing);
        }
    }

    public function updated(Listing $listing): void
    {
        if ($listing->wasChanged('price')) {
            $oldPrice = (int) $listing->getOriginal('price');

            $listing->priceHistories()->create([
                'price' => $listing->price,
                'recorded_at' => now(),
                'note' => 'Promena cene',
            ]);

            $listing->forceFill(['previous_price' => $oldPrice])->saveQuietly();

            if ($listing->price < $oldPrice) {
                $listing->forceFill(['last_price_drop_at' => now()])->saveQuietly();
                $this->notifyPriceDrop($listing, $oldPrice);
            }
        }

        if (
            $listing->status === ListingStatus::Published
            && $listing->wasChanged('status')
            && $listing->getOriginal('status') !== ListingStatus::Published->value
        ) {
            $this->notifySavedSearches($listing);
        }
    }

    protected function notifyPriceDrop(Listing $listing, int $oldPrice): void
    {
        $listing->favoritedByUsers()
            ->whereKeyNot($listing->user_id)
            ->get()
            ->each(fn ($user) => $user->notify(new PriceDropAlert($listing, $oldPrice, 'favorite')));

        SavedSearch::query()
            ->with('user')
            ->where('notify_price_drops', true)
            ->get()
            ->filter(fn (SavedSearch $savedSearch) => $savedSearch->matchesListing($listing))
            ->each(function (SavedSearch $savedSearch) use ($listing, $oldPrice) {
                if ($savedSearch->user_id === $listing->user_id) {
                    return;
                }

                $savedSearch->user?->notify(new PriceDropAlert($listing, $oldPrice, 'saved_search'));
                $savedSearch->forceFill(['last_notified_at' => now()])->saveQuietly();
            });
    }

    protected function notifySavedSearches(Listing $listing): void
    {
        SavedSearch::query()
            ->with('user')
            ->where('notify_new_matches', true)
            ->get()
            ->filter(fn (SavedSearch $savedSearch) => $savedSearch->matchesListing($listing))
            ->each(function (SavedSearch $savedSearch) use ($listing) {
                if ($savedSearch->user_id === $listing->user_id) {
                    return;
                }

                $savedSearch->user?->notify(new NewListingMatchAlert($listing, $savedSearch));
                $savedSearch->forceFill(['last_notified_at' => now()])->saveQuietly();
            });
    }

    protected function uniqueSlug(Listing $listing): string
    {
        $base = Str::slug($listing->title ?: "{$listing->brand} {$listing->model} {$listing->year}");
        $slug = $base;
        $counter = 1;

        while (
            Listing::query()
                ->when($listing->exists, fn ($query) => $query->whereKeyNot($listing->getKey()))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
