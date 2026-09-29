<?php

namespace Tests\Feature;

use App\Livewire\Pages\Listings\FormPage;
use App\Models\Listing;
use App\Models\User;
use App\Rules\ListingDescriptionLength;
use App\Support\ListingDescription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ListingRichDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_saves_formatting_and_keeps_it_when_editing_again(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $html = '<p><strong>Odlično očuvan automobil.</strong> Redovno servisiran i spreman za vožnju.</p><ul><li><p>Nove gume</p></li><li><p><em>Servisna istorija</em></p></li></ul>';

        Livewire::test(FormPage::class)
            ->set('titleInput', 'BMW 320d sa servisnom istorijom')
            ->set('brand', 'BMW')->set('model', '320d')
            ->set('year', '2020')->set('price', '18000')->set('mileage', '120000')
            ->set('fuelType', 'diesel')->set('transmission', 'automatic')->set('city', 'Beograd')
            ->set('description', $html)
            ->call('nextStep')->assertSet('currentStep', 2)
            ->call('previousStep')->assertSet('description', $html)
            ->set('sellerName', 'Demo prodavac')->set('sellerPhones', ['+381 64 123 456'])
            ->call('save')->assertHasNoErrors();

        $listing = Listing::query()->sole();
        $this->assertSame($html, $listing->description);
        Livewire::test(FormPage::class, ['listing' => $listing])->assertSet('description', $html);
        $this->get(route('listings.show', $listing))->assertOk()->assertSeeHtml($html);
    }

    public function test_untrusted_markup_is_cleaned_on_write_and_again_on_display(): void
    {
        $malicious = '<p style="position:fixed" onclick="alert(1)"><strong>Ispravan opis</strong> automobila sa servisnom istorijom.</p>'
            .'<script>alert(1)</script><style>body{display:none}</style><iframe src="https://example.test"></iframe>'
            .'<img src=x onerror=alert(1)><svg onload=alert(1)><script>alert(1)</script></svg>'
            .'<a href="javascript:alert(1)">Tekst linka</a>';
        $safe = '<p><strong>Ispravan opis</strong> automobila sa servisnom istorijom.</p>Tekst linka';
        $listing = Listing::factory()->create(['description' => $malicious]);

        $this->assertSame($safe, $listing->fresh()->description);
        // Raw database/import content must not bypass the output sanitizer.
        DB::table('listings')->where('id', $listing->id)->update(['description' => $malicious]);
        $this->assertSame($safe, $listing->fresh()->descriptionHtml());
        $this->get(route('listings.show', $listing))->assertOk()
            ->assertSeeHtml($safe)->assertDontSeeHtml('onclick="alert(1)"')
            ->assertDontSeeHtml('<script>alert(1)</script>');
    }

    public function test_legacy_plain_text_keeps_line_breaks_and_literal_entities(): void
    {
        $plain = "Prvi vlasnik & redovno servisiran.\nCena < 20.000 €.\nDoslovno &lt;strong&gt;.";
        $listing = Listing::factory()->create(['description' => $plain]);
        $this->assertSame($plain, $listing->fresh()->description);
        $this->assertSame('<p>'.nl2br(e($plain), false).'</p>', $listing->descriptionHtml());
        $this->assertStringContainsString('Doslovno &lt;strong&gt;.', $listing->descriptionText());
    }

    public function test_removed_wrappers_do_not_double_encode_entities_when_saved_again(): void
    {
        $listing = Listing::factory()->create(['description' => '<span>Servis &amp; održavanje, cena &lt; 20.000 €.</span>']);
        $listing->description = $listing->fresh()->description;
        $listing->save();

        $this->assertSame('<p>Servis &amp; održavanje, cena &lt; 20.000 €.</p>', $listing->fresh()->descriptionHtml());
        $this->assertSame('Servis & održavanje, cena < 20.000 €.', $listing->descriptionText());
    }

    public function test_previews_search_and_metadata_use_visible_text(): void
    {
        $html = '<p>Odlično <strong>očuvan</strong> automobil &amp; servis.</p><ul><li><p>Nove gume</p></li></ul>';
        $plain = 'Odlično očuvan automobil & servis. Nove gume';
        $listing = Listing::factory()->create(['description' => $html]);

        $this->assertSame($plain, $listing->descriptionText());
        $this->assertSame($plain, $listing->toSearchableArray()['description']);
        $this->blade('<x-listing-card :listing="$listing" />', ['listing' => $listing])
            ->assertSee($plain)->assertDontSeeHtml('&lt;strong&gt;');
        $response = $this->get(route('listings.show', $listing))->assertOk();
        $response->assertSeeHtml('<meta name="description" content="'.e($plain).'">');
    }

    #[DataProvider('descriptionLengths')]
    public function test_validation_counts_visible_text_instead_of_markup(string $value, bool $valid): void
    {
        $validator = Validator::make(['description' => $value], [
            'description' => ['bail', 'required', 'string', new ListingDescriptionLength],
        ]);
        $this->assertSame($valid, $validator->passes());
    }

    public static function descriptionLengths(): array
    {
        return [
            'empty formatting' => ['<p><strong></strong><br></p>', false],
            'invisible characters' => ['<p>'.str_repeat('&nbsp;&#8203;', 35).'</p>', false],
            'markup cannot satisfy minimum' => ['<p><strong>Kratko</strong></p>', false],
            'scripts do not count' => ['<script>'.str_repeat('x', 50).'</script><p>Kratko</p>', false],
            'unicode minimum' => ['<p>'.str_repeat('č', 30).'</p>', true],
            'entities count once' => ['<p>'.str_repeat('&amp;', 30).'</p>', true],
            'maximum excludes markup' => ['<p><strong>'.str_repeat('š', 5000).'</strong></p>', true],
            'over maximum' => ['<p>'.str_repeat('a', 5001).'</p>', false],
            'oversized markup' => ['<p title="'.str_repeat('a', ListingDescription::MAX_INPUT_BYTES).'">'.str_repeat('b', 40).'</p>', false],
        ];
    }
}
