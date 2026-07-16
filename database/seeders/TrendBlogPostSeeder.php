<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrendBlogPostSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->posts() as $index => $post) {
            $palette = $post['palette'];
            unset($post['palette']);

            $existingPost = BlogPost::query()->where('slug', $post['slug'])->first();

            if ($existingPost?->published_at) {
                $post['published_at'] = $existingPost->published_at;
            }

            $blogPost = BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post,
            )->fresh();

            if ($blogPost->slug !== $post['slug']) {
                $blogPost->forceFill([
                    'slug' => $post['slug'],
                ])->saveQuietly();

                $blogPost = $blogPost->fresh();
            }

            $path = 'blog/trendovi/'.$blogPost->slug.'-cover.svg';

            if ($this->shouldWritePlaceholderCover($blogPost)) {
                Storage::disk('public')->put($path, $this->coverSvg($blogPost, $palette, $index));

                $blogPost->forceFill([
                    'cover_image_path' => $path,
                    'cover_image_alt' => $blogPost->title,
                ])->saveQuietly();
            } elseif (! $blogPost->cover_image_alt) {
                $blogPost->forceFill([
                    'cover_image_alt' => $blogPost->title,
                ])->saveQuietly();
            }
        }
    }

    protected function posts(): array
    {
        return array_merge($this->hubPosts(), [
            [
                'title' => 'Golf 7 ili Audi A3: šta je pametnija kupovina u Srbiji',
                'slug' => 'golf-7-ili-audi-a3-sta-je-pametnija-kupovina-u-srbiji',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dva najčešća izbora za kupce koji žele nemački kompakt, ali ne žele da plate grešku kroz održavanje, kilometražu ili slabiju kasniju prodaju.',
                'content' => <<<'TEXT'
Golf 7 i Audi A3 često ulaze u isti uži izbor jer dele sličnu tehničku osnovu, imaju poznate motore i drže cenu bolje od većine kompaktnih automobila. Razlika je u tome šta kupac zaista želi da plati. Golf je racionalniji izbor kada tražiš jednostavniju kupovinu, više primeraka na tržištu i širi izbor delova. Audi A3 donosi bolji osećaj u kabini i jači premium imidž, ali svaki loš primerak taj imidž brzo pretvori u skuplje održavanje.

## Golf 7 je sigurniji kada želiš lakše poređenje

Kod Golfa 7 najviše smisla imaju automobili sa jasnom servisnom istorijom i realnom kilometražom. Zbog ogromne potražnje ima mnogo oglasa, ali upravo zato ima i velikih razlika između prosečnog i dobrog primerka. Dva Golfa istog godišta mogu delovati slično na fotografijama, a da jedan ima uredan servis menjača, kvačila i dizni, dok drugi samo čeka prvog vlasnika koji će platiti zaostala ulaganja.

## Audi A3 traži strožu proveru premium troškova

Audi A3 treba gledati strože. Kupci ga često biraju jer žele bolji enterijer, bolju izolaciju i osećaj skupljeg automobila, ali kod polovnog A3 taj osećaj vredi samo ako je auto održavan bez preskakanja. Posebno proveri automatski menjač, tragove gradske vožnje, stanje enterijera i da li kilometraža odgovara potrošenosti volana, sedišta i pedala.

## Dizel, DSG i S tronic nisu detalji

Ako kupuješ dizel, ne gledaj samo potrošnju. Kod oba modela treba proveriti DPF, EGR, turbinu i servisni ritam. Dizel ima najviše smisla za otvoren put i veću godišnju kilometražu. Ako uglavnom voziš grad, benzinac može biti mirnija odluka, čak i kada troši malo više, jer skupi dizel kvar lako pojede razliku u potrošnji.

## Šta je pametnija kupovina u Srbiji

Golf 7 je bolji izbor kada hoćeš najlikvidniji polovnjak, lakšu kasniju prodaju i više prostora za poređenje cena. Audi A3 je bolji kada želiš kompaktniji premium osećaj i spreman si da platiš bolji primerak, ne samo oznaku na haubi. Ako su cena, godište i kilometraža slični, prednost daj automobilu sa boljom dokumentacijom, a ne automobilu sa boljim znakom.

Najpametnija kupovina je često dobar Golf umesto prosečnog Audija. Ali ako nađeš A3 sa proverljivom istorijom, korektnom kilometražom i bez tragova zapuštenog održavanja, razlika u ceni može imati smisla. U oba slučaja, pre pregleda napravi listu uporedivih oglasa i ne dozvoli da te oprema ili fotografije odvoje od realnog stanja automobila.

FAQ: Da li je Golf 7 bolji od Audi A3 kao polovnjak?
Golf 7 je često bolji racionalan izbor zbog većeg izbora, lakšeg poređenja cena i jeftinijeg održavanja. Audi A3 ima smisla kada je istorija održavanja jasna i kada premium cena prati stvarno stanje.

FAQ: Šta proveriti kod Golf 7 ili Audi A3 dizela?
Proveri DPF, EGR, turbinu, dizne, servisni ritam, hladan start i menjač. Kod DSG ili S tronic menjača posebno traži dokaz o servisu ulja i probnu vožnju u gradu.
TEXT,
                'highlights' => [
                    'Golf 7 je sigurniji izbor za likvidnost, dostupnost delova i lakše poređenje oglasa.',
                    'Audi A3 ima smisla samo kada je istorija održavanja jača od premium utiska.',
                    'Kod oba modela servisna istorija i menjač vrede više od kilometraže napisane u oglasu.',
                ],
                'tags' => ['Golf 7', 'Audi A3', 'kompakt', 'poređenje'],
                'meta_title' => 'Golf 7 ili Audi A3: šta kupiti kao polovnjak',
                'meta_description' => 'Poređenje Golf 7 i Audi A3 polovnjaka u Srbiji: cena, održavanje, dizel rizici, premium utisak i kasnija prodaja.',
                'is_featured' => true,
                'published_at' => now()->subHours(5),
                'palette' => ['#0f172a', '#22d3ee', '#e2e8f0'],
            ],
            [
                'title' => 'Audi A4 ili BMW 320d: premium limuzina bez skupog iznenađenja',
                'slug' => 'audi-a4-ili-bmw-320d-premium-limuzina-bez-skupog-iznenadenja',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Audi A4 i BMW 320d su među najčešćim premium izborima, ali dobar primerak zavisi manje od marke, a više od istorije, kilometraže i načina vožnje.',
                'content' => <<<'TEXT'
Audi A4 i BMW 320d su automobili koje kupci često porede kada žele ozbiljniji polovnjak za posao, porodicu i duži put. Oba modela mogu biti vrlo dobra kupovina, ali oba mogu biti i skupa lekcija ako se kupe samo zbog značke, opreme ili niske cene. Kod ovakvih automobila najvažnije pitanje nije koji je bolji kao nov, nego koji je konkretniji primerak manje rizičan posle godina korišćenja.

Audi A4 najčešće privlači kupce koji žele mirniji karakter, kvalitetan enterijer i dobar osećaj stabilnosti na autoputu. Kao polovnjak ima smisla kada je servisna istorija jasna, kada se vidi da auto nije održavan samo pred prodaju i kada stanje kabine odgovara kilometraži. Ako je cena primetno ispod sličnih oglasa, razlog moraš pronaći pre nego što odeš na tehnički pregled.

BMW 320d kupci biraju zbog osećaja u vožnji, dobrog balansa potrošnje i performansi i jakog imidža. Baš zato treba biti oprezan sa automobilima koji su voženi agresivno, čipovani bez dokaza ili održavani minimalno. Kod 320d nije dovoljno da motor lepo radi na leru. Treba proveriti hladan start, dim, lanac kod rizičnih generacija, turbinu, menjač i stanje zadnjeg trapa.

U Srbiji se oba modela često kupuju kao uvezeni polovnjaci sa većom kilometražom nego što oglas na prvi pogled sugeriše. Zato poređenje ne sme da bude samo Audi protiv BMW-a. Pravo poređenje je dokumentovan A4 protiv dokumentovanog 320d. Ako jedan auto ima račune, servisnu knjigu, proverljivu istoriju i dosledno stanje, a drugi samo bolju opremu, odluka je mnogo lakša.

Za vozača koji prelazi mnogo kilometara otvorenim putem, oba dizela mogu imati smisla. Za nekoga ko uglavnom vozi grad, kratke relacije i često hladan motor, rizik raste. DPF, EGR, turbina i automatski menjač ne praštaju pogrešan režim korišćenja. Zato potrošnja od nekoliko litara manje nije prava ušteda ako će auto živeti u uslovima za koje nije kupljen.

Ako želiš mirniji premium polovnjak, Audi A4 često deluje kao prirodniji izbor. Ako ti je vožnja važnija i spreman si na strožu proveru mehanike, BMW 320d može biti zadovoljniji izbor. Ali u oba slučaja presudi primerak, ne model. Najbolji savet je da prvo odrediš budžet za kupovinu i početna ulaganja, pa tek onda biraš između Audija i BMW-a.
TEXT,
                'highlights' => [
                    'Kod premium polovnjaka konkretan primerak je važniji od marke.',
                    'BMW 320d traži strožu proveru motora, trapa i načina prethodne vožnje.',
                    'Audi A4 ima smisla kada stanje i dokumentacija opravdavaju premium cenu.',
                ],
                'tags' => ['Audi A4', 'BMW 320d', 'premium', 'dizel'],
                'meta_title' => 'Audi A4 ili BMW 320d: polovni premium vodič',
                'meta_description' => 'Poređenje Audi A4 i BMW 320d polovnjaka: održavanje, dizel rizici, kilometraža, oprema i izbor boljeg primerka.',
                'is_featured' => false,
                'published_at' => now()->subHours(4),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'BMW X3, Audi Q5 ili Audi Q3: koji premium SUV ima najviše smisla',
                'slug' => 'bmw-x3-audi-q5-ili-audi-q3-koji-premium-suv-ima-najvise-smisla',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Premium SUV izgleda kao sigurna kupovina dok se ne saberu gume, pogon, menjač, trap i veća potrošnja. Evo kako da porediš X3, Q5 i Q3.',
                'content' => <<<'TEXT'
BMW X3, Audi Q5 i Audi Q3 često završe u istoj pretrazi, iako nisu potpuno isti tip automobila. X3 i Q5 su ozbiljniji porodični premium SUV modeli, dok je Q3 kompaktniji i lakši za grad. Ako ih porediš samo po ceni, može delovati da je mlađi Q3 bolji od starijeg X3 ili Q5. Ako ih porediš po prostoru, udobnosti i troškovima, slika se brzo menja.

BMW X3 je najbolji za vozača koji želi više prostora, jači osećaj automobila i stabilnost na dužem putu. Kao polovnjak traži detaljnu proveru pogona, automatskog menjača, trapa i guma. SUV troškovi nisu samo servis motora. Veće gume, skuplji amortizeri, složeniji pogon i veća masa znače da dobar X3 mora imati budžet za održavanje, ne samo budžet za kupovinu.

Audi Q5 igra sličnu ulogu, ali često privlači kupce koji žele mirniji premium karakter i kvalitetan enterijer. Kod Q5 posebno gledaj servis menjača, stanje quattro pogona ako ga ima, tragove težeg korišćenja i kvalitet prethodnih popravki. Automobil koji je na fotografijama savršen može imati skupe tragove zanemarivanja ispod karoserije.

Audi Q3 je drugačija kupovina. On ima smisla ako želiš povišenu poziciju sedenja, lakše parkiranje i manji auto za grad, ali ne želiš potpunu veličinu i trošak većeg SUV-a. Mana je što se često plaća premium cena za automobil koji po prostoru nije mnogo korisniji od dobrog kompakta. Zato Q3 treba porediti i sa Audi A3, Golfom, T-Rocom ili Tiguanom, ne samo sa X3 i Q5.

Ako kupuješ SUV zbog porodice, prvo proveri zadnju klupu, gepek, dečja sedišta i realnu potrošnju. Ako kupuješ zbog imidža, budi svestan da imidž ne smanjuje račun za kvar. Najskuplja greška kod premium SUV-a je primerak koji je dovoljno jeftin da privuče kupca, ali dovoljno zapušten da odmah traži velika ulaganja.

X3 je najbolji kada želiš najviše automobila i spreman si na veći trošak. Q5 je dobar kompromis ako nađeš primerak sa jakom istorijom održavanja. Q3 je najrazumniji za grad i manju porodicu, ali samo ako cena ne ulazi previše blizu većih modela. U svakoj varijanti, pre kupovine proveri istoriju, trap, menjač, gume i tragove oštećenja, jer tu premium SUV najčešće krije stvarnu cenu.
TEXT,
                'highlights' => [
                    'X3 i Q5 su veći, udobniji i skuplji za održavanje od Q3.',
                    'Q3 je racionalniji za grad, ali nije zamena za veliki porodični SUV.',
                    'Kod premium SUV-a obavezno proveri pogon, menjač, trap, gume i istoriju oštećenja.',
                ],
                'tags' => ['BMW X3', 'Audi Q5', 'Audi Q3', 'SUV'],
                'meta_title' => 'BMW X3, Audi Q5 ili Audi Q3: koji SUV kupiti',
                'meta_description' => 'Poređenje polovnih premium SUV modela BMW X3, Audi Q5 i Audi Q3: prostor, troškovi, menjač, pogon i realna vrednost.',
                'is_featured' => false,
                'published_at' => now()->subHours(3),
                'palette' => ['#172033', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Škoda Octavia ili Volkswagen Passat: porodični dizel koji se lakše isplati',
                'slug' => 'skoda-octavia-ili-volkswagen-passat-porodicni-dizel-koji-se-lakse-isplati',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Octavia i Passat su logičan izbor za porodicu i duge relacije, ali razlika u prostoru, ceni i održavanju menja računicu.',
                'content' => <<<'TEXT'
Škoda Octavia i Volkswagen Passat godinama su među najlogičnijim izborima za kupce koji žele prostran polovnjak, dizel potrošnju i dobru kasniju prodaju. Oba modela imaju jak ugled, mnogo primeraka na tržištu i široku servisnu podršku. Razlika je u tome što Octavia često nudi bolji odnos prostora i cene, dok Passat donosi više komfora, ozbiljniji osećaj i obično višu početnu cenu.

Octavia ima smisla za kupca koji želi veliki gepek, razumno održavanje i automobil koji ne komplikuje svakodnevicu. Često je dovoljna za porodicu, posao i putovanja, a pritom ne traži isti budžet kao Passat. Problem nastaje kada se kupac zaleti na najjeftiniji primerak, posebno ako je auto bio flotno vozilo, taksi, službeni auto ili je prešao mnogo više nego što kilometraža pokazuje.

Passat je bolji ako ti je važnija udobnost na dužem putu, bolja zvučna izolacija i osećaj većeg automobila. On može biti odlična kupovina za vozača koji prelazi mnogo kilometara, ali traži pažljiviju proveru. Automatski menjač, zamajac, dizne, DPF, EGR, trap i istorija servisa moraju biti deo pregleda, jer Passat retko oprašta kupovinu bez rezerve u budžetu.

Kod oba modela dizel ima smisla samo ako način vožnje podržava dizel. Ako auto većinu vremena provodi na kratkim gradskim relacijama, problemi sa DPF-om i EGR-om mogu se pojaviti čak i kada je model inače dobar. Za porodični automobil koji vozi otvoren put, dizel računica je mnogo zdravija.

Octavia je često pametniji izbor kada hoćeš niži rizik i dobru upotrebljivost za uloženi novac. Passat je bolji kada želiš više udobnosti i spreman si da platiš bolji primerak, ne samo veći auto. Ako su cene bliske, nemoj automatski birati Passat. Ponekad je mlađa i urednija Octavia mnogo bolja kupovina od starijeg Passata sa atraktivnijom opremom.

Pre odluke uporedi bar deset oglasa istog godišta, motora i menjača. Gledaj kilometražu, broj vlasnika, servisnu istoriju, stanje enterijera i koliko dugo oglas stoji. Kod Octavije i Passata tržište ima mnogo podataka, pa iskoristi tu prednost. Dobar porodični dizel ne kupuje se iz jednog oglasa, nego iz poređenja.
TEXT,
                'highlights' => [
                    'Octavia često nudi bolji odnos prostora, cene i održavanja.',
                    'Passat ima više komfora, ali traži veći budžet za dobar primerak.',
                    'Kod oba modela dizel ima smisla tek ako voziš dovoljno otvorenog puta.',
                ],
                'tags' => ['Škoda Octavia', 'Volkswagen Passat', 'porodični auto', 'dizel'],
                'meta_title' => 'Škoda Octavia ili VW Passat: polovni porodični dizel',
                'meta_description' => 'Poređenje Škode Octavije i Volkswagen Passata kao polovnjaka: prostor, komfor, dizel troškovi, održavanje i kasnija prodaja.',
                'is_featured' => false,
                'published_at' => now()->subHours(2),
                'palette' => ['#1f2937', '#84cc16', '#f8fafc'],
            ],
            [
                'title' => 'Opel Astra ili Renault Megane: kompakt do razumnog budžeta',
                'slug' => 'opel-astra-ili-renault-megane-kompakt-do-razumnog-budzeta',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Astra i Megane nisu najprestižniji izbor, ali često imaju više smisla od skupljeg nemačkog polovnjaka kada je budžet ograničen.',
                'content' => <<<'TEXT'
Opel Astra i Renault Megane su automobili koje kupci često razmatraju kada žele kompakt za razuman novac, ali ne žele da plate premiju za Golf, Audi ili BMW. To je dobra polazna tačka, jer u ovom delu tržišta često možeš naći mlađi auto, bolju opremu i manju kilometražu za isti budžet. Ključ je da ne posmatraš ove modele kao kompromis, već kao racionalnu kupovinu koja mora biti proverena jednako ozbiljno.

Opel Astra je dobar izbor za kupce koji žele poznatu mehaniku, pristojan izbor delova i automobil koji većina servisa dobro poznaje. Na tržištu ima mnogo primeraka, što pomaže pri poređenju cena. Obrati pažnju na poreklo, servisnu istoriju, stanje menjača, kvačila i eventualne tragove zapuštenog održavanja. Dobra Astra može biti vrlo zahvalan auto, ali najjeftinija Astra često je jeftina sa razlogom.

Renault Megane često nudi više opreme i udobnosti za isti novac. Dizel motori mogu biti štedljivi i prijatni za duži put, ali traže proveru servisnog ritma, turbine, dizni i DPF-a. Kod benzinskih verzija treba gledati realnu potrošnju, stanje elektronike i da li je auto održavan redovno, a ne samo kada se pojavi kvar.

Velika prednost Astre i Megana je što kupac ne mora uvek da juri premium znak da bi dobio dobar svakodnevni auto. Ako budžet nije veliki, bolji izbor je često mlađi i uredniji kompakt sa jasnom istorijom nego stariji nemački model sa mnogo kilometara i skupim zaostalim ulaganjima. To je posebno važno za kupce kojima je auto potreban za posao, porodicu i svakodnevnu vožnju.

Kod oba modela gledaj celinu. Nije dovoljno da motor radi mirno. Proveri trap, klimu, elektroniku, stanje enterijera, gume, kočnice, servisne račune i ponašanje auta na probnoj vožnji. Ako prodavac nema jasne odgovore, to je signal da nastaviš dalje, čak i kada cena deluje dobra.

Astra je često sigurnija za kupce koji žele poznatu servisnu mrežu i lakše održavanje. Megane može biti bolji ako nađeš bogatiju opremu, udobniji primerak i jasnu istoriju. Najbolji izbor nije model koji ima bolji imidž, nego automobil koji ostavlja manje nepoznanica pre kupovine.
TEXT,
                'highlights' => [
                    'Astra i Megane često nude mlađi auto za isti novac nego skuplji nemački modeli.',
                    'Astra je jača kada želiš jednostavniju servisnu podršku i širok izbor delova.',
                    'Megane ima smisla ako dobijaš bolju opremu, udobnost i dokazano održavanje.',
                ],
                'tags' => ['Opel Astra', 'Renault Megane', 'kompakt', 'budžet'],
                'meta_title' => 'Opel Astra ili Renault Megane: koji polovnjak kupiti',
                'meta_description' => 'Poređenje Opel Astre i Renault Megana kao polovnjaka: održavanje, oprema, dizel i benzin motori, budžet i rizici.',
                'is_featured' => false,
                'published_at' => now()->subHour(),
                'palette' => ['#111827', '#fb7185', '#f8fafc'],
            ],
            [
                'title' => 'Volkswagen Tiguan ili Škoda Kodiaq: koji porodični SUV ima više smisla',
                'slug' => 'volkswagen-tiguan-ili-skoda-kodiaq-koji-porodicni-suv-ima-vise-smisla',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Tiguan i Kodiaq dele sličnu logiku, ali nisu isti izbor. Jedan je lakši za svakodnevicu, drugi je jači kada prostor i porodična upotreba postanu prioritet.',
                'content' => <<<'TEXT'
Volkswagen Tiguan i Škoda Kodiaq često završavaju u istoj pretrazi jer kupci žele povišen porodični automobil, dobar dizel ili benzinac, automatski menjač i dovoljno prostora za putovanja. Ipak, ova dva modela ne rešavaju isti problem. Tiguan je kompaktniji i lakši za grad, dok Kodiaq ima više smisla kada ti je prostor važniji od lakoće parkiranja.

Tiguan je dobar izbor za vozača koji želi SUV osećaj, ali ne želi prevelik automobil. Lakše se koristi u gradu, dovoljno je prostran za manju porodicu i obično ima širi izbor primeraka. Kod polovnog Tiguana treba proveriti servis DSG menjača ako ga ima, stanje trapa, potrošnju guma i da li je auto već imao veća ulaganja oko pogona ili elektronike.

Kodiaq je jači kada treba više prostora. Veći gepek, komfornija zadnja klupa i opcija sa sedam sedišta čine ga boljim za porodice koje često putuju ili imaju više prtljaga. Međutim, veći auto donosi i veće troškove. Gume, kočnice, trap i potrošnja mogu biti primetno skuplji nego kod kompaktnijeg SUV-a. Zbog toga je najjeftiniji Kodiaq retko najbolja kupovina.

Kod oba modela dizel ima smisla za otvoren put i veću godišnju kilometražu. Za gradsku vožnju treba pažljivo razmisliti, jer DPF, EGR i kratke relacije nisu dobra kombinacija. Benzinske verzije mogu biti mirnije za svakodnevicu, ali proveri realnu potrošnju i servisnu istoriju, posebno ako auto ima turbo motor i automatski menjač.

Ako ti treba jedan automobil za sve, Tiguan je često lakši kompromis. Ako ti je porodica prerasla kompaktni SUV i stvarno koristiš prostor, Kodiaq opravdava veću cenu. Najvažnije je da ne plaćaš samo ideju velikog SUV-a. Izmeri realnu upotrebu: koliko često putuješ, koliko prtljaga nosiš, da li ti treba sedam sedišta i gde auto provodi većinu vremena.

Pametna odluka je ona koja prati svakodnevicu. Bolje je kupiti uredan Tiguan sa jasnom istorijom nego Kodiaq koji je veći samo na papiru, ali odmah traži ulaganja. Sa druge strane, dobar Kodiaq je ozbiljno porodično rešenje ako prostor stvarno koristiš, a ne samo želiš.
TEXT,
                'highlights' => [
                    'Tiguan je lakši za grad i jednostavniji kao svakodnevni SUV.',
                    'Kodiaq ima prednost kada su prostor, gepek i sedam sedišta prioritet.',
                    'Kod oba modela posebno proveri DSG, trap, gume i istoriju održavanja.',
                ],
                'tags' => ['Volkswagen Tiguan', 'Škoda Kodiaq', 'porodični SUV', 'poređenje'],
                'meta_title' => 'Volkswagen Tiguan ili Škoda Kodiaq: koji SUV kupiti',
                'meta_description' => 'Poređenje polovnih Volkswagen Tiguan i Škoda Kodiaq SUV modela: prostor, troškovi, DSG, dizel rizici i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(50),
                'palette' => ['#102033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Nissan Qashqai ili Peugeot 3008: crossover za grad i porodicu',
                'slug' => 'nissan-qashqai-ili-peugeot-3008-crossover-za-grad-i-porodicu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Qashqai i 3008 su čest izbor za kupce koji žele povišenu poziciju sedenja, ali razlika u kabini, motorima i održavanju menja računicu.',
                'content' => <<<'TEXT'
Nissan Qashqai i Peugeot 3008 su modeli koje kupci često gledaju kada žele crossover koji nije prevelik, ali deluje ozbiljnije od kompakta. Oba automobila mogu biti dobar porodični izbor, ali privlače različite kupce. Qashqai je poznatiji kao sigurna i široko prihvaćena opcija, dok 3008 nudi moderniji enterijer i jači vizuelni utisak.

Qashqai je dobar za kupce koji žele predvidljiv automobil, solidnu kasniju prodaju i veliki broj oglasa za poređenje. Prednost mu je što tržište dobro zna šta vredi, pa se lakše vidi kada je cena previsoka ili sumnjivo niska. Kod polovnog Qashqaija proveri servisnu istoriju, stanje kvačila ili automatskog menjača, trap, klimu i da li je auto korišćen uglavnom u gradu.

Peugeot 3008 često osvaja kabinom. I-Cockpit, bolji materijali i udobniji osećaj mogu ga učiniti prijatnijim za vožnju od Qashqaija. Ali kod polovnog 3008 treba biti pažljiv oko elektronike, servisnog ritma i izbora motora. Ako je auto bogato opremljen, proveri da li sve opcije rade, jer popravke komforne opreme umeju da budu neprijatno skupe.

Kod dizel verzija oba modela važi isto pravilo: dizel je dobar ako voziš dovoljno otvorenog puta. Ako ti auto služi za kratke gradske relacije, veći rizik nose DPF, EGR i način regeneracije. Benzinci mogu biti mirniji izbor za grad, ali treba proveriti realnu potrošnju i poznate slabosti konkretne generacije motora.

Qashqai je često racionalniji izbor kada želiš jednostavnu kupovinu, lakše poređenje cena i manji rizik pri kasnijoj prodaji. Peugeot 3008 ima smisla kada želiš bolji enterijer, više stila i udobniji osećaj, ali samo ako je primerak održavan uredno i bez skrivenih elektronskih problema.

Najbolji test je probna vožnja i poređenje sa sličnim oglasima. Ako ti Qashqai deluje dovoljno dobar, verovatno plaćaš manje rizika. Ako ti 3008 jasno daje više vrednosti kroz opremu i stanje, može biti bolja kupovina. Ne kupuj crossover zbog izgleda samog po sebi; kupi onaj koji bolje odgovara načinu vožnje i budžetu za održavanje.
TEXT,
                'highlights' => [
                    'Qashqai je lakši za poređenje i često sigurniji za kasniju prodaju.',
                    'Peugeot 3008 nudi jači enterijer, ali traži detaljniju proveru elektronike i opreme.',
                    'Kod oba modela tip motora mora da prati realne relacije koje voziš.',
                ],
                'tags' => ['Nissan Qashqai', 'Peugeot 3008', 'crossover', 'porodica'],
                'meta_title' => 'Nissan Qashqai ili Peugeot 3008: polovni crossover',
                'meta_description' => 'Poređenje Nissan Qashqai i Peugeot 3008 polovnjaka: komfor, motori, elektronika, troškovi održavanja i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(40),
                'palette' => ['#151d2f', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Toyota Corolla Hybrid ili Hyundai Ioniq: koji hibrid je mirnija kupovina',
                'slug' => 'toyota-corolla-hybrid-ili-hyundai-ioniq-koji-hibrid-je-mirnija-kupovina',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Hibridi imaju smisla za grad, ali razlika između Corolle Hybrid i Ioniqa nije samo u potrošnji. Bitni su servis, baterija i kasnija prodaja.',
                'content' => <<<'TEXT'
Toyota Corolla Hybrid i Hyundai Ioniq privlače kupce koji žele nisku potrošnju u gradu, automatik i manje brige oko dizel sistema. Oba modela mogu biti odličan izbor, posebno za vozače koji često voze u gužvi, kratke relacije i kombinaciju grada i obilaznice. Ipak, hibrid ne treba kupovati samo zato što troši malo, već zato što odgovara tvom načinu vožnje.

Corolla Hybrid ima veliku prednost u reputaciji. Toyota hibridni sistem je poznat, tržište mu veruje i kasnija prodaja je obično lakša. Kao polovnjak ima smisla kada su servisni intervali jasni, baterija nema upozorenja i auto nije korišćen kao intenzivno flotno vozilo bez dokumentacije. Posebno proveri stanje enterijera, jer kod hibrida kilometraža često bude visoka, ali dobro sakrivena mirnim radom pogona.

Hyundai Ioniq je racionalan izbor za kupce koji žele efikasan automobil, dobru aerodinamiku i često solidan nivo opreme za novac. Može biti vrlo ekonomičan, ali traži proveru istorije, softvera, baterije i načina održavanja. Prednost je što je Ioniq zamišljen kao hibrid od početka, dok Corolla dolazi kao klasičan kompakt sa hibridnim pogonom.

Kod oba modela ne očekuj čuda na autoputu. Hibrid najviše štedi u gradu i mešovitoj vožnji, gde može često da koristi električni deo pogona. Na visokim konstantnim brzinama prednost se smanjuje, pa kupac koji najviše vozi autoput treba da proveri realnu potrošnju pre odluke.

Corolla je bolja ako želiš lakšu kasniju prodaju, poznat servisni ekosistem i mirniji izbor za duži period. Ioniq ima smisla ako dobijaš više opreme, bolju cenu i urednu istoriju. Kod oba automobila obavezno traži dijagnostiku hibridnog sistema, proveru baterije i jasne servisne tragove.

Najveća greška je posmatrati hibrid kao automobil bez troškova. On može biti vrlo zahvalan, ali i dalje ima gume, kočnice, amortizere, elektroniku i baterijski sistem koji treba proveriti. Ako je primerak uredan, Corolla Hybrid i Ioniq mogu biti pametniji izbor od polovnog dizela za gradsku svakodnevicu.
TEXT,
                'highlights' => [
                    'Corolla Hybrid ima jaču reputaciju i lakšu kasniju prodaju.',
                    'Ioniq može ponuditi više opreme i dobru ekonomičnost za novac.',
                    'Kod hibrida obavezno proveri bateriju, dijagnostiku i servisnu istoriju.',
                ],
                'tags' => ['Toyota Corolla Hybrid', 'Hyundai Ioniq', 'hibrid', 'gradska vožnja'],
                'meta_title' => 'Toyota Corolla Hybrid ili Hyundai Ioniq: polovni hibrid',
                'meta_description' => 'Poređenje Toyota Corolla Hybrid i Hyundai Ioniq polovnjaka: potrošnja, baterija, održavanje, servisna istorija i kasnija prodaja.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(30),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Fiat 500L ili Dacia Duster: praktičan polovnjak za manje para',
                'slug' => 'fiat-500l-ili-dacia-duster-praktican-polovnjak-za-manje-para',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => '500L i Duster kupuju se iz praktičnih razloga: prostor, jednostavnost i cena. Ali jedan je bolji za grad i porodicu, drugi za lošije puteve i robusniju upotrebu.',
                'content' => <<<'TEXT'
Fiat 500L i Dacia Duster nisu automobili koje kupci biraju zbog prestiža. Biraju ih zato što nude mnogo praktičnosti za novac. To ih čini zanimljivim polovnjacima za porodice, vozače kojima treba viši položaj sedenja ili kupce koji žele jednostavniji automobil bez premium troškova. Ipak, razlika između njih je velika.

Fiat 500L je bolji za gradsku i porodičnu svakodnevicu. Ima preglednu kabinu, dosta prostora u odnosu na dimenzije i praktičan ulazak. Kao polovnjak ima smisla kada je održavan redovno, kada je enterijer očuvan i kada nema tragova zapuštene gradske vožnje. Posebno proveri kvačilo, trap, elektroniku, klimu i stanje sedišta, jer 500L često služi kao intenzivan porodični automobil.

Dacia Duster je robusniji izbor. Ima više smisla za lošije puteve, vikendice, sneg, makadam i kupce koji žele jednostavan SUV osećaj bez skupljeg znaka. Ako ima pogon na sva četiri točka, proveri da li je sistem održavan i da li je automobil korišćen grubo. Ako je 4x2, Duster je više povišeni praktičan auto nego pravi terenac.

Kod 500L prednost je odnos prostora i gradske upotrebljivosti. Kod Dustera prednost je jednostavnost i veća otpornost na težu svakodnevicu. Ali Duster često ima skromniji enterijer i manje sofisticiran osećaj u vožnji. Ako provodiš mnogo vremena na autoputu, probna vožnja je obavezna, jer buka, sedišta i stabilnost mogu odlučiti više od cene.

Motori i održavanje treba gledati konkretno po primerku. Kod oba modela možeš naći solidne i loše automobile. Niska cena ne sme da zameni proveru servisne istorije, stanja trapa, guma i kočnica. Ako je auto služio kao radni ili porodični alat, tragovi upotrebe moraju biti uračunati u cenu.

500L je bolji ako ti treba porodični gradski auto sa mnogo kabinskog prostora. Duster je bolji ako često voziš lošijim putevima i želiš robusniji karakter. Najrazumnija kupovina je primerak koji ne pokušava da sakrije svoju namenu: porodični auto treba da bude uredan, a radni auto treba da bude iskreno održavan.
TEXT,
                'highlights' => [
                    'Fiat 500L je praktičniji za gradsku porodicu i lakše parkiranje.',
                    'Dacia Duster je bolji za lošije puteve i robusniju upotrebu.',
                    'Kod oba modela niska cena mora da prati realno stanje, ne samo dobar opis oglasa.',
                ],
                'tags' => ['Fiat 500L', 'Dacia Duster', 'budžet', 'porodični auto'],
                'meta_title' => 'Fiat 500L ili Dacia Duster: praktičan polovnjak',
                'meta_description' => 'Poređenje Fiat 500L i Dacia Duster polovnjaka: prostor, praktičnost, troškovi, grad, loši putevi i izbor boljeg primerka.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(20),
                'palette' => ['#1f1f2e', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Mercedes-Benz C 220 d ili Volvo S60: premium limuzina bez jurnjave za znakom',
                'slug' => 'mercedes-benz-c-220-d-ili-volvo-s60-premium-limuzina-bez-jurnjave-za-znakom',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C 220 d ima jači premium status, dok Volvo S60 često nudi drugačiji balans komfora, bezbednosti i cene. Presuđuje stanje, ne značka.',
                'content' => <<<'TEXT'
Mercedes-Benz C 220 d i Volvo S60 kupuju ljudi koji žele ozbiljniju limuzinu, dobar komfor i automobil koji deluje odraslo na dužem putu. Mercedes ima jači status na tržištu i često drži cenu bolje. Volvo je tiši izbor za kupce koji više cene bezbednost, udobnost i diskretniji imidž. Kao polovnjaci, oba modela traže strogu proveru.

C 220 d privlači kupce zbog znaka, dizel potrošnje i osećaja premium klase. Dobar primerak može biti vrlo prijatan automobil za putovanja, ali loš primerak brzo postaje skup. Proveri servisnu istoriju, automatski menjač, lanac ili poznate slabosti konkretne generacije, DPF, EGR, trap i tragove loših popravki. Kod Mercedesa je posebno opasno kupiti auto koji je bio jeftiniji samo zato što je održavanje odlagano.

Volvo S60 nudi drugačiju logiku. Nije uvek toliko tražen kao Mercedes, što može biti prednost za kupca koji traži bolji odnos cene i stanja. Volvo često ima dobar komfor, odlična sedišta i jak osećaj sigurnosti. Mana je što izbor delova, servisa i polovnih primeraka može biti uži, pa treba proveriti dostupnost održavanja u mestu gde živiš.

Kod oba modela ne kupuj samo kilometražu. Premium limuzine često prelaze mnogo otvorenog puta, što samo po sebi nije problem ako je servisna istorija jasna. Veći problem je automobil sa nejasnim poreklom, ulepšanim enterijerom i bez računa. Stanje sedišta, volana, pedala, guma i diskova često kaže više od same brojke na instrument tabli.

Mercedes je bolji ako želiš likvidniji premium model i lakšu kasniju prodaju. Volvo ima smisla ako želiš udobnost, bezbednost i manje opterećenja statusom, a pronađeš primerak sa jakom dokumentacijom. U oba slučaja ostavi budžet za početna ulaganja, jer kupovina premium polovnjaka bez rezerve obično nije dobra ideja.

Najbolja odluka je poređenje konkretnih automobila, ne poređenje brendova. Uredan S60 može biti bolja kupovina od prosečnog C 220 d, kao što odličan Mercedes može opravdati višu cenu. Ako dokumentacija nije jasna, preskoči oglas bez obzira na znak na haubi.
TEXT,
                'highlights' => [
                    'C 220 d ima jači status i lakšu kasniju prodaju, ali i veći rizik lošeg primerka.',
                    'Volvo S60 može dati bolji odnos cene, komfora i bezbednosti ako je istorija jasna.',
                    'Kod premium limuzina dokumentacija i stanje vrede više od kilometraže u oglasu.',
                ],
                'tags' => ['Mercedes C 220 d', 'Volvo S60', 'premium limuzina', 'dizel'],
                'meta_title' => 'Mercedes C 220 d ili Volvo S60: polovna limuzina',
                'meta_description' => 'Poređenje Mercedes-Benz C 220 d i Volvo S60 polovnih limuzina: komfor, održavanje, dizel rizici, status i realna vrednost.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(10),
                'palette' => ['#141824', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Renault Clio 1.5 dCi: mali dizel koji traži dobru istoriju',
                'slug' => 'polovni-renault-clio-15-dci-mali-dizel-koji-trazi-dobru-istoriju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Clio 1.5 dCi može biti odličan štedljiv gradsko-prigradski auto, ali samo kada servisna istorija prati kilometražu i način vožnje.',
                'content' => <<<'TEXT'
Renault Clio 1.5 dCi je jedan od onih polovnjaka koji lako privuku kupca jer obećava malu potrošnju, pristupačnu registraciju i cenu koja deluje razumno. To može biti dobra kupovina za vozača koji svakodnevno prelazi dovoljno kilometara, ali nije svaki mali dizel automatski pametna odluka. Kod ovakvog auta presudno je kako je korišćen, ne samo koliko košta.

Najveća prednost Clia sa 1.5 dCi motorom je ekonomičnost. Ako voziš prigradske relacije, otvoren put ili kombinaciju grada i dužih vožnji, potrošnja može biti vrlo prijatna. Problem nastaje kada je auto godinama korišćen samo na kratkim relacijama, sa hladnim motorom i bez redovnog održavanja. Tada dizel sistemi koji na papiru deluju štedljivo mogu postati prvi veći trošak.

Pre kupovine proveri servisnu istoriju, intervale zamene ulja, stanje turbine, dizni, EGR-a i DPF-a ako ga konkretna verzija ima. Ne oslanjaj se samo na priču prodavca da auto malo troši. Dobro održavan dizel se vidi kroz račune, miran rad, normalan hladan start i ponašanje na probnoj vožnji. Ako nema dokumentacije, cenu treba gledati mnogo strože.

Clio je mali auto, pa kupci često očekuju da je sve jeftino. To nije uvek tačno. Kvačilo, ubrizgavanje, turbo i izduvni sistemi mogu lako poništiti uštedu u potrošnji ako je primerak loš. Sa druge strane, uredan Clio može biti vrlo zahvalan za svakodnevnu vožnju, posebno ako ti ne treba veliki gepek i ako ne želiš skupe gume, veliku registraciju i komplikovan premium auto.

Obavezno proveri i kabinu. Mali automobili često rade mnogo gradskih kilometara, dostava, firmi ili svakodnevnih kratkih vožnji. Volan, sedište, pedale, menjač i vrata mogu otkriti više od kilometraže u oglasu. Ako auto ima malo kilometara, a enterijer deluje potrošeno, traži dodatno objašnjenje.

Clio 1.5 dCi ima smisla kada želiš mali štedljiv auto i kada možeš da potvrdiš njegovo održavanje. Ako voziš samo kratke gradske relacije, benzinac može biti mirniji izbor čak i kada troši više. Najbolji Clio nije najjeftiniji, nego onaj koji nema skrivenu cenu u prvim mesecima posle kupovine.
TEXT,
                'highlights' => [
                    'Clio 1.5 dCi je najjači kada se koristi na dužim ili mešovitim relacijama.',
                    'Servisna istorija i hladan start vrede više od obećanja o niskoj potrošnji.',
                    'Za kratke gradske relacije benzinac može biti mirnija i jeftinija odluka.',
                ],
                'tags' => ['Renault Clio', '1.5 dCi', 'mali auto', 'dizel'],
                'meta_title' => 'Polovni Renault Clio 1.5 dCi: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Renault Clio 1.5 dCi: potrošnja, servisna istorija, DPF, EGR, turbo i realni rizici.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(9),
                'palette' => ['#111827', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Uvezen auto iz EU: šta proveriti pre kapare i odlaska na pregled',
                'slug' => 'uvezen-auto-iz-eu-sta-proveriti-pre-kapare-i-odlaska-na-pregled',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Uvozni polovnjak može biti dobra prilika, ali kaparu ne treba davati dok ne proveriš poreklo, dokumentaciju, kilometražu i realan trošak registracije.',
                'content' => <<<'TEXT'
Uvezen auto iz EU često deluje kao najbolji put do bolje opreme, urednijih puteva i šireg izbora modela. To zaista može biti prednost, ali samo ako kupac proveri dokumentaciju pre nego što se zaleti na fotografije i opis oglasa. Kod uvoznih polovnjaka najskuplje greške se često dese pre pregleda, kada se kapara da prerano ili se ne razume šta tačno ulazi u cenu.

Prvo proveri da li je auto već ocarinjen i registrovan ili se prodaje u postupku uvoza. Cena koja izgleda niža može biti nepotpuna ako kupac tek treba da plati dažbine, homologaciju, prevod dokumentacije, registraciju ili transport. U oglasu mora biti jasno šta je završeno, a šta tek čeka novog vlasnika. Ako prodavac izbegava precizan odgovor, to je signal za oprez.

Drugi korak je istorija vozila. Traži servisnu dokumentaciju, broj šasije za proveru, prethodne račune i tragove oštećenja. Vozilo iz uvoza nije automatski bolje od domaćeg automobila. Dobar uvozni auto ima proverljivo poreklo, doslednu kilometražu i stanje koje se slaže sa dokumentacijom. Loš uvozni auto često ima lepe fotografije, ali slabu priču.

Posebno obrati pažnju na kilometražu. Auto koji je vozio autoput može imati veću kilometražu i bolje stanje od automobila koji je radio kratke gradske relacije. Zato broj na satu ne sme biti jedini filter. Gledaj istrošenost enterijera, kočnica, guma, stakla, farova i volana. Ako sve izgleda sveže, ali dokumentacija ne postoji, oprez je opravdan.

Kaparu daj tek kada znaš kome je daješ, šta dobijaš zauzvrat i pod kojim uslovima se vraća. Najbolje je da svaki dogovor bude pisan, sa jasnim opisom vozila, cenom, rokom i razlogom za eventualni povraćaj. Usmena obećanja su slaba zaštita kada se kasnije pojavi problem.

Uvozni auto ima smisla kada dobijaš bolji primerak, ne samo bolju priču. Pre puta ili pregleda napravi listu pitanja, proveri troškove i ne preskači nezavisan pregled. Ako prodavac žuri kupca ili traži kaparu pre osnovnih informacija, verovatno postoji bolji oglas.
TEXT,
                'highlights' => [
                    'Pre kapare mora biti jasno da li je auto ocarinjen, registrovan i šta ulazi u cenu.',
                    'Broj šasije, servisna dokumentacija i tragovi oštećenja su obavezni deo provere.',
                    'Uvozni auto nije automatski bolji od domaćeg ako poreklo nije proverljivo.',
                ],
                'tags' => ['uvoz automobila', 'EU polovnjak', 'kapara', 'dokumentacija'],
                'meta_title' => 'Uvezen auto iz EU: šta proveriti pre kupovine',
                'meta_description' => 'Praktičan vodič za proveru uvoznog polovnjaka iz EU: dokumentacija, kapara, kilometraža, carina, registracija i istorija vozila.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(8),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'DPF i EGR u gradu: kada dizel postaje loša računica',
                'slug' => 'dpf-i-egr-u-gradu-kada-dizel-postaje-losa-racunica',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dizel može biti štedljiv na otvorenom putu, ali kratke gradske relacije često prave uslove u kojima DPF i EGR postaju skuplji od uštede.',
                'content' => <<<'TEXT'
Dizel polovnjak mnogim kupcima deluje kao sigurna računica jer troši manje goriva. Problem je što potrošnja nije cela slika. Moderni dizeli imaju sisteme koji traže pravi režim vožnje, a gradske relacije od nekoliko kilometara često su najgore okruženje za njih. DPF i EGR nisu problem sami po sebi, već postaju problem kada auto stalno radi hladan, kratko i bez dovoljno otvorenog puta.

DPF filteru je potrebna temperatura i vreme da završi regeneraciju. Ako se auto svakodnevno vozi samo do posla, škole ili prodavnice, regeneracije se prekidaju, a filter se postepeno puni. Tada kupac koji je želeo malu potrošnju može dobiti lampicu na tabli, slabiji odziv motora i račun koji briše višemesečnu uštedu na gorivu.

EGR ventil takođe trpi kada je vožnja stalno kratka i spora. Naslage, nepravilan rad i greške u sistemu mogu se pojaviti kod zapuštenih automobila ili vozila koja nisu dobijala redovan servis. Zato polovan dizel ne treba kupovati samo zato što je popularan model. Treba pitati gde je vožen, koliko često je išao na otvoren put i šta je servisirano.

Ako prelaziš mnogo kilometara van grada, dizel i dalje može biti odličan izbor. Stabilna vožnja, duže relacije i redovno održavanje daju uslove u kojima dizel ima smisla. Ali ako je tvoja rutina hladan start, gužva, kratka relacija i parkiranje, benzinac ili hibrid često su mirnija odluka, čak i uz veću potrošnju.

Pre kupovine obavezno uradi dijagnostiku i probnu vožnju. Ne gledaj samo da li nema lampica. Proveri parametre, istoriju grešaka, dim, rad motora, temperaturu i ponašanje pri ubrzanju. Ako prodavac kaže da je sve rešeno, traži račun. Kod dizela rečenica bez dokaza ne vredi mnogo.

Najbolja računica je ona koja prati tvoje relacije. Dizel nije loš izbor, ali loš dizel za pogrešnog vozača jeste. Kada kupiš auto koji ne odgovara načinu vožnje, ne štediš gorivo, nego odlažeš trošak.
TEXT,
                'highlights' => [
                    'DPF i EGR najviše trpe kada se dizel stalno vozi kratko i hladan.',
                    'Dizel ima smisla za duže relacije i veću godišnju kilometražu.',
                    'Dijagnostika i servisni računi su obavezni pre kupovine modernog dizela.',
                ],
                'tags' => ['DPF', 'EGR', 'dizel', 'gradska vožnja'],
                'meta_title' => 'DPF i EGR u gradu: kada dizel nije dobra kupovina',
                'meta_description' => 'Objašnjenje zašto moderni dizel može biti loša računica u gradskoj vožnji: DPF, EGR, kratke relacije, dijagnostika i troškovi.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(7),
                'palette' => ['#1f2937', '#fb7185', '#f8fafc'],
            ],
            [
                'title' => 'Kilometraža nije dokaz: kako čitati stanje polovnog automobila',
                'slug' => 'kilometraza-nije-dokaz-kako-citati-stanje-polovnog-automobila',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mala kilometraža u oglasu može biti prednost, ali i zamka. Stvarno stanje se vidi kroz tragove korišćenja, servisne račune i doslednost cele priče.',
                'content' => <<<'TEXT'
Kilometraža je jedan od prvih filtera koje kupci koriste, ali nije dokaz da je auto dobar. Vozilo sa manje kilometara može biti lošije od automobila koji je prešao više, ako je održavano lošije, voženo kratko, zapušteno ili popravljano samo kada kvar već postane očigledan. Zato broj na satu treba tretirati kao početak provere, ne kao zaključak.

Prvi signal je enterijer. Volan, sedište vozača, pedale, ručica menjača, dugmići i bočni oslonci često pokazuju realan intenzitet korišćenja. Ako auto navodno ima malu kilometražu, a kabina izgleda umorno, traži objašnjenje. Nije svaki trag dokaz prevare, ali nesklad između priče i stanja mora smanjiti poverenje.

Drugi signal je servisna istorija. Računi, servisna knjižica, elektronski zapisi i dosledni datumi mnogo vrede. Auto sa 210.000 kilometara i jasnim servisima može biti bolja kupovina od auta sa 130.000 kilometara bez ikakvog traga održavanja. Kupac ne plaća samo kilometre, već i način na koji su ti kilometri napravljeni.

Treći signal je mehaničko stanje. Gume, kočnice, trap, kvačilo, menjač, motor i curenja pokazuju koliko ulaganja dolazi posle kupovine. Neki prodavci stave novu presvlaku, operu motor i naprave dobre fotografije, ali probna vožnja i pregled kod majstora brzo otkriju šta je stvarno.

Posebno oprezno gledaj automobile sa veoma malo kilometara za svoje godište. Takvi primerci postoje, ali treba da imaju jaču dokumentaciju, ne slabiju. Ako je automobil star deset godina i navodno vožen malo, moraš razumeti gde je stajao, kako je održavan i zašto se sada prodaje.

Kilometraža je važna, ali nije dovoljna. Najbolji polovnjak je onaj kod kog se broj na satu, stanje, dokumentacija i cena međusobno slažu. Ako se jedan deo priče ne uklapa, ne ignoriši ga zato što oglas izgleda povoljno.
TEXT,
                'highlights' => [
                    'Mala kilometraža bez dokumentacije nije jači dokaz od dobrog stanja i računa.',
                    'Enterijer često otkriva da li broj na satu ima smisla.',
                    'Najbolji signal je doslednost između stanja, istorije, cene i priče prodavca.',
                ],
                'tags' => ['kilometraža', 'servisna istorija', 'provera vozila', 'oglasi'],
                'meta_title' => 'Kilometraža nije dokaz: kako proveriti polovnjak',
                'meta_description' => 'Kako proceniti polovan auto bez oslanjanja samo na kilometražu: enterijer, servisna istorija, stanje, probna vožnja i cena.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(6),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Električni polovnjak u Srbiji: kome ima smisla, a kome još ne',
                'slug' => 'elektricni-polovnjak-u-srbiji-kome-ima-smisla-a-kome-jos-ne',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Polovan električni auto može biti odličan za kupca koji ima gde da puni i zna svoje relacije, ali nije univerzalna zamena za dizel ili benzinac.',
                'content' => <<<'TEXT'
Električni polovnjak sve češće ulazi u razmatranje, ali u Srbiji još nije kupovina za svakoga. Najvažnije pitanje nije samo koliko košta auto, već gde ga puniš, koliko dnevno voziš i da li možeš da živiš sa njegovim realnim dometom. Kupac koji ima kućno punjenje i predvidljive gradske relacije ima potpuno drugačiju računicu od kupca koji zavisi samo od javnih punjača.

Najveća prednost električnog polovnjaka je jednostavnija svakodnevica kada se puni kod kuće. Nema klasičnog servisa motora, gradska vožnja mu prija i trošak energije može biti dobar ako se punjenje planira pametno. Za vozača koji dnevno prelazi poznatu rutu, ne vuče prikolicu i ne putuje često bez plana, električni auto može biti vrlo prijatan.

Rizik je baterija, domet i infrastruktura. Pre kupovine treba proveriti stanje baterije, realan domet po godišnjem dobu, brzinu punjenja, dostupnost servisa i cenu eventualnih popravki. Deklarisani domet iz oglasa nije dovoljan. Važno je kako se auto ponaša pri tvojoj brzini, tvojoj temperaturi i tvojoj ruti.

Ako živiš u zgradi bez sigurnog punjenja, kupovina može postati komplikovana. Javni punjači pomažu, ali ako se svaki dan oslanjaš na njih, električni auto gubi deo praktičnosti. Isto važi za vozače koji često idu na duža putovanja bez mnogo fleksibilnosti. Tada benzin, hibrid ili dobar dizel mogu biti mirniji izbor.

Državne mere i subvencije mogu promeniti računicu, ali ih ne treba tretirati kao jedini razlog za kupovinu. Uvek proveri važeće uslove, dostupnost i rokove pre nego što uključiš subvenciju u budžet. Polovan električni auto treba da ima smisla i bez idealnog scenarija.

Električni polovnjak je najbolji za kupca sa punjenjem kod kuće, jasnim dnevnim relacijama i realnim očekivanjima. Ako prvo moraš da rešavaš punjenje, domet i servisnu podršku, možda još nije pravi trenutak. Kao i kod svakog polovnjaka, pobediće konkretan primerak, ne tehnologija sama po sebi.
TEXT,
                'highlights' => [
                    'Električni polovnjak najviše smisla ima uz kućno punjenje i predvidljive relacije.',
                    'Pre kupovine proveri stanje baterije, realan domet, brzinu punjenja i servisnu podršku.',
                    'Subvencije mogu pomoći, ali ne smeju biti jedini razlog za kupovinu.',
                ],
                'tags' => ['električni auto', 'EV', 'Srbija', 'punjenje'],
                'meta_title' => 'Električni polovnjak u Srbiji: kome se isplati',
                'meta_description' => 'Vodič za kupovinu polovnog električnog automobila u Srbiji: punjenje, baterija, realan domet, subvencije i svakodnevna upotreba.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(5),
                'palette' => ['#111827', '#10b981', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Toyota Yaris Hybrid: gradski hibrid koji traži mirnu istoriju',
                'slug' => 'polovni-toyota-yaris-hybrid-gradski-hibrid-koji-trazi-mirnu-istoriju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Yaris Hybrid je logičan izbor za gradsku vožnju, ali dobar primerak se ne bira samo po maloj potrošnji nego po bateriji, kočnicama i servisima.',
                'content' => <<<'TEXT'
Toyota Yaris Hybrid je jedan od onih polovnjaka koji na papiru deluje skoro idealno za grad. Troši malo, nema klasičan manuelni menjač, dobro podnosi stani-kreni ritam i lak je za parkiranje. Upravo zato je tražen, a kod traženih modela kupac mora biti pažljiviji jer dobra reputacija često podigne cenu i slabijim primercima.

## Zašto Yaris Hybrid ima smisla u gradu

Najveća prednost Yarisa Hybrid je jednostavna svakodnevica. Hibridni sistem pomaže u gradskoj vožnji, automatski prenos je prijatan i auto ne traži dizel režim vožnje da bi ostao zdrav. Za vozača koji najviše prelazi kratke gradske relacije, to je često mirnija kupovina od malog dizela sa DPF-om i EGR-om.

## Baterija, kočnice i servisna istorija

Ipak, hibrid ne znači da nema provere. Pre kupovine treba proveriti stanje hibridne baterije, servisnu istoriju, rad klima uređaja, stanje kočnica i trap. Hibridi često manje troše klasične kočnice zbog regeneracije, ali to ne znači da diskovi, čeljusti i ležajevi ne mogu biti zapušteni. Auto koji je dugo stajao ili je vožen samo kratko takođe može imati svoje tragove.

## Kilometraža i prethodna namena

Posebno obrati pažnju na poreklo i realnu kilometražu. Yaris Hybrid je često radio kao gradski auto, službeno vozilo ili vozilo za dostavu u nekim tržištima. Takav primerak ne mora biti loš ako je održavan, ali kabina, sedišta, volan i vrata treba da odgovaraju priči prodavca. Ako oglas tvrdi da je auto malo vožen, stanje enterijera mora to da potvrdi.

## Cena ne sme da se plati samo zbog oznake Hybrid

Cena je često najveći izazov. Kupci ponekad plate previše samo zato što piše Hybrid i Toyota. Dobar Yaris vredi više od prosečnog malog automobila, ali samo ako istorija i stanje opravdavaju cenu. Ako je razlika u ceni velika, uporedi ga sa benzinskim Yarisom, Hondom Jazz ili drugim manjim gradskim modelima.

Yaris Hybrid je najbolji za kupca koji želi mali, pouzdan i štedljiv gradski auto i spreman je da plati proverljiv primerak. Nije najbolji izbor za nekoga kome treba veliki gepek, česta otvorena putovanja ili najniža moguća kupovna cena. Kod ovog modela pametna kupovina je mirna istorija, ne samo mala potrošnja.

FAQ: Koliko traje baterija kod Toyota Yaris Hybrid?
Baterija može trajati dugo ako je auto pravilno korišćen i održavan, ali ne treba kupovati bez dijagnostike. Važnije je stanje konkretne baterije nego opšta reputacija Toyote.

FAQ: Da li je Toyota Yaris Hybrid dobar za autoput?
Može da vozi autoput, ali najviše smisla ima u gradu i mešovitoj vožnji. Ako često voziš duge brze relacije, obavezno proveri buku, potrošnju i komfor na probnoj vožnji.
TEXT,
                'highlights' => [
                    'Yaris Hybrid ima najviše smisla za gradsku vožnju i kratke relacije.',
                    'Pre kupovine obavezno proveri hibridnu bateriju, servisnu istoriju, kočnice i realnu namenu prethodnog korišćenja.',
                    'Dobra reputacija ne opravdava svaku cenu; uporedi stanje, ne samo oznaku Hybrid.',
                ],
                'tags' => ['Toyota Yaris Hybrid', 'hibrid', 'gradski auto', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Toyota Yaris Hybrid: baterija i provera',
                'meta_description' => 'Šta proveriti kod polovnog Toyota Yaris Hybrid: baterija, gradska vožnja, servisna istorija, kočnice, kilometraža i realna cena.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(4),
                'palette' => ['#0f172a', '#34d399', '#f8fafc'],
            ],
            [
                'title' => 'Automatski menjač kod polovnjaka: šta proveriti pre probne vožnje',
                'slug' => 'automatski-menjac-kod-polovnjaka-sta-proveriti-pre-probne-voznje',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Automatik može biti odličan u svakodnevnoj vožnji, ali polovan auto sa zapuštenim menjačem brzo pretvara udobnost u veliki račun.',
                'content' => <<<'TEXT'
Automatski menjač je mnogim kupcima postao poželjniji od manuelnog, posebno u gradu i na dužim relacijama. Udobniji je, lakši za vožnju i često čini automobil skupljim i privlačnijim. Ali kod polovnjaka automatik mora da se proverava strože, jer greške nisu uvek očigledne na fotografijama ili kratkom paljenju ispred zgrade.

## Servis ulja i istorija menjača

Prvo pitanje je servisna istorija menjača. Rečenica da je ulje "doživotno" ne znači mnogo kada kupuješ polovan auto sa godinama i kilometražom. Traži račun ili dokaz da je servis menjača rađen u preporučenom intervalu. Ako prodavac kaže da nema potrebe za servisom, to nije automatski dokaz problema, ali jeste razlog za dodatnu proveru.

## Hladna i topla probna vožnja

Probna vožnja mora biti hladna i topla. Hladan start često pokaže trzaje, kašnjenje pri ubacivanju u D ili R i nepravilan rad koji nestane kada se sistem zagreje. Tokom vožnje obrati pažnju na glatko menjanje brzina, proklizavanje, vibracije, udarce pri usporavanju i ponašanje u gužvi. Ne testira se samo ubrzanje, nego i normalna svakodnevna vožnja.

## DSG, CVT i klasični automatik nisu isti rizik

Različiti tipovi automatika imaju različite rizike. Klasični automatik, DSG, CVT i robotizovani menjači ne ponašaju se isto i ne koštaju isto za održavanje. Zato nije dovoljno da oglas kaže "automatik". Treba znati koji je tačno menjač u automobilu, šta je njegov tipičan problem i koliko košta servis u Srbiji.

## Kada opterećenje menja računicu

Ako auto vuče prikolicu, često se vozi u gradu ili ima mnogo snage, opterećenje menjača može biti veće. Isto važi za automobile koji su čipovani ili voženi agresivno. Menjač može raditi korektno na kratkoj vožnji, ali dijagnostika i pregled ulja mogu otkriti tragove koje prodavac ne pominje.

Automatik nije razlog da odustaneš od dobrog auta. Naprotiv, dobar menjač može učiniti svakodnevnu vožnju mnogo prijatnijom. Ali ako nema dokaza o održavanju, ako probna vožnja pokazuje trzaje ili ako cena deluje predobro, računaj menjač kao ozbiljan rizik u pregovorima.

FAQ: Kako proveriti automatski menjač pre kupovine?
Proveri servis ulja, hladno ubacivanje u D i R, ponašanje u gužvi, usporavanje, ubrzavanje, dijagnostiku i eventualne trzaje ili kašnjenja pri promeni brzina.

FAQ: Da li automatik bez servisne istorije treba kupiti?
Samo ako cena ostavlja dovoljno prostora za detaljnu proveru i preventivni servis, a probna vožnja je potpuno uredna. Kod trzaja ili kašnjenja bolje je odustati.
TEXT,
                'highlights' => [
                    'Servisna istorija menjača je važnija od tvrdnje da je ulje doživotno.',
                    'Probna vožnja treba da proveri hladan start, gužvu, usporavanje i ubacivanje u D/R.',
                    'Različiti automatici imaju različite rizike, pa proveri tačan tip menjača pre kupovine.',
                ],
                'tags' => ['automatski menjač', 'DSG', 'CVT', 'probna vožnja'],
                'meta_title' => 'Kako proveriti automatski menjač kod polovnjaka',
                'meta_description' => 'Vodič za proveru automatskog menjača pre kupovine polovnog auta: servis ulja, hladna probna vožnja, trzaji, DSG, CVT i troškovi.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(3),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Karavan ili SUV za porodicu: gde novac stvarno ima više smisla',
                'slug' => 'karavan-ili-suv-za-porodicu-gde-novac-stvarno-ima-vise-smisla',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'SUV deluje privlačnije, ali karavan često nudi više prostora i niže troškove. Prava odluka zavisi od porodice, relacija i budžeta za održavanje.',
                'content' => <<<'TEXT'
Porodični kupci često krenu od SUV-a jer deluje sigurnije, preglednije i modernije. Viša pozicija sedenja, lakši ulazak i popularnost SUV modela jesu realne prednosti. Ipak, kada se uporede prostor, potrošnja, gume, održavanje i cena, karavan često pokazuje da novac može raditi pametnije.

Karavan je obično bolji kada porodici treba veliki gepek, dobar komfor na putu i niža potrošnja. Isti budžet često kupuje mlađi ili bolje opremljen karavan nego SUV. Takođe, niži automobil uglavnom ima bolje ponašanje na autoputu, lakše gume i manju masu, što se vidi kroz troškove.

SUV ima smisla kada često ulaziš i izlaziš iz auta sa decom, kada su lošiji putevi deo svakodnevice ili kada ti viša pozicija zaista olakšava vožnju. Ali SUV nije automatski prostraniji. Neki kompaktni SUV modeli imaju manji gepek od dobrog karavana, a plaćaju se skuplje samo zbog forme.

Kod polovnjaka posebno gledaj gume, trap, pogon i potrošnju. Veće dimenzije guma mogu iznenaditi kupca koji je gledao samo cenu vozila. Ako SUV ima složeniji pogon, servis i kvarovi mogu biti skuplji. Karavan nije bez troškova, ali često ima manje skrivenih izdataka za isti nivo prostora.

Praktičan test je jednostavan: ponesi dečja sedišta, kolica ili stvari koje stvarno koristiš. Otvori gepek, proveri prag utovara, širinu zadnje klupe i pristup ISOFIX tačkama. Fotografije oglasa ne govore kako auto radi u tvojoj rutini.

Ako kupuješ zbog prostora i troškova, karavan često ima više smisla. Ako kupuješ zbog lakšeg ulaska, višeg sedenja i lošijih puteva, SUV može opravdati cenu. Najgore je platiti SUV zato što je popularan, a onda shvatiti da porodici zapravo treba veći gepek i niži mesečni trošak.
TEXT,
                'highlights' => [
                    'Karavan često nudi više prostora i niže troškove za isti budžet.',
                    'SUV ima smisla kada viša pozicija, ulazak i lošiji putevi stvarno rešavaju problem.',
                    'Pre odluke testiraj gepek, dečja sedišta, ISOFIX i realne porodične stvari.',
                ],
                'tags' => ['karavan', 'SUV', 'porodični auto', 'analiza tržišta'],
                'meta_title' => 'Karavan ili SUV za porodicu: šta je pametnije',
                'meta_description' => 'Poređenje karavana i SUV-a za porodicu: prostor, troškovi, potrošnja, gume, gepek, dečja sedišta i realna isplativost.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(2),
                'palette' => ['#0f172a', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'VIN izveštaj i servisna istorija: šta proveriti pre kapare',
                'slug' => 'vin-izvestaj-i-servisna-istorija-sta-proveriti-pre-kapare',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'VIN izveštaj nije čarobna garancija, ali može otkriti nesklad u kilometraži, oštećenja, uvoznu istoriju i detalje koje oglas prećuti.',
                'content' => <<<'TEXT'
VIN broj je jedna od najvažnijih polaznih tačaka pre kupovine polovnog automobila. Ne rešava sve, ne zamenjuje pregled kod majstora i ne dokazuje automatski da je auto dobar, ali može brzo pokazati da li se priča prodavca poklapa sa dostupnim tragovima. Zato VIN proveru treba uraditi pre kapare, ne posle dogovora.

Prvo proveri da li se VIN na dokumentima, šasiji i eventualno staklima slaže. Ako prodavac izbegava da pošalje VIN ili šalje nejasne fotografije, to je signal za oprez. Ozbiljan prodavac nema razlog da krije osnovni identitet vozila.

Drugo, gledaj kilometražu kroz vreme. Jedan unos ne znači mnogo, ali niz unosa može pokazati logiku. Ako kilometraža pada, dugo nestaje iz evidencije ili se ne uklapa sa godištem i stanjem, traži dodatno objašnjenje. Nekad je u pitanju greška u unosu, ali kupac ne treba da pretpostavi najbolji scenario bez dokaza.

Treće, obrati pažnju na štete, aukcije, uvoz i promene vlasništva. Auto koji je imao oštećenje ne mora biti loša kupovina ako je popravljen kvalitetno i cena to odražava. Problem je kada oglas tvrdi da je auto bez ulaganja, a istorija pokazuje ozbiljnu štetu ili nejasan uvoz.

Servisna istorija treba da dopuni VIN izveštaj. Računi, elektronski zapisi i servisna knjižica zajedno daju bolju sliku od jednog PDF izveštaja. Ako auto ima uredne servise, zamene velikih sklopova i logičan tok kilometraže, poverenje raste. Ako ima samo priču, poverenje ne treba da raste.

VIN izveštaj je filter. Koristi ga da odlučiš da li vredi ići na pregled, pregovarati ili odustati. Najbolji rezultat je kada se VIN, računi, stanje automobila i ponašanje prodavca međusobno slažu. Ako jedan deo priče odskače, kapara treba da sačeka.
TEXT,
                'highlights' => [
                    'VIN proveru uradi pre kapare, ne posle dogovora.',
                    'Najvažnije je da se kilometraža, štete, uvoz i servisni tragovi poklapaju sa pričom prodavca.',
                    'VIN izveštaj ne menja pregled kod majstora, ali pomaže da ne gubiš vreme na rizičan oglas.',
                ],
                'tags' => ['VIN', 'servisna istorija', 'provera vozila', 'kapara'],
                'meta_title' => 'VIN izveštaj i servisna istorija pre kupovine',
                'meta_description' => 'Kako koristiti VIN izveštaj pre kupovine polovnog automobila: kilometraža, štete, uvoz, servisna istorija i kapara.',
                'is_featured' => false,
                'published_at' => now()->subMinute(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Pregovaranje posle pregleda: kako spustiti cenu bez svađe',
                'slug' => 'pregovaranje-posle-pregleda-kako-spustiti-cenu-bez-svade',
                'category' => 'Pregovaranje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Najbolji pregovori počinju posle pregleda, kada umesto utiska imaš konkretne stavke: gume, servis, limariju, trap, kočnice i dokumentaciju.',
                'content' => <<<'TEXT'
Pregovaranje za polovan auto često krene pogrešno jer kupac pokušava da spusti cenu pre nego što zna šta kupuje. Mnogo jača pozicija dolazi posle pregleda, kada se razgovor ne vodi oko osećaja nego oko konkretnih stavki. Prodavac lakše prihvata argument ako vidi da ne napadaš auto, već računaš realna ulaganja.

Prvo razdvoji mane od normalnog korišćenja. Polovan auto ne može biti nov, pa sitne ogrebotine ili potrošenost ne znače automatski veliki popust. Ali gume pred zamenu, servis koji kasni, loše kočnice, curenje, greška na dijagnostici ili nejasna dokumentacija jesu stavke koje imaju novčanu vrednost.

Drugo, dođi sa brojkama. Ako majstor kaže da veliki servis košta određeni iznos, zapiši ga. Ako su potrebne četiri gume, proveri realnu cenu za tu dimenziju. Pregovor je mnogo mirniji kada kažeš: "Ovo su ulaganja koja moram odmah da uradim", umesto: "Auto mi deluje skup."

Treće, ne koristi svaku sitnicu kao razlog za rušenje cene. Ako nabrojiš deset nebitnih mana, prodavac može prestati da sluša. Bolje je izdvojiti tri do pet realnih troškova i na osnovu njih dati korektnu ponudu. Cilj nije da pobediš u raspravi, nego da kupiš auto po ceni koja ima smisla.

Ako je prodavac već postavio realnu cenu i auto je dobar, ne očekuj veliki popust. Dobar primerak vredi platiti, posebno ako je dokumentacija jasna. Sa druge strane, ako oglas stoji dugo, cena je viša od sličnih primeraka i pregled otkriva ulaganja, prostor za pregovor je mnogo veći.

Najbolje pregovaranje je mirno, konkretno i spremno na odustajanje. Ako prodavac ne želi da prihvati realne troškove ili pritiska da odmah uplatiš kaparu, to je informacija. Nije svaki auto vredan dogovora. Nekad je najbolji popust onaj koji dobiješ tako što ne kupiš pogrešan primerak.
TEXT,
                'highlights' => [
                    'Pregovaraj posle pregleda, kada imaš konkretne troškove umesto opšteg utiska.',
                    'Najbolji argumenti su gume, servis, kočnice, dijagnostika, trap i dokumentacija.',
                    'Mirna ponuda sa brojkama radi bolje od nabrajanja svake sitne mane.',
                ],
                'tags' => ['pregovaranje', 'pregled vozila', 'cena', 'kapara'],
                'meta_title' => 'Kako pregovarati posle pregleda polovnog auta',
                'meta_description' => 'Praktičan vodič za pregovaranje posle pregleda polovnog automobila: ulaganja, gume, servis, kočnice, argumenti i realna ponuda.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#fb7185', '#f8fafc'],
            ],
            [
                'title' => 'Ford Kuga ili Nissan Qashqai 2022-2023: koji SUV je pametnija kupovina',
                'slug' => 'ford-kuga-ili-nissan-qashqai-2022-2023-koji-suv-je-pametnija-kupovina',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kuga i Qashqai iz 2022. i 2023. deluju kao slični porodični SUV izbori, ali se razlikuju po prostoru, pogonima, potrošnji i riziku kod polovnih primeraka.',
                'content' => <<<'TEXT'
Ford Kuga i Nissan Qashqai iz 2022. i 2023. često ulaze u isti uži izbor jer kupac želi noviji SUV, povišenu poziciju sedenja, dovoljno prostora za porodicu i motor koji neće praviti nervozu u gradu. Ipak, ovo nisu potpuno isti automobili. Kuga je veća, šira i bliža klasi porodičnog SUV-a za duže relacije, dok je Qashqai kompaktniji, lakši za grad i često jednostavniji za svakodnevno parkiranje.

## Ford Kuga je jača kada prostor odlučuje

Kuga ima prednost kada ti je prostor visoko na listi. Duže međuosovinsko rastojanje, veći osećaj automobila i klizna zadnja klupa čine je praktičnijom za porodicu koja često putuje, nosi kolica, torbe ili dečja sedišta. Kod polovnih primeraka 2022-2023 najviše pažnje zaslužuju hibridne verzije, posebno 2.5 FHEV i PHEV. Plug-in Kuga može biti odlična ako je prethodni vlasnik stvarno punio bateriju i ako ti imaš gde da puniš, ali nema smisla platiti PHEV cenu ako će auto stalno raditi kao težak benzinac.

## Nissan Qashqai je lakši za grad

Qashqai je bolji izbor ako ti treba kompaktniji SUV za grad i prigradsku vožnju. Treća generacija donela je moderniji enterijer, dobru bezbednosnu opremu i izbor između 1.3 mild hybrid motora i e-POWER pogona. e-POWER je posebno zanimljiv jer se vozi kao električni auto, ali se ne puni na utičnici. Benzinski motor uglavnom služi kao generator, a to znači mirniji osećaj u gradu i dobar odziv, ali kupac mora da razume da to nije plug-in hibrid i da nema isti električni domet kao Kuga PHEV.

## Potrošnja zavisi od punjenja i relacija

Kod poređenja potrošnje ne gledaj samo fabričke brojke. Kuga PHEV može biti izuzetno štedljiva ako se redovno puni i vozi kratke relacije, ali na autoputu i bez punjenja prednost se smanjuje. Kuga FHEV je jednostavnija za kupca koji ne želi kabl i punjenje. Qashqai e-POWER je prijatan za grad, ali na bržem otvorenom putu treba proveriti realnu potrošnju, buku i ponašanje sistema. Običan 1.3 mild hybrid Qashqai je najjednostavniji za razumevanje, ali nije tako poseban u vožnji kao e-POWER.

## Bezbednost i oprema se proveravaju na konkretnom autu

Bezbednost je jaka strana oba modela, ali proveri konkretan paket opreme. Qashqai treće generacije ima veoma dobre Euro NCAP rezultate iz 2021. godine, dok je aktuelna generacija Kuge ocenjena sa pet zvezdica u ranijem testu. Ipak, kod polovnjaka nije dovoljno da model ima dobar rezultat. Bitno je da konkretan auto nema ozbiljna oštećenja, da su radari i kamere ispravni i da posle eventualne popravke sistemi asistencije rade kako treba.

## Šta proveriti kod uvoza iz EU

Troškovi mogu prelomiti odluku. Kuga je veća i često skuplja za gume, osiguranje, potrošnju i eventualne hibridne provere. Qashqai je kompaktniji i lakši za svakodnevicu, ali e-POWER primerci mogu držati višu cenu zbog tehnologije i novijeg imidža. Ako kupuješ uvoz iz EU, obavezno proveri VIN, servisnu istoriju, stanje baterije kod hibrida, rad menjača ili električnog pogona, tragove oštećenja i da li oprema iz oglasa stvarno postoji na automobilu.

Kuga je pametnija kupovina za porodicu kojoj prostor, gepek, udobnost i mogućnost plug-in vožnje stvarno znače. Qashqai je pametniji za kupca koji želi noviji, pregledan i lakši SUV za grad, uz moderan hibridni osećaj kod e-POWER verzije. Ako su oba automobila iste cene, nemoj birati po znački ili fotografijama. Biraj onaj primerak koji ima jasniju istoriju, manje nepoznanica i pogon koji odgovara tvojoj svakodnevici.

FAQ: Da li je bolji Ford Kuga PHEV ili Nissan Qashqai e-POWER?
Kuga PHEV je bolja ako imaš gde da puniš i često voziš kraće relacije. Qashqai e-POWER je jednostavniji ako želiš hibridni osećaj bez punjenja na utičnici.

FAQ: Šta proveriti kod Ford Kuga ili Qashqai iz uvoza?
Proveri VIN, servisnu istoriju, stanje hibridnog sistema, rad kamera i radara, tragove oštećenja, gume, kočnice i da li oprema iz oglasa stvarno postoji.
TEXT,
                'highlights' => [
                    'Kuga je bolji izbor za porodicu, duže relacije i kupce koji stvarno mogu da koriste PHEV punjenje.',
                    'Qashqai je lakši za grad, posebno kao 1.3 mild hybrid ili e-POWER za vozače koji žele mirniji hibridni osećaj bez punjenja na utičnici.',
                    'Kod oba modela proveri VIN, servisnu istoriju, hibridni sistem, rad asistencija i tragove oštećenja pre kapare.',
                ],
                'tags' => ['Ford Kuga', 'Nissan Qashqai', 'SUV 2022', 'SUV 2023', 'hibrid'],
                'meta_title' => 'Ford Kuga ili Nissan Qashqai 2022/2023: SUV vodič',
                'meta_description' => 'Poređenje Ford Kuga i Nissan Qashqai 2022/2023 polovnjaka: prostor, PHEV, FHEV, e-POWER, potrošnja, oprema i provera.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Hyundai Tucson 2021-2023: šta proveriti kod hibrida i dizela',
                'slug' => 'polovni-hyundai-tucson-2021-2023-sta-proveriti-kod-hibrida-i-dizela',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Tucson novije generacije deluje kao sigurna porodična kupovina, ali razlika između benzinca, dizela, hibrida i plug-in hibrida menja celu računicu.',
                'content' => <<<'TEXT'
Hyundai Tucson iz 2021, 2022. i 2023. godine često deluje kao odličan polovnjak za porodicu: moderan je, prostran, dobro opremljen i ima širok izbor pogona. Upravo zato kupac ne treba da ga posmatra kao jedan model sa jednom računicom. Benzinac, dizel, hibrid i plug-in hibrid mogu izgledati slično na oglasu, ali imaju različite prednosti, troškove i rizike.

Ako gledaš 1.6 T-GDi benzinac, fokus je na servisnoj istoriji, hladnom startu, radu turbine i realnoj potrošnji. To je logičan izbor za vozače koji ne prelaze ogromnu kilometražu i ne žele dizel komplikacije u gradu. Kod polovnog primerka posebno proveri da li su servisi rađeni na vreme, jer turbo benzinac ne voli produžene intervale i jeftino održavanje.

Dizel Tucson ima smisla za vozača koji često ide otvorenim putem i prelazi veću godišnju kilometražu. Ako auto većinu vremena provodi u gradu, računica se brzo menja. DPF, EGR i kratke relacije mogu pretvoriti dobru potrošnju u skupe posete servisu. Zato kod dizela obavezno proveri način prethodne vožnje, ne samo broj kilometara.

Hibrid i plug-in hibrid traže drugačiju proveru. Kod običnog hibrida gledaj stanje baterije, kočnice, trap i da li se sistem ponaša mirno u stani-kreni vožnji. Kod plug-in hibrida proveri kablove, punjenje, realan električni domet i navike prethodnog vlasnika. PHEV ima najviše smisla ako ga stvarno puniš kod kuće ili na poslu; bez punjenja postaje skuplji i teži benzinac.

Tucson ume da bude bogato opremljen, ali oprema nije zamena za stanje. Panoramski krov, veliki ekrani, asistencije i automatski menjač treba proveriti jednako ozbiljno kao motor. Ako je automobil uvezen, traži VIN izveštaj, račune i dokaz o kilometraži. Noviji SUV sa lepim ekranom i dobrim fotografijama ne mora biti uredan primerak.

Najbolji Tucson je onaj čiji pogon odgovara tvojoj rutini. Benzinac za mešovitu vožnju i jednostavniju kupovinu, dizel za duge relacije, hibrid za grad i prigradsku vožnju, PHEV samo ako imaš naviku punjenja. Kada se pogon, istorija i cena poklope, Tucson može biti vrlo dobra porodična kupovina.
TEXT,
                'highlights' => [
                    'Benzinac, dizel, hibrid i PHEV Tucson nemaju istu računicu ni iste rizike.',
                    'PHEV ima smisla samo ako se redovno puni; bez punjenja gubi glavnu prednost.',
                    'Kod uvoza proveri VIN, servisnu istoriju, bateriju kod hibrida i rad asistencija.',
                ],
                'tags' => ['Hyundai Tucson', 'SUV', 'hibrid', 'dizel', 'PHEV'],
                'meta_title' => 'Polovni Hyundai Tucson 2021-2023: vodič za kupovinu',
                'meta_description' => 'Kako kupiti polovni Hyundai Tucson 2021-2023: benzinac, dizel, hibrid, PHEV, servisna istorija, baterija i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#2dd4bf', '#f8fafc'],
            ],
            [
                'title' => 'Mazda CX-5 ili Toyota RAV4: koji SUV je mirnija kupovina',
                'slug' => 'mazda-cx-5-ili-toyota-rav4-koji-suv-je-mirnija-kupovina',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'CX-5 i RAV4 privlače kupce koji ne žele premium troškove, ali žele ozbiljan SUV. Razlika je u pogonu, potrošnji, prostoru i osećaju u vožnji.',
                'content' => <<<'TEXT'
Mazda CX-5 i Toyota RAV4 često se porede kada kupac želi ozbiljan porodični SUV, ali ne želi da ulazi u premium troškove BMW-a, Audija ili Mercedesa. Oba modela imaju jak ugled, dobar osećaj kvaliteta i relativno dobru kasniju prodaju. Ipak, kupuju se iz različitih razloga i ne treba ih birati samo po ceni ili godištu.

Mazda CX-5 je bolja za vozača koji ceni osećaj u vožnji, dobar enterijer i klasičniji karakter automobila. Benzinske verzije su često zanimljive kupcima koji žele da izbegnu dizel rizike, ali treba prihvatiti da potrošnja može biti viša nego kod hibridne Toyote. Kod Mazde proveri servisnu istoriju, koroziju na donjim delovima kod uvoznih primeraka, trap, gume i stanje automatskog menjača ako ga ima.

Toyota RAV4 je racionalnija kada želiš hibridnu svakodnevicu, mirniju gradsku vožnju i niži rizik od dizel komplikacija. Hibridni pogon je glavna prednost, ali ne znači da auto ne treba pregledati. Proveri stanje baterije, kočnice, trap, servisne zapise i da li je auto bio porodično vozilo, rent-a-car ili službeni automobil sa mnogo kratkih vožnji.

Po prostoru su oba modela ozbiljna, ali RAV4 često deluje praktičnije za porodicu kojoj su gepek i zadnja klupa prioritet. CX-5 uzvraća boljim osećajem u kabini i vožnji. Ako često putuješ autoputem, probaj oba modela na bržoj vožnji, jer buka, sedišta i menjač mogu presuditi više od brojeva iz kataloga.

Cena polovnog RAV4 hibrida često je visoka zato što Toyota dobro drži vrednost. To ne znači da je svaki primerak vredan tražene cene. Mazda može ponuditi bolji odnos opreme i cene, ali samo ako je stanje dobro. Kod oba modela jeftin primerak obično traži objašnjenje, posebno ako je uvezen i nema jasnu dokumentaciju.

RAV4 je mirnija kupovina za vozača koji želi hibridnu pouzdanost, praktičnost i jaku kasniju prodaju. CX-5 je bolja ako želiš više vozačkog karaktera, lepši enterijer i spreman si da prihvatiš drugačiju potrošnju. Najpametnija odluka nije model sa boljim ugledom, nego primerak sa manje nepoznanica.
TEXT,
                'highlights' => [
                    'RAV4 ima prednost za kupce koji žele hibridnu svakodnevicu i jaku kasniju prodaju.',
                    'CX-5 ima smisla ako želiš bolji osećaj u vožnji i često bolji odnos opreme i cene.',
                    'Kod oba modela proveri istoriju, trap, gume, automatik i realnu upotrebu prethodnog vlasnika.',
                ],
                'tags' => ['Mazda CX-5', 'Toyota RAV4', 'porodični SUV', 'hibrid'],
                'meta_title' => 'Mazda CX-5 ili Toyota RAV4: koji polovni SUV kupiti',
                'meta_description' => 'Poređenje polovnih Mazda CX-5 i Toyota RAV4 SUV modela: hibrid, benzinac, prostor, potrošnja, održavanje i realna vrednost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Peugeot 2008 ili Renault Captur: mali crossover za grad i porodicu',
                'slug' => 'peugeot-2008-ili-renault-captur-mali-crossover-za-grad-i-porodicu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Peugeot 2008 i Renault Captur ciljaju isti deo tržišta, ali razlika u kabini, motorima i praktičnosti može promeniti odluku.',
                'content' => <<<'TEXT'
Peugeot 2008 i Renault Captur su čest izbor za kupce koji žele povišenu poziciju sedenja, moderan izgled i auto koji nije prevelik za grad. Oba modela nude više praktičnosti od klasičnog malog hečbeka, ali nisu zamena za veliki porodični SUV. Zato ih treba gledati kroz svakodnevicu: parking, dečje sedište, potrošnju, gepek i servisnu mrežu.

Peugeot 2008 privlači kupce dizajnom, enterijerom i modernim i-Cockpit rasporedom. Nekome će taj volan i instrument tabla savršeno odgovarati, a nekome nikako. Pre kupovine obavezno sedi, podesi volan i sedište i proveri da li jasno vidiš instrumente. Kod polovnih primeraka proveri 1.2 PureTech motor, servisni ritam, stanje kaiša gde je primenljivo, turbinu i eventualne greške elektronike.

Renault Captur je često praktičniji i mekši izbor. Ima dobar ulazak, solidan gepek za klasu i kabinu koja je manje specifična od Peugeotovog rasporeda. Kod Captura proveri servisnu istoriju, rad menjača, stanje elektronike, trap i da li je auto imao mnogo gradske vožnje. Ako gledaš E-Tech hibrid, obavezno proveri ponašanje pogona u sporoj vožnji i servisne zapise.

Za gradsku vožnju oba modela imaju smisla, ali ne kupuj crossover samo zato što izgleda veće. Proveri zadnju klupu, širinu vrata, ISOFIX, prag gepeka i preglednost. Ako porodica često putuje, možda će već kompaktni SUV biti bolja opcija. Ako auto najviše služi za grad, 2008 i Captur su taman dovoljno veliki bez svakodnevnog nerviranja oko parkinga.

Troškovi mogu biti vrlo različiti. Peugeot može držati cenu zbog dizajna i novijeg utiska, dok Captur često dobija poene na praktičnosti i dostupnosti primeraka. Kod oba modela, oprema ne sme da sakrije slab servisni trag. Automatska klima, veliki ekran i lepe felne vrede malo ako motor, menjač ili elektronika traže ulaganja odmah posle kupovine.

Peugeot 2008 je bolji za kupca kome odgovara kabina, želi upečatljiv izgled i nalazi primerak sa dokazanim održavanjem. Renault Captur je mirniji izbor za svakodnevicu, posebno ako tražiš praktičnost i jednostavniji raspored komandi. Presudi probna vožnja i pregled, ne fotografije iz oglasa.
TEXT,
                'highlights' => [
                    'Kod Peugeota 2008 obavezno proveri da li ti odgovara i-Cockpit položaj za volanom.',
                    'Captur često dobija poene na praktičnosti, ulasku i svakodnevnoj upotrebi.',
                    'Kod oba modela proveri servisni ritam, elektroniku, trap i realnu gradsku potrošnju.',
                ],
                'tags' => ['Peugeot 2008', 'Renault Captur', 'mali crossover', 'grad'],
                'meta_title' => 'Peugeot 2008 ili Renault Captur: šta kupiti polovno',
                'meta_description' => 'Poređenje polovnih Peugeot 2008 i Renault Captur modela: grad, porodica, motori, elektronika, gepek, potrošnja i praktičnost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Pregled kod majstora pre kupovine: šta tražiti da ne promakne',
                'slug' => 'pregled-kod-majstora-pre-kupovine-sta-traziti-da-ne-promakne',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Pregled polovnog auta nije samo dizalica i dijagnostika. Dobar pregled mora da poveže stanje, istoriju, probnu vožnju i realna ulaganja.',
                'content' => <<<'TEXT'
Pregled kod majstora pre kupovine je jedan od najboljih načina da izbegneš skup promašaj, ali samo ako znaš šta tražiš. Mnogi kupci odvezu auto na brz pogled, čuju da "deluje dobro" i završe sa računom koji nisu planirali. Dobar pregled mora biti strukturisan: dokumentacija, karoserija, mehanika, dijagnostika, probna vožnja i procena ulaganja.

Prvo proveri papire i identitet vozila. VIN na dokumentima mora da se slaže sa vozilom, servisna istorija treba da ima logiku, a kilometraža mora da prati stanje enterijera. Ako već tu postoje rupe, majstorov pregled ne treba da služi da opravda kupovinu, već da potvrdi da li uopšte ima smisla nastaviti.

Drugo, karoserija govori mnogo. Traži merenje laka, proveru zazora, nosača, podova, pragova i tragova nestručne popravke. Auto koji je bio udaren ne mora biti loš ako je popravljen kako treba, ali moraš znati šta plaćaš. Najveći problem je kada prodavac tvrdi da je auto bez oštećenja, a pregled pokaže drugačiju priču.

Treće, mehanika se ne proverava samo na leru. Hladan start, curenja, rad menjača, kvačilo, trap, kočnice, gume, klima i temperatura motora moraju biti deo pregleda. Kod dizela dodaj DPF, EGR, turbinu i dizne. Kod hibrida proveri bateriju i ponašanje sistema. Kod automatika traži probu hladnog i toplog rada.

Dijagnostika je važna, ali nije čarobna. Greške mogu biti obrisane, a neki problemi se ne vide bez probne vožnje. Zato pregled mora da uključi vožnju po gradu, usporavanje, ubrzavanje, skretanje, neravnine i parkiranje. Zvukovi, vibracije i trzaji često se pojave tek kada auto izađe iz dvorišta prodavca.

Na kraju traži listu ulaganja u novcu. Nije dovoljno da majstor kaže da "ima sitnica". Gume, veliki servis, kočnice, amortizeri, curenja i greške na dijagnostici treba pretvoriti u okviran iznos. Tek tada znaš da li cena ima smisla i koliko prostora imaš za pregovor.
TEXT,
                'highlights' => [
                    'Dobar pregled spaja dokumentaciju, karoseriju, mehaniku, dijagnostiku i probnu vožnju.',
                    'Dijagnostika nije dovoljna ako nema hladnog starta i realne probne vožnje.',
                    'Najvažniji rezultat pregleda je lista ulaganja izražena u novcu.',
                ],
                'tags' => ['pregled vozila', 'majstor', 'dijagnostika', 'provera vozila'],
                'meta_title' => 'Pregled kod majstora pre kupovine polovnog auta',
                'meta_description' => 'Šta tražiti na pregledu kod majstora pre kupovine polovnog automobila: VIN, karoserija, dijagnostika, probna vožnja i ulaganja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Gume na polovnom automobilu: skriveni trošak koji menja cenu',
                'slug' => 'gume-na-polovnom-automobilu-skriveni-trosak-koji-menja-cenu',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Gume često deluju kao sitnica u oglasu, ali set dobrih guma može ozbiljno promeniti realnu cenu polovnog automobila.',
                'content' => <<<'TEXT'
Gume su jedan od najlakše zanemarenih troškova kod kupovine polovnog automobila. Kupac gleda kilometražu, opremu, motor i cenu, a onda tek posle kupovine shvati da treba odmah da plati četiri nove gume. Kod većih felni, SUV modela i premium dimenzija taj trošak može ozbiljno promeniti računicu.

Prvo proveri dimenziju guma. Nije isto kupiti set za mali gradski auto i set za SUV sa 19 ili 20 inča. Ako auto ima atraktivne felne, cena guma često raste. To ne znači da takav auto treba izbegavati, ali taj trošak mora biti deo pregovora. Oglas sa lepim felnama nije povoljan ako te odmah čeka skup set pneumatika.

Drugo, proveri DOT, dubinu šare i ravnomerno trošenje. Stara guma sa dovoljno šare može biti loša zbog starosti, tvrdoće ili pukotina. Neravnomerno trošenje može ukazivati na problem sa trapom, geometrijom ili amortizerima. Ako su prednje i zadnje gume različitog stanja, pitaj zašto i gledaj širu sliku automobila.

Treće, sezona menja računicu. Ako kupuješ auto pred zimu sa lošim zimskim gumama, trošak dolazi odmah. Ako prodavac kaže da ima "još jedan set", proveri dimenziju, stanje, felne i starost. Dva seta starih ili loših guma nisu bonus, već problem koji je samo podeljen na više točkova.

Gume takođe govore o prethodnom vlasniku. Kvalitetan i odgovarajući set često pokazuje da auto nije održavan samo minimalno. Najjeftinije gume, pogrešne dimenzije ili istrošen set na skupom automobilu mogu biti signal da je vlasnik štedeo i na drugim stvarima. To nije dokaz kvara, ali jeste razlog za oprez.

Pre ponude proveri realnu cenu guma za tu dimenziju i dodaj montažu, balansiranje i eventualnu geometriju trapa. Ako ulaganje dolazi odmah, koristi ga kao konkretan argument u pregovoru. Gume nisu kozmetika. One direktno utiču na bezbednost, kočenje, potrošnju i stvarnu cenu automobila.
TEXT,
                'highlights' => [
                    'Dimenzija guma može značajno promeniti realan trošak kupovine polovnjaka.',
                    'DOT, dubina šare i neravnomerno trošenje često otkrivaju više od samog oglasa.',
                    'Ako gume traže zamenu odmah, taj iznos mora ući u pregovor o ceni.',
                ],
                'tags' => ['gume', 'troškovi', 'pregovaranje', 'održavanje'],
                'meta_title' => 'Gume na polovnom automobilu: skriveni trošak',
                'meta_description' => 'Kako gume menjaju realnu cenu polovnog automobila: DOT, dimenzija, zimske i letnje gume, trošenje, trap i pregovaranje.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f43f5e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volkswagen ID.3: električni kompakt za grad i punjenje kod kuće',
                'slug' => 'polovni-volkswagen-id3-elektricni-kompakt-za-grad-i-punjenje-kod-kuce',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'ID.3 može biti vrlo zanimljiv električni polovnjak, ali samo ako ti način punjenja, domet i stanje baterije stvarno odgovaraju.',
                'content' => <<<'TEXT'
Volkswagen ID.3 je jedan od prvih električnih modela koji sve češće ulazi u pretragu polovnih automobila kod kupaca koji žele moderan kompakt, tihu vožnju i niže troškove gradske upotrebe. Na papiru deluje jednostavno: nema klasičan motor, nema menjač kao dizel ili benzinac i troši manje po kilometru. U praksi, električni polovnjak traži drugačiju proveru i drugačiju računicu.

Prvo pitanje nije cena automobila, nego punjenje. ID.3 ima najviše smisla ako imaš sigurno mesto za punjenje kod kuće, u garaži ili na poslu. Ako se oslanjaš samo na javne punjače, svakodnevica može postati komplikovanija nego što izgleda u oglasu. Pre kupovine izračunaj koliko često voziš, gde puniš i koliko ti zaista treba dometa zimi, leti i na autoputu.

Drugo, proveri bateriju i istoriju korišćenja. Kod električnog auta kilometraža nije jedini trag stanja. Važno je kako je auto punjen, da li je često korišćeno brzo punjenje, kakav je realan domet i da li servis može očitati stanje baterije. Ako prodavac ne zna da objasni osnovne stvari oko punjenja i dometa, pregled mora biti još ozbiljniji.

Treće, ne zaboravi klasičan deo automobila. Električni pogon ne znači da su gume, trap, kočnice, klima, elektronika i karoserija nebitni. ID.3 je relativno težak za kompakt, pa gume i trap mogu pokazati kako je vožen. Kočnice se kod električnih auta često manje koriste zbog rekuperacije, ali ih zato treba proveriti zbog korozije, neravnomernog trošenja i stajanja.

Enterijer i softver su poseban deo provere. Proveri ekran, komande, rad klime, grejanje, asistencije, aplikaciju ako se koristi i da li su ažuriranja urađena. Električni auto može mehanički delovati jednostavno, ali loš softverski ili elektronski problem može biti naporan za rešavanje, posebno ako nemaš servisnu podršku koja poznaje model.

ID.3 je dobar izbor za vozača koji već ima rutinu pogodnu za električni auto: grad, prigradske relacije, kućno punjenje i realna očekivanja o dometu. Nije idealan ako često ideš na duga neplanirana putovanja, nemaš gde da puniš ili kupuješ samo zato što je električni auto postao jeftiniji kao polovnjak. Najbolji ID.3 nije najjeftiniji u oglasima, nego onaj sa jasnom istorijom, dobrom baterijom i načinom upotrebe koji se uklapa u tvoj život.
TEXT,
                'highlights' => [
                    'ID.3 ima najviše smisla ako možeš redovno da puniš kod kuće ili na poslu.',
                    'Kod električnog polovnjaka proveri stanje baterije, realan domet i istoriju punjenja.',
                    'Ne preskači gume, trap, kočnice, softver i elektroniku samo zato što auto nema klasičan motor.',
                ],
                'tags' => ['Volkswagen ID.3', 'električni auto', 'baterija', 'punjenje'],
                'meta_title' => 'Polovni Volkswagen ID.3: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Volkswagen ID.3: punjenje kod kuće, stanje baterije, realan domet, softver, gume i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Prvi auto za novog vozača: kako izabrati bez skupih početničkih grešaka',
                'slug' => 'prvi-auto-za-novog-vozaca-kako-izabrati-bez-skupih-pocetnickih-gresaka',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Prvi auto ne treba da impresionira komšiluk, nego da bude pregledan, razumljiv za održavanje i dovoljno jeftin da greška ne boli previše.',
                'content' => <<<'TEXT'
Kupovina prvog automobila je često emotivna odluka. Novi vozač želi auto koji lepo izgleda, ima dobru opremu i ne deluje kao kompromis. Problem je što prvi auto obično trpi najviše početničkih grešaka: loše parkiranje, naglo kočenje, zaboravljene sitnice, gradske relacije i učenje kroz svakodnevicu. Zato prvi kriterijum ne treba da bude prestiž, nego jednostavnost.

Najbolji prvi auto je onaj koji je pregledan, relativno jeftin za održavanje i dovoljno čest na tržištu da delovi nisu problem. Mali gradski auto ili jednostavan kompakt često imaju više smisla od starog premium modela. Stariji BMW, Audi ili Mercedes može delovati privlačno u oglasu, ali osiguranje, gume, trap, menjač i neplanirani kvarovi brzo pokažu zašto je cena pala.

Motor treba birati prema realnoj vožnji. Ako će auto uglavnom voziti grad, kratke relacije i male godišnje kilometraže, jednostavniji benzinac je često mirniji izbor od dizela. Dizel može trošiti manje, ali DPF, EGR i kratke relacije nisu prijatelji početničkog budžeta. Prvi auto treba da oprašta, ne da traži posebnu rutinu.

Bezbednost je važna, ali ne samo kroz broj zvezdica iz testa. Proveri gume, kočnice, svetla, brisače, sedišta, pojaseve, vazdušne jastuke, stanje karoserije i da li auto vuče u stranu. Auto koji je bio loše popravljan posle sudara nije dobra škola, čak i ako je lep na fotografijama. Za novog vozača stabilan i ispravan automobil vredi više od opreme.

Troškovi posle kupovine moraju biti deo budžeta. Prvi servis, registracija, osiguranje, gume, akumulator i sitne popravke često dođu odmah. Ako sav novac ode na kupovinu, svaka lampica postaje stres. Bolje je kupiti skromniji auto i ostaviti rezervu nego uzeti skuplji primerak koji već prvog meseca traži ulaganja.

Prvi auto ne mora biti dosadan, ali mora biti razuman. Biraj primerak sa jasnom istorijom, jednostavnom mehanikom i stanjem koje majstor može da potvrdi. Ako novi vozač nauči da kupuje po stanju, a ne po znaku na haubi, svaka sledeća kupovina biće bolja.
TEXT,
                'highlights' => [
                    'Prvi auto treba da bude jednostavan, pregledan i jeftin za održavanje.',
                    'Za gradsku vožnju i male kilometraže benzinac je često mirniji izbor od dizela.',
                    'U budžet odmah uračunaj servis, registraciju, gume, osiguranje i početna ulaganja.',
                ],
                'tags' => ['prvi auto', 'nov vozač', 'kupovina polovnjaka', 'budžet'],
                'meta_title' => 'Prvi auto za novog vozača: kako pametno izabrati',
                'meta_description' => 'Kako izabrati prvi polovni auto za novog vozača: budžet, benzinac ili dizel, bezbednost, održavanje i početničke greške.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa plinom: kada se isplati, a kada je rizik veći od uštede',
                'slug' => 'auto-sa-plinom-kada-se-isplati-a-kada-je-rizik-veci-od-ustede',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Plin može smanjiti trošak vožnje, ali samo ako je sistem ugrađen kvalitetno, održavan redovno i odgovara motoru koji kupuješ.',
                'content' => <<<'TEXT'
Auto sa plinom često deluje kao prečica do jeftinije vožnje. Ako prelaziš mnogo kilometara, razlika u ceni goriva može biti značajna, posebno kod benzinskih motora koji dobro podnose LPG. Ali polovan auto sa plinom nije automatski dobra kupovina. Ušteda postoji samo ako je sistem ugrađen kvalitetno, održavan redovno i ako motor nije već platio cenu loše instalacije.

Prvo proveri dokumentaciju plinskog uređaja. Ugradnja mora biti upisana i tehnički ispravna, boca mora imati važeći atest, a servisni računi treba da pokažu da su filteri i podešavanja rađeni na vreme. Ako prodavac kaže da "sve radi" ali nema papire, rizik prelazi na kupca. Kod plina je papirologija deo tehničkog stanja, ne formalnost.

Drugo, proveri kako motor radi na oba goriva. Auto mora mirno paliti, lepo raditi na benzinu i plinu, bez trzanja, gašenja, lampica i čudnog mirisa. Ako motor loše radi na benzinu, plin neće rešiti problem. Naprotiv, može ga sakriti dok ne postane skuplji. Probna vožnja treba da uključi hladan start, prebacivanje goriva i vožnju pod opterećenjem.

Treće, nije svaki motor jednako dobar za plin. Neki motori odlično podnose LPG, dok drugi traže dodatnu pažnju, češću kontrolu ventila ili jednostavno nisu idealan izbor. Pre kupovine proveri iskustva za konkretan motor, ne samo za model automobila. Isti model može imati više različitih motora i potpuno različitu plinsku računicu.

Trošak nije samo gorivo. Plinski sistem traži servis, filtere, eventualne dizne, isparivač, podešavanje i periodične kontrole. Tu su i ograničenja oko rezervnog točka, zapremine gepeka, parkiranja u nekim garažama i dodatnog tehničkog dela pri registraciji. Ako voziš malo kilometara, ušteda može biti manja nego što očekuješ.

Auto sa plinom ima smisla za vozača koji prelazi dovoljno kilometara, kupuje poznat motor i dobija uredan sistem sa papirima. Nema smisla ako kupuješ najjeftiniji primerak bez istorije, sa lampicom motora i pričom da je "samo sitno podešavanje". Dobar LPG sistem štedi novac. Loš LPG sistem samo pomera kvar u budućnost.
TEXT,
                'highlights' => [
                    'Plin ima smisla samo uz urednu dokumentaciju, atest i servisnu istoriju sistema.',
                    'Motor mora pravilno raditi i na benzinu i na plinu, bez trzanja i lampica.',
                    'Pre kupovine proveri da li konkretan motor dobro podnosi LPG, ne samo model automobila.',
                ],
                'tags' => ['plin', 'LPG', 'troškovi goriva', 'održavanje'],
                'meta_title' => 'Auto sa plinom: kada se LPG isplati kao polovnjak',
                'meta_description' => 'Vodič za kupovinu polovnog auta sa plinom: atest, LPG servis, rad motora, potrošnja, skriveni troškovi i rizici.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#fb923c', '#f8fafc'],
            ],
            [
                'title' => 'Šta proveriti kod karoserije: zazori, lak i tragovi loše popravke',
                'slug' => 'sta-proveriti-kod-karoserije-zazori-lak-i-tragovi-lose-popravke',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Karoserija često otkriva istoriju koju oglas prećuti. Zazori, nijansa laka i tragovi popravke mogu promeniti celu cenu automobila.',
                'content' => <<<'TEXT'
Karoserija polovnog automobila nije samo pitanje estetike. Ogrebotina na braniku može biti sitnica, ali loše popravljena havarija može značiti problem sa bezbednošću, geometrijom, prodajnom vrednošću i kasnijim kvarovima. Zato karoseriju treba gledati hladno, na dnevnom svetlu i bez žurbe.

Prvo proveri zazore između panela. Vrata, hauba, krila, branik i gepek treba da stoje ravnomerno. Ako je jedna strana šira, druga uža, ili panel deluje kao da "beži", moguće je da je auto popravljan. To ne znači automatski da treba odustati, ali znači da prodavac mora imati objašnjenje i da pregled treba nastaviti detaljnije.

Drugo, gledaj nijansu laka iz više uglova. Razlika u boji između vrata i krila, narandžina kora, prašina u laku, tragovi poliranja ili previše sjajan jedan panel često ukazuju na farbanje. Farban panel nije problem ako znaš zašto je farban. Problem je kada se auto prodaje kao potpuno originalan, a karoserija priča drugačije.

Treće, proveri unutrašnje ivice. Otvori vrata, pogledaj pragove, šrafove, nosače, unutrašnju stranu haube i gepeka. Tragovi skidanja šrafova, svež silikon, neravnomerni varovi ili nedostajuće nalepnice mogu pokazati da je rađena ozbiljnija popravka. Posebno pazi na prednji koš, nosače farova i delove oko hladnjaka.

Merenje debljine laka pomaže, ali nije dovoljno samo po sebi. Uređaj može pokazati farbanje, git ili originalan lak, ali broj treba tumačiti uz pregled celog auta. Ako jedan panel ima mnogo veću vrednost, pitaj za razlog. Ako više panela ima sumnjive vrednosti, obavezno uključi majstora ili limara pre kapare.

Dobar polovan auto može imati farban branik, popravljenu ogrebotinu ili zamenjen far. To nije drama ako je cena realna i popravka kvalitetna. Ono što treba izbegavati jeste auto sa nejasnom istorijom, lošim zazorima, tragovima ozbiljnog udara i prodavcem koji umanjuje svaku primedbu. Karoserija ne govori sve, ali često prva pokaže da li priča iz oglasa drži vodu.
TEXT,
                'highlights' => [
                    'Neravni zazori i različita nijansa laka često otkrivaju prethodne popravke.',
                    'Pregledaj šrafove, pragove, nosače, unutrašnje ivice i tragove silikona ili varenja.',
                    'Farban panel nije uvek problem, ali mora biti objašnjen i uračunat u cenu.',
                ],
                'tags' => ['karoserija', 'lak', 'zazori', 'provera polovnjaka'],
                'meta_title' => 'Šta proveriti kod karoserije polovnog automobila',
                'meta_description' => 'Kako proveriti karoseriju polovnog auta: zazori, nijansa laka, merenje laka, šrafovi, nosači, tragovi havarije i pregovaranje.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Renault Austral ili Kia Sportage: noviji porodični SUV bez premium cene',
                'slug' => 'renault-austral-ili-kia-sportage-noviji-porodicni-suv-bez-premium-cene',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Austral i Sportage su zanimljivi kupcima koji žele moderan porodični SUV, ali ne žele premium znak ni premium trošak.',
                'content' => <<<'TEXT'
Renault Austral i Kia Sportage često ulaze u razmatranje kod kupaca koji žele noviji porodični SUV, modernu kabinu, dosta opreme i pogon koji ima smisla za svakodnevicu. Oba modela nude više savremenog utiska nego stariji SUV polovnjaci, ali kupovina ne sme da se svede na ekran, dizajn i broj asistencija. Kod novijih polovnjaka je još važnije proveriti poreklo, garanciju i servisnu istoriju.

Renault Austral je zanimljiv kupcima koji žele francuski pristup udobnosti, moderan enterijer i hibridnu tehnologiju. Kabina deluje digitalno i sveže, a vožnja može biti vrlo prijatna za grad i porodicu. Kod polovnog Australa proveri rad multimedije, asistencije, hibridni sistem ako ga ima, servisne zapise i da li je auto još u fabričkoj garanciji ili ima produženo pokriće.

Kia Sportage je jača kada kupac želi praktičnost, poznat porodični paket i dobru garancijsku priču kod uredno održavanih primeraka. Sportage često nudi dosta opreme, dobar prostor i širok izbor pogona. Kod polovnog primerka proveri da li su servisi rađeni u skladu sa uslovima garancije, stanje menjača, trap, gume i elektroniku. Duga garancija znači mnogo samo ako dokumentacija prati pravila.

Po prostoru su oba modela ozbiljna, ali probaj ih sa stvarima koje stvarno koristiš: dečje sedište, kolica, putne torbe, sedište vozača podešeno za tebe. Na papiru su razlike male, ali u svakodnevici ulazak, gepek, preglednost i raspored komandi mogu presuditi. Nemoj kupiti SUV samo zato što deluje veće na fotografijama.

Kod motora i hibrida najvažnije je da pogon odgovara tvojoj vožnji. Ako voziš uglavnom grad i prigradske relacije, hibrid može imati smisla. Ako često ideš autoputem, proveri buku, potrošnju i ponašanje pri većim brzinama. Automatski menjači, sistemi pomoći i digitalna oprema moraju se testirati u probnoj vožnji, ne samo upaliti u mestu.

Austral je bolji izbor ako ti se dopada udobniji, moderniji i malo drugačiji karakter, uz uslov da je istorija jasna. Sportage je mirniji izbor ako dobijaš urednu garancijsku dokumentaciju, praktičnost i proverljiv servisni trag. Kod oba modela, najbolja kupovina je primerak koji ima manje nepoznanica, ne onaj sa najdužim spiskom opreme.
TEXT,
                'highlights' => [
                    'Austral nudi moderan enterijer i hibridni karakter, ali traži proveru softvera i asistencija.',
                    'Sportage ima prednost kada su garancijska dokumentacija, praktičnost i servisni trag jasni.',
                    'Kod novijih SUV polovnjaka obavezno proveri poreklo, garanciju, menjač, elektroniku i realnu upotrebu.',
                ],
                'tags' => ['Renault Austral', 'Kia Sportage', 'porodični SUV', 'hibrid'],
                'meta_title' => 'Renault Austral ili Kia Sportage: koji SUV kupiti',
                'meta_description' => 'Poređenje polovnih Renault Austral i Kia Sportage SUV modela: hibrid, garancija, prostor, oprema, elektronika i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#34d399', '#f8fafc'],
            ],
            [
                'title' => 'Auto do 5.000 evra: kako izabrati bez skrivene investicije',
                'slug' => 'auto-do-5000-evra-kako-izabrati-bez-skrivene-investicije',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Budžet od 5.000 evra može kupiti dobar svakodnevni auto, ali samo ako se odmah uračunaju servis, gume, registracija i realna ulaganja.',
                'content' => <<<'TEXT'
Kupovina polovnog auta do 5.000 evra traži drugačiji način razmišljanja od kupovine novijeg automobila. U ovom budžetu najvažnije nije pronaći najmlađi auto ili najjači motor, nego primerak koji ima najmanje nepoznanica. Svaki euro koji ode na atraktivan izgled, a ne na stanje, lako se vrati kao servisni račun.

Prvo odvoji budžet za kupovinu od budžeta za prva ulaganja. Ako ukupno imaš 5.000 evra, nije pametno sve dati prodavcu. Registracija, prvi servis, ulje, filteri, gume, akumulator, kočnice ili sitna elektronika često dođu odmah. Mnogo je mirnije kupiti auto za 4.300 evra i imati rezervu nego dati 5.000 evra za primerak koji već prvog meseca traži novac.

Drugo, u ovom rangu jednostavnost pobeđuje prestiž. Stariji premium model može izgledati bolje na fotografijama, ali gume, trap, menjač, turbina i elektronika obično ne znaju da je auto kupljen jeftino. Jednostavan benzinac, dobro održavan kompakt ili gradski auto često su bolji izbor od starog luksuznog automobila bez istorije.

Treće, kilometraža ne sme biti jedini kriterijum. Auto sa više kilometara i urednim računima može biti bolja kupovina od automobila sa navodno malom kilometražom i praznom pričom. Gledaj stanje enterijera, hladan start, probnu vožnju, trap, gume, kočnice i dokumentaciju. Ako se sve slaže, kilometraža je samo deo slike.

Najskuplja jeftina kupovina je auto koji deluje povoljno zato što skriva ulaganja. Zato svaki oglas do 5.000 evra treba gledati kroz pitanje: koliko me ovaj auto realno košta kada ga dovedem u normalno stanje? Tek tada cena iz oglasa postaje upotrebljiva informacija.
TEXT,
                'highlights' => [
                    'Ne troši ceo budžet na cenu iz oglasa; ostavi novac za prvi servis, gume i registraciju.',
                    'Jednostavan i uredan auto često je bolji od starog premium modela bez istorije.',
                    'Realna cena je kupovna cena plus ulaganja koja dolaze odmah posle preuzimanja.',
                ],
                'tags' => ['auto do 5000 evra', 'budžet', 'kupovina polovnjaka', 'troškovi'],
                'meta_title' => 'Auto do 5.000 evra: kako izabrati pametno',
                'meta_description' => 'Vodič za kupovinu polovnog auta do 5.000 evra: budžet, prvi servis, gume, registracija, kilometraža i skrivena ulaganja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Kia Ceed ili Hyundai i30: kompakt bez nemačke premije',
                'slug' => 'kia-ceed-ili-hyundai-i30-kompakt-bez-nemacke-premije',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Ceed i i30 često nude dobru opremu, razumnu cenu i korejsku pouzdanost, ali presuđuju motor, servisna istorija i realno stanje primerka.',
                'content' => <<<'TEXT'
Kia Ceed i Hyundai i30 su logičan izbor za kupce koji žele kompakt za svakodnevicu, ali ne žele da plate premiju za Golf, Audi ili BMW. Ova dva modela često dele sličnu tehničku logiku, nude dobru opremu i imaju reputaciju racionalnih polovnjaka. To ih čini zanimljivim, ali ne znači da treba kupovati bez provere.

Kia Ceed često privlači kupce zbog garancijske priče, solidne opreme i dobrog odnosa cene i stanja. Ako je automobil još u garanciji ili ima urednu servisnu istoriju, to može biti velika prednost. Međutim, garancija vredi samo ako su servisi rađeni po pravilima. Preskočeni intervali, nejasni računi ili servis van propisanog režima mogu promeniti celu računicu.

Hyundai i30 je vrlo sličan po nameni: praktičan kompakt, dovoljno udoban za porodicu i jednostavan za svakodnevnu upotrebu. Kod polovnog i30 proveri stanje motora, menjača, kvačila, trapa i elektronike. Ako gledaš dizel, važe ista pravila kao kod svakog modernog dizela: DPF, EGR i način prethodne vožnje moraju biti deo pregleda.

Razlika između Ceed-a i i30 često je manja od razlike između dva konkretna primerka. Jedan može imati bolju opremu, drugi jasniju istoriju. Jedan može izgledati bolje na fotografijama, drugi može biti tehnički uredniji. Zato nemoj birati samo model, nego auto koji ima manje nepoznanica.

Ceed i i30 su dobri kada želiš razuman kompakt bez preplaćivanja znaka. Najbolji izbor je primerak sa jasnim servisima, normalnom kilometražom za godište i stanjem koje se vidi na probnoj vožnji. Ako je cena sumnjivo niska, razlog moraš pronaći pre kapare.
TEXT,
                'highlights' => [
                    'Ceed i i30 imaju smisla ako želiš racionalan kompakt bez premium cene.',
                    'Garancijska priča vredi samo ako servisna istorija prati propisana pravila.',
                    'Presudi konkretan primerak, ne mala razlika između dva slična modela.',
                ],
                'tags' => ['Kia Ceed', 'Hyundai i30', 'kompakt', 'poređenje'],
                'meta_title' => 'Kia Ceed ili Hyundai i30: koji polovni kompakt kupiti',
                'meta_description' => 'Poređenje polovnih Kia Ceed i Hyundai i30 modela: oprema, garancija, motori, dizel rizici, servisna istorija i realna vrednost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Toyota Auris Hybrid: miran hibrid za grad ili preskupa reputacija',
                'slug' => 'polovni-toyota-auris-hybrid-miran-hibrid-za-grad-ili-preskupa-reputacija',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Auris Hybrid može biti odličan gradski polovnjak, ali dobra reputacija ne sme da zameni proveru baterije, kočnica, servisa i realne cene.',
                'content' => <<<'TEXT'
Toyota Auris Hybrid je jedan od polovnjaka koje kupci često gledaju kada žele nisku potrošnju u gradu, automatski osećaj vožnje i manje dizel rizika. Njegova reputacija nije slučajna, ali upravo zbog te reputacije tržište ponekad traži cenu koju konkretan primerak ne zaslužuje.

Najveća prednost Aurisa Hybrid je gradska svakodnevica. Hibridni sistem dobro podnosi stani-kreni vožnju, nema klasičan manuelni menjač i često troši manje od benzinca slične veličine. Za kupca koji većinu vremena vozi grad i prigradske relacije, to može biti vrlo mirna kupovina.

Ipak, hibrid ne znači da pregled može biti površan. Proveri stanje baterije, rad sistema, servisne zapise, kočnice, trap, gume i elektroniku. Hibridi često manje troše kočnice zbog regeneracije, ali to ne znači da diskovi i čeljusti ne mogu biti zapušteni. Automobil koji je dugo stajao ili radio kratke intenzivne relacije takođe može imati svoje tragove.

Cena je ključna. Auris Hybrid često drži vrednost bolje od prosečnog kompakta, ali kupac ne treba da plati reputaciju umesto stanja. Ako je primerak skuplji od konkurencije, mora imati jasnu istoriju, uredan enterijer, dobru bateriju i realnu kilometražu. Ako toga nema, bolje je porediti ga sa Corollom Hybrid, Yarisom Hybrid ili dobrim benzincem.

Auris Hybrid je odličan za vozača koji želi miran gradski auto i razume hibridnu računicu. Nije idealan ako očekuje najnižu kupovnu cenu ili često vozi autoput velikom brzinom. Najbolji Auris je onaj kod kog se reputacija, dokumentacija i stanje poklope.
TEXT,
                'highlights' => [
                    'Auris Hybrid je najjači u gradu i prigradskoj vožnji.',
                    'Pre kupovine proveri bateriju, kočnice, servisne zapise i realnu namenu prethodnog korišćenja.',
                    'Toyota reputacija ne opravdava svaku cenu ako konkretan primerak nema jasnu istoriju.',
                ],
                'tags' => ['Toyota Auris Hybrid', 'hibrid', 'gradski auto', 'Toyota'],
                'meta_title' => 'Polovni Toyota Auris Hybrid: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Toyota Auris Hybrid modela: baterija, gradska potrošnja, kočnice, servisna istorija, cena i realna isplativost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Kredit, keš ili zamena staro za novo: kako računati stvarnu cenu auta',
                'slug' => 'kredit-kes-ili-zamena-staro-za-novo-kako-racunati-stvarnu-cenu-auta',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Način plaćanja može promeniti realnu cenu automobila. Nije isto platiti keš, finansirati kreditom ili dati stari auto u zamenu.',
                'content' => <<<'TEXT'
Kupci često gledaju samo cenu iz oglasa, ali stvarna cena automobila zavisi i od načina plaćanja. Keš, kredit i zamena staro za novo ne nose isti trošak, isti rizik ni isti prostor za pregovor. Zato pre poziva prodavcu treba znati koliko auto stvarno košta u tvom scenariju.

Keš daje najviše kontrole. Kupac zna koliko ima, može brže da reaguje i često ima jači pregovarački položaj. Mana je što sav novac odlazi odmah, pa je opasno potrošiti ceo budžet na kupovinu. I kod keš kupovine mora ostati rezerva za servis, registraciju, gume i neplanirane popravke.

Kredit može pomoći da kupiš bolji ili noviji auto, ali mesečna rata nije cela priča. Treba uračunati kamatu, obradu, osiguranje, obavezne uslove finansiranja i koliko će auto vredeti kada kredit još traje. Najgora varijanta je kupovina automobila koji brzo gubi vrednost dok rata ostaje ista.

Zamena staro za novo deluje praktično jer rešava prodaju starog automobila, ali uvek proveri koliko stvarno dobijaš za svoje vozilo. Diler često uračuna komfor, rizik i dalju prodaju u ponuđenu cenu. To nije nužno loše, ali moraš znati razliku između tržišne vrednosti starog auta i iznosa koji dobijaš u zameni.

Najbolja odluka nije uvek najniža cena iz oglasa. Najbolja odluka je ukupna računica: koliko plaćaš sada, koliko plaćaš kroz vreme, koliko ulažeš posle kupovine i koliko lako možeš izaći iz auta ako se plan promeni. Auto se kupuje jednom, ali trošak živi svakog meseca.
TEXT,
                'highlights' => [
                    'Keš daje pregovaračku snagu, ali ne sme pojesti rezervu za prva ulaganja.',
                    'Kod kredita računaj kamatu, uslove finansiranja i vrednost auta kroz vreme.',
                    'Zamena staro za novo je praktična samo ako razumeš stvarnu vrednost svog starog auta.',
                ],
                'tags' => ['finansiranje', 'kredit za auto', 'keš', 'staro za novo'],
                'meta_title' => 'Kredit, keš ili zamena: stvarna cena polovnog auta',
                'meta_description' => 'Kako računati stvarnu cenu polovnog automobila kroz keš kupovinu, kredit, zamenu staro za novo, kamatu, ulaganja i pregovaranje.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Veliki servis posle kupovine: šta mora u budžet pre prvog kilometra',
                'slug' => 'veliki-servis-posle-kupovine-sta-mora-u-budzet-pre-prvog-kilometra',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Polovan auto često nije završen trošak danom kupovine. Veliki servis, tečnosti, kočnice i gume mogu odmah promeniti realnu cenu.',
                'content' => <<<'TEXT'
Kupovina polovnog automobila ne završava se potpisom ugovora. Za mnoge kupce pravi trošak počinje odmah posle preuzimanja, kada se postavi pitanje šta mora da se uradi pre mirne vožnje. Veliki servis je najčešća stavka, ali nije jedina.

Prvo proveri da li postoji dokaz kada je veliki servis rađen. Ako nema računa, nalepnice ili jasnog zapisa, tretiraj ga kao da nije urađen. Priča prodavca može biti tačna, ali kupac ne treba da rizikuje motor zbog rečenice bez dokaza. Kaiš, španeri, pumpa vode ili lanac kod određenih motora nisu stavke koje se odlažu ako istorija nije jasna.

Drugo, uradi osnovne tečnosti i filtere ako ne znaš kada su menjani. Ulje u motoru, filteri, rashladna tečnost, kočiona tečnost i ulje u menjaču kod automatika mogu biti važniji od kozmetike. Auto koji je spolja lep, a servisno nejasan, treba budžet za dovođenje u poznato stanje.

Treće, proveri gume, kočnice, akumulator i trap. To su stvari koje direktno utiču na bezbednost i često se vide tek kada auto uđe u svakodnevnu upotrebu. Ako su gume stare, diskovi potrošeni ili akumulator slab, to nije sitnica nego deo realne cene kupovine.

Najbolje je pre kupovine napraviti listu ulaganja i pitati majstora za okvirne cene. Tada znaš da li je oglas stvarno povoljan ili samo prebacuje trošak na novog vlasnika. Polovan auto koji je malo skuplji, ali servisno jasan, često je jeftiniji od jeftinog auta koji odmah traži veliki servis.
TEXT,
                'highlights' => [
                    'Ako nema dokaza o velikom servisu, računaj ga kao obavezno ulaganje.',
                    'Tečnosti, filteri, kočnice, gume i akumulator ulaze u realnu cenu kupovine.',
                    'Povoljan oglas nije povoljan ako samo prebacuje početna ulaganja na kupca.',
                ],
                'tags' => ['veliki servis', 'održavanje', 'troškovi', 'kupovina polovnjaka'],
                'meta_title' => 'Veliki servis posle kupovine polovnog auta',
                'meta_description' => 'Šta uračunati posle kupovine polovnog automobila: veliki servis, ulje, filteri, kočnice, gume, akumulator i realna cena oglasa.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#fb7185', '#f8fafc'],
            ],
            [
                'title' => 'Škoda Fabia ili Opel Corsa: mali auto za grad bez velikog rizika',
                'slug' => 'skoda-fabia-ili-opel-corsa-mali-auto-za-grad-bez-velikog-rizika',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Fabia i Corsa su česti izbori za prvi auto, gradsku vožnju i niže troškove. Razlika je manje u marki, a više u motoru, stanju i servisnoj istoriji.',
                'content' => <<<'TEXT'
Škoda Fabia i Opel Corsa često ulaze u isti uži izbor kada kupac traži mali auto za grad, prigradske relacije ili prvi automobil za novog vozača. Oba modela imaju smisla jer su jednostavna za parkiranje, troše razumno i ne traže budžet velikog porodičnog auta. Ipak, na tržištu polovnjaka mali auto ne znači automatski mali rizik.

Fabia je često racionalniji izbor za kupce koji žele praktičnost. Kabina je obično upotrebljiva, prtljažnik je dobar za klasu, a delovi su dostupni. Kod benzinskih motora proveri hladan start, potrošnju ulja, servisne intervale i stanje kvačila. Ako je auto radio kratke gradske vožnje, kočnice, trap i akumulator mogu biti veći trošak nego što se vidi u oglasu.

Corsa je dobra alternativa kada želiš kompaktniji osećaj i često povoljniju cenu. Važno je proveriti konkretan motor, menjač i elektroniku, jer razlike između primeraka mogu biti velike. Corsa koja izgleda lepo spolja, ali ima nejasnu istoriju ili dugo nije ozbiljno servisirana, brzo može izgubiti prednost niže kupovne cene.

Kod oba modela najvažnije je da ne kupuješ samo kilometražu. Gradski automobili često imaju manje kilometara, ali više hladnih startova, ivičnjaka, kratkih relacija i sitnih oštećenja. Probna vožnja treba da uključi neravnine, kočenje, skretanje punim uglom i proveru svih električnih potrošača.

Fabia je bolja ako ti treba malo više praktičnosti i lakše poređenje velikog broja oglasa. Corsa ima smisla ako dobijaš bolju cenu, jasniju istoriju ili očuvaniji primerak. Najbolji izbor nije model sa boljom reputacijom, nego auto kod kog se cena, stanje i prva ulaganja uklapaju u realan budžet.
TEXT,
                'highlights' => [
                    'Fabia obično nudi više praktičnosti, ali stanje konkretnog primerka presuđuje.',
                    'Corsa može biti povoljnija, ali samo ako su motor, menjač i servisna istorija jasni.',
                    'Kod gradskih automobila mala kilometraža ne znači manje habanja.',
                ],
                'tags' => ['Škoda Fabia', 'Opel Corsa', 'gradski auto', 'prvi auto'],
                'meta_title' => 'Škoda Fabia ili Opel Corsa: koji mali polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Škoda Fabia i Opel Corsa modela: gradska vožnja, prvi auto, motori, kilometraža, održavanje i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Audi Q3: kompaktni premium SUV koji traži hladnu glavu',
                'slug' => 'polovni-audi-q3-kompaktni-premium-suv-koji-trazi-hladnu-glavu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Audi Q3 privlači kupce premium znakom i SUV formom, ali isplativost zavisi od porekla, menjača, pogona, opreme i realne cene održavanja.',
                'content' => <<<'TEXT'
Audi Q3 je jedan od onih polovnjaka koji lako privuče kupca pre nego što računica postane potpuno jasna. Ima premium znak, višu poziciju sedenja, kompaktnu veličinu i dovoljno praktičnosti za svakodnevicu. Upravo zato se često prodaje po ceni koja od kupca traži hladnu glavu, a ne samo dobar prvi utisak.

Prvo proveri poreklo i servisnu istoriju. Q3 koji je uredno održavan može biti vrlo prijatan auto, ali primerak sa nejasnim uvozom, preskočenim servisima ili velikom kilometražom bez dokaza može biti skup za vraćanje u dobro stanje. Posebno proveri račune, intervale, kilometražu kroz vreme i da li se stanje enterijera slaže sa pričom prodavca.

Drugo, menjač i pogon moraju biti deo pregleda. Automatski menjači traže servisnu istoriju, miran rad i probnu vožnju u različitim režimima. Ako auto ima quattro pogon, proveri servis pogonskog sklopa i ponašanje pri skretanju. Dodatna oprema je dobra samo kada radi; svaki kvar na elektronici ili asistencijama kod premium auta lako postane ozbiljan trošak.

Treće, uporedi cenu sa realnim alternativama. Za isti novac ponekad možeš dobiti mlađi porodični SUV bez premium znaka, bolju garancijsku priču ili znatno manju kilometražu. Q3 ima smisla kada želiš premium osećaj u kompaktnom pakovanju, ali nema smisla platiti zapušten primerak samo zato što nosi poznat znak.

Najbolji polovni Q3 je onaj koji nema mnogo nepoznanica. Ako je istorija jasna, menjač uredan, oprema proverena i cena u skladu sa tržištem, može biti dobra kupovina. Ako prodavac izbegava dokumentaciju ili cena deluje predobra, bolje je nastaviti poređenje nego kasnije finansirati tuđe odlaganje servisa.
TEXT,
                'highlights' => [
                    'Q3 ima smisla samo kada premium cena prati jasnu istoriju i dobro stanje.',
                    'Automatski menjač, quattro pogon i elektronika moraju se proveriti u probnoj vožnji.',
                    'Uporedi ga sa mlađim ne-premium SUV modelima pre nego što platiš znak.',
                ],
                'tags' => ['Audi Q3', 'premium SUV', 'quattro', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Audi Q3: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Audi Q3 modela: poreklo, servisna istorija, automatski menjač, quattro pogon, oprema i cena održavanja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Automobil sa malom kilometražom: kada je prednost, a kada crvena zastavica',
                'slug' => 'automobil-sa-malom-kilometrazom-kada-je-prednost-a-kada-crvena-zastavica',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mala kilometraža može biti velika prednost, ali samo kada je potvrđena dokumentacijom, stanjem i logikom korišćenja. Bez toga lako postaje zamka.',
                'content' => <<<'TEXT'
Mala kilometraža je jedan od najjačih mamaca u oglasima za polovne automobile. Kupci prirodno žele auto koji je manje vožen, manje haban i potencijalno bliži fabričkom stanju. Problem je što broj kilometara sam po sebi ne dokazuje skoro ništa ako ga ne prate dokumentacija i realno stanje.

Prava prednost postoji kada se kilometraža može pratiti kroz servise, račune, tehničke preglede, uvozne dokumente i stanje automobila. Ako je auto star deset godina i ima malo kilometara, mora postojati objašnjenje: drugi auto u porodici, sezonsko korišćenje, lokalna vožnja ili jasna istorija vlasništva. Bez toga, mala kilometraža je samo tvrdnja.

Crvena zastavica se pojavljuje kada se broj ne slaže sa automobilom. Izlizani volan, sedište, ručica menjača, pedale, ogrebotine u kabini i umoran trap ne moraju dokazati vraćanje kilometraže, ali traže dodatna pitanja. Isto važi i za servisnu knjižicu koja počinje kasno, nema račune ili ima velike rupe između intervala.

Mala kilometraža ne znači automatski manje ulaganja. Auto koji je dugo stajao može imati stare gume, slab akumulator, zapekle kočnice, probleme sa gumama, tečnostima ili dihtunzima. Kratke gradske relacije takođe habaju motor drugačije od otvorenog puta. Zato pregled mora uključiti način korišćenja, ne samo ukupan broj.

Najbolje je gledati kilometražu kao jedan deo slagalice. Ako se broj, dokumentacija, stanje i priča prodavca poklapaju, mala kilometraža je ozbiljna prednost. Ako se bilo koji deo ne uklapa, nemoj plaćati premiju za broj koji ne možeš proveriti. Dobar polovnjak se kupuje stanjem, ne obećanjem.
TEXT,
                'highlights' => [
                    'Mala kilometraža vredi samo kada je prati proverljiva istorija.',
                    'Enterijer, trap, gume i servisni zapisi moraju se slagati sa brojem kilometara.',
                    'Auto koji je malo vožen može tražiti ulaganja ako je dugo stajao.',
                ],
                'tags' => ['mala kilometraža', 'provera kilometraže', 'servisna istorija', 'provera vozila'],
                'meta_title' => 'Auto sa malom kilometražom: prednost ili rizik',
                'meta_description' => 'Kako proveriti automobil sa malom kilometražom: servisna istorija, stanje enterijera, tehnički tragovi, stajanje i realna vrednost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#34d399', '#f8fafc'],
            ],
            [
                'title' => 'Benzinac, dizel ili hibrid do 10.000 evra: šta ima najviše smisla',
                'slug' => 'benzinac-dizel-ili-hibrid-do-10000-evra-sta-ima-najvise-smisla',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Budžet do 10.000 evra otvara mnogo izbora, ali pravi pogon zavisi od rute, kilometraže, servisa i toga koliko dugo planiraš da zadržiš auto.',
                'content' => <<<'TEXT'
Do 10.000 evra kupac može birati između solidnih benzinaca, dizelaša i sve većeg broja hibrida. To je dovoljno novca da se kupi upotrebljiv porodični auto, kompakt ili manji SUV, ali i dovoljno novca da pogrešan izbor pogona postane skup. Najvažnije pitanje nije koji motor je najbolji, nego koji motor odgovara tvojoj vožnji.

Benzinac ima najviše smisla za kupce koji voze grad, kraće relacije i umerenu godišnju kilometražu. Jednostavniji benzinski motori mogu biti mirniji za održavanje od dizela, posebno ako ne prelaziš mnogo kilometara. Treba proveriti potrošnju ulja, lanac ili kaiš, turbo kod manjih modernih motora i redovnost servisa.

Dizel ima smisla ako često voziš otvoren put, prelaziš veću kilometražu i motor redovno radi dovoljno dugo da sistemi izduva funkcionišu kako treba. Ako se dizel koristi uglavnom po gradu, DPF, EGR, turbina i dizne lako promene celu računicu. Niska potrošnja nije dovoljna ako servisni rizik ne odgovara načinu vožnje.

Hibrid je zanimljiv ako voziš grad, želiš automatski osećaj i spreman si da proveriš bateriju, servisnu istoriju i realnu cenu. Dobar hibrid može biti vrlo miran polovnjak, ali tržište često traži veću cenu zbog reputacije. Zato uporedi ukupnu cenu sa dobrim benzincem, a ne samo potrošnju na papiru.

Najbolji izbor do 10.000 evra je pogon koji prati tvoju svakodnevicu. Za grad i kraće relacije benzinac ili hibrid često imaju više smisla. Za autoput i veću kilometražu dizel može biti racionalan, ali samo ako je istorija jasna. Pogrešan motor može biti skuplji od skupljeg oglasa.
TEXT,
                'highlights' => [
                    'Benzinac je često mirniji izbor za grad i manju godišnju kilometražu.',
                    'Dizel se isplati tek kada način vožnje odgovara DPF, EGR i turbini.',
                    'Hibrid ima smisla u gradu, ali cenu treba uporediti sa dobrim benzincem.',
                ],
                'tags' => ['benzinac', 'dizel', 'hibrid', 'auto do 10000 evra'],
                'meta_title' => 'Benzinac, dizel ili hibrid do 10.000 evra',
                'meta_description' => 'Kako izabrati pogon za polovan auto do 10.000 evra: benzinac, dizel, hibrid, gradska vožnja, autoput, potrošnja i servisni rizici.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Kako prodati polovan auto brže: fotografije, cena i opis koji grade poverenje',
                'slug' => 'kako-prodati-polovan-auto-brze-fotografije-cena-i-opis-koji-grade-poverenje',
                'category' => 'Pregovaranje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dobar oglas ne služi samo da prikaže auto, već da smanji sumnju kupca. Fotografije, realna cena i jasan opis direktno utiču na broj poziva.',
                'content' => <<<'TEXT'
Prodaja polovnog automobila ne zavisi samo od toga koliko je auto dobar. Zavisi i od toga koliko oglas uliva poverenje. Kupci danas brzo porede desetine oglasa, preskaču nejasne fotografije i zovu prodavce koji odmah daju odgovore na glavna pitanja. Dobar oglas skraćuje put do ozbiljnog kupca.

Fotografije su prvi filter. Auto slikaj po danu, čist, iz više uglova i bez prenaglašenih filtera. Prikaži spoljašnjost, enterijer, instrument tablu, gume, gepek, servisnu dokumentaciju i eventualna oštećenja. Ako sakriješ manu, kupac će je pronaći na licu mesta, a poverenje će nestati pre pregovora.

Cena mora imati vezu sa tržištem. Pogledaj slične oglase po marki, modelu, godištu, motoru, opremi i kilometraži. Ako tražiš više od proseka, objasni zašto: servisna istorija, nove gume, veliki servis, prvi vlasnik ili posebna oprema. Ako je cena preniska, kupci će takođe sumnjati da nešto nije u redu.

Opis treba da odgovori na pitanja pre poziva. Napiši koliko dugo je auto kod tebe, šta je skoro urađeno, šta treba uraditi, kakve su gume, kada ističe registracija, da li postoji servisna istorija i zašto se auto prodaje. Ne moraš pisati roman, ali izbegni prazne fraze kao što su "bez ulaganja" ako to ne možeš dokazati.

Najbrže se prodaju automobili čiji oglasi deluju jasno i proverljivo. Realna cena privlači prave kupce, dobre fotografije smanjuju sumnju, a iskren opis štedi vreme. Cilj nije da se javi što više ljudi, nego da se jave kupci koji već razumeju šta gledaju i zašto cena ima smisla.
TEXT,
                'highlights' => [
                    'Dobre fotografije treba da prikažu i prednosti i realno stanje auta.',
                    'Cena mora biti objašnjiva kroz tržište, servisnu istoriju i opremu.',
                    'Jasan opis smanjuje nepotrebne pozive i gradi poverenje pre pregleda.',
                ],
                'tags' => ['prodaja automobila', 'auto oglas', 'fotografije auta', 'cena polovnjaka'],
                'meta_title' => 'Kako prodati polovan auto brže i pametnije',
                'meta_description' => 'Saveti za prodaju polovnog automobila: dobre fotografije, realna cena, opis oglasa, servisna dokumentacija i poverenje kupca.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Mazda 3 ili Honda Civic: benzinac za vozača koji ne želi dizel rizik',
                'slug' => 'mazda-3-ili-honda-civic-benzinac-za-vozaca-koji-ne-zeli-dizel-rizik',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mazda 3 i Honda Civic su čest izbor za kupce koji žele benzinski kompakt sa manje dizel komplikacija, ali presuđuju motor, stanje i istorija održavanja.',
                'content' => <<<'TEXT'
Mazda 3 i Honda Civic često privlače istu publiku: vozače koji žele kompaktan automobil sa dobrim osećajem u vožnji, razumnom potrošnjom i manje dizel nepoznanica. Oba modela imaju reputaciju solidnih benzinaca, ali na tržištu polovnjaka reputacija je samo početak. Pravi izbor zavisi od konkretnog motora, servisa i toga šta kupac realno očekuje od automobila.

Mazda 3 obično privlači kupce koji traže direktniji osećaj za volanom i nešto drugačiji karakter od prosečnog kompakta. Kod benzinskih motora proveri potrošnju ulja, stanje kvačila, tragove gradske vožnje i redovnost servisa. Ako je automobil bio lepo održavan, Mazda 3 može biti vrlo mirna kupovina. Ako je servis preskakan i auto vožen bez pažnje, premium utisak iz kabine ne znači mnogo.

Honda Civic često nudi više prostora i jaču reputaciju kada kupac traži dugoročniji odnos sa autom. Civic ima smisla kada je istorija jasna i kada stanje enterijera, motora i trapa prati kilometražu. Kao i kod svakog traženog modela, cena zna da ode iznad proseka samo zbog imena. To vredi platiti samo kada konkretan primerak zaista izgleda bolje od tržišta, a ne kada samo deluje poznato.

Kod oba modela benzinac ima smisla ako voziš grad, kraće relacije ili umerenu godišnju kilometražu. To ne znači da pregled može biti površan. Hladan start, probna vožnja, trap, kočnice, gume i računi vrede više od fotografija i listi opreme. Kod kompakta se razlika između dobrog i prosečnog primerka često otkrije tek na detaljnom pregledu.

Mazda 3 je bolji izbor ako želiš dinamičniji karakter i primerak sa urednom istorijom. Civic ima više smisla ako prioritet daješ praktičnosti i reputaciji koja lakše drži cenu. U oba slučaja, najbolji benzinac je onaj koji kupuješ kroz stanje i servisne tragove, a ne kroz mit o pouzdanosti.
TEXT,
                'highlights' => [
                    'Mazda 3 i Honda Civic imaju smisla kada želiš benzinac bez dizel rizika.',
                    'Reputacija ne vredi bez jasne servisne istorije i dobrog stanja konkretnog primerka.',
                    'Probna vožnja, hladan start i računi vrede više od opreme na papiru.',
                ],
                'tags' => ['Mazda 3', 'Honda Civic', 'benzinac', 'kompakt'],
                'meta_title' => 'Mazda 3 ili Honda Civic: koji benzinac kupiti',
                'meta_description' => 'Poređenje polovnih Mazda 3 i Honda Civic modela: benzinci, servisna istorija, stanje, gradska vožnja i realna isplativost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni BMW X1: kompaktni premium SUV koji lako sakrije skupe sitnice',
                'slug' => 'polovni-bmw-x1-kompaktni-premium-suv-koji-lako-sakrije-skupe-sitnice',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'BMW X1 izgleda kao razuman ulaz u premium SUV svet, ali dobar primerak traži pažljivu proveru menjača, trapa, servisa i stvarne cene održavanja.',
                'content' => <<<'TEXT'
BMW X1 je često prvi premium SUV koji kupac ozbiljno razmatra jer deluje dostupnije od većih modela, a i dalje nosi poznat znak i povišenu poziciju sedenja. Upravo zato lako ulazi u uži izbor ljudi koji žele “nešto jače”, ali ne žele cenu X3 ili X5. Problem je što ni kompaktni premium SUV ne zna da je kupljen jeftinije.

Prvo proveri servisnu istoriju i način prethodnog korišćenja. X1 koji je redovno servisiran može biti logična kupovina za vozača koji želi premium osećaj u svakodnevici. Ali primerak sa nejasnim računima, uvezen bez tragova ili sa umornim enterijerom često skriva trošak koji će brzo poništiti nižu kupovnu cenu. Posebno obrati pažnju na tragove habanja koji ne prate oglašenu kilometražu.

Drugo, trap, menjač i pogon moraju biti deo pregleda. X1 nije auto kod kog treba improvizovati sa “to ćemo posle”. Neravnine, puno skretanje, kočenje i probna vožnja pri višim brzinama otkrivaju više nego lista opreme. Ako auto ima automatski menjač ili pogon na sve točkove, servisni trag i ponašanje u vožnji su obavezni deo odluke.

Treće, uporedi ga sa racionalnijim alternativama. Za cenu prosečnog X1 ponekad možeš dobiti mlađi Tucson, Qashqai ili Kuga sa manje premium imidža, ali sa manjim servisnim rizikom. X1 ima smisla samo ako ti premium kvalitet zaista nešto donosi i ako primerak koji kupuješ nema mnogo nepoznanica.

Najbolji polovni X1 nije onaj sa najdužom listom opreme, već onaj sa najjasnijom dokumentacijom. Ako servisna istorija, menjač, trap i opšti utisak drže priču na okupu, BMW X1 može biti pametna kupovina. Ako sve zavisi od obećanja prodavca, cena je verovatno samo mamac.
TEXT,
                'highlights' => [
                    'BMW X1 ima smisla samo kada premium osećaj prati jasna dokumentacija i dobro stanje.',
                    'Trap, menjač i eventualni AWD sistem moraju biti provereni u vožnji.',
                    'Uporedi X1 sa mlađim ne-premium SUV modelima pre nego što platiš znak.',
                ],
                'tags' => ['BMW X1', 'premium SUV', 'automatik', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni BMW X1: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog BMW X1 modela: servisna istorija, trap, menjač, premium SUV troškovi i realna cena primerka.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Auto koji je dugo stajao: kako prepoznati skriven problem pre kupovine',
                'slug' => 'auto-koji-je-dugo-stajao-kako-prepoznati-skriven-problem-pre-kupovine',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Automobil sa malo vožnje i dugim stajanjem može delovati primamljivo, ali stajanje često donosi troškove koje oglas ne pokazuje odmah.',
                'content' => <<<'TEXT'
Kupci često pomisle da je auto koji je malo vožen i dugo stajao automatski bolja kupovina. U praksi, dug period stajanja može doneti čitav niz problema koji se ne vide odmah na fotografijama. Oglas može zvučati idealno, ali mehanika ne voli neaktivnost jednako kao ni loše održavanje.

Prvi znak je stanje guma, kočnica i akumulatora. Gume mogu imati dobar dezen, a ipak biti stare i tvrde. Diskovi i pločice mogu zarđati ili izgubiti radnu površinu. Akumulator često oslabi upravo na autima koji retko voze. To nisu ogromne stavke svaka za sebe, ali zajedno menjaju realnu cenu kupovine.

Drugi problem su tečnosti, zaptivke i gumeni delovi. Auto koji dugo stoji može početi da pokazuje curenja tek kada se vrati u svakodnevnu vožnju. Creva, dihtunzi, amortizeri i sitni gumeni elementi stare i kada se ne vozi mnogo. Zato auto koji deluje “kao nov” po kilometraži ne mora biti jeftin za dovođenje u pouzdano stanje.

Treće, obrati pažnju na priču prodavca. Dugo stajanje mora imati objašnjenje koje ima smisla: sezonska vožnja, drugi auto u kući, nasledstvo ili specifičan način korišćenja. Ako priča zvuči nejasno, a servisni trag je tanak, kupac ne dobija povlasticu male kilometraže nego preuzima rizik neaktivnosti.

Najpametnije je pregledati auto koji je dugo stajao kao da kupuješ vozilo sa posebnim tipom habanja. Dobar primerak postoji, ali mora proći pregled kočnica, guma, tečnosti, akumulatora i tragova curenja. Tek tada mala kilometraža postaje prednost, umesto skupog iznenađenja.
TEXT,
                'highlights' => [
                    'Dugo stajanje najčešće se prvo vidi na gumama, kočnicama i akumulatoru.',
                    'Tečnosti, dihtunzi i gumeni delovi mogu praviti trošak i kod malo voženog auta.',
                    'Priča prodavca o dugom stajanju mora imati logiku i servisni trag.',
                ],
                'tags' => ['auto koji je stajao', 'provera vozila', 'mala kilometraža', 'kupovina polovnjaka'],
                'meta_title' => 'Auto koji je dugo stajao: šta proveriti',
                'meta_description' => 'Kako proveriti automobil koji je dugo stajao: gume, kočnice, akumulator, tečnosti, zaptivke i rizici male kilometraže.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Najbolji automatik do 8.000 evra: kako gledati cenu bez skupog kvara',
                'slug' => 'najbolji-automatik-do-8000-evra-kako-gledati-cenu-bez-skupog-kvara',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Budžet do 8.000 evra otvara dosta automatika, ali prava kupovina zavisi više od istorije menjača nego od same opreme ili godišta.',
                'content' => <<<'TEXT'
Automatik do 8.000 evra zvuči kao odlična kombinacija komfora i razumnog budžeta, ali upravo u tom rangu kupac mora biti posebno oprezan. Menjač je jedna od najskupljih stavki na polovnom automobilu, a oglas retko daje dovoljno informacija da bi se stanje razumelo bez pregleda. Zato prvo treba gledati istoriju, a tek onda opremu.

Najveća greška je kupovina automatika samo zato što “lepo šalta” na kratkoj probnoj vožnji. Miran polazak jeste dobar znak, ali nije dovoljan. Potrebno je proveriti da li postoji servisni trag, da li je ulje menjano kada je trebalo, kako menjač radi hladan i zagrejan, i da li pri ubrzanju ili usporavanju postoje trzaji, odlaganja ili čudni zvuci.

Drugo, u ovom budžetu stanje konkretnog primerka vredi više od imena modela. Neki automobili sa manje opreme, ali sa urednom istorijom menjača i motora, bolja su kupovina od lepšeg oglasa bez dokaza. Kupac treba da računa i na prva ulaganja posle kupovine, jer je automatik miran samo kada je ostatak auta servisno zdrav.

Treće, poredi automatike sa ručnim menjačem istog modela. Ako je razlika u ceni mala, pitaj se zašto. Ponekad tržište već ugrađuje sumnju u menjač. Ponekad prodavac žuri. U oba slučaja, dobra kupovina nije ona koja samo deluje povoljno, nego ona kod koje se cena uklapa sa servisnim tragovima.

Najbolji automatik do 8.000 evra nije jedan konkretan model, nego primerak sa jasnim tragom održavanja. Ako pregled pokaže stabilan menjač, zdrav motor i normalna početna ulaganja, automatik u ovom budžetu može imati smisla. Bez toga, komfor vrlo brzo postaje najskuplji deo oglasa.
TEXT,
                'highlights' => [
                    'Istorija menjača i servis ulja važniji su od opreme i izgleda oglasa.',
                    'Automatik treba proveriti hladan i zagrejan, ne samo na kratkoj vožnji.',
                    'U budžetu do 8.000 evra prvi utisak često krije budući trošak.',
                ],
                'tags' => ['automatik', 'auto do 8000 evra', 'menjač', 'analiza tržišta'],
                'meta_title' => 'Najbolji automatik do 8.000 evra: šta gledati',
                'meta_description' => 'Kako birati polovan automatik do 8.000 evra: servis menjača, istorija ulja, probna vožnja, oprema i realna cena kupovine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Toyota Yaris ili Honda Jazz: gradski auto koji lakše opravdava cenu',
                'slug' => 'toyota-yaris-ili-honda-jazz-gradski-auto-koji-lakse-opravdava-cenu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Yaris i Jazz su racionalni gradski favoriti, ali kupac i dalje mora da bira između reputacije, prostora, potrošnje i realnog stanja polovnjaka.',
                'content' => <<<'TEXT'
Toyota Yaris i Honda Jazz često privlače kupce koji žele gradski auto bez velikih iznenađenja. Obe opcije deluju racionalno, ali racionalna kupovina ne znači automatski i laka kupovina. Kada su modeli traženi i dobro kotirani, tržište lako doda cenu samo na osnovu reputacije.

Yaris ima smisla kada kupac traži manji, pregledan auto za grad i želi poznatu reputaciju Toyote. Hibridne verzije dodatno privlače pažnju zbog gradske potrošnje, ali to ne znači da svaki primerak vredi traženu cenu. Baterija, servisni trag, stanje kočnica i opšti utisak moraju potvrditi priču koju cena pokušava da proda.

Jazz sa druge strane često nudi više praktičnosti nego što kupac očekuje od gradskog auta. Prostor, pristup kabini i fleksibilnost enterijera znaju da budu velika prednost. Ipak, kao i kod svakog racionalnog modela, dobar primerak nije isto što i svaki primerak na tržištu. Treba proveriti motor, trap, kočnice i da li stanje enterijera prati kilometražu.

Razlika između Yarisa i Jazza često nije u tome koji je auto “bolji”, nego kome više odgovara. Yaris je logičan za vozača koji hoće jednostavan gradski ritam i jak tržišni ugled. Jazz je bolji za kupca koji želi više prostora i praktičnosti bez prelaska u veću klasu. Kod oba modela servisna istorija mora imati veću težinu od same reputacije.

Najpametniji gradski auto je onaj koji najbolje uklapa cenu, stanje i tvoj način vožnje. Ako je Yaris preskup za ono što nudi, Jazz može imati više smisla. Ako Jazz nema jasan servisni trag, Toyota reputacija može biti mirnija opcija. U oba slučaja, tržište ne treba pratiti slepo, nego kroz konkretan primerak.
TEXT,
                'highlights' => [
                    'Yaris i Jazz imaju smisla samo kada stanje opravdava reputaciju i cenu.',
                    'Yaris je jači u gradskoj jednostavnosti, a Jazz u praktičnosti prostora.',
                    'Kod oba modela servisna istorija mora imati veću težinu od imena modela.',
                ],
                'tags' => ['Toyota Yaris', 'Honda Jazz', 'gradski auto', 'poređenje'],
                'meta_title' => 'Toyota Yaris ili Honda Jazz: koji gradski auto kupiti',
                'meta_description' => 'Poređenje polovnih Toyota Yaris i Honda Jazz modela: gradska vožnja, praktičnost, servisna istorija, potrošnja i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Ford Kuga ili Hyundai Tucson: porodični SUV koji lakše opravdava cenu',
                'slug' => 'ford-kuga-ili-hyundai-tucson-porodicni-suv-koji-lakse-opravdava-cenu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kuga i Tucson deluju kao sličan izbor za porodicu, ali razlika u motoru, opremi, kilometraži i servisnoj istoriji lako promeni koja ponuda stvarno ima smisla.',
                'content' => <<<'TEXT'
Ford Kuga i Hyundai Tucson često završavaju u istoj pretrazi kada kupac traži porodični SUV bez premium cene. Na papiru nude sličnu priču: višu poziciju sedenja, dovoljno prostora, dobar osećaj na otvorenom putu i praktičnost za svakodnevicu. U stvarnosti, razlika između dobre i prosečne kupovine mnogo više zavisi od konkretnog motora, menjača i istorije održavanja nego od same značke na haubi.

Kuga ima smisla za kupca koji želi nešto čvršći osećaj u vožnji i ne smeta mu da bira pažljivo između većih razlika među primercima. Kod nje je važno da proveriš servisnu istoriju, stanje trapa, menjača i to kako je auto korišćen. SUV koji je mnogo vremena proveo po gradu, preko ivičnjaka i sa zanemarenim redovnim servisima može vrlo brzo izgubiti svaku početnu uštedu.

Tucson je često logičan izbor kada kupac traži mirniji porodični auto, mlađe godište za isti novac ili bogatiju opremu. Ipak, ni kod Tucsona ne treba kupovati samo utisak iz kabine. Proveri da li stanje enterijera prati kilometražu, kako se auto ponaša na lošem asfaltu i da li dokumentacija potvrđuje priču prodavca. Lep ekran i uredne fotografije ne znače mnogo ako servisni trag nije potpun.

Kod oba modela motor mora da prati način vožnje. Dizel ima smisla za otvoren put i veću godišnju kilometražu, dok benzinac ili hibridna varijanta često imaju više logike za grad i mešovitu vožnju. Ako planiraš porodične rute, godišnji odmor i duže relacije, proveri i realan gepek, zadnju klupu i koliko će prva ulaganja posle kupovine promeniti računicu.

Najbolja kupovina između Kuge i Tucsona nije nužno jeftiniji auto. To je primerak kod kog cena, stanje i servisna istorija drže istu priču. Ako je Kuga bolje održavana od prosečnog Tucsona, ona je bolja kupovina. Ako Tucson nudi mlađe godište, uredniju dokumentaciju i manje nepoznanica, mirniji je izbor za porodični budžet.
TEXT,
                'highlights' => [
                    'Kuga i Tucson treba porediti kroz konkretan motor, menjač i servisnu istoriju, ne samo kroz opremu.',
                    'Porodični SUV nema smisla ako prva ulaganja posle kupovine pojedu razliku u ceni.',
                    'Najmirniji izbor je primerak sa manje nepoznanica, a ne model sa boljim marketingom.',
                ],
                'tags' => ['Ford Kuga', 'Hyundai Tucson', 'porodični SUV', 'poređenje'],
                'meta_title' => 'Ford Kuga ili Hyundai Tucson: koji polovni SUV kupiti',
                'meta_description' => 'Poređenje polovnih Ford Kuga i Hyundai Tucson modela: porodični SUV, motori, održavanje, oprema, kilometraža i realna vrednost oglasa.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Toyota RAV4 ili Kia Sportage: SUV za porodicu kada tražiš mirniji račun',
                'slug' => 'toyota-rav4-ili-kia-sportage-suv-za-porodicu-kada-trazis-mirniji-racun',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'RAV4 i Sportage su česti izbori za porodicu, ali stvarna isplativost zavisi od pogona, godišta, servisnih tragova i toga koliko tržište traži samo zbog reputacije.',
                'content' => <<<'TEXT'
Toyota RAV4 i Kia Sportage privlače kupce koji žele SUV za porodicu, duži put i svakodnevicu, ali bez ulaska u premium troškove. Oba modela imaju dobru reputaciju, oba drže cenu bolje od proseka i oba mogu delovati kao sigurna kupovina. Upravo zbog toga ih treba gledati hladne glave, jer reputacija lako podigne cenu više nego što podigne stvarnu vrednost konkretnog primerka.

RAV4 ima snažan tržišni ugled i često uliva više poverenja kupcu koji želi mirniji dugoročni odnos sa autom. To, međutim, ne znači da svaki oglas vredi traženu cenu. Kod RAV4 proveri servisnu istoriju, stanje hibridnog sistema ako je hibrid, tragove terenske ili gradske eksploatacije i da li stanje enterijera prati kilometražu. Auto sa Toyota značkom nije automatski dobra kupovina ako su tragovi održavanja tanki.

Sportage je često racionalnija alternativa kada kupac želi više opreme ili mlađe godište za isti novac. To može biti velika prednost, ali samo ako elektronika, trap, motor i menjač ne skrivaju trošak koji će se pojaviti odmah posle kupovine. Kod popularnih porodičnih SUV modela oprema lako skrene pažnju sa važnijih pitanja: šta je rađeno, kada i koliko ulaganja sledi.

U porodičnoj upotrebi važni su detalji koje oglas retko naglašava. Proveri pristup zadnjoj klupi, širinu otvora gepeka, preglednost, stanje klima sistema i realnu potrošnju u uslovima u kojima ćeš voziti. Ako planiraš mnogo grada, hibridna opcija može imati smisla. Ako su relacije duže, dizel ili benzinac možda nude bolji odnos cene i budućih troškova, u zavisnosti od istorije primerka.

RAV4 je bolji izbor kada dobijaš proverenu istoriju i smireniju reputaciju koja nije naduvana samo cenom. Sportage ima više smisla kada za isti novac nudi mlađi, očuvaniji i dokumentovaniji primerak. Između ova dva SUV-a ne pobedi model koji je popularniji, nego onaj koji ostavlja manje otvorenih pitanja posle pregleda.
TEXT,
                'highlights' => [
                    'RAV4 reputacija ima smisla samo kada istorija i stanje opravdavaju višu cenu.',
                    'Sportage često nudi bolji odnos godišta i opreme, ali samo uz proverenu mehaniku i elektroniku.',
                    'Porodični SUV treba meriti kroz realnu upotrebu, ne samo kroz izgled i ugled modela.',
                ],
                'tags' => ['Toyota RAV4', 'Kia Sportage', 'SUV', 'porodica'],
                'meta_title' => 'Toyota RAV4 ili Kia Sportage: koji SUV za porodicu',
                'meta_description' => 'Poređenje polovnih Toyota RAV4 i Kia Sportage modela: porodica, hibrid ili benzinac, oprema, servisna istorija i realna cena SUV oglasa.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Peugeot 3008: crossover koji traži proveru elektronike i servisa',
                'slug' => 'polovni-peugeot-3008-crossover-koji-trazi-proveru-elektronike-i-servisa',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Peugeot 3008 često deluje kao moderan i poželjan polovnjak, ali dobra kupovina zavisi od istorije održavanja, stanja elektronike i toga kako je auto korišćen.',
                'content' => <<<'TEXT'
Peugeot 3008 je jedan od onih polovnjaka koji lako osvoje kupca na prvi pogled. Dizajn kabine, oprema i crossover forma često deluju modernije od proseka tržišta. Problem je što privlačan oglas nije isto što i mirna kupovina. Kod 3008 najvažnije je da se ispod dobrog utiska nalazi uredna dokumentacija i jasan servisni trag.

Prvo proveri elektroniku i svu opremu koju oglas ističe kao prednost. Veliki ekran, kamere, senzori, asistencije, digitalna tabla i klima moraju raditi bez grešaka i improvizacija. Kod automobila koji prodaje tehnologiju kao deo identiteta, neispravna oprema nije sitnica nego direktan trošak. Zato pregled ne sme stati na motoru i karoseriji.

Drugo, servisna istorija mora biti dosledna. Ako 3008 ima dizel motor, proveri DPF, EGR, turbinu i način vožnje prethodnog vlasnika. Ako je benzinac, fokus prebaci na redovnost održavanja, potrošnju ulja, hladan start i tragove gradske eksploatacije. Kod automatika traži dokaz o održavanju menjača, a ne samo priču da radi mirno.

Treće, pogledaj koliko cena zaista ima smisla u odnosu na alternativne modele. 3008 često drži dobru cenu jer izgleda atraktivno i ostavlja moderniji utisak od mnogih konkurenata. To je opravdano samo kada konkretan primerak nema rupe u dokumentaciji, ne krije sitne elektronske kvarove i ne traži velika početna ulaganja odmah po kupovini.

Najbolji polovni 3008 je onaj kod kog sve što deluje moderno radi i na papiru i u vožnji. Ako je istorija jasna, elektronika uredna, menjač miran i cena u skladu sa tržištem, 3008 može biti vrlo dobra kupovina. Ako oglas prodaje samo utisak, a odgovori na pitanja ostaju magloviti, bolji je hladan korak unazad nego skupa improvizacija posle kupovine.
TEXT,
                'highlights' => [
                    'Kod Peugeot 3008 modela elektronika i oprema moraju se proveriti jednako ozbiljno kao motor i trap.',
                    'Servisna istorija je važnija od modernog izgleda kabine i bogate liste opreme.',
                    'Atraktivan crossover nema smisla ako odmah traži ulaganja koja oglas skriva.',
                ],
                'tags' => ['Peugeot 3008', 'crossover', 'elektronika', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Peugeot 3008: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Peugeot 3008 modela: elektronika, servisna istorija, automatski menjač, dizel i benzin rizici i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Službeni auto na oglasu: kada je dobra kupovina, a kada samo lepa priča',
                'slug' => 'sluzbeni-auto-na-oglasu-kada-je-dobra-kupovina-a-kada-samo-lepa-prica',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Službeni automobili često imaju uredniju servisnu evidenciju od proseka, ali mogu skrivati intenzivnu eksploataciju koju kupac mora da pročita iza kilometraže i fotografija.',
                'content' => <<<'TEXT'
Službeni auto na oglasu često zvuči kao dobra vest. Kupac očekuje redovne servise, urednu dokumentaciju i ozbiljniji odnos prema održavanju nego kod prosečnog privatnog vlasnika. To zaista može biti prednost, ali samo ako razumeš i drugu stranu takvog automobila: službena vozila često prelaze mnogo kilometara, koriste ih različiti vozači i retko imaju nežan svakodnevni režim.

Prva stvar koju treba proveriti jeste servisna evidencija. Ako je automobil bio deo firme, trebalo bi da postoji jasniji trag o održavanju nego kod prosečnog privatnog auta. Računi, intervali, tehnički pregledi i servisni unosi moraju imati kontinuitet. Kada takav trag postoji, službeni auto može imati više smisla nego privatni oglas sa malom kilometražom i slabom dokumentacijom.

Druga stvar je tip korišćenja. Auto koji je radio duge relacije na otvorenom putu može biti manje rizičan od gradskog automobila sa malom kilometražom i mnogo hladnih startova. Ali službeni auto koji je menjao vozače, prelazio ivičnjake, nosio teret i retko dobijao pažnju između servisa može biti umorniji nego što brojke govore. Zato trap, enterijer, kvačilo, menjač i tragovi habanja moraju da se slažu sa pričom.

Treće, ne dozvoli da uredna flotna priča zameni pregled. Službeni auto nije automatski bolji auto, već samo auto kod kog neki delovi istorije mogu biti jasniji. Pregled karoserije, probna vožnja, dijagnostika i provera svih potrošača i dalje su obavezni. Kupac ne kupuje servisni PDF, nego konkretan polovnjak koji mora da radi bez iznenađenja.

Najbolja kupovina među službenim automobilima je primerak koji kombinuje urednu dokumentaciju sa stanjem koje to potvrđuje. Ako je trag održavanja jak, a automobil ne pokazuje znake preteranog habanja, službeni auto može biti odlična prilika. Ako je priča o floti jedina prednost, a stanje ne prati papirologiju, onda je to samo lep marketinški okvir za prosečan oglas.
TEXT,
                'highlights' => [
                    'Službeni auto može biti prednost samo kada servisna evidencija stvarno postoji i ima kontinuitet.',
                    'Mnogo različitih vozača i intenzivna eksploatacija često ostavljaju trag koji kilometraža ne pokazuje odmah.',
                    'Flotna istorija ne menja obavezu pregleda trapa, menjača, enterijera i karoserije.',
                ],
                'tags' => ['službeni auto', 'servisna istorija', 'kilometraža', 'provera vozila'],
                'meta_title' => 'Službeni auto na oglasu: prednost ili rizik',
                'meta_description' => 'Kako proveriti službeni auto na oglasu: servisna istorija, flotna eksploatacija, kilometraža, trap, enterijer i realna vrednost ponude.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Auto do 7.000 evra za grad i put: zašto dobar benzinac često pobeđuje',
                'slug' => 'auto-do-7000-evra-za-grad-i-put-zasto-dobar-benzinac-cesto-pobeduje',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'U budžetu do 7.000 evra mnogi jure dizel zbog potrošnje, ali dobar benzinac često nudi mirniju kupovinu kada se vozi i grad i otvoren put.',
                'content' => <<<'TEXT'
Budžet do 7.000 evra otvara veliki broj polovnjaka, ali i veliki broj pogrešnih pretpostavki. Mnogi kupci po automatizmu traže dizel jer žele manju potrošnju i osećaj da kupuju „isplativiji“ auto. U praksi, u ovom cenovnom rangu dobar benzinac često bude mirnija i pametnija kupovina, posebno kada se auto vozi i po gradu i na otvorenom putu.

Razlog je jednostavan: kod jeftinijih polovnjaka stanje i istorija znače više od same vrste goriva. Dizel može biti sjajan izbor za vozača koji prelazi mnogo kilometara i vozi duge relacije, ali u budžetu do 7.000 evra vrlo često dolazi sa većim brojem kilometara, umornijim DPF-om, EGR-om, turbinom i skupim stvarima koje se ne vide na prvoj fotografiji. Niska potrošnja tada postaje najskuplji argument iz oglasa.

Dobar benzinac u ovom budžetu često nudi jednostavniju mehaniku, manje rizika od gradske vožnje i lakšu računicu za kupca koji ne prelazi ekstremnu kilometražu. To ne znači da pregled može biti opušten. Potrošnja ulja, lanac ili kaiš, hladan start, trap, kočnice i servisni trag i dalje su presudni. Ali broj potencijalno skupih dizel nepoznanica obično je manji.

Ako auto koristiš za posao po gradu, vikendom za put i povremeno za dužu rutu, benzinac često bolje prati takav ritam. Dizel se isplati tek kada stvarno radi ono za šta je napravljen. Kupac treba da sabere godišnju kilometražu, režim vožnje i očekivana ulaganja, umesto da slepo prati potrošnju na papiru ili reputaciju određenog motora.

Najbolji auto do 7.000 evra nije onaj koji najmanje troši na oglasu, nego onaj koji posle kupovine traži najmanje neplaniranih ulaganja. Zato dobar benzinac u ovom rangu često pobeđuje dizel. Nije glamurozniji, ali zna da bude racionalniji izbor za stvarni život i realan budžet.
TEXT,
                'highlights' => [
                    'U budžetu do 7.000 evra stanje automobila često je važnije od same vrste goriva.',
                    'Dobar benzinac je često mirnija kupovina za mešovitu vožnju nego umoran dizel sa velikom kilometražom.',
                    'Potrošnja goriva nema smisla kao argument ako posle kupovine sledi skup servisni niz.',
                ],
                'tags' => ['auto do 7000 evra', 'benzinac', 'gradska vožnja', 'analiza tržišta'],
                'meta_title' => 'Auto do 7.000 evra: zašto benzinac često ima više smisla',
                'meta_description' => 'Kako izabrati polovan auto do 7.000 evra za grad i put: benzinac ili dizel, kilometraža, održavanje, DPF rizici i realna cena kupovine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Toyota Corolla ili Škoda Octavia: porodični kompakt kada tržište traži previše',
                'slug' => 'toyota-corolla-ili-skoda-octavia-porodicni-kompakt-kada-trziste-trazi-previse',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Corolla i Octavia imaju jaku reputaciju i često skuplje oglase od proseka. Dobra kupovina zavisi od motora, istorije i toga da li cena prati stvarno stanje.',
                'content' => <<<'TEXT'
Toyota Corolla i Škoda Octavia spadaju među najracionalnije izbore kada kupac traži porodični kompakt koji može da služi i za grad i za put. Upravo zbog te reputacije tržište im često daje višu cenu nego što kupac očekuje. Zato pravo pitanje nije koji model je poznatiji kao dobar polovnjak, nego kada konkretan oglas zaista opravdava premiju.

Corolla ima smisla za kupca koji traži smireniju kupovinu i automobil koji deluje kao dugoročno manje stresan izbor. Posebno je privlačna kada je istorija održavanja jasna, enterijer odgovara kilometraži i nema tragova improvizovanih popravki. Problem nastaje kada se reputacija Toyote koristi kao izgovor da se traži više novca za prosečan primerak bez dovoljno dokaza.

Octavia je često praktičnija za porodicu koja želi više prostora, veći gepek i širok izbor motora i opreme. Ipak, veliki broj oglasa ne znači i veliki broj dobrih primeraka. Kod Octavie treba pažljivo proveriti način prethodnog korišćenja, servisnu istoriju, stanje trapa i logiku same cene. Posebno kod dizela nije dovoljno da auto lepo izgleda na fotografijama ako su DPF, EGR ili menjač ostavljeni sledećem vlasniku.

Kupac koji poredi Corollu i Octaviju mora prvo da odredi način vožnje. Za grad, kraće relacije i mirniji tempo Corolla često ima više smisla. Za mnogo prostora, duži put i lakše poređenje velikog broja oglasa Octavia može biti racionalniji izbor. U oba slučaja presuđuje primerak, ne fama oko modela.

Najskuplja greška kod oba automobila je plaćanje reputacije umesto stanja. Ako Corolla traži premiju, mora ponuditi proverljivu istoriju i manje nepoznanica. Ako Octavia deluje povoljnije, mora dokazati da niža cena ne skriva veća početna ulaganja. Najbolja kupovina nije auto sa jačim ugledom, nego onaj koji ostavlja najmanje otvorenih pitanja posle pregleda.
TEXT,
                'highlights' => [
                    'Corolla i Octavia često nose reputacionu premiju koju treba proveriti kroz stvarno stanje.',
                    'Corolla je mirniji izbor za kupca koji traži manje stresa, dok Octavia često nudi više praktičnosti.',
                    'Kod oba modela cenu treba meriti kroz dokumentaciju, trap, menjač i servisne tragove.',
                ],
                'tags' => ['Toyota Corolla', 'Škoda Octavia', 'porodični kompakt', 'poređenje'],
                'meta_title' => 'Toyota Corolla ili Škoda Octavia: koji kompakt kupiti',
                'meta_description' => 'Poređenje polovnih Toyota Corolla i Škoda Octavia modela: porodični kompakt, cena, dizel rizici, servisna istorija i stvarna isplativost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Audi A3 ili Mazda 3: kompakt za kupca koji ne želi skupo iznenađenje',
                'slug' => 'audi-a3-ili-mazda-3-kompakt-za-kupca-koji-ne-zeli-skupo-iznenadenje',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Audi A3 privlači premium utiskom, Mazda 3 vozačkim karakterom, ali kod polovnjaka razliku prave istorija, motor i koliko je prethodni vlasnik štedeo na održavanju.',
                'content' => <<<'TEXT'
Audi A3 i Mazda 3 privlače kupca koji želi kompaktan automobil iznad proseka, ali ne želi da uđe u priču sa velikim SUV-om ili starijom premium limuzinom. Na papiru oba modela nude dovoljno kvaliteta da deluju kao pametna kupovina. U praksi se razlika najviše vidi u tome šta kupac zapravo plaća: premium osećaj, vozački karakter ili nečije preskočeno održavanje.

Audi A3 ima smisla kada kupac želi bolji enterijer, jači premium utisak i model koji tržište lako razume. Međutim, kod polovnog A3 taj utisak vredi samo ako primerak ima jasnu servisnu istoriju, uredan menjač i stanje enterijera koje prati kilometražu. Auto koji izgleda luksuznije od konkurencije lako sakrije i skuplju listu sitnica koje će novi vlasnik morati da finansira.

Mazda 3 često privlači kupca koji želi drugačiji karakter i manje oslanjanja na sam premium znak. Dobar benzinac u Mazdi 3 može biti vrlo mirna kupovina, ali samo ako je održavanje bilo uredno i ako nema tragova gradske vožnje, loših popravki ili zanemarenog trapa. Reputacija dobrog vozačkog auta ne znači da je svaki oglas automatski vredan tražene cene.

Kod oba modela način vožnje odlučuje više nego priča prodavca. Ako voziš grad i umerenu godišnju kilometražu, benzinac često ima više smisla. Ako porediš dizelaše, pregled DPF-a, EGR-a, turbine i servisnih intervala mora biti deo odluke. Premium izgled ili dobra oprema ne rešavaju lošu servisnu priču.

Audi A3 je racionalan kada dobijaš zaista bolji primerak, a ne samo skuplji znak. Mazda 3 ima više smisla kada tražiš pošten benzinac sa manje premium nepoznanica. Najpametniji kompakt između ova dva modela je onaj koji kupuješ kroz dokumentaciju i stanje, a ne kroz reputaciju koju oglas pokušava da unovči.
TEXT,
                'highlights' => [
                    'Audi A3 ima smisla samo kada premium cena prati jasnu istoriju i uredan menjač.',
                    'Mazda 3 može biti mirnija kupovina ako je benzinac održavan bez preskakanja servisa.',
                    'Kod oba modela reputacija ne sme biti zamena za pregled i proveru mehanike.',
                ],
                'tags' => ['Audi A3', 'Mazda 3', 'kompakt', 'poređenje'],
                'meta_title' => 'Audi A3 ili Mazda 3: koji kompakt polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Audi A3 i Mazda 3 modela: premium utisak, benzinac ili dizel, servisna istorija, menjač i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Nissan Qashqai: kada crossover reputacija stvarno ima smisla',
                'slug' => 'polovni-nissan-qashqai-kada-crossover-reputacija-stvarno-ima-smisla',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Qashqai je godinama jedan od omiljenih crossover modela, ali dobra kupovina zavisi od istorije, menjača, trapa i toga da li cena prati konkretan primerak.',
                'content' => <<<'TEXT'
Nissan Qashqai je jedan od modela koji su toliko prisutni na tržištu da kupcu lako deluje kao sigurna kupovina. Mnogo oglasa, prepoznatljivo ime i crossover forma stvaraju utisak da je dovoljno samo izabrati godište i budžet. U praksi, polovni Qashqai ima smisla tek kada se reputacija potvrdi konkretnim stanjem i istorijom održavanja.

Prvo proveri kako je automobil korišćen. Qashqai često kupuju porodice i vozači koji žele više visine za gradsku i prigradsku vožnju. To može značiti praktičnu upotrebu, ali i mnogo ivičnjaka, kratkih relacija i umoran trap. Pregled preko lošeg asfalta, puno skretanje i kočenje na probnoj vožnji često otkriju više od same liste opreme.

Drugo, motor i menjač moraju imati jasan servisni trag. Ako je dizel, gledaj DPF, EGR, turbinu i koliko se auto uklapa u tvoj režim vožnje. Ako je benzinac, fokus prebaci na redovnost servisa, hladan start i stanje kvačila ili automatika. Qashqai koji je samo „lep na slikama“ lako postaje skup kada iza lepog krosovera ostanu neodržavane mehaničke stavke.

Treće, uporedi cenu sa drugim crossover modelima i sa običnim kompaktnim automobilima. Nekad Qashqai opravdava razliku kroz praktičnost i preglednost. Nekad kupac samo plaća to što je model popularan. Ako isti novac donosi mlađi i dokumentovaniji automobil druge klase, onda crossover reputacija sama po sebi nije dovoljan argument.

Najbolji polovni Qashqai je onaj koji nema mnogo nepoznanica. Ako servisna istorija postoji, trap je uredan, menjač radi kako treba i cena nije naduvana samo zbog imena modela, Qashqai može biti vrlo dobra kupovina. Ako sve zavisi od toga da veruješ prodavcu na reč, crossover forma ne menja računicu.
TEXT,
                'highlights' => [
                    'Qashqai ima smisla samo kada popularnost modela prati realno dobro stanje konkretnog primerka.',
                    'Trap, menjač i režim prethodne vožnje moraju biti deo pregleda, ne samo oprema i izgled.',
                    'Crossover reputacija ne vredi ako cena beži od dokumentacije i servisnih tragova.',
                ],
                'tags' => ['Nissan Qashqai', 'crossover', 'kupovina polovnjaka', 'servisna istorija'],
                'meta_title' => 'Polovni Nissan Qashqai: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Nissan Qashqai modela: crossover reputacija, trap, menjač, dizel ili benzinac i realna cena primerka.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Jedan vlasnik u oglasu: kada znači više, a kada ne menja ništa',
                'slug' => 'jedan-vlasnik-u-oglasu-kada-znaci-vise-a-kada-ne-menja-nista',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Oznaka „jedan vlasnik“ može biti ozbiljna prednost, ali samo kada je prate dokumentacija, stanje i logična istorija korišćenja. Sama po sebi ne garantuje dobru kupovinu.',
                'content' => <<<'TEXT'
Jedan vlasnik je jedna od onih fraza koja u oglasima odmah diže pažnju kupca. Zvuči kao dokaz urednog korišćenja, manjeg rizika i jasnije istorije. To zaista može biti prednost, ali nije automatska garancija dobrog polovnjaka. Kupac ne treba da plati više samo zato što je oglas napisao jednu lepu stvar koju još nije dokazao.

Prava vrednost te informacije postoji kada se može povezati sa dokumentacijom. Ako je automobil zaista imao jednog vlasnika, trebalo bi da postoji jasan trag kroz saobraćajnu istoriju, račune, servisne intervale, tehničke preglede i dosledno stanje enterijera i karoserije. Kada se ti tragovi poklope, jedan vlasnik zaista nosi veću težinu.

Problem nastaje kada kupac poveruje da jedan vlasnik automatski znači pažljivo održavanje. Jedan vlasnik može voziti odlično, ali može i godinama odlagati ulaganja, prelaziti ivičnjake, preskakati servis ili popravljati auto najjeftinije moguće. Auto sa više vlasnika i urednom dokumentacijom ponekad je bolja kupovina od vozila koje je ceo život bilo kod jedne osobe, ali bez jasnog traga šta je stvarno rađeno.

Zato tu oznaku treba posmatrati kao signal za dodatna pitanja, ne kao dokaz. Pitaj koliko je dugo auto bio kod vlasnika, zašto se prodaje, gde je servisiran i šta je skoro urađeno. Pogledaj da li se stanje volana, sedišta, pedala i karoserije slaže sa oglašenom pričom. Tek tada jedan vlasnik dobija stvarnu vrednost.

Najpametnije je da frazu „jedan vlasnik“ spustiš sa nivoa obećanja na nivo provere. Ako dokumentacija, servisni trag i stanje automobila potvrde oglas, to jeste ozbiljna prednost. Ako ne potvrde, onda je to samo marketinška rečenica koja ne menja stvarnu računicu kupovine.
TEXT,
                'highlights' => [
                    'Jedan vlasnik vredi tek kada ga potvrde dokumentacija, stanje i logična servisna istorija.',
                    'Jedan vlasnik ne znači automatski uredno održavanje niti manja početna ulaganja.',
                    'Tu oznaku treba tretirati kao signal za proveru, a ne kao dokaz kvaliteta automobila.',
                ],
                'tags' => ['jedan vlasnik', 'provera vozila', 'servisna istorija', 'kupovina polovnjaka'],
                'meta_title' => 'Jedan vlasnik u oglasu: prednost ili samo fraza',
                'meta_description' => 'Šta zaista znači oznaka jedan vlasnik u oglasu: dokumentacija, servisna istorija, stanje vozila i koliko ta informacija vredi pri kupovini.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#34d399', '#f8fafc'],
            ],
            [
                'title' => 'Dizel za autoput do 9.000 evra: kada računica stvarno pije vodu',
                'slug' => 'dizel-za-autoput-do-9000-evra-kada-racunica-stvarno-pije-vodu',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dizel i autoput deluju kao logičan par, ali u budžetu do 9.000 evra isplativost zavisi od kilometraže, servisnih tragova i toga koliko je motor već odradio pre prodaje.',
                'content' => <<<'TEXT'
Dizel za autoput zvuči kao najlogičnija kupovina za vozača koji prelazi mnogo kilometara. U teoriji, niža potrošnja i mirniji rad na dužim relacijama daju mu jasnu prednost. U praksi, kada budžet stane oko 9.000 evra, kupac ulazi u zonu gde se dobra dizel računica lako pokvari ako motor, turbina, DPF ili menjač već traže sledeću investiciju.

Prvo treba pošteno izračunati sopstvenu kilometražu. Ako zaista mnogo voziš otvoren put i autoput, dizel može imati smisla. Ako je autoput samo povremeni scenario, a ostatak vremena auto provodi u gradu, tada niža potrošnja nije dovoljna da opravda dizel rizike. U tom slučaju benzinac ili hibrid nekad ostavljaju mirniju ukupnu računicu.

Drugo, u ovom budžetu istorija vozila je važnija od samog tipa goriva. Mnogo dizelaša do 9.000 evra već je prošlo ozbiljnu kilometražu, često veću nego što oglas odmah sugeriše. Zato kupac mora da proveri servisne intervale, stanje turbine, DPF-a, EGR-a, dizni i eventualnog automatika. Auto koji je na autoputu nekada štedeo gorivo ne znači mnogo ako sada troši budžet na zaostalo održavanje.

Treće, dizel ima smisla samo kada kupuješ primerak koji može odmah da uđe u tvoj režim vožnje bez većih nepoznanica. Dobar autoputni auto mora biti stabilan, tih, mehanički dosledan i servisno jasan. Ako te niska potrošnja privlači više nego dokumentacija, vrlo lako ćeš kupiti račun koji samo čeka da bude ispostavljen.

Najbolji dizel za autoput do 9.000 evra nije jedan model nego dobra kombinacija stanja, istorije i tvoje realne kilometraže. Kada se ta tri dela poklope, dizel zaista pije vodu. Kada ne, priča o uštedi na gorivu samo pokrije mnogo skuplju mehaničku istinu.
TEXT,
                'highlights' => [
                    'Dizel do 9.000 evra ima smisla tek kada ga prati stvarno velika kilometraža i otvoren put.',
                    'U ovom budžetu servisna istorija i stanje DPF-a, turbine i EGR-a vrede više od same potrošnje.',
                    'Autoputna računica pada u vodu čim oglas skriva velika ulaganja posle kupovine.',
                ],
                'tags' => ['dizel', 'autoput', 'auto do 9000 evra', 'analiza tržišta'],
                'meta_title' => 'Dizel za autoput do 9.000 evra: kada se isplati',
                'meta_description' => 'Kako proceniti polovan dizel za autoput do 9.000 evra: kilometraža, DPF, EGR, turbina, servisna istorija i stvarna računica kupovine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Opel Astra ili Kia Ceed: kompakt za kupca koji želi manje iznenađenja',
                'slug' => 'opel-astra-ili-kia-ceed-kompakt-za-kupca-koji-zeli-manje-iznenadenja',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Astra i Ceed su čest izbor za kupce koji žele racionalan kompakt bez premium cene, ali razliku prave motor, servisna istorija i to koliko je oglas pošten.',
                'content' => <<<'TEXT'
Opel Astra i Kia Ceed često ulaze u isti uži izbor kada kupac traži kompaktan automobil koji treba da bude dovoljno praktičan za svaki dan, a dovoljno razuman za budžet. Na papiru oba modela nude sličnu ideju: normalne troškove, dovoljno prostora i ogroman broj oglasa za poređenje. U praksi, razlika između dobre i prosečne kupovine mnogo više zavisi od konkretnog motora i servisne istorije nego od same oznake na gepeku.

Astra često privlači kupce koji žele poznat i lako razumljiv polovnjak. Njena prednost je što se na tržištu lako upoređuje veliki broj primeraka, pa kupac brže vidi kada je cena prenaduvana. Problem nastaje kada se niža kupovna cena pogrešno protumači kao bolja kupovina. Ako su trap, menjač, dizel sistemi ili osnovno održavanje ostavljeni sledećem vlasniku, Astra brzo izgubi početnu prednost.

Ceed ima smisla za kupca koji traži nešto mirniji utisak i često bolju kombinaciju godišta, opreme i reputacije za isti novac. Ipak, ni kod Ceeda ne treba kupovati priču o urednom autu bez papira. Trap, servisni trag, stanje kabine i logika kilometraže moraju da potvrde ono što oglas pokušava da proda. Kompakt koji izgleda lepo na slikama lako postane prosečan čim se probna vožnja pretvori u listu sitnih ulaganja.

Način vožnje treba da presudi i ovde. Ako voziš uglavnom grad i umerenu kilometražu, benzinac često ima više smisla. Ako porediš dizelaše, moraš gledati DPF, EGR, turbinu i koliko je prethodni vlasnik vozio auto onako kako dizel traži. Kod oba modela dobra oprema ne rešava lošu servisnu istoriju.

Najpametniji izbor između Astre i Ceeda nije model koji deluje popularnije, nego primerak koji ostavlja manje otvorenih pitanja. Astra je bolja kada dobijaš poštenu cenu i jasnu istoriju. Ceed ima smisla kada za isti novac nudi uredniji trag održavanja i mirniji ukupni utisak. Kupac ne treba da bira između dve reputacije, nego između dva konkretna oglasa.
TEXT,
                'highlights' => [
                    'Astra i Ceed treba porediti kroz konkretan motor, servisnu istoriju i stanje, ne samo kroz cenu.',
                    'Niži oglas ne znači bolju kupovinu ako trap, menjač ili dizel sistemi čekaju sledećeg vlasnika.',
                    'Najmirniji kompakt je onaj koji posle pregleda ostavlja najmanje nepoznanica.',
                ],
                'tags' => ['Opel Astra', 'Kia Ceed', 'kompakt', 'poređenje'],
                'meta_title' => 'Opel Astra ili Kia Ceed: koji kompakt polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Opel Astra i Kia Ceed modela: benzinac ili dizel, servisna istorija, kompaktni troškovi i realna isplativost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Hyundai Tucson ili Peugeot 3008: porodični crossover kada dizajn ne sme da odluči',
                'slug' => 'hyundai-tucson-ili-peugeot-3008-porodicni-crossover-kada-dizajn-ne-sme-da-odluci',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Tucson i 3008 privlače slične kupce, ali jedan oglas ne sme pobediti samo zato što lepše izgleda. Presuđuju istorija, motor, elektronika i realna cena održavanja.',
                'content' => <<<'TEXT'
Hyundai Tucson i Peugeot 3008 često stoje jedan pored drugog u pretragama kupaca koji žele porodični crossover sa više prostora, modernijom kabinom i osećajem da voze nešto više od običnog kompakta. Tu se lako napravi prva greška: dizajn i prvi utisak počnu da odlučuju pre nego što dokumentacija i probna vožnja dobiju glavnu reč.

Tucson ima smisla za kupca koji želi smireniji porodični auto i primerak koji često deluje racionalnije na duži rok. Njegova prednost je što tržište obično lakše prihvata korektan porodični SUV bez mnogo teatralnosti. Ipak, ni Tucson nije dobar samo zato što izgleda uredno. Trap, menjač, stanje enterijera i doslednost servisne istorije moraju pokazati da oglas nije samo dobar paket fotografija.

Peugeot 3008 češće osvaja kupca izgledom kabine i utiskom modernijeg automobila. To može biti realna prednost ako oprema zaista radi kako treba i ako je elektronika u skladu sa pričom prodavca. Problem je što kod ovakvog crossovera kvarovi na ekranima, kamerama, senzorima ili asistencijama nisu kozmetika nego trošak. Zato 3008 traži pažnju i na stvarima koje oglas retko prikazuje dovoljno jasno.

Kod oba modela motor mora pratiti režim vožnje. Za duže relacije i više kilometara dizel može imati smisla, ali samo uz urednu istoriju DPF-a, EGR-a i menjača. Za gradsku i mešovitu vožnju benzinac ili drugačija konfiguracija često ostavljaju mirniji servisni trag. Crossover forma sama po sebi ne popravlja pogrešan izbor motora.

Najbolji porodični crossover između Tucsona i 3008 nije onaj koji izgleda skuplje, nego onaj koji je servisno i tehnički dosledniji. Ako Tucson nudi jasniju istoriju i manje elektronskih nepoznanica, to je ozbiljna prednost. Ako 3008 ima urednu dokumentaciju, proverenu opremu i cenu koja ne kažnjava samo dizajn, onda njegov karakter ima smisla. Kupac treba da bira stabilniji oglas, ne lepši poster.
TEXT,
                'highlights' => [
                    'Dizajn ne sme presuditi pre nego što istorija, elektronika i probna vožnja kažu glavnu reč.',
                    'Tucson je racionalniji kada nudi jasniju dokumentaciju i manje nepoznanica.',
                    'Kod 3008 elektronika i oprema moraju biti proverene jednako ozbiljno kao motor i trap.',
                ],
                'tags' => ['Hyundai Tucson', 'Peugeot 3008', 'crossover', 'poređenje'],
                'meta_title' => 'Hyundai Tucson ili Peugeot 3008: koji crossover kupiti',
                'meta_description' => 'Poređenje polovnih Hyundai Tucson i Peugeot 3008 modela: porodični crossover, dizajn, elektronika, motor, servisna istorija i cena održavanja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Hyundai i30: kompakt koji ima smisla samo uz urednu istoriju',
                'slug' => 'polovni-hyundai-i30-kompakt-koji-ima-smisla-samo-uz-urednu-istoriju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Hyundai i30 može biti vrlo racionalan polovnjak, ali samo kada cenu prati proverljiva servisna priča, poštena kilometraža i primerak bez sakrivenih početnih ulaganja.',
                'content' => <<<'TEXT'
Hyundai i30 je jedan od onih polovnjaka koji kupcu retko izaziva previše emocija, ali često ima vrlo smislen odgovor na pitanje šta zapravo treba kupiti. Upravo zato lako dobije etiketu sigurne kupovine. Problem je što nijedan kompakt nije siguran samo zato što ima mirnu reputaciju. Polovni i30 ima smisla tek kada konkretan primerak potvrdi priču kroz stanje i istoriju održavanja.

Prvo proveri koliko je oglas pošten prema kilometraži i načinu korišćenja. i30 često kupuju vozači koji žele racionalan auto za grad, posao i porodicu, pa na tržištu ima i vrlo dobrih i vrlo umornih primeraka. Stanje volana, sedišta, pedala i trapa mora imati logiku u odnosu na broj koji piše u oglasu. Ako enterijer priča jednu priču, a kilometraža drugu, kupac treba da uspori.

Drugo, motor i menjač traže jasan servisni trag. Kod benzinca proveri hladan start, potrošnju ulja i redovnost održavanja. Kod dizela gledaj DPF, EGR, turbinu i da li se način vožnje prethodnog vlasnika uklapa sa motorom koji kupuješ. Ako postoji automatik, servis menjača mora biti deo dokumentacije, ne samo deo obećanja.

Treće, poenta i30 nije da bude najjeftiniji oglas u klasi, nego da za dati novac ostavi najmanje nepoznanica. Kupac koji juri samo nižu cenu lako će završiti sa kompaktnim autom koji izgleda racionalno, a traži niz sitnih ulaganja odmah po kupovini. Dobar i30 vredi malo više samo kada taj višak stvarno kupuje mir.

Najbolji polovni i30 je onaj koji ne pokušava da glumi nešto što nije. Ako je dokumentacija jasna, stanje pošteno i cena u skladu sa tržištem, i30 može biti odlična svakodnevna kupovina. Ako oglas deluje previše dobar za ono što nudi, onda racionalni kompakt vrlo brzo postaje još jedan skup kompromis.
TEXT,
                'highlights' => [
                    'Hyundai i30 ima smisla kada reputaciju prati proverljiva servisna istorija i pošteno stanje.',
                    'Kilometraža mora imati logiku kroz enterijer, trap i način korišćenja vozila.',
                    'Racionalan kompakt prestaje da bude racionalan čim prva ulaganja pojedu razliku u ceni.',
                ],
                'tags' => ['Hyundai i30', 'kompakt', 'kupovina polovnjaka', 'servisna istorija'],
                'meta_title' => 'Polovni Hyundai i30: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Hyundai i30 modela: kilometraža, benzinac ili dizel, servisna istorija, menjač i realna cena primerka.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Dilerska garancija u oglasu: koliko stvarno vredi kad kupuješ polovan auto',
                'slug' => 'dilerska-garancija-u-oglasu-koliko-stvarno-vredi-kad-kupujes-polovan-auto',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Garancija iz oglasa može biti prava vrednost, ali samo ako kupac razume šta pokriva, koliko traje i šta sve ostaje van zaštite čim auto krene kući.',
                'content' => <<<'TEXT'
Dilerska garancija u oglasu zvuči kao ozbiljna prednost. Kupac dobija utisak da deo rizika ostaje kod prodavca i da polovan auto dolazi sa dodatnim slojem sigurnosti. To zaista može biti vredno, ali samo ako se pročita šta ta garancija zapravo znači. Mnogi oglasi koriste reč garancija mnogo šire nego što sam dokument zaista pokriva.

Prva stvar koju treba proveriti jeste obim pokrića. Da li garancija važi samo za motor i menjač, da li uključuje elektriku, turbinu, DPF, hibridne komponente ili samo usko definisane kvarove? Kupac ne treba da pretpostavlja. Ono što nije jasno napisano, kao da nije ni obećano. Garancija vredi onoliko koliko je precizna i primenljiva u stvarnom kvaru.

Drugo, važni su trajanje, uslovi i procedura. Nije isto da li garancija traje tri meseca, godinu dana ili određen broj kilometara. Nije isto ni da li kupac mora servisirati auto tačno gde prodavac kaže, u kom roku mora prijaviti problem i kako se odlučuje da li kvar zaista ulazi u pokriće. Ako je procedura nejasna ili komplikovana, marketinška vrednost garancije je veća od praktične.

Treće, garancija ne menja obavezu pregleda pre kupovine. Kupac ne treba da preskoči trap, karoseriju, elektroniku i probnu vožnju samo zato što diler nudi zaštitu. Dilerska garancija može biti dodatni plus, ali ne treba da služi kao zamena za pregled koji bi otkrio loš primerak pre potpisa ugovora.

Najviše vredi ona garancija koja je kratka, jasna i poštena, uz automobil koji je već dobar sam po sebi. Ako diler nudi uredan primerak, jasne uslove i konkretnu zaštitu za ključne sklopove, to jeste prednost. Ako se garancija koristi samo kao reč koja treba da umiri kupca bez stvarnog sadržaja, onda ne menja mnogo. Dobar polovan auto kupuje se pregledom, a garancija može biti samo dodatna mreža, ne glavni razlog kupovine.
TEXT,
                'highlights' => [
                    'Dilerska garancija vredi samo kada je jasno napisano šta pokriva, a šta ne.',
                    'Trajanje, uslovi servisa i procedura prijave kvara jednako su važni kao sama reč garancija.',
                    'Garancija ne menja obavezu detaljnog pregleda pre kupovine polovnog automobila.',
                ],
                'tags' => ['dilerska garancija', 'provera vozila', 'polovan auto', 'kupovina'],
                'meta_title' => 'Dilerska garancija u oglasu: koliko zaista vredi',
                'meta_description' => 'Kako čitati dilersku garanciju kod polovnog auta: šta pokriva, koliko traje, koji su uslovi i zašto nije zamena za pregled vozila.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Hibrid za grad do 12.000 evra: kada viša cena još uvek ima smisla',
                'slug' => 'hibrid-za-grad-do-12000-evra-kada-visa-cena-jos-uvek-ima-smisla',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Gradski hibrid često košta više od uporedivog benzinca, ali ta razlika nekad kupuje mirniju svakodnevicu. Važno je znati kada premija ima smisla, a kada ne.',
                'content' => <<<'TEXT'
Hibrid za grad do 12.000 evra deluje kao vrlo racionalna ideja: automatik utisak, niža potrošnja u gradskoj vožnji i reputacija mirnijeg svakodnevnog auta. Problem je što tržište to zna i često traži više novca nego što kupac planira. Zato pravo pitanje nije da li je hibrid dobar, nego kada viša cena još uvek kupuje stvarnu vrednost, a kada samo prati trend.

Prvo treba gledati kako se auto zaista koristi. Ako voziš grad, kratke relacije, stani-kreni ritam i ne prelaziš ogromnu kilometražu na autoputu, hibrid može imati vrlo smislen profil. U takvoj upotrebi razlika u odnosu na klasičan benzinac nije samo u potrošnji, nego i u osećaju lagodnije svakodnevice. Ako ti je vožnja pretežno otvoren put, priča postaje manje ubedljiva.

Drugo, viša cena hibrida mora se meriti kroz stanje baterije, servisnu istoriju i konkretan model. Kupac ne treba da plaća reputaciju bez provere. Baterija, elektronika, trap i regularni servisi moraju biti deo iste priče. Hibrid koji izgleda kao pametna kupovina samo zato što je hibrid lako postane preskup benzinac sa dodatnim pitanjima.

Treće, treba porediti hibrid sa dobrim benzincem, a ne sa najgorim primerkom u oglasima. Ako isti budžet donosi poštenog benzinca sa jasnom istorijom i manje godina, kupac mora hladno da sabere šta mu je važnije: niža gradska potrošnja ili ukupno manje nepoznanica. Viša cena hibrida ima smisla samo ako kupuje konkretan mir, ne samo ideju o modernijem pogonu.

Najbolji hibrid za grad do 12.000 evra nije automatski onaj koji troši najmanje na papiru. To je primerak kod kog stanje, servisni trag i način vožnje kupca rade zajedno. Kada se te tri stvari poklope, viša cena još uvek ima smisla. Kada se ne poklope, hibrid postaje skupa etiketa na prosečnom polovnjaku.
TEXT,
                'highlights' => [
                    'Hibrid za grad ima smisla tek kada stvarna upotreba opravdava višu početnu cenu.',
                    'Baterija, elektronika i servisna istorija moraju potvrditi reputaciju modela.',
                    'Premiju za hibrid treba meriti u odnosu na dobar benzinac, ne u odnosu na loše oglase.',
                ],
                'tags' => ['hibrid', 'grad', 'auto do 12000 evra', 'analiza tržišta'],
                'meta_title' => 'Hibrid za grad do 12.000 evra: kada se isplati',
                'meta_description' => 'Kako proceniti polovan hibrid za grad do 12.000 evra: baterija, servisna istorija, gradska vožnja, benzinac alternativa i stvarna isplativost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Seat Leon ili Ford Focus: kompakt za vozača koji želi više od proseka',
                'slug' => 'seat-leon-ili-ford-focus-kompakt-za-vozaca-koji-zeli-vise-od-proseka',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Leon i Focus često privlače kupce koji traže bolji osećaj u vožnji od prosečnog kompakta, ali kod polovnjaka presuđuju motor, trap i uredno održavanje.',
                'content' => <<<'TEXT'
Seat Leon i Ford Focus često završavaju u istom užem izboru kada kupac ne želi samo racionalan kompakt, već automobil koji treba da pruži i malo više karaktera u svakodnevnoj vožnji. Na papiru oba modela nude dovoljno prostora, dovoljno motora i dovoljno oglasa da deluju kao sigurna teritorija. U praksi, dobra kupovina se ovde ne meri kroz reputaciju vozačkog auta, nego kroz stanje konkretnog primerka.

Leon privlači kupca koji želi oštriji dizajn, poznatu VW mehaniku i utisak da vozi nešto skuplji auto nego što cena sugeriše. To može biti realna prednost kada su servisna istorija, stanje menjača i trag održavanja zaista čisti. Problem nastaje kada dobar vizuelni utisak pokrije umoran trap, preskočene servise ili motor koji je već ostavio najveći deo zdravlja kod prethodnog vlasnika.

Focus često dobija poene kod vozača koji traže prirodniji osećaj na volanu i automobil koji deluje pošteno čim izađe na put. Ipak, ni ta reputacija nije dovoljno jaka da opravda slab trag održavanja. Focus koji lepo leži u krivini ne znači mnogo ako hladan start, kvačilo, menjač ili stanje trapa otkrivaju da se auto vozio više nego što oglas priznaje.

Kod oba modela treba pošteno birati i motor. Za gradsku i mešovitu vožnju dobar benzinac često ostavlja mirniju računicu. Ako porediš dizelaše, DPF, EGR, turbina i način prethodne eksploatacije moraju biti deo pregleda. Oprema i sportski paket ne rešavaju skupe mehaničke nepoznanice koje dolaze posle kupovine.

Najbolji kompakt između Leona i Focusa nije onaj koji izgleda zanimljivije, nego onaj koji ostavlja najmanje otvorenih pitanja posle probne vožnje i pregleda. Leon ima smisla kada dobijaš uredan primerak bez skrivene VW premije. Focus je bolji kada za isti novac nudi pošteniju istoriju i manje mehaničkih upitnika. Kupac ne treba da bira priču, nego stanje.
TEXT,
                'highlights' => [
                    'Leon i Focus treba meriti kroz trap, menjač i servisnu istoriju, ne samo kroz vozačku reputaciju.',
                    'Dobar benzinac često ima mirniju računicu od umornog dizela koji je ostavio skupa pitanja sledećem vlasniku.',
                    'Najpametniji kompakt je onaj koji posle pregleda ostavlja najmanje nepoznanica.',
                ],
                'tags' => ['Seat Leon', 'Ford Focus', 'kompakt', 'poređenje'],
                'meta_title' => 'Seat Leon ili Ford Focus: koji kompakt polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Seat Leon i Ford Focus modela: benzinac ili dizel, trap, menjač, servisna istorija i realna cena kupovine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Volkswagen Passat B8 ili Mazda 6: porodična limuzina kada kilometraža odlučuje više od opreme',
                'slug' => 'volkswagen-passat-b8-ili-mazda-6-porodicna-limuzina-kada-kilometraza-odlucuje-vise-od-opreme',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Passat B8 i Mazda 6 deluju kao odlični porodični izbori, ali kod polovnjaka razliku prave istorija, način korišćenja i koliko je kilometraža stvarno poštena.',
                'content' => <<<'TEXT'
Volkswagen Passat B8 i Mazda 6 privlače kupca koji želi ozbiljan porodični automobil bez ulaska u stariji premium segment. Obe opcije nude dovoljno prostora, dovoljno udobnosti i dovoljno tržišne prisutnosti da izgledaju kao razumna kupovina. Međutim, kod polovnjaka ove klase oprema i izgled lako zamagle ono što zaista odlučuje: kako je auto vožen i koliko je kilometraža realna.

Passat B8 ima smisla za kupca koji želi tržišno razumljiv auto, lakšu dalju prodaju i veliki izbor oglasa za poređenje. Njegova slabost je upravo u toj popularnosti. Mnogo primeraka nosi visoku kilometražu, a lep paket opreme često služi da skrene pažnju sa činjenice da su menjač, DPF, EGR ili trap već duboko u fazi sledećeg velikog računa.

Mazda 6 često privlači kupca koji želi elegantniji izgled i osećaj da vozi nešto ređi i karakterom zanimljiviji auto. To može biti odlična kupovina ako servisna istorija prati taj utisak i ako nema tragova zanemarenog održavanja, loših limarskih intervencija ili zapuštene mehanike. Ređa pojava na tržištu nije automatski prednost ako zbog toga imaš manje dobrih primeraka za poređenje.

Kod oba modela kilometraža mora biti samo početak, ne zaključak. Enterijer, volan, sedišta, stanje trapa, hladan start i dokumentacija treba da potvrde priču oglasa. Porodična limuzina sa lepim ekranom i dobrom opremom ne vredi mnogo ako iza toga stoji umoran auto koji će tek kod novog vlasnika pokazati puni spisak ulaganja.

Najpametniji izbor između Passata B8 i Mazde 6 nije model sa boljim paketom opreme, nego automobil čija istorija i stanje ostavljaju najmanje prostora za nagađanje. Passat je pravi izbor kada kilometraža i servisni trag imaju logiku. Mazda 6 ima smisla kada za traženi novac dobijaš uredan i proverljiv primerak, a ne samo lepšu alternativu na oglasu.
TEXT,
                'highlights' => [
                    'Passat B8 i Mazda 6 treba kupovati kroz kilometražu koja se može proveriti, ne kroz opremu.',
                    'Popularnost Passata donosi veći izbor, ali i više primeraka koji kriju skup servisni nastavak.',
                    'Najmirnija porodična limuzina je ona sa istorijom koja potvrđuje stanje na pregledu.',
                ],
                'tags' => ['Volkswagen Passat B8', 'Mazda 6', 'porodična limuzina', 'poređenje'],
                'meta_title' => 'Volkswagen Passat B8 ili Mazda 6: koju limuzinu kupiti',
                'meta_description' => 'Poređenje polovnih Volkswagen Passat B8 i Mazda 6 modela: kilometraža, oprema, servisna istorija, menjač i realna porodična kupovina.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Renault Captur: mali crossover koji lako sakrije gradski život',
                'slug' => 'polovni-renault-captur-mali-crossover-koji-lako-sakrije-gradski-zivot',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Captur deluje kao bezbedan izbor za grad i porodicu, ali polovan primerak traži proveru trapa, enterijera, elektronike i servisnog traga kratkih relacija.',
                'content' => <<<'TEXT'
Renault Captur je jedan od onih modela koji na oglasima brzo deluju kao laka odluka. Mali crossover format, povišeno sedenje i gradski karakter lako prodaju priču o praktičnom automobilu za svaki dan. Upravo zato kupac mora biti oprezan, jer Captur ume veoma dobro da sakrije koliko je gradski život zapravo ostavio traga na konkretnom primerku.

Prvo gledaj tragove svakodnevne upotrebe. Captur je često služio za kratke relacije, ivičnjake, parkiranja u uskim ulicama i tempo koji više troši trap, kvačilo i enterijer nego što kilometraža sama pokazuje. Sedišta, volan, ručice vrata, felne i sitni limarski tragovi često govore više od uredno opranih fotografija na oglasu.

Drugo, motor i elektronika moraju imati jasan servisni trag. Kod benzinca traži dokaz da osnovno održavanje nije preskakano. Kod dizela proveri da li način prethodne vožnje ima smisla za DPF i EGR. Kod oba tipa motora važno je i kako rade klima, multimedija, senzori, kamera i ostale gradske pogodnosti koje kupci kod Captura često uzimaju zdravo za gotovo.

Treće, ne dozvoli da crossover forma automatski digne vrednost oglasa. Captur je smislen kada dobijaš pregledan gradski automobil sa urednom istorijom i bez većih nepoznanica. Ako isti budžet donosi mlađi kompakt ili poštenije održavan automobil druge klase, onda povišena karoserija sama po sebi nije dovoljna prednost.

Najbolji polovni Captur je onaj kod kog se priča o praktičnom gradskom autu poklapa sa tragovima održavanja i realnim stanjem. Ako servis postoji, trap ne lupa, elektronika radi bez improvizacije i enterijer ne odaje teži život nego što oglas priznaje, Captur može biti vrlo dobra kupovina. Ako sve zavisi od prvog utiska, traži sledeći oglas.
TEXT,
                'highlights' => [
                    'Captur često krije intenzivnu gradsku eksploataciju iza urednih fotografija i povišene karoserije.',
                    'Trap, enterijer i sitna elektronika moraju potvrditi da je auto održavan, ne samo čist za slikanje.',
                    'Mali crossover ima smisla samo kada stanje i servisni trag opravdaju cenu oglasa.',
                ],
                'tags' => ['Renault Captur', 'mali crossover', 'kupovina polovnjaka', 'gradska vožnja'],
                'meta_title' => 'Polovni Renault Captur: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Renault Captur modela: gradska vožnja, trap, elektronika, servisna istorija i realna cena malog crossovera.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Servisna knjižica u oglasu: kada je dokaz, a kada samo dobar rekvizit',
                'slug' => 'servisna-knjizica-u-oglasu-kada-je-dokaz-a-kada-samo-dobar-rekvizit',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Servisna knjižica može biti ozbiljan plus, ali samo ako pečati, intervali i stanje automobila pričaju istu priču. Sama knjižica ne potvrđuje dobru kupovinu.',
                'content' => <<<'TEXT'
Servisna knjižica je jedan od najčešćih argumenata u oglasima za polovne automobile. Prodavac je pokaže kao dokaz urednog održavanja, a kupac često odmah spusti gard. To je greška. Servisna knjižica može biti ozbiljna prednost, ali samo kada je deo šire i logične priče koju potvrđuju računi, stanje automobila i servisni intervali koji imaju smisla.

Prvo gledaj kontinuitet. Ako knjižica ima pečate, proveri da li se kilometraža i datumi logično nadovezuju. Veliki skokovi, dugi periodi bez upisa ili pečati koji deluju kao da su dodati naknadno treba odmah da otvore dodatna pitanja. Knjižica koja lepo izgleda na fotografiji ne vredi mnogo ako njeni detalji ne mogu da izdrže osnovnu proveru.

Drugo, knjižica mora da prati stanje auta. Ako oglas tvrdi da je održavanje bilo uredno, to bi trebalo da se vidi kroz hladan start, rad motora, stanje enterijera, trap i logiku opšteg habanja. Nema mnogo smisla da automobil ima besprekorne pečate, a da u vožnji deluje kao da servisni raspored nije viđen godinama.

Treće, pitaj za račune i konkretne radove. Knjižica često potvrdi ritam odlazaka u servis, ali računi pokazuju šta je stvarno rađeno. Razlika između osnovnog servisa i ozbiljnijeg ulaganja nije mala, posebno kod polovnjaka sa većom kilometražom. Kupac mora znati da li kupuje automobil koji je održavan proaktivno ili samo dovoljno da zadrži dobar utisak na oglasu.

Servisna knjižica treba da bude početak provere, ne njen kraj. Kada pečati, računi i stanje automobila pričaju istu priču, to jeste ozbiljan znak poverenja. Kada se ne poklope, knjižica postaje samo dobar rekvizit koji pokušava da zameni ono najvažnije: dokaz da je auto zaista održavan kako treba.
TEXT,
                'highlights' => [
                    'Servisna knjižica vredi samo kada datumi, kilometraža i pečati imaju kontinuitet.',
                    'Stanje automobila mora potvrditi knjižicu, inače pečati ostaju samo lep detalj iz oglasa.',
                    'Računi i konkretni radovi često govore više od same servisne knjižice.',
                ],
                'tags' => ['servisna knjižica', 'provera vozila', 'servisna istorija', 'kupovina polovnjaka'],
                'meta_title' => 'Servisna knjižica u oglasu: koliko zaista vredi',
                'meta_description' => 'Kako proveriti servisnu knjižicu u oglasu: pečati, datumi, kilometraža, računi i stanje vozila koje mora potvrditi servisnu priču.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#34d399', '#f8fafc'],
            ],
            [
                'title' => 'Benzinac za grad do 6.000 evra: kako kupiti mirniji auto bez dizel stresa',
                'slug' => 'benzinac-za-grad-do-6000-evra-kako-kupiti-mirniji-auto-bez-dizel-stresa',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'U budžetu do 6.000 evra dobar benzinac za grad često ima više smisla od jeftinog dizela, ali samo kada kupac gleda stanje, servis i realna ulaganja posle kupovine.',
                'content' => <<<'TEXT'
Budžet do 6.000 evra često tera kupca da traži kompromis koji deluje razumno na papiru, ali ume da bude skup u praksi. U toj zoni mnogi automatski jure dizel zbog niže potrošnje, iako će se auto većinom voziti po gradu. Upravo tu dobar benzinac često postaje pametnija i mirnija kupovina, jer gradski režim vožnje manje kažnjava jednostavniju mehaniku nego umoran dizel sa većim brojem nepoznanica.

Prva stvar je način vožnje. Ako je auto namenjen za posao po gradu, kratke relacije, parkiranje i povremeni izlazak na otvoren put, dizel najčešće ne dobija uslove u kojima može da opravda svoje prednosti. DPF, EGR i turbina ne zanima što oglas obećava malu potrošnju. Zanima ih kako je auto stvarno vožen i kako ćeš ga voziti posle kupovine.

Druga stvar je stanje primerka. Dobar benzinac do 6.000 evra često ima smisla upravo zato što lista potencijalno skupih kvarova može biti kraća. To ne znači da pregled sme biti površan. Hladan start, trap, kvačilo, potrošnja ulja, trag održavanja i stanje enterijera i dalje odlučuju da li je oglas pošten. Mirniji motor nema vrednost ako je ostatak auta zapušten.

Treća stvar je tržišna iluzija da je štedljiviji auto automatski isplativiji auto. U ovom budžetu isplativost mnogo više zavisi od toga koliko neplaniranih ulaganja čeka kupca u prvih šest do dvanaest meseci. Automobil koji troši litar više, ali traži manje servisa i manje rizika, često je realno bolji izbor od navodno štedljivijeg auta koji krije skupu listu problema.

Najbolji benzinac za grad do 6.000 evra nije jedan model nego pošten primerak. Kada se poklope uredan servisni trag, razumno habanje i auto koji odgovara stvarnoj gradskoj vožnji, benzinac ume da bude najmirniji mogući izbor. Kupac u ovom budžetu ne treba da kupuje priču o potrošnji, nego auto koji će tražiti najmanje stresa posle kupovine.
TEXT,
                'highlights' => [
                    'Dobar benzinac do 6.000 evra često je mirnija gradska kupovina od jeftinog dizela sa DPF rizikom.',
                    'Stanje, hladan start i trag održavanja vrede više od papira sa niskom potrošnjom.',
                    'Isplativost u ovom budžetu zavisi od ulaganja posle kupovine, ne samo od potrošnje goriva.',
                ],
                'tags' => ['benzinac', 'auto do 6000 evra', 'gradska vožnja', 'analiza tržišta'],
                'meta_title' => 'Benzinac za grad do 6.000 evra: kako kupiti mirniji auto',
                'meta_description' => 'Kako izabrati polovan benzinac za grad do 6.000 evra: gradska vožnja, dizel rizici, servisna istorija i realna računica kupovine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Audi A4 B8 2.0 TDI ili BMW 320d F30: detaljna analiza motora, menjača i enterijera',
                'slug' => 'audi-a4-b8-20-tdi-ili-bmw-320d-f30-detaljna-analiza-motora-menjaca-i-enterijera',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Gledamo kako se razlikuju 2.0 TDI i 320d motori, kakvi su automatski menjači, koliko enterijer stari i za koga je koji auto realno bolji izbor.',
                'content' => <<<'TEXT'
Kada kupac u Srbiji poredi Audi A4 B8 2.0 TDI i BMW 320d F30, obično misli da bira između dva statusno slična automobila i da presuđuju oprema, kilometraža i prvi utisak. To je previše plitak pristup. Kod ova dva modela razliku u ukupnoj kupovini mnogo više prave konkretan motor, tip menjača, stanje trapa i to koliko je prethodni vlasnik bio disciplinovan sa održavanjem.

Ako priču spustimo na motor, Audi A4 B8 2.0 TDI najčešće privlači kupca koji želi mirniji i izolovaniji karakter. Dobar primerak deluje sabrano, tiše na autoputu i manje “nervozno” u svakodnevnoj vožnji. Međutim, kod ovog auta nije dovoljno da 2.0 TDI lepo vuče na test vožnji. Treba proveriti kako pali hladan, da li dimi pod opterećenjem, kakav je trag redovnih servisa i da li su DPF, EGR i zamajac ostavljeni sledećem vlasniku. Ako je u paru sa S tronic menjačem, istorija zamene ulja i ponašanje pri kretanju i promeni stepena prenosa postaju obavezna stavka, ne bonus proverica.

BMW 320d F30 je slična priča samo sa drugačijim akcentima. On kupca često osvaja motorom koji deluje življe i automobilom koji je lakše “čitati” kroz volan i zadnju osovinu. Ali ta vozačka živost ne znači mnogo ako konkretan primerak dolazi iz priče sa zapuštenim servisima, lancem koji nikada nije ozbiljno proveravan kod rizičnijih ranijih verzija, ili automatikom koja lepo radi samo dok se ne zagreje. Kod F30 je zato važno razdvojiti ono što auto obećava u prvih pet minuta vožnje od onoga što će tražiti u prvih godinu dana vlasništva.

Kad govorimo o menjačima, tu kupci često prave veliku grešku. Automatik kod oba modela može biti velika prednost, ali samo kada postoji dokaz da je održavanje stvarno rađeno. Na oglasima se još uvek prečesto prodaje priča da je menjač “bez ulaganja” samo zato što trenutno ne trza. To nije dovoljno. Kod Audija treba obratiti pažnju na ponašanje pri manevrisanju, kretanju uz blagi gas i promenama u nižim stepenima. Kod BMW-a automatik često ostavlja bolji prvi utisak, ali i dalje treba slušati kako radi hladan, da li ima zadrške pri spuštanju prenosa i da li postoji trag servisa menjača, a ne samo servis motora.

Razlika u enterijeru je podjednako važna jer otkriva kako je auto zaista živeo. Audi A4 B8 često deluje čvršće sklopljeno i vizuelno konzervativnije. Kada je dobar, enterijer ostavlja utisak preciznosti i smirenosti, bez mnogo jeftinih detalja koji odaju godine. Ali ako je auto mnogo vožen, upravo će A4 pokazati habanje na komandama, vozačkom sedištu i centralnim površinama koje oglasne fotografije umeju lepo da sakriju.

BMW F30 sa druge strane često deluje modernije i vozački “lakše”, ali enterijer treba gledati bez premijum romantike. Materijali mogu delovati odlično na prvi pogled, ali stanje volana, bočnih oslonaca sedišta, iDrive komandi i sitnih obloga oko tunela često tačno pokaže da li je kilometraža logična. Ako unutra vidiš više umora nego što bi auto sa te kilometraže smeo da ima, taj signal ne smeš ignorisati čak ni ako motor radi lepo.

Na putu je razlika jasna. Audi A4 B8 je bolji za kupca koji hoće stabilan, tih i odrasliji autoputni osećaj bez potrebe da se automobil stalno “vozi”. BMW 320d F30 više odgovara onome kome je bitno da auto deluje lakše, agilnije i direktnije kada put postane krivudav ili kada jednostavno voli aktivniji osećaj za volanom. Ni jedno ni drugo nije automatski bolje; samo znači da kupac mora znati da li traži smiren premium dizel ili premium dizel koji i dalje pokušava da bude vozački zanimljiv.

Troškovi posle kupovine su mesto gde se ova dva auta zaista razdvajaju u glavi racionalnog kupca. Ako uzmeš Audi zato što deluje urednije, a ispostavi se da S tronic nema dokaz o servisu i da su DPF i zamajac već na granici, početni mir brzo nestaje. Ako uzmeš BMW zato što lepše ide i bolje skreće, a u pozadini stoji zapušten trag održavanja ili primerak koji je vožen tvrđe nego što izgleda, računica takođe pada. Premium značke ne opraštaju nestrpljivu kupovinu.

Zato je najpametniji zaključak sledeći: A4 B8 2.0 TDI ima više smisla za kupca koji želi sabraniji auto, tiši enterijer i manje vozačke teatralnosti, ali samo uz jasan dokaz da su motor i menjač održavani kako treba. BMW 320d F30 je bolji za kupca koji hoće življi auto i više uživanja u vožnji, ali samo ako je spreman da bude nemilosrdan prema proveri servisa, lanca kod ranijih primeraka i stvarnog stanja trapa i enterijera.

Između ova dva modela ne bira se lepši oglas, nego uredniji primerak. Ako Audi ima pošten servisni trag, mirniji rad i enterijer koji potvrđuje kilometražu, to je često sigurniji izbor za duga putovanja i svakodnevni mir. Ako BMW ima jasnu istoriju, dobar automatik i manje tragova tvrde eksploatacije, može biti vozački zahvalniji i emotivno jača kupovina. U oba slučaja, motor, menjač i unutrašnjost moraju pričati istu priču. Ako ne pričaju, oglasi samo glume premium klasu.
TEXT,
                'highlights' => [
                    'Kod A4 B8 i F30 presuđuju konkretan motor, tip menjača i trag održavanja, ne samo paket opreme.',
                    'Audi obično nudi mirniji i tiši karakter, dok BMW češće daje življi osećaj za volanom i direktniji odgovor.',
                    'Enterijer, hladan start i ponašanje automatika često otkriju više od same oglašene kilometraže.',
                ],
                'tags' => ['Audi A4 B8', 'BMW 320d F30', '2.0 TDI', 'detaljna analiza'],
                'meta_title' => 'Audi A4 B8 2.0 TDI ili BMW 320d F30: detaljna analiza',
                'meta_description' => 'Detaljno poređenje Audi A4 B8 2.0 TDI i BMW 320d F30 modela: motor, automatik, enterijer, trap, tipične slabosti i profil pravog kupca.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Škoda Superb ili Opel Insignia: velika dizel limuzina kada prostor nije dovoljan argument',
                'slug' => 'skoda-superb-ili-opel-insignia-velika-dizel-limuzina-kada-prostor-nije-dovoljan-argument',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Superb i Insignia deluju kao logičan izbor za kupca kome treba mnogo prostora za isti novac, ali kod polovnjaka presuđuju menjač, trap, istorija dizela i koliko je auto radio službeni posao.',
                'content' => <<<'TEXT'
Škoda Superb i Opel Insignia često završavaju u istoj pretrazi kada kupac želi veliki dizel automobil bez ulaska u stariji premium segment. Na papiru oba modela nude ono što većina porodica i vozača na otvorenom putu traži: mnogo mesta, ozbiljan gepek, dobar osećaj stabilnosti i dovoljno opreme da oglas deluje vrlo privlačno. Problem je što se kod polovnjaka ove klase prostor često preceni, a mehanika potceni.

Superb obično privlači kupca koji želi racionalan automobil sa jakim utiskom praktičnosti. Mesta pozadi ima više nego kod mnogih skupljih limuzina, gepek rešava pola porodičnih dilema i tržište ga lako razume. Ali upravo zbog te reputacije kupac često zaboravi da proveri najskuplje tačke: DSG kada postoji, trag redovnog održavanja 2.0 TDI motora, stanje plivajućeg zamajca, DPF-a i prednjeg trapa. Ako je Superb dugo služio kao autoputni radnik ili flotni auto, njegova praktičnost ne znači mnogo ako servisna disciplina nije pratila tempo.

Insignia sa druge strane često deluje kao povoljniji ulaz u istu ideju: velika limuzina ili karavan za manje novca od konkurencije. To može biti realna prednost, ali samo ako niža cena ne dolazi zajedno sa većim zaostatkom ulaganja. Kod Insignije posebno vredi gledati kako se automobil ponaša preko lošeg asfalta, kako radi hladan dizel, kakav je trag održavanja i da li enterijer otkriva više eksploatacije nego što kilometraža želi da prizna.

Razlika u svakodnevnom utisku takođe nije zanemarljiva. Superb češće deluje prostranije i racionalnije organizovano, posebno za kupca koji često koristi zadnju klupu ili vozi porodicu na duži put. Insignia ume da deluje vizuelno atraktivnije i vozački kompaktnije, ali to ne menja činjenicu da oba automobila treba kupovati kroz stanje konkretnog primerka, a ne kroz katalog dimenzija.

Najpametniji izbor između Superba i Insignije nije model koji nudi više kvadrata lima za isti novac, nego auto koji ima čišći servisni trag i manje otvorenih pitanja oko dizela, automatika i trapa. Ako Superb nudi urednu istoriju i poštenu kilometražu, njegov prostor zaista ima smisla. Ako Insignia za niži novac deluje mehanički svežije i manje umorno, upravo ona može biti bolja kupovina. Kod oba modela prostor je prednost tek kada ne krije skup račun posle kupovine.
TEXT,
                'highlights' => [
                    'Kod Superba i Insignije prostor nije dovoljan argument ako istorija dizela i menjača nije jasna.',
                    'DSG, plivajući zamajac, DPF i trap često vrede više pažnje od same liste opreme i veličine gepeka.',
                    'Velika limuzina je dobra kupovina tek kada servisni trag prati način na koji je auto korišćen.',
                ],
                'tags' => ['Škoda Superb', 'Opel Insignia', 'dizel limuzina', 'poređenje'],
                'meta_title' => 'Škoda Superb ili Opel Insignia: koju veliku dizel limuzinu kupiti',
                'meta_description' => 'Poređenje polovnih Škoda Superb i Opel Insignia modela: prostor, 2.0 dizel, automatik, trap, flotna eksploatacija i realna isplativost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Toyota C-HR ili Nissan Juke: gradski crossover kada stil ne sme da sakrije računicu',
                'slug' => 'toyota-c-hr-ili-nissan-juke-gradski-crossover-kada-stil-ne-sme-da-sakrije-racunicu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C-HR i Juke kupuju se očima mnogo češće nego razumom, ali kod polovnjaka presuđuju preglednost, zadnja klupa, motor i koliko dizajn prikriva praktične kompromise.',
                'content' => <<<'TEXT'
Toyota C-HR i Nissan Juke privlače kupca koji ne želi običan gradski automobil. Oba modela pokušavaju da prodaju više karaktera, višu poziciju sedenja i crossover utisak, ali uz format koji i dalje mora da živi sa gradom, parkingom i svakodnevnim obavezama. Kod polovnjaka je zato vrlo lako pogrešiti ako odluku donese samo dizajn.

C-HR najčešće osvaja urednijim i ozbiljnijim utiskom, posebno kada se pojavi u hibridnoj ili dobro opremljenoj verziji. To zaista može biti prednost za gradsku vožnju, ali kupac ne sme zaboraviti da stilizovana karoserija donosi i određene kompromise: preglednost unazad, osećaj prostora pozadi i praktičnost prtljažnika nisu jednako dobri kao prvi utisak spolja. Ako se C-HR kupuje za porodicu, nije dovoljno da izgleda moderno, već mora dokazati da odgovara načinu života kupca.

Juke je još ekstremniji primer automobila koji tržište često prodaje kroz izgled. On može biti vrlo smislen za vozača koji želi mali gradski auto sa crossover visinom i ne očekuje čuda od zadnje klupe. Međutim, kod polovnog Jukea kupac mora da proveri da li je gradska vožnja ostavila trag na trapu, enterijeru i osnovnom održavanju. Dizajn lako skrene pažnju sa toga da je auto realno služio teži gradski posao nego što kilometraža sama govori.

Razlika između ova dva modela nije samo u tome koji lepše izgleda. C-HR češće ima smisla za kupca koji želi mirniji svakodnevni utisak i bolju reputaciju pogona, posebno ako se gleda hibrid. Juke više odgovara kupcu koji želi stil i kompaktnost, ali mora hladnije prihvatiti da praktičnost nije glavni razlog njegove popularnosti. U oba slučaja, motor, stanje enterijera i realna upotreba vrede više od same estetike.

Najpametniji gradski crossover između C-HR i Jukea nije onaj koji se bolje fotografiše za oglas, nego onaj koji manje laže o svojim kompromisima. Ako C-HR za traženi novac nudi urednu istoriju i mirniju svakodnevnu vožnju, njegova skuplja reputacija može imati smisla. Ako Juke nudi pošteniji primerak i kupac tačno zna da ne kupuje porodični auto nego gradski stilizovani crossover, i on može biti dobra kupovina. Problem nastaje tek kada stil zameni proveru.
TEXT,
                'highlights' => [
                    'C-HR i Juke često osvajaju dizajnom, ali preglednost, zadnja klupa i praktičnost ne smeju ostati sporedni.',
                    'Kod polovnih gradskih crossovera stanje trapa i enterijera često otkrije teži gradski život nego što oglas pokazuje.',
                    'Najbolji izbor je model čiji kompromisi odgovaraju stvarnoj upotrebi kupca, ne samo ukusu na fotografiji.',
                ],
                'tags' => ['Toyota C-HR', 'Nissan Juke', 'gradski crossover', 'poređenje'],
                'meta_title' => 'Toyota C-HR ili Nissan Juke: koji gradski crossover kupiti',
                'meta_description' => 'Poređenje polovnih Toyota C-HR i Nissan Juke modela: dizajn, praktičnost, zadnja klupa, gradska vožnja i realna računica kupovine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#34d399', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volvo XC60: porodični premium SUV koji traži uredan servisni trag',
                'slug' => 'polovni-volvo-xc60-porodicni-premium-suv-koji-trazi-uredan-servisni-trag',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'XC60 lako deluje kao mirna premium kupovina za porodicu, ali dobar primerak zavisi od servisa, automatika, elektronike i toga koliko je prethodni vlasnik pratio skupe stavke na vreme.',
                'content' => <<<'TEXT'
Volvo XC60 je jedan od onih modela koji kupcu ulivaju poverenje već samim imenom. Deluje porodično, ozbiljno, bez potrebe da se dokazuje agresivnim dizajnom i često ostavlja utisak SUV-a koji je sazreo zajedno sa svojim kupcem. Upravo zato je opasno kupiti ga previše opušteno. Polovni XC60 je dobar samo kada njegova smirena reputacija prati stvarno uredan servisni trag.

Prva stvar koju treba gledati jeste kako je automobil održavan, ne samo gde je servisiran. Premium SUV ove klase nije problem kada je vođen disciplinovano, ali brzo postaje skup kada su automatik, trap, pogon ili elektronika održavani po principu “dok radi, ne diraj”. Kupac ne treba da veruje samo pečatima, već da traži logiku u datumima, kilometrima i konkretnim radovima koji su rađeni.

Drugo, probna vožnja kod XC60 mora da bude mirna, ali pažljiva. Obrati pažnju kako menjač radi pri blagom kretanju, kako auto reaguje preko lošeg asfalta, da li se čuju udarci iz trapa i da li elektronika i komforna oprema rade bez sitnih improvizacija. Kod ovakvog auta “sitnice” retko ostaju sitnice kada dođe vreme za račun.

Treće, XC60 se često kupuje kao porodični kompromis između bezbednosti, komfora i premium osećaja. To ima smisla samo kada auto nije zapušten iza mirnog spoljnog utiska. Sedišta, volan, dugmad, gepek i stanje enterijera treba da potvrde način korišćenja i kilometražu. Ako unutrašnjost deluje umornije nego što oglas obećava, taj signal je obično važniji od lepog dizna ili dodatne opreme.

Najbolji polovni XC60 je onaj koji ne pokušava da se proda samo kroz reputaciju marke. Ako servisna istorija postoji, automatik radi ubedljivo, trap je tih i enterijer prati priču o kilometraži, XC60 može biti vrlo zrela i smirena kupovina. Ako se sve svodi na to da “Volvo deluje sigurno”, onda kupac lako plati premium cenu za prosečan primerak koji tek posle kupovine pokaže pravi karakter troškova.
TEXT,
                'highlights' => [
                    'XC60 ima smisla samo kada premium reputaciju potvrde uredan servisni trag i mehanički miran primerak.',
                    'Automatik, trap i elektronika kod ovakvog SUV-a moraju se proveravati strože nego kod prosečnog oglasa.',
                    'Porodični premium SUV nije dobra kupovina ako enterijer i komforna oprema već odaju zapušten život.',
                ],
                'tags' => ['Volvo XC60', 'premium SUV', 'kupovina polovnjaka', 'servisna istorija'],
                'meta_title' => 'Polovni Volvo XC60: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Volvo XC60 modela: automatik, trap, elektronika, servisna istorija i realna premium porodična kupovina.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Rent-a-car auto na oglasu: kako ga prepoznati pre nego što te zavede oprema',
                'slug' => 'rent-a-car-auto-na-oglasu-kako-ga-prepoznati-pre-nego-sto-te-zavede-oprema',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Bivši rent-a-car auto često izgleda mlađe, opremljenije i privlačnije nego što je stvarno očuvan. Kupac mora naučiti kako da prepozna tragove takve eksploatacije pre kupovine.',
                'content' => <<<'TEXT'
Rent-a-car automobili umeju da izgledaju kao odlična prilika. Često su relativno mladi, imaju dobru opremu, uredno su oprani za oglas i deluju kao da nude više auta za manje novca. Problem je što takav primerak često nosi mnogo više različitih vozača, više kratkih relacija i manje emotivne pažnje nego auto koji je neko zaista vozio kao svoj.

Prvi signal je kombinacija godišta, opreme i cene. Ako automobil izgleda previše atraktivno za ono što tržište inače traži, kupac treba da se zapita zašto. Rent-a-car primerci često imaju dobar vizuelni paket jer je upravo to bilo važno pri kupovini flote, ali stanje enterijera, volana, ručica i prtljažnika može vrlo brzo otkriti intenzivniju upotrebu nego što broj kilometara sugeriše.

Drugi signal je način habanja. Sedišta na vozačevoj strani, ivice plastika, pragovi, felne i sitni limarski tragovi često više govore od servisnog pečata. Automobil može imati uredne osnovne servise, ali i dalje biti vožen hladan, parkiran grubo i korišćen bez mnogo obzira. Kupac zato mora povezati kako auto izgleda iznutra i spolja sa pričom koju prodavac nudi.

Treći signal je dokumentacija. Ako je automobil zaista dolazio iz rent-a-car ili slične kratkoročne flote, trag u papirima, poreklu ili načinu prodaje obično postoji. Problem nije nužno to što je auto bio iznajmljivan, nego što kupac tu činjenicu ne uključi u procenu cene. Auto sa takvom istorijom mora biti mehanički ubedljiv i cenovno pošteniji da bi bio dobra kupovina.

Najgora greška je kada dobra oprema uspava oprez. Kamera, automatik, navigacija i atraktivan paket mogu delovati kao poklon, ali ako je auto prošao mnogo vozača i mnogo grubih svakodnevica, ta oprema ne menja istoriju eksploatacije. Bivši rent-a-car primerak može biti korektan ako je stanje stvarno dobro i cena to priznaje. Ako ne priznaje, onda je to samo lep oglas sa skriveno težim životom.
TEXT,
                'highlights' => [
                    'Rent-a-car primerci često nude dobru opremu i mlađe godište, ali to ne znači automatski mirniji život auta.',
                    'Enterijer, pragovi, felne i sitni tragovi habanja često najbrže otkriju mnogo različitih vozača.',
                    'Takva istorija mora biti uračunata u cenu, inače dobra oprema samo prikriva težu eksploataciju.',
                ],
                'tags' => ['rent-a-car', 'provera vozila', 'oprema', 'istorija korišćenja'],
                'meta_title' => 'Rent-a-car auto na oglasu: kako ga prepoznati',
                'meta_description' => 'Kako prepoznati bivši rent-a-car auto na oglasu: oprema, habanje enterijera, felne, dokumentacija i realna cena takvog primerka.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Automatik za porodicu do 15.000 evra: kada komfor sakrije servisni rizik',
                'slug' => 'automatik-za-porodicu-do-15000-evra-kada-komfor-sakrije-servisni-rizik',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Porodični automatik do 15.000 evra deluje kao idealan kompromis za grad i put, ali upravo u tom budžetu kupac lako potceni stanje menjača i preceni listu komfora.',
                'content' => <<<'TEXT'
Budžet do 15.000 evra danas otvara veliki broj porodičnih polovnjaka sa automatskim menjačem. Na prvi pogled to izgleda kao idealna sredina između komfora, praktičnosti i dovoljno modernog automobila za svaki dan. Problem je što kupci u toj zoni često prebrzo poveruju da je automatik samo dodatni plus, a ne ključni sklop koji mora biti pažljivije proveravan od pola liste opreme zajedno.

Automatik za porodicu ima mnogo smisla kada auto zaista služi gradu, gužvi, putovanjima sa decom i svakodnevnom tempu u kom komfor nešto vredi. Ali baš zato treba odvojiti osećaj udobnosti od tehničke stvarnosti. Menjač koji trenutno deluje uglađeno ne znači mnogo ako nema trag zamene ulja, ako pri hladnom radu skriva trzaje ili ako je auto već odradio težak deo svog života kroz mnogo vuče, grada i stani-kreni režima.

Druga česta greška je poređenje po opremi umesto po stanju. Kupac vidi panoramu, kameru, grejanje sedišta i električna vrata gepeka, pa zaboravi da upravo ti automobili često dolaze sa većom kilometražom ili složenijom mehanikom nego skromniji primerci. Kod porodičnog automatika do 15.000 evra nije poenta da auto ima sve, već da ono što ima radi bez velikih skrivenih računa.

Treća stvar je profil kupovine. Ako porodici stvarno treba veći auto i automatik, nekad ima više smisla uzeti manje atraktivno opremljen, ali urednije održavan primerak. Automobil koji deluje “pun” na oglasu lako postane prazan argument kada prvi veći servis menjača, trapa ili elektronike dođe odmah po kupovini. U tom trenutku komfor više nije dobitak nego izvor nervoze.

Najbolji porodični automatik do 15.000 evra nije onaj koji ostavlja najjači salonski utisak na oglasu, nego onaj kod kog menjač ima istoriju, trap mirno radi i ostatak auta ne krije zamor iza komforne opreme. Kada se to poklopi, automatik zaista vredi svaku dodatnu pažnju. Kada se ne poklopi, upravo komfor postaje najskuplji deo pogrešne kupovine.
TEXT,
                'highlights' => [
                    'Porodični automatik do 15.000 evra treba kupovati kroz stanje menjača, ne kroz listu komforne opreme.',
                    'Hladan rad, istorija zamene ulja i ponašanje pri blagom kretanju vrede više od prvog uglađenog utiska.',
                    'Skromnije opremljen, ali urednije održavan automatik često je bolja kupovina od bogatog oglasa sa većim rizikom.',
                ],
                'tags' => ['automatik', 'porodica', 'auto do 15000 evra', 'analiza tržišta'],
                'meta_title' => 'Automatik za porodicu do 15.000 evra: kada se isplati',
                'meta_description' => 'Kako proceniti polovan automatik za porodicu do 15.000 evra: stanje menjača, komforna oprema, trap, kilometraža i realna servisna računica.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Mazda CX-30 ili Volkswagen T-Roc: kompaktni crossover kada osećaj za volanom menja računicu',
                'slug' => 'mazda-cx-30-ili-volkswagen-t-roc-kompaktni-crossover-kada-osecaj-za-volanom-menja-racunicu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'CX-30 i T-Roc deluju kao sličan izbor za grad i porodicu, ali razliku često prave motor, enterijer i to koliko kupac zaista ceni vozački osećaj naspram lakše preprodaje.',
                'content' => <<<'TEXT'
Mazda CX-30 i Volkswagen T-Roc često završavaju u istoj pretrazi kupca koji želi kompaktni crossover sa modernim izgledom, višom pozicijom sedenja i dovoljno kvaliteta da auto ne deluje kao privremeni kompromis. Na papiru oba modela deluju kao logičan izbor za grad, put i porodičnu svakodnevicu. U praksi, razlika među njima često nastaje tek kada kupac shvati da ne bira samo karoserijski format, nego i karakter automobila.

CX-30 obično više privlači vozača kome je stalo do osećaja za volanom, mirnijeg benzinskog karaktera i enterijera koji deluje zategnutije nego što klasa možda očekuje. To ume da bude ozbiljna prednost ako kupac planira da auto zadrži duže i želi svakodnevni osećaj da vozi nešto smisleno složeno. Problem nastaje kada tržište traži višu cenu samo zbog utiska, a konkretan primerak nema jasan servisni trag ili pokazuje više habanja nego što godine sugerišu.

T-Roc je sa druge strane vrlo često lakše razumljiv kupcu koji gleda oglasnu logiku tržišta. Ime modela, dizajn i šira potražnja često mu pomažu kod dalje prodaje, a to nije zanemarljiva stavka. Ipak, upravo zbog te tržišne preglednosti kupac nekad plati previše za prosečan primerak, posebno ako ga vodi priča o bržoj prodaji, a ne stvarno stanje enterijera, trapa i načina na koji je motor održavan.

Najbolji izbor između CX-30 i T-Roc nije onaj koji zvuči sigurnije u razgovoru sa drugim ljudima, nego onaj koji nudi pošteniji primerak za traženi novac. Ako Mazda nudi mirniji servisni trag i bolji vozački utisak bez skrivene premije, ima vrlo ozbiljan smisao. Ako T-Roc nudi jasniju istoriju, uredno stanje i realniju tržišnu likvidnost, on može biti racionalniji izbor. Kupac ne treba da bira samo klasu auta, nego i filozofiju vlasništva koja mu više odgovara.
TEXT,
                'highlights' => [
                    'CX-30 i T-Roc se ne razlikuju samo dizajnom, već i kroz vozački karakter, enterijer i tržišnu logiku.',
                    'Mazda češće osvaja vozača osećajem za volanom, dok T-Roc češće dobija poene zbog šire prepoznatljivosti na tržištu.',
                    'Najbolji kompaktni crossover je onaj koji za traženi novac daje uredniji primerak, ne glasniji brend utisak.',
                ],
                'tags' => ['Mazda CX-30', 'Volkswagen T-Roc', 'kompaktni crossover', 'poređenje'],
                'meta_title' => 'Mazda CX-30 ili Volkswagen T-Roc: koji crossover kupiti',
                'meta_description' => 'Poređenje polovnih Mazda CX-30 i Volkswagen T-Roc modela: motor, enterijer, vozački osećaj, tržišna potražnja i realna isplativost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Peugeot 508 ili Renault Talisman: velika limuzina kada dizajn ne sme da vodi glavnu reč',
                'slug' => 'peugeot-508-ili-renault-talisman-velika-limuzina-kada-dizajn-ne-sme-da-vodi-glavnu-rec',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => '508 i Talisman lako osvoje izgledom, ali kod polovnjaka razliku prave mehanika, elektronika, automatik i pitanje da li lepši auto zaista znači i mirniji auto.',
                'content' => <<<'TEXT'
Peugeot 508 i Renault Talisman često privlače kupca koji želi veliku modernu limuzinu bez klasične nemačke premije. Obe opcije nude mnogo stila, dobar osećaj dužine i ozbiljniji nastup na putu nego što njihov oglas često košta u poređenju sa poznatijim rivalima. Upravo zato kupac lako poveruje da pametno kupuje “više auta za manje para”. To može biti tačno, ali samo kada stanje konkretne mehanike prati dizajn.

Kod 508 kupca često osvaja utisak modernijeg enterijera i oštrijeg spoljnog dizajna. To deluje kao prednost sve dok se ne zaboravi da elektronika, multimedija i komforna oprema na ovakvim automobilima ne služe samo da impresioniraju na oglasu, nego i da rade bez sitnih nerviranja. Ako je primerak održavan površno, upravo te “male” stvari postaju najuporniji razlog za nezadovoljstvo posle kupovine.

Talisman obično igra na kartu prostranosti, udobnosti i lagodnijeg karaktera. To ume da bude vrlo smislen izbor za vozača koji više ceni mirnu vožnju nego marketinški atraktivan kokpit. Ipak, ni Talisman ne treba kupovati kroz utisak salona. Trap, automatik kada postoji, stanje enterijera i logika servisne istorije moraju potvrditi da automobil nije zapostavljen iza lepog spoljnog paketa.

Najveća greška kod oba modela je kada dizajn zameni pregled. Velika limuzina može delovati mnogo skuplje nego što košta, ali to ne znači da će tako izgledati i prvi ozbiljan račun za održavanje ako je kupac preskočio proveru. Najbolji izbor između 508 i Talisman modela nije lepši auto, nego onaj koji posle probne vožnje i servisa ostavlja manje otvorenih pitanja.
TEXT,
                'highlights' => [
                    '508 i Talisman lako osvoje dizajnom, ali kod polovnjaka dizajn ne sme da zameni proveru mehanike i elektronike.',
                    'Komforna oprema i moderan enterijer vrede samo ako rade bez prikrivenih sitnih kvarova i improvizacija.',
                    'Najbolja velika limuzina je ona koja posle pregleda ostavlja manje nepoznanica, ne jači prvi utisak.',
                ],
                'tags' => ['Peugeot 508', 'Renault Talisman', 'velika limuzina', 'poređenje'],
                'meta_title' => 'Peugeot 508 ili Renault Talisman: koju limuzinu kupiti',
                'meta_description' => 'Poređenje polovnih Peugeot 508 i Renault Talisman modela: dizajn, automatik, elektronika, enterijer i realna isplativost velike limuzine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mercedes GLA: kompaktni premium SUV koji lako sakrije gradsku eksploataciju',
                'slug' => 'polovni-mercedes-gla-kompaktni-premium-suv-koji-lako-sakrije-gradsku-eksploataciju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'GLA deluje kao siguran premium izbor za grad, ali dobar primerak zavisi od menjača, trapa, istorije servisa i toga koliko je auto živeo samo na urbanim kratkim relacijama.',
                'content' => <<<'TEXT'
Mercedes GLA je model koji se na oglasima vrlo lako prodaje kroz ime marke, višu poziciju sedenja i utisak da kupac dobija “mali premium SUV” bez ulaska u veće i skuplje klase. To može biti tačno, ali upravo takvi automobili često žive veoma intenzivan gradski život. Kratke relacije, ivičnjaci, garaže, uska parkiranja i mnogo hladnih startova ostavljaju trag koji se na fotografijama retko vidi jasno.

Kod polovnog GLA zato ne treba prvo gledati zvezdu na haubi, nego način na koji je primerak održavan. Menjač, trap, klima, multimedija i stanje enterijera moraju zajedno potvrditi da auto nije samo lepo očišćen za prodaju. Ako volan, vozačko sedište, prekidači i ivice pragova izgledaju umornije nego što bi kilometraža dozvoljavala, to je ozbiljan signal da je gradska eksploatacija bila teža nego što oglas priznaje.

Drugi važan deo je profil kupca. GLA ima smisla za vozača koji zaista želi kompaktniji premium crossover za grad i put, ali ne treba od njega očekivati čuda sa prostorom ili robusnošću većeg SUV-a. Problem nastaje kada kupac plati premium cenu za ime i izgled, a zanemari da je auto možda već prošao veliki deo svog “lagodnog” života u uslovima koji najviše troše upravo ono što je skupo za održavanje.

Najbolji polovni GLA je onaj koji izgleda premium, ali se ponaša pošteno na pregledu. Ako menjač radi glatko, trap je tih, elektronika ne pokazuje sitne frustracije i servisna istorija je logična, GLA može biti vrlo korektan gradski premium izbor. Ako se sve svodi na to da auto “deluje kao Mercedes”, onda kupac lako plati više za primerak koji tek posle kupovine otkrije koliko je grad već uzeo svoj deo.
TEXT,
                'highlights' => [
                    'GLA se često kupuje kroz premium utisak, ali ga treba proveravati kao auto koji je verovatno živeo vrlo intenzivan gradski ritam.',
                    'Menjač, trap i enterijer brzo pokažu da li kilometraža i priča oglasa zaista imaju logiku.',
                    'Najgora kupovina je premium gradski SUV koji spolja izgleda sređeno, a mehanički već nosi gradsku iscrpljenost.',
                ],
                'tags' => ['Mercedes GLA', 'premium SUV', 'gradska vožnja', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mercedes GLA: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Mercedes GLA modela: menjač, trap, gradska eksploatacija, enterijer i realna premium računica.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Fotošopirane slike u oglasu: kako prepoznati da fotografije kriju više nego što pokazuju',
                'slug' => 'fotosopirane-slike-u-oglasu-kako-prepoznati-da-fotografije-kriju-vise-nego-sto-pokazuju',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Nisu problem samo loše slike oglasa. Problem su i one koje izgledaju predobro, jer često sakrivaju habanje, boju, limarske razlike i tragove realnog života automobila.',
                'content' => <<<'TEXT'
Kupac obično misli da je loša fotografija znak neozbiljnog oglasa, a dobra fotografija znak sigurnije kupovine. To je previše jednostavno. Danas su često veći problem upravo slike koje deluju previše čisto, previše ravnomerno osvetljeno i previše savršeno za realan polovan automobil. Kada oglas izgleda kao katalog, kupac mora pojačati oprez, ne spustiti ga.

Prvi signal su nerealno jednaki tonovi boje i odsustvo svih sitnih nesavršenosti. Ako se na fotografijama ne vide mikrotragovi korišćenja, razlike u odsjaju između elemenata ili prirodna dubina materijala enterijera, moguće je da su fotografije agresivno obrađene. To ne znači automatski da je auto loš, ali znači da prodavac želi da oblikuje prvi utisak jače nego što bi stvarno stanje možda dozvolilo.

Drugi signal su detalji koji su “izgubljeni” baš tamo gde bi kupac voleo da vidi više. Pragovi, donje ivice vrata, bočne strane sedišta, ivice volana, rubovi branika i spojevi limarije često ostanu u senci, zamućeni ili fotografisani iz ugla koji izgleda atraktivno, ali neinformativno. To je trenutak kada dobar oglas prestaje da bude koristan oglas.

Treći signal je nesklad između priče i vizuala. Ako auto navodno ima dosta kilometara, ali enterijer na slikama izgleda kao da nikada nije korišćen, kupac mora tražiti dodatne fotografije i konkretan pregled. Obrada slike može ukloniti nijanse, ali ne može promeniti realno stanje automobila kada staneš pored njega. Zato je najskuplja greška verovati fotografiji više nego logici.

Najpametniji kupac ne traži savršene slike nego poštene slike. Dobar oglas pokazuje i ono što je lepo i ono što je važno za procenu stanja. Kada fotografije izgledaju previše marketinški, to nije razlog za automatsko odbijanje, ali jeste razlog za dodatna pitanja i stroži pregled uživo. Auto se ne kupuje kroz filter.
TEXT,
                'highlights' => [
                    'Previše savršene fotografije mogu biti veći problem od loših slika jer često prikrivaju realno habanje.',
                    'Pragovi, rubovi sedišta, spojevi limarije i donji delovi vrata moraju se videti jasno ako je oglas pošten.',
                    'Kupac ne treba da traži savršene fotografije nego fotografije koje daju informacije, ne samo utisak.',
                ],
                'tags' => ['fotografije oglasa', 'provera vozila', 'limarija', 'enterijer'],
                'meta_title' => 'Fotošopirane slike u oglasu: kako ih prepoznati',
                'meta_description' => 'Kako prepoznati obrađene i previše ulepšane fotografije oglasa: boja, limarija, enterijer, detalji habanja i logika prikaza automobila.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Karavan do 12.000 evra: kada ima više smisla od SUV-a i porodičnog automatika',
                'slug' => 'karavan-do-12000-evra-kada-ima-vise-smisla-od-suv-a-i-porodicnog-automatika',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kupci često beže ka SUV-u ili automatiku čim budžet poraste, ali dobar karavan do 12.000 evra često nudi više prostora, manje mase i mirniju računicu od popularnijih alternativa.',
                'content' => <<<'TEXT'
Kada budžet poraste do oko 12.000 evra, mnogi kupci automatski krenu ka SUV-u ili porodičnom automatiku jer im tržište stalno ponavlja da je to “sledeći nivo” praktičnosti. U stvarnosti, dobar karavan u ovom budžetu često daje više stvarne korisnosti i manje skrivenih troškova od oba ta pravca. Problem je što karavan na oglasu ređe izgleda kao želja, a češće kao racionalna odluka. To ga čini potcenjenim.

Prva prednost karavana je jednostavna: prostor koji služi, a ne samo izgleda veliko. Utovar, nizak prag gepeka, stabilnost na putu i niža masa u odnosu na SUV često daju bolju svakodnevicu porodici koja zaista koristi auto za put, stvari i logistiku. Kupac pritom često dobije više automobila za isti novac jer tržište snažnije nagrađuje crossover izgled nego stvarnu funkcionalnost.

Druga prednost je mehanička računica. SUV i automatik neretko donose više mase, više očekivanja od opreme i često složeniji profil održavanja. To ne znači da su loši, već da kupac mora znati za šta plaća. Dobar karavan može ponuditi mirniji trap, nižu potrošnju, lakšu preglednost mehanike i manje razloga da se oseća kao da je kupio marketinški dodatak na točkovima.

Treća stvar je psihologija kupovine. Karavan retko osvaja na prvu loptu, ali često pobeđuje posle šest meseci vlasništva kada se pokaže koliko malo drame unosi u svakodnevni život. Kupac koji bira kroz realnu upotrebu, a ne kroz trend, često upravo ovde napravi najpametniji potez. Nije glamurozan, ali ume da bude najbolji odnos korisnosti i troška u ovoj zoni budžeta.

Najbolji karavan do 12.000 evra nije dosadna opcija, nego racionalna pobeda nad tržišnim klišeom. Ako porodici stvarno treba prostor, putna stabilnost i mirnija servisna logika, karavan često ima više smisla od SUV-a i skuplje automatske alternative. Problem je samo što to tržište ređe prodaje emocijom, a češće potvrđuje tek iskustvom.
TEXT,
                'highlights' => [
                    'Karavan do 12.000 evra često nudi više stvarnog prostora i manje mase od SUV alternative za isti novac.',
                    'Niža masa i jednostavnija mehanička računica često znače mirniju dugoročnu kupovinu od popularnijih trendova.',
                    'Kupac koji bira kroz stvarnu porodičnu upotrebu često u karavanu dobije više smisla nego što oglas na prvi pogled sugeriše.',
                ],
                'tags' => ['karavan', 'auto do 12000 evra', 'porodica', 'analiza tržišta'],
                'meta_title' => 'Karavan do 12.000 evra: kada ima više smisla od SUV-a',
                'meta_description' => 'Zašto dobar polovni karavan do 12.000 evra često ima više smisla od SUV-a ili porodičnog automatika: prostor, masa, troškovi i praktičnost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Honda CR-V ili Mazda CX-5: porodični benzinac kada miran posed vredi više od mode',
                'slug' => 'honda-cr-v-ili-mazda-cx-5-porodicni-benzinac-kada-miran-posed-vredi-vise-od-mode',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Honda CR-V i Mazda CX-5 deluju kao sigurna kupovina, ali tek kroz motor, prostor, menjač i trošak održavanja vidi se kome koji SUV stvarno leži.',
                'content' => <<<'TEXT'
Honda CR-V i Mazda CX-5 često završe u istoj užoj selekciji kada se traži porodični SUV bez premijum cene i bez dizel komplikacija koje kupac ne želi da nosi narednih nekoliko godina. Na oglasima oba modela drže cenu, pa razlika u izboru retko dolazi iz same cifre. Mnogo više znači kakav stil vožnje kupac ima i koliko mu je bitno da auto bude tih, prostran i predvidiv na duži rok.

CR-V uglavnom kupuju ljudi kojima je prioritet komfor i praktičnost. Zadnja klupa je prostranija, gepek je upotrebljiviji za porodična putovanja, a ergonomija je jednostavnija za svakodnevni život. Benzinski motori u Hondi nemaju sportski karakter, ali zato ostavljaju utisak automobila koji ne traži stalnu pažnju. Kada je istorija uredna, CR-V je često mirniji izbor za kupca koji želi da sipa gorivo, radi servise na vreme i ne razmišlja mnogo dalje od toga.

Mazda CX-5 je na drugoj strani privlačnija vozaču. Upravljač je direktniji, položaj za volanom prirodniji, a enterijer deluje zategnutije i skuplje nego što klasa realno obećava. Skyactiv benzinski motori umeju da traže nešto više obrtaja da bi pokazali najbolju stranu, ali upravo tu deo kupaca dobija osećaj koji Honda nema. Ko uživa u vožnji i ne smeta mu tvrđi karakter na lošijem asfaltu, često će se lakše povezati sa Mazdom nego sa CR-V-om.

Kod automatika je važno gledati tip upotrebe. Honda sa klasičnim automatikom ili CVT-om najviše prija vozaču koji traži glatkoću i rasterećenost u gradu. Mazda automatik ume da deluje prirodnije pri međuubrzanjima, ali u loše održavanim primercima može da otkrije nervozu pri hladnom radu ili kasnijem prebacivanju. Nijedan od ova dva SUV-a ne treba birati bez detaljnog pregleda servisne istorije, jer kupac lako poveruje reputaciji i propusti konkretan primerak koji je već ušao u skupu fazu održavanja.

Zaključak je jednostavan: Honda CR-V više ima smisla za porodicu kojoj su prostor, tišina i rasterećen posed ispred vozačkog utiska, dok Mazda CX-5 više opravdava cenu kada kupac traži SUV koji ne deluje tromo i bezlično. Na istom budžetu često nije pitanje koji je model bolji, nego koji primerak ima uredniju istoriju i bolji mehanički trag.
TEXT,
                'highlights' => [
                    'CR-V obično nudi više prostora i mirniji porodični karakter.',
                    'CX-5 ostavlja bolji utisak za volanom i u kabini.',
                    'Automatik i uredna istorija su važniji od same reputacije modela.',
                ],
                'tags' => ['Honda CR-V', 'Mazda CX-5', 'porodični SUV', 'benzinac'],
                'meta_title' => 'Honda CR-V ili Mazda CX-5: koji porodični benzinac ima više smisla?',
                'meta_description' => 'Detaljno poređenje Honda CR-V i Mazda CX-5 kroz prostor, benzinske motore, automatik, komfor i trošak poseda na polovnom tržištu Srbije.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#2563eb', '#e2e8f0'],
            ],
            [
                'title' => 'Polovni Lexus CT 200h: gradski premium hibrid koji traži miran pregled baterije',
                'slug' => 'polovni-lexus-ct-200h-gradski-premium-hibrid-koji-trazi-miran-pregled-baterije',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Lexus CT 200h nudi premijum utisak i hibridnu mehaniku bez velike drame, ali baterija, trap i gradska eksploatacija traže hladnu proveru pre kupovine.',
                'content' => <<<'TEXT'
Lexus CT 200h je jedan od onih polovnjaka koji na papiru deluju gotovo idealno za grad: kompaktan, tih pri laganoj vožnji, sa hibridnom reputacijom i enterijerom koji deluje ozbiljnije od prosečnog kompakta. Upravo zbog toga mnogi kupci previše veruju priči o pouzdanosti i preskoče dublju proveru. Reputacija jeste jaka, ali ne popravlja loše održavan primerak.

Najvažnija tačka pregleda je stanje hibridne baterije i trag kako je auto korišćen. CT 200h često živi tipičan gradski život: kratke relacije, ivičnjaci, česti hladni startovi i mnogo sitnih kontakata po karoseriji. To znači da dobar pregled ne sme da se završi samo na dijagnostici. Potrebno je proveriti kako se baterija ponaša u vožnji, kako radi ventilacija sistema, da li postoje neprirodna odstupanja u punjenju i pražnjenju i da li auto ostavlja utisak primerka koji je vožen pažljivo ili samo redovno paljen.

Benzinski motor u kombinaciji sa hibridnim sklopom uglavnom je mirnija priča od mnogih malih turbobenzinaca, ali upravo zato kupac lako zanemari trap. CT 200h ume da sakrije umor na lošijem asfaltu dok ga ne oteraš na probnu vožnju preko neravnina i sitnih poprečnih udaraca. Tu se najbrže čuje da li automobil dolazi iz uredne eksploatacije ili je gradski život već pojeo dobar deo osećaja zategnutosti.

Enterijer je kvalitetan, ali nije imun na tragove zanemarivanja. Izlizani volan, umoran naslon vozačevog sedišta i preterano polirane plastike često govore više od brojke na satu. Ako oglas prikazuje „malo vožen“ primerak, a kabina deluje potrošenije nego što kilometraža obećava, treba podići oprez i proveriti celu priču pre nego što cena počne da deluje primamljivo.

Lexus CT 200h ima najviše smisla za kupca koji želi tih i kultivisan gradski auto, a spreman je da plati malo više za uredan primerak sa čistom istorijom. Najskuplja greška nije kupiti skuplji Lexus, nego jeftiniji Lexus koji na prvu izgleda mirno, a tek posle kupovine pokaže koliko gradska eksploatacija ume da ostavi račun.
TEXT,
                'highlights' => [
                    'Hibridna baterija i način gradske upotrebe moraju da se proveravaju zajedno.',
                    'Trap i enterijer brzo otkrivaju da li je primerak zaista negovan.',
                    'Jeftiniji CT 200h lako postane skuplji izbor na srednji rok.',
                ],
                'tags' => ['Lexus CT 200h', 'hibrid', 'premium kompakt', 'polovni automobil'],
                'meta_title' => 'Polovni Lexus CT 200h: šta proveriti pre kupovine?',
                'meta_description' => 'AutoIQ vodič za Lexus CT 200h: baterija, trap, enterijer i gradska eksploatacija pre kupovine polovnog premium hibrida u Srbiji.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Oglas bez registarskih tablica: kada je sitnica, a kada ozbiljan signal za oprez',
                'slug' => 'oglas-bez-registarskih-tablica-kada-je-sitnica-a-kada-ozbiljan-signal-za-oprez',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Zamagljene ili skinute tablice ne znače automatski problem, ali često govore koliko je prodavac spreman da sakrije trag vozila pre nego što ga vidiš uživo.',
                'content' => <<<'TEXT'
Mnogi oglasi dolaze sa prekrivenim ili potpuno skinutim registarskim tablicama i deo kupaca to prihvata kao normalnu pojavu. Nekada zaista jeste samo odluka prodavca da ne izlaže auto javno više nego što mora, ali često način na koji su tablice uklonjene govori koliko je pažnje uloženo da se sakrije kontekst vozila. Kada fotografije već na startu deluju zatvoreno i nedorečeno, kupac treba da uspori i postavi nekoliko dodatnih pitanja.

Prva razlika je između uredno zamućene tablice i vozila koje je fotografisano bez tablica, sa praznim nosačima ili tragovima da je auto tek stigao i još nije ušao u normalnu upotrebu. Kod polovnjaka bez tablica treba odmah pitati da li je vozilo odjavljeno, tek uvezeno, vraćeno iz lizinga ili je možda dugo stajalo. Ovakve informacije same po sebi nisu problem, ali bez jasnog odgovora lako sakriju priču koju kupac čuje tek kad ode na lice mesta.

Važno je i šta prati taj detalj. Ako su tablice uklonjene, a oglas nema jasan VIN, servisni trag, račun poslednjeg održavanja ili makar dosledne fotografije spolja i iznutra, dobijaš obrazac, a ne slučajnost. Prodavac koji krije previše sitnica uglavnom te tera da informacije skupljaš kap po kap, što retko prati stvarno dobar primerak.

Na drugoj strani, privatni prodavac sa urednim opisom, normalnim fotografijama i spremnošću da pošalje dodatne informacije ne mora biti sumnjiv samo zato što je zamutio tablice. Tu je ključ komunikacija. Ako bez zatezanja dobiješ šasiju za proveru, detalje o vlasništvu i razlog zašto su tablice sklonjene, verovatno gledaš razumnu meru opreza, a ne pokušaj skrivanja.

Zato tablice ne treba posmatrati kao dokaz nego kao signal. Same po sebi ne znače ni dobar ni loš auto, ali veoma dobro pokazuju koliko transparentno prodavac vodi priču. A na tržištu polovnjaka transparentnost je često bolji filter od lepog sjaja na slikama.
TEXT,
                'highlights' => [
                    'Prekrivene tablice nisu problem ako ostatak oglasa ostaje jasan i proverljiv.',
                    'Auto bez tablica traži dodatna pitanja o statusu vozila i poreklu.',
                    'Nedostatak VIN-a i servisnog traga uz ovakav oglas podiže rizik.',
                ],
                'tags' => ['tablice', 'provera oglasa', 'VIN', 'polovni automobil'],
                'meta_title' => 'Oglas bez tablica: kada treba da budeš oprezan?',
                'meta_description' => 'Kako tumačiti oglas bez registarskih tablica, kada je to normalno, a kada signal za dodatnu proveru pre kupovine polovnog auta.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#f59e0b', '#e5e7eb'],
            ],
            [
                'title' => 'Lanac ili kaiš: kako ta razlika menja trošak polovnog auta u prve dve godine',
                'slug' => 'lanac-ili-kais-kako-ta-razlika-menja-trosak-polovnog-auta-u-prve-dve-godine',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kupci često misle da je lanac automatski mirniji od kaiša, ali tek kada se uračuna realan servis i rizik kvara vidi se koliko ta razlika menja budžet posle kupovine.',
                'content' => <<<'TEXT'
Kada kupac bira polovan automobil, razlika između lanca i kaiša često zvuči kao kratka tehnička stavka koju će usput precrtati. U praksi ta stavka ume ozbiljno da promeni računicu u prve dve godine poseda. Problem je što se oba pojma previše pojednostavljuju: kaiš se doživljava kao obavezan trošak, a lanac kao nešto što „nema servis“. Takav pogled vodi u pogrešne odluke.

Kaiš je skuplja stavka unapred samo zato što je interval zamene poznatiji i lakše se planira. Ako kupiš auto bez jasnog dokaza kada je poslednji put urađen veliki servis, vrlo verovatno ćeš taj trošak morati odmah da uneseš u budžet. To nije prijatno, ali je makar predvidivo. Kupac zna da rešava kritičnu stavku i posle toga određeni period vozi rasterećenije.

Lanac deluje prijatnije upravo zato što mnogo prodavaca voli da ga predstavi kao trajno rešenje. Međutim, lanac nije besplatan niti večan. Ako se automobil vozio sa lošim intervalima zamene ulja, sa hladnim startovima i bez dovoljno pažnje, lanac, zatezači i prateće komponente umeju da naprave račun koji će lako biti veći od uredno planirane zamene kaiša. Najveći problem je što se taj trošak ne vidi u oglasu, nego se čuje tek na hladnom paljenju, pri radu u leru ili kroz mehanički pregled.

Zato kupac ne sme da gleda samo tip razvoda nego i dokaz održavanja. Auto sa kaišem i jasnim računima može biti mirnija kupovina od auta sa lancem kod kog niko ne zna kako je ulje menjano i kada je poslednji put motor stvarno pregledan. Isto važi i obrnuto: kvalitetan primerak sa lancem i urednim servisnim tragom često opravda to što nema neposredan veliki servis na horizontu.

Prava pouka nije da je jedno rešenje bolje od drugog, nego da različito raspoređuju rizik. Kaiš češće donosi planiran trošak odmah posle kupovine, a lanac potencijalno veći, ali manje predvidiv račun ako je auto zanemarivan. Kupac koji to shvati na vreme lakše postavlja realan budžet i manje veruje marketingu u oglasu.
TEXT,
                'highlights' => [
                    'Kaiš češće znači planiran i proverljiv trošak.',
                    'Lanac nije garancija da motor nema skup servis na vidiku.',
                    'Istorija održavanja je važnija od same tehničke etikete.',
                ],
                'tags' => ['lanac', 'kaiš', 'veliki servis', 'trošak održavanja'],
                'meta_title' => 'Lanac ili kaiš: šta je povoljnije kod polovnog auta?',
                'meta_description' => 'AutoIQ objašnjava kako lanac i kaiš menjaju trošak polovnog automobila u prve dve godine i zašto istorija održavanja odlučuje više od etikete.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'SUV do 13.000 evra: da li vredi juriti višu klasu ili kupiti mlađi kompakt',
                'slug' => 'suv-do-13000-evra-da-li-vredi-juriti-visu-klasu-ili-kupiti-mladi-kompakt',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Na budžetu do 13.000 evra kupci često biraju između starijeg većeg SUV-a i mlađeg kompaktnog crossovera, a prava razlika se vidi tek kroz starost, servis i upotrebu.',
                'content' => <<<'TEXT'
Budžet do 13.000 evra otvara jednu od najzamršenijih zona polovnog tržišta: taman si dovoljno visoko da gledaš ozbiljnije SUV modele, ali i dalje nisi u prostoru gde možeš da biraš bez mnogo kompromisa. Zato kupci često upadnu u dilemu da li da uzmu stariji automobil više klase, sa više prostora i jačim imidžom, ili mlađi kompaktni crossover koji nudi manje auta za isti novac, ali i manji mehanički rizik.

Stariji veći SUV privlači zato što na slikama izgleda kao „pravi auto za porodicu“. Dobijaš više prostora, jaču pojavu na putu i često bogatiju opremu. Problem je što se u tom cenovnom okviru često kupuje i više kilometara, stariji automatik, skuplji trap i šira lista potrošnih delova koji više nisu teorija nego realan trošak. Kupac lako zaboravi da ne plaća samo veličinu vozila, nego i veličinu njegovog računa kad dođe vreme za održavanje.

Mlađi kompaktni crossover deluje kao manji zalogaj, ali često bolje odgovara stvarnoj svakodnevici većine ljudi u Srbiji. Lakše se vozi po gradu, jeftiniji je za gume, kočnice i sitnije intervencije, a obično donosi i noviju bezbednosnu i multimedijalnu opremu. Nedostatak je što kupac ponekad plaća više samo zato što je auto mlađi i traženiji, pa dobije manje širine i tišine nego kod starije više klase.

Najvažnije je razumeti sopstveni profil vožnje. Ako porodica zaista često putuje puna, vuče mnogo prtljaga i želi komfor na otvorenom putu, stariji veći SUV može da ima smisla pod uslovom da servisni trag bude jak i da budžet ne stane na kupovnoj ceni. Ako je većina vožnje gradska, a putovanja povremena, mlađi kompakt često nudi zdraviju ukupnu računicu i manje iznenađenja.

U toj zoni tržišta nema univerzalnog pobednika. Najskuplja greška je kupiti klasu koju budžet jedva pokriva, a potom nemati prostora da ispratiš njen nivo održavanja. Zato SUV do 13.000 evra ne treba birati po veličini auta, nego po veličini rizika koji realno možeš da nosiš.
TEXT,
                'highlights' => [
                    'Veći SUV za isti novac obično znači više kilometara i skuplji servisni rizik.',
                    'Mlađi kompaktni crossover često bolje odgovara gradskoj i mešovitoj upotrebi.',
                    'Kupovna cena nije dovoljna bez rezervnog budžeta za prvu godinu poseda.',
                ],
                'tags' => ['SUV do 13000 evra', 'analiza tržišta', 'crossover', 'polovni automobil'],
                'meta_title' => 'SUV do 13.000 evra: viša klasa ili mlađi kompakt?',
                'meta_description' => 'Analiza AutoIQ za SUV do 13.000 evra: kada ima smisla stariji veći SUV, a kada je mlađi kompaktni crossover pametnija kupovina.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#06b6d4', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Tesla Model 3 u Srbiji: kada baterija nije jedino pitanje',
                'slug' => 'polovni-tesla-model-3-u-srbiji-kada-baterija-nije-jedino-pitanje',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Model 3 privlači kupce niskim troškom vožnje i jakim performansama, ali polovan električni auto traži drugačiju proveru od klasičnog benzinca ili dizela.',
                'content' => <<<'TEXT'
Tesla Model 3 sve češće ulazi u uži izbor kupaca koji razmišljaju o polovnom električnom automobilu, posebno kada se uporedi cena polovnih primeraka sa troškom goriva kod klasičnih automobila. Na prvi pogled deluje kao velika promena za razuman novac: snažne performanse, automatik, tiha vožnja i manji broj klasičnih servisnih stavki. Ipak, baš zato kupac lako preskoči pitanja koja kod električnog auta vrede više od same kilometraže.

Prva tema jeste baterija, ali ne samo kroz procenat kapaciteta. Važno je kako se auto punio, koliko često je korišćeno brzo punjenje, da li se domet ponaša predvidivo i da li postoje poruke sistema koje ukazuju na ograničenje snage ili punjenja. Zdrav Model 3 ne mora da ima savršenu brojku na ekranu, ali mora da ostavi dosledan utisak kroz probnu vožnju, dijagnostiku i istoriju softverskog održavanja.

Druga važna tačka je karoserija i trap. Model 3 je često brz automobil koji se lako vozi, pa deo primeraka iza sebe ima jaču eksploataciju nego što miran enterijer sugeriše. Neravnomerno trošenje guma, zvukovi preko neravnina, tragovi loših popravki i sitna odstupanja zazora mogu biti mnogo važniji od toga koliko auto ubrzava na pravcu. Električni pogon ne poništava fiziku ni cenu dobrog trapa.

Treća tema je svakodnevica kupca u Srbiji. Model 3 ima najviše smisla ako postoji realan plan punjenja kod kuće, na poslu ili na ruti koja se često koristi. Ako kupac zavisi samo od javnih punjača, računica može i dalje biti dobra, ali traži više discipline i manje improvizacije. Električni auto se kupuje kroz način života, ne samo kroz cenu kilometra.

Polovan Tesla Model 3 može biti odlična kupovina za vozača koji razume punjenje, proveri bateriju i ne zanemari mehanički deo automobila. Može biti i skupa frustracija ako se kupi samo zato što deluje moderno i brzo. Najmirniji primerak nije onaj sa najviše opreme, nego onaj koji ima jasnu istoriju, realan domet i vlasnika koji zna da objasni kako je auto korišćen.
TEXT,
                'highlights' => [
                    'Kod polovnog Modela 3 bateriju treba proveriti kroz ponašanje u vožnji, punjenje i dijagnostiku, ne samo kroz prikaz dometa.',
                    'Trap, gume i karoserija ostaju ključni jer električni pogon ne sakriva grubu eksploataciju.',
                    'Kupovina ima najviše smisla kada kupac ima realan i stabilan plan punjenja.',
                ],
                'tags' => ['Tesla Model 3', 'električni automobil', 'baterija', 'polovni auto'],
                'meta_title' => 'Polovni Tesla Model 3 u Srbiji: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Tesla Model 3 u Srbiji: baterija, punjenje, trap, gume, karoserija i realna računica električnog auta.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(24),
                'palette' => ['#111827', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Kupovina auta posle lizinga: kada uredna istorija nije cela slika',
                'slug' => 'kupovina-auta-posle-lizinga-kada-uredna-istorija-nije-cela-slika',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Automobil vraćen iz lizinga često ima servisni trag, ali kupac mora razumeti kako je korišćen, ko ga je vozio i šta se dešava pred kraj ugovora.',
                'content' => <<<'TEXT'
Auto posle lizinga mnogim kupcima zvuči kao uredniji polovnjak. Postoji firma, postoji ugovor, postoji servisni ritam i često postoji jasnija dokumentacija nego kod privatnog vlasnika koji je čuvao papire samo kada se setio. To jeste prednost, ali nije garancija da je konkretan primerak mirna kupovina. Lizing istorija objašnjava okvir, ali ne objašnjava uvek način vožnje.

Najveća prednost ovakvih automobila je trag održavanja. Ako su servisi rađeni u roku, ako postoje računi i ako se vidi doslednost kilometraže, kupac dobija bolju početnu sliku nego kod auta sa praznom servisnom pričom. Problem nastaje kada se ta urednost pogrešno protumači kao dokaz pažljivog korišćenja. Službeni auto može biti održavan redovno, a ipak vožen grubo, hladan, kratkim relacijama i bez osećaja vlasničke brige.

Posebno treba gledati poslednju godinu ugovora. Tada deo korisnika prestaje da ulaže u sitnice jer zna da auto uskoro vraća. Gume mogu biti pri kraju, kočnice odložene, enterijer umoran, a sitni kvarovi prikriveni jer automobil prolazi osnovni nivo predaje. Ako cena deluje dobra, kupac mora uračunati upravo ta odložena ulaganja.

Kod auta iz lizinga pažljivo proveri opremu, klimu, multimediju, brave, senzore, stanje sedišta i tragove korišćenja gepeka. To su detalji koji otkrivaju kako je auto stvarno živeo. Ako je bio kod jednog korisnika i ima logičan ritam servisa, rizik je manji. Ako je bio flotni automobil koji je menjao vozače, treba biti stroži nego kod privatnog vlasništva.

Kupovina posle lizinga može biti vrlo dobra odluka kada dokumentacija, stanje i cena pričaju istu priču. Ali ako se kupac osloni samo na reč "lizing", lako plati automobil koji je formalno održavan, ali praktično potrošen. Pravo pitanje nije da li je auto bio na lizingu, nego da li su tragovi korišćenja u skladu sa kilometražom, cenom i obećanom istorijom.
TEXT,
                'highlights' => [
                    'Lizing istorija pomaže, ali ne dokazuje da je auto vožen pažljivo.',
                    'Poslednja godina ugovora često otkriva odložena ulaganja u gume, kočnice i sitne kvarove.',
                    'Jedan korisnik i dosledni računi vrede više od same činjenice da je auto bio u lizingu.',
                ],
                'tags' => ['lizing', 'službeni auto', 'kupovina polovnjaka', 'servisna istorija'],
                'meta_title' => 'Kupovina auta posle lizinga: šta proveriti',
                'meta_description' => 'Kako proceniti polovan auto posle lizinga: servisna istorija, flotna vožnja, odložena ulaganja, enterijer i realan rizik pre kupovine.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(18),
                'palette' => ['#172033', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'AdBlue kod polovnog dizela: mali rezervoar koji može napraviti veliki račun',
                'slug' => 'adblue-kod-polovnog-dizela-mali-rezervoar-koji-moze-napraviti-veliki-racun',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'AdBlue sistem se često pominje tek kad se upali greška, a kod polovnog dizela upravo ta stavka može otkriti koliko je auto zaista održavan.',
                'content' => <<<'TEXT'
Kod modernih polovnih dizela kupci obično pričaju o DPF-u, EGR-u, turbini i diznama, dok AdBlue ostane sitna fusnota u oglasu. To je greška. AdBlue sistem jeste napravljen da smanji emisije, ali kao polovan deo vlasništva može doneti senzor, pumpu, grejač, diznu, softversko upozorenje i neprijatnu poruku da auto posle određenog broja kilometara neće moći da se pokrene.

Prvi signal za oprez je svaka priča prodavca da je "to samo lampica". Kod AdBlue sistema lampica retko treba da bude prihvaćena kao sitnica dok se ne uradi dijagnostika. Nekad je problem nivo tečnosti ili loš kvalitet AdBlue-a, ali nekad je kvar na sistemu koji traži ozbiljniji račun. Kupac koji to preskoči jer motor radi lepo može vrlo brzo posle kupovine završiti u servisu.

Drugi problem je način korišćenja. Automobil koji često vozi kratke gradske relacije i ne dobija pravilan servisni ritam može imati više problema sa emisijskim sistemima nego auto koji prelazi duže relacije. AdBlue ne treba posmatrati izolovano. Ako postoje problemi sa DPF-om, EGR-om, senzorima izduva ili softverskim intervencijama, cela slika postaje rizičnija.

Posebno treba biti oprezan sa automobilima kod kojih je sistem "rešen" nejasnim softverom. Takav auto može delovati jeftinije i jednostavnije u trenutku kupovine, ali otvara pitanja tehničkog pregleda, legalnosti, daljih kvarova i kasnije prodaje. Kratkoročno uklanjanje problema često samo premešta rizik na sledećeg vlasnika.

Polovan dizel sa AdBlue sistemom nije automatski loša kupovina. Naprotiv, dobar primerak sa jasnim servisima, urednom dijagnostikom i bez aktivnih grešaka može biti sasvim razuman izbor za otvoren put. Ali kupac mora u budžet uneti i ovaj sistem. Ako već plaćaš modernog dizela, proveri sve što ga čini modernim, ne samo potrošnju i snagu motora.
TEXT,
                'highlights' => [
                    'AdBlue greška ne treba da se prihvati kao sitnica bez dijagnostike.',
                    'Sistem treba posmatrati zajedno sa DPF-om, EGR-om i senzorima izduva.',
                    'Nejasno softversko uklanjanje problema može kasnije ugroziti tehnički pregled i prodaju.',
                ],
                'tags' => ['AdBlue', 'dizel', 'troškovi održavanja', 'DPF'],
                'meta_title' => 'AdBlue kod polovnog dizela: trošak koji treba proveriti',
                'meta_description' => 'Šta proveriti kod AdBlue sistema polovnog dizela: lampice, senzori, pumpa, DPF, EGR, softverske intervencije i realan servisni rizik.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(12),
                'palette' => ['#0f172a', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Auto posle lakšeg udesa: kako razlikovati dobru popravku od skrivene štete',
                'slug' => 'auto-posle-lakseg-udesa-kako-razlikovati-dobru-popravku-od-skrivene-stete',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Nije svaki udaren auto loša kupovina, ali kupac mora znati gde se završava korektna kozmetika, a gde počinje rizik konstrukcije, elektronike i kasnije prodaje.',
                'content' => <<<'TEXT'
Na polovnom tržištu skoro svaki kupac želi automobil koji nikada nije bio udaren. U stvarnosti, mnogo automobila ima neku istoriju sitnog kontakta, farbanog branika, menjane haube ili popravljene strane. To samo po sebi ne mora biti problem. Problem počinje kada prodavac lakši udes koristi kao maglovitu frazu za štetu koja je možda bila mnogo ozbiljnija nego što oglas priznaje.

Dobra popravka ima logiku. Zazori su ujednačeni, nijansa laka ne beži pod različitim svetlom, šrafovi i nosači ne deluju sveže dirano bez objašnjenja, a dokumentacija makar okvirno potvrđuje šta je rađeno. Ako prodavac otvoreno kaže da je menjan branik i pokaže fotografije pre popravke, to je često bolja situacija nego savršen oglas bez ijednog odgovora na konkretna pitanja.

Skrivena šteta se najčešće vidi kroz nedoslednosti. Jedan far noviji od drugog, drugačija tekstura laka, tragovi magle u lampama, neravnomerno zatvaranje vrata, neobični zvukovi iz trapa i greške senzora mogu govoriti više od tvrdnje da je "samo ogrebano". Kod modernih automobila i mali udarac može otvoriti pitanje radara, kamera, parking senzora i kalibracije sistema pomoći vozaču.

Posebno je važno proveriti strukturu vozila. Kozmetički farban deo nije isto što i auto koji je imao pomerene nosače, loše varene elemente ili površno vraćene bezbednosne sisteme. Ako pregled pokaže tragove ozbiljnije intervencije na nosećim delovima, niska cena više nije prednost nego upozorenje.

Auto posle lakšeg udesa može biti dobra kupovina ako je popravka kvalitetna, dokumentovana i uračunata u cenu. Kupac tada zna šta plaća i šta kasnije može iskreno reći sledećem vlasniku. Loša kupovina je auto koji je popravljen samo da lepo izgleda na fotografijama, dok mehanika, elektronika i struktura nose posledice koje će se pojaviti tek posle kupovine.
TEXT,
                'highlights' => [
                    'Lakši udes nije automatski razlog za odustajanje ako postoji jasna dokumentacija i kvalitetna popravka.',
                    'Zazori, nijansa laka, farovi, senzori i ponašanje vrata brzo otkrivaju nedoslednosti.',
                    'Strukturna oštećenja i loše vraćeni bezbednosni sistemi menjaju celu računicu kupovine.',
                ],
                'tags' => ['udes', 'karoserija', 'provera vozila', 'farban auto'],
                'meta_title' => 'Auto posle lakšeg udesa: kako proveriti štetu',
                'meta_description' => 'Kako proveriti polovan auto posle lakšeg udesa: zazori, lak, farovi, senzori, struktura, dokumentacija i realna vrednost popravljenog vozila.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(6),
                'palette' => ['#111827', '#fb7185', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Citroen C5 Aircross: udoban porodični SUV koji ne sme da sakrije elektroniku',
                'slug' => 'polovni-citroen-c5-aircross-udoban-porodicni-suv-koji-ne-sme-da-sakrije-elektroniku',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C5 Aircross kupce privlači komforom i drugačijim karakterom od nemačkih SUV modela, ali polovan primerak mora potvrditi da elektronika, trap i motor prate udobnu priču.',
                'content' => <<<'TEXT'
Citroen C5 Aircross je zanimljiv polovan SUV jer ne pokušava da se proda istom logikom kao tvrđi i sportskiji konkurenti. Njegova glavna karta je komfor: mekši osećaj u vožnji, opuštena kabina i porodična upotrebljivost bez želje da se svaki kilometar predstavi kao sportski doživljaj. Za mnoge kupce to je baš ono što im treba, ali kod polovnog primerka udobnost ne sme da uspava proveru.

Prvo treba gledati stanje trapa i način na koji auto prelazi preko lošijeg puta. C5 Aircross treba da deluje mirno i zaokruženo, bez lupkanja, zatezanja ili osećaja da se karoserija posle neravnine smiruje predugo. Ako auto spolja izgleda očuvano, a na probnoj vožnji zvuči umorno, to je signal da komfor možda više postoji u reputaciji nego u konkretnom primerku.

Druga tema je elektronika. Bogatije opremljeni primerci mogu imati dosta sistema koji kupcu prijaju svaki dan, ali svaki ekran, senzor, kamera, klima zona, prekidač i pomoćni sistem mora da radi bez izgovora. Francuski SUV ne treba kupovati kroz predrasude, ali ne treba ni ignorisati sitne elektronske nelogičnosti samo zato što auto lepo izgleda i udobno sedi.

Kod motora treba gledati realnu upotrebu. Dizel ima smisla za duže relacije i porodična putovanja, ali traži proveru DPF-a, AdBlue sistema i servisne istorije. Benzinske verzije mogu biti prijatnije za grad, ali kupac mora razumeti servisni ritam, potrošnju i poznate slabosti konkretne generacije. U oba slučaja je važnije stanje primerka nego želja da se po svaku cenu izbegne ili izabere određeno gorivo.

Polovni C5 Aircross ima najviše smisla za kupca koji želi miran porodični SUV i ne pati od premium značke. Dobar primerak može dati mnogo komfora za novac, ali samo ako probna vožnja, dijagnostika i dokumentacija potvrde da se iza udobne priče ne kriju odloženi troškovi. Ako sve radi uredno, ovo može biti vrlo racionalna alternativa skupljim i tvrđim rivalima.
TEXT,
                'highlights' => [
                    'C5 Aircross treba kupovati kroz kvalitet konkretnog primerka, ne kroz opštu priču o udobnosti.',
                    'Elektronika i sistemi pomoći moraju raditi bez izgovora pre dogovora o ceni.',
                    'Dizel i benzin imaju smisla samo kada odgovaraju stvarnom profilu vožnje kupca.',
                ],
                'tags' => ['Citroen C5 Aircross', 'porodični SUV', 'komfor', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Citroen C5 Aircross: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Citroen C5 Aircross modela: komfor, trap, elektronika, dizel i benzin motori, servisna istorija i realan rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#a3e635', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Subaru Forester: stalni pogon koji traži uredan servisni trag',
                'slug' => 'polovni-subaru-forester-stalni-pogon-koji-trazi-uredan-servisni-trag',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Forester kupce privlači pogonom, preglednošću i robusnim imidžom, ali dobar primerak zavisi od servisa, menjača i toga kako je stvarno korišćen.',
                'content' => <<<'TEXT'
Subaru Forester je polovnjak koji često privuče kupce koji ne žele klasičan gradski SUV, već automobil sa stvarno korisnim stalnim pogonom, dobrom preglednošću i mirnijim karakterom za loš put. Na papiru deluje kao racionalan izbor za porodicu, vikendicu, sneg i vozače koji ne žele da razmišljaju o proklizavanju. Ipak, upravo zbog tog imidža kupac lako pretpostavi da je svaki Forester automatski izdržljiv, što nije dovoljno dobra osnova za kupovinu.

Kod polovnog Forestera servisna istorija mora biti prva tema. Boxer motor traži redovno održavanje, kvalitetno ulje i vlasnika koji razume ritam servisa. Ako prodavac nema jasne račune, ako ne zna šta je rađeno ili ako se sve svodi na opštu priču da je "Subaru pouzdan", treba usporiti. Reputacija ne menja činjenicu da zapušten primerak može tražiti ozbiljnija ulaganja.

Druga tačka je menjač i pogon. Automatski menjači, posebno kod primeraka koji su mnogo vozili grad ili vukli teret, moraju raditi mirno, bez trzaja, zadrške ili neobičnog zvuka. Stalni pogon je prednost tek kada nema zapuštenih diferencijala, loših guma različitog profila i servisa koji su preskakani jer auto "ide svuda".

Forester takođe treba proveriti kroz stvarnu upotrebu. Ako je auto živio po lošem putu, lovu, planini ili vikend naselju, trap, pragovi, pod i zaštita ispod vozila mogu reći više od kilometraže. Dobar primerak će delovati zategnuto i jednostavno. Umoran primerak će pokušati da se sakrije iza robusnog izgleda.

Polovni Subaru Forester ima mnogo smisla za kupca koji zaista koristi pogon i želi praktičan automobil bez premium predstave. Ali treba ga kupovati hladno: servis, menjač, pogon, gume i stanje podvozja pre svega. Ako se to poklopi, Forester može biti vrlo zahvalan izbor. Ako se ne poklopi, specifičnost marke brzo postaje skuplja od uobičajenog SUV-a.
TEXT,
                'highlights' => [
                    'Forester ima smisla samo kada servisna istorija prati reputaciju stalnog pogona.',
                    'Menjač, diferencijali, gume i podvozje moraju biti deo ozbiljne provere.',
                    'Robustan izgled ne sme da sakrije težak život po lošem putu ili zapušten servis.',
                ],
                'tags' => ['Subaru Forester', 'stalni pogon', 'SUV', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Subaru Forester: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Subaru Forestera: boxer motor, stalni pogon, menjač, gume, podvozje, servisna istorija i realan trošak.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(20),
                'palette' => ['#102033', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Kupovina auta bez probne vožnje: kada treba odmah odustati',
                'slug' => 'kupovina-auta-bez-probne-voznje-kada-treba-odmah-odustati',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Prodavac koji ne dozvoljava probnu vožnju traži od kupca da preuzme rizik na slepo, a kod polovnog auta baš vožnja najbrže otkriva skupe nedostatke.',
                'content' => <<<'TEXT'
Kupovina polovnog auta bez probne vožnje deluje kao mala neprijatnost dok se ne shvati koliko se informacija gubi. Fotografije mogu sakriti zvukove, opis može preskočiti trzaje, a uredan enterijer ne govori kako se auto ponaša hladan, u krivini, pri kočenju ili pri promeni brzina. Probna vožnja nije luksuz. To je osnovni deo provere.

Postoje situacije kada prodavac ima razuman razlog za ograničenje: auto nije registrovan, nalazi se u salonu bez tablica ili treba dogovoriti termin. Ali tada mora postojati alternativa, poput pregleda u servisu, probnih tablica ili jasnog načina da se auto pokrene i proveri pod opterećenjem. Ako je odgovor samo "nema potrebe, sve je ispravno", kupac zapravo dobija signal da se od njega očekuje poverenje umesto dokaza.

Najviše se gubi kod menjača, kvačila, trapa i kočnica. Automatski menjač može delovati dobro dok auto stoji, a trzati tek u vožnji. Kvačilo može uhvatiti visoko, turbina može kasniti, trap može lupati preko sitnih neravnina, a kočnice mogu vući u stranu. Sve su to stvari koje fotografije i kratko paljenje u mestu ne rešavaju.

Probna vožnja otkriva i odnos prodavca prema kupcu. Prodavac koji želi transparentnu prodaju uglavnom nema problem da se auto proveri na razuman način. Prodavac koji stalno žuri, skraćuje rutu, izbegava hladan start ili odbija servis često pokušava da kontroliše situaciju u kojoj bi se problem čuo, osetio ili video.

Ne mora svako odbijanje probne vožnje automatski značiti prevaru, ali kupac ne treba da plaća punu cenu za nepotpunu proveru. Ako nema vožnje, nema ni iste računice rizika. Najpametnije je odustati kada prodavac ne nudi nijedan konkretan način da se stanje potvrdi. Polovan auto se ne kupuje na obećanje da je dobar, nego na proveru koja to može da izdrži.
TEXT,
                'highlights' => [
                    'Probna vožnja je osnovna provera menjača, kvačila, trapa i kočnica.',
                    'Ako auto ne može da se vozi, prodavac treba da ponudi razumnu alternativu za servisnu proveru.',
                    'Odbijanje vožnje bez jasnog razloga menja cenu rizika i često je dovoljan razlog za odustajanje.',
                ],
                'tags' => ['probna vožnja', 'provera vozila', 'kupovina polovnjaka', 'rizik'],
                'meta_title' => 'Kupovina auta bez probne vožnje: kada odustati',
                'meta_description' => 'Zašto je probna vožnja važna kod polovnog auta: menjač, kvačilo, trap, kočnice, hladan start, servisna provera i signali za odustajanje.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(15),
                'palette' => ['#111827', '#f43f5e', '#f8fafc'],
            ],
            [
                'title' => 'Euro 6 dizel iz uvoza: kada niska potrošnja ne opravdava emisijski rizik',
                'slug' => 'euro-6-dizel-iz-uvoza-kada-niska-potrosnja-ne-opravdava-emisijski-rizik',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Euro 6 dizeli mogu biti odlični za otvoren put, ali kod uvezenog polovnjaka kupac mora proveriti DPF, EGR, AdBlue i trag stvarne kilometraže.',
                'content' => <<<'TEXT'
Euro 6 dizel često zvuči kao idealna kombinacija za kupca polovnog automobila: moderna generacija motora, niska potrošnja, bolja ekologija i dovoljno snage za put. Kod uvezenih automobila ta priča može biti tačna, ali samo ako je primerak korišćen u uslovima za koje je takav dizel napravljen. Ako nije, niska potrošnja brzo postaje slab argument pred troškovima emisijskih sistema.

Najvažnije je razumeti da Euro 6 dizel nije jednostavan dizel iz stare škole. DPF, EGR, AdBlue, senzori izduva i softver rade zajedno. Kada je auto redovno vozio duže relacije, imao kvalitetno gorivo i servisiran je na vreme, sistem može funkcionisati vrlo dobro. Kada je auto živeo na kratkim relacijama, hladnim startovima i odlaganim servisima, problemi se često skupljaju tiho dok ne stignu do sledećeg vlasnika.

Kod uvoza posebno treba proveriti logiku kilometraže. Euro 6 dizeli iz zapadne Evrope često su kupovani za autoput i velike godišnje kilometraže. To nije automatski loše, ali kilometraža mora biti iskrena. Bolje je kupiti auto sa većom, dokazivom kilometražom i urednim servisima nego primerak koji izgleda "malo vožen", a nema dovoljno dokaza da tu priču podrži.

Treba izbegavati automobile sa nejasnim softverskim intervencijama. Ako je DPF uklonjen, AdBlue ugašen ili EGR rešen samo kroz mapu, kupac preuzima tehnički, pravni i kasniji prodajni rizik. Takav auto može delovati jeftinije u trenutku kupovine, ali često samo znači da je neko pre prodaje rešio simptom, a ne stvarno stanje vozila.

Euro 6 dizel ima smisla za kupca koji prelazi dovoljno otvorenog puta i želi štedljiv automobil za duže relacije. Nema mnogo smisla za vozača koji uglavnom vozi grad, kratke ture i vikend kilometražu. Pravo pitanje nije da li je Euro 6 bolji ili lošiji, nego da li konkretan primerak i tvoja upotreba mogu da održe sistem zdravim bez stalnih kompromisa.
TEXT,
                'highlights' => [
                    'Euro 6 dizel treba proveriti kao sistem, zajedno sa DPF-om, EGR-om, AdBlue-om i senzorima izduva.',
                    'Kod uvoza je iskrena veća kilometraža često bolja od niske brojke bez dokaza.',
                    'Softverski ugašeni emisijski sistemi prebacuju rizik na kupca i kasniju prodaju.',
                ],
                'tags' => ['Euro 6 dizel', 'uvoz', 'DPF', 'AdBlue'],
                'meta_title' => 'Euro 6 dizel iz uvoza: šta proveriti pre kupovine',
                'meta_description' => 'Analiza polovnog Euro 6 dizela iz uvoza: DPF, EGR, AdBlue, kilometraža, softverske intervencije, gradska vožnja i realan rizik.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(10),
                'palette' => ['#172033', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Hibrid sa velikom kilometražom: kada baterija nije jedini rizik',
                'slug' => 'hibrid-sa-velikom-kilometrazom-kada-baterija-nije-jedini-rizik',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kupci polovnih hibrida često gledaju samo bateriju, ali kod većih kilometraža jednako su važni trap, kočnice, enterijer, taksi istorija i servisni ritam.',
                'content' => <<<'TEXT'
Polovan hibrid sa velikom kilometražom može biti vrlo dobra kupovina ako je kilometraža nastala u pravim uslovima i ako je održavanje jasno. Hibridni pogon često bolje podnosi grad od dizela, troši malo i deluje mirno čak i kada je auto prešao mnogo. Upravo ta mirnoća ponekad zavara kupca da gleda samo bateriju, a preskoči ostatak automobila.

Baterija jeste važna, ali nije jedini trošak. Potrebna je dijagnostika, provera ponašanja u vožnji, ventilacije baterije i načina punjenja i pražnjenja. Ipak, ako je auto dugo korišćen u taksiju, dostavi, flotnoj vožnji ili svakodnevnim kratkim turama, trap, sedišta, brave, prekidači, klima i kočnice mogu biti jednako važni za računicu.

Hibridi često imaju regenerativno kočenje, pa klasične kočnice nekad izgledaju manje potrošeno nego kod običnog auta. To ne znači da ih treba preskočiti. Diskovi mogu korodirati, klizači mogu zapeknuti, a nepravilno korišćenje može napraviti problem koji se vidi tek na pregledu ili tehničkom. Mirna vožnja ne znači automatski miran servis.

Enterijer je posebno dobar indikator. Hibrid sa velikom kilometražom može i dalje raditi tiho, ali volan, sedište, pedale, vrata i prekidači teško kriju intenzivnu upotrebu. Ako prodavac priča o porodičnom autu, a kabina izgleda kao radno mesto, treba proveriti celu istoriju mnogo pažljivije.

Hibrid sa velikom kilometražom nije za automatsko odbacivanje. Često je bolja kupovina od dizela koji je mučen kratkim relacijama. Ali treba ga gledati kao ceo automobil: baterija, dijagnostika, trap, kočnice, enterijer i servisna logika. Kada se sve to uklopi, velika kilometraža može biti prihvatljiva. Kada se ne uklopi, tiha vožnja samo odlaže skupo razočaranje.
TEXT,
                'highlights' => [
                    'Kod polovnog hibrida sa velikom kilometražom baterija je važna, ali nije jedini rizik.',
                    'Trap, kočnice, klima, enterijer i trag taksi ili flotne upotrebe menjaju računicu.',
                    'Mirna i tiha vožnja hibrida ne sme da zameni detaljan pregled celog automobila.',
                ],
                'tags' => ['hibrid', 'velika kilometraža', 'baterija', 'troškovi održavanja'],
                'meta_title' => 'Hibrid sa velikom kilometražom: šta proveriti',
                'meta_description' => 'Kako proceniti polovan hibrid sa velikom kilometražom: baterija, dijagnostika, trap, kočnice, enterijer, taksi istorija i servisni rizik.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(5),
                'palette' => ['#1f2937', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mini Countryman: šarmantan crossover koji mora opravdati premium cenu',
                'slug' => 'polovni-mini-countryman-sarmantan-crossover-koji-mora-opravdati-premium-cenu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Countryman nudi poseban stil i više praktičnosti od klasičnog Minija, ali kao polovnjak traži hladnu proveru motora, menjača, elektronike i stvarne vrednosti.',
                'content' => <<<'TEXT'
Mini Countryman je automobil koji se retko kupuje samo razumom. Kupce privlače dizajn, osećaj posebnosti, kompaktne dimenzije i ideja da se dobija crossover koji nije još jedan generički SUV. To može biti sasvim legitiman razlog za kupovinu, ali kod polovnog Countrymana emocija mora da stane pred pregled. Premium cena nije problem ako je primerak zaista premium očuvan.

Prva provera treba da bude mehanika. Zavisno od generacije i motora, treba gledati servisni ritam, potrošnju ulja, stanje turbine kod turbo verzija, rad automatskog menjača i tragove skupljih intervencija. Countryman često voze ljudi koji žele zabavan gradski auto, pa kvačilo, menjač, trap i gume mogu pokazati više stvarnog života nego što kilometraža kaže.

Druga tema je elektronika i oprema. Mini enterijer deluje posebno, ali svi prekidači, ekrani, klima, senzori, panoramski krov ako postoji i komforna oprema moraju raditi bez sitnih izgovora. Kod auta koji se kupuje zbog karaktera, kupac lako oprosti sitnice jer mu se auto sviđa. Upravo te sitnice posle postanu najdosadniji deo vlasništva.

Treba proveriti i praktičnost. Countryman jeste upotrebljiviji od manjeg Minija, ali nije automatski zamena za veći porodični SUV. Zadnja klupa, gepek, dečje sedište i udobnost na dužem putu treba da odgovaraju stvarnom načinu života. Ako kupac plaća premium cenu za stil, mora znati da prostor nije glavna vrednost ovog automobila.

Polovni Mini Countryman ima smisla kada kupac želi specifičan automobil i nađe primerak sa jasnom istorijom, zdravom mehanikom i realnom cenom. Nema smisla kada se plaća samo šarm, a pregled otkriva odložene troškove. Kod ovakvog auta najbolja kupovina je ona gde emocija ostane, ali je stanje vozila ne demantuje.
TEXT,
                'highlights' => [
                    'Countryman treba kupovati zbog karaktera, ali tek posle hladne provere mehanike.',
                    'Elektronika, panoramski krov, menjač i trap mogu brzo pokvariti premium utisak.',
                    'Praktičnost treba proveriti realno jer Countryman nije zamena za veći porodični SUV.',
                ],
                'tags' => ['Mini Countryman', 'crossover', 'premium kompakt', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mini Countryman: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Mini Countryman modela: motor, menjač, elektronika, oprema, praktičnost, premium cena i realan rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Suzuki Vitara: mali SUV koji ne treba kupiti samo zbog reputacije',
                'slug' => 'polovni-suzuki-vitara-mali-suv-koji-ne-treba-kupiti-samo-zbog-reputacije',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Vitara često deluje kao jednostavan i siguran mali SUV, ali konkretan polovan primerak treba proveriti kroz pogon, trap, koroziju, servis i realnu gradsku upotrebu.',
                'content' => <<<'TEXT'
Suzuki Vitara je jedan od onih polovnjaka koji kupci često stave u uži izbor kada žele mali SUV bez premium troškova, sa dobrom preglednošću i reputacijom jednostavnijeg održavanja. To je dobra polazna tačka, ali nije dovoljno za kupovinu. Kod polovnog automobila reputacija marke pomaže tek kada konkretan primerak potvrdi da je zaista održavan kako treba.

Prva provera treba da bude servisna istorija i način vožnje. Vitara često živi mešovit život: grad, kraći put, vikend ture, lošiji asfalt i povremeno sneg ili makadam. Ako auto ima pogon na sve točkove, treba proveriti da li sistem radi pravilno, da li su gume ujednačene i da li postoji trag redovnog održavanja. Pogon je prednost samo ako nije zapušten.

Druga tema je trap. Mali SUV može delovati robusno, ali ivičnjaci, rupe i loš put brzo ostave trag na gumama, ležajevima, sponama i amortizerima. Na probnoj vožnji Vitara treba da bude mirna, bez lupkanja i plivanja preko neravnina. Ako prodavac govori da je sve normalno za SUV, to nije dovoljan odgovor bez pregleda.

Kod starijih primeraka treba gledati i koroziju, posebno ispod vozila, na pragovima, spojevima i mestima gde se skuplja prljavština. Automobil koji izgleda uredno spolja može imati zapušten donji deo ako je godinama vožen zimi, po soli ili van boljeg asfalta. To nije uvek presudno, ali mora biti uračunato u cenu.

Polovna Vitara ima smisla za kupca koji želi praktičan, pregledan i relativno jednostavan mali SUV. Ipak, najbolji izbor nije najlepša fotografija ni najniža kilometraža, nego primerak gde servis, trap, pogon i stanje karoserije pričaju istu priču. Ako se to poklopi, Vitara može biti vrlo razumna kupovina. Ako se ne poklopi, reputacija ne plaća račun.
TEXT,
                'highlights' => [
                    'Vitara ima smisla kada servisna istorija potvrđuje reputaciju jednostavnog malog SUV-a.',
                    'Kod 4x4 verzija proveri pogon, gume i trag održavanja, ne samo oznaku u oglasu.',
                    'Trap i korozija često otkrivaju stvarnu gradsku i zimsku eksploataciju.',
                ],
                'tags' => ['Suzuki Vitara', 'mali SUV', '4x4', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Suzuki Vitara: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Suzuki Vitara modela: servisna istorija, 4x4 pogon, trap, korozija, gume i realna vrednost malog SUV-a.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(6),
                'palette' => ['#102033', '#06b6d4', '#f8fafc'],
            ],
            [
                'title' => 'Auto kupljen na aukciji: kada niža cena nosi skuplji rizik',
                'slug' => 'auto-kupljen-na-aukciji-kada-niza-cena-nosi-skuplji-rizik',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Aukcijski automobili mogu biti dobra prilika, ali kupac mora znati zašto je vozilo završilo tamo, šta piše u izveštaju i koliko košta rizik bez klasične istorije.',
                'content' => <<<'TEXT'
Automobil kupljen na aukciji često zvuči kao odlična prilika: niža ulazna cena, brz promet i mogućnost da se dođe do modela koji bi u redovnom oglasu koštao više. Problem je što aukcijska cena retko govori celu priču. Kupac ne plaća samo automobil, već i nivo nepoznanica koji dolazi sa načinom prodaje.

Prvo pitanje je zašto je auto završio na aukciji. Nekada je to kraj lizinga, flotna prodaja ili standardna zamena vozila. Nekada je to osiguravajuća šteta, povrat posle finansijskog problema, auto sa nejasnom servisnom istorijom ili vozilo koje se prodaje brzo jer ga redovno tržište teže prihvata. Svaka od tih situacija nosi drugačiji rizik.

Drugo, aukcijski izveštaj treba čitati hladno. Fotografije oštećenja, opis stanja, kilometraža, status dokumentacije, oznake o paljenju motora i mogućnosti probne vožnje moraju se uklopiti u cenu. Ako nešto nije jasno, ne treba pretpostavljati najbolji scenario. Kod polovnih automobila nepoznato obično košta.

Treće, treba uračunati sve dodatne troškove: transport, dažbine, popravke, homologaciju, registraciju, prevod dokumentacije i vreme koje auto provede van upotrebe. Auto koji je jeftin na aukciji može posle svih stavki biti skuplji od urednog primerka iz oglasa, posebno ako se naknadno pojavi skrivena šteta.

Aukcija nije automatski loš izvor automobila, ali nije teren za kupca koji želi jednostavnu i mirnu kupovinu. Ima smisla kada postoji jasan izveštaj, realna cena i neko ko zna da proceni rizik pre uplate. Ako kupac samo vidi nižu cenu i zanemari kontekst, aukcijska ušteda se lako pretvori u skuplju lekciju.
TEXT,
                'highlights' => [
                    'Aukcijska cena ima smisla samo kada znaš zašto je auto završio na aukciji.',
                    'Izveštaj, fotografije, dokumentacija i status vozila moraju se proveriti pre uplate.',
                    'Transport, dažbine i popravke često izbrišu prividnu uštedu.',
                ],
                'tags' => ['aukcija', 'uvoz automobila', 'provera vozila', 'skrivena šteta'],
                'meta_title' => 'Auto kupljen na aukciji: kako proceniti rizik',
                'meta_description' => 'Kako proceniti polovan auto kupljen na aukciji: izveštaj, oštećenja, dokumentacija, transport, dodatni troškovi i realna vrednost.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(3),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volvo V60: karavan za porodicu koji traži proveru automatika i trapa',
                'slug' => 'polovni-volvo-v60-karavan-za-porodicu-koji-trazi-proveru-automatika-i-trapa',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'V60 deluje kao miran porodični karavan sa jakim bezbednosnim imidžom, ali polovan primerak mora dokazati da automatik, trap i servis prate tu priču.',
                'content' => <<<'TEXT'
Volvo V60 privlači kupce koji žele porodičan automobil, ali ne žele još jedan SUV. Kao karavan nudi dobar odnos prostora, stabilnosti i sigurnog imidža, a pritom često deluje diskretnije i zrelije od nemačkih premium alternativa. Ipak, kod polovnog V60 nije dovoljno da auto izgleda uredno i nosi Volvo reputaciju. Treba proveriti da li konkretan primerak zaista prati tu mirnu priču.

Prva važna tačka je automatski menjač. V60 se često kupuje za duži put, porodicu i udobnost, pa automatik ima veliki smisao, ali samo ako radi glatko i ima servisni trag. Zadrška pri ubacivanju u brzinu, trzaji pri hladnom radu ili nejasna istorija zamene ulja menjaju računicu. Kod polovnog karavana menjač nije detalj, nego jedna od najskupljih stavki rizika.

Druga tema je trap. Karavan često nosi porodicu, prtljag, duge relacije i loš asfalt, pa amortizeri, spone, ležajevi i gume brzo pokažu koliko je auto stvarno korišćen. Na probnoj vožnji V60 treba da bude stabilan, tih i predvidiv. Ako deluje umorno, ne treba ga spašavati reputacijom marke.

Treća provera je enterijer i oprema. Volvo kabina dobro stari kada je auto održavan, ali vozačko sedište, volan, prekidači, klima, senzori i multimedija mogu otkriti visoku kilometražu ili službenu upotrebu. Bezbednosni sistemi su prednost samo ako rade uredno i bez grešaka.

Polovni Volvo V60 ima mnogo smisla za kupca koji želi praktičan, bezbedan i smiren automobil za porodicu i put. Ali najbolji primerak je onaj sa jasnim servisima, zdravim automatikom i trapom koji ne traži objašnjenja. Ako se to poklopi, V60 može biti pametnija kupovina od popularnijeg SUV-a. Ako ne, karavan brzo postaje premium račun u diskretnoj ambalaži.
TEXT,
                'highlights' => [
                    'V60 je dobar porodični karavan kada automatik ima jasan servisni trag.',
                    'Trap i gume otkrivaju koliko je auto nosio put, porodicu i prtljag.',
                    'Volvo reputacija ne zamenjuje dijagnostiku, probnu vožnju i proveru opreme.',
                ],
                'tags' => ['Volvo V60', 'karavan', 'automatik', 'porodični auto'],
                'meta_title' => 'Polovni Volvo V60: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Volvo V60 karavana: automatik, trap, enterijer, bezbednosni sistemi, servisna istorija i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(4),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Hyundai Kona ili Kia Niro: mali hibridni crossover kada grad odlučuje',
                'slug' => 'hyundai-kona-ili-kia-niro-mali-hibridni-crossover-kada-grad-odlucuje',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kona i Niro često privlače kupce koji žele štedljiv crossover za grad, ali prava odluka zavisi od baterije, prostora, opreme i načina prethodne vožnje.',
                'content' => <<<'TEXT'
Hyundai Kona i Kia Niro ulaze u isti uži izbor kada kupac želi crossover, hibridnu ili elektrifikovanu vožnju i manji rizik od starijeg dizela. Oba modela mogu biti vrlo dobra svakodnevna kupovina, ali ne rešavaju isti problem. Kona je kompaktnija, lakša za grad i parkiranje, dok Niro često bolje odgovara porodici koja želi više prostora i mirniji karakter.

Kod Kone prvo treba proveriti da li kupuješ verziju koja stvarno odgovara tvojoj vožnji. Benzinac, hibrid i električna verzija ne nose iste troškove, isti domet ni isti rizik. Gradski primerci mogu izgledati uredno, ali kočnice, trap, gume, parking oštećenja i stanje enterijera brzo otkrivaju koliko je auto živeo u gužvi.

Kia Niro je racionalniji kada su prostor, potrošnja i porodična upotreba važniji od okretnosti. Kod polovnog Nira treba proveriti bateriju, servisni trag, klimu, elektroniku i stanje zadnje klupe i gepeka. Ako je auto korišćen kao intenzivno gradsko ili službeno vozilo, mala potrošnja ne sme da sakrije istrošenost.

Najbolja kupovina nije model sa najlepšom opremom, nego primerak kod kog se hibridni sistem, servisna istorija i stanje kabine uklapaju. Kona ima više smisla za vozača koji želi kompaktniji crossover za grad. Niro ima prednost kada želiš mirniji porodični paket i više prostora. U oba slučaja dijagnostika hibridnog sistema i probna vožnja vrede više od prosečne potrošnje iz oglasa.
TEXT,
                'highlights' => [
                    'Kona je okretniji gradski izbor, dok Niro bolje pokriva porodičnu upotrebu.',
                    'Kod oba modela proveri hibridni sistem, kočnice, klimu, trap i servisnu istoriju.',
                    'Mala potrošnja nema vrednost ako konkretan primerak krije intenzivan gradski život.',
                ],
                'tags' => ['Hyundai Kona', 'Kia Niro', 'hibridni crossover', 'grad'],
                'meta_title' => 'Hyundai Kona ili Kia Niro: polovni hibridni crossover',
                'meta_description' => 'Poređenje polovnih Hyundai Kona i Kia Niro modela: hibridni sistem, gradska vožnja, prostor, baterija, servis i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Ford Mondeo: velika limuzina koja mora opravdati dizel i trap',
                'slug' => 'polovni-ford-mondeo-velika-limuzina-koja-mora-opravdati-dizel-i-trap',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mondeo nudi mnogo auta za novac, ali kod polovnog primerka servis dizela, stanje trapa, automatik i karoserija odlučuju da li je cena stvarno dobra.',
                'content' => <<<'TEXT'
Ford Mondeo je polovnjak koji često izgleda kao odlična vrednost: mnogo prostora, udobna vožnja, ozbiljan gepek i cena koja je često niža od nemačkih alternativa. Upravo zato ga treba gledati hladno. Velika limuzina ili karavan može biti pametna kupovina, ali samo ako konkretan primerak ne traži ulaganja koja će pojesti celu početnu prednost.

Najveća tema kod Mondeo dizela je servisni trag. Motor, turbina, DPF, EGR i dizne moraju imati logiku u odnosu na kilometražu i način vožnje. Ako je automobil godinama vozio kratke relacije, ušteda na potrošnji brzo gubi smisao. Hladan start, dim, neravnomeran rad i dijagnostika ne smeju se preskočiti.

Druga važna provera je trap. Mondeo je udoban, ali težina, loš asfalt i veće felne mogu napraviti troškove na amortizerima, sponama, ležajevima i gumama. Na probnoj vožnji auto treba da bude tih, stabilan i precizan. Lupkanje preko neravnina nije samo sitnica ako se uzme u obzir veličina i cena delova.

Automatski menjač, klima, elektronika i stanje enterijera dopunjuju sliku. Ako kabina deluje umornije od kilometraže, ako menjač kasni ili ako prodavac nema račune, cenu treba spustiti ili odustati. Polovni Mondeo ima smisla za kupca koji želi prostran auto za put, ali samo kada dizel, trap i dokumentacija potvrde da niska cena nije mamac.
TEXT,
                'highlights' => [
                    'Mondeo daje mnogo prostora za novac, ali samo ako dizel ima uredan servisni trag.',
                    'Trap, gume i amortizeri brzo pokažu stvarno stanje velike limuzine ili karavana.',
                    'Automatik i elektroniku treba proveriti pre pregovora, ne posle kapare.',
                ],
                'tags' => ['Ford Mondeo', 'velika limuzina', 'dizel', 'trap'],
                'meta_title' => 'Polovni Ford Mondeo: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Ford Mondeo modela: dizel, DPF, EGR, trap, automatik, karoserija, servisna istorija i realna vrednost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Panoramski krov na polovnom autu: lep detalj koji može skupo da prokišnjava',
                'slug' => 'panoramski-krov-na-polovnom-autu-lep-detalj-koji-moze-skupo-da-prokisnjava',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Panorama podiže utisak u kabini, ali kod polovnog auta treba proveriti odvode, dihtunge, mehanizam, vlagu i tragove ranijih popravki.',
                'content' => <<<'TEXT'
Panoramski krov je jedan od detalja koji automobil na oglasu odmah učini privlačnijim. Kabina deluje svetlije, oprema bogatije, a fotografije bolje. Problem je što panorama kod polovnog auta nije samo lep dodatak. Ona je i sistem sa staklom, dihtunzima, odvodima, motorima, roletnom i mogućim prodorom vode.

Prva provera je vlaga u kabini. Podigni patosnice, pogledaj nebo krova, stubove, gepek i prostor oko pojaseva. Miris vlage, fleke, zamagljivanje i tragovi čišćenja mogu značiti da je voda već ulazila. Prodavac često kaže da je to kondenzacija, ali kod panorame treba tražiti dokaz, ne objašnjenje.

Druga provera su odvodi i mehanizam. Krov treba da se otvara i zatvara bez trzaja, krckanja i sporog rada. Roletna ne sme zapinjati, a dihtunzi ne smeju biti ispucali ili puni nečistoće. Začepljeni odvodi mogu napraviti štetu na elektronici, tepihu i oblogama, pa sitan problem brzo postane skup.

Panorama nije razlog da se automatski odustane od auta, ali jeste razlog za strožu proveru. Ako je auto garažiran, održavan i bez tragova vlage, taj dodatak može biti prijatan. Ako postoji bilo kakav trag curenja ili loše popravke, pregovaranje mora uključiti realan trošak. Lep pogled kroz krov ne treba da sakrije vlagu ispod sedišta.
TEXT,
                'highlights' => [
                    'Panoramski krov proverava se kroz vlagu, odvode, dihtunge, roletnu i mehanizam.',
                    'Fleke, miris vlage i zamagljivanje mogu značiti skuplji problem od lepog detalja u oglasu.',
                    'Ako postoji trag curenja, cenu treba pregovarati tek posle stručne provere.',
                ],
                'tags' => ['panoramski krov', 'vlaga u autu', 'troškovi održavanja', 'polovni auto'],
                'meta_title' => 'Panoramski krov na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti panoramski krov na polovnom automobilu: odvodi, dihtunzi, roletna, vlaga, elektronika, tragovi curenja i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Tek uvezen auto iz Švajcarske: kada dobra oprema ne garantuje laku kupovinu',
                'slug' => 'tek-uvezen-auto-iz-svajcarske-kada-dobra-oprema-ne-garantuje-laku-kupovinu',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Automobili iz Švajcarske često imaju bogatu opremu i uredan izgled, ali kupac mora proveriti dokumentaciju, koroziju, servis i cenu delova.',
                'content' => <<<'TEXT'
Tek uvezen auto iz Švajcarske često zvuči bolje od prosečnog oglasa. Fotografije su uredne, oprema je bogata, enterijer deluje očuvano, a priča prodavca obično naglašava precizno održavanje. Sve to može biti tačno, ali ne znači da je kupovina automatski laka. Švajcarsko poreklo je samo početna informacija, ne dokaz dobrog stanja.

Prvo treba proveriti dokumentaciju. Servisna istorija, računi, odjava, carinski papiri, homologacija i podaci iz dostupnih izveštaja moraju se slagati. Ako postoji rupa u istoriji, ne treba je popunjavati pretpostavkom da je auto sigurno održavan. Kod uvoza je najvažnije da se vidi kontinuitet.

Druga tema je stanje ispod automobila. Zimski uslovi, so, planinski putevi i vlaga mogu ostaviti trag na podvozju, kočionim cevima, nosačima i spojevima. Spolja auto može izgledati odlično, ali dizalica i pregled odozdo često daju realniju sliku od fotografija na parkingu.

Treće, bogata oprema znači i više mogućih troškova. Adaptivni amortizeri, automatik, panorama, veliki točkovi, LED farovi, senzori i premium sedišta podižu vrednost samo ako rade pravilno. Auto iz Švajcarske može biti odlična kupovina, ali tek kada dokumenti, podvozje, servis i oprema prođu proveru bez previše objašnjenja.
TEXT,
                'highlights' => [
                    'Švajcarsko poreklo nije dokaz stanja bez jasne dokumentacije i servisnog kontinuiteta.',
                    'Pregled podvozja je obavezan zbog soli, vlage i planinskih uslova.',
                    'Bogata oprema ima vrednost samo ako skupi sistemi rade bez grešaka.',
                ],
                'tags' => ['uvoz iz Švajcarske', 'uvoz automobila', 'servisna istorija', 'oprema'],
                'meta_title' => 'Tek uvezen auto iz Švajcarske: šta proveriti',
                'meta_description' => 'Vodič za kupovinu tek uvezenog auta iz Švajcarske: dokumentacija, podvozje, korozija, servis, oprema, delovi i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Polovni auto za dostavu: kako prepoznati težak gradski život pre kupovine',
                'slug' => 'polovni-auto-za-dostavu-kako-prepoznati-tezak-gradski-zivot-pre-kupovine',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Automobil koji je radio dostavu može imati malo kilometara na papiru, ali mnogo startovanja, kočenja, ivičnjaka i habanja koje oglas ne pokazuje.',
                'content' => <<<'TEXT'
Polovni auto koji je radio dostavu često ne izgleda dramatično na prvi pogled. Može imati korektnu kilometražu, pristojne fotografije i jednostavnu opremu. Ipak, gradska dostava troši automobil drugačije od porodične vožnje. Mnogo hladnih startova, kratkih relacija, parkiranja, kočenja i penjanja na ivičnjake može napraviti stanje koje broj kilometara ne objašnjava.

Prvi trag je enterijer. Vozačko sedište, volan, pedale, prekidači, ručica menjača, vrata i prtljažnik često pokazuju intenzivnu upotrebu. Ako su ti delovi potrošeniji od kilometraže, treba postaviti više pitanja. Dostavni auto retko strada od jednog velikog događaja, već od hiljada malih ciklusa.

Drugi trag je mehanika. Kvačilo, kočnice, trap, ležajevi, gume, hladan rad motora i klima treba da se provere pažljivije nego kod običnog gradskog auta. Ako je auto često stajao upaljen ili vozio kratke ture, ulje, akumulator, alternator i DPF kod dizela mogu biti pod većim stresom.

Treći signal je karoserija. Sitne ogrebotine, udarci vrata, tragovi skidanja folije, oštećenja branika i unutrašnjost gepeka mogu otkriti prethodnu namenu. Takav auto nije automatski loša kupovina ako je cena realna i stanje jasno. Problem nastaje kada se prodaje kao običan porodični primerak bez priznate istorije rada.
TEXT,
                'highlights' => [
                    'Dostavni auto može imati realnu kilometražu, ali mnogo težu gradsku eksploataciju.',
                    'Enterijer, gepek, kvačilo, kočnice i trap često otkrivaju prethodnu namenu.',
                    'Kupovina ima smisla samo ako cena priznaje intenzivnu upotrebu i ulaganja.',
                ],
                'tags' => ['auto za dostavu', 'gradska vožnja', 'provera polovnjaka', 'intenzivna eksploatacija'],
                'meta_title' => 'Polovni auto za dostavu: kako prepoznati rizik',
                'meta_description' => 'Kako prepoznati polovan auto koji je radio dostavu: enterijer, gepek, kvačilo, kočnice, trap, hladni startovi i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#eab308', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Nissan X-Trail: porodični SUV koji traži proveru CVT-a i pogona',
                'slug' => 'polovni-nissan-x-trail-porodicni-suv-koji-trazi-proveru-cvt-a-i-pogona',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'X-Trail može biti praktičan porodični SUV, ali dobar primerak mora dokazati stanje CVT menjača, 4x4 sistema, trapa i enterijera.',
                'content' => <<<'TEXT'
Nissan X-Trail često privlači kupce koji žele više prostora od Qashqaija, povišenu poziciju sedenja i porodičan osećaj bez premium cene. Na fotografijama deluje kao logičan izbor za porodicu, putovanja i lošiji asfalt. Ipak, kod polovnog X-Traila nije dovoljno da auto bude velik i lepo opremljen. Najvažnije je da se pogon, menjač i trap uklapaju sa pričom o održavanju.

Prva provera je CVT menjač kod verzija koje ga imaju. Treba obratiti pažnju na zadršku pri kretanju, zavijanje, trzaje, proklizavanje i ponašanje kada se menjač zagreje. Servis ulja mora biti dokaziv, jer priča da se ulje ne menja nije dobar argument kod polovnog automobila koji treba da nosi porodicu i prtljag.

Druga tema je pogon na sve točkove. X-Trail sa 4x4 sistemom ima smisla ako je sistem ispravan, gume ujednačene i nema lupanja ili vibracija pod opterećenjem. Ako je auto često vožen po lošem putu, planini ili snegu, prednost pogona može doći sa troškom trapa, kočnica i amortizera.

Treća provera je enterijer. Porodični SUV često nosi dečja sedišta, opremu, kofere i svakodnevnu gužvu. Zadnja klupa, gepek, obloge, klima i elektronika treba da potvrde da je automobil korišćen normalno. Polovni X-Trail ima smisla kada prostor dolazi sa urednim servisima, mirnim menjačem i trapom koji ne traži objašnjenja.
TEXT,
                'highlights' => [
                    'Kod X-Traila CVT menjač mora imati miran rad i dokaziv servis ulja.',
                    '4x4 pogon je prednost samo ako su gume, trap i sistem provereni bez vibracija.',
                    'Porodična upotreba se vidi kroz enterijer, gepek, klimu i stanje zadnje klupe.',
                ],
                'tags' => ['Nissan X-Trail', 'porodični SUV', 'CVT', '4x4'],
                'meta_title' => 'Polovni Nissan X-Trail: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Nissan X-Trail modela: CVT menjač, 4x4 pogon, trap, enterijer, servisna istorija i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Honda Accord ili Toyota Avensis: velika limuzina kada racionalnost vredi više od značke',
                'slug' => 'honda-accord-ili-toyota-avensis-velika-limuzina-kada-racionalnost-vredi-vise-od-znacke',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Accord i Avensis mogu biti mirnije alternative skupljim premium limuzinama, ali samo kada stanje, servis i korozija opravdaju reputaciju.',
                'content' => <<<'TEXT'
Honda Accord i Toyota Avensis često zanimaju kupce koji žele veliku limuzinu ili karavan, ali ne žele premium troškove nemačkih modela. Oba automobila imaju reputaciju racionalne kupovine, dobrih benzinaca i solidne dugotrajnosti. To je dobra osnova, ali kod starijih polovnjaka reputacija ne sme zameniti pregled.

Accord ima prednost za vozača koji želi bolji osećaj za volanom, preciznije upravljanje i malo više karaktera. Benzinski motori su često traženi, ali treba proveriti servisni ritam, potrošnju ulja, trap, kočnice i stanje limarije. Dizel može biti štedljiv, ali traži ozbiljniju proveru turbine, DPF-a, kvačila i hladnog starta.

Avensis je mirniji izbor za kupca kome su udobnost, jednostavnost i porodična upotreba važniji od dinamike. Kod njega proveri servisnu istoriju, stanje enterijera, koroziju ispod vozila i da li cena odgovara godinama i kilometraži. Toyota znak pomaže pri kasnijoj prodaji, ali ne popravlja zapušten primerak.

Najbolja odluka zavisi od konkretnog auta. Accord ima smisla kada želiš više vozačkog osećaja i nalaziš uredan benzinac. Avensis ima prednost kada želiš mirniji porodični auto sa jasnom istorijom. Ako jedan ima račune, zdrav trap i dobru limariju, a drugi samo poznatu reputaciju, izbor je već napravljen.
TEXT,
                'highlights' => [
                    'Accord je zanimljiviji za vožnju, Avensis mirniji za porodičnu rutinu.',
                    'Kod oba modela korozija, trap i servisna istorija vrede više od reputacije.',
                    'Benzinci su često najpoželjniji, ali samo kada stanje prati cenu.',
                ],
                'tags' => ['Honda Accord', 'Toyota Avensis', 'velika limuzina', 'poređenje'],
                'meta_title' => 'Honda Accord ili Toyota Avensis: polovna limuzina',
                'meta_description' => 'Poređenje polovnih Honda Accord i Toyota Avensis modela: benzinci, dizel, korozija, trap, servisna istorija, udobnost i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa zamenjenim motorom: kada račun vredi više od priče prodavca',
                'slug' => 'auto-sa-zamenjenim-motorom-kada-racun-vredi-vise-od-price-prodavca',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Zamenjen motor ne mora automatski značiti lošu kupovinu, ali bez računa, dokumentacije, dijagnostike i jasnog razloga rizik brzo raste.',
                'content' => <<<'TEXT'
Oglas u kom prodavac kaže da je motor zamenjen može zvučati kao dobra vest: navodno je ugrađen bolji, mlađi ili manje pređen motor, pa kupac dobija automobil sa svežijom mehanikom. U praksi to može biti tačno, ali samo ako postoje dokazi. Bez računa, dokumentacije i jasnog razloga zamene, kupac preuzima tuđu nepoznanicu.

Prvo pitanje je zašto je motor zamenjen. Da li je prethodni stradao zbog lošeg održavanja, pregrevanja, pucanja kaiša, nedostatka ulja ili ozbiljne štete? Ako uzrok nije rešen, novi motor ne rešava ceo problem. Hladnjak, instalacija, turbina, menjač, nosači i elektronika mogu i dalje nositi posledice stare greške.

Druga provera je poreklo motora. Broj motora, račun, ugradnja u servisu, dijagnostika i usklađenost sa dokumentima moraju imati logiku. Motor iz nepoznatog izvora nije isto što i motor sa dokazivom kilometražom i garancijom servisa. Ako prodavac nema papir, priča nema veliku vrednost.

Treća provera je probna vožnja i pregled. Auto treba da pali hladan, radi mirno, ne dimi, ne curi, ne baca greške i ne pokazuje improvizacije oko instalacije. Kupovina auta sa zamenjenim motorom ima smisla samo kada je cena realna, rad dokumentovan i razlog zamene jasan. U suprotnom, jeftiniji primerak može postati najskuplji u oglasima.
TEXT,
                'highlights' => [
                    'Zamenjen motor je prihvatljiv samo uz račun, dokumentaciju i jasan razlog zamene.',
                    'Treba proveriti da li su rešeni uzroci kvara prethodnog motora.',
                    'Dijagnostika, hladan start i pregled instalacije su obavezni pre kapare.',
                ],
                'tags' => ['zamenjen motor', 'provera vozila', 'servisna dokumentacija', 'polovni auto'],
                'meta_title' => 'Auto sa zamenjenim motorom: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto sa zamenjenim motorom: računi, poreklo motora, dokumentacija, dijagnostika, hladan start i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni plug-in hibrid: kada punjenje kod kuće odlučuje celu računicu',
                'slug' => 'polovni-plug-in-hibrid-kada-punjenje-kod-kuce-odlucuje-celu-racunicu',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Plug-in hibrid može biti izuzetno štedljiv, ali samo ako se redovno puni i ako baterija, kablovi, servis i realan domet opravdavaju višu cenu.',
                'content' => <<<'TEXT'
Polovni plug-in hibrid često deluje kao idealan kompromis: vozi na struju po gradu, ima benzinski motor za duži put i na papiru troši vrlo malo. Problem je što PHEV računica zavisi od navike punjenja više nego od same oznake na gepeku. Bez redovnog punjenja, plug-in hibrid često postaje težak benzinac sa skupljom tehnologijom.

Prvo treba proceniti da li zaista imaš gde da puniš. Kućno punjenje ili siguran punjač na poslu menjaju celu sliku. Ako se auto oslanja samo na povremene javne punjače, prednost brzo nestaje. Kupac treba da računa svakodnevnu rutu, ne fabričku potrošnju iz kataloga.

Druga provera je baterija i punjenje. Kablovi, utičnica, softver, realan električni domet i dijagnostika hibridnog sistema moraju biti deo pregleda. Ako prodavac ne zna kada je auto poslednji put punjen ili nema originalne kablove, treba biti oprezan. PHEV koji nikada nije punjen nije korišćen kako je zamišljen.

Treća tema su troškovi. Plug-in hibrid ima motor, bateriju, elektroniku, kočnice, trap i često bogatu opremu. To može biti odlična kupovina kada se koristi pravilno, ali viša cena mora imati realno opravdanje. Najbolji PHEV je onaj koji tvoja rutina puni svakog dana, a ne onaj koji samo lepo izgleda u oglasu.
TEXT,
                'highlights' => [
                    'PHEV ima smisla samo ako ga stvarno puniš kod kuće ili na poslu.',
                    'Baterija, kablovi, softver i realan električni domet moraju se proveriti dijagnostikom.',
                    'Bez punjenja plug-in hibrid lako postaje teži i skuplji benzinac.',
                ],
                'tags' => ['plug-in hibrid', 'PHEV', 'punjenje kod kuće', 'troškovi'],
                'meta_title' => 'Polovni plug-in hibrid: kada se isplati',
                'meta_description' => 'Vodič za kupovinu polovnog plug-in hibrida: kućno punjenje, baterija, kablovi, realan električni domet, PHEV troškovi i servis.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#06b6d4', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Fiat Tipo: kompakt koji mora opravdati nisku cenu održavanja',
                'slug' => 'polovni-fiat-tipo-kompakt-koji-mora-opravdati-nisku-cenu-odrzavanja',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Tipo može biti racionalan porodični kompakt, ali kupac treba da proveri motor, trap, karoseriju, opremu i da li je niska cena zaista prednost.',
                'content' => <<<'TEXT'
Fiat Tipo je polovnjak koji često privlači kupce koji žele jednostavan kompakt, pristojan prostor i niže troškove od popularnijih nemačkih modela. Kao limuzina, hečbek ili karavan može biti vrlo racionalan, posebno za porodicu ili svakodnevnu vožnju. Ipak, niska cena nije dovoljna ako konkretan primerak odmah traži ulaganja.

Prva odluka je motor. Benzinci mogu biti dobar izbor za grad i manje kilometraže, dok dizel ima smisla za duže relacije samo ako ima urednu servisnu istoriju i zdrav DPF/EGR sistem. Tipo ne treba kupiti samo zato što deluje jednostavno. Hladan start, curenja, potrošnja ulja i servisni ritam i dalje moraju biti provereni.

Druga tema je trap i karoserija. Automobili koji su vozili službeno, dostavno ili mnogo po gradu često kriju tragove u gumama, amortizerima, sponama, kočnicama i sitnim oštećenjima. Unutrašnjost gepeka, pragovi, vrata i branici mogu otkriti upotrebu koju oglas ne opisuje.

Tipo ima smisla kada kupac želi razuman auto bez jurnjave za imidžom. Najbolji primerak nije najjeftiniji, nego onaj kod kog niska cena održavanja dolazi uz uredan servis, zdrav trap i opremu koja radi. Ako se to poklopi, Tipo može biti pametnija kupovina od skupljeg kompakta sa boljom značkom i većim rizikom.
TEXT,
                'highlights' => [
                    'Tipo je racionalan kompakt kada stanje potvrđuje priču o niskim troškovima.',
                    'Benzinac je često mirniji za grad, dizel samo uz jasnu istoriju i duže relacije.',
                    'Trap, gepek i karoserija otkrivaju službenu ili intenzivnu gradsku upotrebu.',
                ],
                'tags' => ['Fiat Tipo', 'kompakt', 'porodični auto', 'niski troškovi'],
                'meta_title' => 'Polovni Fiat Tipo: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Fiat Tipo modela: benzinac, dizel, trap, karoserija, servisna istorija, oprema i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Peugeot 308: kompakt koji traži proveru PureTech-a i dizela',
                'slug' => 'polovni-peugeot-308-kompakt-koji-trazi-proveru-puretech-a-i-dizela',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Peugeot 308 može biti atraktivan i udoban kompakt, ali kupac mora proveriti PureTech servis, dizel rizike, elektroniku i realno stanje enterijera.',
                'content' => <<<'TEXT'
Peugeot 308 često izgleda kao zanimljiva alternativa Golfu, Astri ili Meganu. Ima prijatan enterijer, dobru udobnost, moderan dizajn i često bogatu opremu za cenu. Upravo zato kupac mora odvojiti privlačan utisak od stvarnog stanja, jer kod polovnog 308 najskuplja greška često nije oprema, nego preskočena provera motora i elektronike.

Kod benzinskih 1.2 PureTech verzija najvažnije je proveriti servisni ritam, stanje kaiša gde je primenljivo, potrošnju ulja, hladan start i račune. Ako prodavac nema dokaz o održavanju, ne treba pretpostaviti da je motor samo zato što lepo radi u mestu bez rizika. Kratka probna vožnja nije dovoljna ako istorija nije jasna.

Dizel verzije mogu biti odlične za duže relacije, ali samo ako nisu ceo život provele u gradu. DPF, EGR, turbina, dizne i aditivni sistemi moraju biti deo pregleda. Dizel 308 ima smisla za vozača koji stvarno prelazi kilometre na otvorenom putu, a ne za kratke gradske relacije.

Treća tema je enterijer i elektronika. i-Cockpit raspored nekome odgovara, a nekome nikako, pa obavezno proveri položaj volana, preglednost, multimediju, klimu, senzore i greške na dijagnostici. Polovni Peugeot 308 je dobra kupovina kada stil i oprema dolaze uz proverljiv servis, a ne kada samo prikrivaju zapušten primerak.
TEXT,
                'highlights' => [
                    'Kod 1.2 PureTech motora servisni trag i stanje kaiša presudni su za rizik.',
                    'Dizel 308 ima smisla za duže relacije, ne za kratku gradsku rutinu.',
                    'Elektroniku, i-Cockpit ergonomiju i dijagnostiku treba proveriti pre kapare.',
                ],
                'tags' => ['Peugeot 308', 'PureTech', 'dizel', 'kompakt'],
                'meta_title' => 'Polovni Peugeot 308: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Peugeot 308 modela: PureTech motor, dizel, DPF, EGR, elektronika, i-Cockpit, servis i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Mercedes-Benz E klasa ili BMW Serija 5: premium limuzina kada kilometraža odlučuje',
                'slug' => 'mercedes-benz-e-klasa-ili-bmw-serija-5-premium-limuzina-kada-kilometraza-odlucuje',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'E klasa i Serija 5 mogu biti ozbiljni putni automobili, ali kod polovnjaka kilometraža, servis i automatik vrede više od prestiža.',
                'content' => <<<'TEXT'
Mercedes-Benz E klasa i BMW Serija 5 privlače kupce koji žele komfor, snagu, tišinu i osećaj ozbiljnog automobila. Kao novi automobili bili su skupi, a kao polovni često deluju kao velika prilika. Problem je što premium limuzina ne postaje jeftina za održavanje samo zato što joj je kupovna cena pala.

E klasa je prirodniji izbor za vozača kome su udobnost, duga putovanja i smiren karakter na prvom mestu. Kod polovnog primerka treba proveriti automatski menjač, vazdušno oslanjanje ako ga ima, elektroniku, dizel sistem, tragove službene upotrebe i stvarnu kilometražu. Automobil može izgledati dostojanstveno i kada je već ozbiljno umoran.

Serija 5 ima prednost kada vozač želi više osećaja u vožnji i bolju dinamiku. To traži još strožu proveru trapa, guma, kočnica, menjača i motora. Primerci koji su voženi agresivno, čipovani ili održavani minimalno lako pretvore privlačnu cenu u skup početak vlasništva.

Kod oba modela presuđuje dokumentacija. Servisni računi, dijagnostika, stanje enterijera, gume i probna vožnja govore više od značke. Ako kupuješ premium limuzinu, deo budžeta mora ostati za početna ulaganja. Najbolja kupovina nije najjeftinija E klasa ili Serija 5, nego primerak gde kilometraža, servis i stanje pričaju istu priču.
TEXT,
                'highlights' => [
                    'E klasa je mirniji putni izbor, Serija 5 bolja za vozača koji traži dinamiku.',
                    'Automatik, trap, elektronika i stvarna kilometraža nose najveći rizik.',
                    'Premium cena polovnjaka mora uključiti budžet za početna ulaganja.',
                ],
                'tags' => ['Mercedes E klasa', 'BMW Serija 5', 'premium limuzina', 'kilometraža'],
                'meta_title' => 'Mercedes E klasa ili BMW Serija 5: polovni premium',
                'meta_description' => 'Poređenje polovnih Mercedes-Benz E klase i BMW Serije 5: kilometraža, automatik, trap, dizel, elektronika, servis i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa vučnom kukom: kada koristan dodatak otkriva težak život',
                'slug' => 'auto-sa-vucnom-kukom-kada-koristan-dodatak-otkriva-tezak-zivot',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Vučna kuka može biti korisna oprema, ali kod polovnog auta treba proveriti menjač, kvačilo, hlađenje, trap, elektroniku i tragove vuče.',
                'content' => <<<'TEXT'
Vučna kuka u oglasu može delovati kao praktičan bonus. Dobro dođe za prikolicu, nosač bicikala, kamp opremu ili povremeni transport. Ipak, kod polovnog automobila kuka može otkriti i teži život od onog koji prodavac opisuje. Nije problem što auto ima kuku, već šta je sa njom vukao i koliko često.

Prva provera su kvačilo i menjač kod manuelnih automobila, odnosno automatski menjač kod automatika. Vuča opterećuje prenos više nego obična gradska vožnja. Ako menjač trza, kasni, proklizava ili kvačilo hvata visoko, kuka više nije samo dodatak, nego signal za ozbiljniji pregled.

Druga tema je hlađenje i motor. Vuča po uzbrdicama, leti ili sa većom prikolicom može opteretiti hladnjak, turbinu, kočnice i ulje. Treba proveriti temperaturu, curenja, stanje kočnica, trap i gume. Ako auto ima tragove česte vuče, cena mora priznati taj rizik.

Treća provera je instalacija. Elektrika kuke treba da radi uredno, bez improvizovanih spojeva, grešaka svetala ili oštećenja branika. Dobar auto sa kukom može biti odlična kupovina ako je korišćen razumno. Problem je kada prodavac kuku predstavlja kao sitnicu, a ostatak automobila pokazuje da je godinama radio teži posao.
TEXT,
                'highlights' => [
                    'Vučna kuka nije problem sama po sebi, ali traži proveru šta je auto vukao.',
                    'Menjač, kvačilo, hlađenje, kočnice i trap trpe najveće opterećenje.',
                    'Elektrika kuke i tragovi na braniku otkrivaju kvalitet ugradnje i upotrebe.',
                ],
                'tags' => ['vučna kuka', 'provera polovnjaka', 'menjač', 'kvačilo'],
                'meta_title' => 'Auto sa vučnom kukom: šta proveriti pre kupovine',
                'meta_description' => 'Kako proveriti polovan auto sa vučnom kukom: menjač, kvačilo, hlađenje, kočnice, trap, elektrika kuke i tragovi vuče.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Holandije: kada uredna kilometraža ne znači mirnu kupovinu',
                'slug' => 'uvoz-auta-iz-holandije-kada-uredna-kilometraza-ne-znaci-mirnu-kupovinu',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Holandski automobili često imaju proverljive podatke, ali kupac mora gledati gradsku upotrebu, koroziju, servis, opremu i realnu cenu uvoza.',
                'content' => <<<'TEXT'
Automobili iz Holandije često deluju privlačno jer tržište ima dosta vozila, dobru digitalnu istoriju i jasnije podatke od mnogih drugih izvora. To je prednost, ali ne znači da je svaki uvoz iz Holandije mirna kupovina. Uredna kilometraža je samo jedan deo slike, a stanje konkretnog automobila mora potvrditi podatke.

Prva tema je način vožnje. Holandija znači mnogo grada, kratkih relacija, parkiranja, kiše i biciklističke infrastrukture oko automobila. Sitna oštećenja, tragovi parkinga, vlaga u enterijeru, stanje kočnica i trap mogu reći više od same kilometraže. Auto sa manjim brojem kilometara može imati težak gradski ritam.

Druga provera su dokumenti i servis. Dostupni izveštaji, tehnički pregledi, računi i servisna istorija moraju se uklopiti sa stanjem. Ako postoji rupa u istoriji, ne treba je ignorisati samo zato što auto dolazi sa uređenog tržišta. Uvoz i dalje traži hladnu proveru.

Treća tema je cena. Transport, dažbine, zarada prodavca, registracija i početna ulaganja moraju stati u računicu. Uvoz iz Holandije ima smisla kada podaci, stanje i cena idu zajedno. Ako se kupovina oslanja samo na priču o urednoj kilometraži, rizik ostaje na kupcu.
TEXT,
                'highlights' => [
                    'Holandska istorija može pomoći, ali ne zamenjuje pregled stanja.',
                    'Gradska upotreba, vlaga, kočnice i parking tragovi često otkrivaju realan život auta.',
                    'Cena uvoza mora uključiti transport, dažbine, registraciju i početna ulaganja.',
                ],
                'tags' => ['uvoz iz Holandije', 'uvoz automobila', 'kilometraža', 'servisna istorija'],
                'meta_title' => 'Uvoz auta iz Holandije: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta uvezenog iz Holandije: kilometraža, servisna istorija, gradska upotreba, korozija, dokumenti i cena uvoza.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Dacia Sandero Stepway: mali auto koji ne treba platiti kao SUV',
                'slug' => 'polovni-dacia-sandero-stepway-mali-auto-koji-ne-treba-platiti-kao-suv',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Sandero Stepway deluje kao povoljan crossover, ali kupac treba da proveri motor, trap, opremu, prethodnu namenu i da li cena beži od realnosti.',
                'content' => <<<'TEXT'
Dacia Sandero Stepway privlači kupce koji žele jednostavan auto, viši klirens, preglednost i izgled malog crossovera bez velikih troškova. To može biti dobra kupovina, ali samo ako cena ostane u skladu sa onim što automobil zaista jeste. Stepway nije SUV i ne treba ga platiti kao ozbiljniji porodični crossover.

Prva provera je motor i servis. Benzinske i plinske verzije mogu biti vrlo racionalne, ali samo kada je održavanje jasno i kada nema tragova zapuštene gradske upotrebe. Hladan start, curenja, rad klime i stanje izduva treba proveriti bez pretpostavke da jednostavan auto ne može imati problem.

Druga tema je trap. Viši izgled često ohrabri vozače da lakše prelaze preko ivičnjaka, rupa i lošeg puta. Gume, amortizeri, spone, ležajevi i kočnice brzo otkrivaju da li je auto zaista bio nežno korišćen. Ako se čuje lupkanje, cena mora uključiti ulaganje.

Treća tema je oprema i prethodna namena. Stepway se često kupuje kao gradski porodični auto, službeno vozilo ili ekonomičan auto za mnogo kratkih relacija. Enterijer, gepek i vrata treba da prate kilometražu. Dobar Sandero Stepway ima smisla kada je jednostavan, uredan i realno plaćen. Ako ga tržište ceni previše zbog crossover izgleda, običan Sandero ili drugi gradski auto mogu biti pametniji izbor.
TEXT,
                'highlights' => [
                    'Sandero Stepway je praktičan mali auto, ali ne treba ga plaćati kao pravi SUV.',
                    'Motor, klima, trap i gume otkrivaju koliko je auto trpeo grad i loš put.',
                    'Dobar primerak ima smisla samo kada cena ostaje realna za klasu.',
                ],
                'tags' => ['Dacia Sandero Stepway', 'mali crossover', 'gradski auto', 'niski troškovi'],
                'meta_title' => 'Polovni Dacia Sandero Stepway: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Dacia Sandero Stepway modela: motor, trap, oprema, gradska upotreba, cena i realna vrednost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Škoda Karoq: kompaktni SUV koji mora opravdati cenu Tiguana',
                'slug' => 'polovni-skoda-karoq-kompaktni-suv-koji-mora-opravdati-cenu-tiguana',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Karoq može biti racionalan porodični SUV, ali kupac mora proveriti motor, DSG, trap, opremu i da li cena previše prati Tiguan.',
                'content' => <<<'TEXT'
Škoda Karoq je zanimljiv polovnjak za kupce koji žele praktičan kompaktni SUV, ali ne žele da plate punu Volkswagen Tiguan cenu. Problem je što tržište često baš tako formira cene. Dobar Karoq može biti vrlo logična kupovina, ali samo ako stanje i oprema opravdavaju iznos koji prodavac traži.

Prva provera je motor i servisna istorija. Benzinci su dobri za gradsku i mešovitu vožnju kada imaju uredno održavanje, dok dizel ima smisla za duže relacije. Kod svake verzije proveri hladan start, curenja, rad turbine, DPF kod dizela i račune za redovne servise. Karoq sa praznom servisnom pričom ne treba plaćati kao siguran porodični izbor.

Druga tema je menjač i trap. DSG mora menjati brzine glatko, bez trzaja, zadrške i nejasnih zvukova pri manevrisanju. Trap, amortizeri, kočnice i gume treba da pokažu da auto nije samo izgledao kao gradski SUV, nego da je i stvarno nežno korišćen. Velike felne i loš put mogu brzo pojesti prednost urednog enterijera.

Treća tema je oprema. Adaptivni tempomat, LED farovi, kamera, senzori, digitalna tabla i automatska klima podižu vrednost samo ako rade bez greške. Karoq je dobra kupovina kada nudi jasnu istoriju, realnu cenu i manje rizika od Tiguana. Ako je razlika mala, kupac treba hladno da uporedi konkretna dva primerka, ne samo značku.
TEXT,
                'highlights' => [
                    'Karoq ima smisla kada je vidljivo jeftiniji ili uredniji od sličnog Tiguana.',
                    'Motor, DSG, trap i gume odlučuju da li je SUV forma zaista mirna kupovina.',
                    'Bogata oprema vredi samo ako elektronika, senzori i LED farovi rade bez doplate posle kupovine.',
                ],
                'tags' => ['Škoda Karoq', 'polovni SUV', 'DSG', 'Tiguan alternativa'],
                'meta_title' => 'Polovni Škoda Karoq: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Škoda Karoq modela: motor, DSG, trap, oprema, LED farovi, cena u odnosu na Tiguan i servisna istorija.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f241f', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Kia Stonic ili Hyundai Bayon: mali crossover za grad kada budžet ne trpi SUV cenu',
                'slug' => 'kia-stonic-ili-hyundai-bayon-mali-crossover-za-grad-kada-budzet-ne-trpi-suv-cenu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Stonic i Bayon ciljaju kupce koji žele povišeno sedenje i niske troškove, ali stanje, garancija i realna upotreba moraju odlučiti.',
                'content' => <<<'TEXT'
Kia Stonic i Hyundai Bayon često završavaju u istom užem izboru jer nude povišenu poziciju sedenja, gradske dimenzije i niže troškove od većih SUV modela. To nisu porodični SUV automobili za svaku namenu, nego mali crossoveri koji treba da olakšaju grad, parkiranje i svakodnevne relacije.

Stonic ima smisla za kupca koji želi jednostavniji osećaj, poznatu mehaniku i dobar odnos cene i opreme. Kod polovnog primerka proveri servisnu istoriju, stanje kvačila, klime, trapa i guma, jer gradska vožnja brzo ostavi trag. Ako postoji fabrička garancija ili uredan servisni trag, to vredi više od nekoliko dodatnih ekrana u oglasu.

Bayon je praktičan kada kupac želi noviji utisak, dobru opremu i racionalan mali auto za grad i povremeni put. Treba proveriti elektroniku, multimediju, senzore, kočnice i tragove kratkih relacija. Enterijer i gepek moraju odgovarati realnim potrebama, jer crossover izgled ne znači automatski porodični prostor.

Izbor između Stonica i Bayona ne treba rešavati markom, već konkretnim primerkom. Bolja kupovina je auto sa urednom istorijom, jasnom garancijom, zdravim trapom i realnom cenom. Ako se mali crossover plaća kao ozbiljniji kompaktni SUV, vredi pogledati i klasičan kompakt ili karavan.
TEXT,
                'highlights' => [
                    'Stonic i Bayon su mali crossoveri za grad, ne zamena za veći porodični SUV.',
                    'Garancija, servisni trag, trap i gradska istrošenost vrede više od vizuelnog utiska.',
                    'Ako cena ode previsoko, klasičan kompakt može biti racionalnija kupovina.',
                ],
                'tags' => ['Kia Stonic', 'Hyundai Bayon', 'mali crossover', 'gradski auto'],
                'meta_title' => 'Kia Stonic ili Hyundai Bayon: polovni mali crossover',
                'meta_description' => 'Poređenje polovnih Kia Stonic i Hyundai Bayon modela: gradska vožnja, oprema, garancija, trap, troškovi i realna vrednost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa zamenskim farovima: kada loš deo kvari celu kupovinu',
                'slug' => 'auto-sa-zamenskim-farovima-kada-los-deo-kvari-celu-kupovinu',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Zamenski farovi mogu biti bezazleni, ali često otkrivaju udarac, lošu popravku, vlagu, greške senzora ili skupu elektroniku.',
                'content' => <<<'TEXT'
Zamenski far na polovnom autu nije automatski razlog za odustajanje, ali jeste razlog za ozbiljniju proveru. Far je često prvi trag da je auto imao udarac, lošu parking štetu, nekvalitetnu popravku ili problem sa vlagom. Kod modernih LED i adaptivnih farova to više nije sitan estetski detalj.

Prvo proveri zašto je far menjan. Račun, fotografija štete i servisni trag vrede mnogo više od objašnjenja da je prethodni vlasnik samo želeo nov deo. Ako jedan far izgleda novije, drugačije svetli ili ima drugačiji proizvođački znak, treba pogledati branik, nosače, krila, haubu, hladnjak i zazore.

Druga provera je kvalitet dela. Jeftini zamenski farovi mogu lošije osvetljavati put, magliti, skupljati vodu ili praviti problem na tehničkom pregledu. Kod LED i adaptivnih sistema proveri nivelaciju, greške na tabli, rad dnevnog svetla, senzore i pranje farova ako postoji. Far koji radi na parkingu ne znači da će raditi pravilno u realnoj vožnji.

Treća tema je pregovaranje. Ako je far zamenjen posle jasne i dobro popravljene štete, cena može samo da odrazi stanje. Ako dokumenti ne postoje, zazori nisu ujednačeni ili se vide lomljeni nosači, rizik je veći od samog fara. Tada pregled karoserije i dijagnostika nisu opcija, nego uslov kupovine.
TEXT,
                'highlights' => [
                    'Zamenski far traži proveru razloga zamene, nosača, zazora i prednjeg koša.',
                    'Jeftin far može doneti vlagu, loše svetlo, greške senzora i problem na tehničkom.',
                    'Bez računa i jasne istorije, cena mora uključiti rizik loše popravke.',
                ],
                'tags' => ['zamenski farovi', 'provera karoserije', 'LED farovi', 'udarac'],
                'meta_title' => 'Auto sa zamenskim farovima: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto sa zamenskim farovima: udarac, nosači, zazori, vlaga, LED sistemi, senzori, tehnički pregled i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa tuningom i čipom: kada više snage znači više rizika',
                'slug' => 'auto-sa-tuningom-i-cipom-kada-vise-snage-znaci-vise-rizika',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Čipovan polovnjak može biti zabavan, ali kupac mora proveriti ko je radio mapu, stanje turbine, kvačila, menjača i emisijskih sistema.',
                'content' => <<<'TEXT'
Auto sa tuningom ili čipom često izgleda kao bolja ponuda: više snage za istu cenu, zanimljivija vožnja i priča da je sve urađeno profesionalno. Problem je što dodatna snaga ne opterećuje samo motor. Ona menja život turbine, kvačila, menjača, DPF-a, EGR-a, kočnica i guma.

Prvo pitanje je ko je radio mapu i da li postoji dokaz. Račun, dyno merenje, opis softvera i servis koji stoji iza posla vrede više od rečenice da je auto samo blago pojačan. Ako prodavac ne zna detalje, kupac preuzima rizik tuđe improvizacije. Posebno oprezno treba gledati dizel automobile sa ugašenim ili izmenjenim emisijskim sistemima.

Druga provera je mehanika. Hladan start, dim, pritisak turbine, curenja, rad kvačila, proklizavanje, menjač, temperatura i greške na dijagnostici moraju se gledati strože nego kod fabričkog primerka. Auto može ići odlično, a ipak nositi umor koji se vidi tek posle duže vožnje ili pregleda.

Treća tema je vrednost. Tuning ne podiže cenu svakom kupcu. Naprotiv, često sužava tržište i povećava sumnju. Ako auto nema jasnu istoriju, fabričke delove, uredan tehnički status i dokaz da nije vožen agresivno, više snage može značiti samo više budućih troškova.
TEXT,
                'highlights' => [
                    'Čipovan auto traži dokaz ko je radio mapu, kada i sa kojim rezultatima.',
                    'Turbina, kvačilo, menjač, DPF, EGR i kočnice trpe veći rizik od fabričkog primerka.',
                    'Tuning retko povećava realnu vrednost ako nema odličnu dokumentaciju.',
                ],
                'tags' => ['čip tuning', 'tuning', 'turbina', 'DPF', 'menjač'],
                'meta_title' => 'Auto sa tuningom i čipom: šta proveriti',
                'meta_description' => 'Vodič za kupovinu čipovanog polovnjaka: mapa, dyno, turbina, kvačilo, menjač, DPF, EGR, kočnice, dokumentacija i rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Ford S-Max: porodični monovolumen koji traži proveru automatika',
                'slug' => 'polovni-ford-s-max-porodicni-monovolumen-koji-trazi-proveru-automatika',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'S-Max je odličan za porodicu i putovanja, ali kupac mora proveriti Powershift, dizel, trap, klimu, sedam sedišta i stvarnu kilometražu.',
                'content' => <<<'TEXT'
Ford S-Max je jedan od polovnjaka koji najbolje pokazuje zašto monovolumen nije nestao zbog manjka smisla, nego zbog mode SUV modela. Nudi nizak prag utovara, mnogo prostora, dobru vožnju i praktičnu kabinu. Za porodicu može biti pametniji od SUV-a, ali samo ako stanje ne krije skupe troškove.

Prva velika provera je menjač. Powershift automatik mora imati uredan servis ulja i rad bez trzaja, kašnjenja ili proklizavanja. Probna vožnja treba da uključi gradsku gužvu, kretanje uzbrdo, parkiranje i vožnju kada se menjač zagreje. Ako nema računa za servis, u cenu odmah ulazi rizik.

Druga tema je dizel i kilometraža. S-Max se često kupovao za putovanja, službene relacije i prevoz porodice, pa velika kilometraža nije problem ako je uredno održavan. Problem je kada broj na satu ne prati sedišta, volan, pedale, gepek, vrata i servisnu dokumentaciju. DPF, EGR, turbina i curenja treba proveriti bez žurbe.

Treća provera je porodična oprema. Klima mora hladiti i pozadi, sedišta se moraju pomerati i preklapati bez zapinjanja, a elektrika vrata, senzori i parking kamera treba da rade. Dobar S-Max vredi traženja jer daje mnogo auta za novac. Loš primerak brzo pokaže zašto veliki porodični auto ne treba kupovati bez majstora.
TEXT,
                'highlights' => [
                    'S-Max može biti praktičniji od SUV-a kada prostor i utovar zaista odlučuju.',
                    'Powershift automatik traži dokaz servisa ulja i ozbiljnu probnu vožnju.',
                    'Dizel, klima, sedam sedišta, trap i kilometraža moraju se proveriti porodičnim tempom.',
                ],
                'tags' => ['Ford S-Max', 'porodični auto', 'Powershift', 'monovolumen'],
                'meta_title' => 'Polovni Ford S-Max: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Ford S-Max modela: Powershift automatik, dizel, DPF, EGR, klima, sedam sedišta, trap i kilometraža.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Seat Ateca: španski Tiguan koji traži proveru TSI i DSG-a',
                'slug' => 'polovni-seat-ateca-spanski-tiguan-koji-trazi-proveru-tsi-i-dsg-a',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Ateca nudi praktičan SUV format i dobru vožnju, ali kupac mora proveriti TSI, TDI, DSG, trap, opremu i realnu razliku u ceni.',
                'content' => <<<'TEXT'
Seat Ateca često privuče kupca koji želi Volkswagen Tiguan logiku, ali uz malo življi karakter i nižu cenu. To može biti dobra kupovina, posebno kada je primerak uredan i realno plaćen. Problem nastaje kada se Ateca ceni skoro kao Tiguan, a ne donosi bolju istoriju, bolju opremu ili jasnije održavanje.

Prva provera je motor. TSI benzinci imaju smisla za grad i mešovitu vožnju kada su redovno održavani, dok TDI verzije treba birati samo ako prethodna upotreba odgovara dizelu. Hladan start, curenja, potrošnja ulja, rad turbine, DPF i EGR kod dizela moraju biti deo pregleda, ne usputna pitanja posle probne vožnje.

Druga tema je DSG i trap. DSG mora menjati glatko u hladnom i toplom stanju, bez trzaja pri manevrisanju i bez zadrške pri ubacivanju u D ili R. Ateca se često vozi po gradu, preko ivičnjaka i sa većim točkovima, pa amortizeri, spone, gume, kočnice i ležajevi brzo pokažu koliko je SUV izgled stvarno koštao prethodnog vlasnika.

Treća provera je oprema. Kamera, senzori, LED farovi, adaptivni tempomat i multimedija podižu vrednost samo ako rade bez grešaka. Dobra Ateca je zanimljiv polovnjak kada cena ostane ispod Tiguana, a stanje bude uverljivije od proseka. Ako razlika nije dovoljna, kupac treba da poredi konkretne primerke, ne samo značke.
TEXT,
                'highlights' => [
                    'Ateca ima smisla kada nudi bolji odnos stanja i cene od sličnog Tiguana.',
                    'TSI, TDI, DSG i trap treba proveriti strože nego što oglas sugeriše.',
                    'LED, senzori, kamera i multimedija vrede samo ako rade bez grešaka.',
                ],
                'tags' => ['Seat Ateca', 'polovni SUV', 'TSI', 'DSG', 'Tiguan alternativa'],
                'meta_title' => 'Polovni Seat Ateca: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Seat Ateca modela: TSI, TDI, DSG, trap, LED farovi, senzori, oprema i cena u odnosu na Tiguan.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Hyundai Santa Fe ili Kia Sorento: sedam sedišta kada porodica preraste kompaktni SUV',
                'slug' => 'hyundai-santa-fe-ili-kia-sorento-sedam-sedista-kada-porodica-preraste-kompaktni-suv',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Santa Fe i Sorento nude ozbiljan porodični prostor, ali kupac mora proveriti dizel, automatik, pogon, treći red i realne troškove.',
                'content' => <<<'TEXT'
Hyundai Santa Fe i Kia Sorento ulaze u izbor kada porodica preraste kompaktni SUV, a karavan više ne rešava sve potrebe. Sedam sedišta, veliki gepek, udobnost na putu i osećaj ozbiljnijeg auta deluju privlačno. Ipak, ova kupovina nije samo pitanje prostora, nego i troškova koje veći SUV nosi.

Santa Fe često deluje kao mirniji izbor za kupce koji žele udobnost, solidnu opremu i dobru vrednost za novac. Treba proveriti dizel motor, automatski menjač, pogon na sva četiri točka, trap, kočnice i klima uređaj u svim zonama. Ako je auto vukao prikolicu ili često putovao pun, stanje menjača i zadnjeg trapa vredi gledati posebno pažljivo.

Sorento ima sličnu porodičnu logiku, ali često nudi još robusniji utisak i bogatu opremu. Kod polovnog primerka proveri servisnu istoriju, rad automatika, stanje sedišta u trećem redu, elektriku gepeka, kamere, senzore i gume. Veliki SUV sa jeftinim gumama i nejasnim servisima obično nije povoljna prilika, nego najava ulaganja.

Izbor između Santa Fea i Sorenta treba rešiti konkretnim stanjem, ne porodičnim snovima iz oglasa. Bolji je primerak sa jasnom istorijom, zdravim menjačem, dobrim gumama i opremom koja radi. Ako treći red koristiš retko, mlađi kompaktni SUV ili karavan mogu biti racionalniji izbor.
TEXT,
                'highlights' => [
                    'Santa Fe i Sorento imaju smisla kada se treći red i veliki gepek stvarno koriste.',
                    'Automatik, pogon, trap, kočnice i klima brzo menjaju ukupnu cenu vlasništva.',
                    'Veliki SUV nije dobra kupovina ako servisna istorija ne prati težinu i namenu auta.',
                ],
                'tags' => ['Hyundai Santa Fe', 'Kia Sorento', 'sedam sedišta', 'porodični SUV'],
                'meta_title' => 'Hyundai Santa Fe ili Kia Sorento: polovni porodični SUV',
                'meta_description' => 'Poređenje polovnih Hyundai Santa Fe i Kia Sorento modela: sedam sedišta, dizel, automatik, pogon, trap, klima i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mercedes B-klasa: praktičan premium kompakt koji ne sme da se kupi samo zbog značke',
                'slug' => 'polovni-mercedes-b-klasa-praktican-premium-kompakt-koji-ne-sme-da-se-kupi-samo-zbog-znacke',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'B-klasa može biti vrlo praktičan porodični kompakt, ali značka ne sme sakriti proveru motora, automatika, elektronike i enterijera.',
                'content' => <<<'TEXT'
Mercedes B-klasa je polovnjak koji često kupuju ljudi kojima treba praktičnost, viši ulazak i premium utisak bez veličine SUV-a. To je dobra ideja kada je primerak uredan, ali opasna kada kupac plati samo zvezdu na haubi. B-klasa mora dokazati praktičnu vrednost, ne samo izgled i znak.

Prva provera je motor i menjač. Dizel ima smisla za duže relacije, ali traži proveru DPF-a, EGR-a, turbine i servisnog ritma. Benzinac može biti mirniji za grad, ali i dalje treba gledati hladan start, curenja i račune. Automatik mora raditi glatko, bez trzaja i zadrške, jer premium kompakt ne znači jeftinu popravku.

Druga tema je enterijer. B-klasa često služi kao porodični auto, gradski prevoz ili službeno vozilo za mnogo kratkih relacija. Sedišta, volan, pedale, prekidači, gepek i vrata treba da se slažu sa kilometražom. Ako kabina izgleda umorno, a oglas obećava malu kilometražu, proveru treba pooštriti.

Treća provera je elektronika i oprema. Kamera, senzori, multimedija, klima, asistencije i električni dodaci moraju raditi bez lampica i grešaka. Dobra B-klasa je udoban, praktičan i prijatan auto za svakodnevicu. Loša B-klasa je skupi kompakt sa reputacijom koja ne plaća buduće račune.
TEXT,
                'highlights' => [
                    'B-klasa ima smisla kada praktičnost i stanje opravdavaju premium cenu.',
                    'Motor, automatik, DPF, EGR i elektroniku treba proveriti pre pregovora.',
                    'Enterijer često otkriva porodičnu ili službenu upotrebu bolje od kilometraže.',
                ],
                'tags' => ['Mercedes B-klasa', 'premium kompakt', 'porodični auto', 'automatik'],
                'meta_title' => 'Polovni Mercedes B-klasa: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Mercedes B-klasa modela: motor, automatik, DPF, EGR, elektronika, enterijer, oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a3a3a3', '#f8fafc'],
            ],
            [
                'title' => 'Plivajući zamajac kod polovnjaka: tihi deo koji može pokvariti dobru cenu',
                'slug' => 'plivajuci-zamajac-kod-polovnjaka-tihi-deo-koji-moze-pokvariti-dobru-cenu',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Plivajući zamajac se lako previdi na kratkoj probnoj vožnji, a može značajno promeniti računicu dizela, snažnog benzinca i porodičnog auta.',
                'content' => <<<'TEXT'
Plivajući zamajac je jedan od onih delova koji kupac često ne vidi, a ipak može ozbiljno promeniti računicu polovnog automobila. Auto može lepo izgledati, dobro vući i imati korektnu cenu, ali ako zamajac i kvačilo čekaju zamenu, početna ušteda brzo nestaje.

Prvi signal su zvuk i vibracije. Obrati pažnju na lupkanje pri paljenju i gašenju, podrhtavanje na leru, zvuk pri pritisku kvačila, trzaje pri kretanju i vibracije pri niskim obrtajima. Simptomi ne moraju biti dramatični, posebno ako je auto zagrejan ili ako prodavac kontroliše probnu vožnju.

Druga provera je namena auta. Dizel koji je dugo vožen u gradu, auto koji je vukao prikolicu, taksi, službeni auto ili snažniji model sa mnogo obrtnog momenta traže više pažnje. Zamajac ne strada samo od kilometraže, već od načina vožnje, čestog kretanja, lošeg kvačila i agresivnog ubrzavanja iz niskih obrtaja.

Treća tema je cena. Ako postoji sumnja na zamajac, traži okvirnu cenu kompleta sa kvačilom i radom pre pregovora. Prodavac može reći da je to normalan zvuk, ali račun plaća kupac. Dobar polovnjak ne mora imati nov zamajac, ali mora imati cenu koja pošteno prati rizik.
TEXT,
                'highlights' => [
                    'Zamajac proveri kroz hladan start, gašenje, ler, kvačilo i kretanje iz mesta.',
                    'Gradski dizel, prikolica i službena upotreba povećavaju rizik troška.',
                    'Sumnja na zamajac mora ući u pregovor pre kapare, ne posle kupovine.',
                ],
                'tags' => ['plivajući zamajac', 'kvačilo', 'dizel', 'troškovi održavanja'],
                'meta_title' => 'Plivajući zamajac kod polovnjaka: šta proveriti',
                'meta_description' => 'Kako proveriti plivajući zamajac kod polovnog auta: zvuk, vibracije, kvačilo, dizel, gradska vožnja, probna vožnja i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa naknadno ugrađenom multimedijom: kada veliki ekran skriva lošu instalaciju',
                'slug' => 'auto-sa-naknadno-ugradjenom-multimedijom-kada-veliki-ekran-skriva-losu-instalaciju',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Veliki ekran, CarPlay i kamera mogu delovati kao dobra nadogradnja, ali kupac mora proveriti instalaciju, zvuk, senzore i greške elektronike.',
                'content' => <<<'TEXT'
Naknadno ugrađena multimedija može učiniti stariji polovni auto modernijim i prijatnijim za korišćenje. Veliki ekran, CarPlay, Android Auto i kamera za rikverc deluju kao jasna prednost u oglasu. Ipak, kod polovnjaka nije dovoljno da ekran svetli i pusti muziku na parkingu.

Prva provera je kvalitet ugradnje. Pogledaj da li maska lepo naleže, da li se čuju krckanja, da li su isečene plastike, da li komande na volanu rade i da li se uređaj pali i gasi zajedno sa kontaktom. Loša instalacija može prazniti akumulator, praviti šum u zvuku ili izazivati greške na drugim modulima.

Druga tema su funkcije koje su možda izgubljene. Parking senzori, fabrička kamera, prikaz klime, podešavanja vozila, Bluetooth mikrofon, radio prijem i komande na volanu moraju raditi kao pre. Ako je prodavac ugradio veliki ekran, ali su nestale osnovne fabričke funkcije, to nije unapređenje nego kompromis.

Treća provera je dokumentacija. Račun za uređaj i ugradnju, poznat servis i uredno sprovedeni kablovi vrede više od atraktivne fotografije enterijera. Dobra multimedija može podići upotrebljivost auta. Loša ugradnja može sakriti električni problem koji tek novog vlasnika čeka u servisu.
TEXT,
                'highlights' => [
                    'Naknadni ekran proveri kroz paljenje, gašenje, zvuk, komande na volanu i potrošnju akumulatora.',
                    'CarPlay i kamera ne vrede mnogo ako su izgubljene fabričke funkcije.',
                    'Račun i uredna ugradnja su važniji od veličine ekrana na fotografiji oglasa.',
                ],
                'tags' => ['naknadna multimedija', 'CarPlay', 'Android Auto', 'elektronika', 'provera vozila'],
                'meta_title' => 'Auto sa naknadnom multimedijom: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto sa naknadno ugrađenom multimedijom: CarPlay, Android Auto, kamera, komande, senzori, akumulator i instalacija.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Jeep Renegade ili Fiat 500X: isti koreni, različit rizik za kupca',
                'slug' => 'jeep-renegade-ili-fiat-500x-isti-koreni-razlicit-rizik-za-kupca',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Renegade i 500X dele mnogo tehnike, ali kupac mora hladno proveriti motor, automatik, pogon, trap i cenu imidža.',
                'content' => <<<'TEXT'
Jeep Renegade i Fiat 500X često ulaze u isti uži izbor jer dele platformu, deo motora i sličnu crossover logiku. Ipak, kupac ih obično ne gleda istim očima. Renegade prodaje robustan Jeep utisak, dok 500X igra na gradski stil i nižu cenu. Upravo zato treba proveriti da li konkretan primerak opravdava ono što tržište traži.

Renegade ima smisla za kupca koji želi viši položaj sedenja, drugačiji izgled i mogućnost pogona na sva četiri točka kod pojedinih verzija. Kod polovnog primerka proveri motor, automatik, pogon, trap, gume i tragove vožnje po lošijem putu. Jeep značka ne znači da je svaki Renegade spreman za grublju upotrebu.

Fiat 500X može biti racionalniji izbor kada je cena bolja, oprema dobra i istorija jasnija. Treba proveriti iste osnovne stvari: motor, kvačilo ili automatik, elektroniku, senzore, klimu i tragove gradske vožnje. Ako 500X nudi uredniji primerak za manje novca, često je pametnija kupovina od Renegadea kupljenog samo zbog imidža.

Najbolji izbor nije model sa boljom pričom, nego auto sa manje nepoznanica. Ako Renegade ima dokazivu istoriju, zdrav pogon i realnu cenu, može biti zanimljiv. Ako 500X ima bolju dokumentaciju i manje ulaganja, marka ne treba da odluči kupovinu umesto pregleda.
TEXT,
                'highlights' => [
                    'Renegade i 500X dele mnogo tehnike, ali tržište ih često ceni zbog različitog imidža.',
                    'Motor, automatik, pogon, trap i elektronika moraju odlučiti pre značke.',
                    'Uredniji 500X može biti bolja kupovina od skupljeg Renegadea bez jasne istorije.',
                ],
                'tags' => ['Jeep Renegade', 'Fiat 500X', 'mali crossover', 'poređenje'],
                'meta_title' => 'Jeep Renegade ili Fiat 500X: šta kupiti',
                'meta_description' => 'Poređenje polovnih Jeep Renegade i Fiat 500X modela: motor, automatik, pogon, trap, elektronika, oprema, cena i realan rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Honda HR-V: mali crossover koji traži proveru prostora, CVT-a i cene',
                'slug' => 'polovni-honda-hr-v-mali-crossover-koji-trazi-proveru-prostora-cvt-a-i-cene',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'HR-V može biti praktičan i pouzdan mali crossover, ali reputacija ne sme sakriti proveru CVT-a, enterijera, trapa i realne cene.',
                'content' => <<<'TEXT'
Honda HR-V privlači kupce koji žele mali crossover sa praktičnom kabinom, dobrim ugledom i racionalnim troškovima. To može biti odlična kombinacija, ali samo ako cena ne ode predaleko zbog reputacije. Kod HR-V-a treba platiti konkretno stanje, ne samo očekivanje da je Honda uvek sigurna kupovina.

Prva provera je motor i menjač. Benzinske verzije mogu biti mirne za grad i mešovitu vožnju, ali treba proveriti hladan start, servisne račune, curenja i rad klime. Ako je auto sa CVT menjačem, probna vožnja mora obuhvatiti kretanje, usporavanje, ubrzanje i ponašanje kada se menjač zagreje. Neobični zvukovi ili zadrška menjaju cenu.

Druga tema je prostor. HR-V često iznenadi praktičnošću, ali kupac treba da proveri zadnju klupu, gepek, preklapanje sedišta, prag utovara i realnu upotrebu dečjih sedišta. Crossover izgled ne znači da auto rešava svaku porodičnu potrebu, posebno ako se često putuje punim vozilom.

Treća provera je stanje enterijera i trapa. Gradski primerci mogu imati istrošene gume, kočnice, ogrebane branike, umorne amortizere i enterijer koji ne prati kilometražu. Dobar HR-V ima smisla kada je uredan, servisiran i realno plaćen. Ako je preskup, klasičan kompakt ili veći porodični auto mogu ponuditi više vrednosti.
TEXT,
                'highlights' => [
                    'HR-V ne treba platiti samo zbog Hondine reputacije, već zbog stanja konkretnog auta.',
                    'CVT mora raditi mirno hladan i topao, bez zadrške i neprirodnih zvukova.',
                    'Praktičnost proveri sedištima, gepekom i svakodnevnim porodičnim scenarijem.',
                ],
                'tags' => ['Honda HR-V', 'mali crossover', 'CVT', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Honda HR-V: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Honda HR-V modela: motor, CVT menjač, prostor, zadnja klupa, gepek, trap, enterijer i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Toyota Verso: porodični monovolumen koji mora opravdati godine',
                'slug' => 'polovni-toyota-verso-porodicni-monovolumen-koji-mora-opravdati-godine',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Verso je praktičan porodični auto, ali starost, dizel, enterijer, treći red, klima i servisna istorija odlučuju da li reputacija vredi.',
                'content' => <<<'TEXT'
Toyota Verso je polovnjak za kupce koji žele praktičan porodični auto bez SUV mode i bez previše komplikovanja. Dobra reputacija Toyote pomaže, ali Verso je danas često stariji automobil, pa se kupovina mora posmatrati kroz stanje, a ne kroz mit o neuništivosti.

Prva provera je motor i servisna istorija. Benzinske verzije mogu biti jednostavnije za grad, dok dizel ima smisla samo ako je vožen na dužim relacijama i ima dokaz održavanja. Hladan start, curenja, DPF, EGR, kvačilo, menjač i stanje izduva treba proveriti bez pretpostavke da Toyota ne može imati zapušten primerak.

Druga tema je porodična kabina. Verso se često koristio za decu, putovanja, gepek pun stvari i kratke gradske obaveze. Sedišta, mehanizmi preklapanja, pojasevi, plastike, klima i ventilacija pozadi moraju raditi. Ako postoji treći red, proveri da li se lako podiže i da li nije samo marketinški dodatak.

Treća provera je cena. Verso može biti odličan izbor kada je uredan i realno plaćen, ali ne treba ga preplatiti samo zato što nosi Toyota znak. Ako su gume, kočnice, trap, klima i veliki servis pred kupcem, ta ulaganja moraju ući u pregovor pre kapare.
TEXT,
                'highlights' => [
                    'Verso je praktičan porodični auto, ali starost mora biti uračunata u cenu.',
                    'Dizel, DPF, EGR, kvačilo, klima i treći red traže pažljiv pregled.',
                    'Toyota reputacija pomaže samo kada stanje i dokumentacija to potvrde.',
                ],
                'tags' => ['Toyota Verso', 'porodični auto', 'monovolumen', 'sedam sedišta'],
                'meta_title' => 'Polovni Toyota Verso: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Toyota Verso modela: motor, dizel, DPF, EGR, treći red, klima, enterijer, trap i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Auto posle poplave: kako prepoznati vlagu koja ne nestaje posle dubinskog pranja',
                'slug' => 'auto-posle-poplave-kako-prepoznati-vlagu-koja-ne-nestaje-posle-dubinskog-pranja',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Poplavljen auto može izgledati očišćeno, ali vlaga, miris, korozija, instalacije i elektronika često otkrivaju rizik koji traje godinama.',
                'content' => <<<'TEXT'
Auto posle poplave je jedan od najrizičnijih polovnjaka jer se problem ne završava kada se kabina osuši i opere. Voda ulazi u izolaciju, instalacije, konektore, module, podne obloge i skrivene šupljine. Na oglasu auto može izgledati sveže, ali vlaga često ostaje tamo gde kupac ne gleda.

Prvi signal je miris. Težak miris vlage, previše parfema, novo dubinsko pranje, vlažne patosnice, fleke ispod sedišta i zamagljivanje stakala traže oprez. Podigni patosnice, pogledaj gepek, rezervni točak, šine sedišta, šrafove i donje delove plastika. Sitna korozija u kabini nije normalan trag svakodnevne upotrebe.

Druga provera je elektronika. Prozori, brave, senzori, svetla, klima, multimedija, airbag lampice, parking senzori i svi prekidači moraju raditi bez slučajnih grešaka. Kod poplavljenog auta kvarovi se često pojavljuju kasnije, kada konektori oksidiraju. Zato kratka probna vožnja nije dovoljna.

Treća tema je dokumentacija i cena. Ako postoji sumnja na poplavu, traži nezavisan pregled, dijagnostiku i pregled podnih obloga. Prodavac može reći da je auto samo bio prljav ili da je voda ušla kroz prozor. Bez jasnog dokaza, rizik ostaje veliki, a najbolji popust je često odustajanje.
TEXT,
                'highlights' => [
                    'Poplavljen auto može izgledati čist, ali vlaga ostaje u izolaciji, konektorima i podu.',
                    'Miris, šine sedišta, gepek, korozija i previše parfema su važni signali.',
                    'Električne greške posle vode često dolaze kasnije, ne na prvoj probnoj vožnji.',
                ],
                'tags' => ['auto posle poplave', 'vlaga u autu', 'provera vozila', 'elektronika'],
                'meta_title' => 'Auto posle poplave: kako prepoznati rizik',
                'meta_description' => 'Kako proveriti polovan auto posle poplave: vlaga, miris, korozija, šine sedišta, gepek, instalacije, elektronika i kada odustati.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#06b6d4', '#f8fafc'],
            ],
            [
                'title' => 'Slaba klima na polovnom autu: kada letnji test otkriva skup kvar',
                'slug' => 'slaba-klima-na-polovnom-autu-kada-letnji-test-otkriva-skup-kvar',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Klima koja slabo hladi nije sitnica za kasnije, jer kompresor, kondenzator, curenje gasa i elektronika mogu ozbiljno promeniti cenu.',
                'content' => <<<'TEXT'
Slaba klima na polovnom autu često se u oglasu i razgovoru predstavi kao sitnica. Prodavac kaže da samo treba dopuniti gas, kupac pomisli da to nije veliki trošak, a pravi problem može biti kompresor, kondenzator, isparivač, ventilator, senzor pritiska ili curenje koje se vraća čim prođe leto.

Prva provera je jednostavna: uključi klimu odmah na početku pregleda i prati koliko brzo hladi. Obrati pažnju na zvuk kompresora, promenu obrtaja, ventilator hladnjaka, miris iz ventilacije i da li temperatura ostaje stabilna u vožnji. Ako klima hladi samo kratko ili samo na otvorenom putu, problem nije rešen dopunom.

Druga tema je trag održavanja. Račun za servis klime, zamenu kondenzatora ili popravku curenja vredi više od rečenice da je sve skoro rađeno. Tragovi zelenog UV sredstva, masni delovi oko spojeva, oštećen kondenzator i neujednačeno hlađenje po zonama mogu pokazati gde novac odlazi posle kupovine.

Treća provera je pregovaranje. Klima nije luksuz, nego svakodnevna oprema koja utiče na bezbednost, odmagljivanje i komfor. Ako postoji sumnja, traži proveru u servisu pre kapare ili cenu spusti za realan kvar, ne za dopunu gasa. Kod modernog auta klima može biti mnogo skuplja od male letnje neprijatnosti.
TEXT,
                'highlights' => [
                    'Slaba klima nije automatski samo dopuna gasa; curenje i kompresor mogu biti skupi.',
                    'Testiraj hlađenje odmah, u leru, u vožnji i kroz sve zone ventilacije.',
                    'Bez računa ili servisne provere, rizik klime mora ući u pregovor.',
                ],
                'tags' => ['klima', 'troškovi održavanja', 'kompresor klime', 'provera polovnjaka'],
                'meta_title' => 'Slaba klima na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti klimu na polovnom autu: slabo hlađenje, kompresor, kondenzator, curenje gasa, ventilator, servis i realna cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Citroen C3 ili Peugeot 208: mali gradski auto kada dizajn ne sme da zameni proveru',
                'slug' => 'citroen-c3-ili-peugeot-208-mali-gradski-auto-kada-dizajn-ne-sme-da-zameni-proveru',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C3 i 208 nude stil i gradsku praktičnost, ali kupac mora proveriti motor, elektroniku, trap, servisnu istoriju i realnu cenu.',
                'content' => <<<'TEXT'
Citroen C3 i Peugeot 208 često privuku kupce koji žele mali gradski auto sa više karaktera od proseka. Oba modela mogu biti dobra kupovina, ali samo ako dizajn ne zameni proveru. Kod malog auta koji se vozi po gradu stanje trapa, kvačila, kočnica, guma i enterijera često govori više od fotografija.

C3 ima smisla za kupca koji želi udobniji, mekši i jednostavniji gradski auto. Treba proveriti servisnu istoriju, rad klime, elektroniku, senzore, stanje sedišta i tragove čestog parkiranja. Ako je primerak korišćen za kratke relacije, motor i akumulator mogu otkriti više od same kilometraže.

Peugeot 208 često deluje modernije i dinamičnije, ali kod njega oprema i izgled lako podignu cenu. Treba proveriti motor, menjač, i-Cockpit ergonomiju, multimediju, svetla i stanje trapa. Ako se kupuje benzinska verzija, servisni ritam i računi vrede više od tvrdnje da je auto mali i jeftin za održavanje.

Izbor između C3 i 208 treba rešiti konkretnim primerkom. C3 je bolji kada je udobniji, uredniji i realno plaćen. 208 je bolji kada donosi bolji motor, opremu i jasnu istoriju bez preplaćivanja dizajna. Najgori izbor je najlepši oglas sa najviše nepoznanica.
TEXT,
                'highlights' => [
                    'C3 i 208 kupuj po stanju, ne po fotografijama i enterijeru.',
                    'Gradska vožnja brzo otkriva trap, kvačilo, kočnice, gume i akumulator.',
                    'Servisna istorija i realna cena vrede više od stila kod oba modela.',
                ],
                'tags' => ['Citroen C3', 'Peugeot 208', 'gradski auto', 'poređenje'],
                'meta_title' => 'Citroen C3 ili Peugeot 208: koji polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Citroen C3 i Peugeot 208 modela: motor, elektronika, trap, gradska vožnja, oprema, servisna istorija i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni BMW serija 1: kompakt koji traži proveru lanca, trapa i istorije',
                'slug' => 'polovni-bmw-serija-1-kompakt-koji-trazi-proveru-lanca-trapa-i-istorije',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Serija 1 može biti zabavan premium kompakt, ali kupac mora proveriti motor, lanac, trap, menjač, enterijer i trag agresivne vožnje.',
                'content' => <<<'TEXT'
BMW serija 1 je polovnjak koji kupci često biraju srcem: kompaktne dimenzije, premium znak i bolji osećaj u vožnji od prosečnog gradskog auta. To može biti odlična kombinacija, ali samo kada je konkretan primerak uredan. Najjeftinija serija 1 retko je najpametnija serija 1.

Prva provera je motor i servisna istorija. Kod rizičnih generacija posebno treba slušati lanac, proveriti hladan start, dim, curenja i račune za održavanje. Benzinac nije automatski bezbrižan, a dizel nije automatski loš. Bitno je da način vožnje, kilometraža i servisni trag imaju logiku.

Druga tema je trap i pogon. Serija 1 često privlači vozače koji vole dinamičniju vožnju, pa gume, amortizeri, spone, kočnice, felne i zadnji trap mogu otkriti stvaran život auta. Probna vožnja treba da uključi lošiji put, kočenje, ubrzanje i slušanje zvukova iz zadnjeg dela.

Treća provera je enterijer i oprema. Volan, sedišta, prekidači, multimedija i klima moraju pratiti kilometražu. Dobar BMW serija 1 ima smisla kada donosi zdravu mehaniku i jasnu istoriju. Loš primerak je samo skup kompakt koji će brzo potrošiti razliku u ceni.
TEXT,
                'highlights' => [
                    'Seriju 1 ne treba kupiti samo zbog BMW znaka i osećaja u vožnji.',
                    'Lanac, hladan start, trap, gume i kočnice moraju biti deo pregleda.',
                    'Agresivna vožnja i nejasna istorija brzo pretvore kompakt u skup početak vlasništva.',
                ],
                'tags' => ['BMW serija 1', 'premium kompakt', 'lanac', 'trap'],
                'meta_title' => 'Polovni BMW serija 1: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog BMW serija 1 modela: motor, lanac, dizel, benzinac, trap, menjač, enterijer, servisna istorija i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa uklonjenim DPF-om: kada jeftino rešenje postaje skup problem',
                'slug' => 'auto-sa-uklonjenim-dpf-om-kada-jeftino-resenje-postaje-skup-problem',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Uklonjen DPF može sakriti skuplji dizel problem, tehnički rizik i lošu kasniju prodaju, pa kupac mora proveriti tragove pre kapare.',
                'content' => <<<'TEXT'
Auto sa uklonjenim DPF-om često se prodaje uz priču da je problem rešen zauvek. U praksi je često rešen samo simptom. Ako je filter uklonjen, treba pitati zašto je do toga došlo: gradska vožnja, loše dizne, EGR, turbina, pogrešno ulje ili zapušten servis mogu i dalje biti prisutni.

Prvi signal je ponašanje motora. Obrati pažnju na dim, miris izduva, greške na dijagnostici, neobične mape, lampice koje se ne pale pri kontaktu i tragove intervencije na izduvu. Ako prodavac izbegava temu DPF-a ili kaže da je to prednost, kupac treba da bude još oprezniji.

Druga tema je tehnički i pravni rizik. Uklonjen DPF može praviti problem na strožem tehničkom pregledu, smanjiti kasniju prodaju i otvoriti pitanje legalnosti vozila. Čak i ako auto trenutno prolazi, kupac preuzima rizik narednih pregleda i budućih pravila.

Treća provera je računica. Dobar dizel ne mora imati uklonjen DPF da bi bio upotrebljiv. Ako je filter uklonjen, cena mora biti značajno niža, a majstor treba da proveri da li iza toga stoji veći kvar. U mnogim slučajevima je bolje kupiti uredan benzinac nego jeftin dizel sa sakrivenom istorijom.
TEXT,
                'highlights' => [
                    'Uklonjen DPF često rešava lampicu, ali ne i uzrok dizel problema.',
                    'Dim, izduv, dijagnostika i ponašanje lampica moraju se proveriti pre kapare.',
                    'Tehnički pregled, kasnija prodaja i legalnost mogu biti veći rizik od same popravke.',
                ],
                'tags' => ['DPF', 'dizel', 'provera vozila', 'tehnički pregled'],
                'meta_title' => 'Auto sa uklonjenim DPF-om: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto sa uklonjenim DPF-om: dim, dijagnostika, izduv, EGR, turbina, tehnički pregled, legalnost i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mitsubishi ASX: jednostavan crossover koji ne treba platiti kao RAV4',
                'slug' => 'polovni-mitsubishi-asx-jednostavan-crossover-koji-ne-treba-platiti-kao-rav4',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'ASX može biti racionalan i jednostavan crossover, ali kupac mora proveriti motor, trap, koroziju, opremu i realnu tržišnu cenu.',
                'content' => <<<'TEXT'
Mitsubishi ASX je polovnjak koji često prolazi ispod radara kupaca koji gledaju Qashqai, Sportage, Tucson ili RAV4. Njegova prednost može biti jednostavnost, pristojan prostor i manje komplikovana kupovina. Ali ASX ne treba platiti kao popularniji ili veći SUV samo zato što je tržište danas gladno crossovera.

Prva provera je motor i servisna istorija. Benzinske verzije mogu biti zahvalne za mirniju upotrebu, dok dizel traži ozbiljniju proveru DPF-a, EGR-a, turbine i načina vožnje. Kod svake verzije gledaj hladan start, curenja, račune i da li kilometraža prati stanje enterijera.

Druga tema je trap i karoserija. ASX se često kupuje zbog višeg klirensa i praktičnosti, pa proveri gume, amortizere, spone, kočnice, ležajeve i tragove korozije ispod auta. Ako je primerak vožen po lošim putevima ili često parkiran napolju, pregled odozdo vredi više od sjaja laka.

Treća provera je oprema i cena. Kamera, senzori, klima, multimedija i pogon treba da rade bez izgovora. ASX ima smisla kada je uredan, jednostavan i realno jeftiniji od traženijih SUV modela. Ako ga prodavac ceni kao mnogo moderniji crossover, izbor treba proširiti.
TEXT,
                'highlights' => [
                    'ASX ima smisla kada jednostavnost i stanje opravdavaju cenu.',
                    'Motor, trap, korozija i oprema moraju se proveriti bez oslanjanja na reputaciju.',
                    'Ne treba ga plaćati kao veći i traženiji SUV ako ne nudi bolji primerak.',
                ],
                'tags' => ['Mitsubishi ASX', 'polovni crossover', 'SUV', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mitsubishi ASX: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Mitsubishi ASX modela: motor, dizel, DPF, trap, korozija, oprema, cena i poređenje sa SUV konkurencijom.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Oštećena šoferka na polovnom autu: kada pukotina otkriva veći problem',
                'slug' => 'ostecena-soferka-na-polovnom-autu-kada-pukotina-otkriva-veci-problem',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Pukotina na šoferki nije samo estetski detalj, jer može otvoriti pitanje senzora, kamera, kalibracije, tehničkog pregleda i skrivene štete.',
                'content' => <<<'TEXT'
Oštećena šoferka na polovnom autu često se predstavlja kao sitnica koja se lako reši. Nekad to zaista jeste mali kamenčić i jednostavna zamena. Ali kod modernih automobila šoferka može nositi senzore kiše, kamere za asistencije, grejače, antene i elemente koji traže pravilnu ugradnju i kalibraciju.

Prva provera je položaj i veličina oštećenja. Pukotina u vidnom polju vozača, oštećenje blizu ivice ili trag širenja mogu značiti da auto neće proći tehnički pregled bez zamene. Ako je staklo već menjano, proveri da li lepo naleže, da li ima šum vetra, tragove lepka, curenje ili vlagu oko stubova.

Druga tema su senzori i kamere. Posle zamene šoferke sistemi pomoći vozaču moraju biti kalibrisani. Lane assist, automatska svetla, brisači, kamera, head-up prikaz i grejanje stakla treba proveriti u realnom radu. Ako prodavac kaže da je samo staklo bitno, verovatno ne računa sve troškove.

Treća provera je poreklo oštećenja. Pukotina može biti slučajna, ali može pratiti udarac, loše zatvaranje haube ili naprezanje karoserije. Zato pogledaj haubu, krov, stubove, zazore i tragove popravke. Ako zamena stakla ulazi u cenu, pregovor treba da računa i kalibraciju, ne samo najjeftiniju šoferku.
TEXT,
                'highlights' => [
                    'Šoferka sa kamerama i senzorima nije samo običan komad stakla.',
                    'Pukotina može uticati na tehnički pregled, curenje, šum vetra i asistencije.',
                    'U cenu treba uračunati kvalitetno staklo, ugradnju i kalibraciju sistema.',
                ],
                'tags' => ['šoferka', 'vetrobran', 'provera vozila', 'senzori', 'kalibracija'],
                'meta_title' => 'Oštećena šoferka na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti oštećenu šoferku na polovnom autu: pukotina, zamena stakla, senzori, kamera, kalibracija, curenje i tehnički pregled.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Alfa Romeo Giulietta: kompakt sa stilom koji traži hladnu glavu',
                'slug' => 'polovni-alfa-romeo-giulietta-kompakt-sa-stilom-koji-trazi-hladnu-glavu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Giulietta može biti zanimljiviji kompakt od proseka, ali kupac mora proveriti motor, trap, elektroniku, servisnu istoriju i realnu cenu delova.',
                'content' => <<<'TEXT'
Alfa Romeo Giulietta je polovnjak koji često privuče kupca emocijom: lepši dizajn, bolji osećaj za volanom i više karaktera nego kod prosečnog kompakta. To može biti dobra kupovina, ali samo ako se primerak proverava hladno. Kod Giuliette najgora greška je platiti stil, a preskočiti mehaniku, trap i servisni trag.

Prva provera je motor. Benzinske i dizel verzije ne nose isti rizik, pa treba gledati hladan start, curenja, dim, servis ulja, račune i da li je auto vožen normalno ili samo održavan pred prodaju. Ako prodavac nema dokaze o većim servisima, cenu treba računati kao da ulaganje tek dolazi.

Druga tema je trap i upravljanje. Giulietta se često kupuje zbog vožnje, pa loš put, neravnine, kočenje i brzo menjanje pravca mogu otkriti amortizere, spone, ramena, gume i felne. Ako auto izgleda atraktivno na velikim felnama, proveri da li taj izgled već krije skup račun.

Treća provera je elektronika i enterijer. Klima, prozori, svetla, multimedija, prekidači i instrument tabla moraju raditi bez slučajnih grešaka. Dobar primerak Giuliette ima smisla kada nudi urednu istoriju i realnu cenu. Loš primerak je samo lep kompakt koji će brzo tražiti strpljenje i novac.
TEXT,
                'highlights' => [
                    'Giuliettu treba kupiti zbog dobrog primerka, ne samo zbog dizajna i emocije.',
                    'Motor, trap, felne, elektronika i servisni računi moraju biti deo pregleda.',
                    'Atraktivna cena bez istorije često znači da prvi vlasnik plaća zaostala ulaganja.',
                ],
                'tags' => ['Alfa Romeo Giulietta', 'polovni kompakt', 'kupovina polovnjaka', 'trap'],
                'meta_title' => 'Polovni Alfa Romeo Giulietta: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Alfa Romeo Giulietta modela: motor, trap, elektronika, servisna istorija, enterijer, delovi i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#dc2626', '#f8fafc'],
            ],
            [
                'title' => 'Škoda Octavia ili Volkswagen Passat karavan: kada prostor nije jedini argument',
                'slug' => 'skoda-octavia-ili-volkswagen-passat-karavan-kada-prostor-nije-jedini-argument',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Octavia i Passat karavan nude ogroman prostor, ali prava odluka zavisi od kilometraže, motora, menjača, trapa i stvarne porodične upotrebe.',
                'content' => <<<'TEXT'
Škoda Octavia karavan i Volkswagen Passat karavan često završe u istom užem izboru kada kupac želi veliki gepek, dizel za put i auto koji može da izdrži porodičnu rutinu. Razlika nije samo u prostoru. Passat deluje ozbiljnije i udobnije, dok Octavia često nudi bolju cenu za sličnu praktičnost.

Octavia ima smisla kada kupac želi što više upotrebljivog prostora uz racionalnije troškove. Treba proveriti motor, menjač, servisnu istoriju, trap i stanje enterijera, jer se mnoge Octavije koriste službeno i prelaze velike kilometraže. Dobar primerak je odličan alat za porodicu, ali umoran službeni auto nije dobra kupovina samo zato što ima veliki gepek.

Passat karavan bolje odgovara kupcu koji često putuje, želi mirniji auto na autoputu i spreman je da plati višu cenu održavanja. Kod Passata posebno proveri DSG, dizel sistem, trap, električnu opremu i tragove vuče ili velikog tereta. Ako je cena blizu Octavije, razlog mora biti jasno stanje, ne samo oznaka.

Odluka treba da krene od namene. Za grad i mešovitu vožnju Octavia je često pametnija. Za duge relacije, pun gepek i više komfora Passat može opravdati cenu. U oba slučaja presuđuju dokumentacija, probna vožnja i lista početnih ulaganja, ne samo zapremina gepeka.
TEXT,
                'highlights' => [
                    'Octavia karavan često nudi bolji odnos prostora i troška.',
                    'Passat karavan ima smisla kada komfor i duge relacije opravdavaju višu cenu.',
                    'Kod oba modela kilometraža, DSG, dizel sistem i trap vrede više od samog gepeka.',
                ],
                'tags' => ['Škoda Octavia', 'Volkswagen Passat', 'karavan', 'porodični auto'],
                'meta_title' => 'Škoda Octavia ili Volkswagen Passat karavan',
                'meta_description' => 'Poređenje polovnih Škoda Octavia i Volkswagen Passat karavana: prostor, dizel, DSG, trap, službena upotreba, porodična vožnja i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa zamenjenim airbagovima: kada enterijer otkriva ozbiljnu štetu',
                'slug' => 'auto-sa-zamenjenim-airbagovima-kada-enterijer-otkriva-ozbiljnu-stetu',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Zamenjeni airbagovi mogu značiti kvalitetno popravljenu štetu, ali i prikriven udes, lošu elektroniku, neispravne pojaseve i veliki bezbednosni rizik.',
                'content' => <<<'TEXT'
Auto sa zamenjenim airbagovima ne mora automatski biti za izbegavanje, ali mora biti razlog za mnogo ozbiljniju proveru. Airbag se ne menja zbog sitnice. Ako je vazdušni jastuk aktiviran, treba znati šta se desilo, šta je popravljeno, ko je radio popravku i da li su svi bezbednosni sistemi vraćeni u fabričko stanje.

Prva provera je enterijer. Pogledaj poklopac volana, tablu, šavove, boju plastike, obloge stubova, zatezače pojaseva i lampicu airbaga pri kontaktu. Neujednačene plastike, loše uklopljeni delovi, nova tabla u starom enterijeru ili lampica koja se ne ponaša normalno traže dijagnostiku pre bilo kakve kapare.

Druga tema je dokumentacija. Računi za delove, zapisnik o šteti, fotografije pre popravke i dokaz o kalibraciji vrede više od rečenice da je sve sređeno. Ako su ugrađeni polovni ili neprovereni delovi, kupac preuzima rizik koji se ne vidi na probnoj vožnji.

Treća provera je karoserija. Aktivirani airbagovi često idu uz jači udarac, pa proveri nosače, vezni lim, haubu, krila, pragove, stubove, zazore i tragove farbanja. Ako popravka nije jasna, najbolji pregovor je odustajanje. Bezbednost ne treba kupovati na poverenje.
TEXT,
                'highlights' => [
                    'Zamenjeni airbagovi traže ozbiljnu proveru uzroka, delova i elektronike.',
                    'Volan, tabla, pojasevi, lampice i obloge često otkrivaju kvalitet popravke.',
                    'Bez računa, fotografija štete i dijagnostike, rizik je veći od običnog estetskog ulaganja.',
                ],
                'tags' => ['airbag', 'udes', 'provera vozila', 'bezbednost', 'enterijer'],
                'meta_title' => 'Auto sa zamenjenim airbagovima: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto sa zamenjenim airbagovima: volan, tabla, pojasevi, lampice, dijagnostika, udes, karoserija i bezbednost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Opel Mokka: mali SUV koji ne sme da se kupi samo zbog visokog sedenja',
                'slug' => 'polovni-opel-mokka-mali-suv-koji-ne-sme-da-se-kupi-samo-zbog-visokog-sedenja',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mokka može biti praktičan gradski crossover, ali kupac mora proveriti motor, potrošnju, trap, prostor, opremu i da li cena zaista opravdava SUV formu.',
                'content' => <<<'TEXT'
Opel Mokka je polovnjak koji privlači kupce koji žele viši položaj sedenja, kompaktnu dužinu i osećaj malog SUV-a bez velikih dimenzija. To zvuči idealno za grad, ali Mokka ne sme da se kupi samo zato što deluje praktičnije od Corse ili Astre. Treba proveriti da li stvarna upotreba opravdava cenu.

Prva provera je motor. Benzinske verzije treba gledati kroz servis ulja, hladan start, potrošnju i rad turbine ako je ima. Dizel ima smisla samo ako je prethodna vožnja bila pogodna za DPF i ako postoji servisni trag. Kratke gradske relacije brzo menjaju računicu kod malog crossovera.

Druga tema je trap i udobnost. Mokka je viša i često se vozi po gradu, ivičnjacima i lošim ulicama, pa proveri amortizere, spone, gume, felne, kočnice i zvukove preko neravnina. Viši klirens ne znači da je auto spreman za grubo korišćenje.

Treća provera je prostor i cena. Zadnja klupa, gepek, preglednost, kamera, senzori i klima moraju odgovarati tvojoj rutini. Ako Mokka košta kao veći i praktičniji crossover, izbor treba proširiti. Ima smisla kada je uredna, realno plaćena i kupljena zbog namene, ne samo zbog položaja sedenja.
TEXT,
                'highlights' => [
                    'Mokka ima smisla kada viši položaj sedenja zaista odgovara svakodnevnoj vožnji.',
                    'Motor, DPF, trap, gume i gradska upotreba moraju biti deo pregleda.',
                    'Ne treba plaćati SUV formu ako prostor i stanje ne opravdavaju cenu.',
                ],
                'tags' => ['Opel Mokka', 'mali SUV', 'crossover', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Opel Mokka: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Opel Mokka modela: motor, dizel, DPF, benzinac, trap, prostor, oprema, potrošnja i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'All-season gume na polovnom autu: kada praktičnost sakriva loš kompromis',
                'slug' => 'all-season-gume-na-polovnom-autu-kada-prakticnost-sakriva-los-kompromis',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'All-season gume mogu biti racionalne za blagu vožnju, ali stanje, starost, marka, dimenzija i način korišćenja često otkrivaju stvaran odnos troška i bezbednosti.',
                'content' => <<<'TEXT'
All-season gume na polovnom autu često deluju kao prednost: nema dva seta, nema zamene svake sezone i prodavac može reći da je auto spreman za celu godinu. U praksi, te gume mogu biti dobar kompromis, ali mogu i sakriti štednju na održavanju, pogrešnu dimenziju ili gume koje su već izgubile svojstva.

Prva provera je starost i dubina šare. DOT oznaka, neravnomerno trošenje, ispucale bočne strane, različite marke po osovinama i jeftini nepoznati modeli govore mnogo o vlasniku. All-season guma nije dobra zato što ima oznaku za sva godišnja doba, nego zato što je kvalitetna, sveža i pravilno korišćena.

Druga tema je namena. Za blagu gradsku vožnju i manje kilometraže dobar all-season set može imati smisla. Za planinu, sneg, brzu vožnju, teži SUV ili mnogo autoputa, posebne letnje i zimske gume često su bolji izbor. Kupac treba da računa svoju rutu, ne samo pogodnost prodavca.

Treća provera je pregovaranje. Ako su gume stare, loše marke ili neravnomerno potrošene, to je konkretan trošak posle kupovine. U cenu odmah uračunaj novi set, balansiranje, optiku trapa i eventualno felne. Dobre gume ne popravljaju loš auto, ali loše gume mogu pokvariti dobru cenu.
TEXT,
                'highlights' => [
                    'All-season gume nisu prednost ako su stare, ispucale ili lošeg kvaliteta.',
                    'DOT, dubina šare, trošenje i marka guma otkrivaju odnos vlasnika prema održavanju.',
                    'Za sneg, autoput ili teži auto dva sezonska seta često imaju više smisla.',
                ],
                'tags' => ['all-season gume', 'gume', 'troškovi održavanja', 'bezbednost'],
                'meta_title' => 'All-season gume na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti all-season gume na polovnom autu: DOT, dubina šare, marka, neravnomerno trošenje, bezbednost, sezonske gume i pregovor cene.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#84cc16', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Renault Megane: kompakt koji traži proveru EDC-a, dizela i elektronike',
                'slug' => 'polovni-renault-megane-kompakt-koji-trazi-proveru-edc-a-dizela-i-elektronike',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Megane može biti racionalan kompakt sa dobrom cenom, ali kupac mora proveriti motor, EDC menjač, DPF, elektroniku, trap i servisni trag.',
                'content' => <<<'TEXT'
Renault Megane je jedan od onih polovnjaka koji često nude mnogo auta za manje novca od popularnijih nemačkih konkurenata. To može biti odlična računica za kupca koji želi kompaktnu klasu, udobnost i pristojnu opremu. Ali Megane ne treba kupiti samo zato što deluje povoljno. Kod njega konkretan primerak i servisni trag odlučuju mnogo više od prosečne tržišne reputacije.

Prva provera je motor. Dizel verzije mogu biti štedljive i dugotrajne kada imaju uredan servis, ali DPF, EGR, turbina, dizne i kratke gradske relacije moraju biti deo pregleda. Benzinske verzije treba gledati kroz potrošnju ulja, hladan start, servisni ritam i račune. Ako prodavac nema dokaze, cenu računaj kao da prvi servis tek dolazi.

Druga tema je EDC automatik i elektronika. Menjač mora raditi glatko hladan i zagrejan, bez trzaja, kašnjenja i neobičnih zvukova. Multimedija, kartica, senzori, klima, prozori i upozorenja na tabli moraju se proveriti bez žurbe. Sitna elektronska greška kod oglasa često postane dosadan trošak posle kupovine.

Treća provera je trap i enterijer. Megane se često koristi kao porodični ili službeni auto, pa volan, sedišta, pedale, gume i ogrebotine u gepeku mogu otkriti stvarnu kilometražu. Dobar Megane ima smisla kada je uredan, realno plaćen i proverljiv. Loš primerak nije povoljna alternativa, nego samo početak liste ulaganja.
TEXT,
                'highlights' => [
                    'Megane može biti dobra kupovina kada cena prati stanje, a ne samo slabiji imidž.',
                    'Dizel sistem, EDC menjač, kartica, senzori i klima moraju se proveriti pre kapare.',
                    'Kod službenih i porodičnih primeraka enterijer često govori više od kilometraže.',
                ],
                'tags' => ['Renault Megane', 'polovni kompakt', 'EDC', 'dizel', 'elektronika'],
                'meta_title' => 'Polovni Renault Megane: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Renault Megane modela: dizel, EDC menjač, DPF, EGR, elektronika, kartica, trap, enterijer i servisna istorija.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Seat Ibiza ili Volkswagen Polo: mali auto kada značka ne sme da digne cenu',
                'slug' => 'seat-ibiza-ili-volkswagen-polo-mali-auto-kada-znacka-ne-sme-da-digne-cenu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Ibiza i Polo dele sličnu logiku malog gradskog auta, ali prava kupovina zavisi od motora, opreme, trapa, servisne istorije i realne cene.',
                'content' => <<<'TEXT'
Seat Ibiza i Volkswagen Polo često se porede jer nude sličnu veličinu, poznatu tehniku i dobru svakodnevnu upotrebljivost. Polo obično drži jaču cenu zbog znaka i reputacije, dok Ibiza često nudi više opreme ili mlađi primerak za isti novac. To ne znači da je Ibiza uvek pametnija, niti da je Polo automatski sigurniji.

Ibiza ima smisla kada kupac želi malo više karaktera i bolji odnos godišta, opreme i cene. Treba proveriti motor, kvačilo, trap, gume, elektroniku, klimu i tragove gradske vožnje. Mali auto često živi na kratkim relacijama, preko ivičnjaka i u uskim parking prostorima, pa stanje karoserije i trapa nije sitnica.

Polo je često skuplji, ali može imati bolju kasniju prodaju i širu potražnju. Kod Pola treba biti oprezan sa primerima koji se prodaju samo na reputaciju. Ako nema servisne istorije, ako je enterijer umoran ili ako motor i menjač ne rade uredno, značka ne sme da opravda višu cenu.

Najbolja odluka je poređenje dva konkretna primerka. Ako Ibiza nudi jasniju istoriju, bolju opremu i manja ulaganja, razumniji je izbor. Ako Polo ima dokazivo stanje i realnu cenu, premium u maloj klasi može imati smisla. U oba slučaja probna vožnja i pregled vrede više od priče o pouzdanosti.
TEXT,
                'highlights' => [
                    'Ibiza često nudi bolji odnos godišta, opreme i cene.',
                    'Polo ima jaču reputaciju, ali ne sme biti skuplji bez boljeg stanja.',
                    'Kod oba mala auta gradski trap, kvačilo, gume i karoserija moraju se proveriti.',
                ],
                'tags' => ['Seat Ibiza', 'Volkswagen Polo', 'gradski auto', 'poređenje'],
                'meta_title' => 'Seat Ibiza ili Volkswagen Polo: šta kupiti',
                'meta_description' => 'Poređenje polovnih Seat Ibiza i Volkswagen Polo modela: motor, oprema, gradska vožnja, trap, servisna istorija, cena i kasnija prodaja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Lažna servisna knjižica: kako pečati mogu sakriti lošu istoriju polovnog auta',
                'slug' => 'lazna-servisna-knjizica-kako-pecati-mogu-sakriti-losu-istoriju-polovnog-auta',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Servisna knjižica je korisna samo kada se podaci poklapaju sa računima, VIN proverom, stanjem auta i logikom kilometraže.',
                'content' => <<<'TEXT'
Servisna knjižica kod polovnog auta može biti dobar dokaz, ali može biti i dobar rekvizit. Pečati, rukopis i uredne rubrike ne znače mnogo ako se ne poklapaju sa računima, elektronskom istorijom, VIN proverom i stanjem auta. Kupac treba da proverava logiku, ne samo da vidi da knjižica postoji.

Prvi signal je ritam servisa. Datumi, kilometraža, intervali i nazivi servisa treba da imaju kontinuitet. Ako auto navodno godinama prelazi tačno malo kilometara, a enterijer izgleda umorno, nešto ne stoji. Ako su svi pečati isti, rukopis isti ili nedostaju računi za veće servise, knjižica nije dovoljan dokaz.

Druga provera je poređenje sa autom. Volan, sedište, pedale, ručica menjača, gume, kočnice, farovi i stakla moraju pratiti priču iz dokumentacije. Kilometraža se ne proverava jednim papirom, nego skupom tragova. Dobar majstor često vidi nesklad pre nego što kupac stigne do pregovora.

Treća tema je komunikacija sa prodavcem. Traži VIN, račune, slike ranijih servisa i mogućnost provere u ovlašćenom ili specijalizovanom servisu. Ako prodavac tvrdi da je sve jasno, ali izbegava proveru, to je ozbiljan signal. Prava servisna istorija smanjuje rizik. Lažna samo odlaže problem do prve velike popravke.
TEXT,
                'highlights' => [
                    'Servisna knjižica vredi samo kada se poklapa sa računima, VIN-om i stanjem auta.',
                    'Isti pečati, čudan ritam kilometraže i bez računa za velike servise traže oprez.',
                    'Kilometraža se proverava kroz više tragova, ne samo kroz jednu knjižicu.',
                ],
                'tags' => ['servisna knjižica', 'VIN', 'kilometraža', 'provera vozila', 'istorija vozila'],
                'meta_title' => 'Lažna servisna knjižica: kako proveriti polovan auto',
                'meta_description' => 'Kako prepoznati lažnu servisnu knjižicu kod polovnog auta: pečati, računi, VIN, kilometraža, enterijer, servisna istorija i pitanja za prodavca.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volvo S60: limuzina koja mora opravdati bezbednost, automatiku i cenu delova',
                'slug' => 'polovni-volvo-s60-limuzina-koja-mora-opravdati-bezbednost-automatiku-i-cenu-delova',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Volvo S60 može biti mirna i bezbedna limuzina, ali kupac mora proveriti motor, automatski menjač, trap, elektroniku, uvoznu istoriju i cenu održavanja.',
                'content' => <<<'TEXT'
Volvo S60 privlači kupce koji žele nešto mirnije od nemačkih premium limuzina: dobru bezbednost, udobnost, ozbiljan enterijer i diskretniji imidž. Kao polovnjak može biti vrlo dobar izbor, ali samo kada je održavanje jasno. Volvo nije auto koji treba kupiti zato što je jeftiniji od sličnog BMW-a ili Audija, već zato što je konkretan primerak zdrav.

Prva provera je motor i menjač. Dizel verzije traže proveru DPF-a, EGR-a, turbine, hladnog starta i servisnog ritma. Benzinske verzije treba gledati kroz potrošnju, curenja i račune. Automatski menjač mora menjati glatko hladan i topao, bez trzaja i kašnjenja. Servis ulja nije detalj, nego važan dokaz.

Druga tema je elektronika i bezbednosna oprema. Senzori, radar, kamera, svetla, klima, sedišta, parking sistemi i upozorenja moraju raditi bez izgovora. Volvo često ima opremu koja je odlična kada radi, ali nije jeftina kada se popravlja. Zato dijagnostika treba da bude deo osnovnog pregleda.

Treća provera je cena delova i uvozna istorija. S60 može imati veliki broj autoput kilometara, posebno ako je uvezen. To nije problem ako su servisi uredni, ali jeste ako stanje enterijera i dokumentacija ne prate kilometražu. Dobar S60 vredi platiti, ali loš primerak brzo pokaže da bezbedan auto ne znači jeftin auto.
TEXT,
                'highlights' => [
                    'Volvo S60 ima smisla kada postoji jasna istorija i realno stanje, ne samo dobar imidž.',
                    'Automatik, dizel sistem, senzori i bezbednosna oprema moraju se proveriti dijagnostikom.',
                    'Cenu treba računati zajedno sa delovima, servisom i mogućom uvoznom kilometražom.',
                ],
                'tags' => ['Volvo S60', 'polovna limuzina', 'automatik', 'bezbednost', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Volvo S60: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Volvo S60 modela: motor, automatski menjač, DPF, elektronika, senzori, bezbednosna oprema, uvoz i delovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Privatni prodavac ili auto plac: gde polovan auto nosi manji rizik',
                'slug' => 'privatni-prodavac-ili-auto-plac-gde-polovan-auto-nosi-manji-rizik',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kupovina od privatnog prodavca i kupovina na placu imaju različite prednosti, ali rizik zavisi od dokumentacije, provere, garancije i ponašanja prodavca.',
                'content' => <<<'TEXT'
Kupci često pitaju da li je sigurnije kupiti auto od privatnog prodavca ili sa auto placa. Ne postoji odgovor koji važi za svaki slučaj. Privatni prodavac može ponuditi jasnu ličnu istoriju i realnu priču o autu. Plac može ponuditi veći izbor, lakšu logistiku i neku vrstu garancije. U oba slučaja rizik počinje kada kupac poveruje priči bez provere.

Kod privatnog prodavca prednost je mogućnost da vidiš kako je auto stvarno korišćen. Ako prodavac ima račune, servisnu istoriju, stare tehničke preglede i zna detalje o održavanju, to može biti dobar znak. Problem nastaje kada se privatna prodaja koristi samo kao maska za preprodaju ili kada prodavac izbegava VIN, probnu vožnju i majstora.

Kod auto placa prednost je izbor i brzina. Možeš porediti više automobila, lakše završiti papirologiju i nekad dobiti garanciju. Ali plac ne znači automatski bolji auto. Uvezeni primerci, kozmetičko sređivanje, nejasna kilometraža i generičke garancije moraju se proveriti jednako strogo kao kod privatne kupovine.

Najmanji rizik nosi prodavac koji dozvoljava proveru. Bilo da je privatnik ili plac, traži VIN, probnu vožnju, nezavisan pregled, jasnu dokumentaciju i pisani dogovor. Ako jedna strana deluje jeftinije, uračunaj šta ne znaš. Dobar prodavac olakšava proveru. Loš prodavac traži da veruješ na reč.
TEXT,
                'highlights' => [
                    'Nije presudno da li kupuješ od privatnika ili placa, nego koliko je provera otvorena.',
                    'Privatni prodavac može imati bolju priču o korišćenju, ali i sakriti preprodaju.',
                    'Plac nudi izbor i logistiku, ali garancija i uvozna istorija moraju se čitati pažljivo.',
                ],
                'tags' => ['privatni prodavac', 'auto plac', 'kupovina polovnjaka', 'garancija', 'provera'],
                'meta_title' => 'Privatni prodavac ili auto plac: gde kupiti polovan auto',
                'meta_description' => 'Poređenje kupovine polovnog auta od privatnog prodavca i auto placa: dokumentacija, VIN, garancija, probna vožnja, pregled, uvoz i rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Audi A6 C7: premium limuzina koja traži proveru automatika, dizela i elektronike',
                'slug' => 'polovni-audi-a6-c7-premium-limuzina-koja-trazi-proveru-automatika-dizela-i-elektronike',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Audi A6 C7 može biti ozbiljan premium polovnjak, ali samo kada su motor, automatski menjač, elektronika, trap i servisna istorija provereni bez prečica.',
                'content' => <<<'TEXT'
Audi A6 C7 deluje kao mnogo auta za novac kada ga uporediš sa cenom novog kompakta. Dobijaš prostor, komfor, izolaciju, premium enterijer i dobar osećaj na autoputu. Ali polovni A6 nije racionalna kupovina samo zato što je pao u cenu. Trošak održavanja ostaje premium, čak i kada cena oglasa deluje dostupno.

Prva provera je motor i servisna istorija. Dizel verzije traže proveru DPF-a, EGR-a, turbine, dizni, curenja, hladnog starta i servisnog ritma. Ako postoje računi za veliki servis, ulje, menjač i veće intervencije, to vredi više od opreme. Ako prodavac nema dokaze, kupac treba odmah da računa ozbiljan početni budžet.

Druga tema je automatski menjač i pogon. Menjač mora raditi glatko hladan i topao, bez trzaja, kašnjenja i vibracija. Quattro, trap, kočnice, gume i amortizeri moraju se proveriti na dizalici i u vožnji. Težak auto brzo pokaže koliko je prethodni vlasnik štedeo na delovima.

Treća provera je elektronika. MMI, klima, sedišta, parking senzori, kamera, farovi, instrument tabla i svi moduli moraju raditi bez slučajnih grešaka. Dobar A6 C7 je odličan za dug put i mirnu vožnju. Loš primerak je skup podsetnik da premium limuzina ne postaje jeftin auto samo zato što je polovna.
TEXT,
                'highlights' => [
                    'Audi A6 C7 treba kupiti samo ako istorija održavanja prati premium troškove.',
                    'Dizel sistem, automatik, quattro, trap i kočnice moraju se proveriti pre kapare.',
                    'Elektronika i oprema mogu biti velika prednost, ali i skup izvor sitnih kvarova.',
                ],
                'tags' => ['Audi A6 C7', 'premium limuzina', 'automatik', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Audi A6 C7: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Audi A6 C7 modela: dizel, automatski menjač, quattro, trap, elektronika, servisna istorija, oprema i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Fiat 500L ili Renault Scenic: porodični auto kada SUV nije jedino rešenje',
                'slug' => 'fiat-500l-ili-renault-scenic-porodicni-auto-kada-suv-nije-jedino-resenje',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => '500L i Scenic nude mnogo praktičnosti za manje novca od SUV-a, ali odluka zavisi od prostora, motora, elektronike, trapa i stvarne porodične rutine.',
                'content' => <<<'TEXT'
Fiat 500L i Renault Scenic često ostanu po strani jer tržište danas više voli SUV. Ipak, za porodicu koja traži prostor, preglednost, lak ulazak i dobar odnos cene i praktičnosti, ova dva modela mogu imati mnogo smisla. Pitanje nije koji izgleda modernije, nego koji primerak bolje rešava svakodnevicu bez skrivenih ulaganja.

Fiat 500L ima smisla za kupca koji želi jednostavan, pregledan i praktičan auto za grad i porodicu. Treba proveriti motor, kvačilo, trap, klimu, elektroniku i tragove gradske upotrebe. Velika kabina i visoko sedenje su prednost, ali ne opravdavaju primerak bez servisne istorije ili sa umornim enterijerom.

Renault Scenic često nudi više porodičnih detalja, udobnosti i fleksibilnosti, ali traži pažljivu proveru elektronike, kartice, parking senzora, klime i dizel sistema ako je u pitanju dCi. Kod Scenica je važno da oprema radi, jer upravo ona čini auto prijatnim za svakodnevnu porodičnu vožnju.

Izbor treba rešiti konkretnom potrebom. Za grad, jednostavnost i niže troškove 500L često ima prednost. Za duža putovanja, više udobnosti i fleksibilniji enterijer Scenic može biti bolji. U oba slučaja dobar monovolumen može biti pametniji od skupljeg SUV-a, ali samo kada stanje i istorija potvrđuju cenu.
TEXT,
                'highlights' => [
                    '500L i Scenic mogu biti racionalniji porodični izbor od skupljeg SUV-a.',
                    'Fiat 500L ima prednost u jednostavnosti i gradskoj preglednosti.',
                    'Renault Scenic nudi više porodične fleksibilnosti, ali traži strožu proveru elektronike.',
                ],
                'tags' => ['Fiat 500L', 'Renault Scenic', 'porodični auto', 'monovolumen', 'poređenje'],
                'meta_title' => 'Fiat 500L ili Renault Scenic: porodični polovnjak',
                'meta_description' => 'Poređenje polovnih Fiat 500L i Renault Scenic modela: prostor, motor, elektronika, trap, porodična upotreba, cena i alternativa SUV-u.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa korozijom na podu: kada rđa nije samo estetski problem',
                'slug' => 'auto-sa-korozijom-na-podu-kada-rdja-nije-samo-estetski-problem',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Korozija na podu i pragovima može otkriti lošu popravku, zimske uslove, curenje, slabo održavanje i skup problem za tehnički pregled.',
                'content' => <<<'TEXT'
Korozija na polovnom autu često se vidi tek kada se auto podigne. Spoljašnji lak može izgledati dobro, enterijer može biti očišćen, a fotografije mogu sakriti pragove, pod, nosače, rubove i mesta oko oslanjanja. Rđa nije samo estetski problem kada dođe do konstrukcije, kočionih cevi, nosača ili mesta koja trpe opterećenje.

Prva provera je dizalica. Pogledaj pragove, pod, rubove, zadnji most, nosače, pod gepeka, mesta oko dizalice, izduv, kočione cevi i šrafove. Površinska korozija nije isto što i duboka rđa, ali kupac ne treba sam da procenjuje granicu ako nema iskustva. Majstor i limar mogu brzo reći da li je problem kozmetički ili strukturni.

Druga tema je poreklo. Automobili iz područja sa mnogo soli, snega i vlage mogu imati više korozije, posebno ako su loše oprani i održavani. Curenje vode u kabini, vlažne obloge, loše popravljeni pragovi i sveže nanet zaštitni premaz preko prljavštine traže oprez.

Treća provera je računica. Popravka rđe može biti skuplja nego što deluje, naročito ako treba seći, variti, farbati i vraćati zaštitu. Ako korozija utiče na tehnički pregled ili sigurnost, popust nije dovoljan razlog za kupovinu. Nekad je najjeftinija odluka odustati pre kapare.
TEXT,
                'highlights' => [
                    'Korozija na podu i pragovima se ozbiljno proverava tek na dizalici.',
                    'Sveža zaštita odozdo može sakriti problem ako nema jasnog objašnjenja i računa.',
                    'Duboka rđa može uticati na tehnički pregled, bezbednost i vrednost auta.',
                ],
                'tags' => ['korozija', 'rđa', 'provera vozila', 'pragovi', 'tehnički pregled'],
                'meta_title' => 'Auto sa korozijom na podu: šta proveriti',
                'meta_description' => 'Kako proveriti koroziju na polovnom autu: pod, pragovi, nosači, kočione cevi, zaštita odozdo, tehnički pregled, popravka i kada odustati.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Citroen Berlingo: praktičan porodični van koji ne sme da sakrije težak radni život',
                'slug' => 'polovni-citroen-berlingo-praktican-porodicni-van-koji-ne-sme-da-sakrije-tezak-radni-zivot',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Berlingo je odličan kada treba prostor i praktičnost, ali kupac mora proveriti da li je auto bio porodični prevoz ili umorno dostavno vozilo.',
                'content' => <<<'TEXT'
Citroen Berlingo je jedan od najpraktičnijih polovnjaka za kupce kojima su važni prostor, klizna vrata, visok krov i jednostavna svakodnevna upotreba. Može biti odličan porodični auto, ali može biti i bivše dostavno ili radno vozilo koje je samo prepakovano za prodaju. Zato se Berlingo mora proveravati po stvarnom životu, ne po tome koliko je kabina velika.

Prva provera je namena iz prošlosti. Pogledaj gepek, pod, pragove, klizna vrata, obloge, sedišta, šrafove, krovni nosač i tragove tereta. Ako auto ima ogrebotine, udubljenja, umorne obloge i nejasnu istoriju, moguće je da je radio mnogo teže nego što kilometraža pokazuje.

Druga tema je motor i trap. Dizel verzije treba proveriti kroz DPF, EGR, turbinu, dizne, hladan start i servis ulja. Trap, gume, kočnice i amortizeri trpe mnogo kada se auto vozi natovaren. Probna vožnja treba da uključi neravnine, kočenje i slušanje zadnjeg dela.

Treća provera je porodična upotreba. Klizna vrata, klima, zadnja klupa, ISOFIX, gepek, police, senzori i preglednost moraju raditi za tvoju rutinu. Dobar Berlingo je izuzetno koristan polovnjak. Loš primerak je samo veliko prazno vozilo koje posle kupovine traži ulaganja u sve ono što je nosilo težak radni dan.
TEXT,
                'highlights' => [
                    'Berlingo treba proveriti da li je bio porodični auto ili umorno radno vozilo.',
                    'Klizna vrata, gepek, pod, obloge i zadnji trap često otkrivaju težak život.',
                    'Dizel sistem, kočnice, gume i amortizeri moraju se gledati kroz moguću vožnju pod teretom.',
                ],
                'tags' => ['Citroen Berlingo', 'porodični van', 'dostavno vozilo', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Citroen Berlingo: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Citroen Berlingo modela: porodična upotreba, dostavni rad, dizel, DPF, klizna vrata, gepek, trap i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa stranom dokumentacijom: kada papiri moraju biti jasniji od obećanja',
                'slug' => 'auto-sa-stranom-dokumentacijom-kada-papiri-moraju-biti-jasniji-od-obecanja',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Strana dokumentacija može biti uredan trag uvoza, ali kupac mora proveriti vlasništvo, odjavu, carinu, porez, homologaciju i prevod pre kapare.',
                'content' => <<<'TEXT'
Auto sa stranom dokumentacijom može biti sasvim uredna kupovina, ali samo kada su papiri jasni pre dogovora. Problem nastaje kada prodavac kaže da je sve lako, da se registracija završava brzo ili da će dokumenti stići naknadno. Kod uvoznog polovnjaka obećanje ne sme da zameni proveru vlasništva, odjave i troškova.

Prva provera su osnovni papiri. Treba videti stranu saobraćajnu, dokaz o odjavi, kupoprodajni ugovor ili fakturu, carinsku dokumentaciju, dokaz o plaćenim obavezama i VIN koji se poklapa na vozilu i dokumentima. Ako se ime prodavca ne poklapa sa tokom papira, pitaj ko stvarno prodaje auto i po kom osnovu.

Druga tema su troškovi i rokovi. Homologacija, prevod, carina, porez, registracija, tehnički pregled i eventualni nedostajući dokument mogu promeniti računicu. Cena oglasa nije puna cena ako auto još nije spreman za registraciju. Pre kapare napiši šta je uključeno, šta nije i ko snosi rizik ako papir fali.

Treća provera je istorija. Strani papiri ne govore sami da je auto dobar. Proveri servisnu istoriju, tehničke zapise, kilometražu, štetu, vlasništvo i da li postoje ograničenja. Ako prodavac žuri sa kaparom pre dokumentacije, to nije dobra prilika nego signal da kupac treba usporiti.
TEXT,
                'highlights' => [
                    'Kod strane dokumentacije prvo proveri vlasništvo, odjavu, fakturu i VIN.',
                    'Cena nije kompletna dok nisu jasni carina, porez, homologacija, prevod i registracija.',
                    'Kapara nema smisla ako dokumenti tek treba da stignu ili nisu usklađeni.',
                ],
                'tags' => ['strana dokumentacija', 'uvoz auta', 'homologacija', 'carina', 'registracija'],
                'meta_title' => 'Auto sa stranom dokumentacijom: šta proveriti',
                'meta_description' => 'Kako proveriti auto sa stranom dokumentacijom: vlasništvo, odjava, VIN, faktura, carina, porez, homologacija, prevod, registracija i kapara.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volkswagen Touran: porodični monovolumen koji traži proveru dizela, DSG-a i kliznih sedišta',
                'slug' => 'polovni-volkswagen-touran-porodicni-monovolumen-koji-trazi-proveru-dizela-dsg-a-i-kliznih-sedista',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Touran može biti odličan porodični auto za kupce kojima SUV nije neophodan, ali samo kada su dizel, DSG, sedišta, vrata i servisna istorija provereni bez prečica.',
                'content' => <<<'TEXT'
Volkswagen Touran je porodični auto koji često ima više stvarne upotrebljivosti od mnogih polovnih SUV-ova. Visok krov, dobra preglednost, velika kabina, pomična sedišta i veliki gepek čine ga veoma praktičnim za porodicu, posao i duža putovanja. Ali baš zato što je praktičan, Touran često iza sebe ima težak život: mnogo gradske vožnje, dečja sedišta, kratke relacije, službenu upotrebu ili vožnju pod teretom.

Prva provera je motor i menjač. Dizel verzije treba gledati kroz hladan start, DPF, EGR, turbinu, dizne, curenja i servis ulja. DSG menjač mora menjati glatko hladan i topao, bez trzaja, kašnjenja i vibracija. Ako nema dokaza o servisu menjača, kupac mora računati preventivni servis ili veći rizik.

Druga tema je enterijer. Touran se kupuje zbog praktičnosti, pa treba proveriti svako sedište, mehanizme pomeranja, preklapanje, ISOFIX, vrata, bravu gepeka, klimu, ventilaciju pozadi i tragove vlage. Umoran enterijer nije samo estetski problem. On često govori koliko je auto zaista radio.

Treća provera je trap i porodična rutina. Probna vožnja treba da uključi neravnine, kočenje, parkiranje i vožnju preko ležećih policajaca. Dobar Touran može biti pametniji od skupljeg SUV-a. Loš primerak samo izgleda racionalno dok ne počnu ulaganja u menjač, dizel sistem i potrošene porodične detalje.
TEXT,
                'highlights' => [
                    'Touran ima smisla kada stvarna praktičnost vredi više od SUV imidža.',
                    'Dizel sistem i DSG moraju imati jasne servisne tragove i dobru probnu vožnju.',
                    'Sedišta, vrata, klima, ISOFIX i enterijer često otkrivaju koliko je auto radio.',
                ],
                'tags' => ['Volkswagen Touran', 'porodični monovolumen', 'DSG', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Volkswagen Touran: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Volkswagen Touran modela: dizel, DSG, sedišta, klima, enterijer, trap, porodična upotreba, servis i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Toyota Prius ili Hyundai Ioniq: polovni hibrid kada grad i potrošnja odlučuju',
                'slug' => 'toyota-prius-ili-hyundai-ioniq-polovni-hibrid-kada-grad-i-potrosnja-odlucuju',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Prius i Ioniq mogu biti vrlo štedljivi polovnjaci za grad, ali odluka zavisi od baterije, kočnica, servisne istorije, prostora i realne cene primerka.',
                'content' => <<<'TEXT'
Toyota Prius i Hyundai Ioniq su logični kandidati za kupca koji želi polovni hibrid, nisku potrošnju i mirnu gradsku vožnju bez dizel rizika. Oba modela imaju reputaciju racionalne kupovine, ali reputacija nije dovoljna. Kod hibrida se kupuje stanje kompletnog sistema, a ne samo obećanje male potrošnje.

Prius ima jači hibridni ugled i često drži cenu bolje. To je prednost pri kasnijoj prodaji, ali i razlog da kupac ne plati previše samo zbog imena. Treba proveriti hibridnu bateriju, 12V bateriju, kočnice, trap, klimu i realnu istoriju korišćenja. Automobil koji je vozio mnogo gradskih kilometara može spolja izgledati mirno, a da mu unutrašnjost i mehanika već traže ulaganja.

Ioniq često nudi moderniji osećaj, dobru aerodinamiku i zanimljiv odnos cene i opreme. Kod njega treba proveriti bateriju, softver, kočnice, menjač, servisnu istoriju i da li je auto korišćen kao taksi, službeni ili rent-a-car primerak. Dobra oprema ne treba da sakrije umoran primerak.

Ako ti je kasnija prodaja i dokazana reputacija najvažnija, Prius ima prednost. Ako želiš bolji odnos cene, opreme i modernijeg enterijera, Ioniq može biti bolji izbor. U oba slučaja dijagnostika hibridnog sistema i jasna servisna istorija vrede više od prosečne potrošnje napisane u oglasu.
TEXT,
                'highlights' => [
                    'Prius ima jaču reputaciju, ali ne treba plaćati ime bez dijagnostike baterije.',
                    'Ioniq može ponuditi bolju opremu za novac, ali traži proveru upotrebe i servisa.',
                    'Kod oba modela proveri hibridni sistem, 12V bateriju, kočnice, trap i istoriju.',
                ],
                'tags' => ['Toyota Prius', 'Hyundai Ioniq', 'polovni hibrid', 'poređenje', 'gradska vožnja'],
                'meta_title' => 'Toyota Prius ili Hyundai Ioniq: polovni hibrid vodič',
                'meta_description' => 'Poređenje polovnih Toyota Prius i Hyundai Ioniq hibrida: baterija, potrošnja, kočnice, servis, oprema, gradska vožnja, cena i kasnija prodaja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa sumnjivim zvukom turbine: kada zvižduk postaje skup račun',
                'slug' => 'auto-sa-sumnjivim-zvukom-turbine-kada-zvizduk-postaje-skup-racun',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Zviždanje turbine, dim, slab odziv i tragovi ulja mogu biti prvi signal da je jeftin dizel zapravo kupovina sa velikim početnim ulaganjem.',
                'content' => <<<'TEXT'
Turbina kod polovnog auta ne sme se procenjivati samo po tome da li motor trenutno vuče. Mnogi problemi počinju tiho: blag zvižduk, čudan zvuk pri ubrzanju, masni tragovi oko creva, dim pri dodavanju gasa ili osećaj da auto čas ide dobro, čas deluje tromo. Takvi signali nisu razlog za paniku, ali jesu razlog za ozbiljnu proveru pre kapare.

Prva provera je hladan start i vožnja pod opterećenjem. Obrati pažnju na zviždanje, zavijanje, dim, trzaje, lampice, gubitak snage i miris ulja. Kratka vožnja oko placa nije dovoljna. Auto treba voziti kroz grad, otvoren put i ubrzanje iz nižih obrtaja, uz dijagnostiku pre i posle vožnje.

Druga tema su uzroci. Turbina može stradati zbog lošeg ulja, preskakanja servisa, zapušenog DPF-a, problema sa EGR-om, loše mape, curenja na usisu ili agresivne vožnje hladnog motora. Ako se popravi samo turbina, a uzrok ostane, novi kvar može doći brzo.

Treća provera je računica. Polovan dizel sa lošom turbinom nije dobar samo zato što je cena spuštena. Treba uračunati turbinu, rad, ulje, creva, DPF, EGR i moguće posledice. Ako prodavac kaže da se turbina samo malo čuje, neka cena i pregled potvrde tu priču. U suprotnom, bolje je odustati nego kupiti prvi veliki račun.
TEXT,
                'highlights' => [
                    'Zviždanje, dim, slab odziv i ulje oko usisa traže ozbiljnu proveru turbine.',
                    'Dijagnostika i probna vožnja pod opterećenjem moraju se uraditi pre kapare.',
                    'Kvar turbine često ima uzrok u servisu, DPF-u, EGR-u, ulju ili lošoj mapi.',
                ],
                'tags' => ['turbina', 'dizel', 'provera vozila', 'DPF', 'EGR', 'troškovi'],
                'meta_title' => 'Auto sa sumnjivim zvukom turbine: šta proveriti',
                'meta_description' => 'Kako proveriti turbinu kod polovnog auta: zviždanje, dim, gubitak snage, ulje, DPF, EGR, dijagnostika, probna vožnja i računica popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Opel Insignia B: velika limuzina koja traži proveru dizela i elektronike',
                'slug' => 'polovni-opel-insignia-b-velika-limuzina-koja-trazi-proveru-dizela-i-elektronike',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Insignia B deluje kao mnogo auta za novac, ali kupac mora proveriti motor, menjač, elektroniku, trap, uvoznu istoriju i realnu kilometražu.',
                'content' => <<<'TEXT'
Opel Insignia B privlači kupce koji žele veliku limuzinu ili karavan, mnogo opreme i ozbiljan osećaj na autoputu bez premium cene. Na papiru deluje vrlo racionalno: udobna je, prostrana, dobro izgleda i često nudi bogatu opremu. Kao polovnjak, međutim, mora opravdati stanje jer veliki auto sa jeftinom cenom često krije veliku kilometražu ili zaostala ulaganja.

Prva provera je motor. Dizel verzije treba gledati kroz hladan start, DPF, EGR, turbinu, dizne, AdBlue ako ga ima, curenja i servis ulja. Benzinske verzije treba proveriti kroz potrošnju, rad motora, servisnu istoriju i eventualna curenja. U oba slučaja kilometraža mora imati logiku sa enterijerom, volanom, sedištem i računima.

Druga tema je elektronika i oprema. Infotainment, klima, senzori, kamera, adaptivna svetla, sedišta, grejanje, parking sistemi i svi moduli moraju raditi bez slučajnih upozorenja. Bogata oprema je prednost samo ako radi. Ako prodavac kaže da je sitnica, kupac treba da proveri koliko ta sitnica košta.

Treća provera je trap i uvozna istorija. Insignia često ima mnogo autoput kilometara, što nije problem ako je održavanje uredno. Problem je kada se kilometraža ne poklapa sa stanjem i dokumentacijom. Dobar primerak je udoban i sposoban porodični auto. Loš primerak može brzo postati skupa velika limuzina koju je teško prodati bez dodatnih ulaganja.
TEXT,
                'highlights' => [
                    'Insignia B nudi mnogo prostora i opreme, ali stanje mora opravdati cenu.',
                    'Dizel sistem, elektronika, senzori i klima treba da prođu detaljnu proveru.',
                    'Uvozna kilometraža mora se porediti sa dokumentacijom i potrošenošću enterijera.',
                ],
                'tags' => ['Opel Insignia B', 'polovna limuzina', 'dizel', 'elektronika', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Opel Insignia B: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Opel Insignia B modela: dizel, DPF, EGR, elektronika, oprema, trap, uvozna istorija, kilometraža i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa previše vlasnika: kada broj u saobraćajnoj menja rizik kupovine',
                'slug' => 'auto-sa-previse-vlasnika-kada-broj-u-saobracajnoj-menja-rizik-kupovine',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Više prethodnih vlasnika nije automatski problem, ali kupac mora razumeti zašto je auto često menjao ruke i da li dokumentacija prati svaku promenu.',
                'content' => <<<'TEXT'
Broj prethodnih vlasnika kod polovnog auta ne govori celu istinu, ali je važan signal. Auto sa jednim vlasnikom može biti zapušten. Auto sa više vlasnika može biti uredan. Ipak, ako je vozilo često menjalo ruke, kupac treba da razume zašto. Nekad je razlog normalna zamena auta, a nekad skriven kvar, loša popravka, neisplativo održavanje ili problem sa papirima.

Prva provera je dokumentacija. Saobraćajna, kupoprodajni ugovori, računi, servisna istorija, tehnički pregledi i VIN treba da naprave logičan trag. Ako se vlasnici smenjuju brzo, a servisni računi nestaju baš u periodu kada je auto menjao ruke, treba usporiti.

Druga tema je stanje. Više vlasnika često znači različite navike održavanja. Jedan je mogao ulagati na vreme, drugi odlagati servis, treći pripremiti auto samo za prodaju. Zato stanje enterijera, guma, kočnica, trapa, tečnosti i elektronike mora da se čita zajedno sa papirima.

Treća provera je razgovor sa prodavcem. Pitaj koliko dugo je auto kod njega, zašto ga prodaje, šta je radio od kupovine i koje račune ima. Ako je odgovor maglovit, a auto je više puta preprodavan, cena mora nositi taj rizik. Najmanji problem je broj vlasnika. Veći problem je kada nijedan trag ne objašnjava život automobila.
TEXT,
                'highlights' => [
                    'Više vlasnika nije automatski razlog za odustajanje, ali traži jasniji trag dokumenata.',
                    'Česte promene vlasništva treba povezati sa servisima, tehničkim pregledima i VIN istorijom.',
                    'Kupac treba da plati stanje i dokaze, ne samo priču da je auto dobro čuvan.',
                ],
                'tags' => ['prethodni vlasnici', 'saobraćajna', 'kupovina polovnjaka', 'dokumentacija', 'VIN'],
                'meta_title' => 'Auto sa previše vlasnika: šta proveriti pre kupovine',
                'meta_description' => 'Kako proceniti polovan auto sa više prethodnih vlasnika: saobraćajna, dokumentacija, VIN, servisna istorija, tehnički pregledi, preprodaja i rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Škoda Scala: kompakt koji traži proveru TSI-a, trapa i opreme',
                'slug' => 'polovni-skoda-scala-kompakt-koji-trazi-proveru-tsi-a-trapa-i-opreme',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Scala može biti racionalan kompakt za porodicu i posao, ali kupac mora proveriti motor, trap, elektroniku, servisnu istoriju i realnu cenu u odnosu na Octaviju.',
                'content' => <<<'TEXT'
Škoda Scala deluje kao mirnija kupovina za kupce koji žele prostor, moderan enterijer i niže troškove od većih polovnih modela. Nije Octavia, ali nudi dovoljno mesta za porodicu, prtljažnik koji rešava svakodnevicu i jednostavniji osećaj u vožnji. Kao polovnjak ima smisla kada cena prati stanje, a ne samo činjenicu da je auto relativno nov.

Prva provera je motor i servis. TSI verzije treba gledati kroz hladan start, rad u leru, potrošnju ulja, curenja, servisne intervale i račune. Dizel ima smisla samo ako je vožen na dužim relacijama i ako DPF, EGR i turbina ne nose skrivenu cenu. Kod svakog primerka bitnije je šta piše u računima nego koliko opreme ima.

Druga tema je trap i gradski život. Scala često radi kao porodični, službeni ili gradski auto. Proveri gume, kočnice, amortizere, letvu volana, neravnine, parking tragove i stanje enterijera. Auto koji izgleda novo na fotografijama može imati mnogo kratkih relacija, ivičnjaka i sitnih udaraca iza sebe.

Treća provera je odnos cene i alternative. Ako je Scala blizu cene Octavije, mora imati bolju istoriju, manje rizika ili mlađe godište. Ako je primetno jeftinija, proveri zašto. Dobar primerak je racionalan kompakt. Loš primerak je samo skuplji mali auto koji se kupuje zato što deluje bezbedno na papiru.
TEXT,
                'highlights' => [
                    'Scala ima smisla kada cena prati stanje, servisnu istoriju i stvarnu upotrebu.',
                    'TSI motor, dizel sistem, trap i gradski tragovi moraju se proveriti pre kapare.',
                    'Poredi je sa Octavijom i drugim kompaktima, ne samo po godištu i opremi.',
                ],
                'tags' => ['Škoda Scala', 'polovni kompakt', 'TSI', 'kupovina polovnjaka', 'trap'],
                'meta_title' => 'Polovni Škoda Scala: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovne Škoda Scala: TSI, dizel, trap, oprema, servisna istorija, gradska vožnja, cena i poređenje sa Octavijom.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Peugeot 5008 ili Škoda Kodiaq: sedam sedišta kada porodica traži više od gepeka',
                'slug' => 'peugeot-5008-ili-skoda-kodiaq-sedam-sedista-kada-porodica-trazi-vise-od-gepeka',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => '5008 i Kodiaq nude sedam sedišta i ozbiljan porodični prostor, ali odluka zavisi od motora, menjača, trapa, elektronike i stvarne potrebe za trećim redom.',
                'content' => <<<'TEXT'
Peugeot 5008 i Škoda Kodiaq su dva česta odgovora kada porodica preraste kompaktni SUV, karavan ili monovolumen. Oba mogu ponuditi sedam sedišta, veliki gepek i dobar komfor na putu, ali ih ne treba kupovati samo zato što izgledaju kao logično rešenje za svaku porodičnu situaciju. Sedam sedišta ima smisla samo ako se stvarno koriste.

Peugeot 5008 često privlači kupce dizajnom, enterijerom i fleksibilnim rasporedom. Treba proveriti elektroniku, ekran, klimu, senzore, trap i motor. Kod dizela gledaj DPF, EGR, AdBlue ako ga ima, turbinu i servisnu istoriju. Kod benzinca posebno proveri servisni ritam i poznate slabosti konkretne motorizacije.

Škoda Kodiaq deluje ozbiljnije i robusnije, sa jakim porodičnim imidžom i boljom kasnijom prodajom. Ali DSG, 4x4, veće gume, trap, kočnice i skupa oprema mogu promeniti računicu. Kodiaq nije automatski bolja kupovina ako je skuplji primerak umorniji ili slabije dokumentovan.

Prava odluka zavisi od rutine. Ako ti je važniji dizajn, fleksibilnost i bolja cena, 5008 može biti vrlo racionalan. Ako želiš mirniju kasniju prodaju i čvršći osećaj, Kodiaq ima prednost. U oba slučaja testiraj treći red, dečja sedišta, gepek sa podignutim sedištima, klimu pozadi i troškove prvih ulaganja.
TEXT,
                'highlights' => [
                    'Sedam sedišta kupuj samo ako treći red stvarno rešava svakodnevnu potrebu.',
                    '5008 traži proveru elektronike, motora i fleksibilnosti enterijera.',
                    'Kodiaq traži strogu proveru DSG-a, 4x4 pogona, trapa i skupljih potrošnih delova.',
                ],
                'tags' => ['Peugeot 5008', 'Škoda Kodiaq', 'sedam sedišta', 'porodični SUV', 'poređenje'],
                'meta_title' => 'Peugeot 5008 ili Škoda Kodiaq: polovni SUV sa 7 sedišta',
                'meta_description' => 'Poređenje polovnih Peugeot 5008 i Škoda Kodiaq modela: sedam sedišta, gepek, dizel, DSG, elektronika, 4x4, porodična upotreba i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Čudan miris u kabini polovnog auta: kada nos otkriva vlagu, dim ili lošu popravku',
                'slug' => 'cudan-miris-u-kabini-polovnog-auta-kada-nos-otkriva-vlagu-dim-ili-losu-popravku',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Miris vlage, dima, goriva, hemije ili ustajale klime može otkriti curenje, poplavu, loše čišćenje, kvar klime ili skriven problem posle udesa.',
                'content' => <<<'TEXT'
Čudan miris u kabini polovnog auta nije sitnica koju treba pokriti mirisnom jelkicom. Kabina pamti vlagu, dim, kućne ljubimce, prosutu tečnost, buđ, curenje grejanja, poplavu, loš servis klime i agresivno dubinsko pranje pred prodaju. Ako miris postoji, treba razumeti odakle dolazi pre dogovora o ceni.

Prva provera je vlaga. Podigni patosnice, pipni tepih, proveri gepek, rezervni točak, donje ivice vrata, pragove, nebo, stakla i prostor oko klime. Magljenje stakala, mokar tepih i miris buđi mogu značiti curenje, zapušene odvode ili ozbiljniju istoriju vode u vozilu.

Druga tema je dim i hemija. Miris cigareta se teško uklanja, a jak miris sredstva za čišćenje može značiti da prodavac pokušava da sakrije problem. Miris goriva, izduvnih gasova ili rashladne tečnosti traži mehaničku proveru, ne pregovaranje naslepo.

Treća provera je klima. Uključi ventilaciju, grejanje, recirkulaciju i klimu na različitim režimima. Loš miris iz ventilacije može biti samo servis klime, ali može ukazati i na vlagu, zapušen odvod ili problem sa grejačem. Ako kabina ne miriše normalno, ne pretpostavljaj da će se rešiti posle jednog čišćenja.
TEXT,
                'highlights' => [
                    'Miris vlage, dima, goriva ili hemije treba tretirati kao signal za dodatnu proveru.',
                    'Tepisi, gepek, odvodi, klima i donje ivice vrata često otkrivaju skriven uzrok.',
                    'Dubinsko pranje ne rešava problem ako je uzrok curenje, buđ ili mehanički kvar.',
                ],
                'tags' => ['miris u kabini', 'vlaga', 'klima', 'provera vozila', 'polovan auto'],
                'meta_title' => 'Čudan miris u kabini polovnog auta: šta proveriti',
                'meta_description' => 'Kako proveriti čudan miris u kabini polovnog auta: vlaga, dim, gorivo, klima, buđ, tepisi, gepek, curenje, poplava i dubinsko pranje.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Honda Civic 10: kompakt koji traži proveru turbobenzinca, CVT-a i limarskog stanja',
                'slug' => 'polovni-honda-civic-10-kompakt-koji-trazi-proveru-turbobenzinca-cvt-a-i-limarskog-stanja',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Civic 10 može biti odličan izbor za vozača koji želi više od prosečnog kompakta, ali stanje motora, menjača, trapa i karoserije mora opravdati cenu.',
                'content' => <<<'TEXT'
Honda Civic 10 privlači kupce koji žele drugačiji kompakt: više prostora nego što izgled sugeriše, dobar osećaj u vožnji, atraktivan dizajn i reputaciju Honde. Kao polovnjak može biti vrlo zanimljiv, ali nije auto koji treba kupiti samo zato što ima japanski znak. Konkretan primerak mora dokazati da je održavan i vožen razumno.

Prva provera je motor. Turbobenzinci traže uredan servis ulja, miran hladan start, normalan rad pod opterećenjem i proveru eventualnih curenja ili čudnih zvukova. Ako je auto vožen kratkim relacijama ili agresivno, reputacija marke ne briše rizik. Dizel verzije treba gledati kroz DPF, EGR, turbinu i kilometražu.

Druga tema je menjač i vožnja. Manuelni menjač proveri kroz kvačilo, sinhrone i rad u svim brzinama. CVT mora raditi glatko, bez trzaja, zavijanja i neobičnog kašnjenja. Probna vožnja treba da uključi grad, otvoren put, neravnine i kočenje.

Treća provera je karoserija. Civic 10 ima upečatljiv oblik, pa loše popravljeni paneli, branici, farovi i zazori mogu brzo otkriti prethodnu štetu. Dobar Civic 10 vredi više od prosečnog kompakta. Loš primerak ne treba platiti kao dobar samo zato što je redak na tržištu.
TEXT,
                'highlights' => [
                    'Civic 10 ima smisla kada stanje prati reputaciju i višu tržišnu cenu.',
                    'Turbobenzinac, dizel, manuelni menjač i CVT traže različitu probnu vožnju.',
                    'Karoserija, zazori, farovi i branici moraju se proveriti zbog mogućih loših popravki.',
                ],
                'tags' => ['Honda Civic 10', 'polovni kompakt', 'CVT', 'turbobenzinac', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Honda Civic 10: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Honda Civic 10 modela: turbobenzinac, dizel, CVT, manuelni menjač, trap, karoserija, servis i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa lizinga iz inostranstva: kada uredna istorija ne govori sve o korišćenju',
                'slug' => 'auto-sa-lizinga-iz-inostranstva-kada-uredna-istorija-ne-govori-sve-o-koriscenju',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Lizing vozilo iz inostranstva može imati uredne servise, ali kupac mora proveriti namenu, kilometražu, opremu, štete, dokumentaciju i stvarno stanje.',
                'content' => <<<'TEXT'
Auto sa lizinga iz inostranstva često deluje kao sigurna kupovina jer ima urednu dokumentaciju, servisne zapise i poznat tok vlasništva. To jeste prednost, ali nije cela slika. Lizing vozilo može biti pažljivo održavan službeni auto, ali može biti i automobil koji je svaki dan prelazio mnogo kilometara, vozilo više vozača i završio ugovor sa minimalnim ulaganjima pred vraćanje.

Prva provera je namena. Pitaj da li je auto bio službeni, flotni, rent-a-car, menadžerski ili privatni lizing. Ista kilometraža ne znači isto korišćenje. Autoput kilometri, gradske gužve, kratke relacije i više vozača ostavljaju različite tragove na enterijeru, trapu, gumama, kočnicama i kvačilu ili automatskom menjaču.

Druga tema je dokumentacija. Servisna istorija, faktura, odjava, izvozna dokumenta, VIN, izveštaji o šteti i tehnički pregledi moraju imati kontinuitet. Uredan servis ne znači da nije bilo karoserijskih popravki ili da je oprema kompletna. Proveri i da li su svi ključevi, kodovi, dodatna oprema i knjižice prisutni.

Treća provera je računica. Lizing auto može biti dobra kupovina ako cena priznaje kilometražu i namenu. Ako se prodaje kao skoro privatno korišćen vozilo, a stanje govori drugačije, treba pregovarati ili odustati. Uredna istorija smanjuje rizik, ali ga ne briše bez pregleda konkretnog primerka.
TEXT,
                'highlights' => [
                    'Lizing istorija je prednost samo kada se zna namena i način korišćenja vozila.',
                    'Servisni zapisi moraju se povezati sa VIN-om, štetama, odjavom i izvozom.',
                    'Flotna upotreba, više vozača i velika kilometraža moraju biti uračunati u cenu.',
                ],
                'tags' => ['lizing', 'uvoz auta', 'službeni auto', 'flotno vozilo', 'dokumentacija'],
                'meta_title' => 'Auto sa lizinga iz inostranstva: šta proveriti',
                'meta_description' => 'Kako proveriti auto sa lizinga iz inostranstva: servisna istorija, namena, flotna upotreba, VIN, štete, dokumentacija, kilometraža i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Renault Kadjar: crossover koji traži proveru dCi-a, TCe-a i elektronike',
                'slug' => 'polovni-renault-kadjar-crossover-koji-trazi-proveru-dci-a-tce-a-i-elektronike',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kadjar može biti udoban i racionalan porodični crossover, ali kupac mora proveriti motor, elektroniku, trap, servisnu istoriju i stvarnu cenu u odnosu na Qashqai.',
                'content' => <<<'TEXT'
Renault Kadjar je polovni crossover koji često ulazi u uži izbor kupaca koji žele viši položaj sedenja, solidan komfor i razumnu cenu bez premium ambicija. Deli mnogo logike sa Nissan Qashqai modelom, ali to ne znači da svaki primerak automatski nosi mirnu kupovinu. Kod Kadjara je najvažnije da stanje, servis i cena imaju smisla zajedno.

Prva provera je motor. dCi dizeli mogu biti vrlo štedljivi i prijatni na dužem putu, ali traže proveru DPF-a, EGR-a, turbine, dizni, hladnog starta i servisnih intervala. TCe benzinci traže pažnju oko potrošnje ulja, rada u leru, curenja, servisa i načina vožnje. Ako prodavac nema račune, kupac treba da računa veći početni budžet.

Druga tema je elektronika i oprema. Kartica, start-stop, ekran, klima, kamera, parking senzori, električni podizači, svetla i upozorenja na tabli moraju raditi bez izgovora. Kadjar često ima dovoljno opreme da deluje skuplje nego što jeste, ali baš ta oprema može otkriti zapušten primerak.

Treća provera je trap i svakodnevna upotreba. Crossover koji je vozio grad, ivičnjake i loše puteve može imati umorne amortizere, gume, kočnice i letvu volana. Dobar Kadjar je miran porodični auto sa dobrom cenom. Loš primerak je Qashqai alternativa samo na fotografijama, dok račun za ulaganja brzo pojede razliku.
TEXT,
                'highlights' => [
                    'Kadjar ima smisla kada servisna istorija potvrđuje stanje, a cena ne glumi skuplji SUV.',
                    'dCi, TCe, elektronika i trap moraju se proveriti pre kapare i pregovora.',
                    'Poredi ga sa Qashqai modelom, ali odlučuj po konkretnom primerku i računima.',
                ],
                'tags' => ['Renault Kadjar', 'polovni crossover', 'dCi', 'TCe', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Renault Kadjar: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Renault Kadjar modela: dCi, TCe, elektronika, oprema, trap, servisna istorija, cena i poređenje sa Qashqai.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Opel Grandland ili Peugeot 3008: isti koreni, različita računica polovnjaka',
                'slug' => 'opel-grandland-ili-peugeot-3008-isti-koreni-razlicita-racunica-polovnjaka',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Grandland i 3008 dele tehničku osnovu, ali kupac treba da bira po motoru, elektronici, enterijeru, servisnoj istoriji i ceni, ne po dizajnu iz oglasa.',
                'content' => <<<'TEXT'
Opel Grandland i Peugeot 3008 često se posmatraju kao slična kupovina jer dele tehničku osnovu, motore i deo servisne logike. Ipak, kao polovnjaci ostavljaju različit utisak. Peugeot obično privlači dizajnom i enterijerom, dok Grandland često deluje uzdržanije i jednostavnije. Nijedan pristup nije automatski bolji ako konkretan primerak nema dobru istoriju.

Peugeot 3008 ima jači vizuelni karakter, moderniji kokpit i često bogatiju opremu. To može biti prednost pri svakodnevnoj vožnji i kasnijoj prodaji, ali traži detaljnu proveru ekrana, elektronike, senzora, klime i svih komandi. Kod motora treba gledati servisni ritam, DPF, EGR, AdBlue kod dizela i poznate slabosti benzinskih verzija.

Opel Grandland je često racionalniji izbor za kupca koji ne želi da plati dodatno zbog dizajna. Može imati mirniji enterijer i dobru opremu za novac, ali ne sme se kupiti samo zato što je jeftiniji. Isti osnovni rizici ostaju: motor, menjač, elektronika, trap, gume, kočnice i dokumentacija.

Ako želiš efektniji enterijer i lakšu kasniju prodaju, 3008 ima prednost. Ako želiš nižu cenu i diskretniji auto, Grandland može biti bolja računica. Prava odluka je ona u kojoj servisni računi, probna vožnja i pregled kod majstora obore ili potvrde razliku u ceni.
TEXT,
                'highlights' => [
                    '3008 i Grandland dele tehničku logiku, ali tržište ih vrednuje različito.',
                    'Peugeot traži posebno pažljivu proveru elektronike, opreme i motora.',
                    'Grandland može biti bolja kupovina samo ako niža cena ne krije slabiju istoriju.',
                ],
                'tags' => ['Opel Grandland', 'Peugeot 3008', 'poređenje', 'polovni SUV', 'kupovina polovnjaka'],
                'meta_title' => 'Opel Grandland ili Peugeot 3008: polovni SUV poređenje',
                'meta_description' => 'Poređenje polovnih Opel Grandland i Peugeot 3008 modela: motori, elektronika, oprema, enterijer, servisna istorija, cena i rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Oštećene felne na polovnom autu: kada udarac u rupu otkriva skuplji trap',
                'slug' => 'ostecene-felne-na-polovnom-autu-kada-udarac-u-rupu-otkriva-skuplji-trap',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Izgrebane, krive ili varene felne nisu samo estetski minus, već signal da treba proveriti gume, trap, glavčine, kočnice, geometriju i istoriju udaraca.',
                'content' => <<<'TEXT'
Oštećene felne na polovnom autu mnogi kupci tretiraju kao kozmetiku, ali one često govore više od ogrebotine na braniku. Dubok udarac u rupu, ivičnjak ili prepreku može iskriviti felnu, oštetiti gumu, pomeriti trap, opteretiti ležaj i napraviti problem koji se vidi tek pri većoj brzini ili na optici.

Prva provera je vizuelna. Pogledaj ivice felni, tragove varenja, pukotine, sveže farbanje, neravnomerno trošenje guma i razliku između točkova. Jedna nova guma pored tri stare može značiti normalnu zamenu, ali može značiti i udarac koji je rešavan najjeftinije moguće.

Druga tema je probna vožnja. Auto ne sme vući u stranu, tresti volan, brujati, skakati preko neravnina ili pokazivati čudno ponašanje pri kočenju. Vožnja treba da uključi spor prelazak preko neravnina, brže ubrzanje, kočenje i deo puta na kom se oseća balans točkova.

Treća provera je dizalica i optika trapa. Felna se može zameniti, ali kriv nosač, amortizer, glavčina, viljuška ili loša geometrija menjaju celu računicu. Ako prodavac kaže da su felne samo malo izgrebane, pregled treba da potvrdi da je problem stvarno samo estetski. U suprotnom, popust mora pokriti punu proveru i realne delove.
TEXT,
                'highlights' => [
                    'Krive, varene ili sveže farbane felne mogu ukazati na udarac i problem trapa.',
                    'Neravnomerno trošenje guma, vibracije i vučenje u stranu traže optiku i dizalicu.',
                    'Estetski popust nije dovoljan ako postoji rizik za glavčine, amortizere ili viljuške.',
                ],
                'tags' => ['felne', 'trap', 'gume', 'provera vozila', 'optika trapa'],
                'meta_title' => 'Oštećene felne na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti oštećene felne kod polovnog auta: krive felne, varenje, gume, trap, glavčine, optika, vibracije, kočenje i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mercedes C-klasa W205: premium limuzina koja traži proveru dizela, automatika i opreme',
                'slug' => 'polovni-mercedes-c-klasa-w205-premium-limuzina-koja-trazi-proveru-dizela-automatika-i-opreme',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C-klasa W205 može biti prijatan premium polovnjak, ali kupac mora proveriti motor, automatski menjač, elektroniku, trap, opremu i realnu kilometražu.',
                'content' => <<<'TEXT'
Mercedes C-klasa W205 je jedan od onih polovnjaka koji lako ubede kupca da kupuje više klase za razuman novac. Enterijer, udobnost, značka i osećaj na putu mogu biti vrlo privlačni, ali premium limuzina ne prestaje da bude premium kada postane polovna. Troškovi održavanja, delova i dobre dijagnostike moraju biti deo odluke od prvog oglasa.

Prva provera je motor. Dizel verzije treba gledati kroz hladan start, DPF, EGR, AdBlue ako ga ima, turbinu, curenja i servisni ritam. Benzinske verzije traže proveru rada motora, servisne istorije, potrošnje ulja i rashladnog sistema. Kilometraža mora imati logiku sa sedištima, volanom, komandama i računima.

Druga tema je automatski menjač, trap i kočnice. Menjač mora raditi glatko hladan i topao, bez trzaja, kašnjenja i vibracija. Trap, gume, diskovi, amortizeri i eventualno vazdušno ogibljenje moraju se proveriti na dizalici i u vožnji. Jeftin primerak sa skupim potrošnim delovima brzo menja računicu.

Treća provera je oprema. Senzori, kamera, klima, sedišta, ekran, komandni točkić, svetla, parking sistemi i sigurnosni paketi moraju raditi bez slučajnih grešaka. Dobar W205 vredi platiti zbog stanja i istorije. Loš primerak ne treba spašavati zato što na fotografijama izgleda kao dobra prilika.
TEXT,
                'highlights' => [
                    'W205 treba gledati kroz servisnu istoriju i stanje, ne samo kroz premium osećaj.',
                    'Dizel, benzinac, automatik, trap i kočnice moraju proći hladnu i toplu proveru.',
                    'Bogata oprema je prednost samo ako svaki sistem radi bez grešaka i improvizacije.',
                ],
                'tags' => ['Mercedes C-klasa W205', 'premium limuzina', 'automatik', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mercedes C-klasa W205: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Mercedes C-klasa W205 modela: dizel, benzinac, automatik, trap, elektronika, oprema, kilometraža i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#a3e635', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa neusklađenom opremom i VIN-om: kada paket opreme otkriva skrivenu priču',
                'slug' => 'auto-sa-neuskladjenom-opremom-i-vin-om-kada-paket-opreme-otkriva-skrivenu-pricu',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kada oprema iz oglasa, VIN izveštaj i stvarno stanje automobila ne pričaju istu priču, kupac treba da uspori i proveri poreklo, štetu i prepravke.',
                'content' => <<<'TEXT'
Neusklađena oprema i VIN nisu uvek dokaz prevare, ali jesu razlog da kupac uspori. Nekad je problem samo loše napisan oglas ili pogrešno označen paket opreme. Nekad, međutim, razlika između VIN izveštaja, fabričke specifikacije i stvarnog auta može otkriti uvoznu zamenu delova, lošu popravku posle udesa, naknadnu ugradnju ili nejasno poreklo.

Prva provera je fabrička specifikacija. VIN treba da pokaže motor, menjač, boju, nivo opreme, bitne pakete, datum proizvodnje i tržište za koje je auto namenjen. Ako oglas tvrdi da auto ima fabričku opremu koju VIN ne potvrđuje, pitaj da li je naknadno ugrađena i traži račun ili dokaz.

Druga tema je fizički pregled. Volan, sedišta, ekran, svetla, branici, kamera, senzori, felne, instrument tabla i sigurnosna oprema moraju izgledati kao celina. Ako su delovi različitog godišta, nijanse ili nivoa opreme, moguće je da je auto popravljan ili sklapljen jeftinijim delovima.

Treća provera je vrednost auta. Naknadna oprema nije nužno loša ako je urađena kvalitetno i dokumentovano. Problem je kada se prodaje kao fabrička, a utiče na cenu i poverenje. Ako VIN, oglas i stvarno stanje ne mogu da se usklade, bolje je platiti detaljan pregled nego kasnije objašnjavati zašto auto nema ono što je prodavac obećao.
TEXT,
                'highlights' => [
                    'VIN treba da potvrdi motor, menjač, boju, tržište i ključni paket opreme.',
                    'Razlika između oglasa i fabričke specifikacije traži račune ili dokaz o naknadnoj ugradnji.',
                    'Neusklađena oprema može ukazati na lošu popravku, zamenu delova ili nerealnu cenu.',
                ],
                'tags' => ['VIN', 'oprema', 'paket opreme', 'kupovina polovnjaka', 'provera dokumentacije'],
                'meta_title' => 'Auto sa neusklađenom opremom i VIN-om',
                'meta_description' => 'Kako proveriti auto kada se oprema iz oglasa ne slaže sa VIN-om: fabrička specifikacija, naknadna ugradnja, popravke, dokumentacija i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Kia XCeed ili Renault Arkana: crossover kada stil ne sme da pobedi praktičnost',
                'slug' => 'kia-xceed-ili-renault-arkana-crossover-kada-stil-ne-sme-da-pobedi-prakticnost',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'XCeed i Arkana nude drugačiji crossover pristup od klasičnog SUV-a, ali kupac mora porediti prostor, motor, menjač, opremu, cenu i realnu porodičnu rutinu.',
                'content' => <<<'TEXT'
Kia XCeed i Renault Arkana privlače kupce koji žele crossover izgled, ali ne žele potpuno klasičan porodični SUV. XCeed je bliži podignutom kompaktu, dok Arkana igra na coupe-crossover stil i upečatljiviji nastup. Oba mogu biti zanimljiva kao polovnjaci, ali samo ako kupac ne dopusti da oblik karoserije zameni proveru praktičnosti i stanja.

XCeed ima smisla za vozača koji želi kompaktnije dimenzije, preglednost u gradu i dobar odnos opreme i cene. Treba proveriti benzinske motore, servisne intervale, automatski menjač ako ga ima, trap, gume i elektroniku. Prednost je što se često ponaša kao normalan kompakt, pa ga je lakše živeti svakog dana nego što fotografije sugerišu.

Arkana donosi jači vizuelni efekat i često bolji osećaj posebnosti. Kod nje treba proveriti TCe ili E-Tech pogon, hibridnu logiku ako je ima, multimediju, senzore, kameru, gepek i zadnju klupu. Coupe linija izgleda atraktivno, ali kupac treba da proba ulazak pozadi, preglednost i stvarnu upotrebu gepeka.

Ako ti je važnija praktična svakodnevica, XCeed često ima mirniju računicu. Ako želiš stil, drugačiji izgled i prihvataš kompromise u preglednosti, Arkana može biti zanimljivija. U oba slučaja pobednik nije lepši auto, nego primerak sa boljom istorijom, jasnijim servisima i cenom koja priznaje stvarno stanje.
TEXT,
                'highlights' => [
                    'XCeed je racionalniji kada kupac želi crossover osećaj bez velikog SUV troška.',
                    'Arkana osvaja stilom, ali traži proveru prostora, preglednosti i hibridnog ili TCe pogona.',
                    'Kod oba modela probna vožnja i servisni trag vrede više od izgleda na fotografijama.',
                ],
                'tags' => ['Kia XCeed', 'Renault Arkana', 'crossover', 'poređenje', 'kupovina polovnjaka'],
                'meta_title' => 'Kia XCeed ili Renault Arkana: polovni crossover vodič',
                'meta_description' => 'Poređenje polovnih Kia XCeed i Renault Arkana modela: prostor, stil, TCe, E-Tech, menjač, oprema, gepek, gradska vožnja i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Toyota Camry Hybrid: velika limuzina koja traži proveru baterije, kočnica i uvoza',
                'slug' => 'polovni-toyota-camry-hybrid-velika-limuzina-koja-trazi-proveru-baterije-kocnica-i-uvoza',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Camry Hybrid može biti izuzetno mirna i udobna kupovina, ali samo kada su hibridni sistem, kočnice, poreklo, kilometraža i servisni trag jasni.',
                'content' => <<<'TEXT'
Toyota Camry Hybrid je polovnjak za kupca koji želi veliku, tihu i udobnu limuzinu bez dizel komplikacija. Hibridni pogon, reputacija Toyote i dobar komfor čine je vrlo privlačnom, ali Camry nije auto koji treba kupiti samo zato što deluje pouzdano. Velika limuzina mora imati istoriju koja opravdava cenu, uvoz i stanje.

Prva provera je hibridni sistem. Treba uraditi dijagnostiku baterije, proveriti 12V akumulator, rad motora, prelaze između električne i benzinske vožnje, potrošnju i eventualna upozorenja. Hibrid može dugo raditi mirno, ali baš zato loš primerak ponekad sakrije umor bolje nego klasičan dizel.

Druga tema su kočnice, trap i gume. Regenerativno kočenje može usporiti trošenje, ali diskovi, pločice i klizači mogu stradati od stajanja, korozije ili gradske vožnje. Teža limuzina traži dobar trap, mirne amortizere i pravilne gume. Probna vožnja treba da uključi neravnine, kočenje i otvoren put.

Treća provera je poreklo. Camry često dolazi iz uvoza, službene upotrebe ili flotnog okruženja. To nije problem ako postoje računi, tehnički tragovi i logična kilometraža. Dobar Camry Hybrid je izuzetno prijatan auto za dugo vlasništvo. Loš primerak može biti skupa limuzina koju je tržište platilo zbog reputacije, ne zbog stvarnog stanja.
TEXT,
                'highlights' => [
                    'Camry Hybrid kupuj kroz dijagnostiku baterije, servisnu istoriju i poreklo, ne samo reputaciju.',
                    'Kočnice i trap treba proveriti jer velika hibridna limuzina ne otkriva uvek umor odmah.',
                    'Uvozna i službena istorija nisu problem ako kilometraža i računi imaju kontinuitet.',
                ],
                'tags' => ['Toyota Camry Hybrid', 'polovni hibrid', 'velika limuzina', 'baterija', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Toyota Camry Hybrid: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Toyota Camry Hybrid modela: baterija, 12V akumulator, kočnice, trap, uvoz, servisna istorija i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Audi A4 B9: premium limuzina i karavan koji traže proveru TDI-a, S tronica i opreme',
                'slug' => 'polovni-audi-a4-b9-premium-limuzina-i-karavan-koji-traze-proveru-tdi-a-s-tronica-i-opreme',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Audi A4 B9 može biti odličan premium polovnjak, ali kupac mora hladno proveriti TDI, TFSI, S tronic, quattro, elektroniku, trap i realnu kilometražu.',
                'content' => <<<'TEXT'
Audi A4 B9 deluje kao zrela premium kupovina: kvalitetan enterijer, dobra izolacija, ozbiljan osećaj na autoputu i izbor limuzine ili karavana. Na tržištu polovnjaka često izgleda kao mnogo auta za novac, ali taj utisak može biti opasan ako kupac zaboravi da održavanje ostaje premium čak i kada cena oglasa padne.

Prva provera je motor. TDI verzije treba gledati kroz hladan start, DPF, EGR, AdBlue ako ga ima, turbinu, dizne, curenja i servis ulja. TFSI verzije traže proveru potrošnje ulja, rashladnog sistema, rada pod opterećenjem i servisnog ritma. Kod oba motora računi vrede više od opreme.

Druga tema je S tronic, quattro i trap. Menjač mora menjati glatko hladan i topao, bez trzaja, kašnjenja i vibracija. Quattro pogon, veće felne, kočnice, amortizeri i prednji trap mogu brzo promeniti računicu. Probna vožnja mora uključiti grad, otvoren put i spore manevre.

Treća provera je elektronika i poreklo. Virtuelni kokpit, MMI, senzori, kamera, LED svetla, klima i asistencije moraju raditi bez slučajnih grešaka. Ako je A4 uvezen i ima mnogo autoput kilometara, to nije problem samo ako dokumentacija prati stanje. Dobar B9 vredi platiti. Prosečan B9 ne treba platiti kao dobar samo zbog četiri prstena.
TEXT,
                'highlights' => [
                    'Audi A4 B9 traži premium budžet za održavanje, čak i kada cena oglasa deluje dostupno.',
                    'TDI, TFSI, S tronic, quattro i trap moraju se proveriti na hladno, toplo i na dizalici.',
                    'Elektronika i oprema su prednost samo ako postoje jasni računi i nema skrivenih grešaka.',
                ],
                'tags' => ['Audi A4 B9', 'premium limuzina', 'S tronic', 'TDI', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Audi A4 B9: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Audi A4 B9 modela: TDI, TFSI, S tronic, quattro, trap, elektronika, oprema, kilometraža i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Curenje ulja na polovnom autu: kada opran motor krije skuplji kvar',
                'slug' => 'curenje-ulja-na-polovnom-autu-kada-opran-motor-krije-skuplji-kvar',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Masni tragovi, miris ulja, dim, sveže opran motor i fleke ispod auta mogu otkriti kvar koji prodavac pokušava da predstavi kao sitnicu.',
                'content' => <<<'TEXT'
Curenje ulja kod polovnog auta ne treba odmah pretvoriti u paniku, ali ne sme se ni ignorisati. Mala fleka može biti jeftina zaptivka, ali može biti i signal za turbinu, semering, dihtung, karter, hladnjak ulja ili posledicu lošeg servisa. Najveći problem je kada je motor sveže opran baš pre prodaje, pa kupac više ne vidi tragove.

Prva provera je hladan i suv motor. Pogledaj spojeve, karter, poklopac ventila, turbinu, creva, filter ulja, prostor oko menjača i donju zaštitu. Masnoća na jednom mestu govori drugačiju priču od ulja razbacanog po celom motoru. Ako je sve neprirodno čisto, pitaj zašto je motor pran i traži pregled posle probne vožnje.

Druga tema je miris i dim. Ulje koje kaplje na vruć deo može napraviti miris paljevine. Plavičast dim, nepravilan rad, pad nivoa ulja ili upozorenje na tabli znače da problem nije kozmetički. Dijagnostika ne vidi svako curenje, zato su dizalica, lampa i iskustvo majstora ključni.

Treća provera je računica. Prodavac često kaže da je samo zaptivka, ali kupac treba da zna cenu rada, pristup delovima i rizik da se iza male fleke krije veća intervencija. Ako curenje nije jasno locirano, popust mora pokriti najgori realan scenario ili kupovina nema smisla.
TEXT,
                'highlights' => [
                    'Sveže opran motor pre prodaje može sakriti tragove curenja ulja.',
                    'Proveri karter, poklopac ventila, turbinu, semeringe, filter ulja i donju zaštitu.',
                    'Bez jasne dijagnoze, curenje ulja treba računati kao rizik u pregovorima.',
                ],
                'tags' => ['curenje ulja', 'provera vozila', 'motor', 'turbina', 'kupovina polovnjaka'],
                'meta_title' => 'Curenje ulja na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti curenje ulja kod polovnog auta: opran motor, fleke, miris, dim, karter, semering, turbina, dijagnostika i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Slab akumulator na polovnom autu: kada teško paljenje otkriva alternator, kratke relacije ili elektroniku',
                'slug' => 'slab-akumulator-na-polovnom-autu-kada-tesko-paljenje-otkriva-alternator-kratke-relacije-ili-elektroniku',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Akumulator koji jedva pali auto može biti samo potrošna stavka, ali može otkriti problem punjenja, kratke relacije, potrošače, elektroniku ili lošu pripremu za prodaju.',
                'content' => <<<'TEXT'
Slab akumulator kod polovnog auta često se predstavlja kao sitnica. Prodavac kaže da auto samo dugo stoji, kupac računa cenu novog akumulatora i nastavlja dalje. Nekad je to zaista sve. Ali teško paljenje, resetovanje sata, greške na tabli i čudno ponašanje elektronike mogu otkriti problem punjenja, alternator, parazitsku potrošnju ili auto koji je radio mnogo kratkih relacija.

Prva provera je hladan start. Auto treba upaliti hladan, bez prethodnog dopunjavanja, booster uređaja ili izgovora da je baš juče ostao otvoren gepek. Obrati pažnju na brzinu verglanja, lampice, zvuk anlasera i da li se greške pojavljuju posle paljenja.

Druga tema je punjenje. Majstor treba da izmeri napon akumulatora, rad alternatora, punjenje pod potrošačima i eventualnu potrošnju kada je auto ugašen. Start-stop sistemi i moderni automobili često traže AGM ili EFB akumulator, pa pogrešan tip može napraviti dodatne greške.

Treća provera je način korišćenja. Mnogo kratkih gradskih relacija, dugo stajanje, naknadna multimedija, alarm, kamera ili loša instalacija mogu prazniti akumulator. Ako je problem samo star akumulator, to je pregovaračka stavka. Ako je problem elektrika, mala stavka može postati duga potraga za kvarom.
TEXT,
                'highlights' => [
                    'Teško paljenje nije uvek samo cena novog akumulatora.',
                    'Treba proveriti alternator, punjenje pod potrošačima i potrošnju kada je auto ugašen.',
                    'Start-stop sistemi traže pravi tip akumulatora, a loša ugradnja opreme može ga prazniti.',
                ],
                'tags' => ['akumulator', 'alternator', 'elektronika', 'start-stop', 'troškovi održavanja'],
                'meta_title' => 'Slab akumulator na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti slab akumulator kod polovnog auta: hladan start, alternator, punjenje, parazitska potrošnja, start-stop, elektronika i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Hyundai i20 ili Nissan Micra: mali gradski auto kada budžet ne trpi skupe greške',
                'slug' => 'hyundai-i20-ili-nissan-micra-mali-gradski-auto-kada-budzet-ne-trpi-skupe-greske',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'i20 i Micra mogu biti miran gradski izbor, ali prava odluka zavisi od motora, menjača, trapa, opreme, servisne istorije i toga koliko auto stvarno košta posle kupovine.',
                'content' => <<<'TEXT'
Hyundai i20 i Nissan Micra su česti kandidati kada kupac traži mali gradski auto koji ne bi trebalo da bude skup za održavanje. Oba modela imaju smisla za početnike, svakodnevnu vožnju po gradu i kupce koji žele niže troškove registracije, guma i potrošnje. Ipak, mali auto nije automatski jeftin auto ako je konkretan primerak zapušten.

i20 obično deluje racionalnije i praktičnije. Kabina je upotrebljiva, preglednost dobra, a ponuda delova i servisa uglavnom mirna. Kod kupovine treba proveriti benzinski motor, kvačilo, menjač, hladan start, trap, kočnice, akumulator i tragove gradske upotrebe. Ako je auto radio kratke relacije, kilometraža nije dovoljan dokaz da je sve lako.

Micra često privlači kupce dizajnom, lakim parkiranjem i jednostavnim gradskim karakterom. Treba proveriti motor, menjač, klimu, elektroniku, prednji trap, stanje enterijera i eventualne tragove udaraca po branicima i vratima. Kod uvezenih primeraka dokumentacija mora objasniti zašto je cena dobra.

Ako želiš mirniju praktičnost i širu upotrebu, i20 često ima prednost. Ako ti je važniji kompaktniji osećaj i gradska lakoća, Micra može biti bolji izbor. U oba slučaja kupuj stanje, ne najnižu cenu. Jeftin mali auto sa lošim gumama, slabim kvačilom i nejasnom istorijom brzo prestaje da bude jeftin.
TEXT,
                'highlights' => [
                    'i20 je često praktičniji izbor kada kupac želi miran mali auto za svaki dan.',
                    'Micra ima prednost u gradskoj lakoći, ali treba proveriti trap, klimu i dokumentaciju.',
                    'Kod oba modela kvačilo, kočnice, gume i akumulator mogu promeniti realnu cenu.',
                ],
                'tags' => ['Hyundai i20', 'Nissan Micra', 'mali gradski auto', 'poređenje', 'kupovina polovnjaka'],
                'meta_title' => 'Hyundai i20 ili Nissan Micra: mali gradski polovnjak',
                'meta_description' => 'Poređenje polovnih Hyundai i20 i Nissan Micra modela: motor, menjač, trap, klima, gradska upotreba, servisna istorija, početnici i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volkswagen Arteon: elegantan fastback koji traži proveru TDI-a, DSG-a i opreme',
                'slug' => 'polovni-volkswagen-arteon-elegantan-fastback-koji-trazi-proveru-tdi-a-dsg-a-i-opreme',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Arteon može delovati kao premium prilika za manje novca, ali kupac mora proveriti TDI, DSG, trap, elektroniku, opremu, uvoznu istoriju i realnu cenu održavanja.',
                'content' => <<<'TEXT'
Volkswagen Arteon je polovnjak koji lako privuče kupca izgledom. Deluje elegantnije od Passata, ozbiljnije od običnog kompakta i dovoljno posebno da opravda višu cenu. Upravo zato ga treba proveravati hladne glave. Arteon nije samo lepši Passat na fotografijama, već veliki auto sa složenijom opremom i troškovima koji moraju imati pokriće u istoriji održavanja.

Prva provera je motor. TDI verzije treba gledati kroz hladan start, DPF, EGR, AdBlue ako ga ima, turbinu, dizne, curenja i servisne intervale. Benzinske verzije traže proveru potrošnje ulja, rada pod opterećenjem, rashladnog sistema i računa. Ako nema jasnih servisa, cena treba da prizna rizik.

Druga tema su DSG, trap i kočnice. Menjač mora raditi glatko hladan i topao, bez trzaja, kašnjenja i vibracija. Veće felne, niski profil guma, amortizeri, kočnice i prednji trap mogu biti skupi ako je auto vožen po lošim putevima ili kupljen samo zbog izgleda.

Treća provera je oprema. Adaptivna svetla, kamera, senzori, digitalna tabla, električna sedišta, klima, asistencije i multimedija moraju raditi bez slučajnih grešaka. Dobar Arteon je odličan auto za put i svaki dan. Loš primerak je dokaz da lep dizajn ne smanjuje cenu premium održavanja.
TEXT,
                'highlights' => [
                    'Arteon treba kupiti zbog stanja i istorije, ne samo zbog atraktivnog fastback izgleda.',
                    'TDI, DSG, trap, kočnice i veće felne mogu brzo promeniti računicu.',
                    'Bogata oprema je prednost samo ako elektronika radi bez grešaka i improvizacije.',
                ],
                'tags' => ['Volkswagen Arteon', 'TDI', 'DSG', 'fastback', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Volkswagen Arteon: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Volkswagen Arteon modela: TDI, DSG, trap, oprema, elektronika, uvozna istorija, servis i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mercedes CLA: kompaktni premium koji mora opravdati motor, menjač i limarsko stanje',
                'slug' => 'polovni-mercedes-cla-kompaktni-premium-koji-mora-opravdati-motor-menjac-i-limarsko-stanje',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'CLA kupce privlači znakom i coupe linijom, ali polovan primerak mora dokazati stanje motora, automatika, trapa, elektronike, karoserije i enterijera.',
                'content' => <<<'TEXT'
Mercedes CLA je polovnjak koji se često kupuje srcem. Coupe linija, premium znak i atraktivna kabina mogu lako sakriti činjenicu da je osnova kompaktnija nego što cena sugeriše. CLA ima smisla kada kupac zna šta plaća: izgled, imidž i opremu, ali samo ako konkretan primerak nije umoran, loše popravljan ili održavan na minimumu.

Prva provera je motor. Dizel verzije treba gledati kroz DPF, EGR, turbinu, hladan start, curenja i servisni ritam. Benzinske verzije treba proveriti kroz rad u leru, potrošnju ulja, rashladni sistem i istoriju održavanja. Kod svake verzije kilometraža mora imati logiku sa sedištima, volanom, komandama i računima.

Druga tema je menjač i trap. Automatski menjač mora menjati glatko, bez trzaja i kašnjenja. Trap, gume, kočnice i amortizeri treba da prođu probnu vožnju preko neravnina i pregled na dizalici. Niski auto sa lepim felnama često nosi tragove ivičnjaka i loših puteva.

Treća provera je karoserija. Coupe oblik ne prašta loše zazore, različite nijanse, loše nameštene branike, farove i vrata. Ako je CLA popravljan, kupac treba da zna gde, kako i sa kojim delovima. Dobar CLA je zanimljiv premium kompakt. Loš primerak je skupa lekcija da znak na haubi ne menja stanje auta.
TEXT,
                'highlights' => [
                    'CLA se ne sme kupiti samo zbog znaka i coupe linije.',
                    'Motor, automatik, trap i kočnice moraju potvrditi da auto nije samo ulepšan za prodaju.',
                    'Zazori, farovi, branici i nijanse boje često otkrivaju loše limarske popravke.',
                ],
                'tags' => ['Mercedes CLA', 'premium kompakt', 'automatik', 'limarija', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mercedes CLA: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Mercedes CLA modela: dizel, benzinac, automatski menjač, trap, karoserija, oprema, enterijer i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Rashladna tečnost na polovnom autu: kada antifriz otkriva dihtung, hladnjak ili curenje',
                'slug' => 'rashladna-tecnost-na-polovnom-autu-kada-antifriz-otkriva-dihtung-hladnjak-ili-curenje',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Nivo, boja i miris rashladne tečnosti mogu otkriti curenje, loš hladnjak, termostat, vodenu pumpu ili ozbiljniji problem sa dihtungom glave.',
                'content' => <<<'TEXT'
Rashladna tečnost je mala stavka koja može otkriti veliki problem kod polovnog auta. Kupac često pogleda ulje i gume, ali preskoči ekspanzionu posudu, creva, hladnjak i ponašanje temperature u vožnji. Antifriz ne treba da nestaje, menja boju bez objašnjenja ili miriše čudno. Ako se to dešava, problem treba razumeti pre kapare.

Prva provera je hladan motor. Pogledaj nivo tečnosti, boju, tragove mulja, masnoće, beličaste naslage, pukotine na posudi, creva i tragove curenja oko hladnjaka. Poklopac posude i creva ne smeju delovati kao da je sistem pod čudnim pritiskom pre vožnje.

Druga tema je temperatura. Probna vožnja treba da pokaže da motor normalno dostiže radnu temperaturu i da je drži u gradu, na otvorenom putu i pri uključenoj klimi. Ventilator hladnjaka, grejanje kabine, termostat i vodena pumpa mogu otkriti problem koji se ne vidi dok auto stoji na placu.

Treća provera je najskuplji scenario. Mešanje ulja i rashladne tečnosti, beli dim, gubitak antifriza, pritisak u sistemu ili tragovi pregrevanja mogu značiti ozbiljan kvar. Ako prodavac kaže da samo treba doliti tečnost, pregled kod majstora mora potvrditi da to nije priča za odlaganje velikog računa.
TEXT,
                'highlights' => [
                    'Antifriz ne sme nestajati bez jasnog uzroka i računa za popravku.',
                    'Nivo, boja, miris, creva, hladnjak i temperatura u vožnji moraju se proveriti zajedno.',
                    'Mešanje ulja i rashladne tečnosti ili pritisak u sistemu traže ozbiljan pregled.',
                ],
                'tags' => ['rashladna tečnost', 'antifriz', 'hladnjak', 'dihtung glave', 'provera vozila'],
                'meta_title' => 'Rashladna tečnost na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti rashladnu tečnost kod polovnog auta: antifriz, hladnjak, creva, termostat, vodena pumpa, dihtung glave, temperatura i curenje.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Auto pod zalogom ili kreditom: kada papiri moraju biti čistiji od cene',
                'slug' => 'auto-pod-zalogom-ili-kreditom-kada-papiri-moraju-biti-cistiji-od-cene',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Polovan auto koji je pod kreditom, lizingom ili zalogom može biti legalna kupovina, ali samo ako su vlasništvo, brisanje tereta i isplata jasno dogovoreni.',
                'content' => <<<'TEXT'
Auto pod zalogom, kreditom ili aktivnim finansiranjem nije automatski loša kupovina. Problem nastaje kada kupac ne razume ko je stvarni vlasnik, ko ima pravo da proda auto i kako se teret briše. Dobra cena ne vredi mnogo ako papiri nisu jasni pre uplate novca.

Prva provera je vlasništvo. Saobraćajna, ugovor, potvrda banke ili lizing kuće, iznos preostalog duga i uslovi brisanja zaloge moraju biti jasni. Ako prodavac kaže da će sve rešiti posle kapare, kupac treba da uspori. Redosled novca i papira mora biti napisan, ne prepušten poverenju.

Druga tema je način plaćanja. Ako deo novca ide banci, a deo prodavcu, treba znati ko izdaje potvrdu, kada se briše teret i kada kupac može registrovati auto bez rizika. Najsigurnije je da se sve radi uz pisani trag, proveru registra i po mogućnosti u dogovoru sa institucijom koja drži potraživanje.

Treća provera je cena. Auto sa čistim papirima i auto sa finansijskim teretom ne nose isti rizik. Ako procedura nije jasna, popust nije dovoljan razlog za kupovinu. Kupac ne treba da rešava tuđi kredit bez dokaza da će na kraju dobiti vozilo bez ograničenja.
TEXT,
                'highlights' => [
                    'Auto pod zalogom nije automatski problem, ali redosled novca i papira mora biti jasan.',
                    'Potvrda banke ili lizing kuće vredi više od usmenog obećanja prodavca.',
                    'Cena mora priznati rizik dok se teret ne obriše i vlasništvo ne bude čisto.',
                ],
                'tags' => ['zalog', 'kredit za auto', 'lizing', 'dokumentacija', 'kupovina polovnjaka'],
                'meta_title' => 'Auto pod zalogom ili kreditom: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto pod zalogom, kreditom ili lizingom: vlasništvo, banka, brisanje tereta, plaćanje, ugovor, registracija i rizik.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Volkswagen Golf Sportsvan ili BMW serija 2 Active Tourer: praktičan porodični kompakt kada SUV nije jedino rešenje',
                'slug' => 'volkswagen-golf-sportsvan-ili-bmw-serija-2-active-tourer-praktican-porodicni-kompakt-kada-suv-nije-jedino-resenje',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Golf Sportsvan i BMW serija 2 Active Tourer nude više upotrebljivosti od klasičnog kompakta, ali traže različit pristup ceni, servisu i proveri.',
                'content' => <<<'TEXT'
Volkswagen Golf Sportsvan i BMW serija 2 Active Tourer često prođu ispod radara jer kupci automatski traže SUV. To je greška ako ti treba lak ulazak, dobar pregled, fleksibilna zadnja klupa i upotrebljiv gepek bez cene većeg crossovera. Oba modela nude porodičnu praktičnost, ali ne nose isti rizik i ne privlače istog kupca.

Golf Sportsvan je racionalniji izbor za porodicu koja želi poznatu tehniku, više prostora od običnog Golfa i lakše poređenje oglasa. Prednost je jednostavnija kasnija prodaja i široka servisna podrška. Kod kupovine treba proveriti TSI ili TDI motor, DSG ako ga ima, trap, kočnice, stanje zadnje klupe i da li je auto zaista bio porodičan ili službeni.

BMW serija 2 Active Tourer je skuplji za kupovinu i održavanje, ali nudi premium enterijer, bolju izolaciju i ozbiljniji osećaj u vožnji. Ne treba ga kupiti samo zato što je BMW. Kod polovnog primerka proveri automatski menjač, prednji trap, elektroniku, servisnu istoriju, tragove gradske upotrebe i da li oprema radi bez upozorenja.

Ako ti je najvažnija računica, Golf Sportsvan često ima više smisla. Ako želiš komforniji auto i spreman si da platiš uredan primerak, Active Tourer može biti dobra alternativa SUV-u. U oba slučaja poredi konkretno stanje, a ne ideju o marki. Dobar monovolumen-kompakt može porodici doneti više koristi od starijeg SUV-a sa većim gumama, skupljim trapom i manje prostora nego što fotografije obećavaju.
TEXT,
                'highlights' => [
                    'Golf Sportsvan je mirniji izbor kada su prioritet prostor, servisna podrška i realna cena.',
                    'BMW serija 2 Active Tourer ima smisla samo ako premium oprema i istorija opravdavaju viši trošak.',
                    'Oba modela treba porediti kao praktičnu alternativu SUV-u, ne kao običan kompakt.',
                ],
                'tags' => ['Golf Sportsvan', 'BMW serija 2 Active Tourer', 'porodični kompakt', 'monovolumen', 'poređenje'],
                'meta_title' => 'Golf Sportsvan ili BMW serija 2 Active Tourer',
                'meta_description' => 'Poređenje polovnih Volkswagen Golf Sportsvan i BMW serija 2 Active Tourer modela: prostor, motor, menjač, oprema, servis i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Renault Zoe: mali električni auto koji traži proveru baterije, punjenja i vlasništva baterije',
                'slug' => 'polovni-renault-zoe-mali-elektricni-auto-koji-trazi-proveru-baterije-punjenja-i-vlasnistva-baterije',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Renault Zoe može biti odličan gradski električni polovnjak, ali baterija, punjač, kablovi, domet i papiri odlučuju da li je kupovina mirna.',
                'content' => <<<'TEXT'
Renault Zoe je jedan od najzanimljivijih polovnih električnih automobila za grad jer nudi nizak trošak vožnje, lakše parkiranje i dovoljno dometa za svakodnevicu. Ali električni polovnjak se ne proverava kao običan benzinac. Kod Zoe-a baterija, punjenje, vlasništvo baterije, servisna istorija i realan domet vrede više od izgleda i godišta.

Prva provera je baterija. Traži stanje zdravlja baterije, dijagnostiku, realan domet u gradskoj i hladnijoj vožnji, kao i informacije o eventualnoj zameni ili servisima. Ako prodavac ne zna razliku između prikazanog dometa i stvarne upotrebe, pregled treba biti stroži. Dobar Zoe može biti vrlo miran auto, ali samo kada baterija ima jasnu sliku.

Druga tema je punjenje. Proveri koji punjač auto podržava, da li rade svi režimi punjenja, kakvi kablovi dolaze uz auto i da li tvoj kućni ili javni režim punjenja ima smisla. Polovan električni auto nije dobra kupovina ako se oslanjaš na infrastrukturu koju realno nećeš koristiti.

Treća provera su papiri. Kod starijih Zoe primeraka obavezno proveri da li je baterija u vlasništvu ili postoji ugovor o najmu. Ta razlika menja cenu, rizik i kasniju prodaju. Ako je sve jasno, Zoe može biti odličan drugi auto za porodicu ili prvi gradski automobil. Ako papiri, punjenje ili baterija nisu jasni, niska cena ne rešava problem.
TEXT,
                'highlights' => [
                    'Kod Zoe-a prvo proveri stanje baterije i realan domet, ne samo kilometražu.',
                    'Punjač, kablovi i tvoj način punjenja odlučuju da li električni auto ima smisla.',
                    'Vlasništvo ili najam baterije mora biti jasno pre kapare.',
                ],
                'tags' => ['Renault Zoe', 'električni auto', 'baterija', 'punjenje', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Renault Zoe: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Renault Zoe: baterija, domet, punjenje, kablovi, vlasništvo baterije, servisna istorija i gradska upotreba.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mazda 2: mali Japanac koji traži proveru benzinca, korozije i gradske upotrebe',
                'slug' => 'polovni-mazda-2-mali-japanac-koji-trazi-proveru-benzinca-korozije-i-gradske-upotrebe',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mazda 2 je zanimljiv mali benzinac za grad i početnike, ali dobar primerak mora dokazati stanje karoserije, motora, trapa i enterijera.',
                'content' => <<<'TEXT'
Mazda 2 često privlači kupce koji žele mali, jednostavan i pouzdan gradski auto bez dizel komplikacija. To je dobra početna ideja, ali ne znači da svaki primerak treba platiti bez pregleda. Mali automobili žive težak gradski život: kratke relacije, ivičnjaci, tesna parkiranja, hladni startovi i neuredno održavanje brzo ostave trag.

Prva tema je benzinac. Motor treba da pali mirno hladan, radi ravnomerno, ne dimi, ne troši ulje neobjašnjivo i ima račune za osnovno održavanje. Kod malog benzinca mnogo znači redovan servis, svećice, filteri, rashladni sistem i kvalitet goriva. Ako je auto vožen uglavnom na kratkim relacijama, obrati pažnju na akumulator, kvačilo i rad u leru.

Druga provera je karoserija. Mazda reputacija ne uklanja potrebu da se gledaju rubovi, pragovi, pod, vrata, gepek, spojevi panela i tragovi loših popravki. Korozija na malom autu lako pojede prednost dobre mehanike, posebno ako je auto dugo boravio u vlažnim uslovima ili je uvezen iz područja sa mnogo soli.

Treća tema je gradska upotreba. Proveri kvačilo, menjač, trap, kočnice, felne, gume, klimu i elektroniku. Mazda 2 ima smisla kada je cena realna, istorija jasna i stanje bolje od proseka. Ako kupuješ prvi auto ili auto za svaki dan, plati uredniji primerak, jer najjeftiniji mali automobil često brzo traži gume, kočnice, servis i limarske sitnice.
TEXT,
                'highlights' => [
                    'Mazda 2 ima najviše smisla kao uredan benzinac za grad i početnike.',
                    'Korozija, rubovi, pragovi i pod moraju se proveriti jednako pažljivo kao motor.',
                    'Kvačilo, trap, gume i akumulator otkrivaju koliko je auto živeo u gradu.',
                ],
                'tags' => ['Mazda 2', 'mali auto', 'benzinac', 'korozija', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mazda 2: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Mazda 2 modela: benzinac, korozija, trap, kvačilo, gradska upotreba, servisna istorija i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'ABS i ESP lampice na polovnom autu: kada senzor točka krije skuplju dijagnostiku',
                'slug' => 'abs-i-esp-lampice-na-polovnom-autu-kada-senzor-tocka-krije-skuplju-dijagnostiku',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Upaljene ABS i ESP lampice mogu biti sitan senzor, ali mogu ukazati na instalaciju, ležaj, modul, geometriju, kočnice ili lošu popravku posle udarca.',
                'content' => <<<'TEXT'
ABS i ESP lampice na polovnom autu često se objašnjavaju kao sitnica. Prodavac kaže da je samo senzor točka, kupac pomisli da je to mali trošak i nastavi pregovore. Nekad je zaista senzor. Ali ista upozorenja mogu kriti ležaj sa magnetnim prstenom, oštećenu instalaciju, problem sa ABS modulom, lošu geometriju, neispravne kočnice ili tragove loše popravke posle udarca.

Prva provera je dijagnostika. Ne gledaj samo lampicu, već konkretne greške, istoriju brisanja grešaka i podatke sa svakog točka. Ako se greška vraća odmah posle brisanja, problem nije rešena sitnica. Ako prodavac ne dozvoljava dijagnostiku, rizik treba računati kao ozbiljan kvar.

Druga tema je mehanički pregled. ABS i ESP zavise od senzora, ležajeva, točkova, guma, trapa, geometrije i kočnica. Različite gume, loš ležaj, oštećena glavčina ili kriv trap mogu zbuniti sistem. Zato lampica nije samo elektronski problem, već signal da auto treba pogledati na dizalici i u vožnji.

Treća provera je istorija oštećenja. Ako su lampice upaljene posle zamene farova, branika, glavčine, amortizera ili popravke trapa, treba proveriti kvalitet rada i delova. Kupovina sa aktivnim ABS ili ESP greškama ima smisla samo ako je kvar tačno dijagnostikovan, cena umanjena i popravka realna. Bez toga, mala lampica lako postaje skupa potraga.
TEXT,
                'highlights' => [
                    'ABS i ESP lampice ne treba prihvatiti kao sitnicu bez dijagnostike.',
                    'Senzor točka, ležaj, instalacija, modul, trap i gume mogu dati sličan simptom.',
                    'Aktivna greška treba da smanji cenu samo kada je kvar tačno potvrđen.',
                ],
                'tags' => ['ABS', 'ESP', 'dijagnostika', 'kočnice', 'provera vozila'],
                'meta_title' => 'ABS i ESP lampice na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti ABS i ESP lampice kod polovnog auta: senzor točka, ležaj, instalacija, ABS modul, trap, gume, kočnice i dijagnostika.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Italije: kada dobra oprema ne znači mirnu istoriju',
                'slug' => 'uvoz-auta-iz-italije-kada-dobra-oprema-ne-znaci-mirnu-istoriju',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Italijanski uvoz može doneti dobru opremu i atraktivnu cenu, ali dokumentacija, servis, limarija, kilometraža i način vožnje moraju biti jasni.',
                'content' => <<<'TEXT'
Uvoz auta iz Italije može biti dobra prilika, posebno kada primerak ima bogatu opremu, lep enterijer i cenu koja deluje povoljnije od domaćih oglasa. Ali italijansko poreklo samo po sebi nije ni garancija ni problem. Prava pitanja su gde je auto vožen, kako je servisiran, da li su kilometri proverljivi i da li karoserija krije tragove gradske upotrebe ili loših popravki.

Prva provera je dokumentacija. Traži servisne račune, tehničke preglede, izvoznu dokumentaciju, VIN proveru i logiku između kilometraže, stanja enterijera i starosti. Ako se istorija svodi na usmeno objašnjenje, cena mora priznati nepoznanicu. Dobar uvoz ima papire koji pričaju istu priču kao automobil.

Druga tema je karoserija. Italijanski gradski automobili često nose tragove uskih ulica, parking oštećenja, lakiranih branika, vrata i felni. To ne mora biti problem ako je urađeno kvalitetno i jasno. Problem je kada se loša popravka prodaje kao fabričko stanje. Proveri zazore, nijanse, farove, pragove, pod, gume i geometriju.

Treća provera je servis. Dizeli traže proveru DPF-a, EGR-a, turbine i servisnih intervala, dok benzinci traže hladan start, potrošnju ulja, rashladni sistem i redovno održavanje. Ako je auto iz Italije atraktivan zbog opreme, nemoj dozvoliti da oprema zameni dokaz o stanju. Uvoz ima smisla kada dokumentacija, pregled i cena stoje u istoj realnosti.
TEXT,
                'highlights' => [
                    'Italijanski uvoz treba kupovati po dokazima, ne po opremi i sjajnim fotografijama.',
                    'VIN, računi i izvozna dokumentacija moraju potvrditi kilometražu i servisni trag.',
                    'Gradska oštećenja, lakirani delovi i loša geometrija često otkrivaju realnu istoriju.',
                ],
                'tags' => ['uvoz iz Italije', 'uvoz auta', 'servisna istorija', 'VIN', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Italije: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu auta iz Italije: dokumentacija, VIN, kilometraža, servisna istorija, limarija, oprema, gradska upotreba i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Škoda Roomster ili Citroen C3 Picasso: mali porodični auto kada budžet ne prati SUV želje',
                'slug' => 'skoda-roomster-ili-citroen-c3-picasso-mali-porodicni-auto-kada-budzet-ne-prati-suv-zelje',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Roomster i C3 Picasso nude mnogo prostora za malo novca, ali dobar izbor zavisi od benzinca, dizela, trapa, elektronike i stvarne porodične upotrebe.',
                'content' => <<<'TEXT'
Škoda Roomster i Citroen C3 Picasso su automobili koje mnogi kupci preskoče jer ne izgledaju kao moderni SUV. To ne znači da su loša kupovina. Naprotiv, za porodicu sa ograničenim budžetom mogu ponuditi više realne upotrebljivosti od atraktivnijeg crossovera: viši položaj sedenja, preglednu kabinu, veliki gepek i praktična zadnja sedišta.

Roomster je racionalniji i utilitarniji izbor. Njegova prednost je jednostavna koncepcija, dobra preglednost i poznata Volkswagen grupacija tehnika, ali to ne znači da treba kupiti prvi primerak. Proveri benzinski ili dizel motor, trap, zadnja vrata, gepek, stanje enterijera i da li je auto bio porodičan, službeni ili mali dostavni zamenski izbor.

C3 Picasso je udobniji i prijatniji u kabini, ali traži više pažnje oko elektronike, klime, senzora, prednjeg trapa i servisne istorije. Kod francuskih polovnjaka nije problem sama marka, već primerci koji su održavani samo kada nešto otkaže. Ako kartica, klima, prekidači i upozorenja rade bez grešaka, C3 Picasso može biti vrlo udoban mali porodični auto.

Ako tražiš najjednostavniju računicu, Roomster često ima prednost. Ako ti je važnija udobnost i kabinski osećaj, C3 Picasso može biti bolji izbor. U oba slučaja presudi stanje. Ovi automobili se kupuju zato što rešavaju svakodnevicu, a ne zato što izgledaju poželjno na oglasu. Zato je pregled trapa, kočnica, guma, klime i zadnje klupe važniji od boje i ukrasnih detalja.
TEXT,
                'highlights' => [
                    'Roomster je praktičniji izbor kada su prioritet jednostavnost, preglednost i niži rizik.',
                    'C3 Picasso ima prednost u udobnosti, ali traži pažljiviju proveru elektronike i klime.',
                    'Oba modela su dobra SUV alternativa samo ako stanje potvrdi porodičnu upotrebljivost.',
                ],
                'tags' => ['Škoda Roomster', 'Citroen C3 Picasso', 'porodični auto', 'monovolumen', 'poređenje'],
                'meta_title' => 'Škoda Roomster ili Citroen C3 Picasso',
                'meta_description' => 'Poređenje polovnih Škoda Roomster i Citroen C3 Picasso modela: prostor, motor, trap, klima, elektronika, porodična upotreba i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Opel Meriva: praktičan mali monovolumen koji traži proveru vrata, trapa i benzinca',
                'slug' => 'polovni-opel-meriva-praktican-mali-monovolumen-koji-trazi-proveru-vrata-trapa-i-benzinca',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Opel Meriva može biti vrlo praktičan porodični i gradski auto, ali kupac mora proveriti motor, zadnja vrata, trap, klimu, elektroniku i servisni trag.',
                'content' => <<<'TEXT'
Opel Meriva je polovnjak koji ima smisla za kupca kome treba mali auto sa mnogo lakšim ulaskom, visokom kabinom i porodičnim detaljima. Ne izgleda atraktivno kao crossover, ali često nudi bolju svakodnevnu ergonomiju za manje novca. To je posebno važno za kupce koji prevoze decu, starije članove porodice ili često ulaze i izlaze iz auta u gradu.

Prva provera je motor. Benzinske verzije treba slušati hladne, proveriti rad u leru, potrošnju ulja, rashladni sistem i račune za redovno održavanje. Dizel može imati smisla samo ako je vožen na dužim relacijama i ima jasan servisni trag. Za kratku gradsku vožnju dobar benzinac je često mirnija odluka.

Druga tema su vrata, kabina i praktični mehanizmi. Meriva sa zadnjim vratima koja se otvaraju unazad mora imati ispravne brave, zaptivke, šarke i elektroniku. Proveri sva sedišta, preklapanje, pojaseve, podizače, klimu, ekran i dugmad. Praktičan auto gubi smisao ako detalji zbog kojih ga kupuješ rade polovično.

Treća provera je trap. Meriva često radi gradske relacije, prelazi preko ivičnjaka i nosi porodični teret. Probna vožnja treba da uključi neravnine, kočenje, okretanje volana u mestu i parkiranje. Dobar primerak može biti razumna kupovina za svaki dan. Zapušten primerak brzo traži kočnice, gume, trap, akumulator i sitne električne popravke koje pojedu prednost niske cene.
TEXT,
                'highlights' => [
                    'Meriva ima smisla kada kupac stvarno koristi visok ulaz, fleksibilnu kabinu i praktična vrata.',
                    'Benzinac je često mirniji izbor za grad od dizela sa nejasnom istorijom.',
                    'Vrata, klima, elektronika, trap i kočnice moraju raditi bez izgovora.',
                ],
                'tags' => ['Opel Meriva', 'mali monovolumen', 'benzinac', 'porodični auto', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Opel Meriva: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Opel Meriva modela: benzinac, dizel, zadnja vrata, trap, klima, elektronika, porodična upotreba i troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Fiat Panda 4x4: mali terenac koji ne sme da sakrije skupu mehaniku',
                'slug' => 'polovni-fiat-panda-4x4-mali-terenac-koji-ne-sme-da-sakrije-skupu-mehaniku',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Panda 4x4 je šarmantan mali auto za loše puteve i zimu, ali pogon, kvačilo, trap, korozija i stvarna upotreba odlučuju da li je cena opravdana.',
                'content' => <<<'TEXT'
Fiat Panda 4x4 je jedan od onih polovnjaka koji izgledaju jednostavno, ali na tržištu često drže cenu iznad očekivanja. Razlog je jasan: mali auto, dobar pregled, kratke mere i pogon na sva četiri točka čine ga korisnim za sneg, brda, selo i lošije puteve. Ipak, baš zato treba proveriti da li je prethodni vlasnik tu sposobnost koristio pažljivo ili bez mnogo obzira.

Prva tema je pogon. Panda 4x4 ne sme da škripi, lupa, zateže ili pokazuje čudne vibracije pri kretanju, skretanju i promeni opterećenja. Proveri kardane, diferencijal, nosače, kvačilo, menjač i tragove curenja. Mali auto sa 4x4 sistemom nije skup samo zato što je mali; zapušten pogon može promeniti celu računicu.

Druga provera je karoserija. Panda često živi napolju, ide po snegu, blatu i soli, pa treba proveriti pragove, pod, rubove, zadnji deo, nosače i donji postroj. Površinska rđa nije isto što i konstrukcioni problem. Ako prodavac kaže da je samo estetski, dizalica treba da potvrdi priču.

Treća tema je svakodnevna upotreba. Panda 4x4 je odlična kada ti stvarno treba mali auto za loše uslove, ali nije idealna ako tražiš tišinu, autoput komfor ili veliki gepek. Dobar primerak treba platiti kao specifičan alat, ne kao običnu Pandu sa većom cenom. Ako pogon, trap i limarija nisu jasni, bolje je kupiti običan mali auto u boljem stanju.
TEXT,
                'highlights' => [
                    'Panda 4x4 vredi više samo kada pogon, trap i limarija potvrde stvarno stanje.',
                    'Kardan, diferencijal, kvačilo i curenja moraju se proveriti u vožnji i na dizalici.',
                    'Korozija na podu, pragovima i zadnjem delu može poništiti prednost malog 4x4 auta.',
                ],
                'tags' => ['Fiat Panda 4x4', 'mali terenac', 'pogon 4x4', 'korozija', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Fiat Panda 4x4: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Fiat Panda 4x4 modela: pogon, kardan, diferencijal, kvačilo, trap, korozija, loši putevi i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Vibracije pri kočenju na polovnom autu: kada diskovi kriju trap, ležajeve ili lošu popravku',
                'slug' => 'vibracije-pri-kocenju-na-polovnom-autu-kada-diskovi-kriju-trap-lezajeve-ili-losu-popravku',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Tresenje volana pri kočenju može biti potrošan disk, ali može otkriti čeljusti, ležaj, trap, glavčinu, gume ili lošu popravku posle udarca.',
                'content' => <<<'TEXT'
Vibracije pri kočenju su jedan od simptoma koje kupci često prihvate kao običnu potrošnu stavku. Prodavac kaže da treba samo zameniti diskove, kupac uračuna osnovni servis i nastavi dalje. Nekad je to tačno. Ali tresenje volana, pulsiranje pedale ili povlačenje auta u stranu može ukazati na širi problem koji se ne rešava samo novim diskovima.

Prva provera je probna vožnja. Koči blago i snažnije, pri različitim brzinama, na ravnom putu i bez naglog cimanja volana. Obrati pažnju da li vibrira volan, sedište ili pedala, da li auto vuče u stranu i da li se simptom menja kada su kočnice tople. To pomaže majstoru da razlikuje diskove, zadnju osovinu, gume ili trap.

Druga tema je pregled na dizalici. Diskovi, pločice, čeljusti, klizači, ležajevi, glavčine, kugle, seleni i amortizeri moraju se gledati zajedno. Ako se diskovi brzo krive posle zamene, uzrok može biti loša glavčina, zapekla čeljust, nepravilan moment zatezanja točkova ili nekvalitetan deo.

Treća provera je istorija oštećenja. Vibracije pri kočenju posle zamene felni, guma, trapa ili limarske popravke mogu govoriti da auto nije pravilno složen. Kupovina ima smisla ako je kvar konkretno dijagnostikovan i cena realno smanjena. Bez toga, "samo diskovi" može biti početak mnogo duže potrage.
TEXT,
                'highlights' => [
                    'Tresenje pri kočenju nije uvek samo set diskova i pločica.',
                    'Volan, pedala, zadnja osovina i ponašanje na toplim kočnicama daju važne tragove.',
                    'Čeljusti, ležajevi, glavčine, trap i gume treba proveriti pre pregovora o ceni.',
                ],
                'tags' => ['vibracije pri kočenju', 'diskovi', 'kočnice', 'trap', 'provera vozila'],
                'meta_title' => 'Vibracije pri kočenju na polovnom autu',
                'meta_description' => 'Kako proveriti vibracije pri kočenju kod polovnog auta: diskovi, pločice, čeljusti, ležajevi, glavčine, trap, gume i loša popravka.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Francuske: kada niža cena traži proveru servisa, limarije i elektronike',
                'slug' => 'uvoz-auta-iz-francuske-kada-niza-cena-trazi-proveru-servisa-limarije-i-elektronike',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Francuski uvoz može biti dobra prilika, ali servisni trag, gradska oštećenja, elektronika, kilometraža i dokumentacija moraju biti jasni pre kapare.',
                'content' => <<<'TEXT'
Uvoz auta iz Francuske često privlači kupce jer se na oglasima pojave dobro opremljeni primerci sa cenom koja deluje razumno. Peugeot, Renault, Citroen i druge marke mogu biti vrlo dobra kupovina kada je istorija jasna. Problem nastaje kada se niža cena koristi da sakrije nepoznatu kilometražu, gradska oštećenja, slab servisni trag ili elektronske greške.

Prva provera je dokumentacija. VIN, računi, tehnički pregledi, servisni zapisi i izvozna dokumentacija moraju imati logiku. Ako kilometraža deluje nisko, enterijer, volan, sedišta, pedale i stanje komandi treba to da potvrde. Francusko poreklo nije problem samo po sebi; problem je auto bez proverljive priče.

Druga tema je limarija. Gradska vožnja, uske ulice i parking oštećenja često ostavljaju tragove na branicima, vratima, felnama i farovima. Lakirani elementi nisu automatski razlog za odustajanje, ali kupac mora znati šta je popravljano i kako. Loše uklopljeni branici, različite nijanse i čudni zazori traže pregled kod limara.

Treća provera je elektronika i servis. Klima, kartica ili ključ, ekran, senzori, podizači, svetla i upozorenja na tabli moraju raditi bez slučajnih grešaka. Dizeli traže proveru DPF-a, EGR-a i turbine, dok benzinci traže hladan start, ulje i rashladni sistem. Dobar francuski uvoz ima smisla kada cena, papiri i pregled govore istu stvar. Ako jedna karika nedostaje, popust mora biti ozbiljan ili kupovina treba da sačeka.
TEXT,
                'highlights' => [
                    'Francuski uvoz treba kupovati po dokumentaciji, ne po opremi i povoljnoj ceni.',
                    'Gradska oštećenja, lakirani delovi i elektronske greške često menjaju realnu vrednost.',
                    'VIN, računi, tehnički pregledi i stanje enterijera moraju potvrditi kilometražu.',
                ],
                'tags' => ['uvoz iz Francuske', 'uvoz auta', 'servisna istorija', 'limarija', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Francuske: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Francuske: dokumentacija, VIN, kilometraža, servisna istorija, limarija, elektronika, dizel rizici i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Ford B-Max ili Kia Venga: mali porodični auto kada vrata i prostor vrede više od imidža',
                'slug' => 'ford-b-max-ili-kia-venga-mali-porodicni-auto-kada-vrata-i-prostor-vrede-vise-od-imidza',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'B-Max i Venga nude praktičnost malog porodičnog auta bez SUV cene, ali dobar izbor zavisi od vrata, trapa, benzinca, klime i stvarnog stanja.',
                'content' => <<<'TEXT'
Ford B-Max i Kia Venga su automobili koji često izgledaju neatraktivno pored modernih crossovera, ali za porodicu sa realnim budžetom mogu biti pametniji izbor. Oba nude viši ulazak, dobru preglednost, praktičnu kabinu i dovoljno prostora za grad, školu, kupovinu i kraća porodična putovanja. Njihova vrednost nije u imidžu, već u tome koliko olakšavaju svakodnevicu.

B-Max ima posebnu prednost zbog kliznih zadnjih vrata i izostanka klasičnog B stuba, što može biti odlično na uskim parkinzima. To je ujedno i tačka provere. Vrata, brave, klizači, zaptivke, senzori i centralno zaključavanje moraju raditi bez izgovora. Kod motora proveri hladan start, kvačilo, menjač, potrošnju ulja i servisnu istoriju.

Kia Venga je jednostavniji izbor za kupca koji želi praktičan mali auto bez specifične konstrukcije vrata. Prednost je pregledna kabina i često dobra oprema, ali treba proveriti trap, klimu, elektroniku, stanje enterijera i da li je auto radio mnogo kratkih gradskih relacija. Ako je servisna istorija uredna, Venga može biti vrlo mirna kupovina za svaki dan.

Ako su oba primerka sličnog stanja, B-Max ima prednost kada ti klizna vrata stvarno rešavaju problem. Venga ima prednost kada želiš manje specifičnih mehanizama i jednostavniju proveru. U oba slučaja ne kupuj najjeftiniji oglas samo zato što model nije popularan. Kod malih porodičnih automobila gume, kočnice, trap, klima i kvačilo brzo pojedu razliku između dobrog i zapuštenog primerka.
TEXT,
                'highlights' => [
                    'B-Max je odličan kada klizna vrata stvarno olakšavaju porodičnu rutinu.',
                    'Venga je jednostavniji izbor ako želiš praktičnost bez specifičnog mehanizma vrata.',
                    'Kod oba modela proveri trap, kvačilo, klimu, elektroniku i tragove gradske upotrebe.',
                ],
                'tags' => ['Ford B-Max', 'Kia Venga', 'mali porodični auto', 'monovolumen', 'poređenje'],
                'meta_title' => 'Ford B-Max ili Kia Venga: mali porodični auto',
                'meta_description' => 'Poređenje polovnih Ford B-Max i Kia Venga modela: prostor, klizna vrata, motor, trap, klima, gradska upotreba, oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volkswagen Up: mali gradski auto koji traži proveru kvačila, trapa i gradske upotrebe',
                'slug' => 'polovni-volkswagen-up-mali-gradski-auto-koji-trazi-proveru-kvacila-trapa-i-gradske-upotrebe',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Volkswagen Up može biti odličan mali gradski auto, ali stanje kvačila, trapa, kočnica, klime, enterijera i servisnog traga odlučuje kupovinu.',
                'content' => <<<'TEXT'
Volkswagen Up je mali auto koji kupci često gledaju kao jednostavno i sigurno rešenje za grad. Kratak je, pregledan, lako se parkira i ne traži velike gume ni skupu registraciju. Ali mali gradski auto ne znači automatski mali rizik. Kratke relacije, česta paljenja, ivičnjaci, tesna parkiranja i štednja na servisima brzo ostavljaju trag.

Prva provera je motor i kvačilo. Benzinski motor treba da pali mirno hladan, radi ravnomerno i ima račune za redovne servise. Kvačilo, menjač i nosači motora često najviše govore o gradskoj eksploataciji. Ako auto trza pri kretanju, teško ubacuje u brzinu ili deluje umorno na kratkoj probnoj vožnji, cena mora priznati rizik.

Druga tema je trap. Up je lagan auto, ali gradske rupe i ivičnjaci mogu napraviti zvukove, krive felne, neravnomerno trošenje guma i loš osećaj na volanu. Proveri kočnice, gume, amortizere, kugle, ležajeve i geometriju. Jeftina gradska vožnja prestaje da bude jeftina kada odmah posle kupovine dolaze gume, kočnice i trap.

Treća provera je kabina i oprema. Klima, podizači, brave, sedišta, pojasevi, svetla, brisači i elektronika moraju raditi bez izgovora. Up ima smisla kada kupuješ uredan primerak za realnu svakodnevicu, a ne najjeftiniji oglas. Ako ti treba auto za grad, bolji je skuplji Up sa jasnom istorijom nego lepši primerak bez dokaza o održavanju.
TEXT,
                'highlights' => [
                    'Volkswagen Up je dobar gradski izbor kada servis i stanje potvrđuju nisku cenu upotrebe.',
                    'Kvačilo, menjač, nosači i trap najbrže otkrivaju težak gradski život.',
                    'Gume, kočnice, klima i osnovna oprema moraju ući u realnu cenu kupovine.',
                ],
                'tags' => ['Volkswagen Up', 'mali gradski auto', 'benzinac', 'kvačilo', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Volkswagen Up: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Volkswagen Up modela: benzinac, kvačilo, menjač, trap, kočnice, klima, gradska upotreba i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Suzuki SX4 S-Cross: crossover koji traži proveru benzinca, dizela i 4x4 pogona',
                'slug' => 'polovni-suzuki-sx4-s-cross-crossover-koji-trazi-proveru-benzinca-dizela-i-4x4-pogona',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Suzuki SX4 S-Cross može biti racionalan crossover, ali kupac mora proveriti motor, 4x4 pogon, trap, kvačilo, koroziju, opremu i servisnu istoriju.',
                'content' => <<<'TEXT'
Suzuki SX4 S-Cross je zanimljiv polovnjak za kupce koji žele praktičan crossover bez premium cene i bez previše komplikovane slike. Nudi dobar ulazak, solidan prostor, često pristojnu opremu i reputaciju razumnog održavanja. Ipak, reputacija nije zamena za pregled. Dobar S-Cross može biti mirna kupovina, ali zapušten primerak lako sakrije troškove kroz trap, pogon i servisni trag.

Prva provera je motor. Benzinske verzije treba gledati kroz hladan start, potrošnju ulja, servisne intervale, rad kvačila i realnu potrošnju. Dizel može imati smisla za duže relacije, ali samo ako su DPF, EGR, turbina i servis ulja jasni. Ako je auto većinom vožen po gradu, dizel rizik raste i niska potrošnja ne sme biti jedini argument.

Druga tema je 4x4 pogon. Ako primerak ima pogon na sva četiri točka, proveri rad sistema, zvukove pri skretanju, curenja, kardane, diferencijal i stanje guma. Različite gume po osovinama, čudne vibracije ili nejasan servis mogu značiti da dodatni pogon nije samo prednost, već i budući trošak.

Treća provera je karoserija, trap i oprema. SX4 S-Cross se često koristi kao porodični auto za sve uslove, pa treba pogledati pragove, pod, amortizere, kočnice, klimu, senzore, multimediju i tragove loših popravki. Najbolja kupovina je primerak sa jasnim računima i realnom cenom, ne najbogatiji oglas sa nepoznatom istorijom.
TEXT,
                'highlights' => [
                    'S-Cross je racionalan crossover samo kada servisna istorija prati reputaciju marke.',
                    'Kod dizela proveri DPF, EGR, turbinu i režim vožnje, a kod benzinca ulje i kvačilo.',
                    '4x4 pogon traži proveru guma, kardana, diferencijala, curenja i zvukova u skretanju.',
                ],
                'tags' => ['Suzuki SX4 S-Cross', 'crossover', '4x4', 'benzinac', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Suzuki SX4 S-Cross: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Suzuki SX4 S-Cross modela: benzinac, dizel, 4x4 pogon, trap, kvačilo, korozija, oprema i servis.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Dim iz auspuha na polovnom autu: kada boja dima otkriva turbo, dizne, ulje ili rashladnu tečnost',
                'slug' => 'dim-iz-auspuha-na-polovnom-autu-kada-boja-dima-otkriva-turbo-dizne-ulje-ili-rashladnu-tecnost',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dim iz auspuha nije samo neprijatan detalj: bela, plava ili crna boja mogu otkriti ulje, rashladnu tečnost, dizne, turbo, DPF ili loš servis.',
                'content' => <<<'TEXT'
Dim iz auspuha kod polovnog auta ne treba ignorisati, čak ni kada prodavac kaže da je motor hladan ili da auto dugo stoji. Kratak trag pare po hladnom vremenu može biti normalan, ali gust beli, plavi ili crni dim traži objašnjenje pre kapare. Boja, miris i trenutak kada se dim pojavljuje često govore više od samog broja kilometara.

Beli dim može biti bezazlena para, ali može ukazati i na rashladnu tečnost u cilindrima, problem dihtunga glave, hladnjak EGR-a ili trag pregrevanja. Zato je važno gledati nivo rashladne tečnosti, pritisak u sistemu, temperaturu motora i da li se dim zadržava kada se motor zagreje. Ako se priča svodi na "to je normalno", pregled kod majstora mora potvrditi razlog.

Plavi dim najčešće otvara pitanje potrošnje ulja, turbine, karika, vođica ventila ili lošeg održavanja. Posebno obrati pažnju pri hladnom startu, posle dužeg rada u leru i pri jačem ubrzanju. Crni dim kod dizela može ukazivati na loše sagorevanje, dizne, turbinu, EGR, DPF, mapiranje ili zapušten servis.

Najbolja provera je hladan start, probna vožnja i dijagnostika. Auto treba gledati pre nego što ga prodavac zagreje. Ako dim nestane tek posle brisanja grešaka ili agresivne vožnje, to nije dokaz da je problem rešen. Kupovina ima smisla samo kada uzrok dima ima jasnu dijagnozu i cenu popravke. Bez toga, dim je signal da treba stati, a ne samo spustiti cenu na osećaj.
TEXT,
                'highlights' => [
                    'Beli, plavi i crni dim ne znače isti kvar i moraju se tumačiti u uslovima hladnog i toplog motora.',
                    'Beli dim traži proveru rashladne tečnosti, pritiska sistema i tragova pregrevanja.',
                    'Plavi ili crni dim mogu značiti ulje, turbinu, dizne, EGR, DPF ili loše održavanje.',
                ],
                'tags' => ['dim iz auspuha', 'turbo', 'dizne', 'rashladna tečnost', 'provera vozila'],
                'meta_title' => 'Dim iz auspuha na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti dim iz auspuha kod polovnog auta: beli, plavi i crni dim, turbo, dizne, ulje, rashladna tečnost, DPF, EGR i servis.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Nemačke: kada dobra servisna istorija nije dovoljna bez provere kilometraže i opreme',
                'slug' => 'uvoz-auta-iz-nemacke-kada-dobra-servisna-istorija-nije-dovoljna-bez-provere-kilometraze-i-opreme',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Nemački uvoz često deluje sigurnije zbog servisnih zapisa, ali kilometraža, flotna upotreba, zimski uslovi, oprema i papiri moraju se proveriti.',
                'content' => <<<'TEXT'
Uvoz auta iz Nemačke kod kupaca često ima bolji zvuk od drugih tržišta. Razlog je jasan: veće tržište, mnogo službenih automobila, redovni servisi i veliki izbor. Ali nemačko poreklo nije garancija da je primerak miran. Auto može imati uredne servise, a da je vozio ogromnu kilometražu, mnogo autoputa, flotnu eksploataciju ili zimske uslove koji se vide tek na detaljnom pregledu.

Prva provera je kilometraža. Servisna istorija mora imati logiku sa tehničkim pregledima, računima, stanjem sedišta, volana, pedala, guma i kočnica. Kod uvoza iz Nemačke često je veći problem visoka realna kilometraža nego lažna mala kilometraža. Ako je auto prešao mnogo, to nije automatski loše, ali cena mora odgovarati stanju.

Druga tema je prethodna namena. Službeni auto, lizing, rent-a-car, autoput flotno vozilo i privatni primerak nisu isti rizik. Auto koji je redovno servisiran može i dalje imati umoran enterijer, zamor trapa, istrošene kočnice, napregnut automatik ili opremu koja radi polovično. Zato ne gledaj samo pečate, već i način korišćenja.

Treća provera su papiri i oprema. VIN, izvozna dokumentacija, računi, COC, servisni izveštaji i poreklo moraju biti jasni. Proveri da li oprema iz oglasa odgovara VIN-u, da li su farovi, asistencije, senzori, kamera, klima i multimedija ispravni. Dobar nemački uvoz ima smisla kada su papiri, kilometraža i stanje u istoj priči. Ako prodavac prodaje samo reputaciju tržišta, kupac treba da uspori.
TEXT,
                'highlights' => [
                    'Nemački uvoz nije garancija mirne kupovine bez provere kilometraže i namene.',
                    'Servisna istorija mora se slagati sa stanjem enterijera, trapa, kočnica i opreme.',
                    'Flotna, lizing i autoput upotreba menjaju cenu čak i kada su servisi uredni.',
                ],
                'tags' => ['uvoz iz Nemačke', 'uvoz auta', 'servisna istorija', 'kilometraža', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Nemačke: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Nemačke: servisna istorija, kilometraža, lizing, flotna upotreba, oprema, VIN, dokumentacija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Dacia Logan MCV ili Škoda Rapid Spaceback: karavan razum ili kompakt kada budžet traži prostor',
                'slug' => 'dacia-logan-mcv-ili-skoda-rapid-spaceback-karavan-razum-ili-kompakt-kada-budzet-trazi-prostor',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Logan MCV i Rapid Spaceback nude prostor bez SUV cene, ali kupac mora odlučiti da li mu više vrede jednostavnost, oprema, enterijer ili kasnija prodaja.',
                'content' => <<<'TEXT'
Dacia Logan MCV i Škoda Rapid Spaceback ne kupuju se zato što izazivaju uzbuđenje na parkingu. Kupuje ih neko kome treba prostor, razuman trošak i auto koji neće pojesti budžet pre prvog porodičnog putovanja. Upravo zato ih vredi porediti hladne glave: jedan nudi karavan logiku i jednostavnost, drugi bolji kompaktni osećaj i uredniji enterijer.

Logan MCV je izbor za kupca koji želi mnogo gepeka za što manje novca. Ima smisla za porodicu, alat, hobi, putovanja i sve situacije u kojima prostor zaista radi posao. Ali niska cena ne sme da zaslepi. Proveri benzinski ili dizel motor, trap, vrata gepeka, pragove, sedišta, klimu i da li je auto radio kao privatni porodični auto ili praktična radna mašina.

Rapid Spaceback je civilizovaniji kompromis. Kabina deluje bliže klasičnom kompaktnom automobilu, vožnja je često prijatnija, a kasnija prodaja lakša ako je primerak uredan. Mana je što ne nudi isti karavanski gepek kao Logan MCV. Kod Rapida proveri TSI ili TDI motor, kvačilo, menjač, zadnji trap, elektroniku i da li kilometraža ima smisla sa stanjem enterijera.

Ako ti treba maksimalan prostor za novac, Logan MCV je poštenija priča. Ako želiš bolji balans osećaja u vožnji, opreme i svakodnevne upotrebe, Rapid Spaceback može biti pametniji izbor. U oba slučaja ne kupuj ideju o jeftinom prostoru, nego konkretan primerak. Dobar auto sa manje opreme vredi više od lepšeg oglasa koji odmah traži gume, trap, veliki servis i klimu.
TEXT,
                'highlights' => [
                    'Logan MCV je bolji kada je gepek glavni razlog kupovine.',
                    'Rapid Spaceback ima prednost kada želiš bolji kompaktni osećaj i lakšu kasniju prodaju.',
                    'Kod oba modela stanje trapa, klime, enterijera i servisnog traga odlučuje realnu cenu.',
                ],
                'tags' => ['Dacia Logan MCV', 'Škoda Rapid Spaceback', 'karavan', 'kompakt', 'poređenje'],
                'meta_title' => 'Dacia Logan MCV ili Škoda Rapid Spaceback',
                'meta_description' => 'Poređenje polovnih Dacia Logan MCV i Škoda Rapid Spaceback modela: prostor, motor, trap, klima, enterijer, servis i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Peugeot 207: mali auto koji traži proveru benzinca, elektronike i zadnjeg trapa',
                'slug' => 'polovni-peugeot-207-mali-auto-koji-trazi-proveru-benzinca-elektronike-i-zadnjeg-trapa',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Peugeot 207 može biti povoljan mali auto za grad i početnike, ali dobar primerak mora dokazati motor, zadnji trap, elektroniku, klimu i servisni trag.',
                'content' => <<<'TEXT'
Peugeot 207 često ulazi u potragu kada kupac želi mali auto koji nije preskup, izgleda pristojno i može da posluži za grad, početnika ili drugi porodični automobil. Na papiru deluje jednostavno: mali auto, razumna cena, dovoljno oglasa. U praksi razlika između urednog i zapuštenog 207 može biti veća nego što fotografije pokazuju.

Prva provera je motor. Benzinske verzije treba slušati hladne, gledati rad u leru, potrošnju ulja, curenja, rashladni sistem i račune za redovne servise. Dizel može biti štedljiv, ali samo ako nije ceo život proveo na kratkim relacijama. Kod svakog primerka kilometraža mora imati logiku sa volanom, sedištem, pedalama i opštim osećajem kabine.

Druga tema je zadnji trap i gradska upotreba. Mali francuski auto često je živeo na ivičnjacima, rupama i uskim parkinzima. Probna vožnja treba da uključi neravnine, kočenje, kružno okretanje i slušanje zadnjeg dela. Ako se čuju udarci, škripanje ili auto stoji čudno, cena mora priznati da pregled tek počinje.

Treća provera je elektronika. Klima, podizači, brave, svetla, instrument tabla, radio, senzori i upozorenja na tabli moraju raditi bez izgovora. Dobar Peugeot 207 može biti simpatičan i razuman mali auto. Loš primerak je podsetnik da niska cena kupovine ne znači nisku cenu prvih šest meseci.
TEXT,
                'highlights' => [
                    'Peugeot 207 ima smisla kao povoljan mali auto samo ako motor i servisni trag nisu nepoznanica.',
                    'Zadnji trap, gume i kočnice često otkrivaju težak gradski život.',
                    'Elektronika i klima moraju raditi bez slučajnih grešaka i izgovora prodavca.',
                ],
                'tags' => ['Peugeot 207', 'mali auto', 'benzinac', 'zadnji trap', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Peugeot 207: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Peugeot 207 modela: benzinac, dizel, zadnji trap, elektronika, klima, gradska upotreba i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Nissan Note: praktičan mali auto koji ne sme da sakrije CVT, trap i gradsku upotrebu',
                'slug' => 'polovni-nissan-note-praktican-mali-auto-koji-ne-sme-da-sakrije-cvt-trap-i-gradsku-upotrebu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Nissan Note nudi iznenađujuće mnogo prostora u malom pakovanju, ali kupac mora proveriti motor, CVT, trap, klimu, enterijer i servisnu istoriju.',
                'content' => <<<'TEXT'
Nissan Note je auto koji kupci često razumeju tek kada sednu unutra. Spolja deluje kao mali gradski automobil, ali kabina i zadnja klupa mogu iznenaditi svakoga ko traži praktičnost bez velikih dimenzija. Zato Note ima smisla za porodicu, starije vozače, gradske relacije i kupce kojima je lak ulazak važniji od imidža.

Prva provera je motor i menjač. Benzinske verzije treba gledati kroz hladan start, rad u leru, potrošnju ulja i račune za servis. Ako je auto sa CVT menjačem, probna vožnja mora biti ozbiljna: bez trzaja, zavijanja, kašnjenja i čudnog ponašanja pri kretanju. CVT može biti prijatan, ali zapušten menjač menja celu cenu kupovine.

Druga tema je gradski život. Note često radi kratke relacije, nosi kupovinu, decu i svakodnevni tempo. Proveri trap, kočnice, gume, klimu, brave, podizače, sedišta i stanje gepeka. Auto koji je spolja mali može unutra pokazati ozbiljan umor ako je godinama korišćen bez pažnje.

Treća provera je praktičnost koju stvarno koristiš. Zadnja klupa, položaj sedenja, preglednost, parkiranje i gepek treba da odgovaraju tvojoj rutini. Dobar Note je razuman mali praktičar. Loš Note je samo jeftin oglas sa mogućim CVT, trap i elektronskim troškovima koje je neko drugi odložio za novog vlasnika.
TEXT,
                'highlights' => [
                    'Nissan Note vredi gledati kada mali auto mora ponuditi stvarno upotrebljivu kabinu.',
                    'CVT menjač traži hladnu i toplu probnu vožnju bez trzaja, kašnjenja i zavijanja.',
                    'Trap, klima, brave i enterijer otkrivaju koliko je auto živeo u gradu.',
                ],
                'tags' => ['Nissan Note', 'mali porodični auto', 'CVT', 'gradska upotreba', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Nissan Note: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Nissan Note modela: benzinac, CVT menjač, trap, klima, enterijer, gradska upotreba i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Nemiran ler na polovnom autu: kada podrhtavanje otkriva nosače, dizne, usis ili struju',
                'slug' => 'nemiran-ler-na-polovnom-autu-kada-podrhtavanje-otkriva-nosace-dizne-usis-ili-struju',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Podrhtavanje u leru može biti sitan servis, ali može otkriti nosače motora, dizne, svećice, bobine, usis, vakum, EGR ili loše održavanje.',
                'content' => <<<'TEXT'
Nemiran ler je jedan od simptoma koje je lako potceniti na placu. Auto se malo trese, prodavac kaže da je hladan, klima je uključena ili je gorivo loše, a kupac već razmišlja o ceni. Nekad je uzrok zaista sitan. Ali podrhtavanje u leru može otvoriti priču o nosačima motora, diznama, bobinama, svećicama, usisu, vakumu, EGR-u ili zapuštenom servisu.

Prva provera je hladan start. Auto treba upaliti kada motor nije zagrejan i slušati prvih nekoliko minuta. Obrati pažnju na obrtaje, vibracije u volanu, sedištu i ručici menjača, miris goriva, dim i lampice na tabli. Ako se problem smanji kada se motor zagreje, to nije dokaz da je nestao, već trag za dijagnostiku.

Druga tema je ponašanje pod opterećenjem. Uključi klimu, svetla, grejače i pomeraj volan u mestu. Kod benzinca proveri svećice, bobine, usis i vakum. Kod dizela gledaj dizne, EGR, nosače i korekcije ubrizgavanja. Ako auto radi mirno samo kada je sve isključeno, svakodnevna vožnja može brzo pokazati više.

Treća provera je cena popravke. Nemiran ler nije dovoljan razlog za automatsko odustajanje, ali jeste razlog da se kvar precizno dijagnostikuje pre kapare. Prodavčeva procena da je "samo sitnica" nema vrednost bez očitanih grešaka, probne vožnje i mišljenja majstora. Kupac treba da plati auto koji radi mirno, ili da dobije popust za stvaran, a ne zamišljen kvar.
TEXT,
                'highlights' => [
                    'Nemiran ler treba proveriti na hladnom motoru, ne posle zagrevanja pred dolazak kupca.',
                    'Benzinci i dizeli imaju različite uzroke: svećice, bobine, usis, dizne, EGR ili nosači.',
                    'Popust ima smisla tek kada dijagnostika potvrdi stvaran uzrok podrhtavanja.',
                ],
                'tags' => ['nemiran ler', 'nosači motora', 'dizne', 'bobine', 'provera vozila'],
                'meta_title' => 'Nemiran ler na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti nemiran ler kod polovnog auta: hladan start, nosači motora, dizne, svećice, bobine, usis, EGR, vakum i dijagnostika.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Belgije: kada uredan oglas traži proveru kilometraže, korozije i jezika dokumentacije',
                'slug' => 'uvoz-auta-iz-belgije-kada-uredan-oglas-trazi-proveru-kilometraze-korozije-i-jezika-dokumentacije',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Belgijski uvoz može izgledati uredno i dobro dokumentovano, ali kilometraža, Car-Pass trag, korozija, oprema i dokumentacija moraju imati jasnu logiku.',
                'content' => <<<'TEXT'
Uvoz auta iz Belgije ume da deluje vrlo uredno. Oglasi su često jasni, automobili dobro opremljeni, a dokumentacija na prvi pogled ozbiljnija nego kod mnogih drugih tržišta. To je dobra početna tačka, ali nije razlog da se preskoči provera. Belgijsko poreklo ne garantuje da je auto imao lagan život, posebno ako je vozio po vlažnim uslovima, kratkim relacijama ili kao službeno vozilo.

Prva provera je kilometraža. Belgija je poznata po Car-Pass tragu, ali kupac i dalje mora da vidi da li se brojevi slažu sa servisima, tehničkim pregledima, stanjem enterijera i cenom. Ako prodavac ne može jasno objasniti dokumentaciju, prevod ili poreklo podataka, uredan oglas nije dovoljan dokaz.

Druga tema je korozija i donji deo auta. Vlažno vreme, so, zimski uslovi i gradska vožnja mogu ostaviti tragove na podu, pragovima, nosačima, kočnicama i izduvu. Ne mora svaki belgijski auto imati problem, ali pregled na dizalici treba da bude obavezan. Fotografije sjajne karoserije ne govore šta se dešava ispod.

Treća provera je oprema i jezik dokumentacije. Proveri da li VIN potvrđuje paket opreme, da li svi sistemi rade, i da li su računi, servisni zapisi i izvozni papiri razumljivi pre uplate. Dobar belgijski uvoz može biti odlična kupovina kada papiri, kilometraža i stanje pričaju istu priču. Ako jedna stvar ne može da se objasni, bolje je usporiti nego platiti uredno upakovan rizik.
TEXT,
                'highlights' => [
                    'Belgijski uvoz deluje uredno samo ako se kilometraža slaže sa dokumentacijom i stanjem auta.',
                    'Car-Pass trag je koristan, ali ne zamenjuje pregled enterijera, servisa i tehničkih zapisa.',
                    'Vlažni uslovi traže proveru poda, pragova, kočnica, izduva i donjeg postroja.',
                ],
                'tags' => ['uvoz iz Belgije', 'Car-Pass', 'uvoz auta', 'kilometraža', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Belgije: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Belgije: Car-Pass, kilometraža, dokumentacija, korozija, servisni trag, oprema, VIN i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Renault Twingo ili Smart Forfour: gradski auto kada okretanje i parkiranje vrede više od gepeka',
                'slug' => 'renault-twingo-ili-smart-forfour-gradski-auto-kada-okretanje-i-parkiranje-vrede-vise-od-gepeka',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Twingo i Smart Forfour nude neobičnu gradsku logiku sa zadnjim pogonom i sjajnim manevrisanjem, ali kupac mora proveriti motor, trap, klimu i realnu praktičnost.',
                'content' => <<<'TEXT'
Renault Twingo i Smart Forfour nisu mali automobili koji se kupuju samo zato što su kratki. Njihova priča je drugačija: motor pozadi, odličan krug okretanja, lako parkiranje i osećaj da se kroz grad provlačiš bez napora. To može biti velika prednost za vozača koji svaki dan traži mesto u uskim ulicama, ali nije automatski najbolji izbor ako mali auto mora da glumi porodični kompakt.

Twingo je prirodniji izbor za kupca koji želi jednostavniju servisnu mrežu, više oglasa i poznatiju Renault logistiku. Kabina je šarmantna, preglednost dobra, a grad mu je prirodno okruženje. Ipak, proveri motor, hlađenje, kvačilo, trap, klimu, zadnja vrata i tragove parking udaraca. Mali auto koji je stalno živeo u gradu često nosi mnogo sitnih tragova koje fotografije sakriju.

Smart Forfour privlači kupca koji želi nešto drugačije, kompaktnije i upečatljivije. Može biti vrlo prijatan za grad, ali traži hladnu proveru specifičnih delova, elektronike, opreme i servisne istorije. Prednost neobičnog koncepta postoji samo ako se konkretan primerak održavao bez improvizacije. Ako prodavac ne zna da objasni servisni trag, retkost modela više nije prednost nego rizik.

Ako je glavna rutina grad, kratke relacije i parkiranje, oba modela imaju smisla. Twingo je mirniji izbor kada želiš lakše poređenje i jednostavniji posed. Smart Forfour ima više karaktera, ali mora cenom i stanjem opravdati specifičnost. Najbolja kupovina je ona gde odlična okretnost dolazi uz ispravnu klimu, zdrav trap, jasne servise i gepek koji zaista odgovara tvojoj svakodnevici.
TEXT,
                'highlights' => [
                    'Twingo i Forfour imaju smisla kada je grad glavna ruta, a lako parkiranje stvarna prednost.',
                    'Twingo je mirniji za servis i poređenje oglasa, dok Forfour traži bolji dokaz stanja.',
                    'Kod oba modela proveri hlađenje, klimu, trap, kvačilo, elektroniku i tragove parking udaraca.',
                ],
                'tags' => ['Renault Twingo', 'Smart Forfour', 'gradski auto', 'mali auto', 'poređenje'],
                'meta_title' => 'Renault Twingo ili Smart Forfour: polovni gradski auto',
                'meta_description' => 'Poređenje polovnih Renault Twingo i Smart Forfour modela: gradska vožnja, parkiranje, motor pozadi, trap, klima, servis i realna praktičnost.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Seat Leon 5F: kompakt koji traži proveru TSI-a, TDI-a, DSG-a i trapa',
                'slug' => 'polovni-seat-leon-5f-kompakt-koji-trazi-proveru-tsi-a-tdi-a-dsg-a-i-trapa',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Seat Leon 5F često nudi Golf tehniku za življi novac, ali dobar primerak mora dokazati motor, DSG, trap, opremu i servisni trag.',
                'content' => <<<'TEXT'
Seat Leon 5F je jedan od onih polovnjaka koji lako privuče kupca koji želi kompakt, ali ne želi da plati punu cenu Golfa. Izgleda oštrije, često ima dobru opremu i koristi poznatu Volkswagen grupu mehanike. Upravo zato zna da zavara: kupac vidi racionalnu alternativu, a preskoči činjenicu da isti motori, menjači i elektronika traže istu ozbiljnu proveru.

Prva provera je motor. TSI verzije mogu biti vrlo prijatne za grad i otvoren put, ali traže uredne servise, miran hladan start, bez čudnog rada i bez potrošnje ulja koja se objašnjava kao normalna. TDI ima smisla za duže relacije, ali samo ako DPF, EGR, turbina, dizne i servis ulja imaju jasnu priču. Leon koji je radio kratke relacije kao dizel nije bolji zato što troši malo na papiru.

Druga tema je DSG i trap. Ako auto ima automatik, probna vožnja mora uključiti hladno kretanje, gradsko puzanje, rikverc, kočenje i ponovno ubrzanje. Trzaji, kašnjenje i nejasan servis ulja menjaju cenu. Trap proveri preko neravnina, jer veće felne, sportski izgled i gradska vožnja često ostave zvukove, krive felne ili umorne amortizere.

Treća provera je oprema. Ekran, klima, senzori, svetla, tempomat, podizači, brave i svi moduli moraju raditi bez slučajnih grešaka. Dobar Leon 5F može biti odličan kompakt za vozača koji želi više karaktera od proseka. Loš primerak je samo Golf rizik upakovan u atraktivniji oblik, sa računom koji ne pita koja značka stoji na haubi.
TEXT,
                'highlights' => [
                    'Leon 5F može biti odlična Golf alternativa, ali ne sme se proveravati površnije.',
                    'TSI, TDI i DSG traže servisni trag, hladnu probu i jasnu dijagnostiku.',
                    'Sportski izgled često znači da trap, felne i amortizeri moraju pažljivo na pregled.',
                ],
                'tags' => ['Seat Leon 5F', 'TSI', 'TDI', 'DSG', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Seat Leon 5F: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Seat Leon 5F modela: TSI, TDI, DSG, trap, elektronika, oprema, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Opel Zafira Tourer: sedam sedišta koja moraju opravdati dizel, automatiku i porodični umor',
                'slug' => 'polovni-opel-zafira-tourer-sedam-sedista-koja-moraju-opravdati-dizel-automatiku-i-porodicni-umor',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Zafira Tourer može biti razuman porodični monovolumen, ali kupac mora proveriti dizel, automatik, treći red, enterijer, trap i tragove teške upotrebe.',
                'content' => <<<'TEXT'
Opel Zafira Tourer kupuje se kada porodica preraste običan kompakt, ali ne želi ili ne može da plati veliki SUV. Na fotografijama deluje kao razuman odgovor: sedam sedišta, pristojan komfor, veliki gepek kada treći red nije u upotrebi i cena koja često izgleda poštenije od modernih crossovera. Ali baš zato treba proveriti da li je auto služio porodici ili je porodica već potrošila ono najbolje iz njega.

Prva provera je motor. Dizel može imati smisla ako je Zafira vozila duže relacije i redovno servisirana, ali DPF, EGR, turbina, dizne i plivajući zamajac moraju biti deo pregleda. Benzinac je mirniji za kraće relacije, ali ne znači da može bez provere potrošnje ulja, hlađenja i servisnog ritma. Težak monovolumen ne prašta zapušteno održavanje.

Druga tema je menjač i trap. Automatik mora menjati glatko hladan i topao, bez trzaja i kašnjenja. Manuelni traži proveru kvačila i zamajca. Trap, kočnice, gume i amortizeri trpe mnogo kada se auto vozi natovaren, sa decom, prtljagom i čestim putovanjima. Probna vožnja treba da uključi neravnine, kočenje i slušanje zadnjeg dela.

Treća provera je porodični umor. Pogledaj treći red sedišta, mehanizme sklapanja, klimu pozadi, pod, tapacire, vrata, gepek, ISOFIX, brave i stanje plastika. Dobra Zafira Tourer može biti vrlo praktičan auto za realan novac. Loša Zafira je dokaz da sedam sedišta nije prednost ako svako sedište krije odloženi račun.
TEXT,
                'highlights' => [
                    'Zafira Tourer ima smisla kada sedam sedišta stvarno rešava porodičnu rutinu.',
                    'Dizel, automatik, kvačilo, zamajac i DPF moraju imati jasnu servisnu priču.',
                    'Treći red, klima, enterijer i zadnji trap otkrivaju koliko je auto bio porodično potrošen.',
                ],
                'tags' => ['Opel Zafira Tourer', 'sedam sedišta', 'monovolumen', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Opel Zafira Tourer: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Opel Zafira Tourer modela: sedam sedišta, dizel, automatik, DPF, trap, enterijer, klima i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Letva volana na polovnom autu: kada lupkanje, težak volan i servo otkrivaju skup račun',
                'slug' => 'letva-volana-na-polovnom-autu-kada-lupkanje-tezak-volan-i-servo-otkrivaju-skup-racun',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Problem sa letvom volana ne mora biti samo sitan zvuk iz trapa; može uključiti servo pumpu, elektroniku, curenje, spone i skupu reparaciju.',
                'content' => <<<'TEXT'
Letva volana je deo koji kupac često primeti tek kada problem već postane očigledan. Auto malo lupka preko neravnina, volan je teži pri parkiranju ili se čuje čudan zvuk pri okretanju. Prodavac to često objasni kao spone, gumice ili normalan zvuk trapa. Nekad je zaista sitnica, ali nekad je početak skupe priče oko letve, servo sistema, elektronike ili loše prethodne popravke.

Prva provera je vožnja preko neravnina i okretanje volana u mestu. Obrati pažnju na lupkanje, preskakanje, nejednak otpor, zujanje pumpe, lampicu volana i da li auto vuče u stranu. Ako se volan ne vraća prirodno ili se ponašanje menja između hladnog i toplog stanja, problem treba razumeti pre kapare.

Druga tema je curenje i mehanički pregled. Kod hidrauličnog serva proveri nivo ulja, creva, pumpu i tragove curenja oko letve. Kod električnog serva proveri greške, senzore, instalaciju i ponašanje pri parkiranju. Spone, krajevi spona, kugle, amortizeri i gume mogu dati slične simptome, zato pregled na dizalici mora razdvojiti uzrok.

Treća provera je cena popravke. Reparacija letve, nova pumpa, električni modul ili rad na instalaciji mogu brzo pojesti popust koji je delovao dobar. Ako prodavac tvrdi da je "samo trap", neka majstor potvrdi šta je tačno. Dobar polovan auto treba da skreće tiho, precizno i predvidljivo. Sve drugo je stavka za ozbiljan pregled, ne samo tema za kratko cenkanje.
TEXT,
                'highlights' => [
                    'Lupkanje, težak volan ili zujanje pri parkiranju ne treba svesti na običnu sitnicu.',
                    'Hidraulični i električni servo imaju različite rizike i različitu cenu popravke.',
                    'Letva, spone, kugle, amortizeri i gume moraju se razdvojiti pregledom na dizalici.',
                ],
                'tags' => ['letva volana', 'servo volan', 'trap', 'provera vozila', 'polovni auto'],
                'meta_title' => 'Letva volana na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti letvu volana kod polovnog auta: lupkanje, težak volan, servo pumpa, električni servo, curenje, spone, trap i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Poljske: kada dobra cena traži proveru korozije, kilometraže i porekla',
                'slug' => 'uvoz-auta-iz-poljske-kada-dobra-cena-trazi-proveru-korozije-kilometraze-i-porekla',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Poljski uvoz može ponuditi dobru cenu i solidan izbor, ali kupac mora proveriti poreklo, koroziju, kilometražu, servisni trag i kvalitet popravki.',
                'content' => <<<'TEXT'
Uvoz auta iz Poljske sve češće ulazi u razgovor kada kupac traži bolju cenu ili model koji nije lako naći lokalno. Tržište je veliko, izbor može biti zanimljiv, a oglasi ponekad deluju povoljnije od zapadnoevropskih primeraka. Ali dobra cena ne sme da zameni pitanje porekla. Kod svakog uvoza iz Poljske treba razumeti da li je auto zaista poljski primerak, prethodno uvezen iz druge zemlje ili popravljan za dalju prodaju.

Prva provera je dokumentacija. VIN, servisni zapisi, tehnički pregledi, računi, izvozna dokumentacija i broj vlasnika moraju se slagati sa pričom prodavca. Ako je auto pre Poljske došao iz Nemačke, Francuske, Belgije ili aukcije, kupac mora znati celu putanju. Poreklo nije problem samo po sebi, ali nejasno poreklo jeste.

Druga tema je korozija i kvalitet popravki. Zimski uslovi, so, loši putevi i brze kozmetičke pripreme mogu sakriti stanje poda, pragova, rubova, nosača, kočnica i izduva. Pregled na dizalici je obavezan, kao i merenje laka. Sveže ofarban branik nije problem ako ima objašnjenje, ali neujednačeni zazori, različite nijanse i jeftini farovi menjaju celu računicu.

Treća provera je kilometraža i oprema. Broj na satu mora imati logiku sa enterijerom, servisima, gumama, kočnicama i cenom. Proveri da li oprema odgovara VIN-u i da li rade klima, senzori, kamera, svetla i asistencije. Poljski uvoz može biti dobra kupovina kada cena priznaje stanje i dokumentacija nema rupa. Ako je jedini argument "povoljnije je", kupac treba da uspori.
TEXT,
                'highlights' => [
                    'Kod uvoza iz Poljske prvo proveri punu putanju vozila, ne samo zemlju iz oglasa.',
                    'Korozija, pod, pragovi i kvalitet popravki moraju se gledati na dizalici i merenjem laka.',
                    'Dobra cena ima smisla samo kada kilometraža, oprema i dokumentacija pričaju istu priču.',
                ],
                'tags' => ['uvoz iz Poljske', 'uvoz auta', 'korozija', 'kilometraža', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Poljske: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Poljske: poreklo, VIN, kilometraža, korozija, servisna istorija, kvalitet popravki, oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Toyota Aygo ili Citroen C1: gradski blizanci kada niska potrošnja nije cela priča',
                'slug' => 'toyota-aygo-ili-citroen-c1-gradski-blizanci-kada-niska-potrosnja-nije-cela-prica',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Aygo i C1 dele istu gradsku logiku, ali kupac mora gledati stanje, servis, kvačilo, trap i realnu cenu umesto samo male potrošnje.',
                'content' => <<<'TEXT'
Toyota Aygo i Citroen C1 često deluju kao jednostavan odgovor kada treba mali auto za grad, početnika ili drugi porodični automobil. Kratki su, pregledni, troše malo i lako se parkiraju. Ali baš zato kupci često spuste gard i očekuju da mali auto automatski znači malu brigu. Kod polovnjaka te klase najskuplje greške ne dolaze iz luksuza, nego iz zanemarenih sitnica.

Aygo ima prednost reputacije Toyote i često se lakše brani u razgovoru o kasnijoj prodaji. To ne znači da svaki primerak zaslužuje višu cenu. Proveri hladan start, kvačilo, menjač, lanac, curenja, klimu i stanje enterijera. Ako je auto godinama radio kratke relacije, akumulator, kočnice i izduv mogu biti umorniji nego što kilometraža sugeriše.

C1 je često povoljniji i može biti jednako dobar ako je konkretan primerak uredan. Njegova prednost je što kupac ponekad dobije bolji odnos cene i stanja jer značka ne nosi istu reputacijsku premiju. Ipak, treba gledati iste stvari: motor, kvačilo, trap, gume, vrata, podizače, klimu i tragove parking udaraca. Kod malog gradskog auta oštećeni branici i felne često pričaju istoriju svakodnevice.

Ako su oba auta sličnog stanja, Aygo ima prednost za kupca koji želi mirniju kasniju prodaju. C1 ima smisla kada je cena realnija, servis jasniji ili je primerak očuvaniji. Najbolji izbor nije onaj koji najmanje troši na papiru, nego onaj koji neće odmah tražiti kvačilo, kočnice, gume, akumulator i servis koji je prethodni vlasnik odlagao.
TEXT,
                'highlights' => [
                    'Aygo i C1 su bliski tehnički, pa stanje konkretnog primerka često vredi više od značke.',
                    'Kvačilo, kočnice, akumulator, klima i trap otkrivaju koliko je auto živeo u gradu.',
                    'Mala potrošnja nema smisla ako prvi mesec donese servis koji je prodavac odlagao.',
                ],
                'tags' => ['Toyota Aygo', 'Citroen C1', 'gradski auto', 'mali auto', 'poređenje'],
                'meta_title' => 'Toyota Aygo ili Citroen C1: koji mali polovnjak',
                'meta_description' => 'Poređenje polovnih Toyota Aygo i Citroen C1 modela: gradska vožnja, potrošnja, kvačilo, trap, klima, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Ford Fiesta: mali auto koji traži proveru EcoBoost-a, trapa i gradske upotrebe',
                'slug' => 'polovni-ford-fiesta-mali-auto-koji-trazi-proveru-ecoboost-a-trapa-i-gradske-upotrebe',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Fiesta može biti odličan mali auto za vozača koji želi življi osećaj, ali kupac mora proveriti motor, servisni ritam, trap, kvačilo i elektroniku.',
                'content' => <<<'TEXT'
Ford Fiesta je mali auto koji često privuče kupca koji ne želi da gradska vožnja bude dosadna. Upravljanje je prijatno, dimenzije su razumne, a ponuda polovnjaka široka. Zato Fiesta lako deluje kao sigurnija kupovina od egzotičnijih malih automobila. Problem je što popularan model ne oprašta površnu proveru, posebno kada je godinama korišćen po gradu.

Prva provera je motor. EcoBoost verzije mogu biti vrlo dobre kada imaju jasan servisni ritam, kvalitetno ulje i dokumentaciju. Bez toga, mala zapremina i turbo priča traže oprez. Proveri hladan start, curenja, potrošnju rashladne tečnosti, rad turbine, servisne račune i da li je vlasnik znao šta vozi. Jednostavniji benzinci mogu biti mirniji izbor ako je budžet skroman.

Druga tema je trap i gradski život. Fiesta se često vozi življe, preko ivičnjaka, rupa i kratkih relacija. Probna vožnja treba da uključi neravnine, kočenje, okretanje volana i slušanje prednjeg kraja. Kvačilo, menjač, kočnice, gume i amortizeri mogu brzo poništiti razliku između jeftinog oglasa i urednog primerka.

Treća provera je elektronika i kabina. Klima, podizači, brave, multimedija, senzori, svetla i instrument tabla moraju raditi bez izgovora. Dobra Fiesta je odličan mali polovnjak za vozača koji želi praktičnost sa malo karaktera. Loša Fiesta je dokaz da mali auto sa lepim volanom i dobrom cenom i dalje može sakriti ozbiljan prvi račun.
TEXT,
                'highlights' => [
                    'Fiesta ima odličan gradski karakter, ali EcoBoost traži dokumentovan servisni ritam.',
                    'Trap, kvačilo, kočnice i gume često otkrivaju da li je auto vožen grubo po gradu.',
                    'Kod malog auta ne preskači klimu, brave, podizače, svetla i instrument tablu.',
                ],
                'tags' => ['Ford Fiesta', 'EcoBoost', 'mali auto', 'gradska upotreba', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Ford Fiesta: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Ford Fiesta modela: EcoBoost, benzinac, trap, kvačilo, klima, gradska upotreba, servis i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Citroen C4 Picasso: porodični monovolumen koji ne sme da sakrije elektroniku i EGS',
                'slug' => 'polovni-citroen-c4-picasso-porodicni-monovolumen-koji-ne-sme-da-sakrije-elektroniku-i-egs',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C4 Picasso nudi mnogo prostora i udobnosti za porodicu, ali dobar primerak mora dokazati elektroniku, menjač, dizel sistem, trap i enterijer.',
                'content' => <<<'TEXT'
Citroen C4 Picasso je auto koji porodicu lako kupi prostorom, svetlom kabinom i osećajem da je neko stvarno mislio o svakodnevici. Kada deca, torbe, kolica i vikend putovanja postanu redovna rutina, ovakav monovolumen može imati više smisla od skupljeg SUV-a. Ali udobna porodična priča ne sme da sakrije pitanje održavanja, posebno kod primeraka sa bogatom opremom.

Prva provera je motor i menjač. Dizel može biti dobar izbor ako je auto vozio duže relacije i ima uredne servise, ali DPF, EGR, turbina, dizne i plivajući zamajac moraju biti jasni. Ako primerak ima robotizovani EGS menjač, probna vožnja mora pokazati kako kreće, menja brzine, manevriše i ponaša se u gužvi. Trzaji nisu stvar ukusa kada popravka ulazi u budžet.

Druga tema je elektronika. Ekrani, klima, parking senzori, kamera, električna ručna, brave, podizači, svetla, instrument tabla i svi porodični dodaci moraju raditi bez slučajnih upozorenja. Prodavčevo "to je samo senzor" nema vrednost dok dijagnostika ne kaže šta je stvarno. Kod ovakvog auta oprema je prednost samo ako radi.

Treća provera je porodični umor. Pogledaj sedišta, ISOFIX, stočiće, gepek, pod, tapacire, vrata, klizne pregrade, zadnji trap i kočnice. Dobar C4 Picasso može biti vrlo razumna kupovina za porodicu koja želi prostor bez SUV cene. Loš primerak je udobna kabina puna sitnih problema koji se sabiraju brže nego što kupac očekuje.
TEXT,
                'highlights' => [
                    'C4 Picasso ima smisla kada porodici treba prostor, ali oprema mora raditi bez izgovora.',
                    'EGS menjač, DPF, EGR i dizel servisni trag treba proveriti pre pregovora.',
                    'Enterijer, ISOFIX, gepek i zadnji trap otkrivaju koliko je auto već porodično potrošen.',
                ],
                'tags' => ['Citroen C4 Picasso', 'monovolumen', 'EGS', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Citroen C4 Picasso: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Citroen C4 Picasso modela: dizel, EGS menjač, elektronika, klima, porodična upotreba, trap i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Check engine lampica na polovnom autu: kada obrisana greška vredi više od probne vožnje',
                'slug' => 'check-engine-lampica-na-polovnom-autu-kada-obrisana-greska-vredi-vise-od-probne-voznje',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Check engine lampica ne mora značiti skup kvar, ali obrisane greške, readiness status i ponavljanje simptoma mogu otkriti šta oglas pokušava da preskoči.',
                'content' => <<<'TEXT'
Check engine lampica je jedna od onih stvari koju kupac ne želi da vidi na polovnom autu. Još opasnija je situacija kada je ne vidi, a prodavac usput kaže da je "pre neki dan obrisana neka stara greška". Lampica sama po sebi nije presuda. Problem je kada se kvar ne razume, kada se greške brišu pred dolazak kupca ili kada probna vožnja nije dovoljno duga da pokaže da se simptom vraća.

Prva provera je dijagnostika pre brisanja. Ako greška postoji, treba je očitati, zapisati i povezati sa simptomima. Sonda, bobina, EGR, DPF, katalizator, dizne, senzor pritiska, usis ili loš akumulator mogu svi otvoriti različite račune. Prodavčeva rečenica da je "samo senzor" ne znači ništa bez koda greške i pregleda.

Druga tema je readiness status. Posle brisanja grešaka, neki sistemi još nisu završili samoproveru. Auto može kratko delovati čist, a problem se vratiti posle određene vožnje. Zato je važno gledati da li su monitori spremni, da li se lampica vraća posle hladnog starta, gradske vožnje, otvorenog puta i jačeg ubrzanja.

Treća provera je računica. Check engine nije automatski razlog za odustajanje, ali jeste razlog da se kvar dijagnostikuje pre kapare. Ako prodavac ne dozvoljava dijagnostiku ili insistira da se greška ignoriše, rizik prelazi na kupca. Dobar auto ne mora biti bez ijedne stare greške u istoriji, ali mora imati objašnjenje koje majstor može da potvrdi.
TEXT,
                'highlights' => [
                    'Check engine grešku treba očitati pre brisanja i povezati je sa stvarnim simptomima.',
                    'Readiness status posle brisanja grešaka može otkriti da provera nije završena.',
                    'Popust ima smisla tek kada majstor zna uzrok i cenu popravke.',
                ],
                'tags' => ['check engine', 'dijagnostika', 'obrisane greške', 'provera vozila', 'polovni auto'],
                'meta_title' => 'Check engine lampica na polovnom autu',
                'meta_description' => 'Kako proveriti check engine lampicu kod polovnog auta: dijagnostika, obrisane greške, readiness status, EGR, DPF, sonde, dizne i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Danske: kada uredan servis traži proveru korozije, poreza i opreme',
                'slug' => 'uvoz-auta-iz-danske-kada-uredan-servis-trazi-proveru-korozije-poreza-i-opreme',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Danski uvoz može imati dobru servisnu disciplinu, ali kupac mora proveriti koroziju, poreklo, izvoznu dokumentaciju, opremu i realan razlog cene.',
                'content' => <<<'TEXT'
Uvoz auta iz Danske kod kupaca često zvuči uredno: strogo tržište, servisna disciplina i automobili koji na fotografijama deluju čisto. To može biti dobra polazna tačka, ali nije garancija mirne kupovine. Danska ima svoje specifičnosti, od poreza i izvozne dokumentacije do vremenskih uslova koji mogu ostaviti trag na donjem delu automobila.

Prva provera je dokumentacija i poreklo cene. Auto koji napušta dansko tržište može imati dobar razlog za izvoz, ali kupac treba da razume papire, vlasništvo, servisne račune, tehničke zapise i da li cena odražava stanje ili samo komplikovan put do registracije. Ako posrednik ne ume da objasni dokumentaciju, uredna fotografija nije dovoljna.

Druga tema je korozija. Vlažno vreme, zima, so i otvoreni putevi mogu uticati na pod, pragove, nosače, kočnice, izduv i zavrtnje. Pregled na dizalici je obavezan, naročito kod starijih auta i vozila koja su dosta vozila zimi. Auto može izgledati odlično spolja, a da ispod traži ulaganja koja oglas ne pominje.

Treća provera je oprema i kilometraža. VIN treba da potvrdi paket opreme, a svi sistemi moraju raditi: svetla, klima, grejanje sedišta, senzori, kamera, multimedija i asistencije. Kilometraža mora imati logiku sa enterijerom, servisima i gumama. Dobar danski uvoz ima smisla kada su papiri, stanje i cena u istoj priči. Ako jedna od te tri stvari škripi, kupac treba da uspori.
TEXT,
                'highlights' => [
                    'Danski uvoz može biti uredan, ali dokumentacija i razlog izvoza moraju biti jasni.',
                    'Vlažni i zimski uslovi traže pregled poda, pragova, kočnica i izduva na dizalici.',
                    'VIN, oprema, kilometraža i servisni trag moraju se slagati pre kapare.',
                ],
                'tags' => ['uvoz iz Danske', 'uvoz auta', 'korozija', 'kilometraža', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Danske: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Danske: servisna istorija, izvozna dokumentacija, korozija, poreklo cene, oprema, VIN i realno stanje.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Lancia Ypsilon ili Fiat Punto: mali auto kada stil i servis moraju da se slože',
                'slug' => 'lancia-ypsilon-ili-fiat-punto-mali-auto-kada-stil-i-servis-moraju-da-se-sloze',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Ypsilon i Punto mogu biti povoljni gradski automobili, ali kupac mora odlučiti da li plaća izgled, jednostavnost, dostupnost delova ili stvarno stanje.',
                'content' => <<<'TEXT'
Miloš je tražio mali auto za suprugu i kratke gradske relacije, a u užem izboru ostali su Lancia Ypsilon iz oglasa sa lepšim enterijerom i Fiat Punto koji je delovao skromnije, ali je imao jasnije račune. Na fotografijama je Ypsilon izgledao kao bolja prilika. Na papiru je Punto bio dosadniji, ali lakši za proveru i servis. To je tačna dilema kod ovih modela: stil protiv jednostavnije računice.

Ypsilon ima smisla kada kupac želi mali auto koji ne deluje potpuno obično. Kabina, boje i detalji mogu podići osećaj vrednosti, ali ne smeju sakriti osnovne provere. Hladan start, kvačilo, menjač, trap, klima, brave, podizači i stanje enterijera moraju biti uredni. Ako prodavac traži višu cenu samo zbog izgleda, servisna istorija mora pratiti tu priču.

Punto je racionalniji izbor kada je budžet ograničen i kada kupac želi auto koji većina servisa poznaje. Prednost su dostupniji delovi, mnogo iskustva na tržištu i lakše poređenje oglasa. Mana je što su mnogi primerci dugo služili kao jeftin gradski alat, pa treba gledati pragove, trap, kvačilo, grejanje, klimu, elektroniku i tragove odloženog održavanja.

Ako je Ypsilon uredniji, sa dokazanim servisom i bez sitnih elektronskih izgovora, može opravdati višu cenu za kupca kome je izgled važan. Ako je Punto mlađi, dokumentovaniji i jeftiniji za prvo sređivanje, bolja je kupovina. Kod oba modela ne plaćaj fotografije nego stanje: mali auto koji odmah traži kvačilo, gume, klimu i trap brzo prestaje da bude povoljan.
TEXT,
                'highlights' => [
                    'Ypsilon vredi više samo kada izgled prati uredna mehanika i jasna servisna istorija.',
                    'Punto je racionalniji kada kupac želi dostupnije delove i lakše poređenje oglasa.',
                    'Kod oba modela kvačilo, trap, klima, brave i gradski tragovi odlučuju stvarnu cenu.',
                ],
                'tags' => ['Lancia Ypsilon', 'Fiat Punto', 'mali auto', 'gradska vožnja', 'poređenje'],
                'meta_title' => 'Lancia Ypsilon ili Fiat Punto: koji polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Lancia Ypsilon i Fiat Punto modela: gradska vožnja, servis, delovi, trap, kvačilo, klima, elektronika i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Chevrolet Cruze: limuzina koja traži proveru delova, servisa i dizela',
                'slug' => 'polovni-chevrolet-cruze-limuzina-koja-trazi-proveru-delova-servisa-i-dizela',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Cruze često privuče kupca cenom, prostorom i ozbiljnim izgledom, ali dobar primerak mora dokazati servis, dostupnost delova, motor i elektroniku.',
                'content' => <<<'TEXT'
Jelena je gledala kompakte do skromnog budžeta, ali joj je Chevrolet Cruze delovao kao klasa više za isti novac: veća limuzina, ozbiljan izgled i oprema koja kod popularnijih modela košta više. Upravo tu Cruze najčešće hvata kupca. Cena deluje privlačno, ali pitanje nije samo koliko auto košta danas, nego koliko lako možeš rešiti servis i delove posle kupovine.

Prva provera je motor i servisni ritam. Benzinske verzije treba slušati hladne, gledati curenja, rad u leru, hlađenje i račune za redovne servise. Dizel može biti prijatan na otvorenom putu, ali traži proveru turbine, dizni, DPF-a, EGR-a, zamajca i načina prethodne vožnje. Ako je Cruze kupljen zato što je bio jeftiniji od konkurencije, proveri da li je i održavanje bilo jeftino, a ne redovno.

Druga tema je dostupnost delova i elektronika. Klima, instrument tabla, senzori, svetla, brave, podizači i multimedija moraju raditi bez objašnjenja da je "to sitnica". Pre kapare proveri kod servisa koliko koštaju delovi za konkretan motor i godište. Povoljan oglas nema smisla ako prvi kvar pretvori čekanje dela u skuplju kupovinu nego što si planirao.

Cruze ima smisla kada je primerak dokumentovan, motor radi mirno, elektronika je uredna, a servis zna šta kupuješ. Nema smisla ako je jedini argument niska cena i veći auto za manje novca. Bolje je platiti uredan kompakt sa jasnim tragom nego limuzinu koja već na prvoj proveri traži opravdanja za motor, delove i opremu.
TEXT,
                'highlights' => [
                    'Cruze privlači cenom i prostorom, ali servisni trag mora biti jači od povoljnog oglasa.',
                    'Dizel traži proveru turbine, dizni, DPF-a, EGR-a i zamajca pre pregovora.',
                    'Dostupnost delova i ispravnost elektronike treba proveriti pre kapare.',
                ],
                'tags' => ['Chevrolet Cruze', 'limuzina', 'dizel', 'delovi', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Chevrolet Cruze: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Chevrolet Cruze modela: benzinac, dizel, DPF, EGR, delovi, elektronika, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Dacia Lodgy: sedam sedišta kada niska cena mora da dokaže porodični život',
                'slug' => 'polovni-dacia-lodgy-sedam-sedista-kada-niska-cena-mora-da-dokaze-porodicni-zivot',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Lodgy nudi mnogo prostora za malo novca, ali kupac mora proveriti da li su sedišta, trap, klima, dizel i enterijer već potrošeni porodičnom upotrebom.',
                'content' => <<<'TEXT'
Porodica iz Pančeva tražila je sedam sedišta bez SUV cene i brzo je stigla do Dacia Lodgy. Na oglasu je delovala kao praktično rešenje: veliki gepek, jednostavna kabina i cena koja ostavlja novac za registraciju i prvi servis. Ali kod Lodgyja niska cena nije kraj računice. To je početak provere koliko je auto već radio za porodicu, posao ili oba.

Prva provera je enterijer. Sedišta, šine, preklapanje drugog i trećeg reda, pojasevi, ISOFIX, tapaciri, gepek i plastike govore koliko je auto zaista korišćen. Lodgy često nosi decu, prtljag, kolica, alat ili robu, pa uredna karoserija ne znači da kabina nije umorna. Ako treći red ne radi lako ili klima ne hladi celu kabinu, prostor gubi deo vrednosti.

Druga tema je motor, trap i kočnice. Dizel ima smisla za duže relacije, ali DPF, EGR, turbina, dizne i servis ulja moraju imati jasan trag. Benzinac može biti mirniji za kraće vožnje, ali težak auto traži proveru potrošnje, kvačila i hlađenja. Trap, amortizeri, gume i kočnice posebno trpe kada je auto često natovaren.

Lodgy je dobra kupovina kada kupac stvarno koristi prostor i dobija primerak sa razumljivom istorijom. Nije dobra kupovina ako je sedam sedišta samo ideja, a auto odmah traži klimu, gume, trap i veliki servis. Kod ovakvog auta prvo probaj porodični scenario: sedišta, gepek, dečja oprema, ulazak i probna vožnja preko neravnina. Ako tu prođe, cena tek tada postaje zanimljiva.
TEXT,
                'highlights' => [
                    'Lodgy ima smisla kada zaista koristiš sedam sedišta i veliki gepek.',
                    'Treći red, klima, tapaciri i ISOFIX otkrivaju stvaran porodični umor.',
                    'Dizel, trap, kočnice i gume moraju priznati težinu i čestu natovarenost auta.',
                ],
                'tags' => ['Dacia Lodgy', 'sedam sedišta', 'porodični auto', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Dacia Lodgy: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Dacia Lodgy modela: sedam sedišta, dizel, klima, trap, kočnice, enterijer, ISOFIX i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Auto sa samo jednim ključem: kada sitnica otkriva papire, elektroniku ili rizik',
                'slug' => 'auto-sa-samo-jednim-kljucem-kada-sitnica-otkriva-papire-elektroniku-ili-rizik',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Jedan ključ ne mora značiti problem, ali može otvoriti pitanja vlasništva, uvoza, krađe, elektronike, kodiranja i stvarnog troška posle kupovine.',
                'content' => <<<'TEXT'
Nikola je skoro završio kupovinu urednog karavana kada je prodavac usput rekao da postoji samo jedan ključ. Delovalo je kao sitnica, ali majstor ga je zaustavio: kod polovnog auta ključ nije samo komad plastike. On dodiruje papire, elektroniku, imobilajzer, alarm, brave i pitanje da li je istorija vozila kompletna.

Prva provera je objašnjenje. Jedan ključ može biti izgubljen tokom godina, ali prodavac treba da zna kada i kako. Ako je auto tek uvezen, bio na lizingu, kupljen na aukciji ili menjao više vlasnika, nedostatak drugog ključa traži više pažnje. Dokumentacija, VIN, vlasništvo i servisni zapisi moraju biti jasni pre nego što se tema svede na mali popust.

Druga tema je trošak i bezbednost. Moderni ključ može uključiti daljinsko otključavanje, imobilajzer, keyless sistem i kodiranje kod ovlašćenog ili specijalizovanog servisa. Nije isto napraviti običan metalni ključ i programirati pametni ključ. Proveri cenu, rok i da li stari izgubljeni ključ može biti obrisan iz sistema ako postoji sumnja.

Kupovina nije automatski loša zbog jednog ključa, ali ne treba dati kaparu dok se ne proveri vlasništvo, cena izrade i rad svih brava. Ako prodavac odbija da tema uđe u ugovor ili pregovor, rizik ostaje kupcu. Dobar dogovor je onaj u kome se trošak novog ključa i provera elektronike priznaju pre prenosa, a ne posle prvog zaključavanja na parkingu.
TEXT,
                'highlights' => [
                    'Jedan ključ traži proveru vlasništva, uvozne dokumentacije i objašnjenja prodavca.',
                    'Cena novog ključa zavisi od imobilajzera, keyless sistema i kodiranja.',
                    'Izgubljeni ključ treba obrisati iz sistema kada postoji bezbednosna sumnja.',
                ],
                'tags' => ['jedan ključ', 'imobilajzer', 'keyless', 'dokumentacija', 'provera vozila'],
                'meta_title' => 'Auto sa samo jednim ključem: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto sa jednim ključem: vlasništvo, dokumentacija, imobilajzer, keyless, kodiranje, brave, bezbednost i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Austrije: kada uredan servis traži proveru soli, porekla i cene',
                'slug' => 'uvoz-auta-iz-austrije-kada-uredan-servis-trazi-proveru-soli-porekla-i-cene',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Austrijski uvoz može imati uredne servise i bogatu opremu, ali planinski uslovi, so, korozija, poreklo i realna cena moraju biti provereni.',
                'content' => <<<'TEXT'
Kupac iz Novog Sada je našao karavan iz Austrije sa servisnom istorijom, dobrim gumama i opremom koja je na domaćem tržištu retka. Oglas je delovao ubedljivo jer austrijski automobili često nose sliku urednog održavanja. Ipak, poreklo samo otvara vrata. Ne govori dovoljno o zimama, soli, planinskim putevima, prethodnoj nameni i razlogu zašto auto sada dolazi u Srbiju.

Prva provera je dokumentacija. Servisni računi, tehnički pregledi, vlasništvo, izvozna dokumentacija i VIN moraju se slagati sa kilometražom i stanjem. Ako je auto bio službeni, karavan za duge relacije ili porodični automobil iz planinskog kraja, cena mora priznati način korišćenja. Uredan pečat ne govori sam koliko su trap, kočnice i pod već videli zime.

Druga tema je so i donji deo auta. Austrijski putevi zimi mogu ostaviti trag na pragovima, podu, nosačima, kočionim cevima, izduvu, šrafovima i vešanju. Pregled na dizalici je obavezan, čak i kada karoserija blista. Posebno gledaj tragove svežeg premaza koji može biti zaštita, ali može biti i pokušaj da se sakrije rđa.

Austrijski uvoz ima smisla kada dokumenti, kilometraža, oprema i stanje podvozja pričaju istu priču. Ako je cena niža od sličnih primeraka, prvo pronađi razlog: kilometraža, korozija, flotna upotreba, skupi servis ili oprema koja ne radi. Dobar auto iz Austrije može biti odlična kupovina, ali tek posle pregleda koji ide ispod fotografija i reputacije tržišta.
TEXT,
                'highlights' => [
                    'Austrijsko poreklo nije dovoljno bez provere servisnih računa, VIN-a i izvozne dokumentacije.',
                    'Zimska so traži pregled poda, pragova, kočionih cevi, izduva i vešanja na dizalici.',
                    'Niža cena mora imati objašnjenje pre kapare, posebno kod karavana i službenih vozila.',
                ],
                'tags' => ['uvoz iz Austrije', 'uvoz auta', 'korozija', 'servisna istorija', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Austrije: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Austrije: servisna istorija, VIN, dokumentacija, so, korozija, podvozje, oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Renault Espace ili Ford Galaxy: sedam sedišta kada porodica ne želi SUV cenu',
                'slug' => 'renault-espace-ili-ford-galaxy-sedam-sedista-kada-porodica-ne-zeli-suv-cenu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Espace i Galaxy mogu dati mnogo prostora za manje novca od SUV-a, ali kupac mora proveriti automatiku, klimu, sedišta i porodični umor.',
                'content' => <<<'TEXT'
Jovana i Marko su posle trećeg dečjeg sedišta shvatili da kompaktni SUV više ne rešava svaki dan. U oglasima su im Renault Espace i Ford Galaxy delovali kao razumniji odgovor od skupljeg sedmoseda sa SUV oznakom: prava sedišta, veliki gepek kada se treći red spusti i cena koja ostavlja novac za prvi servis. Ali kod velikog monovolumena prostor je samo početak provere, ne dokaz da je kupovina dobra.

Espace je privlačniji kada želiš udobniji, moderniji osećaj i auto koji se ne vozi kao kombi. Treba proveriti elektroniku, automatski menjač, rad svih ekrana, klimu u celoj kabini, amortizere, trap i tragove skupog održavanja koje je prethodni vlasnik možda odlagao. Ako je primerak bogato opremljen, svaka funkcija mora raditi bez izgovora, jer porodični komfor vredi samo dok ne postane lista sitnih kvarova.

Galaxy je jači kandidat kada prioritet nisu stil i dizajn, nego izdržljiv prostor. Kod njega su važni dizel, automatik ako postoji, zadnji trap, kočnice, klizanje i preklapanje sedišta, klima za zadnje putnike i stanje enterijera. Primerak koji je vozio decu, prtljag i duge odmore može biti poštena kupovina, ali samo ako habanje prati cenu. Ako treći red, pojasevi, ISOFIX i tapaciri ne izgledaju uverljivo, pregovaranje počinje odmah.

Ako porodici zaista treba sedam sedišta, Espace ima smisla kada želiš udobniji auto i možeš da dokažeš urednu elektroniku i servis. Galaxy ima prednost kada tražiš jednostavniju porodičnu alatku i veći prag tolerancije na svakodnevnu upotrebu. U oba slučaja ne plaćaj samo broj sedišta: povedi porodicu na probu, ubaci sedišta i kolica, proveri klimu i dogovori pregled koji će reći da li je cena prostora već potrošena.
TEXT,
                'highlights' => [
                    'Sedam sedišta vredi samo ako treći red, ISOFIX, pojasevi i klima stvarno rade za porodicu.',
                    'Espace traži strožu proveru elektronike, automatika i bogate opreme.',
                    'Galaxy ima smisla kada stanje enterijera, trapa i kočnica priznaje porodičnu upotrebu.',
                ],
                'tags' => ['Renault Espace', 'Ford Galaxy', 'sedam sedišta', 'monovolumen', 'poređenje'],
                'meta_title' => 'Renault Espace ili Ford Galaxy: koji polovnjak',
                'meta_description' => 'Poređenje polovnih Renault Espace i Ford Galaxy modela: sedam sedišta, klima, automatik, trap, enterijer, porodična upotreba i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Volkswagen Sharan: porodični van koji mora dokazati DSG, klizna vrata i kabinu',
                'slug' => 'polovni-volkswagen-sharan-porodicni-van-koji-mora-dokazati-dsg-klizna-vrata-i-kabinu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Sharan je praktičan izbor za velike porodice, ali dobar primerak mora pokazati uredan DSG, klizna vrata, klimu i realan porodični trag.',
                'content' => <<<'TEXT'
Kupac iz Kragujevca je tražio auto u koji staju troje dece, kolica i vikend prtljag bez slagalice u gepeku. Volkswagen Sharan mu je na oglasu delovao kao sigurna, skoro poslovno racionalna kupovina: sedam sedišta, klizna vrata, dizel i poznata servisna mreža. Ali kod Sharana se ne kupuje samo značka. Kupuje se veliki auto koji je često već radio težak porodični posao.

Prva provera su klizna vrata, sedišta i kabina. Vrata moraju raditi glatko, brave ne smeju praviti slučajne greške, a sedišta treba proveriti kroz svako preklapanje, šinu i pojas. Ako prodavac kaže da "to niko ne koristi", probaj baš taj deo. Kod porodičnog vana male nepravilnosti brzo postanu svakodnevna nervoza, naročito kada deca ulaze i izlaze više puta dnevno.

Druga tema su dizel, DSG i trap. Sharan često prelazi velike kilometraže, pa servisna istorija mora imati više od lepog opisa. Traži dokaze o ulju u menjaču, velikom servisu, kočnicama, gumama, amortizerima, DPF-u i EGR-u. Probna vožnja treba da uključi grad, neravnine, parkiranje i ubrzanje pod opterećenjem. Ako menjač trza ili trap lupa, prednost prostora više nije dovoljna.

Polovni Sharan ima smisla kada stvarno koristiš prostor i kada primerak ne krije porodicu iza dubinskog pranja. Ako ti treba samo povišeno sedenje, manji SUV može biti lakši za održavanje. Ako ti trebaju prava sedišta i vrata koja olakšavaju život, Sharan je ozbiljan kandidat, ali cenu gradi tek posle pregleda kabine, mehanike i liste ulaganja.
TEXT,
                'highlights' => [
                    'Kod Sharana prvo proveri klizna vrata, sedišta, pojaseve, ISOFIX i tragove porodične upotrebe.',
                    'DSG, dizel, DPF, EGR i trap moraju imati servisni trag, ne samo uveravanje prodavca.',
                    'Dobar Sharan kupuje se zbog stvarne potrebe za prostorom, ne zbog ideje da je svaki veliki VW siguran.',
                ],
                'tags' => ['Volkswagen Sharan', 'porodični van', 'DSG', 'sedam sedišta', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Volkswagen Sharan: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Volkswagen Sharan modela: DSG, dizel, klizna vrata, sedam sedišta, klima, trap, enterijer i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Kia Picanto: mali gradski auto koji ne sme da sakrije kratke relacije',
                'slug' => 'polovni-kia-picanto-mali-gradski-auto-koji-ne-sme-da-sakrije-kratke-relacije',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Picanto je logičan prvi ili drugi auto za grad, ali kupac mora proveriti kvačilo, trap, klimu, kratke relacije i servisni trag.',
                'content' => <<<'TEXT'
Ana je tražila prvi auto za odlazak na posao, vrtić i uske parkinge oko zgrade. Kia Picanto joj je delovao kao mirna odluka: mali, pregledan, pristupačan za registraciju i bez komplikovane slike velikog polovnjaka. Upravo zato kod Picanta kupci često spuste gard. Mali auto ne znači da je prethodni život bio lak, posebno ako je svaki dan paljen hladan i vožen po par kilometara.

Prvo pogledaj tragove gradske upotrebe. Kvačilo, menjač, prednji trap, kočnice, gume, felne, branici i pragovi brzo otkrivaju auto koji je živeo među ivičnjacima i kratkim relacijama. Picanto može biti zahvalan, ali ako podrhtava pri kretanju, vuče u stranu ili ima neujednačeno trošenje guma, pregovaranje ne treba čekati kraj pregleda.

Druga provera su motor, klima i osnovna oprema. Mali benzinac treba da pali mirno, radi ravnomerno i ne pokazuje zapušten servis. Klima mora hladiti bez dugog čekanja, podizači, brave, svetla i multimedija treba da rade bez slučajnih izgovora. Kod jeftinijih gradskih auta prodavci često računaju da kupac neće detaljno proveriti sitnice jer je cena niža.

Polovni Picanto ima smisla kada želiš jednostavan gradski auto i kada cena ostavlja novac za gume, servis i eventualno kvačilo. Nema smisla ako ga plaćaš kao veći auto samo zato što lepo izgleda i ima nisku kilometražu. Najbolji primerak je onaj gde stanje enterijera, pedala, volana i servisa potvrđuje priču, a ne onaj koji je samo dobro opran za fotografije.
TEXT,
                'highlights' => [
                    'Picanto proveri kroz kvačilo, trap, kočnice i tragove uskog gradskog parkiranja.',
                    'Niska kilometraža nije dovoljna ako kratke relacije nisu pratile uredan servis.',
                    'Klima, brave, svetla i osnovna oprema moraju raditi jer sitnice brzo pojedu razliku u ceni.',
                ],
                'tags' => ['Kia Picanto', 'mali auto', 'gradski auto', 'prvi auto', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Kia Picanto: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Kia Picanto modela: gradska vožnja, kvačilo, trap, klima, gume, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Tragovi varenja na šasiji polovnog auta: kada pregled mora zaustaviti kupovinu',
                'slug' => 'tragovi-varenja-na-sasiji-polovnog-auta-kada-pregled-mora-zaustaviti-kupovinu',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Sveže varenje, čudan zaštitni premaz ili nejednaki nosači mogu otkriti ozbiljnu štetu koju sjajna karoserija pokušava da sakrije.',
                'content' => <<<'TEXT'
Nikola je skoro kaparisao karavan jer je spolja izgledao bolje od svih koje je gledao tog vikenda. Tek na dizalici se videlo da ispod svežeg zaštitnog premaza postoje tragovi varenja, drugačija tekstura metala i nosač koji nije izgledao kao fabrički. Prodavac je tvrdio da je "rađena zaštita od korozije", ali majstor je odmah usporio kupovinu. Kod polovnog auta šasija ne sme da traži poverenje, nego dokaz.

Prvo gledaj simetriju i kontinuitet. Pragovi, nosači, pod, koševi, mesta oko vešanja i prednji deo oko hladnjaka treba da imaju logičan oblik, fabričke tačke i ravnomeran zaštitni sloj. Svež bitumen, gruba masa, neravni varovi, drugačiji šrafovi ili tragovi brušenja ne znače automatski katastrofu, ali znače da treba objasniti šta je rađeno i zašto.

Drugi korak je povezivanje tragova sa ostatkom auta. Ako su farovi novi, zazori neravni, airbag lampica čudna, gume se troše nejednako ili auto vuče u stranu, varenje više nije estetska tema. Tada treba proveriti geometriju, dijagnostiku, dokumentaciju popravke i da li je vozilo bezbedno za svakodnevnu vožnju. Račun dobrog limara vredi više od priče da je "sve sređeno".

Kupovina može da se nastavi samo ako je popravka jasna, kvalitetna, dokumentovana i cena priznaje rizik. Ako prodavac izbegava dizalicu, ne zna šta je rađeno ili se tragovi ne slažu sa pričom, odustajanje je najjeftiniji potez. Polovan auto sme imati istoriju, ali ne sme imati sakrivenu konstrukcionu priču koju kupac otkrije tek posle registracije.
TEXT,
                'highlights' => [
                    'Svež zaštitni premaz, neravni varovi i drugačiji nosači traže dizalicu i ozbiljno objašnjenje.',
                    'Tragove varenja poveži sa zazorima, farovima, airbagovima, gumama i ponašanjem u vožnji.',
                    'Bez dokumentovane popravke i jasne cene rizika bolje je odustati pre kapare.',
                ],
                'tags' => ['šasija', 'varenje', 'karoserija', 'pregled vozila', 'provera polovnjaka'],
                'meta_title' => 'Tragovi varenja na šasiji polovnog auta',
                'meta_description' => 'Kako proveriti tragove varenja na šasiji polovnog auta: pragovi, nosači, zaštitni premaz, zazori, geometrija, dokumentacija i kada odustati.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Češke: kada flotna istorija i dobra cena traže dodatnu proveru',
                'slug' => 'uvoz-auta-iz-ceske-kada-flotna-istorija-i-dobra-cena-traze-dodatnu-proveru',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Češki uvoz može biti uredan i dobro opremljen, ali kupac mora proveriti flotnu upotrebu, kilometražu, servis, koroziju i dokumentaciju.',
                'content' => <<<'TEXT'
Kupac iz Niša je našao Škodu iz Češke sa lepom opremom, urednim fotografijama i cenom koja je bila bolja od sličnih nemačkih oglasa. Na prvi pogled sve je imalo logiku: zemlja gde su Škode česte, široka servisna mreža i mnogo službenih automobila sa redovnim održavanjem. Ali baš ta flotna priča traži dodatnu proveru, jer uredan servis ne znači uvek lagan život.

Prvo proveri poreklo i dokumentaciju. VIN, servisni zapisi, tehnički pregledi, računi, izvozna dokumentacija i broj prethodnih korisnika moraju se slagati. Ako je auto bio u firmi, renti ili dugoročnom najmu, to nije automatski loše, ali mora se videti koliko je vožen, na kojim relacijama i da li je održavanje rađeno po minimumu ili stvarnoj potrebi.

Druga provera je stanje koje prati kilometražu. Češki uvoz često može imati mnogo autoput kilometara, ali i zimske uslove, so, parking udarce i flotni tempo. Pogledaj podvozje, pragove, farove, vetrobran, felne, trap, kočnice, sedište vozača, volan i pedale. Ako enterijer deluje umornije od kilometraže ili je auto sveže pripremljen za prodaju, traži jači dokaz.

Uvoz iz Češke ima smisla kada su cena, dokumentacija i stanje u istoj priči. Dobra oprema i poznat model nisu dovoljni ako ne znaš prethodnu namenu. Ako prodavac transparentno pokazuje VIN, račune i putanju vozila, možeš pregovarati na osnovu realnih ulaganja. Ako se sve svodi na "uvoz je uredan", kupac treba da uspori i prvo plati pregled, a ne kaparu.
TEXT,
                'highlights' => [
                    'Češki uvoz prvo proveri kroz VIN, servisne zapise, izvozna dokumenta i prethodnu namenu.',
                    'Flotna istorija nije problem ako kilometraža, enterijer i održavanje pričaju istu priču.',
                    'Podvozje, trap, kočnice, so i parking tragovi moraju ući u realnu cenu kupovine.',
                ],
                'tags' => ['uvoz iz Češke', 'uvoz auta', 'flotna vozila', 'kilometraža', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Češke: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Češke: flotna istorija, VIN, servisna dokumentacija, kilometraža, korozija, podvozje, oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Opel Crossland ili Citroen C3 Aircross: mali crossover kada udobnost i praktičnost odlučuju',
                'slug' => 'opel-crossland-ili-citroen-c3-aircross-mali-crossover-kada-udobnost-i-prakticnost-odlucuju',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Crossland i C3 Aircross deluju kao isti recept za grad i porodicu, ali polovan primerak mora dokazati motor, trap, elektroniku i realnu namenu.',
                'content' => <<<'TEXT'
Marko i Jelena su tražili mali crossover za Novi Sad, vrtić i povremeni put do mora. Opel Crossland im je delovao ozbiljnije i poznatije, dok je Citroen C3 Aircross imao udobniju kabinu i veseliji enterijer. Oba auta su se uklapala u budžet, ali tek kada su uporedili konkretne primerke shvatili su da izbor nije znak na volanu, nego tragovi gradske upotrebe, motor i način održavanja.

Crossland je sigurniji izbor za kupca koji želi jednostavniji osećaj, preglednu kabinu i lakše prihvatanje na tržištu polovnjaka. Ima smisla kada je servisna istorija uredna, kada trap ne lupa preko neravnina i kada elektronika radi bez sitnih grešaka. Kod benzinca proveri hladan start, rad motora, potrošnju ulja i servisni ritam. Kod dizela proveri DPF, EGR i da li je auto stvarno vožen na relacijama koje dizel može da podnese.

C3 Aircross kupuje se zbog udobnosti, modularnosti i mekšeg karaktera. To je prednost za porodicu koja želi lak ulazak, dobru preglednost i manje nervoze u gradu, ali udobnost ne sme da sakrije stanje. Proveri sedišta, kliznu zadnju klupu ako je ima, klimu, multimediju, senzore, trap i tragove parking udaraca. Ako enterijer izgleda umornije nego što kilometraža sugeriše, cena mora da se vrati na zemlju.

Pametniji izbor je auto koji bolje odgovara rutini. Crossland ima prednost kada želiš konzervativniji polovnjak i lakšu kasniju prodaju. C3 Aircross ima smisla kada je udobnost važnija i kada konkretan primerak ima bolju dokumentaciju. Ako prodavac nema VIN, račune i hladnu probnu vožnju, ne pregovaraj oko boje i opreme, nego uspori kupovinu dok stanje ne postane jasno.
TEXT,
                'highlights' => [
                    'Crossland je mirniji izbor kada kupac želi jednostavniji crossover i lakšu kasniju prodaju.',
                    'C3 Aircross ima smisla kada udobnost, kabina i modularnost stvarno odgovaraju porodici.',
                    'Kod oba modela proveri motor, trap, elektroniku, parking tragove i da li cena prati stanje.',
                ],
                'tags' => ['Opel Crossland', 'Citroen C3 Aircross', 'mali crossover', 'poređenje', 'kupovina polovnjaka'],
                'meta_title' => 'Opel Crossland ili Citroen C3 Aircross',
                'meta_description' => 'Poređenje polovnih Opel Crossland i Citroen C3 Aircross modela: motor, trap, elektronika, udobnost, praktičnost, servis i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mazda CX-3: mali crossover koji traži proveru benzinca, dizela i korozije',
                'slug' => 'polovni-mazda-cx-3-mali-crossover-koji-trazi-proveru-benzinca-dizela-i-korozije',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mazda CX-3 može biti lep i pouzdan gradski crossover, ali kupac mora proveriti motor, podvozje, prostor i da li cena prati realnu upotrebljivost.',
                'content' => <<<'TEXT'
Ana je došla da pogleda Mazdu CX-3 jer joj je delovala kao bolji kompromis od klasičnog gradskog auta: viši položaj sedenja, lep enterijer i japanska reputacija. Na fotografijama je auto izgledao gotovo premium, ali probna vožnja je brzo pokazala da mali crossover ne rešava svaki porodični zadatak. Gepek je skroman, zadnja klupa traži kompromis, a dobar primerak mora opravdati cenu stanjem, ne samo izgledom.

Benzinska CX-3 je često mirniji izbor za grad i mešovitu vožnju, posebno kada ima uredne servise i normalnu potrošnju ulja. Proveri hladan start, rad kvačila, menjač, trap, kočnice i da li je auto vozio kratke gradske relacije sa čestim penjanjem na ivičnjake. Lep volan i dobra oprema ne znače mnogo ako su gume, diskovi i amortizeri pred zamenu.

Dizel traži strožu odluku. Može imati smisla za duže relacije, ali samo ako je istorija čista i režim vožnje odgovara dizelu. DPF, EGR, dizne, turbina i servisni intervali moraju biti jasni pre pregovora. Ako se prodavac oslanja na malu potrošnju, a nema račune i dijagnostiku, rizik je veći od uštede.

Kod svake CX-3 obavezno pogledaj donji deo vrata, rubove, podvozje, nosače, šrafove, gepek i tragove korozije ili loših popravki. Kupovina ima smisla kada stanje, servis i prostor odgovaraju tvojoj rutini. Ako ti treba pravi porodični auto, možda je veći kompakt bolji. Ako ti treba lep gradski crossover sa jasnom istorijom, dobra CX-3 može biti razumna, ali ne po svakoj ceni.
TEXT,
                'highlights' => [
                    'Mazda CX-3 ima smisla kao gradski crossover, ali prostor treba proveriti pre pregovora.',
                    'Benzinac je često mirniji izbor, dok dizel traži jasan servisni trag i duže relacije.',
                    'Korozija, trap, kočnice, gume i tragovi gradskog korišćenja moraju ući u realnu cenu.',
                ],
                'tags' => ['Mazda CX-3', 'mali crossover', 'benzinac', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mazda CX-3: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovne Mazde CX-3: benzinac, dizel, DPF, trap, korozija, prostor, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#ef4444', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Mitsubishi Space Star: gradski auto koji mora opravdati nisku cenu',
                'slug' => 'polovni-mitsubishi-space-star-gradski-auto-koji-mora-opravdati-nisku-cenu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Space Star privlači niskom potrošnjom i jednostavnošću, ali kupac mora proveriti kvačilo, trap, klimu, opremu i tragove kratkih relacija.',
                'content' => <<<'TEXT'
Petar je tražio prvi auto za ćerku i Mitsubishi Space Star mu je delovao kao racionalan izbor: mali, štedljiv, jednostavan i jeftiniji od popularnijih gradskih modela. Problem je što niska cena lako uspava kupca. Mali auto koji je godinama vozio kratke relacije, ivičnjake i parkinge može tražiti više ulaganja nego što oglas sugeriše.

Prvo proveri kako se auto ponaša u gradu. Kvačilo, menjač, volan, kočnice i trap najbrže otkrivaju težak život. Ako auto trza pri polasku, lupa preko neravnina, teško menja brzine ili volan ne stoji mirno, cena mora da prizna ulaganja. Space Star nije skup za održavanje, ali zbir guma, diskova, amortizera, akumulatora i servisa brzo pojede prednost povoljnog oglasa.

Druga provera je oprema. Klima mora hladiti, brave i podizači moraju raditi, lampice se moraju ugasiti posle starta, a enterijer treba da odgovara kilometraži. Kod jeftinijih gradskih auta često se preskaču sitni servisi jer vlasnik računa da auto "samo ide po gradu". Upravo te sitnice prave razliku između poštene kupovine i automobila koji odmah traži novac.

Space Star ima smisla kada kupac želi jednostavan gradski auto, malu potrošnju i niske osnovne troškove. Nema smisla ako ga plaćaš kao popularniji model samo zato što je mlađi ili lepo opran. Ako je istorija uredna, probna vožnja mirna i ulaganja jasno sabrana, može biti dobar prvi auto. Ako prodavac ne dozvoljava detaljan pregled, potraži drugi primerak.
TEXT,
                'highlights' => [
                    'Space Star kupuj kao jednostavan gradski auto, ne kao zamenu za veći porodični model.',
                    'Kvačilo, trap, kočnice, gume i akumulator najbrže otkrivaju kratke gradske relacije.',
                    'Niska cena ima smisla samo kada klima, oprema, servis i probna vožnja potvrde stanje.',
                ],
                'tags' => ['Mitsubishi Space Star', 'gradski auto', 'prvi auto', 'kvačilo', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Mitsubishi Space Star: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Mitsubishi Space Star modela: gradska vožnja, kvačilo, trap, klima, gume, oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Datumi na staklima polovnog auta: kada šifra otkriva skrivenu popravku',
                'slug' => 'datumi-na-staklima-polovnog-auta-kada-sifra-otkriva-skrivenu-popravku',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Različiti datumi na staklima ne znače uvek udes, ali mogu otvoriti pitanja o limariji, krađi, vandalizmu, popravci i iskrenosti prodavca.',
                'content' => <<<'TEXT'
Miloš je na pregledu primetio da prednje bočno staklo ima drugačiju oznaku od ostalih. Prodavac je odmah rekao da je "sigurno sitnica", ali majstor nije nastavio kao da se ništa nije desilo. Jedno zamenjeno staklo može biti posledica kamena, obijanja ili vandalizma. Može biti i trag bočnog udarca, loše popravke vrata ili neispričane istorije vozila.

Prvo nauči da datum na staklu nije samostalan dokaz. Oznake se razlikuju po proizvođaču i načinu obeležavanja, ali sva stakla na autu treba da imaju logiku sa godinom proizvodnje. Ako je jedno staklo mnogo novije, pitaj zašto je menjano i traži račun. Ako prodavac nema odgovor, ta sitnica postaje razlog za detaljniji pregled vrata, stubova, krova i unutrašnjih obloga.

Drugi korak je povezivanje sa ostatkom automobila. Proveri zazore oko vrata, boju, šrafove, gumice, dihtunge, vlagu u kabini, rad podizača i tragove skidanja tapacirunga. Ako su uz staklo menjani far, krilo, retrovizor ili airbag elementi, više ne gledaš izolovan detalj. Tada treba proveriti limariju, dijagnostiku i geometriju pre bilo kakve kapare.

Kupovina se može nastaviti ako postoji jasno objašnjenje i stanje se slaže sa pričom. Zamenjeno staklo zbog provale ili kamena nije razlog za automatsko odustajanje, ali jeste argument za nižu cenu ako postoje tragovi popravke. Ako prodavac minimizira pitanje, ne dozvoljava pregled ili priča ne prati fizičke tragove, bolje je odustati dok rizik još nije tvoj.
TEXT,
                'highlights' => [
                    'Različit datum na staklu nije presuda, ali traži objašnjenje, račun i pregled okolnih delova.',
                    'Staklo poveži sa zazorima, šrafovima, tapacirungom, vlagom, vratima i dijagnostikom airbaga.',
                    'Ako se priča prodavca ne slaže sa tragovima popravke, kapara treba da sačeka.',
                ],
                'tags' => ['stakla', 'datum stakla', 'limarija', 'provera vozila', 'polovan auto'],
                'meta_title' => 'Datumi na staklima polovnog auta',
                'meta_description' => 'Kako proveriti datume na staklima polovnog auta: oznake, zamena stakla, vrata, stubovi, limarija, vlaga, dokumentacija i kada odustati.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Švedske: kada uredan servis traži proveru korozije i opreme',
                'slug' => 'uvoz-auta-iz-svedske-kada-uredan-servis-trazi-proveru-korozije-i-opreme',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Švedski uvoz može doneti bogatu opremu i uredne zapise, ali kupac mora proveriti so, podvozje, grejanje, kilometražu i dokumentaciju.',
                'content' => <<<'TEXT'
Kupac iz Subotice je našao karavan iz Švedske sa odličnom opremom, grejačima sedišta i urednim servisnim zapisima. Cena je bila primamljiva jer slični primerci iz Nemačke nisu imali toliko opreme. Ipak, švedski uvoz traži drugačiji pogled: nije dovoljno da auto ima servisnu istoriju, mora se videti kako je podneo zimu, so, duge relacije i hladne startove.

Prvo proveri poreklo. VIN, servisni zapisi, tehnički pregledi, izvozna dokumentacija i kilometraža moraju se slagati. Švedski automobili često imaju dobru evidenciju, ali kupac u Srbiji ne sme da se osloni samo na tvrdnju prodavca. Ako dokumentacija nije prevedena ili jasna, traži vreme da je proveriš pre kapare.

Druga provera je podvozje. So, vlaga i zimski uslovi mogu ostaviti trag na pragovima, kočionim cevima, nosačima, šrafovima, rubovima i auspuhu. Dobar auto ne mora biti bez tačkice korozije, ali korozija ne sme biti konstrukciona, sveže maskirana ili ignorisana. Pregled na dizalici je obavezan, posebno kod skupljih karavana, SUV-ova i dizela.

Švedski uvoz ima smisla kada bogata oprema, servis i stanje rade zajedno. Grejači, webasto, dobra svetla i sigurnosni paketi jesu prednost, ali nisu zamena za zdrav pod, uredan motor i jasnu dokumentaciju. Ako je cena niža zato što postoje ulaganja, pregovaraj hladno. Ako se problem svodi na "svi švedski su takvi", bolje je platiti pregled nego tuđu zimu.
TEXT,
                'highlights' => [
                    'Švedski uvoz proveri kroz VIN, servisne zapise, tehničke preglede i jasnu izvoznu dokumentaciju.',
                    'Podvozje, pragovi, kočione cevi, nosači i auspuh moraju na dizalicu zbog soli i zime.',
                    'Bogata oprema vredi samo kada korozija, kilometraža i servisna istorija ne kvare računicu.',
                ],
                'tags' => ['uvoz iz Švedske', 'uvoz auta', 'korozija', 'servisna istorija', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Švedske: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Švedske: VIN, servisna dokumentacija, kilometraža, korozija, podvozje, zimska oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Chevrolet Aveo ili Hyundai Getz: mali auto kada budžet ne trpi iluzije',
                'slug' => 'chevrolet-aveo-ili-hyundai-getz-mali-auto-kada-budzet-ne-trpi-iluzije',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Aveo i Getz privlače niskom cenom, ali kupac mora proveriti koroziju, trap, delove, klimu i da li je jeftin auto stvarno jeftin posle prenosa.',
                'content' => <<<'TEXT'
Miloš je tražio prvi auto za posao i vožnju po gradu, sa budžetom koji nije ostavljao prostor za velike popravke. Chevrolet Aveo i Hyundai Getz su se pojavili kao logični kandidati: mali, jednostavni i jeftiniji od popularnijih gradskih modela. Ali upravo kod najjeftinijih oglasa kupac mora biti najstroži, jer loš primerak brzo potroši razliku u ceni.

Aveo ima smisla kada je cena realna, motor radi mirno i postoji dokaz da osnovno održavanje nije preskakano. Treba proveriti koroziju na pragovima i podu, trap, kočnice, kvačilo, klimu i dostupnost delova za konkretan motor. Ako prodavac kaže da je auto "jeftin pa ne može sve da se gleda", to je razlog da se gleda još pažljivije.

Getz često deluje kao mirnija kupovina zbog reputacije jednostavnog malog auta i bolje prihvaćenosti na tržištu. Ipak, godine se vide kroz gumice, amortizere, hladan start, curenja, svetla, brave i stanje enterijera. Primerak koji je ceo život proveo na kratkim relacijama može imati malu kilometražu, ali umorno kvačilo, akumulator, kočnice i trap.

Pametniji izbor je automobil koji posle pregleda ostavlja manje otvorenih troškova, ne onaj koji ima lepši oglas. Aveo može pobediti ako je znatno jeftiniji i uredan, dok Getz ima prednost kada stanje i kasnija prodaja opravdaju višu cenu. Ako nema klime, registracije, dobrih guma i jasnih papira, pregovaraj odmah ili nastavi potragu.
TEXT,
                'highlights' => [
                    'Aveo ima smisla samo kada niska cena dolazi uz zdrav motor, pod i dostupne delove.',
                    'Getz je mirniji kandidat ako stanje, klima, trap i servisni trag opravdaju višu cenu.',
                    'Kod jeftinog malog auta gume, kočnice, kvačilo i registracija mogu promeniti celu računicu.',
                ],
                'tags' => ['Chevrolet Aveo', 'Hyundai Getz', 'mali auto', 'prvi auto', 'poređenje'],
                'meta_title' => 'Chevrolet Aveo ili Hyundai Getz: mali polovnjak',
                'meta_description' => 'Poređenje polovnih Chevrolet Aveo i Hyundai Getz modela: motor, korozija, trap, klima, delovi, prvi auto i realna cena kupovine.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Daihatsu Terios: mali terenac koji mora dokazati pogon i rđu',
                'slug' => 'polovni-daihatsu-terios-mali-terenac-koji-mora-dokazati-pogon-i-rdju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Terios deluje kao jednostavan mali 4x4 za loše puteve, ali kupac mora proveriti koroziju, pogon, trap, delove i da li teren nije već pojeo auto.',
                'content' => <<<'TEXT'
Zoran je tražio mali auto za selo, vikendicu i zimske puteve gde običan gradski automobil često zapne. Daihatsu Terios mu je delovao kao zanimljivo rešenje: kompaktan, povišen, sa pogonom koji obećava više sigurnosti van asfalta. Ali kod ovakvog polovnjaka najveći rizik nije marka, nego prethodni život po blatu, snegu, rupama i kratkim relacijama.

Prva provera je donji deo auta. Pragovi, pod, rubovi, nosači, kočione cevi, auspuh i mesta oko vešanja moraju na dizalicu. Terios može spolja izgledati simpatično i očuvano, a ispod kriti rđu ili tragove grubog terena. Svež zaštitni premaz nije dokaz, već poziv da se proveri šta je premazano.

Druga tema su pogon, menjač i trap. Proveri da li 4x4 radi bez lupanja, zavijanja i zatezanja, da li menjač ulazi glatko u sve brzine i da li trap ne otkriva udarce preko rupa. Gume moraju biti jednake i pravilno potrošene, jer različite ili loše gume mogu pokazati da je pogon trpeo pogrešnu upotrebu.

Terios ima smisla kada kupac stvarno koristi povišen auto i kada primerak nije samo jeftina karta za 4x4. Ako ti treba gradski auto, običan benzinac može biti mirniji i jeftiniji. Ako ti treba mali terenac, plati pregled, proveri dostupnost delova i pregovaraj na osnovu rđe, guma, trapa i servisa, ne na osnovu retkosti modela.
TEXT,
                'highlights' => [
                    'Terios treba proveriti na dizalici jer rđa i teren često odlučuju vrednost auta.',
                    'Pogon, menjač, trap i jednake gume moraju raditi bez lupanja, zavijanja i zatezanja.',
                    'Kupovina ima smisla samo ako stvarno koristiš mali 4x4 i cena priznaje dostupnost delova.',
                ],
                'tags' => ['Daihatsu Terios', '4x4', 'mali terenac', 'korozija', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Daihatsu Terios: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Daihatsu Terios modela: 4x4 pogon, korozija, trap, menjač, gume, delovi, teren i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Fiat Linea: limuzina koja ne sme da se kupi samo zbog gepeka',
                'slug' => 'polovni-fiat-linea-limuzina-koja-ne-sme-da-se-kupi-samo-zbog-gepeka',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Linea nudi veliki gepek i pristupačnu cenu, ali kupac mora proveriti motor, trap, klimu, elektroniku, limariju i da li prostor opravdava godine.',
                'content' => <<<'TEXT'
Ivana je tražila porodični auto za skroman budžet i Fiat Linea joj je zapao za oko zbog velikog gepeka, jednostavne kabine i cene koja deluje razumnije od popularnih karavana. Na papiru to ima logiku. Problem je što kod Linee prostor lako postane glavni argument, pa kupac kasno primeti godine, umor i odložena ulaganja.

Prvo proveri motor i osnovno održavanje. Benzinske verzije treba da pale mirno, rade ravnomerno i ne kriju curenja, zapušten servis ili probleme sa hlađenjem. Dizel može imati smisla za duže relacije, ali samo uz jasne račune, dobar hladan start, mirnu turbinu i proveru EGR-a, DPF-a ako postoji i stanja kvačila.

Druga provera je svakodnevna upotreba. Linea često radi kao porodični ili službeni auto, pa trap, kočnice, vrata, gepek, brava prtljažnika, klima i podizači moraju biti provereni bez žurbe. Veliki gepek ne vredi mnogo ako klima ne hladi, zadnji trap lupa ili enterijer pokazuje da je auto nosio više tereta nego što prodavac priznaje.

Polovna Linea je dobra kupovina kada je realno plaćena, jednostavna i servisno jasna. Nije dobra kada je kupuješ samo zato što za malo novca izgleda kao veći auto. Uporedi je sa Tipom, Loganom, Rapidom i starijim kompaktima, pa odluči da li konkretan primerak nudi prostor bez skrivene investicije.
TEXT,
                'highlights' => [
                    'Linea privlači gepekom, ali motor, klima, trap i brava prtljažnika moraju biti provereni.',
                    'Dizel ima smisla samo uz duže relacije, račune i jasnu proveru turbine, EGR-a i kvačila.',
                    'Dobar primerak treba platiti kao pristupačnu limuzinu, ne kao porodični auto bez ulaganja.',
                ],
                'tags' => ['Fiat Linea', 'limuzina', 'porodični auto', 'gepek', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Fiat Linea: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Fiat Linea modela: motor, dizel, trap, klima, gepek, elektronika, limarija, servis i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Menjač iskače iz brzine na polovnom autu: kada probna vožnja mora prekinuti kupovinu',
                'slug' => 'menjac-iskace-iz-brzine-na-polovnom-autu-kada-probna-voznja-mora-prekinuti-kupovinu',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Iskakanje iz brzine nije sitan zvuk ni navika starog auta, već signal za proveru menjača, nosača, sajli, kvačila i stvarne cene popravke.',
                'content' => <<<'TEXT'
Petar je na probnoj vožnji primetio da ručica menjača povremeno izađe iz treće brzine kada pusti gas. Prodavac je rekao da se "samo treba naviknuti", ali majstor nije prihvatio objašnjenje. Menjač koji iskače iz brzine nije kozmetička mana. To je simptom koji može značiti od podešavanja sajli do ozbiljnog unutrašnjeg kvara.

Prva provera je ponavljanje simptoma. Auto treba voziti hladan i zagrejan, kroz ubrzavanje, usporavanje motorom, neravnine i različite stepene prenosa. Obrati pažnju da li brzina ispada pod opterećenjem, pri kočenju motorom ili kada se ručica samo pomeri. Ako prodavac ne dozvoljava dužu probnu vožnju, rizik ostaje nepoznat.

Druga tema su uzroci oko menjača. Nosači motora i menjača, sajle ili poluge, kvačilo, hidraulika i ulje u menjaču mogu praviti osećaj nepreciznosti. Ipak, pohabani zupčanici, sinhroni ili viljuške menjaju računicu potpuno drugačije. Zato dijagnostika ovde nije dovoljna. Potreban je majstor koji zna da proceni mehanički deo i cenu realne popravke.

Kupovina se nastavlja samo ako je uzrok jasan, popravka uračunata i cena dovoljno spuštena. Ako prodavac umanjuje problem, kaže da se tako vozi godinama ili traži kaparu pre pregleda, odustajanje je najjeftinija odluka. Polovan auto može imati mane, ali menjač mora ostati pod kontrolom kupca, ne pod obećanjem prodavca.
TEXT,
                'highlights' => [
                    'Iskakanje iz brzine treba proveriti hladno, toplo, pod gasom i pri kočenju motorom.',
                    'Uzrok može biti nosač, sajla ili kvačilo, ali i skup unutrašnji kvar menjača.',
                    'Bez jasne dijagnoze i cene popravke kapara treba da sačeka ili kupovina treba da stane.',
                ],
                'tags' => ['menjač', 'probna vožnja', 'kvačilo', 'provera vozila', 'polovan auto'],
                'meta_title' => 'Menjač iskače iz brzine: šta proveriti',
                'meta_description' => 'Kako proveriti polovan auto kada menjač iskače iz brzine: probna vožnja, sajle, nosači, kvačilo, sinhroni, ulje i kada odustati.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Slovenije: kada blizina tržišta ne znači lakšu proveru',
                'slug' => 'uvoz-auta-iz-slovenije-kada-blizina-trzista-ne-znaci-laksu-proveru',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Slovenački uvoz deluje blisko i uredno, ali kupac mora proveriti kilometražu, so, servis, flotnu upotrebu, papire i da li cena stvarno ima smisla.',
                'content' => <<<'TEXT'
Kupac iz Beograda je našao karavan iz Slovenije koji je delovao kao lakša kupovina od udaljenog uvoza: blizu tržište, razumljivija dokumentacija i oprema koja odgovara našem regionu. Baš zato je skoro preskočio detalje. Blizina ne znači da je auto automatski čist, niti da kilometraža, servis i podvozje mogu ostati bez provere.

Prvo proveri poreklo i papire. VIN, servisni zapisi, tehnički pregledi, izvozna dokumentacija, broj vlasnika i namena moraju se slagati. Slovenački automobili mogu imati urednu istoriju, ali mogu biti i flotni, službeni ili korišćeni za duge relacije kroz region. Ako prodavac ima samo kratku priču i brz rok za kaparu, uspori kupovinu.

Druga tema su klima, zima i relacije. Planinski krajevi, so, kiša i autoput kilometri mogu ostaviti trag na podvozju, kočnicama, vetrobranu, farovima, gumama i trapu. Pregled na dizalici i poređenje stanja enterijera sa kilometražom su obavezni, posebno kod karavana, dizela i automobila koji su radili posao.

Uvoz iz Slovenije ima smisla kada dokumentacija, stanje i cena pričaju istu priču. Ako je auto skuplji od domaćeg zbog navodno urednog porekla, to poreklo mora biti dokazano. Ako je jeftiniji, razlog mora biti jasan pre pregovora. Dobra kupovina nije ona koja je najbliže stigla, nego ona koju možeš najlakše proveriti.
TEXT,
                'highlights' => [
                    'Slovenački uvoz proveri kroz VIN, tehničke preglede, servisne zapise i jasnu izvoznu dokumentaciju.',
                    'So, kiša, autoput relacije i flotna upotreba mogu ostaviti trag na podvozju, trapu i enterijeru.',
                    'Blizina tržišta vredi samo ako cena, papiri i stanje daju proverljivu računicu.',
                ],
                'tags' => ['uvoz iz Slovenije', 'uvoz auta', 'servisna istorija', 'korozija', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Slovenije: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Slovenije: VIN, dokumentacija, kilometraža, servis, so, podvozje, flotna upotreba i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Opel Adam ili Fiat 500: mali gradski auto kada stil ne sme da pojede budžet',
                'slug' => 'opel-adam-ili-fiat-500-mali-gradski-auto-kada-stil-ne-sme-da-pojede-budzet',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Adam i Fiat 500 kupuju se srcem, ali polovan primerak mora dokazati motor, klimu, trap, opremu i cenu koja ne kažnjava samo zbog izgleda.',
                'content' => <<<'TEXT'
Milica je tražila mali auto za centar grada, uske parkinge i kratke relacije do posla. Opel Adam joj je delovao čvršće i ozbiljnije, dok je Fiat 500 imao više šarma i bolju prepoznatljivost. Oba su je privukla izgledom, ali je tek na pregledu shvatila da kod ovakvih auta stil često podigne cenu brže nego što stanje može da je opravda.

Fiat 500 ima prednost kada kupac želi auto koji se lako prodaje, lako parkira i ima jak gradski identitet. To ne znači da treba platiti svaki primerak. Proveri hladan start, rad kvačila, menjač, klimu, trap, elektroniku, panoramu ako postoji i tragove udaraca na branicima. Mali auto koji je godinama živeo po centru može imati malo kilometara, ali umorne gume, kočnice, vrata i enterijer.

Opel Adam često deluje mirnije i ozbiljnije u kabini, sa opremom koja ume da bude bogata za klasu. Njegova mana je što ga tržište slabije prepoznaje od Fiata 500, pa cena mora biti realnija. Proveri motor, lanac ili servisni ritam za konkretan agregat, klimu, multimediju, felne, trap i da li su skuplje opcije stvarno ispravne. Lep enterijer ne sme da sakrije nejasnu istoriju.

Pametniji izbor je onaj primerak koji posle pregleda ostavlja manje otvorenih troškova. Fiat 500 ima smisla kada je uredan i cena ne traži doplatu samo za imidž. Opel Adam ima smisla kada dobijaš bolju opremu, bolje stanje i nižu cenu. Ako prodavac priča o boji, felnama i retkosti više nego o računima, pregovor treba vratiti na gume, servis, klimu i probnu vožnju.
TEXT,
                'highlights' => [
                    'Fiat 500 lakše drži pažnju tržišta, ali samo uredan primerak opravdava višu cenu.',
                    'Opel Adam ima smisla kada oprema i stanje daju više vrednosti od samog izgleda.',
                    'Kod oba modela proveri kvačilo, trap, klimu, elektroniku, felne i tragove gradske vožnje.',
                ],
                'tags' => ['Opel Adam', 'Fiat 500', 'gradski auto', 'mali auto', 'poređenje'],
                'meta_title' => 'Opel Adam ili Fiat 500: koji polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Opel Adam i Fiat 500 modela: gradska vožnja, motor, kvačilo, trap, klima, oprema, stil i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Nissan Pulsar: kompakt koji mora opravdati prostor, CVT i mirnu reputaciju',
                'slug' => 'polovni-nissan-pulsar-kompakt-koji-mora-opravdati-prostor-cvt-i-mirnu-reputaciju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Pulsar nudi mnogo prostora za novac, ali kupac mora proveriti motor, CVT, trap, elektroniku, limariju i da li niska potražnja zaista spušta rizik.',
                'content' => <<<'TEXT'
Nenad je tražio kompakt za porodicu i posao, ali nije želeo da plati cenu Golfa, Octavije ili i30. Nissan Pulsar mu je zato izgledao kao skrivena prilika: dosta mesta nazad, velik gepek za klasu i miran karakter. Problem je što manje popularan model nije automatski bolja kupovina. On samo traži drugačije pitanje: zašto je jeftiniji i šta kupac dobija posle pregleda.

Prvo proveri motor i servisnu istoriju. Benzinske verzije treba slušati hladne, proveriti curenja, rad turbine ako postoji, potrošnju ulja i urednost servisa. Dizel ima smisla samo ako su relacije i istorija jasni, jer DPF, EGR i dizne ne opraštaju kratke gradske vožnje. Pulsar ne treba kupiti samo zato što deluje racionalno na papiru.

Ako je auto sa CVT menjačem, probna vožnja mora biti duža i pažljivija. Menjač treba da povlači glatko, bez trzaja, zavijanja, zadrške ili neprirodnog podizanja obrtaja. Traži račun za servis ulja ili bar cenu preventivnog servisa uračunaj u pregovor. Kod manuelnog proveri kvačilo, hod ručice i ponašanje pri ubrzanju u višem stepenu prenosa.

Pulsar ima smisla kada kupac želi prostran, nenametljiv kompakt i kada konkretan primerak ima bolju istoriju od popularnije alternative. Proveri trap, kočnice, klimu, kameru, senzore, vrata, gepek i da li enterijer prati kilometražu. Ako je cena niska zato što tržište slabije traži model, to može biti prednost. Ako je niska zato što servis i menjač imaju nepoznanice, bolje je nastaviti potragu.
TEXT,
                'highlights' => [
                    'Pulsar vredi gledati zbog prostora, ali samo uz jasnu servisnu istoriju i realnu cenu.',
                    'CVT menjač mora raditi glatko, uz dužu probnu vožnju i proveru servisa ulja.',
                    'Manja popularnost je prednost samo ako stanje nije razlog za nisku cenu.',
                ],
                'tags' => ['Nissan Pulsar', 'kompakt', 'CVT', 'porodični auto', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Nissan Pulsar: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Nissan Pulsar modela: motor, CVT, trap, klima, prostor, servisna istorija, elektronika i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Hyundai ix20: mali monovolumen koji traži proveru prostora, trapa i klime',
                'slug' => 'polovni-hyundai-ix20-mali-monovolumen-koji-trazi-proveru-prostora-trapa-i-klime',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Hyundai ix20 može biti praktičan gradsko-porodični auto, ali dobar primerak mora dokazati motor, kvačilo, trap, klimu, enterijer i realnu cenu.',
                'content' => <<<'TEXT'
Goran i Ana su tražili auto za školu, pijacu, starije roditelje i povremeni odlazak van grada. SUV im je bio preskup, a klasičan mali auto prenizak i tesan. Hyundai ix20 je zato ušao u uži izbor kao razuman kompromis: viši ulazak, pregledna kabina i dovoljno prostora bez velike karoserije. Ipak, praktičnost ne sme da zameni pregled.

Prva provera je kako je auto korišćen. ix20 često služi kao porodični i gradski alat, pa trap, kočnice, kvačilo, gume i amortizeri mogu biti umorniji nego što kilometraža pokazuje. Probna vožnja treba da uključi neravnine, okretanje volana u mestu, kočenje i parkiranje. Ako lupa preko rupa ili kvačilo hvata visoko, cena mora priznati ulaganja.

Druga tema su motor, klima i elektronika. Benzinac je često mirniji izbor za kratke relacije, ali mora raditi tiho, bez curenja i bez zanemarenog servisa. Dizel traži dokaz da nije ceo život proveo u gradu. Klima mora hladiti, ventilacija ne sme mirisati na vlagu, a podizači, brave, senzori i svetla treba da rade bez slučajnih izgovora.

ix20 ima smisla kada kupac stvarno koristi viši ulazak, praktičnu kabinu i skromne dimenzije. Nema smisla ako se plaća skoro kao veći crossover ili ako je enterijer umoran od porodičnog života. Dobar primerak treba da ima jasne račune, očuvan trap i cenu koja ostavlja prostor za početni servis. Ako prodavac prodaje samo "mali porodični auto", traži dokaze da porodica nije već potrošila najbolji deo.
TEXT,
                'highlights' => [
                    'ix20 je dobar kada kupcu treba viši ulazak i praktičnost bez SUV troška.',
                    'Trap, kvačilo, kočnice, gume i klima najbrže otkrivaju težak gradski život.',
                    'Benzinac je mirniji za kratke relacije, dok dizel traži jasan servisni trag i duže vožnje.',
                ],
                'tags' => ['Hyundai ix20', 'mali monovolumen', 'porodični auto', 'klima', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Hyundai ix20: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Hyundai ix20 modela: motor, trap, kvačilo, klima, enterijer, porodična upotreba i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Krckanje pri punom motanju: kada homokinetički zglob otkriva skuplji trap polovnjaka',
                'slug' => 'krckanje-pri-punom-motanju-kada-homokineticki-zglob-otkriva-skuplji-trap-polovnjaka',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Krckanje pri skretanju nije samo zvuk starog auta, već signal za proveru homokinetičkih zglobova, manžetni, poluosovina, trapa i realnog ulaganja.',
                'content' => <<<'TEXT'
Stefan je skoro dogovorio kupovinu kompakta kada je na parkingu, pri punom motanju ulevo, čuo kratko krckanje. Prodavac je rekao da je to normalno za godine, ali majstor ga je zamolio da ponovi manevar u oba smera. Zvuk koji se javlja pri punom motanju često nije slučajnost. Može otvoriti priču o homokinetičkom zglobu, manžetni, poluosovini ili širem stanju trapa.

Prva provera je kada se zvuk pojavljuje. Napravi spor krug punim levim i desnim volanom, zatim ponovi pod blagim gasom. Obrati pažnju da li krcka samo u jednom smeru, da li se čuje pri polasku, preko neravnina ili pri promeni opterećenja. Zglob koji se čuje pod opterećenjem obično traži više od lepog objašnjenja prodavca.

Druga tema su manžetne i tragovi masti. Na dizalici treba pogledati gumene manžetne, stege, curenje masti, luft u poluosovini, kugle, spone, ležajeve i stanje guma. Pukla manžetna može biti jeftin kvar ako je primećena rano, ali ako je zglob dugo radio bez masti, račun raste. Ne gledaj samo deo koji se čuje, nego zašto se čuje.

Kupovina se može nastaviti ako je uzrok jasan, cena popravke poznata i ostatak trapa uredan. Ako se uz krckanje vide loše gume, krive felne, lupanje preko rupa i nejednako trošenje, problem više nije izolovan. Tada pregovor mora obuhvatiti ceo prednji trap, ne samo jedan zglob. Ako prodavac odbija dizalicu, zvuk je već dovoljan razlog da se kupovina zaustavi.
TEXT,
                'highlights' => [
                    'Krckanje proveri punim levim i desnim motanjem, hladno i pod blagim opterećenjem.',
                    'Manžetne, mast, poluosovine, kugle, spone, ležajevi i gume moraju na pregled.',
                    'Ako se zvuk spaja sa lošim trapom ili krivim felnama, pregovor mora uključiti veći trošak.',
                ],
                'tags' => ['homokinetički zglob', 'trap', 'poluosovina', 'probna vožnja', 'provera vozila'],
                'meta_title' => 'Krckanje pri punom motanju: šta proveriti',
                'meta_description' => 'Kako proveriti krckanje pri punom motanju kod polovnog auta: homokinetički zglob, manžetna, poluosovina, trap, gume i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Mađarske: kada blizina oglasa traži proveru kilometraže, porekla i rđe',
                'slug' => 'uvoz-auta-iz-madjarske-kada-blizina-oglasa-trazi-proveru-kilometraze-porekla-i-rdja',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Mađarski uvoz može delovati brzo i blizu, ali kupac mora proveriti VIN, servis, vlasništvo, koroziju, flotnu upotrebu i punu cenu registracije.',
                'content' => <<<'TEXT'
Kupac iz Subotice je našao auto iz Mađarske koji je delovao kao najlakši uvoz: blizu je, može da se ode istog dana, a cena je bila niža od sličnih domaćih oglasa. Upravo ta blizina ume da napravi problem. Kada put nije skup i prodavac žuri, kupac lako preskoči proveru porekla, kilometraže i svih troškova do registracije u Srbiji.

Prvo proveri dokumentaciju. VIN, saobraćajna, odjava, kupoprodajni trag, servisni zapisi, tehnički pregledi i broj vlasnika moraju imati logiku. Ako je auto bio službeni, flotni ili preprodavan kroz više ruku, to mora ući u cenu. Jezik dokumentacije nije izgovor da se papiri gledaju površno. Pre kapare prevedi i proveri ono što ne razumeš.

Druga tema su kilometraža i stanje. Mađarski automobili mogu imati uredan servis, ali mogu nositi tragove autoputa, grada, soli, slabijih puteva i parking oštećenja. Pregled na dizalici treba da uključi pragove, pod, kočione cevi, rubove, auspuh, trap i tragove svežeg zaštitnog premaza. Enterijer, volan, pedale i sedišta treba da potvrde broj na satu.

Uvoz iz Mađarske ima smisla kada blizina pomaže proveri, a ne kada služi da se odluka ubrza. Saberi transport, prevod, homologaciju, carinu, porez, registraciju, početni servis i eventualne gume pre poređenja sa domaćim oglasima. Ako prodavac nudi samo nisku cenu i brz dogovor, rizik ostaje tvoj. Dobra kupovina je ona čije poreklo možeš proveriti lakše nego što možeš stići do nje.
TEXT,
                'highlights' => [
                    'Mađarski uvoz proveri kroz VIN, odjavu, servisne zapise, vlasništvo i tehničke preglede.',
                    'Blizina tržišta ne menja potrebu za dizalicom, proverom rđe, trapa i stvarne kilometraže.',
                    'Pun trošak uvoza mora uključiti prevod, dažbine, registraciju, servis i početna ulaganja.',
                ],
                'tags' => ['uvoz iz Mađarske', 'uvoz auta', 'kilometraža', 'korozija', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Mađarske: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Mađarske: VIN, dokumentacija, kilometraža, korozija, flotna upotreba, troškovi uvoza i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Citroen C2 ili Ford Ka: mali auto za grad kada cena ne sme da prevari',
                'slug' => 'citroen-c2-ili-ford-ka-mali-auto-za-grad-kada-cena-ne-sme-da-prevari',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C2 i Ka deluju kao jeftin ulaz u gradski auto, ali kupac mora proveriti rđu, kvačilo, klimu, trap i da li niska cena ostavlja prostor za servis.',
                'content' => <<<'TEXT'
Jelena je tražila mali auto za odlazak na posao, parking u centru i povremenu vožnju do roditelja. Citroen C2 i Ford Ka su ušli u izbor jer su jeftini, kratki i jednostavni za grad. Na papiru je delovalo da je dovoljno izabrati očuvaniji primerak, ali kod ovako starih malih auta najniža cena često krije prvi račun posle prenosa.

Citroen C2 ima prednost kada kupac želi praktičniju kabinu, nešto bolji osećaj svakodnevne upotrebe i auto koji se lakše uklapa u gradske obaveze. Treba proveriti motor, menjač, kvačilo, klimu, elektriku, podizače i zadnja vrata. Ako auto trza, teško pali ili se elektronika ponaša slučajno, mala cena nije dovoljan argument.

Ford Ka je jednostavan i simpatičan, ali godine i korozija moraju biti glavna tema pregleda. Pragovi, rubovi, pod, nosači, auspuh i donji deo vrata treba da se gledaju pre opreme i boje. Dobar Ka ima smisla kada je stvarno zdrav i jeftin za održavanje. Loš primerak može biti toliko jeftin da kupac prekasno shvati da popravka lima vredi više od auta.

Pametniji izbor je onaj koji posle pregleda ima manje otvorenih troškova, ne onaj koji je najlepše opran. C2 može biti bolji ako tražiš upotrebljiviji mali auto, a Ka ako je jednostavan, zdrav i realno jeftin. Kod oba modela u cenu odmah uračunaj gume, kočnice, veliki servis ako nema dokaza, registraciju i klima servis. Ako prodavac ne dozvoljava dizalicu, nastavi potragu.
TEXT,
                'highlights' => [
                    'C2 je praktičniji gradski izbor ako motor, kvačilo, klima i elektronika rade bez izgovora.',
                    'Ford Ka mora prvo dokazati zdrav pod, pragove i rubove, pa tek onda nisku cenu.',
                    'Kod oba modela gume, kočnice, registracija i početni servis lako promene računicu.',
                ],
                'tags' => ['Citroen C2', 'Ford Ka', 'mali auto', 'gradski auto', 'poređenje'],
                'meta_title' => 'Citroen C2 ili Ford Ka: mali polovnjak za grad',
                'meta_description' => 'Poređenje polovnih Citroen C2 i Ford Ka modela: rđa, motor, kvačilo, klima, trap, registracija, početni servis i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Toyota Urban Cruiser: mali crossover koji mora opravdati retkost i cenu',
                'slug' => 'polovni-toyota-urban-cruiser-mali-crossover-koji-mora-opravdati-retkost-i-cenu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Urban Cruiser privlači Toyotinom reputacijom i višim sedenjem, ali kupac mora proveriti koroziju, 4x4, servis, delove i da li retkost diže cenu bez osnova.',
                'content' => <<<'TEXT'
Marko je tražio mali povišen auto za suprugu, ali nije želeo popularan crossover koji svi jure. Toyota Urban Cruiser mu je delovala kao mirna kupovina: japanska reputacija, preglednost i kompaktne dimenzije. Problem je što retkost na tržištu ume da napravi iluziju vrednosti. Nije svaki redak auto bolji, niti je svaka Toyota automatski bez rizika.

Prvo proveri poreklo i servis. Urban Cruiser treba da ima jasne račune, logičnu kilometražu i stanje koje prati godine. Benzinski motor mora raditi mirno, bez curenja i preskočenih servisa. Dizel, ako ga gledaš, traži više opreza oko relacija, EGR-a, turbine i potrošnje. Kod ređih modela nije dovoljno da auto "dobro radi"; važno je koliko brzo i po kojoj ceni možeš rešiti kvar.

Druga tema su podvozje, trap i pogon. Ako primerak ima 4x4, proveri da li sistem radi bez lupanja, zatezanja i čudnih zvukova. Gume treba da budu jednake, trap miran, a pod i pragovi bez ozbiljne korozije. Mali crossover koji je vožen po lošim putevima može izgledati bezazleno, a ispod kriti račun koji prodavac ne pominje.

Urban Cruiser ima smisla kada kupac želi mali, pregledan i pouzdan auto, ali samo ako cena ne kažnjava kupca zbog Toyotinog znaka i male ponude. Uporedi ga sa SX4, Yarisom, Jazzom i manjim crossoverima pre odluke. Ako je primerak uredan i pregled potvrdi stanje, retkost može biti simpatična prednost. Ako prodavac traži premiju bez računa i jasne istorije, reputacija nije dovoljan dokaz.
TEXT,
                'highlights' => [
                    'Urban Cruiser kupuj zbog stanja i namene, ne samo zbog Toyotine reputacije.',
                    'Kod 4x4 primerka proveri pogon, jednake gume, trap, podvozje i tragove loših puteva.',
                    'Retkost modela ima smisla samo ako dostupnost delova i servisna istorija ne dižu rizik.',
                ],
                'tags' => ['Toyota Urban Cruiser', 'mali crossover', '4x4', 'Toyota', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Toyota Urban Cruiser: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Toyota Urban Cruiser modela: motor, 4x4 pogon, korozija, trap, delovi, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Honda FR-V: šest sedišta koja moraju dokazati porodični život',
                'slug' => 'polovni-honda-fr-v-sest-sedista-koja-moraju-dokazati-porodicni-zivot',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Honda FR-V nudi neobičnih šest sedišta i praktičnost, ali kupac mora proveriti motor, trap, klimu, koroziju, enterijer i koliko porodica već umorila auto.',
                'content' => <<<'TEXT'
Dejan je tražio porodični auto za troje dece, ali nije želeo veliki van ni SUV cenu. Honda FR-V mu je zato izgledala kao pametna niša: tri sedišta napred, tri pozadi i Hondina reputacija. Na prvom gledanju auto je delovao zanimljivo, ali kod ovakvog modela raspored sedišta ne sme da skrene pažnju sa godina, održavanja i porodičnog umora.

Prvo proveri motor i servisni ritam. Benzinske verzije treba da pale mirno, rade ravnomerno i imaju dokaz o redovnim servisima. Dizel može biti prijatan za duži put, ali traži proveru turbine, EGR-a, kvačila, zamajca i relacija na kojima je korišćen. FR-V nije nov auto i ne treba ga kupiti samo zato što Honda ima dobru reputaciju.

Druga provera je kabina. Svako sedište, pojas, preklapanje, ISOFIX ako postoji, klima, ventilacija, brave, podizači i gepek moraju se proveriti kao da će se koristiti svakog dana. Porodični auto često krije tragove dečjih sedišta, prosute tečnosti, izgrebane plastike i umor mehanizama. To nije automatski razlog za odustajanje, ali jeste razlog za realnu cenu.

FR-V ima smisla kada stvarno koristiš šest sedišta i kada primerak ne traži velika početna ulaganja. Ako ti treba običan porodični auto, možda je Civic, Jazz, Scenic ili Touran lakša kupovina. Ako ti neobičan raspored rešava konkretan problem, plati detaljan pregled trapa, kočnica, korozije i klime. Kupovina je dobra tek kada praktičnost i stanje rade zajedno.
TEXT,
                'highlights' => [
                    'FR-V ima smisla samo ako kupac stvarno koristi šest sedišta i neobičan raspored kabine.',
                    'Motor, kvačilo, zamajac, trap, kočnice i korozija moraju biti važniji od Hondine reputacije.',
                    'Kabinu proveri kroz svako sedište, pojas, klimu, brave, podizače i tragove porodičnog umora.',
                ],
                'tags' => ['Honda FR-V', 'porodični auto', 'šest sedišta', 'monovolumen', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Honda FR-V: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Honda FR-V modela: šest sedišta, motor, dizel, trap, klima, korozija, enterijer i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Grejač zadnjeg stakla ne radi: kada sitna linija otkriva veći problem',
                'slug' => 'grejac-zadnjeg-stakla-ne-radi-kada-sitna-linija-otkriva-veci-problem',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Neispravan grejač zadnjeg stakla nije samo zimska neprijatnost, već razlog da proveriš instalaciju, staklo, gepek, vlagu, osigurače i tragove popravke.',
                'content' => <<<'TEXT'
Ana je skoro kaparisala mali karavan dok nije primetila da zadnje staklo ostaje zamagljeno i posle nekoliko minuta rada grejača. Prodavac je rekao da je to sitnica, ali majstor je pogledao prekidač, osigurače, provodnike i gepek. Kod polovnog auta sitna linija na staklu ponekad otkrije mnogo širu priču: lošu popravku, vlagu, oštećenu instalaciju ili zamenjeno staklo bez pažnje.

Prva provera je jednostavna. Uključi grejač zadnjeg stakla i prati da li se magla ravnomerno povlači. Ako rade samo neke linije, moguće je fizičko oštećenje grejnih niti. Ako ne radi ništa, treba proveriti prekidač, osigurač, relej, kontakte, masu i instalaciju kroz vrata ili poklopac gepeka. Ne prihvataj objašnjenje da "samo treba vremena" ako nema promene.

Drugi korak je povezivanje sa stanjem zadnjeg dela auta. Proveri da li je staklo menjano, da li ima vlage u gepeku, tragova skidanja tapacirunga, loših dihtunga, korozije oko vrata ili problema sa brisačem i bravom gepeka. Kod hečbeka i karavana kablovi u pregibu često trpe otvaranje i zatvaranje, pa kvar grejača može ići zajedno sa svetlima, bravom ili zadnjim brisačem.

Kupovina se može nastaviti ako je kvar jasan, jeftin i ne prati ga vlaga ili trag ozbiljne popravke. Ako je zadnje staklo zamenjeno posle udarca, ako voda ulazi u kabinu ili ako više funkcija zadnjeg dela ne radi, pregovor mora biti stroži. Grejač zadnjeg stakla nije najskuplji deo auta, ali je dobar test koliko prodavac ozbiljno shvata sitnice koje utiču na svakodnevnu bezbednost.
TEXT,
                'highlights' => [
                    'Grejač proveri u realnom radu: magla treba da se povlači ravnomerno kroz nekoliko minuta.',
                    'Ako ne radi ništa, proveri osigurač, relej, prekidač, masu, kontakte i instalaciju kroz gepek.',
                    'Kvar poveži sa zamenjenim staklom, vlagom, zadnjim brisačem, bravom i tragovima popravke.',
                ],
                'tags' => ['grejač zadnjeg stakla', 'elektrika', 'vlaga', 'gepek', 'provera vozila'],
                'meta_title' => 'Grejač zadnjeg stakla ne radi: šta proveriti',
                'meta_description' => 'Kako proveriti grejač zadnjeg stakla kod polovnog auta: osigurač, relej, instalacija, vlaga, gepek, zamenjeno staklo i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Euro 5 dizel u Srbiji: kada niska cena još ima smisla, a kada je zamka',
                'slug' => 'euro-5-dizel-u-srbiji-kada-niska-cena-jos-ima-smisla-a-kada-je-zamka',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Euro 5 dizeli često nude mnogo auta za manje novca, ali kupac mora sabrati DPF, EGR, kilometražu, relacije, ekologiju, registraciju i kasniju prodaju.',
                'content' => <<<'TEXT'
Vladimir je gledao porodični karavan Euro 5 norme koji je bio znatno jeftiniji od mlađeg Euro 6 primerka. Oglas je delovao razumno: dobar prostor, mala potrošnja i cena koja ostavlja novac za servis. Ipak, kod starijeg dizela prava odluka nije samo da li auto sada radi dobro, nego da li njegov režim vožnje, održavanje i kasnija prodaja odgovaraju kupcu u Srbiji.

Euro 5 dizel ima smisla za vozača koji prelazi duže relacije, vozi otvoren put i zna da mu dizel tehnologija odgovara. Tada potrošnja, obrtni moment i niža početna cena mogu biti realna prednost. Ali DPF, EGR, turbina, dizne, zamajac i automatski menjač moraju biti provereni pre kapare. Jeftiniji oglas nije ušteda ako odmah traži skupu emisijsku ili mehaničku intervenciju.

Za gradsku vožnju računica je slabija. Kratke relacije, hladan motor, gužva i retko izduvavanje brzo pretvaraju dizel u auto koji stalno traži pažnju. Kupac treba da pogleda istoriju regeneracija ako je dostupna, dim, hladan start, lampice, greške, servisne račune i da li je DPF fizički prisutan. Uklonjen DPF nije rešenje, nego rizik za tehnički pregled, ekologiju i kasniju prodaju.

Euro 5 dizel nije automatski loša kupovina, ali više nije univerzalan odgovor. Ima smisla kada je cena dovoljno niža, istorija jasna, relacije duže i početna ulaganja sabrana. Ako auto kupuješ za kratke gradske vožnje ili planiraš brzu prodaju, mlađi benzinac, hibrid ili uredan Euro 6 može biti mirnija odluka. Tržišna cena treba da prizna stariju normu, ne samo dobru opremu.
TEXT,
                'highlights' => [
                    'Euro 5 dizel ima smisla za duže relacije i jasnu servisnu istoriju, ne za kratku gradsku rutinu.',
                    'DPF, EGR, turbina, dizne, zamajac i menjač moraju ući u računicu pre kapare.',
                    'Niža cena vredi samo ako kasnija prodaja, ekologija i početna ulaganja ne ponište uštedu.',
                ],
                'tags' => ['Euro 5 dizel', 'dizel', 'DPF', 'EGR', 'analiza tržišta'],
                'meta_title' => 'Euro 5 dizel u Srbiji: kada ga kupiti',
                'meta_description' => 'Analiza kupovine Euro 5 dizela u Srbiji: DPF, EGR, kilometraža, gradska vožnja, otvoren put, tehnički pregled, cena i kasnija prodaja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Daihatsu Sirion ili Mitsubishi Colt: mali Japanac kada retkost menja cenu',
                'slug' => 'daihatsu-sirion-ili-mitsubishi-colt-mali-japanac-kada-retkost-menja-cenu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Sirion i Colt mogu biti racionalni mali automobili za grad, ali kupac mora proveriti delove, koroziju, kvačilo, trap i da li retkost zaista spušta cenu.',
                'content' => <<<'TEXT'
Ivana je tražila mali benzinac za Novi Sad, kratke relacije i početnički budžet, ali nije želela još jednu Corsu ili Punto. Daihatsu Sirion i Mitsubishi Colt su joj delovali zanimljivo jer nude japansku reputaciju za manje novca. Prvi obilazak je brzo pokazao da kod retkih malih automobila pitanje nije samo koji je pouzdaniji, nego koji možeš održavati bez čekanja i iznenađenja.

Sirion ima smisla kada je primerak zdrav, jednostavan i realno jeftin. Proveri hladan start, rad kvačila, menjač, klimu, servo, trap i koroziju na pragovima, podu i zadnjim rubovima. Ako prodavac naglašava samo da je "japanac", traži račune i proveri dostupnost delova za konkretan motor. Reputacija ne pomaže mnogo ako mali kvar čeka deo nedeljama.

Colt često nudi ozbiljniji osećaj u vožnji i upotrebljiviju kabinu, ali ni on ne sme da se kupi površno. Obrati pažnju na lanac ili servisni ritam, potrošnju ulja, elektroniku, zadnja vrata, trap, kočnice i stanje enterijera. Primerak koji je služio kao gradski auto može imati malo kilometara, a ipak umorno kvačilo, akumulator, gume i amortizere.

Pametnija kupovina je auto kod kog retkost radi za kupca kroz nižu cenu, a ne protiv kupca kroz skuplju nabavku delova. Sirion je bolji ako je jednostavniji, zdraviji i osetno povoljniji. Colt ima prednost ako dobijaš bolji prostor, bolji osećaj i uredniju istoriju. Ako oba automobila imaju sličnu cenu, izaberi onaj koji majstor može jasnije da pregleda i za koji odmah znaš cenu početnog servisa.
TEXT,
                'highlights' => [
                    'Sirion ima smisla samo ako retkost ne komplikuje delove, servis i početna ulaganja.',
                    'Colt može ponuditi bolji osećaj i prostor, ali traži proveru lanca, trapa i gradske upotrebe.',
                    'Kod oba modela cena mora priznati starost, koroziju, kvačilo, gume i dostupnost delova.',
                ],
                'tags' => ['Daihatsu Sirion', 'Mitsubishi Colt', 'mali auto', 'benzinac', 'poređenje'],
                'meta_title' => 'Daihatsu Sirion ili Mitsubishi Colt: koji mali auto kupiti',
                'meta_description' => 'Poređenje polovnih Daihatsu Sirion i Mitsubishi Colt modela: benzinac, delovi, korozija, kvačilo, trap, gradska vožnja i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Suzuki Splash: mali auto koji mora dokazati gradsku rutinu',
                'slug' => 'polovni-suzuki-splash-mali-auto-koji-mora-dokazati-gradsku-rutinu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Splash je praktičan i pregledan mali auto, ali dobar primerak mora dokazati motor, kvačilo, trap, klimu, koroziju i cenu koja ne kažnjava retkost.',
                'content' => <<<'TEXT'
Miloš je tražio auto za majku: viši ulazak, dobra preglednost, mali trošak i dovoljno prostora za pijacu i lekara. Suzuki Splash se pojavio kao logičan kandidat jer nije velik, ali ne deluje prenisko ni tesno. Na oglasima je izgledao kao jednostavna kupovina, dok pregled nije otvorio pitanja koja mali gradski automobili često kriju.

Prvo proveri kako je auto živeo u gradu. Kvačilo, menjač, kočnice, amortizeri, spone, gume i felne često trpe više od motora. Probna vožnja treba da uključi neravnine, parkiranje, okretanje volana u mestu i kočenje pri maloj brzini. Ako auto lupa, vuče ili kvačilo hvata visoko, niska potrošnja ne rešava prvi račun.

Druga tema su motor, klima i korozija. Benzinac mora paliti mirno hladan, raditi bez trzaja i imati jasan servis ulja. Klima treba da hladi bez mirisa vlage, a pod, pragovi, rubovi i donji deo vrata treba da se pogledaju na dizalici. Splash je mali auto, ali popravka lima i zapuštena klima mogu brzo pojesti razliku između dobrog i najjeftinijeg oglasa.

Splash ima smisla kada kupac želi praktičan gradski auto za mirnu svakodnevicu, a ne status ili brzinu. Dobar primerak treba da ima urednu istoriju, očuvan enterijer i cenu koja ostavlja prostor za gume, servis i registraciju. Ako prodavac traži premiju samo zato što je Suzuki i "malo troši", pregovor vrati na stvarno stanje, delove i rezultate probne vožnje.
TEXT,
                'highlights' => [
                    'Splash je dobar za viši ulazak i grad, ali kvačilo, trap i kočnice moraju proći probnu vožnju.',
                    'Benzinski motor, klima, pragovi, pod i rubovi odlučuju da li je mali trošak stvaran.',
                    'Retkost modela vredi samo ako cena ostavlja prostor za servis, gume i lako dostupne delove.',
                ],
                'tags' => ['Suzuki Splash', 'mali auto', 'gradski auto', 'benzinac', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Suzuki Splash: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Suzuki Splash modela: benzinac, kvačilo, trap, klima, korozija, gradska upotreba i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#14b8a6', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Fiat Qubo: praktičan kutijasti auto koji ne sme sakriti radni život',
                'slug' => 'polovni-fiat-qubo-praktican-kutijasti-auto-koji-ne-sme-sakriti-radni-zivot',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Qubo nudi klizna vrata i odličnu praktičnost, ali kupac mora proveriti da li je auto bio porodični pomoćnik, dostavno vozilo ili umoran gradski alat.',
                'content' => <<<'TEXT'
Aleksandar je tražio auto koji može da nosi dečji bicikl, kolica, alat i vikend prtljag, ali nije želeo veliki van. Fiat Qubo mu je delovao idealno: kratak spolja, kutijast unutra i sa kliznim vratima koja olakšavaju parking. Ipak, takav oblik često privlači i porodice i male poslove, pa se pre kupovine mora razumeti kako je auto stvarno korišćen.

Prva provera je kabina i tovarni trag. Pogledaj pod gepeka, obloge, prag utovara, klizna vrata, šine, brave, sedišta, pojaseve i plastike. Ako se vide tragovi alata, dostave, vlage ili jakog habanja, cena mora biti drugačija od porodičnog primerka. Qubo može izgledati simpatično spolja, a unutra pokazati težak radni život.

Druga tema su motor, trap i menjač. Dizel ima smisla samo ako su relacije i servisna istorija jasni, jer kratka gradska upotreba opterećuje EGR, turbinu i DPF kod primeraka koji ga imaju. Benzinac je mirniji za grad, ali proveri potrošnju, kvačilo, hladan start i curenja. Trap, zadnji kraj, kočnice i gume moraju se gledati kao kod malog dostavnog vozila, ne samo kao kod putničkog auta.

Qubo je dobra kupovina kada stvarno koristiš prostor, klizna vrata i jednostavnu praktičnost. Nema smisla ako ga plaćaš kao očuvan porodični auto, a pregled pokaže da je već odradio posao za nekog drugog. Ako istorija, kabina i mehanika pričaju istu priču, pregovaraj na osnovu početnog servisa. Ako prodavac izbegava pitanje prethodne namene, nastavi potragu.
TEXT,
                'highlights' => [
                    'Qubo prvo proveri kroz kabinu, klizna vrata, prag utovara i tragove dostavne upotrebe.',
                    'Dizel traži jasne relacije i servis, dok benzinac bolje odgovara kratkoj gradskoj rutini.',
                    'Cena mora razlikovati porodični primerak od auta koji je već radio kao mali teretnjak.',
                ],
                'tags' => ['Fiat Qubo', 'porodični auto', 'klizna vrata', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Fiat Qubo: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Fiat Qubo modela: klizna vrata, kabina, dostavna upotreba, dizel, benzinac, trap i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Ručna kočnica na polovnom autu: kada visok hod otkriva skuplji zadnji kraj',
                'slug' => 'rucna-kocnica-na-polovnom-autu-kada-visok-hod-otkriva-skuplji-zadnji-kraj',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Ručna kočnica nije sitnica za tehnički pregled, već trag za proveru sajli, čeljusti, doboša, diskova, zadnjih ležajeva i zapuštenog održavanja.',
                'content' => <<<'TEXT'
Nikola je na probnoj vožnji skoro završio kupovinu kada je na blagoj uzbrdici povukao ručnu i auto se polako pomerio. Prodavac je rekao da "samo treba zategnuti", ali majstor je tražio da se pogleda zadnji kraj. Visok hod ručne kočnice nekad jeste podešavanje, ali nekad otvara priču o sajlama, čeljustima, dobošima, diskovima i dugom odlaganju servisa.

Prva provera je ponašanje na mestu i u vožnji. Ručna treba da hvata u razumnom hodu, da drži auto na blagoj uzbrdici i da se posle spuštanja točkovi slobodno okreću. Ako hvata previsoko, ne drži ravnomerno ili se auto teško pokreće posle spuštanja, ne prihvataj objašnjenje bez pregleda. Problem može biti jednostavan, ali mora biti jasan.

Na dizalici treba pogledati sajle, povratne opruge, zadnje čeljusti, doboše ili diskove, pločice, paknove, ležajeve i tragove korozije. Kod automobila koji dugo stoje ili se voze kratko, zadnje kočnice često zaribaju, a problem se maskira pranjem i kratkom probnom vožnjom. Ako je sistem elektronske parking kočnice, proveri lampice, dijagnostiku i rad oba zadnja točka.

Kupovina se može nastaviti kada je uzrok poznat i cena popravke uračunata. Ako ručna ne drži, zadnje kočnice su neravnomerne, čeljusti cure ili prodavac odbija dizalicu, rizik više nije sitnica. Ručna kočnica je mali test discipline održavanja. Auto koji ne može mirno da stoji na uzbrdici ne treba da dobije kaparu pre jasnog pregleda.
TEXT,
                'highlights' => [
                    'Ručna kočnica mora držati na uzbrdici i otpuštati bez zadržavanja zadnjih točkova.',
                    'Sajle, čeljusti, doboši, diskovi, paknovi, ležajevi i korozija traže pregled na dizalici.',
                    'Visok hod je prihvatljiv samo kada je uzrok jasan i cena popravke uđe u pregovor.',
                ],
                'tags' => ['ručna kočnica', 'zadnje kočnice', 'sajle', 'tehnički pregled', 'provera vozila'],
                'meta_title' => 'Ručna kočnica na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti ručnu kočnicu kod polovnog auta: visok hod, sajle, čeljusti, doboši, diskovi, zadnji ležajevi, korozija i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Slovačke: kada dobra cena traži proveru porekla i flote',
                'slug' => 'uvoz-auta-iz-slovacke-kada-dobra-cena-trazi-proveru-porekla-i-flote',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Slovački uvoz može izgledati povoljno i blisko, ali kupac mora proveriti flotnu istoriju, kilometražu, koroziju, dokumentaciju i pun trošak registracije.',
                'content' => <<<'TEXT'
Petar je našao karavan iz Slovačke koji je bio povoljniji od sličnih nemačkih oglasa, a dovoljno blizu da se odlazak organizuje za vikend. Oprema je bila dobra, kilometraža umerena, a prodavac je naglašavao da je auto iz EU. Baš tada treba usporiti. Slovačko tržište može ponuditi dobre automobile, ali i primerke iz flote, rent-a-car upotrebe ili prethodnog uvoza iz druge zemlje.

Prva provera je putanja vozila. VIN, saobraćajna, odjava, servisni računi, tehnički pregledi i broj vlasnika moraju pokazati da li je auto zaista slovački primerak ili samo prolazi kroz Slovačku. Ako je bio službeni, leasing ili flotni auto, to nije automatski loše, ali cena mora priznati način korišćenja i veću kilometražu kroz vreme.

Druga tema su stanje i zimski uslovi. Pregled na dizalici treba da obuhvati pragove, pod, kočione cevi, rubove, auspuh, trap, amortizere i tragove svežeg zaštitnog premaza. Enterijer, sedište vozača, volan, pedale i gepek treba da potvrde broj na satu. Ako je auto pripremljen za prodaju, ali ispod ima so, rđu ili umoran trap, dobra cena brzo gubi smisao.

Uvoz iz Slovačke ima smisla kada dokumentacija, kilometraža i stanje rade zajedno. Pre poređenja sa domaćim oglasima saberi transport, prevod, homologaciju, carinu, porez, registraciju, početni servis i gume. Ako prodavac žuri, nudi nepotpune papire ili priču svodi na "EU auto", bolje je platiti proveru nego kupiti nečiju flotnu nepoznanicu.
TEXT,
                'highlights' => [
                    'Slovački uvoz proveri kroz VIN, odjavu, servisne račune, tehničke preglede i broj vlasnika.',
                    'Flotna ili leasing istorija nije problem samo ako kilometraža, stanje i cena ostaju logični.',
                    'Pun trošak mora uključiti transport, prevod, dažbine, registraciju, servis, gume i pregled podvozja.',
                ],
                'tags' => ['uvoz iz Slovačke', 'uvoz auta', 'flotno vozilo', 'kilometraža', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Slovačke: šta proveriti',
                'meta_description' => 'Vodič za kupovinu auta iz Slovačke: VIN, dokumentacija, flotna istorija, kilometraža, korozija, troškovi uvoza i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'SsangYong Korando ili Renault Koleos: ređi SUV kada cena nije dovoljna',
                'slug' => 'ssangyong-korando-ili-renault-koleos-redji-suv-kada-cena-nije-dovoljna',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Korando i Koleos mogu ponuditi mnogo SUV-a za manje novca, ali kupac mora proveriti delove, pogon, menjač i kasniju prodaju pre kapare.',
                'content' => <<<'TEXT'
Miloš je tražio porodični SUV za vikend putovanja i lošiji seoski put, ali nije želeo da plati cenu Tiguana ili RAV4. U oglasima su mu zapali za oko SsangYong Korando i Renault Koleos jer za isti budžet nude više prostora, bolju opremu i često mirniju kilometražu. Prvi obilazak ga je brzo spustio na zemlju: kod ređeg SUV-a niska cena nije dovoljna ako delovi, servis i kasnija prodaja nisu jasni.

Korando ima smisla za kupca koji želi jednostavniji, robusniji osećaj i ne plaši se manje popularne značke. Treba proveriti motor, menjač, pogon ako postoji, koroziju podvozja, dostupnost delova i da li lokalni servis zaista poznaje model. Ako prodavac ne može da pokaže račune i ako se svaki odgovor svodi na to da je auto "kao nov", rizik se samo prebacuje na prvog narednog vlasnika.

Koleos deluje poznatije zbog Renault mreže, udobnije kabine i mirnijeg porodičnog karaktera, ali ni on ne sme da se kupi samo zato što je jeftiniji od popularnijih SUV-ova. Posebno proveri dizel servis, automatski menjač ako ga ima, elektroniku, trap i tragove teže upotrebe. Udobnost je prednost samo ako stanje potvrđuje da auto nije godinama krpio budžet prethodnog vlasnika.

Najbolja odluka između Koranda i Koleosa nije pitanje koji je lepši u oglasu, nego koji ostavlja manje nepoznanica posle pregleda. Ako Korando ima jasnu istoriju, dobar servis u blizini i realnu cenu, može biti razumna kupovina. Ako Koleos ima bolju dokumentaciju i lakšu podršku, vredi platiti malo više. Ako nijedan ne može dokazati održavanje, bolji je skuplji ali proverljiviji SUV nego retka prilika koja se teško popravlja.
TEXT,
                'highlights' => [
                    'Kod ređeg SUV-a prvo proveri servisnu podršku, delove i kasniju prodaju.',
                    'Korando ima smisla kada stanje i održavanje nadoknade slabiju tržišnu likvidnost.',
                    'Koleos je mirniji izbor samo ako dizel, menjač, elektronika i trap imaju jasnu istoriju.',
                ],
                'tags' => ['SsangYong Korando', 'Renault Koleos', 'SUV', 'poređenje', 'delovi'],
                'meta_title' => 'SsangYong Korando ili Renault Koleos: polovni SUV',
                'meta_description' => 'Poređenje polovnih SsangYong Korando i Renault Koleos SUV modela: delovi, pogon, menjač, servis, kasnija prodaja i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Subaru XV: crossover koji mora dokazati pogon, servis i rđu',
                'slug' => 'polovni-subaru-xv-crossover-koji-mora-dokazati-pogon-servis-i-rdju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Subaru XV privlači stalnim pogonom i japanskom reputacijom, ali dobar primerak mora imati jasan servis, zdravo podvozje i realnu cenu delova.',
                'content' => <<<'TEXT'
Jelena je gledala Subaru XV jer joj je trebalo nešto više od običnog kompakta: povišen klirens, siguran osećaj zimi i auto koji ne izgleda kao svaki drugi crossover na parkingu. Na prvoj probnoj vožnji XV je delovao čvrsto i drugačije, ali prodavac nije imao račune za redovne servise. Kod ovakvog auta upravo ta praznina odlučuje da li je posebnost prednost ili budući trošak.

Prva provera je pogon. Stalni pogon na sva četiri točka je razlog za kupovinu Subaru XV-a, ali mora raditi tiho i bez zatezanja, vibracija ili čudnih zvukova pri manevrisanju. Gume moraju biti iste dimenzije, slične starosti i pravilno trošene, jer loša kombinacija guma može opteretiti pogon. Ako prodavac štedi na gumama, pitanje je gde je još štedeo.

Druga provera su motor, CVT ako ga auto ima i servisna istorija. Boxer motor traži redovno ulje i hladan start bez neobičnih zvukova, a CVT mora imati mirno kretanje, bez zavijanja koje ne prati ubrzanje i bez zadrške kada se zagreje. Računi za servis ulja, svećice, kočnice i tečnosti vrede više od opšte priče da je Subaru pouzdan.

Treća provera je podvozje. XV se često kupuje zbog lošijih puteva, snega i vikend upotrebe, pa dizalica mora pokazati pragove, rubove, nosače, izduv, kočione cevi i tragove korozije. Dobar XV je zanimljiv polovnjak za kupca koji stvarno koristi pogon i povišen klirens. Loš primerak je samo ređi crossover sa skupljim delovima i manjim brojem kupaca kada dođe vreme prodaje.
TEXT,
                'highlights' => [
                    'Stalni pogon je prednost samo kada su gume, zvukovi i servis pogona uredni.',
                    'Kod CVT-a proveri hladnu i toplu probnu vožnju, zadršku i račune za održavanje.',
                    'Podvozje i korozija moraju se gledati na dizalici, posebno kod auta koji je vožen zimi.',
                ],
                'tags' => ['Subaru XV', 'polovni crossover', '4x4', 'CVT', 'korozija'],
                'meta_title' => 'Polovni Subaru XV: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Subaru XV modela: stalni pogon, gume, CVT, boxer motor, korozija, servisna istorija i delovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Seat Alhambra: porodični van koji mora opravdati klizna vrata i dizel',
                'slug' => 'polovni-seat-alhambra-porodicni-van-koji-mora-opravdati-klizna-vrata-i-dizel',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Alhambra je ozbiljno porodično rešenje, ali polovan primerak mora dokazati DSG, dizel, klizna vrata, sedišta i stvaran život kabine.',
                'content' => <<<'TEXT'
Marko i Ivana su gledali Seat Alhambru jer troje dece, kolica i putovanja više nisu stajali u običan karavan. Auto je na fotografijama delovao kao idealno rešenje: sedam sedišta, klizna vrata, veliki gepek i dobar dizel. Tek kada su počeli da otvaraju svaka vrata i pomeraju svako sedište, postalo je jasno da porodični van ne sme da se kupi iz jedne lepe slike.

Prva provera su klizna vrata, sedišta i kabina. Vrata moraju raditi glatko, bez grešaka brave, preskakanja šina ili čudnog zvuka motora ako su električna. Sva sedišta, pojasevi, ISOFIX tačke, preklapanje, ventilacija pozadi i plastike treba proveriti kao da će porodica sutra krenuti na put. Umorna kabina nije samo estetika, već trag koliko je auto zaista radio.

Druga provera su dizel i menjač. Alhambra se često kupuje za duge relacije, ali mnogi primerci imaju veliku kilometražu, službenu ili porodičnu eksploataciju i zaostale servise. Kod TDI motora proveri DPF, EGR, turbinu, zamajac, curenja i hladan start. Ako je auto sa DSG-om, račun za servis ulja i probna vožnja u gužvi nisu dodatak, nego uslov.

Alhambra ima smisla kada stvarno koristiš prostor i kada cena odražava stanje, a ne samo činjenicu da je auto praktičan. Dobar primerak može zameniti SUV, karavan i monovolumen u jednoj kupovini. Loš primerak brzo pretvara porodičnu praktičnost u listu sitnih kvarova: vrata, klima, senzori, sedišta, dizel i menjač. Ako prodavac žuri kroz proveru kabine, kupac treba da uspori.
TEXT,
                'highlights' => [
                    'Klizna vrata, sedišta, pojasevi, ISOFIX i zadnja ventilacija moraju raditi bez izgovora.',
                    'TDI i DSG traže račune, hladan start i probnu vožnju u realnoj gradskoj gužvi.',
                    'Alhambra je dobra kupovina samo ako prostor prati uredna istorija i realna cena.',
                ],
                'tags' => ['Seat Alhambra', 'porodični van', 'sedam sedišta', 'TDI', 'DSG'],
                'meta_title' => 'Polovni Seat Alhambra: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovne Seat Alhambre: klizna vrata, sedam sedišta, TDI, DSG, klima, porodični umor i servisna istorija.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#84cc16', '#f8fafc'],
            ],
            [
                'title' => 'Parking senzori na polovnom autu: kada pištanje krije branik, instalaciju ili modul',
                'slug' => 'parking-senzori-na-polovnom-autu-kada-pistanje-krije-branik-instalaciju-ili-modul',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Neispravni parking senzori deluju kao sitnica, ali mogu otkriti udarac, lošu popravku branika, vlagu u instalaciji ili skuplji elektronski kvar.',
                'content' => <<<'TEXT'
Nemanja je skoro završio kupovinu kompaktnog karavana kada je pri rikvercu čuo konstantno pištanje parking senzora. Prodavac je odmah rekao da je "samo senzor" i ponudio mali popust. Problem je što kod polovnog auta parking senzori retko treba da se posmatraju sami: oni često pričaju priču o braniku, udarcu, instalaciji i kvalitetu prethodne popravke.

Prva provera je jednostavna. Uključi rikverc, proveri svaki senzor rukom ili približavanjem predmeta, slušaj da li sistem reaguje ravnomerno i pogledaj da li se greška javlja odmah ili tek posle pranja, kiše ili vožnje. Senzor koji radi povremeno može značiti vlagu, loš konektor, napuklo kućište ili instalaciju koja je loše vraćena posle skidanja branika.

Druga provera je branik. Neujednačeni zazori, druga nijansa boje, polomljeni nosači, loše uklopljeni senzori ili tragovi varenja plastike znače da kvar nije samo elektronski. Ako auto ima kameru, automatsko parkiranje ili fabrički prikaz na ekranu, proveri da li svi sistemi rade zajedno. Jeftina zamena senzora ne rešava problem ako je modul, kabl ili branik već pretrpeo lošu popravku.

Kupovina nije automatski loša zbog parking senzora, ali cena mora priznati uzrok, ne samo simptom. Ako dijagnostika pokaže tačan senzor i branik je uredan, to je pregovaračka stavka. Ako prodavac ne dozvoljava proveru, greška se briše pred dolazak ili se vide tragovi udarca, bolje je odustati nego prihvatiti priču da je sitnica. Sitnice kod elektronike često postanu skupe tek posle prenosa.
TEXT,
                'highlights' => [
                    'Parking senzore proveri pojedinačno, na rikvercu, uz ekran, kameru i realno manevrisanje.',
                    'Grešku poveži sa zazorima branika, bojom, nosačima, vlagom i instalacijom.',
                    'Popust ima smisla tek kada dijagnostika pokaže tačan uzrok i cenu popravke.',
                ],
                'tags' => ['parking senzori', 'provera vozila', 'branik', 'elektronika', 'dijagnostika'],
                'meta_title' => 'Parking senzori na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti parking senzore na polovnom autu: branik, udarac, vlaga, instalacija, modul, kamera, dijagnostika i pregovor cene.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Euro 4 benzinac u Srbiji: kada niska cena još uvek ima smisla',
                'slug' => 'euro-4-benzinac-u-srbiji-kada-niska-cena-jos-uvek-ima-smisla',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Stariji Euro 4 benzinci mogu biti razumna kupovina za mali budžet, ali samo kada registracija, stanje, potrošnja i očekivanja ostanu prizemni.',
                'content' => <<<'TEXT'
Nikola je tražio auto za posao, pijacu i povremeni odlazak van grada, sa budžetom koji ne trpi moderni dizel, automatik ili skupe elektronske sisteme. U oglasima su se izdvojili stariji Euro 4 benzinci: jednostavniji, jeftiniji za kupovinu i često manje rizični od zapuštenog dizela. Ipak, niska cena nije plan ako auto odmah traži gume, veliki servis, trap i registraciju.

Euro 4 benzinac ima smisla kada kupac vozi kratke relacije, ne želi DPF i EGR brige i prihvata da auto neće imati savremenu opremu. Jednostavniji motor može biti prednost, ali samo ako pali hladan, radi mirno, ne troši ulje, nema curenja i ima bar osnovne servisne tragove. Kod starijeg benzinca stanje karoserije, pragova, kočnica i trapa često odlučuje više od samog motora.

Tržišna zamka je u tome što se niska cena lako pomeša sa niskim troškom. Kupac vidi mali iznos u oglasu, ali zaboravi da su gume, akumulator, auspuh, veliki servis, kvačilo i registracija skoro isti trošak bez obzira na vrednost auta. Ako početna ulaganja pređu razuman deo cene, jeftin auto prestaje da bude jeftin i postaje privremeno rešenje koje stalno traži novac.

Pametna kupovina Euro 4 benzinca je miran, uredan primerak sa realnom cenom i jasnom namenom: grad, kratke relacije, skroman budžet i jednostavno održavanje. Nije pametna ako kupac očekuje čudo, dugu autoput udobnost ili kasniju prodaju bez gubitka. Ako pregled pokaže zdravu karoseriju, miran motor i malu listu ulaganja, stariji benzinac još može imati smisla. Ako stanje nije jasno, bolje je sačekati nego kupiti najjeftiniji oglas.
TEXT,
                'highlights' => [
                    'Euro 4 benzinac ima smisla za kratke relacije kada kupac želi da izbegne dizel rizike.',
                    'Karoserija, trap, gume, servis i registracija mogu biti veći problem od samog motora.',
                    'Niska cena vredi samo ako početna ulaganja ne pojedu prednost jeftine kupovine.',
                ],
                'tags' => ['Euro 4', 'benzinac', 'jeftin polovnjak', 'tržište polovnjaka', 'budžet'],
                'meta_title' => 'Euro 4 benzinac u Srbiji: kada se isplati',
                'meta_description' => 'Analiza kupovine Euro 4 benzinca u Srbiji: niska cena, kratke relacije, registracija, karoserija, trap, ulaganja i dizel alternative.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Suzuki Baleno ili Hyundai i20: mali auto kada prostor i cena ne govore sve',
                'slug' => 'suzuki-baleno-ili-hyundai-i20-mali-auto-kada-prostor-i-cena-ne-govore-sve',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Baleno i i20 mogu delovati kao isti gradski izbor, ali kupac mora uporediti prostor, dostupnost delova, servisnu istoriju i kasniju prodaju pre kapare.',
                'content' => <<<'TEXT'
Ana je tražila mali auto za Novi Sad, ali ne najmanji mogući. Trebalo joj je da stanu dečje sedište, nedeljna kupovina i povremeni put do roditelja. U oglasima su joj se izdvojili Suzuki Baleno i Hyundai i20 jer deluju štedljivo, pregledno i dovoljno mlado za razuman budžet. Na papiru je Baleno nudio više prostora za novac, dok je i20 delovao poznatije i lakše za kasniju prodaju.

Baleno ima smisla za kupca koji želi lagan, prostran i jednostavan auto, ali ne sme se kupiti samo zato što je povoljniji od popularnijih gradskih modela. Proveri servisnu istoriju, stanje kvačila, trap, kočnice, klimu i dostupnost delova kod lokalnog majstora. Ako prodavac objašnjava cenu samo time da je model potcenjen, traži konkretne račune, hladan start i pregled podvozja.

Hyundai i20 je mirniji izbor za kupca koji želi poznatiji model, širu servisnu podršku i lakše poređenje oglasa. To ne znači da svaki i20 opravdava višu cenu. Kod gradskog auta gledaj tragove kratkih relacija: kvačilo, ogrebotine na branicima, trap, gume, sedište vozača, klimu i servis ulja. Dobar i20 vredi više samo ako stanje prati reputaciju.

Odluka između Balena i i20 ne treba da bude priča o znački, nego o stvarnoj upotrebi. Ako Baleno ima jasnu istoriju, zdrav trap i cenu koja ostavlja prostor za početni servis, može biti pametniji od skupljeg i20. Ako i20 ima bolju dokumentaciju, manje nepoznanica i lakšu kasniju prodaju, razlika u ceni ima smisla. Ako nijedan prodavac ne dozvoljava ozbiljnu proveru, pravi izbor je treći oglas.
TEXT,
                'highlights' => [
                    'Baleno vredi gledati kada prostor i cena dolaze uz jasnu istoriju, a ne samo retkost.',
                    'i20 ima prednost kroz poznatije tržište, ali samo ako stanje opravdava višu cenu.',
                    'Kod oba modela proveri kvačilo, trap, klimu, gume i tragove kratke gradske vožnje.',
                ],
                'tags' => ['Suzuki Baleno', 'Hyundai i20', 'mali auto', 'gradski auto', 'poređenje'],
                'meta_title' => 'Suzuki Baleno ili Hyundai i20: koji mali auto kupiti',
                'meta_description' => 'Poređenje polovnih Suzuki Baleno i Hyundai i20 modela: prostor, cena, servis, kvačilo, trap, gradska upotreba i kasnija prodaja.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#2dd4bf', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Opel Karl: mali auto koji mora dokazati gradsku upotrebu',
                'slug' => 'polovni-opel-karl-mali-auto-koji-mora-dokazati-gradsku-upotrebu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Opel Karl je jednostavan gradski auto, ali dobar primerak mora potvrditi kvačilo, trap, kočnice, klimu, kratke relacije i cenu koja ne kažnjava kupca.',
                'content' => <<<'TEXT'
Milan je tražio auto za majku: mali, pregledan, sa pet vrata i bez komplikovanog dizela. Opel Karl mu je delovao kao razumna kupovina jer nije skup kao traženiji gradski modeli, a dovoljno je nov da ne izgleda umorno. Prvi pregled ga je podsetio da mali gradski auto ne znači automatski mali rizik, posebno kada je život proveo u uskim ulicama i kratkim relacijama.

Prvo proveri kvačilo, menjač i trap. Karl se često koristi za polazak-stajanje vožnju, parkiranje uz ivičnjake i kratke rute na hladan motor. Probna vožnja treba da uključi kretanje uzbrdo, sporo manevrisanje, prelazak preko neravnina, kočenje i pun krug volanom. Ako se čuje lupanje, škripa ili menjač zapinje, cena mora priznati stvaran trošak, ne samo mali format auta.

Druga provera su klima, elektronika i tragovi kabine. Mali auto za grad često ima mnogo ulazaka, kratkih vožnji i parking oštećenja, pa pogledaj vrata, branike, rubove, sedište vozača, pedale, prekidače, ventilaciju i rad svih lampica. Jednostavan auto je prednost samo ako nije zapušten. Jeftina kupovina brzo prestaje da bude jeftina ako odmah traži gume, akumulator, kočnice i servis.

Opel Karl ima smisla kada kupac želi miran gradski alat, a ne auto za dokazivanje. Dobar primerak treba da pali hladan bez drame, da vozi pravo, da nema skrivenu koroziju i da ima bar osnovne servisne tragove. Ako prodavac traži cenu blisku poznatijim modelima, Karl mora ponuditi bolje stanje. Ako je istorija mutna, nastavi potragu, jer kod malog auta nema dovoljno prostora u budžetu za velika iznenađenja.
TEXT,
                'highlights' => [
                    'Karl prvo proveri kroz kvačilo, menjač, trap i realnu gradsku probnu vožnju.',
                    'Parking tragovi, kabina, klima i elektronika često otkrivaju koliko je auto radio.',
                    'Kupovina ima smisla samo ako cena ostavlja prostor za servis, gume, akumulator i kočnice.',
                ],
                'tags' => ['Opel Karl', 'mali auto', 'gradski auto', 'benzinac', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Opel Karl: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Opel Karl modela: kvačilo, trap, menjač, klima, gradska upotreba, parking oštećenja i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#1f2937', '#fb7185', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Peugeot Rifter: praktičan porodični van koji traži proveru radnog života',
                'slug' => 'polovni-peugeot-rifter-praktican-porodicni-van-koji-trazi-proveru-radnog-zivota',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Rifter nudi ogroman prostor i porodičnu praktičnost, ali kupac mora proveriti da li je bio porodični auto, dostavno vozilo ili umoran službeni primerak.',
                'content' => <<<'TEXT'
Jovan i Katarina su posle drugog deteta počeli da gledaju Peugeot Rifter jer im je klasičan kompakt postao tesan. Klizna vrata, visok krov i veliki gepek delovali su kao rešenje za kolica, bicikle i putovanja. Ali baš zato što Rifter može da služi i porodici i poslu, svaki polovan primerak mora prvo dokazati kakav je život zaista imao.

Prva provera je kabina, gepek i vrata. Pogledaj prag utovara, pod gepeka, obloge, zadnju klupu, ISOFIX tačke, klizna vrata, brave, šine i tragove alata ili dostave. Porodična upotreba ostavlja drugačije tragove od radne. Ako je auto oglašen kao porodičan, a unutra se vide oguljene plastike, savijen pod i umorne brave, cena mora biti bliža radnom vozilu.

Druga provera su motor i menjač. Kod dizela proveri servis ulja, AdBlue ako ga verzija ima, DPF, EGR, turbinu, curenja i hladan start. Kod benzinca gledaj miran rad, potrošnju ulja, servisni ritam i da li motor ima dovoljno snage za opterećen porodični auto. Menjač, kvačilo i trap treba testirati sa pažnjom jer visok i praktičan auto često nosi više tereta nego što fotografije pokazuju.

Rifter je odlična kupovina kada prostor stvarno koristiš i kada stanje potvrđuje urednu namenu. Nema smisla platiti ga kao očuvan porodični auto ako pregled govori da je bio službeni alat. Ako dokumentacija, kabina i mehanika pričaju istu priču, pregovaraj na osnovu početnog servisa i guma. Ako prodavac preskače pitanja o prethodnoj nameni, taj prostor verovatno krije skuplju priču.
TEXT,
                'highlights' => [
                    'Rifter prvo proveri kroz klizna vrata, gepek, obloge, sedišta i tragove radne upotrebe.',
                    'Dizel traži proveru AdBlue sistema, DPF-a, EGR-a, turbine, curenja i servisnog ritma.',
                    'Porodična praktičnost vredi samo ako cena prati stvarno stanje kabine, trapa i menjača.',
                ],
                'tags' => ['Peugeot Rifter', 'porodični van', 'klizna vrata', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Peugeot Rifter: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Peugeot Rifter modela: klizna vrata, kabina, radna upotreba, dizel, AdBlue, trap i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Lupanje preko neravnina na polovnom autu: kada trap traži pregovor ili odustajanje',
                'slug' => 'lupanje-preko-neravnina-na-polovnom-autu-kada-trap-trazi-pregovor-ili-odustajanje',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Lupanje iz trapa nije samo neprijatan zvuk; može otkriti ramena, kugle, stabilizatore, amortizere, nosače ili lošu popravku posle udarca.',
                'content' => <<<'TEXT'
Stefan je skoro dogovorio kupovinu porodičnog hečbeka kada je na kratkoj ulici sa ležećim policajcima čuo tupo lupanje napred desno. Prodavac je odmah rekao da je "samo gumica stabilizatora" i da to košta malo. Problem je što kupac ne zna da li zvuk dolazi iz gumice, kugle, ramena, amortizera, nosača ili traga starog udarca dok auto ne ode na dizalicu.

Prva provera je probna vožnja na različitim neravninama. Vozi sporo preko ležećeg policajca, pređi preko sitnih talasa, skreni punim volanom na parkingu i zakoči blago na neravnom asfaltu. Slušaj da li se zvuk javlja napred ili pozadi, levo ili desno, samo pri udaru ili i pri skretanju. Jedan zvuk može biti jeftin, ali kombinacija lupanja, zanošenja i nejednakog trošenja guma menja celu računicu.

Druga provera je dizalica. Majstor treba da proveri ramena, kugle, spone, krajeve spona, stabilizatore, amortizere, opruge, nosače motora, ležajeve točka, kočnice i tragove udarca na felni ili nosačima. Ako je auto nedavno opran odozdo, ima sveže zategnute delove ili različite gume, pitaj zašto. Trap retko strada sam od sebe; često priča priču o rupama, ivičnjacima ili lošem održavanju.

Kupovina nije automatski loša zbog lupanja, ali cena mora biti vezana za dijagnozu, ne za obećanje prodavca. Ako majstor potvrdi sitan deo i ostatak auta je uredan, to je pregovaračka stavka. Ako prodavac odbija dizalicu, zvuk se širi na više strana ili se vidi trag udarca, bolje je odustati. Trap je deo koji direktno utiče na bezbednost, gume i kasnije troškove.
TEXT,
                'highlights' => [
                    'Lupanje testiraj na ležećim policajcima, sitnim neravninama, kočenju i punom motanju.',
                    'Dizalica mora proveriti ramena, kugle, spone, stabilizatore, amortizere, nosače i tragove udarca.',
                    'Pregovor ima smisla tek kada majstor napiše uzrok i okvirnu cenu popravke.',
                ],
                'tags' => ['lupanje trapa', 'provera vozila', 'trap', 'amortizeri', 'probna vožnja'],
                'meta_title' => 'Lupanje preko neravnina: šta proveriti kod polovnjaka',
                'meta_description' => 'Kako proveriti lupanje iz trapa polovnog auta: probna vožnja, ramena, kugle, spone, stabilizatori, amortizeri, gume i pregovor cene.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#facc15', '#f8fafc'],
            ],
            [
                'title' => 'Euro 3 gradski auto: kada najjeftiniji oglas više nije najjeftinija kupovina',
                'slug' => 'euro-3-gradski-auto-kada-najjeftiniji-oglas-vise-nije-najjeftinija-kupovina',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Stariji Euro 3 gradski automobili mogu rešiti hitnu potrebu za prevozom, ali kupac mora sabrati registraciju, limariju, gume, servis i buduću prodaju.',
                'content' => <<<'TEXT'
Vladimir je tražio najjeftiniji gradski auto za odlazak na posao i vožnju deteta do vrtića. Euro 3 automobili u oglasima delovali su privlačno jer je cena bila niža od svake rate kredita. Ipak, najjeftiniji oglas nije isto što i najjeftinija kupovina. Kod starog gradskog auta svaki deo budžeta mora biti unapred izračunat, jer mali kvar brzo postane veliki procenat vrednosti auta.

Euro 3 gradski auto ima smisla samo kada kupac prihvata ograničenja: skromnu opremu, manju bezbednosnu rezervu, slabiju izolaciju, godine lima i verovatno kraću kasniju prodaju. Prednost je jednostavnija mehanika, ali samo ako motor pali hladan, ne dimi, ne troši ulje, trap ne lupa i karoserija nije načeta na pragovima, podu ili nosačima.

Tržišna zamka je u početnim ulaganjima. Gume, akumulator, veliki servis, kočnice, auspuh, registracija, mali servis i osnovna limarija mogu koštati skoro koliko razlika između lošeg i dobrog primerka. Ako auto nema tehnički pregled, ako prodavac ne želi probnu vožnju ili ako je cena niska zato što "treba malo ulaganja", traži precizan spisak pre nego što pregovaraš.

Pametna kupovina Euro 3 gradskog auta je uredan, jednostavan primerak kupljen za jasnu namenu i kratak horizont, ne projekat koji treba spašavati. Ako pregled pokaže zdravu osnovu i mala ulaganja, takav auto može služiti racionalno. Ako se niz troškova otvara već u prvih deset minuta, bolje je dodati novac za mlađi i zdraviji auto nego kupiti jeftinu ulaznicu u stalne popravke.
TEXT,
                'highlights' => [
                    'Euro 3 gradski auto ima smisla samo kada je zdrav lim, miran motor i mala lista ulaganja.',
                    'Gume, akumulator, servis, kočnice, auspuh i registracija mogu poništiti nisku cenu oglasa.',
                    'Najjeftiniji primerak preskoči ako prodavac ne dozvoljava probnu vožnju, dizalicu ili jasne papire.',
                ],
                'tags' => ['Euro 3', 'gradski auto', 'jeftin polovnjak', 'tržište polovnjaka', 'budžet'],
                'meta_title' => 'Euro 3 gradski auto: kada se isplati',
                'meta_description' => 'Analiza kupovine Euro 3 gradskog auta u Srbiji: niska cena, registracija, limarija, servis, gume, bezbednost i stvarna računica.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#a3e635', '#f8fafc'],
            ],
            [
                'title' => 'Suzuki Ignis ili Opel Agila: mali auto kada visina ne sme da zameni proveru',
                'slug' => 'suzuki-ignis-ili-opel-agila-mali-auto-kada-visina-ne-sme-da-zameni-proveru',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Ignis i Agila nude lak ulazak, preglednost i mali format, ali kupac mora odvojiti praktičnu visinu od stvarnog stanja motora, trapa, lima i delova.',
                'content' => <<<'TEXT'
Maja je tražila mali auto za majku koja teško ulazi u niske gradske modele. Suzuki Ignis i Opel Agila brzo su ušli u uži izbor jer imaju višu kabinu, kratak auto za parking i jednostavan karakter. Na fotografijama oba deluju kao pametna alternativa običnom malom autu, ali kod starijih primeraka visoko sedenje ne sme da sakrije godine, limariju i dostupnost delova.

Ignis ima smisla kada kupac želi nešto robustniji mali auto, bolju preglednost i upotrebljivost na lošijem putu. Ipak, baš zato proveri tragove seoske vožnje, zimu, so, pragove, pod, zadnji deo, trap, kočnice i eventualni 4x4 pogon ako ga primerak ima. Ako prodavac cenu objašnjava retkošću, traži račune, hladan start i pregled odozdo, jer retkost ne plaća koroziju.

Agila je racionalnija kada je cilj jednostavan gradski auto sa lakšim ulaskom i poznatijom servisnom podrškom. Treba proveriti benzinski motor, kvačilo, menjač, klimu, podizače, vrata, zadnju klupu i tragove kratkih relacija. Agila koja je godinama vozila samo grad može spolja izgledati uredno, a da odmah traži gume, trap, akumulator i kočnice.

Odluka ne treba da bude Ignis protiv Agile po imenu, nego koji primerak ima jasniju istoriju i manje početno ulaganje. Ignis je bolji ako stvarno treba viši auto za lošiji put i ako je lim zdrav. Agila je bolja ako kupac želi mirniju gradsku računicu i lakše održavanje. Ako nijedan auto ne može na dizalicu pre kapare, visoko sedenje nije prednost nego skupa distrakcija.
TEXT,
                'highlights' => [
                    'Ignis vredi gledati kada viša kabina dolazi uz zdrav lim, trap i jasnu servisnu istoriju.',
                    'Agila ima smisla kao jednostavan gradski auto ako kvačilo, klima, vrata i trap rade bez ulaganja.',
                    'Kod oba modela dizalica i hladan start vrede više od priče da je auto mali i jeftin za održavanje.',
                ],
                'tags' => ['Suzuki Ignis', 'Opel Agila', 'mali auto', 'gradski auto', 'poređenje'],
                'meta_title' => 'Suzuki Ignis ili Opel Agila: koji mali auto kupiti',
                'meta_description' => 'Poređenje polovnih Suzuki Ignis i Opel Agila modela: visoko sedenje, limarija, trap, motor, klima, delovi, gradska vožnja i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Kia Rio: mali auto koji traži proveru kvačila, trapa i klime',
                'slug' => 'polovni-kia-rio-mali-auto-koji-trazi-proveru-kvacila-trapa-i-klime',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Kia Rio može biti razuman gradski i porodični mali auto, ali dobar primerak mora potvrditi servis, kvačilo, trap, klimu, opremu i realnu cenu.',
                'content' => <<<'TEXT'
Dejan je tražio prvi ozbiljniji auto za ćerku: ne premali, ne skup za održavanje i dovoljno pregledan za grad. Kia Rio mu je delovala kao mirniji izbor od razvikanijih modela jer često nudi dobru opremu za cenu. Prvi obilazak ga je podsetio da korejska reputacija ne zamenjuje pregled konkretnog primerka, posebno kada je auto godinama radio kratke relacije.

Prvo proveri motor, kvačilo i menjač. Rio kao mali benzinac treba da pali hladan bez dugog verglanja, radi ravnomerno, ne trza i ne pokazuje odložene servise. Kvačilo ne sme hvatati previsoko, menjač ne sme zapinjati, a probna vožnja treba da uključi kretanje uzbrdo, sporo manevrisanje i gradsku gužvu. Ako prodavac kaže da je "sve to normalno", traži mišljenje majstora.

Druga provera su trap, kočnice, gume i klima. Mali auto često živi po ivičnjacima, rupama i kratkim rutama, pa amortizeri, spone, ležajevi, diskovi i gume brzo pokažu stvarno stanje. Klima mora hladiti bez čudnih zvukova i mirisa, jer njena popravka kod jeftinog auta lako pojede pregovaračku prednost.

Rio ima smisla kada kupac dobija uredan, jednostavan auto sa servisnom istorijom i cenom koja ne kažnjava manju popularnost modela. Ako je primerak očuvaniji od konkurencije, vredi ga uzeti ozbiljno. Ako nema računa, ako je enterijer umorniji od kilometraže ili ako trap već traži ulaganje, bolji je skuplji i jasniji oglas nego Rio kupljen samo zato što izgleda povoljno.
TEXT,
                'highlights' => [
                    'Kod Rio modela proveri hladan start, kvačilo, menjač i ponašanje u gradskoj vožnji.',
                    'Trap, kočnice, gume i klima često otkrivaju koliko je mali auto stvarno radio.',
                    'Dobar Rio vredi kada stanje nadoknađuje manju popularnost, a cena ostavlja prostor za servis.',
                ],
                'tags' => ['Kia Rio', 'mali auto', 'gradski auto', 'benzinac', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Kia Rio: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Kia Rio modela: motor, kvačilo, menjač, trap, klima, gume, kočnice, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Renault Fluence: limuzina koja mora opravdati nisku cenu',
                'slug' => 'polovni-renault-fluence-limuzina-koja-mora-opravdati-nisku-cenu',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Renault Fluence privlači velikim gepekom i nižom cenom, ali kupac mora proveriti dizel, elektroniku, trap, enterijer i razlog zašto je limuzina povoljna.',
                'content' => <<<'TEXT'
Saša je tražio limuzinu za porodicu i posao, ali nije želeo da plati cenu popularnog karavana ili SUV-a. Renault Fluence mu je privukao pažnju velikim gepekom, udobnim sedištima i oglasima koji deluju povoljnije od nemačkih alternativa. Takav auto može biti razumna kupovina, ali samo ako kupac razume zašto je cena niža i šta mora da proveri pre kapare.

Prva tema je motor i servis. Kod dizela proveri hladan start, dim, turbinu, dizne, DPF ako ga verzija ima, EGR, curenja i račune za ulje. Kod benzinca gledaj miran rad, potrošnju ulja, rashladni sistem i da li je auto održavan redovno, a ne samo pred prodaju. Fluence sa mutnom istorijom nije jeftina limuzina, već nepoznat račun u velikom pakovanju.

Druga provera su elektronika, kabina i trap. Kartica ili ključ, klima, podizači, brave, instrument tabla, senzori i svetla moraju raditi bez izgovora. Veliki gepek i udobnost često znače da je auto služio porodici, putovanjima ili poslu, pa proveri zadnju klupu, prag gepeka, gume, kočnice, amortizere i ležajeve. Ako kabina priča priču o teškom životu, cena mora biti iskrena.

Fluence ima smisla za kupca koji želi prostor i udobnost bez plaćanja tržišne mode. Dobar primerak treba da ima dosledne papire, miran motor i opremu koja radi. Ako je cena niska zato što model nije tražen, to može biti prilika. Ako je niska zato što prodavac preskače servis, dijagnostiku ili probnu vožnju, bolje je odustati pre nego što veliki gepek postane mesto za račune.
TEXT,
                'highlights' => [
                    'Fluence kupuj zbog prostora i stanja, ne samo zato što je povoljniji od traženijih limuzina.',
                    'Dizel traži proveru turbine, dizni, DPF-a, EGR-a, curenja i servisnog ritma.',
                    'Elektronika, kartica, klima, trap i gepek moraju potvrditi da niska cena nije maska za ulaganja.',
                ],
                'tags' => ['Renault Fluence', 'limuzina', 'dizel', 'porodični auto', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Renault Fluence: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Renault Fluence modela: dizel, DPF, EGR, elektronika, kartica, klima, trap, gepek i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Električni podizači stakala na polovnom autu: kada spor prozor otkriva vrata, instalaciju ili udarac',
                'slug' => 'elektricni-podizaci-stakala-na-polovnom-autu-kada-spor-prozor-otkriva-vrata-instalaciju-ili-udarac',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Spor ili bučan podizač stakla nije samo sitna neprijatnost; može otkriti mehanizam, instalaciju, vlagu, skidane tapacirunge ili lošu popravku vrata.',
                'content' => <<<'TEXT'
Luka je na pregledu kompakta skoro ignorisao zadnji prozor koji se spuštao sporo i uz krckanje. Prodavac je rekao da je to sitnica i da se "samo ne koristi često". Na jeftinijem autu to može zvučati nevažno, ali električni podizač stakla često otkriva šta se dešavalo sa vratima, instalacijom, vlagom ili prethodnom limarskom popravkom.

Prva provera je jednostavna: spusti i podigni svako staklo više puta, hladno i posle kratke vožnje. Slušaj krckanje, zatezanje, preskakanje, usporavanje i da li se staklo vraća krivo. Proveri prekidače sa vozačevih vrata i sa svakih pojedinačnih vrata. Ako jedan prekidač radi, a drugi ne, problem može biti u instalaciji, modulu ili samom prekidaču.

Druga provera je veza sa vratima. Pogledaj zazore, šrafove, tapacirung, gumice, tragove skidanja, vlagu na donjoj ivici vrata i da li centralna brava radi uredno. Spor prozor posle zamene stakla ili popravke vrata može značiti loše namešten mehanizam. Ako su istovremeno čudni zazori, druga nijansa laka ili problem sa zvučnikom, više ne gledaš izolovanu sitnicu.

Kupovina nije loša samo zato što podizač radi sporo, ali pregovor mora krenuti od dijagnoze. Jeftin mehanizam je jedna stvar, prelomljena instalacija u vratima ili trag udarca druga. Ako prodavac odbija da se skine tapacirung ili da majstor proveri vrata, sitan prozor postaje signal za oprez. Sitnice na polovnom autu često vrede zato što pokažu gde treba gledati dublje.
TEXT,
                'highlights' => [
                    'Svako staklo testiraj više puta sa glavnog prekidača i sa prekidača na samim vratima.',
                    'Spor prozor poveži sa zazorima, tapacirungom, vlagom, centralnom bravom i tragovima popravke.',
                    'Pregovaraj tek kada znaš da li je problem mehanizam, prekidač, instalacija ili loše popravljena vrata.',
                ],
                'tags' => ['podizači stakala', 'elektronika', 'vrata', 'provera vozila', 'polovan auto'],
                'meta_title' => 'Podizači stakala na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti električne podizače stakala kod polovnog auta: spor prozor, prekidači, instalacija, vrata, vlaga, tapacirung i tragovi udarca.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Hrvatske: kada blizina tržišta ne sme da uspava proveru',
                'slug' => 'uvoz-auta-iz-hrvatske-kada-blizina-trzista-ne-sme-da-uspava-proveru',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Automobil iz Hrvatske može delovati kao lakši regionalni uvoz, ali kupac mora proveriti poreklo, servis, more, so, papire, kilometražu i realnu cenu.',
                'content' => <<<'TEXT'
Marko je našao karavan iz Hrvatske koji je delovao bliže, razumljivije i lakše proverljivo od uvoza iz udaljenijih zemalja. Prodavac je imao lepe fotografije, uredan opis i priču da je auto "iz regiona, pa je sve jednostavno". Blizina zaista može olakšati komunikaciju i proveru, ali ne sme uspavati kupca koji treba da proveri poreklo, kilometražu, servis i stanje podvozja.

Prvo proveri papire. VIN, saobraćajna, servisni računi, tehnički pregledi, odjava, COC ako je potreban i put od vlasnika do prodavca moraju biti jasni. Regionalni jezik ne znači automatski jasnu istoriju. Ako auto ima više vlasnika, flotnu upotrebu ili servisne rupe, to mora biti objašnjeno pre kapare, ne posle dolaska na plac.

Druga tema je klima i podvozje. Automobili sa primorja, ostrva ili područja sa mnogo soli i vlage mogu imati tragove na kočnicama, izduvu, nosačima, vijcima, pragovima i podu. Automobili sa kontinentalnih relacija mogu nositi autoput kilometre, službenu upotrebu ili umor enterijera. Pregled na dizalici i poređenje stanja kabine sa kilometražom su obavezni.

Uvoz iz Hrvatske ima smisla kada cena, papiri i stanje čine proverljivu celinu, a ne kada kupac samo želi bliže tržište. Dobar primerak može biti lakši za proveru od udaljenog uvoza, posebno ako postoje računi i kontakt prethodnog servisa. Ako prodavac koristi blizinu kao zamenu za dokumentaciju, računaj kao da kupuješ bilo koji nejasan uvoz: polako, bez kapare i sa majstorom pre odluke.
TEXT,
                'highlights' => [
                    'Blizina Hrvatske pomaže samo ako VIN, odjava, servisni računi i vlasnički put imaju logiku.',
                    'Primorje, vlaga i so traže pažljiv pregled poda, kočnica, izduva, nosača i pragova.',
                    'Regionalni uvoz kupuj kao proverljiv auto, ne kao lakšu kupovinu samo zbog jezika i udaljenosti.',
                ],
                'tags' => ['uvoz iz Hrvatske', 'uvoz auta', 'polovni automobili', 'poreklo vozila', 'tržište polovnjaka'],
                'meta_title' => 'Uvoz auta iz Hrvatske: šta proveriti',
                'meta_description' => 'Analiza uvoza auta iz Hrvatske: VIN, odjava, servisni računi, kilometraža, primorje, so, podvozje, vlasnici i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Citroen C-Elysee ili Peugeot 301: budžetska limuzina kada prostor nije dovoljan',
                'slug' => 'citroen-c-elysee-ili-peugeot-301-budzetska-limuzina-kada-prostor-nije-dovoljan',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C-Elysee i Peugeot 301 nude veliki gepek za mali budžet, ali kupac mora proveriti poreklo, motor, trap, kabinu i razlog niske cene.',
                'content' => <<<'TEXT'
Milan je tražio jeftinu limuzinu za posao i vikend putovanja, pa su mu Citroen C-Elysee i Peugeot 301 delovali kao isti odgovor sa dva znaka. Na fotografijama oba nude mnogo auta za novac: veliki gepek, jednostavnu kabinu i cenu koja ne ulazi u teritoriju traženijih kompaktnih karavana. Problem je što takvi automobili često privuku kupca prostorom, a tek na pregledu pokažu koliko su radili kao službeni, taksi ili porodični auto sa odloženim servisima.

C-Elysee ima smisla kada želiš jednostavan auto i ne očekuješ premium osećaj. Proveri hladan start, curenja, kvačilo, menjač, trap, zadnju klupu, gepek i tragove jeftinih popravki. Dizel može biti štedljiv na otvorenom putu, ali bez servisnih računa za ulje, filtere, EGR i eventualni DPF ne treba verovati samo mirnom radu na parkingu. Benzinac je često mirniji izbor za grad, ali i on mora pokazati redovno održavanje.

Peugeot 301 je slična priča, ali konkretan primerak odlučuje više od znaka. Ako je kabina istrošena, prag gepeka izgreban, zadnji trap umoran ili su gume različite, auto verovatno nije imao lak život. Kupac treba da uporedi cenu sa realnim ulaganjima: kočnice, gume, veliki servis, klima i osnovna elektronika mogu brzo pojesti prednost jeftine kupovine. Niska cena je dobra samo ako znaš zašto je niska.

Između C-Elysee i 301 ne treba birati po znački, nego po dokazima. Bolji je auto sa jasnom istorijom, ujednačenim stanjem i cenom koja ostavlja prostor za početni servis. Ako prodavac izbegava probnu vožnju, nema račune ili pokušava da proda veliki gepek kao zamenu za dokumentaciju, nastavi dalje. Ove limuzine vrede kada kupuješ pošten prostor, ne kada kupuješ najjeftiniju priču u oglasima.
TEXT,
                'highlights' => [
                    'C-Elysee i 301 poredi po stanju, poreklu i servisnim računima, ne po znaku na haubi.',
                    'Veliki gepek često krije službenu, taksi ili intenzivnu porodičnu upotrebu.',
                    'Dobra kupovina postoji samo ako niska cena ostavlja prostor za početni servis i realna ulaganja.',
                ],
                'tags' => ['Citroen C-Elysee', 'Peugeot 301', 'limuzina', 'budžetski auto', 'poređenje'],
                'meta_title' => 'Citroen C-Elysee ili Peugeot 301: polovna limuzina',
                'meta_description' => 'Poređenje polovnih Citroen C-Elysee i Peugeot 301 limuzina: gepek, dizel, benzinac, trap, servisna istorija, službena upotreba i cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Škoda Yeti: kutijasti SUV koji mora dokazati pogon i rđu',
                'slug' => 'polovni-skoda-yeti-kutijasti-suv-koji-mora-dokazati-pogon-i-rdju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Škoda Yeti je praktičan i pregledan polovnjak, ali dobar primerak traži proveru TSI-a, TDI-a, DSG-a, 4x4 pogona, trapa, korozije i enterijera.',
                'content' => <<<'TEXT'
Ivana je tražila viši auto za vikendicu i grad, ali nije želela veliki SUV. Škoda Yeti joj je delovala taman: kratak, pregledan, kutijast i praktičan. Prvi oglas je imao lepe fotografije i bogatu opremu, ali je pregled brzo pokazao da Yeti može imati dva života, jedan uredan porodični i jedan mnogo teži, sa lošim putevima, zimom, vučom i odloženim održavanjem.

Prvo proveri motor i menjač. Kod TSI benzinca traži miran hladan start, račune za servis i objašnjenje potrošnje ulja ako postoji. Kod TDI dizela proveri DPF, EGR, turbinu, dizne i da li je auto stvarno vožen na relacijama koje dizel voli. Ako ima DSG, servis ulja i probna vožnja u gradu nisu opcija nego uslov. Kratko okretanje oko placa ne otkriva dovoljno.

Druga tema su pogon, trap i korozija. Yeti sa 4x4 pogonom mora pokazati da zadnji pogon radi, da nema udaraca, curenja i preskočenih servisa. Pregled na dizalici treba da obuhvati pragove, pod, rubove, nosače, izduv, kočnice, amortizere i gume. Kutijasta karoserija je praktična, ali ne sme sakriti loše popravljene ivice, vlagu u gepeku ili tragove korišćenja van asfalta.

Dobar Yeti ima smisla za kupca koji želi pregledan, upotrebljiv auto sa više karaktera od običnog hečbeka. Loš Yeti postaje skup kada se spoje zapušten dizel, umoran DSG, slab trap i rđa. Ako je primerak uredan, papiri jasni i cena ne glumi noviji SUV, vredi ga ozbiljno pogledati. Ako prodavac prodaje samo reputaciju Škode i povišeno sedenje, pregovaraj tvrdo ili odustani.
TEXT,
                'highlights' => [
                    'Kod Yetija proveri TSI ili TDI motor, DSG servis i ponašanje u gradskoj probnoj vožnji.',
                    '4x4 pogon, trap, pragovi, pod, izduv i kočnice moraju proći pregled na dizalici.',
                    'Yeti je dobra kupovina samo kada praktičnost prati jasna istorija, a ne samo visoko sedenje.',
                ],
                'tags' => ['Škoda Yeti', 'polovni SUV', '4x4', 'DSG', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Škoda Yeti: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Škoda Yeti modela: TSI, TDI, DSG, 4x4 pogon, trap, korozija, podvozje, oprema i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#84cc16', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Honda Insight: hibrid koji mora opravdati retkost i bateriju',
                'slug' => 'polovni-honda-insight-hibrid-koji-mora-opravdati-retkost-i-bateriju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Honda Insight može biti štedljiv gradski hibrid, ali kupac mora proveriti bateriju, IMA sistem, CVT, kočnice, klimu, delove i razlog zašto je primerak redak.',
                'content' => <<<'TEXT'
Nikola je hteo hibrid, ali su mu popularne Toyote bile preskupe za stanje koje je nalazio. Honda Insight se pojavila kao zanimljiva alternativa: niža cena, štedljiva vožnja i manje oglasa koji privlače masu kupaca. Upravo ta retkost traži hladniju glavu, jer polovan Insight nije dobar samo zato što je hibrid, nego zato što konkretan primerak ima zdrav sistem, jasne račune i dostupnu servisnu podršku.

Prva provera je hibridni sistem. Dijagnostika mora pokazati stanje IMA baterije, greške, punjenje i ponašanje tokom vožnje. Obrati pažnju na prelaze između benzinskog motora i asistencije, rad start-stop sistema, lampice i ponašanje pri ubrzanju. Slaba 12V baterija, zapuštena klima ili greške koje se brišu pred prodaju mogu napraviti lažan utisak da je problem mali.

Druga tema je CVT, kočnice i svakodnevna upotreba. Insight treba voziti hladan i zagrejan, u gužvi, pri parkiranju i na otvorenom putu. Menjač ne sme trzati, zavijati neobično ili kasniti. Kočnice kod hibrida mogu delovati dobro na kratkoj vožnji, ali proveri diskove, čeljusti i neravnomerno trošenje. Enterijer, gepek i zadnja klupa često otkrivaju da li je auto bio porodičan, službeni ili zapušten zbog niske potrošnje.

Insight ima smisla kada želiš miran gradski hibrid i prihvataš da izbor delova i majstora nije širok kao kod popularnijih modela. Ako je dijagnostika čista, baterija stabilna, CVT uredan i cena priznaje retkost, kupovina može biti dobra. Ako prodavac računa samo na reč "hibrid" i ne dozvoljava detaljnu proveru, bolje je platiti skuplji, jasniji auto nego retku štednju sa nepoznatim računom.
TEXT,
                'highlights' => [
                    'Insight traži dijagnostiku IMA baterije, grešaka, punjenja i ponašanja hibridnog sistema.',
                    'CVT, kočnice, klima i 12V baterija moraju se proveriti hladni, topli i u gradskoj vožnji.',
                    'Retkost je prihvatljiva samo ako cena i servisna podrška prate realno stanje primerka.',
                ],
                'tags' => ['Honda Insight', 'polovni hibrid', 'IMA baterija', 'CVT', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Honda Insight: šta proveriti kod hibrida',
                'meta_description' => 'Vodič za kupovinu polovnog Honda Insight hibrida: IMA baterija, dijagnostika, CVT menjač, kočnice, klima, 12V baterija i delovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Električni retrovizori na polovnom autu: kada malo staklo otkriva vrata, instalaciju ili udarac',
                'slug' => 'elektricni-retrovizori-na-polovnom-autu-kada-malo-staklo-otkriva-vrata-instalaciju-ili-udarac',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Retrovizor koji se ne sklapa, ne greje ili ne podešava nije samo sitnica; može otkriti udarac, lošu instalaciju, modul vrata ili skupu opremu.',
                'content' => <<<'TEXT'
Petar je skoro prešao preko desnog retrovizora koji se sklapao sporije od levog. Prodavac je rekao da je to samo prljavština i da se "retko koristi". Na polovnom autu takva sitnica vredi proveru, jer retrovizor stoji na mestu koje prvo strada na uskim parking mestima, pri bočnom udarcu, lošoj popravci vrata ili naknadnom sklapanju polovnog dela.

Prvo testiraj sve funkcije. Podesi staklo u svim smerovima, uključi grejanje, proveri električno sklapanje ako postoji, memoriju sedišta ako je povezana i signalizaciju u kućištu retrovizora. Slušaj preskakanje, zujanje, spor rad i gledaj da li oba retrovizora rade istom brzinom. Ako jedan radi samo iz određenog položaja prekidača, problem može biti instalacija, prekidač ili modul vrata.

Druga provera je trag udarca. Pogledaj kućište, boju, zazor prema vratima, šrafove, gumice, tapacirung i da li staklo ima oznake koje se slažu sa ostatkom auta. Retrovizor druge nijanse nije automatski razlog za odustajanje, ali mora imati objašnjenje. Ako uz njega vidiš lakirana vrata, loš zazor, problem sa podizačem ili grešku mrtvog ugla, više ne proveravaš samo mali deo.

Kupovina nije loša zato što retrovizor traži popravku, ali cena mora priznati tačan kvar. Obično staklo i motor nisu isti trošak kao sklopivi, grejani ili blind-spot retrovizor sa kamerom. Ako prodavac odbija da se kvar dijagnostikuje ili ga gura pod "sitnice", zapiši ga kao pregovaračku stavku. Mali retrovizor često pokaže koliko je auto pažljivo popravljan.
TEXT,
                'highlights' => [
                    'Testiraj podešavanje, grejanje, sklapanje, žmigavac, memoriju i senzore na oba retrovizora.',
                    'Retrovizor poveži sa zazorima vrata, tapacirungom, bojom, prekidačima i mogućim bočnim udarcem.',
                    'Cena popravke zavisi od opreme, pa običan kvar i blind-spot retrovizor nisu ista stavka.',
                ],
                'tags' => ['električni retrovizori', 'provera vozila', 'vrata', 'elektronika', 'polovan auto'],
                'meta_title' => 'Električni retrovizori na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti električne retrovizore kod polovnog auta: podešavanje, grejanje, sklapanje, blind spot, instalacija, vrata, udarac i cena popravke.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Rumunije: kada dobra cena traži proveru porekla, puteva i papira',
                'slug' => 'uvoz-auta-iz-rumunije-kada-dobra-cena-trazi-proveru-porekla-puteva-i-papira',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Rumunski uvoz može delovati povoljno, ali kupac mora proveriti VIN, servis, vlasnički put, kilometražu, podvozje, taksi tragove i realnu uvoznu računicu.',
                'content' => <<<'TEXT'
Aleksandar je našao karavan iz Rumunije koji je delovao povoljnije od sličnih automobila iz zapadne Evrope. Prodavac je naglašavao da je auto iz EU, da su papiri "standardni" i da se isti model kod nas prodaje skuplje. Takva ponuda može biti dobra, ali samo ako kupac ne preskoči pitanje zašto je cena niža i da li poreklo, kilometraža i stanje podvozja prate priču iz oglasa.

Prvo proveri dokumentaciju. VIN, saobraćajna, odjava, servisni računi, tehnički pregledi, COC ako je potreban i veza između prethodnog vlasnika, izvoznika i domaćeg prodavca moraju biti jasni. Posebno obrati pažnju na automobile koji su mogli raditi kao taksi, službena vozila ili flotni automobili. Jezik i udaljenost ne smeju biti izgovor za maglovite papire.

Druga tema su putevi, podvozje i karoserija. Loši putevi, zimski uslovi, gradska eksploatacija i brze pripreme za prodaju mogu ostaviti trag na trapu, amortizerima, gumama, kočnicama, pragovima i podu. Pregled na dizalici i merenje laka treba uraditi pre kapare. Ako enterijer deluje umornije od kilometraže, ako su volan i sedište izlizani ili ako su gume različite, računaj da broj na satu nije cela istina.

Uvoz iz Rumunije ima smisla kada niža cena ostane niža i posle provere, transporta, carinskih troškova, homologacije, registracije i početnog servisa. Ako papiri imaju logiku, stanje je proverljivo i prodavac prihvata pregled, kupovina može biti racionalna. Ako se cela priča oslanja na "dobra je cena", kupac treba da uspori, jer jeftin uvoz postaje skup čim prvi servis otkrije ono što papiri nisu rekli.
TEXT,
                'highlights' => [
                    'Rumunski uvoz proveri kroz VIN, odjavu, servisne račune, tehničke preglede i vlasnički put.',
                    'Taksi, flotna upotreba, loši putevi i zima često ostavljaju trag na kabini, trapu i podvozju.',
                    'Dobra cena ima smisla tek posle transporta, dažbina, registracije, homologacije i početnog servisa.',
                ],
                'tags' => ['uvoz iz Rumunije', 'uvoz auta', 'polovni automobili', 'poreklo vozila', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Rumunije: šta proveriti',
                'meta_description' => 'Analiza uvoza auta iz Rumunije: VIN, odjava, servisni računi, kilometraža, taksi tragovi, podvozje, carinski troškovi i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Citroen C4 Cactus ili Ford EcoSport: crossover kada stil ne sme da sakrije stanje',
                'slug' => 'citroen-c4-cactus-ili-ford-ecosport-crossover-kada-stil-ne-sme-da-sakrije-stanje',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'C4 Cactus i EcoSport privlače kupce koji žele drugačiji crossover, ali odluka mora krenuti od motora, trapa, kabine, porekla i realne cene.',
                'content' => <<<'TEXT'
Jelena je tražila povišen auto za grad, ali nije želela još jedan isti crossover sa oglasa. Citroen C4 Cactus joj je delovao mekano i posebno, dok je Ford EcoSport nudio viši položaj i robusniji utisak. Oba auta umeju da privuku izgledom, ali kod polovnjaka stil je samo početak razgovora. Prava odluka nastaje kada se vidi motor, trap, istorija i koliko je prodavac spreman da objasni konkretan primerak.

C4 Cactus ima smisla za kupca koji želi udobniji, lakši i opušteniji auto. Proveri PureTech ili dizel motor kroz hladan start, servisne račune, potrošnju ulja, remen ako je relevantan, EGR, DPF i rad klime. Kabina mora pokazati da su sedišta, multimedija, vrata i plastični elementi izdržali svakodnevicu. Ako prodavac prodaje samo neobičan dizajn, a nema račune, prednost brzo nestaje.

EcoSport treba gledati kao mali crossover koji često nosi više gradskog i rubnog života nego što fotografije pokažu. Proveri benzinski ili dizel motor, kvačilo, menjač, zadnji trap, gume, pragove, gepek vrata i tragove parking udaraca. Više sedenje ne znači automatski bolji porodični auto, posebno ako je kabina uska za tvoju namenu ili ako je auto bio kupljen samo zbog izgleda SUV-a.

Između C4 Cactusa i EcoSporta bolji je primerak koji ima jasne papire, miran motor i cenu koja priznaje tržišnu nišu. Cactus je zanimljiv kada udobnost i niža masa stvarno odgovaraju vožnji. EcoSport ima smisla kada ti odgovara položaj sedenja i stanje je bolje od proseka. Ako jedan od njih traži da oprostiš lošu istoriju zbog stila, nastavi potragu.
TEXT,
                'highlights' => [
                    'C4 Cactus i EcoSport poredi po stanju i računima, ne po tome koji deluje originalnije.',
                    'Kod Cactusa posebno proveri motor, klimu, kabinu i da li dizajn krije odložene servise.',
                    'Kod EcoSporta gledaj trap, gepek vrata, gume, parking tragove i realnu upotrebljivost kabine.',
                ],
                'tags' => ['Citroen C4 Cactus', 'Ford EcoSport', 'crossover', 'poređenje', 'kupovina polovnjaka'],
                'meta_title' => 'Citroen C4 Cactus ili Ford EcoSport: polovni crossover',
                'meta_description' => 'Poređenje polovnih Citroen C4 Cactus i Ford EcoSport modela: motor, trap, klima, kabina, gepek vrata, poreklo i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Seat Exeo: limuzina koja mora opravdati Audi korene i godine',
                'slug' => 'polovni-seat-exeo-limuzina-koja-mora-opravdati-audi-korene-i-godine',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Seat Exeo može delovati kao povoljan Audi A4 u drugom odelu, ali kupac mora proveriti dizel, trap, koroziju, enterijer, delove i stvarne godine.',
                'content' => <<<'TEXT'
Miloš je želeo ozbiljnu limuzinu za autoput, ali nije hteo da plati cenu popularnog premium znaka. Seat Exeo mu je delovao kao skrivena prilika: poznata tehnička osnova, dobra oprema i manje interesovanja kupaca. Upravo tu počinje oprez. Exeo nije dobar zato što liči na racionalniji Audi, nego samo ako konkretan auto ima papire, stanje i cenu koje priznaju godine.

Prvo proveri motor i servis. Dizel mora hladno da pali mirno, bez dima, curenja, grubog rada i izgovora oko turbine, dizni, EGR-a ili DPF-a. Benzinac traži proveru potrošnje ulja, rashladnog sistema i redovnog održavanja. Veliki servis, ulje, filteri, kvačilo i eventualni plivajući zamajac moraju biti deo razgovora pre kapare, ne iznenađenje posle kupovine.

Druga provera su trap, karoserija i kabina. Exeo je često vožen kao službeni ili putnički auto, pa uporedi kilometražu sa sedištem, volanom, pedalama, pragom gepeka i gumama. Na dizalici gledaj ramena, amortizere, kočnice, pod, pragove, izduv i tragove korozije. Ako enterijer deluje umornije od oglasa, nemoj da te dobra oprema ubedi da preskočiš pregled.

Polovni Exeo ima smisla za kupca koji želi komfornu limuzinu za razuman novac i prihvata da godine donose ulaganja. Dobar primerak treba da bude jeftiniji od traženijih alternativa, ali ne toliko jeftin da krije servisni dug. Ako prodavac stalno ponavlja Audi korene, a nema račune i ne dozvoljava proveru, to nije argument nego signal da pregovor treba završiti.
TEXT,
                'highlights' => [
                    'Exeo kupuj po servisnoj istoriji i stanju, ne samo zbog poznate tehničke osnove.',
                    'Dizel traži proveru turbine, dizni, EGR-a, DPF-a, kvačila i velikog servisa.',
                    'Trap, kabina, pragovi, gepek i korozija moraju potvrditi da godine nisu pojele nisku cenu.',
                ],
                'tags' => ['Seat Exeo', 'limuzina', 'dizel', 'Audi osnova', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Seat Exeo: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Seat Exeo modela: dizel, EGR, DPF, turbo, trap, korozija, enterijer, servisna istorija i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Hyundai Elantra: limuzina koja traži proveru uvoza, trapa i klime',
                'slug' => 'polovni-hyundai-elantra-limuzina-koja-trazi-proveru-uvoza-trapa-i-klime',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Hyundai Elantra privlači prostorom i mirnom reputacijom, ali dobar primerak traži proveru porekla, benzinca, trapa, klime, kabine i dostupnosti delova.',
                'content' => <<<'TEXT'
Ana je tražila porodičnu limuzinu koja nije preskupa zato što nosi popularan evropski znak. Hyundai Elantra joj je delovala mirno: prostrana, nenametljiva i često povoljnija od traženijih kompaktnih karavana. Takav auto može biti pametna kupovina, ali samo ako kupac proveri poreklo, servis i razlog zašto konkretan primerak stoji u oglasu po toj ceni.

Prvo gledaj dokumentaciju i motor. Elantra često dolazi iz uvoza, pa VIN, servisni računi, tehnički pregledi i vlasnički put moraju imati logiku. Benzinski motor treba da radi mirno, bez curenja, čudnog zvuka, pregrevanja i potrošnje ulja koju prodavac umanjuje. Ako postoji automatik, probna vožnja u gradu mora pokazati glatko prebacivanje i normalno ponašanje pri parkiranju.

Druga tema su trap, klima i kabina. Limuzina sa velikim gepekom često je služila porodici, poslu ili dužim relacijama. Proveri amortizere, kočnice, gume, zadnji trap, prag gepeka, zadnju klupu, klimu, multimediju, brave i podizače. Ako klima hladi slabo ili trap lupa preko neravnina, to nisu sitnice koje treba prihvatiti zato što je auto "japanac u duši" ili zato što ima dobru reputaciju.

Elantra ima smisla kada želiš jednostavniji, prostran auto i kada cena ostavlja prostor za početni servis. Dobar primerak treba da bude dosadan na najbolji način: miran motor, čista kabina, jasni papiri i pregled bez velikih pitanja. Ako prodavac nema odgovor na poreklo, održavanje ili dostupnost delova, povoljna limuzina može postati kupovina koju je teško prodati dalje.
TEXT,
                'highlights' => [
                    'Kod Elantre prvo proveri VIN, uvoznu istoriju, servisne račune i logiku kilometraže.',
                    'Benzinski motor, automatik, trap i klima moraju biti provereni u stvarnoj probnoj vožnji.',
                    'Veliki gepek i niža cena imaju smisla samo ako delovi, stanje i kasnija prodaja prate računicu.',
                ],
                'tags' => ['Hyundai Elantra', 'limuzina', 'benzinac', 'uvoz auta', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Hyundai Elantra: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Hyundai Elantra modela: uvoz, VIN, benzinac, automatik, trap, klima, kabina, delovi i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#22d3ee', '#f8fafc'],
            ],
            [
                'title' => 'Tempomat na polovnom autu: kada dugme otkriva elektroniku, kočnice ili udarac',
                'slug' => 'tempomat-na-polovnom-autu-kada-dugme-otkriva-elektroniku-kocnice-ili-udarac',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Tempomat koji ne radi nije samo komforna sitnica; može ukazati na prekidače kočnice, volan, instalaciju, radar, branik, modul ili skrivenu popravku.',
                'content' => <<<'TEXT'
Dejan je gledao karavan za autoput i tek na kraju probne vožnje primetio da tempomat ne prihvata komandu. Prodavac je odmah rekao da se to "nikad ne koristi" i da je verovatno dugme. Kod polovnog auta tempomat nije presudan za svakog kupca, ali kvar na njemu može pokazati problem sa kočnicama, volanom, instalacijom, branikom ili prethodnim udarcem.

Prva provera je probna vožnja na putu gde sme bezbedno da se aktivira. Uključi sistem, podesi brzinu, probaj povećanje i smanjenje, otkazivanje preko kočnice i kvačila, pa ponovno vraćanje brzine. Ako lampica radi, ali sistem ne drži brzinu, problem može biti prekidač kočnice, kvačila, senzor brzine ili greška u modulu. Ako dugmad na volanu rade povremeno, gledaj i spiralu volana ili instalaciju.

Druga provera zavisi od opreme. Običan tempomat nije isto što i adaptivni tempomat sa radarom u braniku. Kod adaptivnog sistema proveri radar, nosač, branik, masku, kalibraciju, greške na dijagnostici i tragove prednjeg udarca. Ako je branik sveže farban, zazori nisu isti ili sistem javlja grešku posle pranja, moguće je da popravka nije završena kako treba.

Kvar tempomata ne mora zaustaviti kupovinu, ali mora promeniti pregovor. Jeftin prekidač i skupa kalibracija radara nisu ista stavka. Ako prodavac ne dozvoljava dijagnostiku ili tvrdi da oprema nije važna, a cenu drži kao da sve radi, kupac treba da bude stroži. Komforna funkcija često otkrije koliko su elektronika i popravke zaista uredne.
TEXT,
                'highlights' => [
                    'Tempomat testiraj u vožnji kroz aktiviranje, promenu brzine, otkazivanje i ponovno vraćanje.',
                    'Običan kvar dugmeta nije isti rizik kao adaptivni tempomat sa radarom, branikom i kalibracijom.',
                    'Dijagnostika treba da pokaže prekidače kočnice i kvačila, module, volan, senzore i greške radara.',
                ],
                'tags' => ['tempomat', 'adaptivni tempomat', 'elektronika', 'provera vozila', 'polovan auto'],
                'meta_title' => 'Tempomat na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti tempomat kod polovnog auta: dugmad, prekidač kočnice, kvačilo, volan, dijagnostika, radar, branik, kalibracija i udarac.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Norveške: kada niska kilometraža traži proveru hladnoće, soli i porekla',
                'slug' => 'uvoz-auta-iz-norveske-kada-niska-kilometraza-trazi-proveru-hladnoce-soli-i-porekla',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Norveški uvoz može doneti dobru opremu i nižu kilometražu, ali kupac mora proveriti koroziju, bateriju, grejanje, papire, poreze i stvarnu računicu.',
                'content' => <<<'TEXT'
Vladimir je našao oglas za auto iz Norveške sa bogatom opremom i kilometražom koja je delovala privlačno za godište. Prodavac je naglašavao urednu zemlju porekla, dobru infrastrukturu i činjenicu da su automobili često dobro opremljeni. Sve to može biti tačno, ali norveški uvoz traži posebnu proveru hladnoće, soli, poreza, papira i sistema koji kod nas mogu biti skuplji za rešavanje.

Prvo proveri dokumentaciju i računicu. VIN, odjava, vlasnički put, servisni računi, tehnički pregledi, porezi, transport, homologacija i registracija moraju biti jasni pre kapare. Norveška nije EU članica, pa kupac ne sme da računa kao da uvozi iz susedne EU zemlje. Ako prodavac ne zna da objasni troškove i papire, niska kilometraža nije dovoljna uteha.

Druga tema je klima u kojoj je auto živeo. Hladnoća, so, vlaga i zimska upotreba mogu ostaviti trag na podu, pragovima, kočnicama, izduvu, vijcima, nosačima i električnim konektorima. Kod električnih i hibridnih modela proveri bateriju, grejanje kabine, toplotnu pumpu ako postoji, punjenje, kablove i servisnu podršku. Kod dizela gledaj relacije, grejanje motora, DPF, EGR i da li je auto radio mnogo kratkih vožnji.

Uvoz iz Norveške ima smisla kada se dobra oprema, kilometraža i stanje potvrde pregledom na dizalici, dijagnostikom i jasnim papirima. Ako je auto zaista uredan, može biti zanimljiviji od prosečnog zapadnoevropskog primerka. Ako se priča oslanja na zemlju porekla, a podvozje, baterija ili dokumenti ostaju magloviti, bolje je platiti bliži i proverljiviji auto.
TEXT,
                'highlights' => [
                    'Norveški uvoz proveri kroz VIN, odjavu, poreze, transport, homologaciju i registraciju pre kapare.',
                    'Hladnoća, so i vlaga traže pregled poda, pragova, kočnica, izduva, konektora i nosača.',
                    'Kod hibrida i električnih auta proveri bateriju, grejanje, punjenje, kablove i lokalnu servisnu podršku.',
                ],
                'tags' => ['uvoz iz Norveške', 'uvoz auta', 'polovni automobili', 'korozija', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Norveške: šta proveriti',
                'meta_description' => 'Analiza uvoza auta iz Norveške: VIN, odjava, porezi, transport, korozija, so, baterija, grejanje, punjenje i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Renault Modus ili Nissan Tiida: zaboravljeni polovnjaci kada cena ne sme sama da odluči',
                'slug' => 'renault-modus-ili-nissan-tiida-zaboravljeni-polovnjaci-kada-cena-ne-sme-sama-da-odluci',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Modus i Tiida privuku kupce niskom cenom i praktičnošću, ali prava odluka zavisi od prostora, delova, stanja kabine i toga koliko lako ćeš auto kasnije prodati.',
                'content' => <<<'TEXT'
Miloš je tražio jeftin auto za posao, pijacu i povremeni put do roditelja, pa su mu u uži izbor ušli Renault Modus i Nissan Tiida jer su oba koštala manje od popularnijih modela istog godišta. Modus mu je delovao praktično i pregledno, dok je Tiida nudila veći auto za sličan novac. Prodavci su oba automobila opisivali kao "potcenjene", ali kod potcenjenih polovnjaka kupac mora hladno da odvoji stvarnu vrednost od slabije potražnje.

Modus ima smisla kada se kupuje gradski auto sa visokim sedenjem, dobrim pregledom i kabinom koja lakše prima starije putnike ili dečje sedište. Pre odluke proveri elektroniku, podizače, klimu, trap, tragove vlage i dostupnost sitnih delova enterijera. Ako je auto bio drugi porodični automobil, kratke relacije mogu značiti umoran akumulator, slab auspuh, zapuštene kočnice i servis koji je rađen tek kada nešto prestane da radi.

Tiida je bolja kada kupac želi više prostora, mirniji karakter i jednostavniju svakodnevicu, ali baš zato treba proveriti da li niska cena krije ređu ponudu delova, slabiju kasniju prodaju ili auto koji je dugo stajao. Pogledaj zadnji trap, kvačilo, klimu, gumene delove, koroziju na donjim zonama i da li servisna istorija ima logiku. Prostran auto nije dobar dogovor ako svaka sitnica mora da se traži danima.

Između Modusa i Tiide ne pobeđuje model koji je jeftiniji u oglasu, nego primerak koji ima jasnije papire, uredniji enterijer i manje početnih ulaganja. Modus je razumniji za grad i lakše parkiranje, Tiida za kupca kome stvarno treba više prostora. Ako prodavac ne ume da objasni održavanje, dostupnost delova i razlog niske cene, bolje je nastaviti potragu nego kupiti "potcenjen" auto koji kasnije niko ne želi da preuzme.
TEXT,
                'highlights' => [
                    'Modus je bolji za grad, preglednost i lak ulazak, ali traži proveru elektronike, vlage i trapa.',
                    'Tiida nudi više prostora, ali kupac mora računati dostupnost delova i slabiju kasniju prodaju.',
                    'Kod oba modela niska cena ima smisla samo uz jasnu istoriju i realnu listu početnih ulaganja.',
                ],
                'tags' => ['Renault Modus', 'Nissan Tiida', 'mali auto', 'budžet', 'poređenje'],
                'meta_title' => 'Renault Modus ili Nissan Tiida: polovni vodič',
                'meta_description' => 'Poređenje polovnih Renault Modus i Nissan Tiida modela: prostor, delovi, trap, klima, elektronika, kasnija prodaja i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Toyota Corolla Verso: porodični auto koji mora opravdati sedišta i dizel',
                'slug' => 'polovni-toyota-corolla-verso-porodicni-auto-koji-mora-opravdati-sedista-i-dizel',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Corolla Verso deluje kao miran porodični izbor, ali kupac mora proveriti sedišta, zadnju klupu, dizel motor, klimu, trap i koliko je auto stvarno služio porodici.',
                'content' => <<<'TEXT'
Ivana je tražila porodični auto koji nije SUV, a Toyota Corolla Verso joj je delovala kao logičan odgovor: sedam sedišta, poznato ime i cena niža od popularnijih monovolumena. Na prvom oglasu sve je izgledalo uredno dok nije otvorila zadnji red i videla pohabane kopče, izgrebane plastike i gepek koji je očigledno nosio više od dečjih torbi. Kod Versa porodična praktičnost vredi samo ako je kabina preživela godine bez grubog rada.

Prvo proveri sedišta, šine, preklapanje, pojaseve, ISOFIX, zadnja vrata, klimu za zadnji deo kabine i tragove vlage u gepeku. Auto sa sedam sedišta često je vozio decu, prtljag, vikend selidbe i kratke gradske relacije. Ako se sedišta teško pomeraju, plastike krckaju, a klima slabo hladi, ne prihvataj priču da su to samo godine jer svaka sitnica postaje deo početnog budžeta.

Dizel motor mora imati uredan servisni trag, hladan start bez dima, normalan rad turbine i jasnu priču o relacijama. Corolla Verso se često kupovala zbog pouzdanosti, pa su neki vlasnici predugo odlagali ulaganja misleći da "Toyota trpi sve". Proveri EGR, kvačilo, plivajući zamajac ako ga ima, curenja, kočnice, zadnji trap i gume, jer porodični auto pod opterećenjem troši delove tiše nego što prodavac priznaje.

Corolla Verso ima smisla kada kupac želi praktičan, nenametljiv auto i kada stanje kabine potvrđuje da je održavanje bilo uredno. Ako su sedišta ispravna, papiri jasni i pregled ne otkrije velika ulaganja, može biti bolja odluka od starijeg SUV-a kupljenog zbog mode. Ako prodavac cenu drži samo na osnovu Toyotinog imena, a zanemaruje kabinu, dizel i trap, pregovaraj o stvarnim troškovima ili odustani.
TEXT,
                'highlights' => [
                    'Kod Corolla Verso modela proveri svih sedam sedišta, šine, pojaseve, ISOFIX i tragove vlage.',
                    'Dizel traži hladan start, servisne račune, proveru EGR-a, turbine, kvačila i relacija.',
                    'Toyotina reputacija vredi samo ako kabina, trap i početna ulaganja prate traženu cenu.',
                ],
                'tags' => ['Toyota Corolla Verso', 'porodični auto', 'sedam sedišta', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Toyota Corolla Verso: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Toyota Corolla Verso modela: sedam sedišta, dizel, EGR, kvačilo, klima, trap, kabina i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Chevrolet Captiva: veliki SUV koji mora dokazati pogon, delove i servis',
                'slug' => 'polovni-chevrolet-captiva-veliki-suv-koji-mora-dokazati-pogon-delove-i-servis',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Captiva privlači prostorom i cenom velikog SUV-a, ali kupac mora proveriti pogon, automatik, dizel, delove, koroziju i realnu cenu održavanja.',
                'content' => <<<'TEXT'
Nikola je želeo veliki SUV za porodicu i pecanje, a Chevrolet Captiva mu je delovala kao mnogo automobila za novac koji bi kod poznatijih marki kupio manji crossover. Prvi utisak je bio dobar: visok položaj, mnogo prostora i oprema koja zvuči bogato. Problem je što kod Captive cena često izgleda povoljno baš zato što tržište već uračunava skuplji pogon, težu nabavku nekih delova i slabiju kasniju prodaju.

Prvo proveri pogon, menjač i dizel motor u realnoj vožnji, ne samo na parkingu. Obrati pažnju na trzaje automatika, kašnjenje pri ubacivanju u D i R, zvukove iz prenosa, curenja, dim, hladan start, EGR, DPF i turbinu. Ako auto ima pogon na sva četiri točka, pregled mora obuhvatiti kardane, diferencijale, nosače, gume istih dimenzija i tragove korišćenja van asfalta.

Druga tema su delovi, korozija i kabina. Captiva je veliki, težak auto, pa kočnice, trap, amortizeri, gume i ležajevi rade više nego kod kompakta. Proveri pragove, pod, zadnji kraj, nosače, klimu, elektriku, sedišta i gepek. Ako prodavac kaže da je sve jeftino "jer je to Opel tehnika", traži konkretne cene delova i servis koji zaista radi taj model.

Captiva ima smisla samo kada je znatno bolji primerak od proseka i kada cena ostavlja novac za početni servis. Dobar auto može biti koristan porodični SUV za kupca koji prihvata veće troškove i slabiju likvidnost. Ako kupuješ zato što želiš najjeftiniji veliki SUV, rizik je visok; ako kupuješ provereno stanje sa jasnim računima, Captiva može opravdati prostor koji nudi.
TEXT,
                'highlights' => [
                    'Captiva traži ozbiljnu proveru automatika, 4x4 pogona, dizela, DPF-a, EGR-a i turbine.',
                    'Velika masa znači skuplje gume, kočnice, trap, amortizere i ležajeve nego kod manjih crossovera.',
                    'Pre kupovine proveri dostupnost delova i servis koji stvarno poznaje model, ne samo priču prodavca.',
                ],
                'tags' => ['Chevrolet Captiva', 'veliki SUV', '4x4', 'automatik', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Chevrolet Captiva: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Chevrolet Captiva SUV-a: dizel, automatik, 4x4 pogon, delovi, korozija, trap, gume i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#f97316', '#f8fafc'],
            ],
            [
                'title' => 'Sigurnosni pojasevi na polovnom autu: kada spor povratak otkriva udarac ili vlagu',
                'slug' => 'sigurnosni-pojasevi-na-polovnom-autu-kada-spor-povratak-otkriva-udarac-ili-vlagu',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Pojas koji se sporo vraća, ima fleke, drugačiju etiketu ili zatezač sa greškom može otkriti vlagu, lošu popravku, airbag intervenciju ili ozbiljniji udarac.',
                'content' => <<<'TEXT'
Sara je gledala mali gradski auto za ćerku i sve je delovalo uredno dok su se zadnji pojasevi jedva vraćali u stub. Prodavac je rekao da je auto samo dugo stajao i da pojasevi "uvek tako rade". Kod polovnog auta sigurnosni pojas nije detalj za kasnije sređivanje; on može pokazati vlagu, prljavštinu, zapuštenu kabinu, zamenu posle udesa ili problem sa zatezačima.

Prvo izvuci svaki pojas do kraja i pusti ga da se vrati bez pomoći rukom. Proveri da li se zaglavljuje, da li je traka uvijena, iskrzana, izbledela, flekava ili tvrda od dubinskog pranja. Pogledaj kopče, brave, šrafove, plastike stubova i etikete sa datumima. Ako jedan pojas deluje novije od ostalih ili se plastika oko njega ne uklapa, pitaj za razlog i traži dijagnostiku.

Druga provera je istorija udara i vlage. Zatezači pojaseva često rade zajedno sa airbag sistemom, pa greške na dijagnostici, upaljena lampica, brisane greške ili neobjašnjivo zamenjeni pojasevi moraju biti razlog za oprez. Sporo vraćanje zadnjih pojaseva može biti samo prljavština, ali može biti i znak vlage u stubu, poplave, loše oprane kabine ili auta koji je radio kao porodični prevoz bez pažnje.

Pojas ne treba koristiti samo za spuštanje cene; on odlučuje da li je auto bezbedan za porodicu. Ako je problem sitan i majstor potvrdi da nema grešaka na zatezačima, može biti pregovaračka stavka. Ako prodavac izbegava skidanje plastika, dijagnostiku ili pitanja o airbagovima, kupac treba da odustane jer nijedna dobra cena ne vredi nejasnu pasivnu bezbednost.
TEXT,
                'highlights' => [
                    'Svaki pojas izvuci do kraja, proveri traku, kopče, brave, etikete i brzinu povratka.',
                    'Različit datum pojasa, nova plastika stuba ili airbag greška traže obaveznu dijagnostiku.',
                    'Sporo vraćanje može biti prljavština, vlaga ili trag udesa, pa ne sme ostati neprovereno.',
                ],
                'tags' => ['sigurnosni pojasevi', 'airbag', 'zatezači', 'provera vozila', 'polovan auto'],
                'meta_title' => 'Sigurnosni pojasevi na polovnom autu: provera',
                'meta_description' => 'Kako proveriti sigurnosne pojaseve kod polovnog auta: spor povratak, fleke, etikete, kopče, zatezači, airbag greške, vlaga i udes.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Portugalije: kada topla klima ne znači automatski mirnu kupovinu',
                'slug' => 'uvoz-auta-iz-portugalije-kada-topla-klima-ne-znaci-automatski-mirnu-kupovinu',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Portugalija može delovati privlačno zbog blaže klime i manje soli, ali kupac mora proveriti sunce, obalu, servisnu istoriju, poreklo, transport i realne troškove uvoza.',
                'content' => <<<'TEXT'
Aleksandar je našao auto iz Portugalije sa lepom opremom i pričom da je zbog tople klime mnogo bolji od severnoevropskih primeraka. Fotografije su pokazivale sjajan lak, očuvane felne i enterijer koji nije izgledao umorno. Ipak, zemlja porekla sama po sebi nije garancija; portugalski auto može imati manje soli, ali može imati tragove sunca, obalne vlage, slabije dokumentacije ili dugog transporta koji menja računicu.

Prvo proveri papire i put automobila do Srbije. VIN, vlasnički sled, servisni računi, tehnički pregledi, odjava, transport, carina, porezi, homologacija i registracija moraju biti jasni pre kapare. Ako prodavac ne zna da objasni gde je auto kupljen, ko ga je dovezao i koji troškovi su već plaćeni, kupac ne sme da prihvati priču da je "Portugalija sigurna" kao zamenu za dokumente.

Druga tema su sunce i obala. Pregledaj lak na krovu i haubi, izbledele farove, gumene dihtunge, plastike enterijera, nebo kabine, komandnu tablu, sedišta i tragove vlage u gepeku. Auto sa obale može imati oksidaciju šrafova, konektora i sitnih metalnih delova, čak i kada nema klasičnu zimsku koroziju. Klima uređaj mora hladiti pravilno jer je u toploj zemlji često radio mnogo više nego što kilometraža pokazuje.

Uvoz iz Portugalije ima smisla kada dokumenti, stanje laka, enterijera i mehanike potvrde priču prodavca. Može biti dobar izbor ako kupac dobije uredan auto bez severnjačke soli i sa jasnim servisima. Ako se sve svodi na egzotičnu zemlju porekla, lepe fotografije i obećanje da je klima blaga, računaj pun pregled i pregovaraj kao da kupuješ svaki drugi uvozni polovnjak.
TEXT,
                'highlights' => [
                    'Kod portugalskog uvoza proveri VIN, odjavu, transport, carinu, poreze, homologaciju i registraciju.',
                    'Topla klima traži proveru laka, farova, guma, plastika, klime i tragova obalne vlage.',
                    'Zemlja porekla je prednost samo kada servisni računi i pregled potvrde stanje automobila.',
                ],
                'tags' => ['uvoz iz Portugalije', 'uvoz auta', 'polovni automobili', 'dokumentacija', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Portugalije: šta proveriti',
                'meta_description' => 'Analiza uvoza auta iz Portugalije: VIN, dokumenti, transport, carina, sunce, obalna vlaga, klima, servisna istorija i realni troškovi.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#0f172a', '#60a5fa', '#f8fafc'],
            ],
        ]);
    }

    protected function hubPosts(): array
    {
        return [
            [
                'title' => 'Najbolji polovni automobili do 10.000 evra: kako izabrati bez skupe greške',
                'slug' => 'najbolji-polovni-automobili-do-10000-evra',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Budžet do 10.000 evra traži hladnu selekciju: stanje, motor, održavanje i namena vrede više od opreme i marke.',
                'content' => <<<'TEXT'
Budžet do 10.000 evra u Srbiji može kupiti dobar gradski auto, kompakt, porodični karavan ili stariji SUV, ali ne može kupiti sve odjednom. Najveća greška je tražiti najviše opreme, najjači motor i najlepše fotografije, a tek posle gledati stanje. Kod polovnjaka u ovom budžetu bolja odluka je primerak sa urednom istorijom i manjim rizikom nego atraktivniji auto koji odmah traži velika ulaganja.

## Šta prvo gledati kod auta do 10.000 evra

Prvo odredi realnu namenu. Za gradsku vožnju često ima više smisla benzinac ili hibrid nego dizel sa DPF-om. Za otvoren put dizel može biti dobar, ali samo ako ima servisni trag i ako se vidi da nije ceo život proveo na kratkim relacijama. Za porodicu prostor i bezbedno stanje vrede više od kože, velikih felni i panorame.

## Modeli koji najčešće imaju smisla

U uži izbor često ulaze Volkswagen Golf 7, Audi A3, Škoda Octavia, Toyota Yaris Hybrid, Honda Jazz, Opel Astra, Renault Megane, Kia Ceed, Hyundai i30 i Fiat 500L. To ne znači da je svaki primerak dobra kupovina. Znači samo da za njih postoji dovoljno oglasa, delova, iskustava i servisne podrške da kupac može lakše da poredi.

## Kada preskočiti povoljan oglas

Preskoči oglas kada nema servisne istorije, kada kilometraža ne prati stanje enterijera, kada prodavac izbegava VIN, kada se automobil prodaje bez probne vožnje ili kada cena deluje prenisko u odnosu na slične primerke. Najjeftiniji auto do 10.000 evra često postane skuplji od urednog primerka čim se dodaju gume, veliki servis, kočnice, trap i prvi kvar.

## Kako napraviti uži izbor

Izaberi tri do pet modela koji odgovaraju tvojoj vožnji, zatim poredi samo primerke sa jasnom istorijom, realnom cenom i stanjem koje se može proveriti. Pre kapare proveri VIN, uradi probnu vožnju i pregled kod majstora. Ako auto ne može da prođe taj redosled, nije prava prilika.

FAQ: Koji polovni auto do 10.000 evra je najbolji?
Ne postoji jedan najbolji model. Za grad često pobeđuju manji benzinci i hibridi, za porodicu kompakti i karavani, a za duži put uredan dizel. Najbolji je primerak koji ima dokazivo stanje i odgovara tvojoj vožnji.

FAQ: Da li kupiti dizel do 10.000 evra?
Dizel ima smisla ako voziš duže relacije i ima servisnu istoriju. Za kratku gradsku vožnju često je rizičniji zbog DPF-a, EGR-a, turbine i skupljih kvarova.
TEXT,
                'highlights' => [
                    'Do 10.000 evra prvo biraj stanje i namenu, pa tek onda marku i opremu.',
                    'Dobar benzinac ili hibrid često je mirniji za grad od starijeg dizela.',
                    'Pre kapare obavezno proveri VIN, servisnu istoriju, probnu vožnju i listu ulaganja.',
                ],
                'tags' => ['auto do 10000 evra', 'kupovina polovnjaka', 'budžet', 'polovni automobili'],
                'meta_title' => 'Najbolji polovni automobili do 10.000 evra',
                'meta_description' => 'Vodič za izbor polovnog auta do 10.000 evra u Srbiji: benzinac, dizel, hibrid, porodični auto, servisna istorija i šta proveriti.',
                'is_featured' => false,
                'published_at' => now()->subDays(2),
                'palette' => ['#0f172a', '#f59e0b', '#f8fafc'],
            ],
            [
                'title' => 'Polovni automatik: šta kupiti i šta izbegavati pre probne vožnje',
                'slug' => 'polovni-automatik-sta-kupiti-i-sta-izbegavati',
                'category' => 'Troškovi i održavanje',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Automatik može biti odlična kupovina, ali samo kada tip menjača, servis ulja i probna vožnja ne ostavljaju nepoznanice.',
                'content' => <<<'TEXT'
Polovni automatik treba kupovati drugačije od manuelnog automobila. Kod manuelnog menjača kupac često brzo oseti kvačilo, sinhrone i osnovne probleme. Kod automatika kvar može biti skuplji, a simptomi se nekad pojave tek kada se menjač zagreje, kada se vozi u gužvi ili kada se auto parkira više puta zaredom.

## Tip menjača menja rizik

Nije dovoljno da oglas kaže automatik. Klasični automatik, DSG, CVT i robotizovani menjači ne koštaju isto, ne ponašaju se isto i nemaju iste slabosti. Pre odlaska na pregled saznaj koji je tačno menjač u tom modelu i koliko košta servis ulja ili popravka u Srbiji.

## Servis ulja je ključan dokaz

Prodavac može reći da je menjač odličan, ali račun za servis ulja vredi više od utiska. Kod mnogih polovnih automatika problem nije tehnologija, nego preskočeno održavanje. Ako nema dokaza o servisu, u cenu odmah uračunaj proveru i preventivni servis, a kod trzaja ili kašnjenja budi spreman da odustaneš.

## Probna vožnja mora biti hladna i topla

Menjač proveri kada je auto hladan, zatim posle gradske vožnje. Obrati pažnju na ubacivanje u D i R, trzaje pri kretanju, zadršku pri promeni brzina, vibracije, proklizavanje i ponašanje pri usporavanju. Krug oko parkinga nije dovoljna provera automatika.

FAQ: Da li je DSG loš izbor kao polovnjak?
DSG nije loš ako ima uredan servis i radi glatko, ali zapušten DSG može biti skup. Bitni su konkretan tip menjača, servisna istorija i probna vožnja, ne samo oznaka DSG.

FAQ: Kada treba odustati od polovnog automatika?
Odustani ako menjač trza, kasni pri ubacivanju u D ili R, proklizava, nema servisni trag ili prodavac ne dozvoljava ozbiljnu probnu vožnju i dijagnostiku.
TEXT,
                'highlights' => [
                    'Kod automatika prvo saznaj tačan tip menjača, pa tek onda poredi cenu.',
                    'Servis ulja i probna vožnja hladnog i toplog menjača su obavezni.',
                    'Trzaji, kašnjenje i nejasna istorija menjaju cenu ili prekidaju kupovinu.',
                ],
                'tags' => ['automatik', 'automatski menjač', 'DSG', 'CVT', 'probna vožnja'],
                'meta_title' => 'Polovni automatik: šta kupiti i šta izbegavati',
                'meta_description' => 'Vodič za kupovinu polovnog automatika: DSG, CVT, klasični automatik, servis ulja, probna vožnja, trzaji i kada odustati.',
                'is_featured' => false,
                'published_at' => now()->subDays(2)->addMinute(),
                'palette' => ['#111827', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni hibridi: Toyota, Honda i Hyundai koje vredi proveriti',
                'slug' => 'polovni-hibridi-toyota-honda-hyundai-sta-proveriti',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Hibrid je odličan za grad kada baterija, servisna istorija i prethodna namena potvrđuju da reputacija nije jedini argument.',
                'content' => <<<'TEXT'
Polovni hibridi imaju sve više smisla za kupce koji voze grad, kratke relacije i gužvu. Toyota, Honda i Hyundai nude modele koji mogu biti štedljivi i mirni za svakodnevicu, ali hibrid nije auto bez provere. Baterija, kočnice, trap, klima, elektronika i istorija korišćenja i dalje odlučuju da li je kupovina dobra.

## Toyota kao najpoznatiji izbor

Toyota Yaris Hybrid, Auris Hybrid, Corolla Hybrid i RAV4 Hybrid imaju jak ugled i dobru kasniju prodaju. To je prednost, ali i razlog za višu cenu. Kod Toyote ne plaćaj reputaciju ako nema dokaza o servisu, dijagnostici hibridnog sistema i realnom stanju enterijera.

## Honda i Hyundai kao racionalne alternative

Honda Jazz Hybrid i Hyundai Ioniq mogu biti vrlo dobri izbori kada nude urednu istoriju i bolju cenu za stanje. Kod njih proveri bateriju, softver, kočnice i način prethodne vožnje. Posebno pazi na vozila koja su radila intenzivno u gradu, kao službena ili dostavna vozila.

## Šta hibrid ne prašta

Hibrid dobro podnosi grad, ali ne prašta zanemarivanje. Dugo stajanje, loša 12V baterija, zapuštene kočnice, slaba servisna istorija i nejasna kilometraža mogu napraviti trošak. Dijagnostika hibridnog sistema treba da bude deo pregleda, ne dodatak ako ostane vremena.

FAQ: Da li treba brinuti zbog baterije kod polovnog hibrida?
Baterija nije razlog za automatski strah, ali mora se proveriti dijagnostikom. Mnogo je važnije stanje konkretnog primerka nego opšta reputacija modela.

FAQ: Da li je hibrid bolji od dizela za grad?
Za kratke gradske relacije hibrid je često mirniji izbor od dizela jer nema iste DPF i EGR rizike. Za duge autoput relacije dobar dizel i dalje može imati bolju računicu.
TEXT,
                'highlights' => [
                    'Hibrid ima najviše smisla za gradsku i mešovitu vožnju.',
                    'Toyota ima najjaču reputaciju, ali stanje i dijagnostika odlučuju kupovinu.',
                    'Kod svakog hibrida proveri bateriju, kočnice, 12V bateriju, trap i servisnu istoriju.',
                ],
                'tags' => ['polovni hibrid', 'Toyota Hybrid', 'Honda Hybrid', 'Hyundai Ioniq', 'baterija'],
                'meta_title' => 'Polovni hibridi: Toyota, Honda i Hyundai vodič',
                'meta_description' => 'Kako kupiti polovan hibrid: Toyota Yaris, Corolla, Honda Jazz, Hyundai Ioniq, baterija, dijagnostika, gradska vožnja i servis.',
                'is_featured' => false,
                'published_at' => now()->subDays(2)->addMinutes(2),
                'palette' => ['#10231f', '#22c55e', '#f8fafc'],
            ],
            [
                'title' => 'Porodični SUV polovnjaci: šta kupiti kada prostor nije jedini kriterijum',
                'slug' => 'porodicni-suv-polovnjaci-sta-kupiti',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Porodični SUV treba da opravda prostor, troškove, pogon i bezbednost, a ne samo popularnu formu i višu poziciju sedenja.',
                'content' => <<<'TEXT'
Porodični SUV je danas najpopularniji odgovor na skoro svaku kupovinu, ali nije svaki SUV bolji porodični auto od kompakta, karavana ili monovolumena. Kupac često plati višu cenu, veće gume i veću potrošnju, a dobije gepek koji nije mnogo bolji od karavana. Zato SUV treba birati po stvarnoj upotrebi.

## Kompaktni ili veći SUV

Nissan Qashqai, Peugeot 3008, Hyundai Tucson, Ford Kuga, Volkswagen Tiguan, Škoda Kodiaq, Toyota RAV4 i Kia Sportage rešavaju različite probleme. Kompaktni SUV je bolji za grad i lakše parkiranje. Veći SUV ima smisla kada često putuješ, nosiš mnogo stvari ili stvarno koristiš zadnju klupu i gepek.

## Troškovi koje oglasi često sakriju

SUV trošak nisu samo gorivo i registracija. Veće gume, trap, kočnice, amortizeri, automatski menjač, pogon na sva četiri točka i hibridni sistemi mogu ozbiljno promeniti računicu. Ako je auto jeftiniji od sličnih primeraka, prvo proveri koji trošak čeka kupca posle prenosa.

## Test porodične upotrebe

Pre kapare ponesi dečje sedište, proveri ISOFIX, gepek, prag utovara, zadnju klupu, preglednost i parkiranje. Auto koji izgleda ozbiljno na fotografijama može biti nezgodan za tvoju rutinu. Porodični SUV treba da olakša dan, ne da samo izgleda kao logičan izbor.

FAQ: Koji porodični SUV je najbolji polovan?
Najbolji je onaj koji odgovara tvojoj rutini i ima urednu istoriju. Qashqai i 3008 su lakši za grad, Tucson i Kuga su jači porodični kompromisi, a Kodiaq i RAV4 imaju smisla kada prostor i mirnija istorija opravdavaju cenu.

FAQ: Da li je SUV bolji od karavana za porodicu?
Ne uvek. Karavan često nudi veći gepek, niže troškove i bolju potrošnju. SUV ima smisla ako ti zaista trebaju viši ulazak, lošiji putevi ili povišena pozicija sedenja.
TEXT,
                'highlights' => [
                    'SUV kupuj zbog konkretne porodične upotrebe, ne samo zbog popularnosti.',
                    'Veće gume, trap, pogon i automatik često su skriveni trošak polovnog SUV-a.',
                    'Pre kupovine testiraj dečja sedišta, gepek, zadnju klupu i svakodnevno parkiranje.',
                ],
                'tags' => ['porodični SUV', 'Tiguan', 'Kodiaq', 'Qashqai', 'Tucson', 'Kuga'],
                'meta_title' => 'Porodični SUV polovnjaci: šta kupiti',
                'meta_description' => 'Vodič za izbor porodičnog polovnog SUV-a: Qashqai, 3008, Tucson, Kuga, Tiguan, Kodiaq, RAV4, troškovi, gepek i provera.',
                'is_featured' => false,
                'published_at' => now()->subDays(2)->addMinutes(3),
                'palette' => ['#172033', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Kako proveriti polovan auto pre kupovine: redosled koji štedi novac',
                'slug' => 'kako-proveriti-polovan-auto-pre-kupovine',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dobra provera polovnog auta ima redosled: oglas, VIN, dokumentacija, hladan start, probna vožnja, majstor i tek onda kapara.',
                'content' => <<<'TEXT'
Polovan auto se ne proverava tek kada se dogovori cena. Provera počinje čitanjem oglasa i završava se tek kada se stanje, dokumentacija, probna vožnja i pregled kod majstora slože u jednu priču. Ako jedan deo odskače, kapara treba da sačeka.

## Prvi filter je oglas

Gledaj da li opis ima konkretne servise, VIN, jasne fotografije, realnu cenu i podatke koji se mogu proveriti. Oglas pun opštih fraza ne znači automatski problem, ali ne daje dovoljno poverenja. Ako prodavac izbegava pitanja pre gledanja auta, to je već signal.

## Drugi filter su dokumenti i VIN

VIN, servisna knjižica, računi, uvozna dokumentacija, tehnički pregledi i stanje enterijera moraju imati logiku. Kilometraža nije dokaz sama po sebi. Broj na satu treba da se poklopi sa volanom, sedištem, pedalama, gumama, servisima i pričom prodavca.

## Treći filter je probna vožnja i majstor

Probna vožnja treba da uključi hladan start, gradsku vožnju, ubrzanje, kočenje, neravnine, parkiranje i rad menjača. Posle toga majstor treba da proveri karoseriju, dijagnostiku, trap, kočnice, curenja, motor i okvirna ulaganja u novcu. Pregled bez liste troškova nije kompletan.

FAQ: Šta prvo proveriti kod polovnog auta?
Prvo proveri oglas, VIN, dokumentaciju i da li se kilometraža slaže sa stanjem. Tek zatim ima smisla ići na probnu vožnju i pregled kod majstora.

FAQ: Da li dati kaparu pre pregleda kod majstora?
Ne, osim ako je uslov jasno dogovoren i povratan. Najsigurnije je da kapara ide tek posle provere dokumentacije, probne vožnje i pregleda.
TEXT,
                'highlights' => [
                    'Provera počinje oglasom i VIN-om, ne tek kod majstora.',
                    'Kilometraža mora da se poklopi sa stanjem, dokumentacijom i enterijerom.',
                    'Kapara ima smisla tek kada probna vožnja i pregled daju jasnu listu ulaganja.',
                ],
                'tags' => ['provera polovnog auta', 'VIN', 'servisna istorija', 'majstor', 'kapara'],
                'meta_title' => 'Kako proveriti polovan auto pre kupovine',
                'meta_description' => 'Redosled provere polovnog auta pre kupovine: oglas, VIN, servisna istorija, kilometraža, probna vožnja, majstor i kapara.',
                'is_featured' => false,
                'published_at' => now()->subDays(2)->addMinutes(4),
                'palette' => ['#111827', '#a78bfa', '#f8fafc'],
            ],
            [
                'title' => 'Škoda Citigo ili Hyundai i10: mali auto kada jednostavnost mora pobediti opremu',
                'slug' => 'skoda-citigo-ili-hyundai-i10-mali-auto-kada-jednostavnost-mora-pobediti-opremu',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Nikola je za prvi auto birao između Citiga sa skromnom opremom i i10 sa više komfora; pobedio je primerak sa boljom istorijom, ne duži spisak dugmića.',
                'content' => <<<'TEXT'
Nikola je tražio mali auto za posao i vikend odlazak do roditelja. Našao je Škodu Citigo sa jasnim računima, ali bez ekrana i senzora, i Hyundai i10 sa bogatijom opremom, ali nejasnim servisom. Njegova dilema nije bila koji model izgleda modernije, već da li dodatna oprema može nadoknaditi nepoznatu prošlost polovnjaka.

Citigo je dobar izbor kada ti je važna preglednost, kompaktna mera i jednostavan gradski ritam. Kod svakog primerka proveri hladan start, kvačilo, menjač, rad klima-uređaja, stanje kočnica i tragove udaraca po točkovima i pragovima. Auto koji je stalno parkiran uz ivičnjak lako sakrije umoran trap, pa probna vožnja preko neravnine vredi više od sjajne fotografije.

Hyundai i10 može dati prijatniju kabinu i često bolju opremu za isti novac, ali to nije dozvola da se preskoče računi i pregled. Obrati pažnju na redovan servis benzinca, ravnomerno trošenje guma, kvačilo, elektroniku i da li klima radi bez čudnog mirisa. Ako prodavac nema objašnjenje za praznine u istoriji, pregovaraj kao da ćeš prvi servis raditi odmah.

Nikola je izabrao Citigo jer je majstor našao uredan donji deo, sveže kočnice i jasan trag održavanja, dok je i10 imao lepšu opremu ali više pitanja nego odgovora. Citigo je pravi izbor kada nađeš zdrav gradski primerak, a i10 kada njegova udobnost dolazi uz dokaziv servis. Ako oba automobila traže ista ulaganja, uzmi onaj koji ti ostavlja više budžeta posle prenosa; ako istorija ne postoji, odustani od oba.
TEXT,
                'highlights' => [
                    'Citigo i i10 biraj po stanju, računima i probnoj vožnji, ne po ekranu i opremi.',
                    'Kod oba proveri hladan start, kvačilo, trap, gume, kočnice i klima-uređaj.',
                    'Pregovaraj za svaku prazninu u servisnoj istoriji; nejasan primerak nije povoljan.',
                ],
                'tags' => ['Škoda Citigo', 'Hyundai i10', 'mali auto', 'prvi auto', 'poređenje'],
                'meta_title' => 'Škoda Citigo ili Hyundai i10: koji mali polovnjak kupiti',
                'meta_description' => 'Poređenje polovnih Škoda Citigo i Hyundai i10: servis, motor, kvačilo, trap, oprema, gradska vožnja i kada odustati.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Peugeot 1007: neobičan mali auto koji mora dokazati klizna vrata',
                'slug' => 'polovni-peugeot-1007-neobican-mali-auto-koji-mora-dokazati-klizna-vrata',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Milan je našao povoljan Peugeot 1007 za grad, ali je cena izgubila smisao kada su klizna vrata zapela pri svakom drugom otvaranju.',
                'content' => <<<'TEXT'
Milan je želeo mali auto u koji se lako ulazi na tesnim parking mestima i oglas za Peugeot 1007 delovao je kao retka prilika. Auto je bio uredan spolja, motor je radio mirno, a cena je bila ispod sličnih gradskih modela. Tek kada je na licu mesta nekoliko puta otvorio obe strane, jedna klizna vrata su zastala, pa je jeftin oglas dobio sasvim drugačiju računicu.

Kod 1007 prvo proveri klizna vrata više puta, sa ugašenim i upaljenim motorom, preko daljinca i tastera u kabini. Treba da se otvaraju i zatvaraju ravnomerno, bez zastoja, čudnih zvukova i upozorenja na tabli. Pogledaj šine, dihtunge, kablove u pregibima i da li se vrata pravilno zaključavaju, jer neobična karoserija nije dobra kupovina ako je njena glavna prednost neispravna.

Zatim vozi auto kao da ćeš ga stvarno koristiti: hladan start, kvačilo, menjač, kočenje, trap, klima i položaj sedenja. Mali gradski primerci često imaju mnogo kratkih vožnji, pa se na pedalama, volanu, sedištu, branicima i felnama brzo vidi koliko je auto stvarno radio. Traži račune za redovan servis i proveri da li za konkretan motor i opremu imaš razuman pristup delovima.

Milan je odustao od prvog auta, a drugi primerak je kupio tek kada su vrata prošla pregled bez zastajkivanja i kada je majstor potvrdio da nema skrivenog udara. Peugeot 1007 ima smisla za kupca kome njegov format rešava gradsku rutinu i koji prihvata manju ponudu primeraka. Ako vrata ne rade savršeno ili prodavac problem naziva sitnicom, traži ozbiljan popust sa procenom popravke ili se okreni drugom autu.
TEXT,
                'highlights' => [
                    'Klizna vrata proveri više puta preko svih komandi pre nego što razgovaraš o kapari.',
                    'Hladan start, kvačilo, trap, kočnice i klima ostaju jednako važni kao vrata.',
                    'Ne kupuj retkost bez servisa i dostupnih delova samo zato što je oglas jeftin.',
                ],
                'tags' => ['Peugeot 1007', 'klizna vrata', 'mali auto', 'gradska vožnja', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Peugeot 1007: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Peugeot 1007: klizna vrata, motor, kvačilo, trap, klima, servisna istorija, delovi i realna cena.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(3),
                'palette' => ['#241b2f', '#c084fc', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Fiat Freemont: porodični SUV koji mora dokazati prostor, pogon i istoriju',
                'slug' => 'polovni-fiat-freemont-porodicni-suv-koji-mora-dokazati-prostor-pogon-i-istoriju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Jelena je tražila sedam sedišta bez SUV premije; Freemont je imao prostora, ali tek su pregled pogona i servisni računi odlučili da li je za porodicu.',
                'content' => <<<'TEXT'
Jelena je tražila auto za troje dece, kolica i duža putovanja, a Fiat Freemont joj je ponudio mnogo prostora za cenu ispod popularnijih SUV-ova. Na oglasu je sve izgledalo idealno: sedam sedišta, dizel i bogata oprema. Međutim, takav auto ne treba birati po broju sedišta, već po tome da li su motor, menjač, pogon i kabina podneli težak porodični život bez preskakanja održavanja.

Prvo utvrdi kako ćeš koristiti treći red. Otvori i zatvori sva sedišta, proveri pojaseve, brave, ventilaciju pozadi, gepek i mehanizme preklapanja. Ako je auto vozio veliku porodicu ili služio za duge relacije, enterijer, zadnji trap i kočnice mogu pokazati više od sjajnog laka. Ponesi dečje sedište na gledanje; ono što stane u katalogu ne mora lako da funkcioniše u tvojoj rutini.

Kod dizela proveri hladan start, dim, dijagnostiku, servis ulja i da li se priča o kilometraži poklapa sa računima i kabinom. Ako primerak ima automatik ili pogon na sva četiri točka, probna vožnja mora uključiti polazak uzbrdo, manevrisanje, pun ugao volana i ubrzanje bez trzaja. Pregled na dizalici treba da obuhvati trap, curenja, izduv, gume i stanje pogonskih komponenti.

Jelena je kupila Freemont tek nakon što je pregled pokazao uredne račune, miran rad menjača i istrošenost kabine koja odgovara kilometraži. Freemont ima smisla kada ti stvarno treba prostor i kada ostane novca za preventivni servis posle kupovine. Ako je treći red umoran, pogon se javlja zvukom ili servisna priča nije potpuna, ne pregovaraj samo o ceni: preskoči auto.
TEXT,
                'highlights' => [
                    'Treći red, pojaseve, brave i gepek testiraj sa stvarnim porodičnim potrebama.',
                    'Dizel, automatik i 4x4 traže dijagnostiku, dugu probnu vožnju i pregled na dizalici.',
                    'Prostor vredi samo ako servisna istorija i stanje pogona potvrđuju mirnu kupovinu.',
                ],
                'tags' => ['Fiat Freemont', 'sedam sedišta', 'porodični SUV', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Fiat Freemont: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Fiat Freemont: sedam sedišta, dizel, automatik, 4x4, trap, servis, gepek i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(2),
                'palette' => ['#17231d', '#4ade80', '#f8fafc'],
            ],
            [
                'title' => 'Poklopac rezervoara na polovnom autu: kada mali otvor otkriva udarac, rđu ili lošu popravku',
                'slug' => 'poklopac-rezervoara-na-polovnom-autu-kada-mali-otvor-otkriva-udarac-rdju-ili-losu-popravku',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Marko je pri točenju goriva primetio da se poklopac ne zatvara ravno; nekoliko minuta pregleda otkrilo je loše popravljenu bočnu stranu auta.',
                'content' => <<<'TEXT'
Marko je došao da pogleda uredan hečbek i prodavac je već pričao o kapari kada je otvorio poklopac rezervoara. Vratašca su zapinjala, šrafovi su bili drugačije boje, a zazor prema blatobranu nije bio ravan. To samo po sebi ne dokazuje ozbiljan udar, ali je dovoljno da priču o besprekornom stanju zaustaviš dok ne dobiješ objašnjenje i pregled.

Otvori poklopac nekoliko puta i pogledaj da li se zatvara ravno, da li je opruga čvrsta i da li se brava otključava zajedno sa autom. Uporedi nijansu laka sa susednim panelom, pregledaj rub otvora, šrafove, gumu oko čepa i tragove maskiranja ili sveže farbe. Kod starijih auta proveri ima li rđe oko otvora, jer voda i prosuto gorivo mogu oštetiti lim tamo gde se ne vidi na prvoj fotografiji.

Zatim poveži nalaz sa ostatkom bočne strane: zazori vrata, rub blatobrana, zadnje svetlo, unutrašnjost gepeka i merenje laka treba da pričaju istu priču. Ako poklopac pokazuje raniju popravku, to ne znači automatski da treba odustati, ali prodavac mora objasniti obim štete i dokazati da su geometrija, svetla i zaštita od korozije ostali uredni.

Kod Markovog auta majstor je našao više slojeva kita oko zadnjeg krila, pa je kupovina stala pre kapare. Mali detalj je koristan jer je brz, besplatan i tera pregled na pravo mesto. Nastavi sa kupovinom kada poklopac, lak i unutrašnja strana panela imaju logiku; pregovaraj samo za jasno dokumentovanu kozmetiku; odustani kada loš zazor otvori niz novih pitanja bez odgovora.
TEXT,
                'highlights' => [
                    'Otvori poklopac rezervoara više puta i proveri zatvaranje, bravu, oprugu i zazore.',
                    'Uporedi lak, šrafove i rub otvora sa blatobranom, vratima i unutrašnjošću gepeka.',
                    'Nejasna popravka bočne strane traži merenje laka i pregled kod majstora pre kapare.',
                ],
                'tags' => ['provera vozila', 'poklopac rezervoara', 'limarija', 'merenje laka', 'korozija'],
                'meta_title' => 'Poklopac rezervoara polovnog auta: šta otkriva',
                'meta_description' => 'Kako poklopac rezervoara otkriva udarac, rđu ili lošu limarsku popravku na polovnom autu: zazori, lak, šrafovi, gepek i kada odustati.',
                'is_featured' => false,
                'published_at' => now()->subMinute(),
                'palette' => ['#2a1915', '#fb923c', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Letonije: kada povoljna cena traži proveru zime, soli i porekla',
                'slug' => 'uvoz-auta-iz-letonije-kada-povoljna-cena-trazi-proveru-zime-soli-i-porekla',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Vladan je našao povoljan karavan iz Letonije, ali je niska cena dobila smisao tek kada su podvozje, dokumenti i istorija objasnili gde je auto proveo zime.',
                'content' => <<<'TEXT'
Vladan je na oglasu video karavan iz Letonije sa dobrom opremom i cenom koja je izgledala niže od domaćih primeraka. Prodavac je naglašavao uredan enterijer i malu kilometražu, ali fotografije spolja ne govore kako su zima, vlaga i so uticale na podvozje. Kod ovakvog uvoza zemlja porekla nije presuda, već razlog da se poreklo i stanje proveravaju detaljnije.

Pre puta pošalji VIN i traži fotografije servisne dokumentacije, poslednjih tehničkih pregleda, računa i donjeg dela auta. Uporedi datume, kilometražu i vlasništvo sa stanjem volana, sedišta, pedala i gepeka. Ako dokumenti preskaču godine ili prodavac ne ume objasniti da li je auto bio privatni, službeni ili flotni, računaj da je niža cena naknada za rizik, ne automatska ušteda.

Na pregledu kod majstora podigni auto i gledaj pragove, pod, nosače, kočione cevi, izduv, šrafove, rubove i mesta oko vešanja. So ne mora značiti konstrukcionu rđu, ali svež premaz preko prljavštine, natečeni rubovi ili zapekli vijci menjaju i cenu i odluku. Kod dizela proveri i trag kratkih zimskih relacija kroz DPF, EGR, grejanje i servisne intervale.

Vladan je kupio drugi primerak tek kada su VIN, računi i pregled na dizalici potvrdili da cena nije sakrila loš donji deo. Uvoz iz Letonije može biti dobra prilika kada istorija ima kontinuitet, karoserija je zdrava i ukupni trošak ostaje realan posle prenosa i prvog servisa. Ako je cena jedini jasan podatak, pregovaraj iz pozicije rizika ili odustani i sačuvaj budžet za proverljiv auto.
TEXT,
                'highlights' => [
                    'Letonski uvoz proveravaj kroz VIN, račune, tehničke preglede i stvarnu namenu vozila.',
                    'Pod, pragovi, kočione cevi, nosači i šrafovi moraju na dizalicu zbog zime i soli.',
                    'Niska cena ima smisla tek kada pregled objasni poreklo, stanje i prvi servis.',
                ],
                'tags' => ['uvoz iz Letonije', 'uvoz auta', 'korozija', 'VIN', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Letonije: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za uvoz polovnog auta iz Letonije: VIN, dokumentacija, zima, so, korozija, podvozje, kilometraža i realna cena.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#60a5fa', '#f8fafc'],
            ],
            [
                'title' => 'Mazda 5 ili Ford Grand C-Max: porodični van kada klizna vrata nisu jedini argument',
                'slug' => 'mazda-5-ili-ford-grand-c-max-porodicni-van-kada-klizna-vrata-nisu-jedini-argument',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Petar je tražio porodični van sa lakim ulaskom za decu; između Mazde 5 i Grand C-Maxa presudili su stanje kliznih vrata, zadnjeg trapa i realni trošak posle kupovine.',
                'content' => <<<'TEXT'
Petar je tražio auto za dvoje dece, sedišta i vikend putovanja, pa su mu Mazda 5 i Ford Grand C-Max delovali kao logična alternativa skupim SUV-ovima. Obe ponude su imale klizna zadnja vrata i dovoljno mesta za porodičnu rutinu, ali na licu mesta razlika nije bila u samom konceptu nego u tome koliko je konkretan primerak sačuvao mehaniku, kabinu i budžet za prvi servis.

Mazda 5 ima smisla kada želiš jednostavniji porodični van sa japanskom reputacijom i mirnijim benzinskim karakterom. Kod svakog primerka proveri rad kliznih vrata, stanje pragova, zadnjeg trapa, klima-uređaja i trećeg reda ako ti zaista treba. Stariji primerci lako sakriju koroziju oko donjih ivica, pa pregled odozdo vredi više od uredne fotografije spolja.

Grand C-Max je često privlačan jer deluje modernije, preglednije i lakše za svakodnevni grad, ali traži strožu proveru dizela, turbine, DPF-a i elektronike ako gledaš bogatiju opremu. Otvaraj vrata više puta, proveri mehanizme sedišta, ravnomerno trošenje guma i da li tragovi porodične upotrebe u kabini odgovaraju kilometraži. Ako jedan auto nudi više opreme, a manje računa, ta oprema samo podiže rizik.

Petar je na kraju uzeo Mazdu 5 jer je pregled pokazao zdrav pod, mirniji trap i jasnije račune, dok je Grand C-Max tražio više početnih ulaganja nego što oglas priznaje. Mazda 5 je bolja kada želiš rasterećeniji posed i ne juriš najmlađi primerak po svaku cenu. Grand C-Max ima smisla kada je istorija čista i kada ti više znači moderniji osećaj za volanom nego potencijalno skuplji dizel sistem. U oba slučaja, nastavi samo sa autom koji posle pregleda ostavlja rezerve u budžetu, a odustani od onog koji se prodaje kroz ideju praktičnosti bez pokrića u stanju.
TEXT,
                'highlights' => [
                    'Klizna vrata proveri više puta, ali odluku donesi tek posle pregleda trapa, poda i dokumentacije.',
                    'Mazda 5 traži proveru korozije, zadnjeg trapa i stanja kabine kroz porodičnu upotrebu.',
                    'Grand C-Max ima smisla samo kada dizel, DPF i elektronika imaju jasnu servisnu priču.',
                ],
                'tags' => ['Mazda 5', 'Ford Grand C-Max', 'porodični van', 'klizna vrata', 'poređenje'],
                'meta_title' => 'Mazda 5 ili Ford Grand C-Max: koji porodični van kupiti',
                'meta_description' => 'Poređenje polovnih Mazda 5 i Ford Grand C-Max modela: klizna vrata, trap, dizel rizici, korozija, sedišta i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now(),
                'palette' => ['#172033', '#38bdf8', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Chevrolet Orlando: porodični van koji mora opravdati sedišta, dizel i delove',
                'slug' => 'polovni-chevrolet-orlando-porodicni-van-koji-mora-opravdati-sedista-dizel-i-delove',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dejan je hteo sedam sedišta za manje novca od popularnih vanova, ali je Orlando imao smisla tek kada su treći red, dizel i dostupnost delova prošli proveru.',
                'content' => <<<'TEXT'
Dejan je želeo sedam sedišta, visok položaj sedenja i cenu nižu od traženijih porodičnih modela, pa mu je Chevrolet Orlando delovao kao pametna prečica. Na oglasu je sve izgledalo razumno: prostrana kabina, dizel i dosta opreme za novac. Međutim, ovakav auto nije dobar samo zato što deluje povoljnije od konkurencije, već zato što sedišta, motor i servisna logistika mogu da izdrže još nekoliko godina bez improvizacije.

Prvo proveri kako ti Orlando stvarno rešava porodičnu rutinu. Otvori treći red, pomeri drugu klupu, pogledaj pojaseve, mehanizme preklapanja i koliko gepeka ostaje kada su sva sedišta u upotrebi. Tragovi dečjih sedišta, izgrebana plastika i umorni mehanizmi nisu sami po sebi razlog za odustajanje, ali moraju biti usklađeni sa kilometražom i cenom.

Kod dizela proveri hladan start, DPF, EGR, turbinu, zamajac i ponašanje menjača u gradskoj vožnji i pri opterećenju. Orlando treba podići na dizalicu zbog trapa, kočnica i curenja, a posebno pitaj koliko su delovi dostupni za konkretan motor i opremu koju gledaš. Jeftiniji oglas nema smisla ako već prvi servis traži delove koji čekaju i podižu račun.

Dejan je kupio drugi primerak tek kada su pregled i računi pokazali da su treći red i dizel korišćeni, ali ne i zapušteni. Orlando ima smisla za porodicu koja stvarno koristi dodatna sedišta i prihvata da je mreža delova uža od najpopularnijih rivala. Nastavi kada auto ima jasnu istoriju, miran rad i realnu dostupnost servisa; pregovaraj kada mehanika traži ulaganje sa jasnom procenom; odustani kada se prodavac više oslanja na prostor nego na račune.
TEXT,
                'highlights' => [
                    'Treći red, mehanizme sedišta i gepek proveri kao da auto kupuješ za sutrašnje putovanje.',
                    'Dizel Orlando traži proveru DPF-a, EGR-a, turbine, zamajca i pregleda na dizalici.',
                    'Povoljna cena vredi samo ako su delovi i prvi servis realno dostupni u budžetu.',
                ],
                'tags' => ['Chevrolet Orlando', 'sedam sedišta', 'porodični van', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Chevrolet Orlando: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Chevrolet Orlando modela: sedam sedišta, dizel, DPF, delovi, trap, gepek i porodična upotreba.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(1),
                'palette' => ['#241b2f', '#c084fc', '#f8fafc'],
            ],
            [
                'title' => 'Polovni Hyundai ix35: SUV koji mora dokazati 4x4, dizel i gradsku istoriju',
                'slug' => 'polovni-hyundai-ix35-suv-koji-mora-dokazati-4x4-dizel-i-gradsku-istoriju',
                'category' => 'Kupovina polovnjaka',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Ivana je želela povišen SUV za grad i put, ali je ix35 postao dobra kupovina tek kada su pogon, dizel i tragovi kratkih relacija dobili jasno objašnjenje.',
                'content' => <<<'TEXT'
Ivana je tražila SUV koji će joj dati lak ulazak, bolju preglednost i dovoljno mira za put do vikendice, a Hyundai ix35 se često pojavljivao kao povoljnija alternativa traženijim modelima. Oglas je obećavao 4x4, dizel i dobru opremu, ali takva kombinacija ume da izgleda bolje na papiru nego u stvarnom životu ako je auto većinu vremena proveo u gradu i preskakao održavanje.

Kod ix35 prvo odredi da li ti pogon na sva četiri točka zaista treba. Ako ga auto ima, proveri da li se sistem javlja šumom, trzajem ili upozorenjem pri manevrisanju i punom skretanju. Pogledaj stanje guma, kardana, zadnjeg diferencijala i da li su sve četiri gume iste mere i približno istog trošenja, jer neusklađen set može sakriti skuplju priču o pogonu.

Dizel primerci traže hladan start, proveru DPF-a, EGR-a, turbine i istorije servisa, posebno ako se na kabini vide kratke gradske relacije i mala prosečna brzina života. Ix35 treba podići zbog korozije na donjem delu, trapa i eventualnih curenja, a probna vožnja mora uključiti i grad i otvoren put. Ako prodavac priča samo o opremi, a ne zna kada je poslednji put servisiran menjač, pogon ili dizel sistem, računaj da ti prepušta rizik.

Ivana je uzela primerak sa urednim računima, ravnomernim gumama i tihim radom pogona, iako nije bio najjeftiniji u pretrazi. Ix35 je dobra kupovina kada želiš jednostavniji SUV i kada stanje opravdava cenu bez oslanjanja na reputaciju marke. Nastavi kada su 4x4, dizel i gradski tragovi pod kontrolom; pregovaraj kada ulaganja imaju jasan spisak; odustani kada pogon, kilometraža i servisna priča ne mogu da stanu u jednu logičnu rečenicu.
TEXT,
                'highlights' => [
                    '4x4 proveri kroz jednake gume, manevrisanje, zvukove pogona i pregled zadnjeg diferencijala.',
                    'Dizel ix35 traži hladan start, proveru DPF-a, EGR-a, turbine i tragova kratkih relacija.',
                    'Najjeftiniji SUV prestaje biti povoljan čim pogon i prvi servis ostanu bez dokumentacije.',
                ],
                'tags' => ['Hyundai ix35', 'SUV', '4x4', 'dizel', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Hyundai ix35: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za kupovinu polovnog Hyundai ix35 modela: 4x4 pogon, dizel, DPF, trap, korozija, gradska vožnja i realna cena.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(2),
                'palette' => ['#17231d', '#4ade80', '#f8fafc'],
            ],
            [
                'title' => 'Grejanje sedišta na polovnom autu: kada topao komfor otkriva instalaciju, presvlake ili vlagu',
                'slug' => 'grejanje-sedista-na-polovnom-autu-kada-topao-komfor-otkriva-instalaciju-presvlake-ili-vlagu',
                'category' => 'Provera vozila',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Sara je na letnjem pregledu zanemarila grejanje sedišta, a tek kasnije shvatila da neujednačeno zagrevanje otkriva raskopanu instalaciju i lošu presvlaku.',
                'content' => <<<'TEXT'
Sara je gledala lepo očuvan hatchback po toplom danu i skoro preskočila proveru grejanja sedišta jer joj ta opcija tada nije delovala važna. Kada je prodavac ipak uključio sistem, naslon se nije zagrevao ravnomerno, a sedište vozača je mirisalo na naknadno lepljenu presvlaku. Takav detalj ne mora biti veliki kvar, ali često otvori priču o raskopanoj instalaciji, vlazi ili lošoj popravci enterijera.

Uključi grejanje na oba prednja sedišta i sačekaj nekoliko minuta da vidiš da li i sedalni deo i naslon reaguju ravnomerno. Proveri sve stepene, lampice, prekidače i da li se toplota javlja prebrzo samo na jednoj tački ili potpuno izostaje. Neravnomerno grejanje može značiti prekid mreže, zamenu presvlake ili improvizaciju posle oštećenja sedišta.

Zatim pogledaj bočne stranice sedišta, šavove, tragove skidanja presvlake, rad airbag oznaka i stanje instalacije ispod sedišta. Ako auto miriše na vlagu, ima zamagljena stakla ili oksidaciju na konektorima, grejanje sedišta postaje još korisniji trag jer voda često prva napravi problem baš u donjem delu kabine. Kod električno podesivih sedišta proveri sve komande zajedno, jer jedan kvar ume da sakrije drugi.

Sara je odustala od prvog auta kada je majstor našao vlagu ispod tepiha i neoriginalno krpljenje instalacije do sedišta. Grejanje sedišta je dobar test jer spaja komfor, struju i stanje enterijera u jednoj brzoj proveri. Nastavi sa kupovinom kada se oba sedišta greju ravnomerno i kabina nema skrivene tragove rastavljanja; pregovaraj kada je problem jasan i lokalizovan; odustani kada topao prekidač otvori priču o vodi, airbagu ili loše vraćenoj unutrašnjosti.
TEXT,
                'highlights' => [
                    'Testiraj grejanje na oba sedišta i proveri da li se sedalni deo i naslon greju ravnomerno.',
                    'Pregledaj šavove, bočne stranice, konektore i tragove skidanja presvlake ispod sedišta.',
                    'Grejanje sedišta je brz trag za vlagu, lošu instalaciju i nestručno vraćen enterijer.',
                ],
                'tags' => ['grejanje sedišta', 'provera vozila', 'enterijer', 'elektronika', 'vlaga'],
                'meta_title' => 'Grejanje sedišta na polovnom autu: šta proveriti',
                'meta_description' => 'Kako proveriti grejanje sedišta na polovnom autu: prekidači, mreža grejača, presvlake, konektori, vlaga, airbag i kada odustati.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(3),
                'palette' => ['#2a1915', '#fb923c', '#f8fafc'],
            ],
            [
                'title' => 'Uvoz auta iz Finske: kada uredna istorija traži proveru zime, grejača i korozije',
                'slug' => 'uvoz-auta-iz-finske-kada-uredna-istorija-trazi-proveru-zime-grejaca-i-korozije',
                'category' => 'Analiza tržišta',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Nikola je našao uredan finski uvoz sa jakom opremom, ali su tek pregled podvozja, pomoćnih grejača i servisne logike pokazali da li zimski život krije dodatni račun.',
                'content' => <<<'TEXT'
Nikola je na oglasu video karavan iz Finske sa urednom servisnom istorijom, automatskom klimom i dodatnom zimskom opremom koja je delovala primamljivo za domaće tržište. Problem je što uredni papiri ne govore sami po sebi kako su hladnoća, so i kratke zimske relacije uticale na podvozje, grejače i dizel sistem. Kod finskog uvoza priča počinje tek kada dokumenti i stanje potvrde jedno drugo.

Pre odlaska po auto traži VIN, servisne račune, poslednje tehničke preglede i fotografije donjeg dela, pragova i prostora oko amortizera. Finski primerci često imaju dodatne grejače motora, kabine ili sedišta, pa pitaj šta je fabričko, šta radi i da li su ti sistemi ikada popravljani. Dobra oprema vredi samo kada je pregled pokaže ispravnom i kada ne krije improvizovanu instalaciju.

Na licu mesta podigni auto i gledaj pod, kočione cevi, izduv, nosače, rubove vrata i tragove zaštitnog premaza koji možda skriva koroziju. Kod dizela proveri DPF, EGR, hladan start i koliko logično izgleda upotreba auta u zimskim uslovima. Ako dokumentacija izgleda uredno, a kabina, šrafovi i podvozje govore sasvim drugu priču, veruj mehanici pre papira.

Nikola je kupio tek drugi finski primerak, onaj kod kog su računi, pomoćni grejači i podvozje imali istu logiku. Uvoz iz Finske može biti dobra prilika kada dobijaš uredan servis, bogatu ali ispravnu opremu i karoseriju bez skrivene zime u donjem delu. Nastavi kada pregled potvrdi kontinuitet istorije; pregovaraj kada ulaganja imaju jasnu cenu; odustani kada uredan fascikl pokušava da pokrije umoran metal i nejasne električne dodatke.
TEXT,
                'highlights' => [
                    'Finski uvoz traži VIN, račune, tehničke preglede i fotografije podvozja pre puta.',
                    'Posebno proveri dodatne grejače, instalaciju, hladan start i tragove zime na donjem delu auta.',
                    'Papiri vrede samo kada pod, pragovi, šrafovi i oprema potvrde istu priču.',
                ],
                'tags' => ['uvoz iz Finske', 'uvoz auta', 'korozija', 'grejači', 'analiza tržišta'],
                'meta_title' => 'Uvoz auta iz Finske: šta proveriti pre kupovine',
                'meta_description' => 'Vodič za uvoz polovnog auta iz Finske: VIN, servisna istorija, korozija, pomoćni grejači, dizel, podvozje i realna cena.',
                'is_featured' => false,
                'published_at' => now()->subMinutes(4),
                'palette' => ['#172033', '#60a5fa', '#f8fafc'],
            ],
        ];
    }

    protected function shouldWritePlaceholderCover(BlogPost $post): bool
    {
        $path = trim((string) $post->cover_image_path);

        return $path === ''
            || str_starts_with($path, 'blog/trendovi/')
            || str_ends_with($path, '.svg');
    }

    protected function coverSvg(BlogPost $post, array $palette, int $index): string
    {
        [$background, $accent, $text] = $palette;
        $title = $this->svgText($post->title);
        $category = $this->svgText((string) $post->category);
        $excerpt = $this->svgText(Str::of($post->excerptText())->limit(120)->toString());
        $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1600" height="900" viewBox="0 0 1600 900" role="img" aria-label="{$title}">
  <rect width="1600" height="900" fill="{$background}"/>
  <path d="M0 640 C280 560 430 690 710 590 C990 490 1190 520 1600 390 L1600 900 L0 900 Z" fill="{$accent}" opacity="0.18"/>
  <path d="M-80 250 C240 140 460 240 760 150 C1040 70 1260 120 1680 40" fill="none" stroke="{$accent}" stroke-width="3" opacity="0.45"/>
  <rect x="96" y="96" width="210" height="58" rx="0" fill="{$accent}" opacity="0.95"/>
  <text x="128" y="134" fill="{$background}" font-size="24" font-family="Manrope, Arial, sans-serif" font-weight="800" letter-spacing="4">AUTOIQ {$number}</text>
  <text x="96" y="250" fill="{$accent}" font-size="28" font-family="Manrope, Arial, sans-serif" font-weight="800" letter-spacing="8">{$category}</text>
  <foreignObject x="92" y="292" width="1120" height="280">
    <div xmlns="http://www.w3.org/1999/xhtml" style="font-family: Manrope, Arial, sans-serif; font-size: 78px; line-height: 1.02; font-weight: 850; color: {$text}; letter-spacing: 0;">
      {$title}
    </div>
  </foreignObject>
  <foreignObject x="96" y="640" width="950" height="130">
    <div xmlns="http://www.w3.org/1999/xhtml" style="font-family: Manrope, Arial, sans-serif; font-size: 30px; line-height: 1.45; color: {$text}; opacity: .82;">
      {$excerpt}
    </div>
  </foreignObject>
  <circle cx="1320" cy="625" r="150" fill="none" stroke="{$accent}" stroke-width="2" opacity="0.5"/>
  <circle cx="1320" cy="625" r="92" fill="{$accent}" opacity="0.16"/>
</svg>
SVG;
    }

    protected function svgText(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
