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

        $this->assertCount(275, $posts);
        $this->assertSame($posts->count(), $posts->pluck('slug')->unique()->count());
        $this->assertSame($posts->count(), $posts->pluck('title')->unique()->count());
        $this->assertSame(1, $posts->where('is_featured', true)->count());
        $this->assertCount(68, $posts->where('category', 'Poređenje modela'));
        $this->assertTrue($posts->contains('slug', 'skoda-citigo-ili-hyundai-i10-mali-auto-kada-jednostavnost-mora-pobediti-opremu'));
        $this->assertTrue($posts->contains('slug', 'polovni-peugeot-1007-neobican-mali-auto-koji-mora-dokazati-klizna-vrata'));
        $this->assertTrue($posts->contains('slug', 'polovni-fiat-freemont-porodicni-suv-koji-mora-dokazati-prostor-pogon-i-istoriju'));
        $this->assertTrue($posts->contains('slug', 'poklopac-rezervoara-na-polovnom-autu-kada-mali-otvor-otkriva-udarac-rdju-ili-losu-popravku'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-letonije-kada-povoljna-cena-trazi-proveru-zime-soli-i-porekla'));
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
        $this->assertTrue($posts->contains('slug', 'polovni-nissan-x-trail-porodicni-suv-koji-trazi-proveru-cvt-a-i-pogona'));
        $this->assertTrue($posts->contains('slug', 'honda-accord-ili-toyota-avensis-velika-limuzina-kada-racionalnost-vredi-vise-od-znacke'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-zamenjenim-motorom-kada-racun-vredi-vise-od-price-prodavca'));
        $this->assertTrue($posts->contains('slug', 'polovni-plug-in-hibrid-kada-punjenje-kod-kuce-odlucuje-celu-racunicu'));
        $this->assertTrue($posts->contains('slug', 'polovni-fiat-tipo-kompakt-koji-mora-opravdati-nisku-cenu-odrzavanja'));
        $this->assertTrue($posts->contains('slug', 'polovni-peugeot-308-kompakt-koji-trazi-proveru-puretech-a-i-dizela'));
        $this->assertTrue($posts->contains('slug', 'mercedes-benz-e-klasa-ili-bmw-serija-5-premium-limuzina-kada-kilometraza-odlucuje'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-vucnom-kukom-kada-koristan-dodatak-otkriva-tezak-zivot'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-holandije-kada-uredna-kilometraza-ne-znaci-mirnu-kupovinu'));
        $this->assertTrue($posts->contains('slug', 'polovni-dacia-sandero-stepway-mali-auto-koji-ne-treba-platiti-kao-suv'));
        $this->assertTrue($posts->contains('slug', 'polovni-skoda-karoq-kompaktni-suv-koji-mora-opravdati-cenu-tiguana'));
        $this->assertTrue($posts->contains('slug', 'kia-stonic-ili-hyundai-bayon-mali-crossover-za-grad-kada-budzet-ne-trpi-suv-cenu'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-zamenskim-farovima-kada-los-deo-kvari-celu-kupovinu'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-tuningom-i-cipom-kada-vise-snage-znaci-vise-rizika'));
        $this->assertTrue($posts->contains('slug', 'polovni-ford-s-max-porodicni-monovolumen-koji-trazi-proveru-automatika'));
        $this->assertTrue($posts->contains('slug', 'polovni-seat-ateca-spanski-tiguan-koji-trazi-proveru-tsi-i-dsg-a'));
        $this->assertTrue($posts->contains('slug', 'hyundai-santa-fe-ili-kia-sorento-sedam-sedista-kada-porodica-preraste-kompaktni-suv'));
        $this->assertTrue($posts->contains('slug', 'polovni-mercedes-b-klasa-praktican-premium-kompakt-koji-ne-sme-da-se-kupi-samo-zbog-znacke'));
        $this->assertTrue($posts->contains('slug', 'plivajuci-zamajac-kod-polovnjaka-tihi-deo-koji-moze-pokvariti-dobru-cenu'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-naknadno-ugradjenom-multimedijom-kada-veliki-ekran-skriva-losu-instalaciju'));
        $this->assertTrue($posts->contains('slug', 'jeep-renegade-ili-fiat-500x-isti-koreni-razlicit-rizik-za-kupca'));
        $this->assertTrue($posts->contains('slug', 'polovni-honda-hr-v-mali-crossover-koji-trazi-proveru-prostora-cvt-a-i-cene'));
        $this->assertTrue($posts->contains('slug', 'polovni-toyota-verso-porodicni-monovolumen-koji-mora-opravdati-godine'));
        $this->assertTrue($posts->contains('slug', 'auto-posle-poplave-kako-prepoznati-vlagu-koja-ne-nestaje-posle-dubinskog-pranja'));
        $this->assertTrue($posts->contains('slug', 'slaba-klima-na-polovnom-autu-kada-letnji-test-otkriva-skup-kvar'));
        $this->assertTrue($posts->contains('slug', 'citroen-c3-ili-peugeot-208-mali-gradski-auto-kada-dizajn-ne-sme-da-zameni-proveru'));
        $this->assertTrue($posts->contains('slug', 'polovni-bmw-serija-1-kompakt-koji-trazi-proveru-lanca-trapa-i-istorije'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-uklonjenim-dpf-om-kada-jeftino-resenje-postaje-skup-problem'));
        $this->assertTrue($posts->contains('slug', 'polovni-mitsubishi-asx-jednostavan-crossover-koji-ne-treba-platiti-kao-rav4'));
        $this->assertTrue($posts->contains('slug', 'ostecena-soferka-na-polovnom-autu-kada-pukotina-otkriva-veci-problem'));
        $this->assertTrue($posts->contains('slug', 'polovni-alfa-romeo-giulietta-kompakt-sa-stilom-koji-trazi-hladnu-glavu'));
        $this->assertTrue($posts->contains('slug', 'skoda-octavia-ili-volkswagen-passat-karavan-kada-prostor-nije-jedini-argument'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-zamenjenim-airbagovima-kada-enterijer-otkriva-ozbiljnu-stetu'));
        $this->assertTrue($posts->contains('slug', 'polovni-opel-mokka-mali-suv-koji-ne-sme-da-se-kupi-samo-zbog-visokog-sedenja'));
        $this->assertTrue($posts->contains('slug', 'all-season-gume-na-polovnom-autu-kada-prakticnost-sakriva-los-kompromis'));
        $this->assertTrue($posts->contains('slug', 'polovni-renault-megane-kompakt-koji-trazi-proveru-edc-a-dizela-i-elektronike'));
        $this->assertTrue($posts->contains('slug', 'seat-ibiza-ili-volkswagen-polo-mali-auto-kada-znacka-ne-sme-da-digne-cenu'));
        $this->assertTrue($posts->contains('slug', 'lazna-servisna-knjizica-kako-pecati-mogu-sakriti-losu-istoriju-polovnog-auta'));
        $this->assertTrue($posts->contains('slug', 'polovni-volvo-s60-limuzina-koja-mora-opravdati-bezbednost-automatiku-i-cenu-delova'));
        $this->assertTrue($posts->contains('slug', 'privatni-prodavac-ili-auto-plac-gde-polovan-auto-nosi-manji-rizik'));
        $this->assertTrue($posts->contains('slug', 'polovni-audi-a6-c7-premium-limuzina-koja-trazi-proveru-automatika-dizela-i-elektronike'));
        $this->assertTrue($posts->contains('slug', 'fiat-500l-ili-renault-scenic-porodicni-auto-kada-suv-nije-jedino-resenje'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-korozijom-na-podu-kada-rdja-nije-samo-estetski-problem'));
        $this->assertTrue($posts->contains('slug', 'polovni-citroen-berlingo-praktican-porodicni-van-koji-ne-sme-da-sakrije-tezak-radni-zivot'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-stranom-dokumentacijom-kada-papiri-moraju-biti-jasniji-od-obecanja'));
        $this->assertTrue($posts->contains('slug', 'polovni-volkswagen-touran-porodicni-monovolumen-koji-trazi-proveru-dizela-dsg-a-i-kliznih-sedista'));
        $this->assertTrue($posts->contains('slug', 'toyota-prius-ili-hyundai-ioniq-polovni-hibrid-kada-grad-i-potrosnja-odlucuju'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-sumnjivim-zvukom-turbine-kada-zvizduk-postaje-skup-racun'));
        $this->assertTrue($posts->contains('slug', 'polovni-opel-insignia-b-velika-limuzina-koja-trazi-proveru-dizela-i-elektronike'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-previse-vlasnika-kada-broj-u-saobracajnoj-menja-rizik-kupovine'));
        $this->assertTrue($posts->contains('slug', 'polovni-skoda-scala-kompakt-koji-trazi-proveru-tsi-a-trapa-i-opreme'));
        $this->assertTrue($posts->contains('slug', 'peugeot-5008-ili-skoda-kodiaq-sedam-sedista-kada-porodica-trazi-vise-od-gepeka'));
        $this->assertTrue($posts->contains('slug', 'cudan-miris-u-kabini-polovnog-auta-kada-nos-otkriva-vlagu-dim-ili-losu-popravku'));
        $this->assertTrue($posts->contains('slug', 'polovni-honda-civic-10-kompakt-koji-trazi-proveru-turbobenzinca-cvt-a-i-limarskog-stanja'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-lizinga-iz-inostranstva-kada-uredna-istorija-ne-govori-sve-o-koriscenju'));
        $this->assertTrue($posts->contains('slug', 'polovni-renault-kadjar-crossover-koji-trazi-proveru-dci-a-tce-a-i-elektronike'));
        $this->assertTrue($posts->contains('slug', 'opel-grandland-ili-peugeot-3008-isti-koreni-razlicita-racunica-polovnjaka'));
        $this->assertTrue($posts->contains('slug', 'ostecene-felne-na-polovnom-autu-kada-udarac-u-rupu-otkriva-skuplji-trap'));
        $this->assertTrue($posts->contains('slug', 'polovni-mercedes-c-klasa-w205-premium-limuzina-koja-trazi-proveru-dizela-automatika-i-opreme'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-neuskladjenom-opremom-i-vin-om-kada-paket-opreme-otkriva-skrivenu-pricu'));
        $this->assertTrue($posts->contains('slug', 'kia-xceed-ili-renault-arkana-crossover-kada-stil-ne-sme-da-pobedi-prakticnost'));
        $this->assertTrue($posts->contains('slug', 'polovni-toyota-camry-hybrid-velika-limuzina-koja-trazi-proveru-baterije-kocnica-i-uvoza'));
        $this->assertTrue($posts->contains('slug', 'polovni-audi-a4-b9-premium-limuzina-i-karavan-koji-traze-proveru-tdi-a-s-tronica-i-opreme'));
        $this->assertTrue($posts->contains('slug', 'curenje-ulja-na-polovnom-autu-kada-opran-motor-krije-skuplji-kvar'));
        $this->assertTrue($posts->contains('slug', 'slab-akumulator-na-polovnom-autu-kada-tesko-paljenje-otkriva-alternator-kratke-relacije-ili-elektroniku'));
        $this->assertTrue($posts->contains('slug', 'hyundai-i20-ili-nissan-micra-mali-gradski-auto-kada-budzet-ne-trpi-skupe-greske'));
        $this->assertTrue($posts->contains('slug', 'polovni-volkswagen-arteon-elegantan-fastback-koji-trazi-proveru-tdi-a-dsg-a-i-opreme'));
        $this->assertTrue($posts->contains('slug', 'polovni-mercedes-cla-kompaktni-premium-koji-mora-opravdati-motor-menjac-i-limarsko-stanje'));
        $this->assertTrue($posts->contains('slug', 'rashladna-tecnost-na-polovnom-autu-kada-antifriz-otkriva-dihtung-hladnjak-ili-curenje'));
        $this->assertTrue($posts->contains('slug', 'auto-pod-zalogom-ili-kreditom-kada-papiri-moraju-biti-cistiji-od-cene'));
        $this->assertTrue($posts->contains('slug', 'volkswagen-golf-sportsvan-ili-bmw-serija-2-active-tourer-praktican-porodicni-kompakt-kada-suv-nije-jedino-resenje'));
        $this->assertTrue($posts->contains('slug', 'polovni-renault-zoe-mali-elektricni-auto-koji-trazi-proveru-baterije-punjenja-i-vlasnistva-baterije'));
        $this->assertTrue($posts->contains('slug', 'polovni-mazda-2-mali-japanac-koji-trazi-proveru-benzinca-korozije-i-gradske-upotrebe'));
        $this->assertTrue($posts->contains('slug', 'abs-i-esp-lampice-na-polovnom-autu-kada-senzor-tocka-krije-skuplju-dijagnostiku'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-italije-kada-dobra-oprema-ne-znaci-mirnu-istoriju'));
        $this->assertTrue($posts->contains('slug', 'skoda-roomster-ili-citroen-c3-picasso-mali-porodicni-auto-kada-budzet-ne-prati-suv-zelje'));
        $this->assertTrue($posts->contains('slug', 'polovni-opel-meriva-praktican-mali-monovolumen-koji-trazi-proveru-vrata-trapa-i-benzinca'));
        $this->assertTrue($posts->contains('slug', 'polovni-fiat-panda-4x4-mali-terenac-koji-ne-sme-da-sakrije-skupu-mehaniku'));
        $this->assertTrue($posts->contains('slug', 'vibracije-pri-kocenju-na-polovnom-autu-kada-diskovi-kriju-trap-lezajeve-ili-losu-popravku'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-francuske-kada-niza-cena-trazi-proveru-servisa-limarije-i-elektronike'));
        $this->assertTrue($posts->contains('slug', 'ford-b-max-ili-kia-venga-mali-porodicni-auto-kada-vrata-i-prostor-vrede-vise-od-imidza'));
        $this->assertTrue($posts->contains('slug', 'polovni-volkswagen-up-mali-gradski-auto-koji-trazi-proveru-kvacila-trapa-i-gradske-upotrebe'));
        $this->assertTrue($posts->contains('slug', 'polovni-suzuki-sx4-s-cross-crossover-koji-trazi-proveru-benzinca-dizela-i-4x4-pogona'));
        $this->assertTrue($posts->contains('slug', 'dim-iz-auspuha-na-polovnom-autu-kada-boja-dima-otkriva-turbo-dizne-ulje-ili-rashladnu-tecnost'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-nemacke-kada-dobra-servisna-istorija-nije-dovoljna-bez-provere-kilometraze-i-opreme'));
        $this->assertTrue($posts->contains('slug', 'dacia-logan-mcv-ili-skoda-rapid-spaceback-karavan-razum-ili-kompakt-kada-budzet-trazi-prostor'));
        $this->assertTrue($posts->contains('slug', 'polovni-peugeot-207-mali-auto-koji-trazi-proveru-benzinca-elektronike-i-zadnjeg-trapa'));
        $this->assertTrue($posts->contains('slug', 'polovni-nissan-note-praktican-mali-auto-koji-ne-sme-da-sakrije-cvt-trap-i-gradsku-upotrebu'));
        $this->assertTrue($posts->contains('slug', 'nemiran-ler-na-polovnom-autu-kada-podrhtavanje-otkriva-nosace-dizne-usis-ili-struju'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-belgije-kada-uredan-oglas-trazi-proveru-kilometraze-korozije-i-jezika-dokumentacije'));
        $this->assertTrue($posts->contains('slug', 'renault-twingo-ili-smart-forfour-gradski-auto-kada-okretanje-i-parkiranje-vrede-vise-od-gepeka'));
        $this->assertTrue($posts->contains('slug', 'polovni-seat-leon-5f-kompakt-koji-trazi-proveru-tsi-a-tdi-a-dsg-a-i-trapa'));
        $this->assertTrue($posts->contains('slug', 'polovni-opel-zafira-tourer-sedam-sedista-koja-moraju-opravdati-dizel-automatiku-i-porodicni-umor'));
        $this->assertTrue($posts->contains('slug', 'letva-volana-na-polovnom-autu-kada-lupkanje-tezak-volan-i-servo-otkrivaju-skup-racun'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-poljske-kada-dobra-cena-trazi-proveru-korozije-kilometraze-i-porekla'));
        $this->assertTrue($posts->contains('slug', 'toyota-aygo-ili-citroen-c1-gradski-blizanci-kada-niska-potrosnja-nije-cela-prica'));
        $this->assertTrue($posts->contains('slug', 'polovni-ford-fiesta-mali-auto-koji-trazi-proveru-ecoboost-a-trapa-i-gradske-upotrebe'));
        $this->assertTrue($posts->contains('slug', 'polovni-citroen-c4-picasso-porodicni-monovolumen-koji-ne-sme-da-sakrije-elektroniku-i-egs'));
        $this->assertTrue($posts->contains('slug', 'check-engine-lampica-na-polovnom-autu-kada-obrisana-greska-vredi-vise-od-probne-voznje'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-danske-kada-uredan-servis-trazi-proveru-korozije-poreza-i-opreme'));
        $this->assertTrue($posts->contains('slug', 'lancia-ypsilon-ili-fiat-punto-mali-auto-kada-stil-i-servis-moraju-da-se-sloze'));
        $this->assertTrue($posts->contains('slug', 'polovni-chevrolet-cruze-limuzina-koja-trazi-proveru-delova-servisa-i-dizela'));
        $this->assertTrue($posts->contains('slug', 'polovni-dacia-lodgy-sedam-sedista-kada-niska-cena-mora-da-dokaze-porodicni-zivot'));
        $this->assertTrue($posts->contains('slug', 'auto-sa-samo-jednim-kljucem-kada-sitnica-otkriva-papire-elektroniku-ili-rizik'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-austrije-kada-uredan-servis-trazi-proveru-soli-porekla-i-cene'));
        $this->assertTrue($posts->contains('slug', 'renault-espace-ili-ford-galaxy-sedam-sedista-kada-porodica-ne-zeli-suv-cenu'));
        $this->assertTrue($posts->contains('slug', 'polovni-volkswagen-sharan-porodicni-van-koji-mora-dokazati-dsg-klizna-vrata-i-kabinu'));
        $this->assertTrue($posts->contains('slug', 'polovni-kia-picanto-mali-gradski-auto-koji-ne-sme-da-sakrije-kratke-relacije'));
        $this->assertTrue($posts->contains('slug', 'tragovi-varenja-na-sasiji-polovnog-auta-kada-pregled-mora-zaustaviti-kupovinu'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-ceske-kada-flotna-istorija-i-dobra-cena-traze-dodatnu-proveru'));
        $this->assertTrue($posts->contains('slug', 'opel-crossland-ili-citroen-c3-aircross-mali-crossover-kada-udobnost-i-prakticnost-odlucuju'));
        $this->assertTrue($posts->contains('slug', 'polovni-mazda-cx-3-mali-crossover-koji-trazi-proveru-benzinca-dizela-i-korozije'));
        $this->assertTrue($posts->contains('slug', 'polovni-mitsubishi-space-star-gradski-auto-koji-mora-opravdati-nisku-cenu'));
        $this->assertTrue($posts->contains('slug', 'datumi-na-staklima-polovnog-auta-kada-sifra-otkriva-skrivenu-popravku'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-svedske-kada-uredan-servis-trazi-proveru-korozije-i-opreme'));
        $this->assertTrue($posts->contains('slug', 'chevrolet-aveo-ili-hyundai-getz-mali-auto-kada-budzet-ne-trpi-iluzije'));
        $this->assertTrue($posts->contains('slug', 'polovni-daihatsu-terios-mali-terenac-koji-mora-dokazati-pogon-i-rdju'));
        $this->assertTrue($posts->contains('slug', 'polovni-fiat-linea-limuzina-koja-ne-sme-da-se-kupi-samo-zbog-gepeka'));
        $this->assertTrue($posts->contains('slug', 'menjac-iskace-iz-brzine-na-polovnom-autu-kada-probna-voznja-mora-prekinuti-kupovinu'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-slovenije-kada-blizina-trzista-ne-znaci-laksu-proveru'));
        $this->assertTrue($posts->contains('slug', 'opel-adam-ili-fiat-500-mali-gradski-auto-kada-stil-ne-sme-da-pojede-budzet'));
        $this->assertTrue($posts->contains('slug', 'polovni-nissan-pulsar-kompakt-koji-mora-opravdati-prostor-cvt-i-mirnu-reputaciju'));
        $this->assertTrue($posts->contains('slug', 'polovni-hyundai-ix20-mali-monovolumen-koji-trazi-proveru-prostora-trapa-i-klime'));
        $this->assertTrue($posts->contains('slug', 'krckanje-pri-punom-motanju-kada-homokineticki-zglob-otkriva-skuplji-trap-polovnjaka'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-madjarske-kada-blizina-oglasa-trazi-proveru-kilometraze-porekla-i-rdja'));
        $this->assertTrue($posts->contains('slug', 'citroen-c2-ili-ford-ka-mali-auto-za-grad-kada-cena-ne-sme-da-prevari'));
        $this->assertTrue($posts->contains('slug', 'polovni-toyota-urban-cruiser-mali-crossover-koji-mora-opravdati-retkost-i-cenu'));
        $this->assertTrue($posts->contains('slug', 'citroen-c4-cactus-ili-ford-ecosport-crossover-kada-stil-ne-sme-da-sakrije-stanje'));
        $this->assertTrue($posts->contains('slug', 'polovni-seat-exeo-limuzina-koja-mora-opravdati-audi-korene-i-godine'));
        $this->assertTrue($posts->contains('slug', 'polovni-hyundai-elantra-limuzina-koja-trazi-proveru-uvoza-trapa-i-klime'));
        $this->assertTrue($posts->contains('slug', 'tempomat-na-polovnom-autu-kada-dugme-otkriva-elektroniku-kocnice-ili-udarac'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-norveske-kada-niska-kilometraza-trazi-proveru-hladnoce-soli-i-porekla'));
        $this->assertTrue($posts->contains('slug', 'polovni-honda-fr-v-sest-sedista-koja-moraju-dokazati-porodicni-zivot'));
        $this->assertTrue($posts->contains('slug', 'grejac-zadnjeg-stakla-ne-radi-kada-sitna-linija-otkriva-veci-problem'));
        $this->assertTrue($posts->contains('slug', 'euro-5-dizel-u-srbiji-kada-niska-cena-jos-ima-smisla-a-kada-je-zamka'));
        $this->assertTrue($posts->contains('slug', 'daihatsu-sirion-ili-mitsubishi-colt-mali-japanac-kada-retkost-menja-cenu'));
        $this->assertTrue($posts->contains('slug', 'polovni-suzuki-splash-mali-auto-koji-mora-dokazati-gradsku-rutinu'));
        $this->assertTrue($posts->contains('slug', 'polovni-fiat-qubo-praktican-kutijasti-auto-koji-ne-sme-sakriti-radni-zivot'));
        $this->assertTrue($posts->contains('slug', 'rucna-kocnica-na-polovnom-autu-kada-visok-hod-otkriva-skuplji-zadnji-kraj'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-slovacke-kada-dobra-cena-trazi-proveru-porekla-i-flote'));
        $this->assertTrue($posts->contains('slug', 'ssangyong-korando-ili-renault-koleos-redji-suv-kada-cena-nije-dovoljna'));
        $this->assertTrue($posts->contains('slug', 'polovni-subaru-xv-crossover-koji-mora-dokazati-pogon-servis-i-rdju'));
        $this->assertTrue($posts->contains('slug', 'polovni-seat-alhambra-porodicni-van-koji-mora-opravdati-klizna-vrata-i-dizel'));
        $this->assertTrue($posts->contains('slug', 'parking-senzori-na-polovnom-autu-kada-pistanje-krije-branik-instalaciju-ili-modul'));
        $this->assertTrue($posts->contains('slug', 'euro-4-benzinac-u-srbiji-kada-niska-cena-jos-uvek-ima-smisla'));
        $this->assertTrue($posts->contains('slug', 'suzuki-baleno-ili-hyundai-i20-mali-auto-kada-prostor-i-cena-ne-govore-sve'));
        $this->assertTrue($posts->contains('slug', 'polovni-opel-karl-mali-auto-koji-mora-dokazati-gradsku-upotrebu'));
        $this->assertTrue($posts->contains('slug', 'polovni-peugeot-rifter-praktican-porodicni-van-koji-trazi-proveru-radnog-zivota'));
        $this->assertTrue($posts->contains('slug', 'lupanje-preko-neravnina-na-polovnom-autu-kada-trap-trazi-pregovor-ili-odustajanje'));
        $this->assertTrue($posts->contains('slug', 'euro-3-gradski-auto-kada-najjeftiniji-oglas-vise-nije-najjeftinija-kupovina'));
        $this->assertTrue($posts->contains('slug', 'suzuki-ignis-ili-opel-agila-mali-auto-kada-visina-ne-sme-da-zameni-proveru'));
        $this->assertTrue($posts->contains('slug', 'polovni-kia-rio-mali-auto-koji-trazi-proveru-kvacila-trapa-i-klime'));
        $this->assertTrue($posts->contains('slug', 'polovni-renault-fluence-limuzina-koja-mora-opravdati-nisku-cenu'));
        $this->assertTrue($posts->contains('slug', 'elektricni-podizaci-stakala-na-polovnom-autu-kada-spor-prozor-otkriva-vrata-instalaciju-ili-udarac'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-hrvatske-kada-blizina-trzista-ne-sme-da-uspava-proveru'));
        $this->assertTrue($posts->contains('slug', 'citroen-c-elysee-ili-peugeot-301-budzetska-limuzina-kada-prostor-nije-dovoljan'));
        $this->assertTrue($posts->contains('slug', 'polovni-skoda-yeti-kutijasti-suv-koji-mora-dokazati-pogon-i-rdju'));
        $this->assertTrue($posts->contains('slug', 'polovni-honda-insight-hibrid-koji-mora-opravdati-retkost-i-bateriju'));
        $this->assertTrue($posts->contains('slug', 'elektricni-retrovizori-na-polovnom-autu-kada-malo-staklo-otkriva-vrata-instalaciju-ili-udarac'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-rumunije-kada-dobra-cena-trazi-proveru-porekla-puteva-i-papira'));
        $this->assertTrue($posts->contains('slug', 'renault-modus-ili-nissan-tiida-zaboravljeni-polovnjaci-kada-cena-ne-sme-sama-da-odluci'));
        $this->assertTrue($posts->contains('slug', 'polovni-toyota-corolla-verso-porodicni-auto-koji-mora-opravdati-sedista-i-dizel'));
        $this->assertTrue($posts->contains('slug', 'polovni-chevrolet-captiva-veliki-suv-koji-mora-dokazati-pogon-delove-i-servis'));
        $this->assertTrue($posts->contains('slug', 'sigurnosni-pojasevi-na-polovnom-autu-kada-spor-povratak-otkriva-udarac-ili-vlagu'));
        $this->assertTrue($posts->contains('slug', 'uvoz-auta-iz-portugalije-kada-topla-klima-ne-znaci-automatski-mirnu-kupovinu'));

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
