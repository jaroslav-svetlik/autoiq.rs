<?php

namespace Database\Seeders;

use App\Enums\FuelType;
use App\Enums\SellerType;
use App\Enums\TransmissionType;
use App\Enums\UserRole;
use App\Models\BlogPost;
use App\Models\DealerProfile;
use App\Models\Listing;
use App\Models\SearchLog;
use App\Models\User;
use App\Services\MarketInsightsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create([
            'name' => 'AutoIQ Admin',
            'email' => 'admin@autoiq.rs',
            'password' => 'Admin12345!',
            'role' => UserRole::Admin,
            'city' => 'Beograd',
        ]);

        $dealerOne = User::factory()->create([
            'name' => 'Auto Centar Novi Sad',
            'email' => 'diler1@autoiq.rs',
            'role' => UserRole::Dealer,
            'city' => 'Novi Sad',
            'phone' => '+381 64 111 1111',
        ]);

        $dealerTwo = User::factory()->create([
            'name' => 'Premium Auto House',
            'email' => 'diler2@autoiq.rs',
            'role' => UserRole::Dealer,
            'city' => 'Beograd',
            'phone' => '+381 64 222 2222',
        ]);

        $userOne = User::factory()->create([
            'name' => 'Miloš Marković',
            'email' => 'milos@autoiq.rs',
            'city' => 'Niš',
        ]);

        $userTwo = User::factory()->create([
            'name' => 'Jelena Petrović',
            'email' => 'jelena@autoiq.rs',
            'city' => 'Kragujevac',
        ]);

        $userThree = User::factory()->unverified()->create([
            'name' => 'Nenad Jovanović',
            'email' => 'nenad@autoiq.rs',
            'city' => 'Čačak',
        ]);

        $admin->syncPlatformRole(UserRole::Admin);
        $dealerOne->syncPlatformRole(UserRole::Dealer);
        $dealerTwo->syncPlatformRole(UserRole::Dealer);
        $userOne->syncPlatformRole(UserRole::User);
        $userTwo->syncPlatformRole(UserRole::User);
        $userThree->syncPlatformRole(UserRole::User);

        $dealerProfileOne = DealerProfile::query()->create([
            'user_id' => $dealerOne->id,
            'company_name' => 'Auto Centar Novi Sad',
            'slug' => 'auto-centar-novi-sad',
            'description' => 'Specijalizovani za premium nemačke limuzine i SUV modele sa proverljivom servisnom istorijom.',
            'phone' => '+381 64 111 1111',
            'email' => $dealerOne->email,
            'website' => 'https://dealer.example.com/ns',
            'city' => 'Novi Sad',
            'address' => 'Bulevar Evrope 33',
            'logo_path' => 'https://placehold.co/160x160/0f172a/f8fafc?text=AC',
            'verified_at' => now(),
        ]);

        $dealerProfileTwo = DealerProfile::query()->create([
            'user_id' => $dealerTwo->id,
            'company_name' => 'Premium Auto House',
            'slug' => 'premium-auto-house',
            'description' => 'Dilerska ponuda porodičnih i poslovnih vozila sa detaljnim pregledom stanja i podrškom oko dokumentacije.',
            'phone' => '+381 64 222 2222',
            'email' => $dealerTwo->email,
            'website' => 'https://dealer.example.com/bg',
            'city' => 'Beograd',
            'address' => 'Zrenjaninski put 44',
            'logo_path' => 'https://placehold.co/160x160/111827/f8fafc?text=PAH',
            'verified_at' => now(),
        ]);

        $userOne->savedSearches()->create([
            'name' => 'BMW 320d do 15.000 €',
            'query' => 'BMW 320d',
            'filters' => [
                'search' => 'BMW 320d',
                'brand' => 'BMW',
                'model' => '320d',
                'max_price' => 15000,
                'min_year' => 2014,
            ],
        ]);

        $userTwo->savedSearches()->create([
            'name' => 'Golf 7 automatik Beograd',
            'query' => 'Golf 7',
            'filters' => [
                'search' => 'Golf 7',
                'brand' => 'Volkswagen',
                'model' => 'Golf 7',
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Beograd',
            ],
        ]);

        $listings = $this->seedDemoListings($this->demoCatalog(
            $dealerProfileOne,
            $dealerProfileTwo,
            $userOne,
            $userTwo,
            $userThree,
        ));

        $userOne->favoriteListings()->syncWithoutDetaching(
            $listings->whereIn('brand', ['BMW', 'Toyota'])->pluck('id')->take(3)
        );

        $userTwo->favoriteListings()->syncWithoutDetaching(
            $listings->whereIn('brand', ['Volkswagen', 'Škoda', 'Audi'])->pluck('id')->take(4)
        );

        $this->simulatePriceDrops($listings);
        $this->seedSearchLogs($userOne, $userTwo, $userThree);
        $this->seedBlogPosts($this->blogCatalog());

        Cache::forget(MarketInsightsService::HOME_CACHE_KEY);
    }

    protected function demoCatalog(
        DealerProfile $dealerProfileOne,
        DealerProfile $dealerProfileTwo,
        User $userOne,
        User $userTwo,
        User $userThree,
    ): array {
        $equipment = $this->equipmentPresets();

        return [
            [
                'user_id' => $dealerProfileOne->user_id,
                'dealer_profile_id' => $dealerProfileOne->id,
                'title' => 'BMW 320d M paket, servisna knjiga',
                'brand' => 'BMW',
                'model' => '320d',
                'year' => 2016,
                'price' => 13_900,
                'mileage' => 182_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Novi Sad',
                'description' => 'Uredno održavan primerak, urađen veliki servis, dva seta guma i kompletna servisna istorija.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(19),
                'equipment' => $equipment['premium_diesel_auto'],
            ],
            [
                'user_id' => $userThree->id,
                'title' => 'BMW 320d EfficientDynamics, proverena kilometraža',
                'brand' => 'BMW',
                'model' => '320d',
                'year' => 2016,
                'price' => 12_600,
                'mileage' => 209_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Čačak',
                'description' => 'Redovno održavan, mali potrošač i odličan na otvorenom putu uz dokumentovanu servisnu istoriju.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(16),
                'equipment' => $equipment['business_diesel_manual'],
            ],
            [
                'user_id' => $dealerProfileTwo->user_id,
                'dealer_profile_id' => $dealerProfileTwo->id,
                'title' => 'Audi A4 2.0 TDI S line',
                'brand' => 'Audi',
                'model' => 'A4',
                'year' => 2017,
                'price' => 16_800,
                'mileage' => 168_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Beograd',
                'description' => 'S line oprema, navigacija, virtual cockpit i odličan odnos stanja i cene.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(14),
                'equipment' => $equipment['premium_sport_auto'],
            ],
            [
                'user_id' => $userTwo->id,
                'title' => 'Audi A4 Avant 2.0 TDI, panorama i LED',
                'brand' => 'Audi',
                'model' => 'A4',
                'year' => 2017,
                'price' => 15_300,
                'mileage' => 191_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Kraljevo',
                'description' => 'Karavan sa dosta prostora, matrix svetla i urednom servisnom dokumentacijom.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(12),
                'equipment' => $equipment['touring_manual'],
            ],
            [
                'user_id' => $userOne->id,
                'title' => 'Volkswagen Golf 7 1.6 TDI DSG',
                'brand' => 'Volkswagen',
                'model' => 'Golf 7',
                'year' => 2016,
                'price' => 10_400,
                'mileage' => 204_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Beograd',
                'description' => 'Domaći auto, drugi vlasnik, zamajac i menjač servisirani prošle godine.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(11),
                'equipment' => $equipment['compact_auto'],
            ],
            [
                'user_id' => $dealerProfileTwo->user_id,
                'dealer_profile_id' => $dealerProfileTwo->id,
                'title' => 'Volkswagen Golf 7 GTD, fabrička navigacija',
                'brand' => 'Volkswagen',
                'model' => 'Golf 7',
                'year' => 2016,
                'price' => 15_200,
                'mileage' => 177_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Beograd',
                'description' => 'Sportski paket, proverena kilometraža i odlične performanse za svakodnevnu vožnju.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(9),
                'equipment' => $equipment['hot_hatch'],
            ],
            [
                'user_id' => $userTwo->id,
                'title' => 'Škoda Octavia 1.8 TSI Style',
                'brand' => 'Škoda',
                'model' => 'Octavia',
                'year' => 2018,
                'price' => 14_500,
                'mileage' => 121_000,
                'fuel_type' => FuelType::Petrol->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Kragujevac',
                'description' => 'Porodični automobil sa bogatom opremom, pregledan i bez ulaganja.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(10),
                'equipment' => $equipment['family_manual'],
            ],
            [
                'user_id' => $dealerProfileOne->user_id,
                'dealer_profile_id' => $dealerProfileOne->id,
                'title' => 'Škoda Octavia 2.0 TDI Ambition DSG',
                'brand' => 'Škoda',
                'model' => 'Octavia',
                'year' => 2018,
                'price' => 15_800,
                'mileage' => 139_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Novi Sad',
                'description' => 'Odlična za flotnu i porodičnu vožnju, pregledana i sa urednom servisnom evidencijom.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(8),
                'equipment' => $equipment['family_auto'],
            ],
            [
                'user_id' => $dealerProfileOne->user_id,
                'dealer_profile_id' => $dealerProfileOne->id,
                'title' => 'Toyota Corolla Hybrid Luna',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
                'price' => 19_900,
                'mileage' => 86_000,
                'fuel_type' => FuelType::Hybrid->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Novi Sad',
                'description' => 'Hibrid bez ulaganja, jedan vlasnik, kompletna istorija održavanja i odlična potrošnja.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(7),
                'equipment' => $equipment['modern_hybrid'],
            ],
            [
                'user_id' => $userOne->id,
                'title' => 'Toyota Corolla 1.8 Hybrid Style',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
                'price' => 18_700,
                'mileage' => 98_000,
                'fuel_type' => FuelType::Hybrid->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Niš',
                'description' => 'Tiha gradska vožnja, adaptivni tempomat i vrlo umereni troškovi održavanja.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(6),
                'equipment' => $equipment['modern_hybrid'],
            ],
            [
                'user_id' => $dealerProfileTwo->user_id,
                'dealer_profile_id' => $dealerProfileTwo->id,
                'title' => 'Mercedes-Benz C 220 d Avantgarde',
                'brand' => 'Mercedes-Benz',
                'model' => 'C 220 d',
                'year' => 2017,
                'price' => 20_900,
                'mileage' => 154_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Beograd',
                'description' => 'Elegantna limuzina sa bogatom opremom, kamerom i urednom istorijom održavanja.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(5),
                'equipment' => $equipment['premium_luxury'],
            ],
            [
                'user_id' => $userOne->id,
                'title' => 'Renault Megane 1.5 dCi Intens',
                'brand' => 'Renault',
                'model' => 'Megane',
                'year' => 2019,
                'price' => 12_100,
                'mileage' => 144_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Niš',
                'description' => 'Dobro očuvan hatchback sa LED svetlima, navigacijom i urednim enterijerom.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(13),
                'equipment' => $equipment['compact_manual'],
            ],
            [
                'user_id' => $dealerProfileOne->user_id,
                'dealer_profile_id' => $dealerProfileOne->id,
                'title' => 'Peugeot 3008 1.6 BlueHDi Allure',
                'brand' => 'Peugeot',
                'model' => '3008',
                'year' => 2018,
                'price' => 17_600,
                'mileage' => 136_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Novi Sad',
                'description' => 'Popularan SUV sa i-Cockpit enterijerom, adaptivnim tempomatom i dobrom preglednošću.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(15),
                'equipment' => $equipment['family_suv_auto'],
            ],
            [
                'user_id' => $userThree->id,
                'title' => 'Opel Astra K 1.6 CDTI Innovation',
                'brand' => 'Opel',
                'model' => 'Astra',
                'year' => 2017,
                'price' => 10_800,
                'mileage' => 172_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Čačak',
                'description' => 'Pouzdan i ekonomičan kompakt sa dobrim paketom opreme i povoljnom registracijom.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(4),
                'equipment' => $equipment['compact_manual'],
            ],
            [
                'user_id' => $dealerProfileTwo->user_id,
                'dealer_profile_id' => $dealerProfileTwo->id,
                'title' => 'Hyundai Tucson 1.6 GDi Comfort',
                'brand' => 'Hyundai',
                'model' => 'Tucson',
                'year' => 2019,
                'price' => 18_400,
                'mileage' => 118_000,
                'fuel_type' => FuelType::Petrol->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Beograd',
                'description' => 'Tražen porodični SUV sa visokom pozicijom sedenja i urednim stanjem enterijera.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(18),
                'equipment' => $equipment['family_suv_manual'],
            ],
            [
                'user_id' => $userTwo->id,
                'title' => 'Kia Ceed SW 1.6 CRDi EX',
                'brand' => 'Kia',
                'model' => 'Ceed SW',
                'year' => 2018,
                'price' => 12_900,
                'mileage' => 147_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Kragujevac',
                'description' => 'Praktičan karavan sa dosta prostora u gepeku i odličan izbor za porodična putovanja.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(17),
                'equipment' => $equipment['touring_manual'],
            ],
            [
                'user_id' => $userOne->id,
                'title' => 'Mazda 3 Skyactiv-G Plus',
                'brand' => 'Mazda',
                'model' => '3',
                'year' => 2020,
                'price' => 17_300,
                'mileage' => 92_000,
                'fuel_type' => FuelType::Petrol->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Niš',
                'description' => 'Veoma lepo očuvan primerak sa head-up ekranom, kamerom i odličnom završnom obradom.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(3),
                'equipment' => $equipment['modern_petrol_auto'],
            ],
            [
                'user_id' => $dealerProfileOne->user_id,
                'dealer_profile_id' => $dealerProfileOne->id,
                'title' => 'Nissan Qashqai 1.5 dCi Tekna',
                'brand' => 'Nissan',
                'model' => 'Qashqai',
                'year' => 2018,
                'price' => 16_400,
                'mileage' => 149_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Novi Sad',
                'description' => 'Popularan crossover sa panoramom, kamerama i pouzdanim dCi motorom.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(20),
                'equipment' => $equipment['family_suv_manual'],
            ],
            [
                'user_id' => $userThree->id,
                'title' => 'Ford Focus 1.5 EcoBlue Titanium',
                'brand' => 'Ford',
                'model' => 'Focus',
                'year' => 2019,
                'price' => 13_200,
                'mileage' => 133_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Manual->value,
                'city' => 'Čačak',
                'description' => 'Precizan u vožnji, dobro izolovan i sa modernim infotainment sistemom.',
                'seller_type' => SellerType::Private->value,
                'published_at' => now()->subDays(2),
                'equipment' => $equipment['compact_manual'],
            ],
            [
                'user_id' => $dealerProfileTwo->user_id,
                'dealer_profile_id' => $dealerProfileTwo->id,
                'title' => 'BMW X1 sDrive18d xLine',
                'brand' => 'BMW',
                'model' => 'X1',
                'year' => 2018,
                'price' => 22_600,
                'mileage' => 127_000,
                'fuel_type' => FuelType::Diesel->value,
                'transmission' => TransmissionType::Automatic->value,
                'city' => 'Beograd',
                'description' => 'Kompaktan premium SUV sa xLine opremom, velikim servisom i urednim trapom.',
                'seller_type' => SellerType::Dealer->value,
                'published_at' => now()->subDays(1),
                'equipment' => $equipment['premium_suv_auto'],
            ],
        ];
    }

    protected function seedDemoListings(array $catalog): Collection
    {
        Storage::disk('public')->deleteDirectory('demo/listings');
        Storage::disk('public')->makeDirectory('demo/listings');

        return collect($catalog)->values()->map(function (array $item, int $index) {
            $equipmentKeys = $item['equipment'] ?? [];
            unset($item['equipment']);

            $listing = Listing::query()->create($item);
            $listing->syncEquipment($equipmentKeys);
            $listing = $listing->fresh();

            $this->seedListingGallery($listing, $index);
            $this->seedPriceHistory($listing, $index);

            return $listing->fresh(['images', 'priceHistories', 'equipmentItems']);
        });
    }

    protected function blogCatalog(): array
    {
        return [
            [
                'title' => 'Kako da proceniš da li je oglas realno postavljen u Srbiji',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Tri brza koraka za proveru da li je tražena cena u skladu sa tržištem, kilometražom i profilom prodavca.',
                'content' => <<<'TEXT'
Kada oglas deluje primamljivo, prvi korak nije poziv prodavcu nego poređenje sa sličnim vozilima. Najviše znači da uporediš isti model, isto godište i približnu kilometražu, jer prosečna cena bez tog konteksta lako zavara. Dva automobila mogu imati isti naziv modela, a potpuno drugačiji paket opreme, istoriju održavanja i tržišnu potražnju.

Zatim pogledaj koliko dugo oglas stoji aktivan i da li je cena već korigovana. Ako vozilo nedeljama nema promenu, a pritom je iznad proseka, vrlo često postoji prostor za pregovor ili signal da tržište ne prihvata traženu cenu. Suprotno tome, oglas koji brzo nestane sa tržišta obično je bio dobro pozicioniran.

Treći sloj je profil prodavca. Kod dilera je često viša početna cena jer u nju ulaze priprema vozila, finansiranje, zamena staro za novo ili garancija. Kod privatnog lica cena može biti niža, ali to ne znači automatski i bolju kupovinu ako servisna istorija nije proverljiva ili je opis štur.

Najzdraviji pristup je da cenu uvek gledaš kao kombinaciju tržišnog proseka, stanja vozila i poverenja u prodavca. Tek kada se ta tri signala poklope, ima smisla da izdvojiš vreme za detaljan pregled automobila uživo.
TEXT,
                'highlights' => [
                    'Poredi isti model, godište i sličnu kilometražu.',
                    'Posmatraj koliko dugo je oglas aktivan i da li je cena menjana.',
                    'Uvek odvoj cenu od poverenja koje uliva sam prodavac.',
                ],
                'tags' => ['cene', 'analiza tržišta', 'kupovina'],
                'is_featured' => true,
                'published_at' => now()->subDays(6),
                'palette' => ['#0f172a', '#0ea5e9', '#f8fafc'],
            ],
            [
                'title' => 'Diler ili privatno lice: gde je manji rizik pri kupovini polovnjaka',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Razlika nije samo u ceni. Važno je šta dobijaš kroz dokumentaciju, mogućnost reklamacije i kvalitet pripreme vozila.',
                'content' => <<<'TEXT'
Kupovina od dilera najčešće donosi više strukture. Oglas je obično detaljniji, vozilo je oprano i pripremljeno za pregled, a kupac lakše dobija račun, ugovor i osnovnu logistiku oko prenosa vlasništva. To ne znači da je svaki diler automatski sigurniji izbor, ali znači da je proces često uredniji.

Privatno lice može ponuditi bolju cenu zato što nema iste operativne troškove. Kada prodavac poznaje istoriju svog automobila, čuva račune i otvoreno govori o ulaganjima, takav oglas može biti odlična prilika. Problem nastaje kada opis skriva bitne detalje, kilometraža nema potvrdu ili je priča o održavanju nejasna.

Kod dilera treba proveriti šta tačno znači svaka pogodnost koja se navodi u oglasu. Garancija ponekad pokriva samo određene sklopove, a ne kompletno vozilo. Kod privatnog lica treba proveriti da li je vozilo zaista dugo u vlasništvu prodavca ili je reč o preprodaji bez jasnog porekla.

Najmanji rizik nije vezan za etiketu prodavca nego za količinu proverljivih informacija. Ako uz cenu dobijaš servisnu istoriju, tačne fotografije, jasne odgovore i doslednu dokumentaciju, tek tada oglas zaista zaslužuje pažnju.
TEXT,
                'highlights' => [
                    'Diler obično nudi uredniji proces i više papirologije.',
                    'Privatno lice može biti povoljnije kada je istorija vozila jasna.',
                    'Najvažnije je šta možeš da proveriš, ne ko prodaje auto.',
                ],
                'tags' => ['dileri', 'privatna prodaja', 'rizik'],
                'is_featured' => false,
                'published_at' => now()->subDays(4),
                'palette' => ['#111827', '#f59e0b', '#fde68a'],
            ],
            [
                'title' => 'Dizel, benzin ili hibrid: šta danas ima najviše smisla',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Prava računica zavisi od godišnje kilometraže, relacija koje voziš i toga koliko ti znači niži rizik od većih servisa.',
                'content' => <<<'TEXT'
Ako većinu vožnje praviš u gradu i prelaziš manju godišnju kilometražu, benzinac i hibrid danas često imaju više smisla od dizela. Hibrid je posebno dobar za gradski ritam kada često krećeš, staješ i tražiš što nižu potrošnju bez komplikovanog punjenja kao kod električnog vozila.

Dizel i dalje ima svoje mesto kada voziš otvoren put, praviš duže relacije i prelaziš mnogo kilometara tokom godine. Tada niža potrošnja na autoputu i među-gradskim rutama može opravdati potencijalno skuplje održavanje. Problem nastaje kada dizel kupuje vozač koji ga koristi uglavnom na kratkim gradskim relacijama.

Kod polovnih automobila treba gledati i rizik sistema koji dolaze uz motor. DPF, EGR, turbina i složeniji automatski menjači mogu promeniti računicu ako je vozilo zanemarivano. Zato je važno da tip goriva ne biraš samo po potrošnji nego i po dokazima o održavanju.

Najbolja odluka je ona koja prati tvoju realnu svakodnevicu. Ako automobil ne odgovara načinu na koji ga koristiš, ni dobra cena neće dugo izgledati kao dobra kupovina.
TEXT,
                'highlights' => [
                    'Za gradske relacije benzin i hibrid često imaju najviše smisla.',
                    'Dizel je i dalje logičan za vozače sa velikom godišnjom kilometražom.',
                    'Tip motora uvek proveravaj zajedno sa istorijom održavanja.',
                ],
                'tags' => ['dizel', 'benzin', 'hibrid'],
                'is_featured' => false,
                'published_at' => now()->subDays(2),
                'palette' => ['#172033', '#10b981', '#99f6e4'],
            ],
            [
                'title' => 'Kako pregovarati kada oglas stoji dugo i cena polako pada',
                'category' => 'Pregovaranje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Istorija korekcija cene često otkriva koliko je prodavac blizu realnog nivoa i kada je pravi trenutak za ponudu.',
                'content' => <<<'TEXT'
Oglas koji dugo stoji aktivan ne znači automatski da sa vozilom nešto nije u redu. Često znači da je početna cena postavljena optimistično i da tržište polako vraća prodavca na realan nivo. Upravo zato je istorija cene jedna od najvažnijih informacija kada razmišljaš o pregovorima.

Ako vidiš da je cena već spuštana više puta, prodavac je verovatno svestan da postoji otpor kupaca. Tada najviše vredi miran nastup sa jasnim argumentima: tržišni prosek, uporedivi oglasi i konkretni troškovi koje vidiš na vozilu. Agresivan nastup retko pomaže, dok precizna argumentacija često otvara prostor.

Najbolji trenutak za ponudu je kada oglas posle korekcije i dalje ostaje malo iznad proseka. Tada prodavac obično želi da zadrži utisak da nije drastično popustio, ali je već spremniji da razgovara. Ako je cena već pala ispod proseka, pregovor treba da bude oprezniji jer možda gubiš dobru priliku.

Pregovaranje ne treba da bude igra pogađanja. Kada ponuda ima jasnu logiku i zasniva se na podacima, mnogo je veća šansa da se razgovor završi korektno i bez nepotrebnog natezanja.
TEXT,
                'highlights' => [
                    'Dug život oglasa često znači da tržište ne prihvata početnu cenu.',
                    'Najbolje prolaze mirne ponude sa konkretnim tržišnim argumentima.',
                    'Pad ispod proseka može značiti da je prilika već vrlo dobra.',
                ],
                'tags' => ['pregovaranje', 'pad cene', 'oglasi'],
                'is_featured' => false,
                'published_at' => now()->subDay(),
                'palette' => ['#1f2937', '#fb7185', '#fecdd3'],
            ],
        ];
    }

    protected function seedBlogPosts(array $catalog): Collection
    {
        Storage::disk('public')->deleteDirectory('demo/blog');
        Storage::disk('public')->makeDirectory('demo/blog');

        return collect($catalog)->values()->map(function (array $item, int $index) {
            $palette = $item['palette'] ?? [
                '#0f172a',
                '#38bdf8',
                '#f8fafc',
            ];

            unset($item['palette']);

            $blogPost = BlogPost::query()->create($item)->fresh();
            $path = 'demo/blog/'.$blogPost->slug.'-cover.svg';

            Storage::disk('public')->put($path, $this->demoBlogSvg($blogPost, $palette, $index));

            $blogPost->forceFill([
                'cover_image_path' => $path,
                'cover_image_alt' => $blogPost->title,
            ])->saveQuietly();

            return $blogPost->fresh();
        });
    }

    protected function equipmentPresets(): array
    {
        return [
            'premium_diesel_auto' => [
                'dual_zone_climate',
                'heated_seats',
                'leather_seats',
                'keyless_entry',
                'navigation',
                'bluetooth',
                'parking_sensors',
                'parking_camera',
                'cruise_control',
                'led_headlights',
                'alloy_wheels',
                'rain_sensor',
                'light_sensor',
            ],
            'business_diesel_manual' => [
                'air_conditioning',
                'cruise_control',
                'parking_sensors',
                'navigation',
                'bluetooth',
                'usb',
                'abs',
                'esp',
                'airbags',
                'alloy_wheels',
                'fog_lights',
                'rain_sensor',
                'start_stop',
            ],
            'premium_sport_auto' => [
                'dual_zone_climate',
                'heated_seats',
                'leather_seats',
                'keyless_entry',
                'navigation',
                'bluetooth',
                'digital_cockpit',
                'parking_sensors',
                'parking_camera',
                'adaptive_cruise_control',
                'lane_assist',
                'blind_spot_monitor',
                'led_headlights',
                'alloy_wheels',
                'head_up_display',
            ],
            'touring_manual' => [
                'dual_zone_climate',
                'cruise_control',
                'parking_sensors',
                'navigation',
                'bluetooth',
                'usb',
                'airbags',
                'esp',
                'panoramic_roof',
                'led_headlights',
                'alloy_wheels',
                'roof_rails',
                'rain_sensor',
            ],
            'compact_auto' => [
                'air_conditioning',
                'parking_sensors',
                'cruise_control',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'usb',
                'abs',
                'esp',
                'airbags',
                'led_headlights',
                'alloy_wheels',
                'rain_sensor',
                'start_stop',
            ],
            'hot_hatch' => [
                'dual_zone_climate',
                'heated_seats',
                'parking_sensors',
                'navigation',
                'bluetooth',
                'digital_cockpit',
                'apple_carplay',
                'android_auto',
                'abs',
                'esp',
                'airbags',
                'xenon_headlights',
                'alloy_wheels',
                'rain_sensor',
                'light_sensor',
            ],
            'family_manual' => [
                'dual_zone_climate',
                'cruise_control',
                'parking_sensors',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'usb',
                'abs',
                'esp',
                'airbags',
                'alloy_wheels',
                'rain_sensor',
                'light_sensor',
                'start_stop',
            ],
            'family_auto' => [
                'dual_zone_climate',
                'heated_seats',
                'cruise_control',
                'parking_sensors',
                'parking_camera',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'abs',
                'esp',
                'airbags',
                'led_headlights',
                'alloy_wheels',
                'rain_sensor',
                'light_sensor',
                'start_stop',
            ],
            'modern_hybrid' => [
                'dual_zone_climate',
                'keyless_entry',
                'adaptive_cruise_control',
                'parking_sensors',
                'parking_camera',
                'lane_assist',
                'front_collision_warning',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'wireless_charging',
                'led_headlights',
                'alloy_wheels',
                'light_sensor',
            ],
            'premium_luxury' => [
                'dual_zone_climate',
                'heated_seats',
                'electric_seats',
                'leather_seats',
                'keyless_entry',
                'adaptive_cruise_control',
                'parking_sensors',
                'parking_camera',
                'blind_spot_monitor',
                'lane_assist',
                'front_collision_warning',
                'navigation',
                'bluetooth',
                'premium_sound',
                'digital_cockpit',
                'led_headlights',
                'alloy_wheels',
                'electric_tailgate',
            ],
            'compact_manual' => [
                'air_conditioning',
                'cruise_control',
                'parking_sensors',
                'navigation',
                'bluetooth',
                'usb',
                'abs',
                'esp',
                'airbags',
                'led_headlights',
                'fog_lights',
                'alloy_wheels',
                'rain_sensor',
                'light_sensor',
                'start_stop',
            ],
            'family_suv_auto' => [
                'dual_zone_climate',
                'heated_seats',
                'keyless_entry',
                'adaptive_cruise_control',
                'parking_sensors',
                'parking_camera',
                'blind_spot_monitor',
                'lane_assist',
                'front_collision_warning',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'panoramic_roof',
                'led_headlights',
                'alloy_wheels',
                'roof_rails',
                'electric_tailgate',
            ],
            'family_suv_manual' => [
                'dual_zone_climate',
                'cruise_control',
                'parking_sensors',
                'parking_camera',
                'hill_assist',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'abs',
                'esp',
                'airbags',
                'panoramic_roof',
                'led_headlights',
                'alloy_wheels',
                'roof_rails',
            ],
            'modern_petrol_auto' => [
                'dual_zone_climate',
                'heated_seats',
                'keyless_entry',
                'adaptive_cruise_control',
                'parking_sensors',
                'parking_camera',
                'lane_assist',
                'blind_spot_monitor',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'head_up_display',
                'led_headlights',
                'alloy_wheels',
                'rain_sensor',
                'light_sensor',
            ],
            'premium_suv_auto' => [
                'dual_zone_climate',
                'heated_seats',
                'electric_seats',
                'leather_seats',
                'keyless_entry',
                'adaptive_cruise_control',
                'parking_sensors',
                'parking_camera',
                'blind_spot_monitor',
                'lane_assist',
                'navigation',
                'bluetooth',
                'apple_carplay',
                'android_auto',
                'digital_cockpit',
                'led_headlights',
                'alloy_wheels',
                'roof_rails',
                'electric_tailgate',
            ],
        ];
    }

    protected function seedListingGallery(Listing $listing, int $index): void
    {
        $palettes = [
            ['#0f172a', '#1d4ed8', '#38bdf8'],
            ['#111827', '#c2410c', '#f59e0b'],
            ['#172033', '#0f766e', '#2dd4bf'],
            ['#140f2d', '#7c3aed', '#a78bfa'],
            ['#1f2937', '#be123c', '#fb7185'],
            ['#111827', '#065f46', '#34d399'],
        ];

        $views = ['Studio', 'Road'];

        foreach ($views as $variant => $view) {
            $palette = $palettes[($index + $variant) % count($palettes)];
            $path = 'demo/listings/'.$listing->slug.'-'.($variant + 1).'.svg';

            Storage::disk('public')->put($path, $this->demoListingSvg($listing, $palette, $view));

            $listing->images()->create([
                'path' => $path,
                'alt_text' => $listing->title.' - '.$view,
                'sort_order' => $variant + 1,
            ]);
        }
    }

    protected function seedPriceHistory(Listing $listing, int $index): void
    {
        $firstLift = 1200 - (($index % 4) * 150);
        $secondLift = 550 - (($index % 3) * 75);

        $listing->priceHistories()->create([
            'price' => $listing->price + $firstLift,
            'recorded_at' => now()->subDays(26 - ($index % 6)),
            'note' => 'Istorijska cena',
        ]);

        $listing->priceHistories()->create([
            'price' => $listing->price + $secondLift,
            'recorded_at' => now()->subDays(11 - ($index % 4)),
            'note' => 'Korekcija',
        ]);
    }

    protected function simulatePriceDrops(Collection $listings): void
    {
        $priceDrops = [
            'BMW 320d M paket, servisna knjiga' => 13_200,
            'Volkswagen Golf 7 1.6 TDI DSG' => 9_900,
            'Audi A4 2.0 TDI S line' => 16_100,
            'Toyota Corolla Hybrid Luna' => 19_200,
            'Škoda Octavia 1.8 TSI Style' => 13_900,
        ];

        foreach ($priceDrops as $title => $newPrice) {
            $listings->firstWhere('title', $title)?->update(['price' => $newPrice]);
        }
    }

    protected function seedSearchLogs(User $userOne, User $userTwo, User $userThree): void
    {
        $users = collect([$userOne->id, $userTwo->id, $userThree->id]);

        foreach ([
            ['query' => 'BMW 320d', 'brand' => 'BMW', 'model' => '320d'],
            ['query' => 'BMW 320d M paket', 'brand' => 'BMW', 'model' => '320d'],
            ['query' => 'Audi A4 2017', 'brand' => 'Audi', 'model' => 'A4'],
            ['query' => 'Audi A4 automatik', 'brand' => 'Audi', 'model' => 'A4'],
            ['query' => 'Golf 7', 'brand' => 'Volkswagen', 'model' => 'Golf 7'],
            ['query' => 'Golf 7 DSG', 'brand' => 'Volkswagen', 'model' => 'Golf 7'],
            ['query' => 'Golf 7 GTD', 'brand' => 'Volkswagen', 'model' => 'Golf 7'],
            ['query' => 'Octavia 2018', 'brand' => 'Škoda', 'model' => 'Octavia'],
            ['query' => 'Octavia DSG', 'brand' => 'Škoda', 'model' => 'Octavia'],
            ['query' => 'Toyota Corolla hybrid', 'brand' => 'Toyota', 'model' => 'Corolla'],
            ['query' => 'Tucson benzin', 'brand' => 'Hyundai', 'model' => 'Tucson'],
            ['query' => 'BMW X1 dizel', 'brand' => 'BMW', 'model' => 'X1'],
        ] as $log) {
            SearchLog::query()->create([
                'user_id' => $users->random(),
                'query' => $log['query'],
                'brand' => $log['brand'],
                'model' => $log['model'],
                'filters' => $log,
            ]);
        }
    }

    protected function demoListingSvg(Listing $listing, array $palette, string $view): string
    {
        [$background, $surface, $accent] = $palette;

        $title = $this->svgText($listing->brand.' '.$listing->model);
        $subtitle = $this->svgText($listing->year.'  |  '.number_format($listing->price, 0, ',', '.').' EUR  |  '.$listing->city);
        $badge = $this->svgText($view.' VIEW');
        $caption = $this->svgText($listing->title);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" role="img" aria-labelledby="title desc">
  <title id="title">{$title}</title>
  <desc id="desc">{$caption}</desc>
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$background}" />
      <stop offset="100%" stop-color="{$surface}" />
    </linearGradient>
    <linearGradient id="accent" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0%" stop-color="{$accent}" stop-opacity="0.95" />
      <stop offset="100%" stop-color="#ffffff" stop-opacity="0.15" />
    </linearGradient>
  </defs>

  <rect width="1600" height="900" fill="url(#bg)" />
  <circle cx="1240" cy="180" r="220" fill="{$accent}" opacity="0.12" />
  <circle cx="250" cy="720" r="260" fill="#ffffff" opacity="0.05" />
  <path d="M0 760 C280 640 540 690 760 760 C980 830 1260 840 1600 720 L1600 900 L0 900 Z" fill="#020617" opacity="0.55" />

  <g opacity="0.95">
    <path d="M330 570 C420 470 575 430 785 430 H935 C1025 430 1100 450 1165 495 L1265 565 C1288 581 1300 603 1300 628 V666 C1300 684 1285 699 1267 699 H1227 C1212 652 1175 620 1123 620 C1070 620 1031 652 1014 699 H584 C570 652 533 620 480 620 C427 620 388 652 372 699 H330 C308 699 290 681 290 659 V615 C290 596 304 579 330 570 Z" fill="#dbe4f0" opacity="0.92" />
    <path d="M487 454 H805 C876 454 931 474 971 516 L1043 590 H382 L427 513 C442 486 460 467 487 454 Z" fill="url(#accent)" opacity="0.92" />
    <path d="M1036 516 H1143 C1189 516 1232 533 1267 565 L1287 583 H1089 L1036 516 Z" fill="#f8fafc" opacity="0.82" />
    <path d="M412 607 H1106" stroke="#ffffff" stroke-opacity="0.35" stroke-width="8" stroke-linecap="round" />
    <circle cx="480" cy="700" r="84" fill="#0f172a" />
    <circle cx="1123" cy="700" r="84" fill="#0f172a" />
    <circle cx="480" cy="700" r="48" fill="#cbd5e1" />
    <circle cx="1123" cy="700" r="48" fill="#cbd5e1" />
    <path d="M437 451 L401 590 H614 L594 451 Z" fill="#d7f0ff" opacity="0.55" />
    <path d="M637 451 L654 590 H1016 L951 514 C917 480 871 451 805 451 Z" fill="#d7f0ff" opacity="0.55" />
    <rect x="1034" y="603" width="132" height="17" rx="8.5" fill="#fb7185" opacity="0.85" />
    <rect x="386" y="603" width="122" height="17" rx="8.5" fill="#f8fafc" opacity="0.8" />
  </g>

  <g transform="translate(120 120)">
    <rect x="0" y="0" width="216" height="42" rx="21" fill="#0f172a" fill-opacity="0.42" stroke="#ffffff" stroke-opacity="0.18" />
    <text x="28" y="28" fill="#e2e8f0" font-size="20" font-family="Manrope, Arial, sans-serif" font-weight="700" letter-spacing="4">{$badge}</text>
    <text x="0" y="126" fill="#ffffff" font-size="78" font-family="Space Grotesk, Arial, sans-serif" font-weight="700">{$title}</text>
    <text x="0" y="178" fill="#cbd5e1" font-size="28" font-family="Manrope, Arial, sans-serif">{$subtitle}</text>
    <text x="0" y="512" fill="#f8fafc" fill-opacity="0.82" font-size="26" font-family="Manrope, Arial, sans-serif">AutoIQ demo listing gallery</text>
  </g>
</svg>
SVG;
    }

    protected function demoBlogSvg(BlogPost $blogPost, array $palette, int $index): string
    {
        [$background, $accent, $surface] = $palette;

        $title = $this->svgText($blogPost->title);
        $category = $this->svgText((string) $blogPost->category);
        $excerpt = $this->svgText(str($blogPost->excerptText())->limit(120)->toString());
        $meta = $this->svgText($blogPost->readingTimeLabel().'  |  '.optional($blogPost->published_at)->format('d.m.Y'));
        $label = $this->svgText('AutoIQ Blog');
        $patternOffset = 80 + ($index * 24);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" role="img" aria-labelledby="title desc">
  <title id="title">{$title}</title>
  <desc id="desc">{$excerpt}</desc>
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$background}" />
      <stop offset="100%" stop-color="{$accent}" />
    </linearGradient>
    <linearGradient id="surface" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.18" />
      <stop offset="100%" stop-color="{$surface}" stop-opacity="0.12" />
    </linearGradient>
  </defs>

  <rect width="1600" height="900" fill="url(#bg)" />
  <circle cx="1320" cy="170" r="240" fill="#ffffff" opacity="0.06" />
  <circle cx="230" cy="770" r="280" fill="#020617" opacity="0.2" />
  <path d="M0 620 C240 560 430 640 620 620 C800 600 990 470 1180 480 C1340 488 1470 560 1600 640 L1600 900 L0 900 Z" fill="#020617" opacity="0.36" />

  <g opacity="0.14" stroke="#ffffff" stroke-width="2">
    <path d="M{$patternOffset} 110 H1510" />
    <path d="M{$patternOffset} 170 H1330" />
    <path d="M{$patternOffset} 230 H1460" />
  </g>

  <g transform="translate(110 104)">
    <rect x="0" y="0" width="220" height="42" rx="21" fill="#020617" fill-opacity="0.35" stroke="#ffffff" stroke-opacity="0.2" />
    <text x="28" y="28" fill="#e2e8f0" font-size="20" font-family="Manrope, Arial, sans-serif" font-weight="700" letter-spacing="4">{$label}</text>

    <rect x="0" y="96" width="264" height="40" rx="20" fill="url(#surface)" stroke="#ffffff" stroke-opacity="0.14" />
    <text x="24" y="122" fill="#dbeafe" font-size="18" font-family="Manrope, Arial, sans-serif" font-weight="700" letter-spacing="3">{$category}</text>

    <text x="0" y="220" fill="#ffffff" font-size="74" font-family="Space Grotesk, Arial, sans-serif" font-weight="700">{$title}</text>
    <text x="0" y="286" fill="#dbe4f0" font-size="28" font-family="Manrope, Arial, sans-serif">{$excerpt}</text>
    <text x="0" y="348" fill="#f8fafc" fill-opacity="0.78" font-size="24" font-family="Manrope, Arial, sans-serif">{$meta}</text>

    <g transform="translate(0 476)">
      <rect x="0" y="0" width="860" height="210" rx="34" fill="#020617" fill-opacity="0.28" stroke="#ffffff" stroke-opacity="0.12" />
      <text x="48" y="76" fill="#f8fafc" font-size="28" font-family="Space Grotesk, Arial, sans-serif" font-weight="700">Podaci, tržišni signali i praktični saveti</text>
      <text x="48" y="132" fill="#cbd5e1" font-size="24" font-family="Manrope, Arial, sans-serif">AutoIQ blog pomaže da oglase gledaš kroz cenu, rizik i stvarnu vrednost.</text>
      <rect x="48" y="162" width="242" height="12" rx="6" fill="#ffffff" fill-opacity="0.22" />
      <rect x="48" y="186" width="184" height="12" rx="6" fill="#ffffff" fill-opacity="0.12" />
    </g>
  </g>
</svg>
SVG;
    }

    protected function svgText(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
