<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Services\BlogSeoLinkService;
use Database\Seeders\TrendBlogPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrendBlogPostSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_trend_blog_post_seeder_creates_idempotent_unique_article_catalog(): void
    {
        Storage::fake('public');

        $this->seed(TrendBlogPostSeeder::class);
        $this->seed(TrendBlogPostSeeder::class);

        $posts = BlogPost::query()->get();

        $this->assertCount(105, $posts);
        $this->assertSame($posts->count(), $posts->pluck('slug')->unique()->count());
        $this->assertSame($posts->count(), $posts->pluck('title')->unique()->count());
        $this->assertSame(1, $posts->where('is_featured', true)->count());
        $this->assertCount(34, $posts->where('category', 'Poređenje modela'));
        $this->assertTrue($posts->contains('slug', 'najbolji-polovni-automobili-do-10000-evra'));
        $this->assertTrue($posts->contains('slug', 'polovni-automatik-sta-kupiti-i-sta-izbegavati'));
        $this->assertTrue($posts->contains('slug', 'polovni-hibridi-toyota-honda-hyundai-sta-proveriti'));
        $this->assertTrue($posts->contains('slug', 'porodicni-suv-polovnjaci-sta-kupiti'));
        $this->assertTrue($posts->contains('slug', 'kako-proveriti-polovan-auto-pre-kupovine'));
        $this->assertTrue($posts->where('category', 'Kupovina polovnjaka')->isNotEmpty());
        $this->assertTrue($posts->where('category', 'Troškovi i održavanje')->isNotEmpty());
        $this->assertTrue($posts->where('category', 'Analiza tržišta')->isNotEmpty());
        $this->assertTrue($posts->where('category', 'Provera vozila')->isNotEmpty());
        $this->assertTrue($posts->where('category', 'Pregovaranje')->isNotEmpty());
        $this->assertTrue($posts->contains('slug', 'golf-7-ili-audi-a3-sta-je-pametnija-kupovina-u-srbiji'));
        $this->assertTrue($posts->contains('slug', 'bmw-x3-audi-q5-ili-audi-q3-koji-premium-suv-ima-najvise-smisla'));
        $this->assertTrue($posts->contains('slug', 'volkswagen-tiguan-ili-skoda-kodiaq-koji-porodicni-suv-ima-vise-smisla'));
        $this->assertTrue($posts->contains('slug', 'toyota-corolla-hybrid-ili-hyundai-ioniq-koji-hibrid-je-mirnija-kupovina'));
        $this->assertTrue($posts->contains('slug', 'polovni-renault-clio-15-dci-mali-dizel-koji-trazi-dobru-istoriju'));
        $this->assertTrue($posts->contains('slug', 'uvezen-auto-iz-eu-sta-proveriti-pre-kapare-i-odlaska-na-pregled'));
        $this->assertTrue($posts->contains('slug', 'dpf-i-egr-u-gradu-kada-dizel-postaje-losa-racunica'));
        $this->assertTrue($posts->contains('slug', 'kilometraza-nije-dokaz-kako-citati-stanje-polovnog-automobila'));
        $this->assertTrue($posts->contains('slug', 'elektricni-polovnjak-u-srbiji-kome-ima-smisla-a-kome-jos-ne'));
        $this->assertTrue($posts->contains('slug', 'polovni-toyota-yaris-hybrid-gradski-hibrid-koji-trazi-mirnu-istoriju'));
        $this->assertTrue($posts->contains('slug', 'automatski-menjac-kod-polovnjaka-sta-proveriti-pre-probne-voznje'));
        $this->assertTrue($posts->contains('slug', 'karavan-ili-suv-za-porodicu-gde-novac-stvarno-ima-vise-smisla'));
        $this->assertTrue($posts->contains('slug', 'vin-izvestaj-i-servisna-istorija-sta-proveriti-pre-kapare'));
        $this->assertTrue($posts->contains('slug', 'pregovaranje-posle-pregleda-kako-spustiti-cenu-bez-svade'));
        $this->assertTrue($posts->contains('slug', 'ford-kuga-ili-nissan-qashqai-2022-2023-koji-suv-je-pametnija-kupovina'));
        $this->assertTrue($posts->contains('slug', 'polovni-hyundai-tucson-2021-2023-sta-proveriti-kod-hibrida-i-dizela'));
        $this->assertTrue($posts->contains('slug', 'mazda-cx-5-ili-toyota-rav4-koji-suv-je-mirnija-kupovina'));
        $this->assertTrue($posts->contains('slug', 'peugeot-2008-ili-renault-captur-mali-crossover-za-grad-i-porodicu'));
        $this->assertTrue($posts->contains('slug', 'pregled-kod-majstora-pre-kupovine-sta-traziti-da-ne-promakne'));
        $this->assertTrue($posts->contains('slug', 'gume-na-polovnom-automobilu-skriveni-trosak-koji-menja-cenu'));
        $this->assertTrue($posts->contains('slug', 'polovni-volkswagen-id3-elektricni-kompakt-za-grad-i-punjenje-kod-kuce'));
        $this->assertTrue($posts->contains('slug', 'prvi-auto-za-novog-vozaca-kako-izabrati-bez-skupih-pocetnickih-gresaka'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-plinom-kada-se-isplati-a-kada-je-rizik-veci-od-ustede'));
        $this->assertTrue($posts->contains('slug', 'sta-proveriti-kod-karoserije-zazori-lak-i-tragovi-lose-popravke'));
        $this->assertTrue($posts->contains('slug', 'renault-austral-ili-kia-sportage-noviji-porodicni-suv-bez-premium-cene'));
        $this->assertTrue($posts->contains('slug', 'auto-do-5000-evra-kako-izabrati-bez-skrivene-investicije'));
        $this->assertTrue($posts->contains('slug', 'kia-ceed-ili-hyundai-i30-kompakt-bez-nemacke-premije'));
        $this->assertTrue($posts->contains('slug', 'polovni-toyota-auris-hybrid-miran-hibrid-za-grad-ili-preskupa-reputacija'));
        $this->assertTrue($posts->contains('slug', 'kredit-kes-ili-zamena-staro-za-novo-kako-racunati-stvarnu-cenu-auta'));
        $this->assertTrue($posts->contains('slug', 'veliki-servis-posle-kupovine-sta-mora-u-budzet-pre-prvog-kilometra'));
        $this->assertTrue($posts->contains('slug', 'skoda-fabia-ili-opel-corsa-mali-auto-za-grad-bez-velikog-rizika'));
        $this->assertTrue($posts->contains('slug', 'polovni-audi-q3-kompaktni-premium-suv-koji-trazi-hladnu-glavu'));
        $this->assertTrue($posts->contains('slug', 'automobil-sa-malom-kilometrazom-kada-je-prednost-a-kada-crvena-zastavica'));
        $this->assertTrue($posts->contains('slug', 'benzinac-dizel-ili-hibrid-do-10000-evra-sta-ima-najvise-smisla'));
        $this->assertTrue($posts->contains('slug', 'kako-prodati-polovan-auto-brze-fotografije-cena-i-opis-koji-grade-poverenje'));
        $this->assertTrue($posts->contains('slug', 'mazda-3-ili-honda-civic-benzinac-za-vozaca-koji-ne-zeli-dizel-rizik'));
        $this->assertTrue($posts->contains('slug', 'polovni-bmw-x1-kompaktni-premium-suv-koji-lako-sakrije-skupe-sitnice'));
        $this->assertTrue($posts->contains('slug', 'auto-koji-je-dugo-stajao-kako-prepoznati-skriven-problem-pre-kupovine'));
        $this->assertTrue($posts->contains('slug', 'najbolji-automatik-do-8000-evra-kako-gledati-cenu-bez-skupog-kvara'));
        $this->assertTrue($posts->contains('slug', 'toyota-yaris-ili-honda-jazz-gradski-auto-koji-lakse-opravdava-cenu'));
        $this->assertTrue($posts->contains('slug', 'ford-kuga-ili-hyundai-tucson-porodicni-suv-koji-lakse-opravdava-cenu'));
        $this->assertTrue($posts->contains('slug', 'toyota-rav4-ili-kia-sportage-suv-za-porodicu-kada-trazis-mirniji-racun'));
        $this->assertTrue($posts->contains('slug', 'polovni-peugeot-3008-crossover-koji-trazi-proveru-elektronike-i-servisa'));
        $this->assertTrue($posts->contains('slug', 'sluzbeni-auto-na-oglasu-kada-je-dobra-kupovina-a-kada-samo-lepa-prica'));
        $this->assertTrue($posts->contains('slug', 'auto-do-7000-evra-za-grad-i-put-zasto-dobar-benzinac-cesto-pobeduje'));
        $this->assertTrue($posts->contains('slug', 'toyota-corolla-ili-skoda-octavia-porodicni-kompakt-kada-trziste-trazi-previse'));
        $this->assertTrue($posts->contains('slug', 'audi-a3-ili-mazda-3-kompakt-za-kupca-koji-ne-zeli-skupo-iznenadenje'));
        $this->assertTrue($posts->contains('slug', 'polovni-nissan-qashqai-kada-crossover-reputacija-stvarno-ima-smisla'));
        $this->assertTrue($posts->contains('slug', 'jedan-vlasnik-u-oglasu-kada-znaci-vise-a-kada-ne-menja-nista'));
        $this->assertTrue($posts->contains('slug', 'dizel-za-autoput-do-9000-evra-kada-racunica-stvarno-pije-vodu'));
        $this->assertTrue($posts->contains('slug', 'opel-astra-ili-kia-ceed-kompakt-za-kupca-koji-zeli-manje-iznenadenja'));
        $this->assertTrue($posts->contains('slug', 'hyundai-tucson-ili-peugeot-3008-porodicni-crossover-kada-dizajn-ne-sme-da-odluci'));
        $this->assertTrue($posts->contains('slug', 'polovni-hyundai-i30-kompakt-koji-ima-smisla-samo-uz-urednu-istoriju'));
        $this->assertTrue($posts->contains('slug', 'dilerska-garancija-u-oglasu-koliko-stvarno-vredi-kad-kupujes-polovan-auto'));
        $this->assertTrue($posts->contains('slug', 'hibrid-za-grad-do-12000-evra-kada-visa-cena-jos-uvek-ima-smisla'));
        $this->assertTrue($posts->contains('slug', 'seat-leon-ili-ford-focus-kompakt-za-vozaca-koji-zeli-vise-od-proseka'));
        $this->assertTrue($posts->contains('slug', 'volkswagen-passat-b8-ili-mazda-6-porodicna-limuzina-kada-kilometraza-odlucuje-vise-od-opreme'));
        $this->assertTrue($posts->contains('slug', 'polovni-renault-captur-mali-crossover-koji-lako-sakrije-gradski-zivot'));
        $this->assertTrue($posts->contains('slug', 'servisna-knjizica-u-oglasu-kada-je-dokaz-a-kada-samo-dobar-rekvizit'));
        $this->assertTrue($posts->contains('slug', 'benzinac-za-grad-do-6000-evra-kako-kupiti-mirniji-auto-bez-dizel-stresa'));
        $this->assertTrue($posts->contains('slug', 'audi-a4-b8-20-tdi-ili-bmw-320d-f30-detaljna-analiza-motora-menjaca-i-enterijera'));
        $this->assertTrue($posts->contains('slug', 'skoda-superb-ili-opel-insignia-velika-dizel-limuzina-kada-prostor-nije-dovoljan-argument'));
        $this->assertTrue($posts->contains('slug', 'toyota-c-hr-ili-nissan-juke-gradski-crossover-kada-stil-ne-sme-da-sakrije-racunicu'));
        $this->assertTrue($posts->contains('slug', 'polovni-volvo-xc60-porodicni-premium-suv-koji-trazi-uredan-servisni-trag'));
        $this->assertTrue($posts->contains('slug', 'rent-a-car-auto-na-oglasu-kako-ga-prepoznati-pre-nego-sto-te-zavede-oprema'));
        $this->assertTrue($posts->contains('slug', 'automatik-za-porodicu-do-15000-evra-kada-komfor-sakrije-servisni-rizik'));
        $this->assertTrue($posts->contains('slug', 'mazda-cx-30-ili-volkswagen-t-roc-kompaktni-crossover-kada-osecaj-za-volanom-menja-racunicu'));
        $this->assertTrue($posts->contains('slug', 'peugeot-508-ili-renault-talisman-velika-limuzina-kada-dizajn-ne-sme-da-vodi-glavnu-rec'));
        $this->assertTrue($posts->contains('slug', 'polovni-mercedes-gla-kompaktni-premium-suv-koji-lako-sakrije-gradsku-eksploataciju'));
        $this->assertTrue($posts->contains('slug', 'fotosopirane-slike-u-oglasu-kako-prepoznati-da-fotografije-kriju-vise-nego-sto-pokazuju'));
        $this->assertTrue($posts->contains('slug', 'karavan-do-12000-evra-kada-ima-vise-smisla-od-suv-a-i-porodicnog-automatika'));
        $this->assertTrue($posts->contains('slug', 'honda-cr-v-ili-mazda-cx-5-porodicni-benzinac-kada-miran-posed-vredi-vise-od-mode'));
        $this->assertTrue($posts->contains('slug', 'polovni-lexus-ct-200h-gradski-premium-hibrid-koji-trazi-miran-pregled-baterije'));
        $this->assertTrue($posts->contains('slug', 'oglas-bez-registarskih-tablica-kada-je-sitnica-a-kada-ozbiljan-signal-za-oprez'));
        $this->assertTrue($posts->contains('slug', 'lanac-ili-kais-kako-ta-razlika-menja-trosak-polovnog-auta-u-prve-dve-godine'));
        $this->assertTrue($posts->contains('slug', 'suv-do-13000-evra-da-li-vredi-juriti-visu-klasu-ili-kupiti-mladi-kompakt'));
        $this->assertTrue($posts->contains('slug', 'polovni-tesla-model-3-u-srbiji-kada-baterija-nije-jedino-pitanje'));
        $this->assertTrue($posts->contains('slug', 'kupovina-auta-posle-lizinga-kada-uredna-istorija-nije-cela-slika'));
        $this->assertTrue($posts->contains('slug', 'adblue-kod-polovnog-dizela-mali-rezervoar-koji-moze-napraviti-veliki-racun'));
        $this->assertTrue($posts->contains('slug', 'auto-posle-lakseg-udesa-kako-razlikovati-dobru-popravku-od-skrivene-stete'));
        $this->assertTrue($posts->contains('slug', 'polovni-citroen-c5-aircross-udoban-porodicni-suv-koji-ne-sme-da-sakrije-elektroniku'));
        $this->assertTrue($posts->contains('slug', 'polovni-subaru-forester-stalni-pogon-koji-trazi-uredan-servisni-trag'));
        $this->assertTrue($posts->contains('slug', 'kupovina-auta-bez-probne-voznje-kada-treba-odmah-odustati'));
        $this->assertTrue($posts->contains('slug', 'euro-6-dizel-iz-uvoza-kada-niska-potrosnja-ne-opravdava-emisijski-rizik'));
        $this->assertTrue($posts->contains('slug', 'hibrid-sa-velikom-kilometrazom-kada-baterija-nije-jedini-rizik'));
        $this->assertTrue($posts->contains('slug', 'polovni-mini-countryman-sarmantan-crossover-koji-mora-opravdati-premium-cenu'));
        $this->assertTrue($posts->contains('slug', 'polovni-suzuki-vitara-mali-suv-koji-ne-treba-kupiti-samo-zbog-reputacije'));
        $this->assertTrue($posts->contains('slug', 'auto-kupljen-na-aukciji-kada-niza-cena-nosi-skuplji-rizik'));
        $this->assertTrue($posts->contains('slug', 'polovni-volvo-v60-karavan-za-porodicu-koji-trazi-proveru-automatika-i-trapa'));
        $this->assertTrue($posts->contains('slug', 'hyundai-kona-ili-kia-niro-mali-hibridni-crossover-kada-grad-odlucuje'));
        $this->assertTrue($posts->contains('slug', 'polovni-ford-mondeo-velika-limuzina-koja-mora-opravdati-dizel-i-trap'));
        $this->assertTrue($posts->contains('slug', 'panoramski-krov-na-polovnom-autu-lep-detalj-koji-moze-skupo-da-prokisnjava'));
        $this->assertTrue($posts->contains('slug', 'tek-uvezen-auto-iz-svajcarske-kada-dobra-oprema-ne-garantuje-laku-kupovinu'));
        $this->assertTrue($posts->contains('slug', 'polovni-auto-za-dostavu-kako-prepoznati-tezak-gradski-zivot-pre-kupovine'));

        $posts->each(function (BlogPost $post) {
            $this->assertNotEmpty($post->cover_image_path);
            Storage::disk('public')->assertExists($post->cover_image_path);
        });
    }

    public function test_trend_blog_post_seeder_keeps_existing_generated_cover_images(): void
    {
        Storage::fake('public');

        $generatedPath = 'blog/generated/golf-7-ili-audi-a3-sta-je-pametnija-kupovina-u-srbiji.webp';
        Storage::disk('public')->put($generatedPath, 'existing generated image');

        BlogPost::factory()->create([
            'title' => 'Golf 7 ili Audi A3: šta je pametnija kupovina u Srbiji',
            'cover_image_path' => $generatedPath,
            'cover_image_alt' => 'Generated cover',
        ]);

        $this->seed(TrendBlogPostSeeder::class);

        $post = BlogPost::query()
            ->where('slug', 'golf-7-ili-audi-a3-sta-je-pametnija-kupovina-u-srbiji')
            ->firstOrFail();

        $this->assertSame($generatedPath, $post->cover_image_path);
        $this->assertSame('Generated cover', $post->cover_image_alt);
        Storage::disk('public')->assertExists($generatedPath);
    }

    public function test_seeded_trend_blog_posts_have_internal_seo_link_targets(): void
    {
        Storage::fake('public');

        $this->seed(TrendBlogPostSeeder::class);

        $seoLinks = app(BlogSeoLinkService::class);

        BlogPost::query()->get()->each(function (BlogPost $post) use ($seoLinks) {
            $this->assertTrue(
                $seoLinks->contextualBlogLinks($post)->isNotEmpty(),
                'Expected contextual blog links for '.$post->slug,
            );

            $this->assertTrue(
                $seoLinks->marketLinks($post)->isNotEmpty(),
                'Expected market CTA links for '.$post->slug,
            );
        });
    }
}
