<?php

namespace Database\Factories;

use App\Enums\FuelType;
use App\Enums\ListingStatus;
use App\Enums\SellerType;
use App\Enums\TransmissionType;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        $catalog = collect([
            ['brand' => 'BMW', 'model' => '320d'],
            ['brand' => 'Audi', 'model' => 'A4'],
            ['brand' => 'Volkswagen', 'model' => 'Golf 7'],
            ['brand' => 'Škoda', 'model' => 'Octavia'],
            ['brand' => 'Toyota', 'model' => 'Corolla'],
        ])->random();

        $year = fake()->numberBetween(2010, 2022);

        return [
            'user_id' => User::factory(),
            'title' => "{$catalog['brand']} {$catalog['model']} {$year}",
            'brand' => $catalog['brand'],
            'model' => $catalog['model'],
            'year' => $year,
            'price' => fake()->numberBetween(4_500, 24_000),
            'mileage' => fake()->numberBetween(60_000, 260_000),
            'fuel_type' => fake()->randomElement(array_column(FuelType::cases(), 'value')),
            'transmission' => fake()->randomElement(array_column(TransmissionType::cases(), 'value')),
            'city' => fake()->randomElement(config('autoiq.cities')),
            'description' => fake()->paragraphs(2, true),
            'seller_type' => SellerType::Private,
            'status' => ListingStatus::Published,
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ];
    }
}
