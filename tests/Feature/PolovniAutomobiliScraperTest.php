<?php

namespace Tests\Feature;

use App\Services\Imports\PolovniAutomobiliScraper;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PolovniAutomobiliScraperTest extends TestCase
{
    public function test_parser_extracts_listing_payload_from_html_fixture(): void
    {
        $html = file_get_contents(base_path('tests/Fixtures/polovni-automobili-listing.html'));

        $result = app(PolovniAutomobiliScraper::class)->parseHtml(
            html: (string) $html,
            url: 'https://www.polovniautomobili.com/auto-oglasi/26947280/audi-a4',
            httpStatus: 200,
        );

        $this->assertSame('parsed', $result->status);
        $this->assertSame('26947280', $result->sourceListingId);
        $this->assertSame('Audi A4 2.0 TDI S line', data_get($result->payload, 'listing.title'));
        $this->assertSame('Audi', data_get($result->payload, 'listing.brand'));
        $this->assertSame('A4', data_get($result->payload, 'listing.model'));
        $this->assertSame(2017, data_get($result->payload, 'listing.year'));
        $this->assertSame(16100, data_get($result->payload, 'listing.price'));
        $this->assertSame(168000, data_get($result->payload, 'listing.mileage'));
        $this->assertSame('diesel', data_get($result->payload, 'listing.fuel_type'));
        $this->assertSame('automatic', data_get($result->payload, 'listing.transmission'));
        $this->assertSame('Beograd', data_get($result->payload, 'listing.city'));
        $this->assertSame('dealer', data_get($result->payload, 'listing.seller_type'));
    }

    public function test_scrape_detects_challenge_response(): void
    {
        config([
            'autoiq.imports.polovni_automobili.enabled' => true,
            'autoiq.imports.polovni_automobili.allow_fetch' => true,
            'autoiq.imports.polovni_automobili.respectful_delay_ms' => 0,
        ]);

        Http::fake([
            '*' => Http::response(
                '<html><head><title>Just a moment...</title></head><body>Enable JavaScript and cookies to continue</body></html>',
                403,
                ['cf-mitigated' => 'challenge'],
            ),
        ]);

        $result = app(PolovniAutomobiliScraper::class)->scrape('https://www.polovniautomobili.com/auto-oglasi/26947280/audi-a4');

        $this->assertSame('blocked', $result->status);
        $this->assertTrue($result->challengeDetected);
        $this->assertSame(403, $result->httpStatus);
    }
}
