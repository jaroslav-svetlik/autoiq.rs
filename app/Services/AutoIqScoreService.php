<?php

namespace App\Services;

use App\Enums\ListingStatus;
use App\Enums\SellerType;
use App\Models\Listing;

class AutoIqScoreService
{
    public function evaluate(Listing $listing): array
    {
        $marketAverage = (float) Listing::query()
            ->when($listing->exists, fn ($query) => $query->whereKeyNot($listing->getKey()))
            ->where('brand', $listing->brand)
            ->where('model', $listing->model)
            ->where('year', $listing->year)
            ->where('status', ListingStatus::Published)
            ->avg('price');

        if ($marketAverage <= 0) {
            $marketAverage = (float) $listing->price;
        }

        $priceRatio = $marketAverage > 0 ? $listing->price / $marketAverage : 1;

        $priceScore = match (true) {
            $priceRatio <= 0.85 => 45,
            $priceRatio <= 0.95 => 38,
            $priceRatio <= 1.05 => 28,
            $priceRatio <= 1.15 => 16,
            default => 6,
        };

        $expectedMileage = max(30_000, max(now()->year - $listing->year, 1) * 18_000);
        $mileageRatio = $listing->mileage / $expectedMileage;

        $mileageScore = match (true) {
            $mileageRatio <= 0.75 => 25,
            $mileageRatio <= 1.00 => 18,
            $mileageRatio <= 1.25 => 12,
            default => 5,
        };

        $age = max(now()->year - $listing->year, 0);

        $yearScore = match (true) {
            $age <= 3 => 20,
            $age <= 6 => 17,
            $age <= 10 => 12,
            $age <= 14 => 8,
            default => 4,
        };

        $sellerScore = $listing->seller_type === SellerType::Private ? 10 : 7;

        $score = max(0, min(100, (int) round($priceScore + $mileageScore + $yearScore + $sellerScore)));
        $deviation = $marketAverage > 0
            ? round((($listing->price - $marketAverage) / $marketAverage) * 100, 2)
            : null;

        return [
            'score' => $score,
            'label' => $this->label($score),
            'tone' => $this->tone($score),
            'market_average_price' => (int) round($marketAverage),
            'price_deviation_percentage' => $deviation,
        ];
    }

    public function label(int $score): string
    {
        return match (true) {
            $score >= 75 => 'Dobra kupovina',
            $score >= 50 => 'Realna cena',
            default => 'Precijenjeno',
        };
    }

    public function tone(int $score): string
    {
        return match (true) {
            $score >= 75 => 'emerald',
            $score >= 50 => 'amber',
            default => 'rose',
        };
    }
}
