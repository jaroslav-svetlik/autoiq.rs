<?php

namespace Tests\Feature;

use App\Models\Listing;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoCatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_twenty_demo_listings_with_local_images(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);

        $listings = Listing::query()->with(['images', 'equipmentItems'])->get();

        $this->assertCount(20, $listings);
        $this->assertTrue($listings->every(fn (Listing $listing) => $listing->images->count() === 2));
        $this->assertTrue($listings->every(fn (Listing $listing) => $listing->equipmentItems->isNotEmpty()));

        $listings
            ->flatMap->images
            ->each(fn ($image) => Storage::disk('public')->assertExists($image->path));
    }
}
