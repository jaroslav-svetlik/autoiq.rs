<?php

namespace Tests\Feature;

use App\Services\Imports\MojAutoScraper;
use Tests\TestCase;

class MojAutoScraperTest extends TestCase
{
    public function test_parser_extracts_listing_payload_from_html_fixture(): void
    {
        $html = file_get_contents(base_path('tests/Fixtures/mojauto-listing.html'));

        $result = app(MojAutoScraper::class)->parseHtml(
            html: (string) $html,
            url: 'https://www.mojauto.rs/polovni-automobili/3546901_Audi_A4_2003_god/?cena=low',
            httpStatus: 200,
        );

        $this->assertSame('parsed', $result->status);
        $this->assertSame('mojauto_rs', $result->sourceName);
        $this->assertSame('3546901', $result->sourceListingId);
        $this->assertSame('Audi A4 Povoljno,odlican', data_get($result->payload, 'listing.title'));
        $this->assertSame('Audi', data_get($result->payload, 'listing.brand'));
        $this->assertSame('A4', data_get($result->payload, 'listing.model'));
        $this->assertSame(2003, data_get($result->payload, 'listing.year'));
        $this->assertSame(2400, data_get($result->payload, 'listing.price'));
        $this->assertSame(269000, data_get($result->payload, 'listing.mileage'));
        $this->assertSame('lpg', data_get($result->payload, 'listing.fuel_type'));
        $this->assertSame('manual', data_get($result->payload, 'listing.transmission'));
        $this->assertSame('Zaječar', data_get($result->payload, 'listing.city'));
        $this->assertSame('private', data_get($result->payload, 'listing.seller_type'));
        $this->assertCount(3, data_get($result->payload, 'listing.image_urls', []));
        $this->assertEqualsCanonicalizing(
            ['abs', 'esp', 'fog_lights', 'alloy_wheels', 'light_sensor', 'air_conditioning'],
            data_get($result->payload, 'listing.equipment_keys', []),
        );
    }

    public function test_parser_extracts_city_and_dealer_type_from_span_seller_info_variant(): void
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html lang="sr">
<body>
    <span class="priceReal">14.999 EUR</span>
    <ul class="basicSingleData">
        <li class="basicTitle"><strong>Audi A4</strong></li>
        <li><span>2017. godište</span></li>
        <li><span>193.269 km</span></li>
        <li><span>Dizel</span></li>
    </ul>
    <div class="contactSalesman">
        <span class="sellerInfoText">
            <h4><a title="Auto plac Auto Exclusive Slovakia s.r.o." href="/dealer">Auto Exclusive Slovakia s.r.o.</a></h4>
            Myslenicka 1<br>
            Pezinok (Slovačka)
        </span>
    </div>
    <div class="singleBox singleBoxPanel">
        <h1 class="singleBoxHeader">Tehnički podaci</h1>
        <ul class="techSpec">
            <li><span>Menjač</span><strong>automatski</strong></li>
            <li><span>Klima</span><strong>Klima</strong></li>
            <li><span>Prešao kilometara</span><strong>193269</strong></li>
        </ul>
    </div>
    <div class="singleBox singleBoxPanel">
        <h1 class="singleBoxHeader">Opis</h1>
        <div class="descWrapp"><p>Uredno vozilo bez ulaganja.</p></div>
    </div>
</body>
</html>
HTML;

        $result = app(MojAutoScraper::class)->parseHtml(
            html: $html,
            url: 'https://www.mojauto.rs/polovni-automobili/3081478_Audi_A4_2017_god/',
            httpStatus: 200,
        );

        $this->assertSame('parsed', $result->status);
        $this->assertSame('Pezinok (Slovačka)', data_get($result->payload, 'listing.city'));
        $this->assertSame('dealer', data_get($result->payload, 'listing.seller_type'));
    }
}
