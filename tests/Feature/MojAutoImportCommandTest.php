<?php

namespace Tests\Feature;

use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\ListingImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MojAutoImportCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_stores_import_record_and_creates_published_listing_from_local_html(): void
    {
        Storage::fake('public');

        config([
            'autoiq.imports.mojauto.owner_email' => 'admin@autoiq.rs',
        ]);

        Http::fake([
            '*' => Http::response(
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a7JYAAAAASUVORK5CYII='),
                200,
                ['Content-Type' => 'image/png'],
            ),
        ]);

        User::factory()->create([
            'email' => 'admin@autoiq.rs',
        ]);

        $url = 'https://www.mojauto.rs/polovni-automobili/3546901_Audi_A4_2003_god/?cena=low';
        $canonicalUrl = 'https://www.mojauto.rs/polovni-automobili/3546901_Audi_A4_2003_god/';

        $this->artisan('imports:mojauto', [
            'url' => $url,
            '--html' => base_path('tests/Fixtures/mojauto-listing.html'),
            '--store-draft' => true,
        ])->assertSuccessful();

        $record = ListingImport::query()->where('source_url', $canonicalUrl)->firstOrFail();

        $this->assertSame('mojauto_rs', $record->source_name);
        $this->assertSame('imported', $record->status);
        $this->assertNotNull($record->listing_id);

        $listing = Listing::query()->with(['images', 'equipmentItems'])->findOrFail($record->listing_id);

        $this->assertSame(ListingStatus::Published, $listing->status);
        $this->assertNotNull($listing->published_at);
        $this->assertSame('Audi A4 Povoljno,odlican', $listing->title);
        $this->assertCount(3, $listing->images);
        $this->assertTrue($listing->images->every(fn ($image) => ! str_starts_with($image->path, 'http')));
        $listing->images->each(fn ($image) => Storage::disk('public')->assertExists($image->path));
        $this->assertEqualsCanonicalizing(
            ['abs', 'esp', 'fog_lights', 'alloy_wheels', 'light_sensor', 'air_conditioning'],
            $listing->equipmentKeys()->all(),
        );
    }

    public function test_reimport_updates_existing_draft_instead_of_creating_duplicate_listing(): void
    {
        Storage::fake('public');

        config([
            'autoiq.imports.mojauto.owner_email' => 'admin@autoiq.rs',
        ]);

        Http::fake([
            '*' => Http::response(
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a7JYAAAAASUVORK5CYII='),
                200,
                ['Content-Type' => 'image/png'],
            ),
        ]);

        User::factory()->create([
            'email' => 'admin@autoiq.rs',
        ]);

        $url = 'https://www.mojauto.rs/polovni-automobili/3546901_Audi_A4_2003_god/?cena=low';
        $canonicalUrl = 'https://www.mojauto.rs/polovni-automobili/3546901_Audi_A4_2003_god/';

        $this->artisan('imports:mojauto', [
            'url' => $url,
            '--html' => base_path('tests/Fixtures/mojauto-listing.html'),
            '--store-draft' => true,
        ])->assertSuccessful();

        $firstListingId = ListingImport::query()->where('source_url', $canonicalUrl)->value('listing_id');

        $this->artisan('imports:mojauto', [
            'url' => $url,
            '--html' => base_path('tests/Fixtures/mojauto-listing.html'),
            '--store-draft' => true,
        ])->assertSuccessful();

        $record = ListingImport::query()->where('source_url', $canonicalUrl)->firstOrFail();

        $this->assertSame($firstListingId, $record->listing_id);
        $this->assertSame(1, Listing::query()->count());
    }

    public function test_url_variants_for_same_listing_reuse_single_import_record(): void
    {
        Storage::fake('public');

        config([
            'autoiq.imports.mojauto.owner_email' => 'admin@autoiq.rs',
        ]);

        Http::fake([
            '*' => Http::response(
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a7JYAAAAASUVORK5CYII='),
                200,
                ['Content-Type' => 'image/png'],
            ),
        ]);

        User::factory()->create([
            'email' => 'admin@autoiq.rs',
        ]);

        $variantUrl = 'https://www.mojauto.rs/polovni-automobili/3546901_Audi_A4_2003_god/?cena=low';
        $canonicalUrl = 'https://www.mojauto.rs/polovni-automobili/3546901_Audi_A4_2003_god/';

        $this->artisan('imports:mojauto', [
            'url' => $variantUrl,
            '--html' => base_path('tests/Fixtures/mojauto-listing.html'),
            '--store-draft' => true,
        ])->assertSuccessful();

        $this->artisan('imports:mojauto', [
            'url' => $canonicalUrl,
            '--html' => base_path('tests/Fixtures/mojauto-listing.html'),
            '--store-draft' => true,
        ])->assertSuccessful();

        $record = ListingImport::query()->firstOrFail();

        $this->assertSame(1, ListingImport::query()->count());
        $this->assertSame(1, Listing::query()->count());
        $this->assertSame($canonicalUrl, $record->source_url);
    }
}
