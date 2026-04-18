<?php

namespace Tests\Feature;

use App\Models\DealerProfile;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetUrlModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_image_uses_relative_storage_url(): void
    {
        $listing = Listing::factory()->for(User::factory())->create();

        $image = $listing->images()->create([
            'path' => 'demo/listings/test-image.svg',
            'sort_order' => 1,
        ]);

        $this->assertSame('/storage/demo/listings/test-image.svg', $image->url());
    }

    public function test_dealer_logo_uses_relative_storage_url(): void
    {
        $dealer = DealerProfile::query()->create([
            'user_id' => User::factory()->create()->id,
            'company_name' => 'Demo Diler',
            'slug' => 'demo-diler',
            'logo_path' => 'demo/logos/demo-diler.svg',
        ]);

        $this->assertSame('/storage/demo/logos/demo-diler.svg', $dealer->logoUrl());
    }
}
