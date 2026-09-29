<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\ListingImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalCatalogListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_external_listing_attributes_the_source_and_never_uses_import_account_contacts(): void
    {
        $owner = User::factory()->create(['name' => 'Internal import account', 'phone' => '+381641234567']);
        $listing = Listing::factory()->for($owner)->create([
            'seller_name' => 'Originalni auto plac',
            'seller_phones' => ['+381649999999'],
        ]);
        $record = $this->source($listing, true);
        $listing = $listing->fresh();

        $this->assertSame($record->id, $listing->externalSource->id);
        $this->assertSame('Originalni auto plac', $listing->sellerContactName());
        $this->assertEmpty($listing->sellerContactPhones());

        $this->get(route('listings.show', $listing))
            ->assertOk()
            ->assertSee('Oglas sa sajta MojAuto')
            ->assertSee('Originalni auto plac')
            ->assertSee('Pogledaj originalni oglas')
            ->assertSee($record->source_url, false)
            ->assertDontSee('Internal import account')
            ->assertDontSee('tel:', false)
            ->assertDontSee('sms:', false)
            ->assertDontSee('Član od')
            ->assertDontSee('Proveren prodavac');

        $this->get(route('home'))->assertOk()->assertSee('Izvor: MojAuto');
    }

    public function test_an_unmarked_import_keeps_the_existing_seller_contact_flow(): void
    {
        $owner = User::factory()->create(['name' => 'Pravi prodavac', 'phone' => '+381641234567']);
        $listing = Listing::factory()->for($owner)->create();
        $this->source($listing, false);
        $listing = $listing->fresh();

        $this->assertNull($listing->externalSource);
        $this->assertSame('Pravi prodavac', $listing->sellerContactName());
        $this->get(route('listings.show', $listing))
            ->assertOk()
            ->assertSee('tel:+381641234567', false)
            ->assertDontSee('Pogledaj originalni oglas');
    }

    public function test_later_unmarked_imports_do_not_remove_external_provenance(): void
    {
        $listing = Listing::factory()->create();
        $external = $this->source($listing, true);
        $this->source($listing, false, '1234568');

        $this->assertSame($external->id, $listing->fresh()->externalSource->id);
    }

    public function test_public_source_links_only_allow_matching_known_listing_urls(): void
    {
        $record = new ListingImport(['source_name' => 'mojauto_rs', 'source_listing_id' => '1234567']);
        $valid = 'https://www.mojauto.rs/polovni-automobili/1234567_Toyota_Corolla_2021_god/';
        $record->source_url = $valid.'?tracking=anything#fragment';
        $this->assertSame($valid, $record->publicSourceUrl());

        foreach (['javascript:alert(1)', str_replace('https:', 'http:', $valid), str_replace('www.mojauto.rs', 'www.mojauto.rs.example.com', $valid), str_replace('www.mojauto.rs', 'www.mojauto.rs:443', $valid), str_replace('https://', 'https://user@', $valid), str_replace('1234567', '7654321', $valid), 'https://www.mojauto.rs/redirect'] as $url) {
            $record->source_url = $url;
            $this->assertNull($record->publicSourceUrl(), $url);
        }

        $record->source_name = 'polovni_automobili';
        $record->source_url = 'https://www.polovniautomobili.com/auto-oglasi/1234567/toyota-corolla';
        $this->assertSame($record->source_url, $record->publicSourceUrl());
        $record->source_name = 'unknown';
        $this->assertNull($record->publicSourceUrl());
    }

    private function source(Listing $listing, bool $external, string $id = '1234567'): ListingImport
    {
        return ListingImport::create([
            'listing_id' => $listing->id,
            'source_name' => 'mojauto_rs',
            'source_listing_id' => $id,
            'source_url' => 'https://www.mojauto.rs/polovni-automobili/'.$id.'_Toyota_Corolla_2021_god/',
            'status' => 'imported',
            'payload' => ['external_catalog' => $external],
            'fetched_at' => now(),
        ]);
    }
}
