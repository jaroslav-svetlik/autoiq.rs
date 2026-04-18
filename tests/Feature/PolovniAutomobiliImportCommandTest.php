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

class PolovniAutomobiliImportCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_stores_import_record_and_creates_draft_listing_from_local_html(): void
    {
        Storage::fake('public');

        config([
            'autoiq.imports.polovni_automobili.owner_email' => 'admin@autoiq.rs',
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

        $url = 'https://www.polovniautomobili.com/auto-oglasi/26947280/audi-a4';

        $this->artisan('imports:polovni-automobili', [
            'url' => $url,
            '--html' => base_path('tests/Fixtures/polovni-automobili-listing.html'),
            '--store-draft' => true,
        ])->assertSuccessful();

        $record = ListingImport::query()->where('source_url', $url)->firstOrFail();

        $this->assertSame('imported', $record->status);
        $this->assertNotNull($record->listing_id);
        $listing = Listing::query()->with('images')->findOrFail($record->listing_id);

        $this->assertSame('Audi A4 2.0 TDI S line', $listing->title);
        $this->assertSame(ListingStatus::Draft, $listing->status);
        $this->assertTrue($listing->images->isNotEmpty());
        $this->assertTrue($listing->images->every(fn ($image) => ! str_starts_with($image->path, 'http')));
        $listing->images->each(fn ($image) => Storage::disk('public')->assertExists($image->path));
    }
}
