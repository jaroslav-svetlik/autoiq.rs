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

            $blogPost = BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post,
            )->fresh();

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
        return [
            [
                'title' => 'Golf 7 ili Audi A3: šta je pametnija kupovina u Srbiji',
                'slug' => 'golf-7-ili-audi-a3-sta-je-pametnija-kupovina-u-srbiji',
                'category' => 'Poređenje modela',
                'author_name' => 'AutoIQ redakcija',
                'excerpt' => 'Dva najčešća izbora za kupce koji žele nemački kompakt, ali ne žele da plate grešku kroz održavanje, kilometražu ili slabiju kasniju prodaju.',
                'content' => <<<'TEXT'
Golf 7 i Audi A3 često ulaze u isti uži izbor jer dele sličnu tehničku osnovu, imaju poznate motore i drže cenu bolje od većine kompaktnih automobila. Razlika je u tome šta kupac zaista želi da plati. Golf je racionalniji izbor kada tražiš jednostavniju kupovinu, više primeraka na tržištu i širi izbor delova. Audi A3 donosi bolji osećaj u kabini i jači premium imidž, ali svaki loš primerak taj imidž brzo pretvori u skuplje održavanje.

Kod Golfa 7 najviše smisla imaju automobili sa jasnom servisnom istorijom i realnom kilometražom. Zbog ogromne potražnje ima mnogo oglasa, ali upravo zato ima i velikih razlika između prosečnog i dobrog primerka. Dva Golfa istog godišta mogu delovati slično na fotografijama, a da jedan ima uredan servis menjača, kvačila i dizni, dok drugi samo čeka prvog vlasnika koji će platiti zaostala ulaganja.

Audi A3 treba gledati strože. Kupci ga često biraju jer žele bolji enterijer, bolju izolaciju i osećaj skupljeg automobila, ali kod polovnog A3 taj osećaj vredi samo ako je auto održavan bez preskakanja. Posebno proveri automatski menjač, tragove gradske vožnje, stanje enterijera i da li kilometraža odgovara potrošenosti volana, sedišta i pedala.

Ako kupuješ dizel, ne gledaj samo potrošnju. Kod oba modela treba proveriti DPF, EGR, turbinu i servisni ritam. Dizel ima najviše smisla za otvoren put i veću godišnju kilometražu. Ako uglavnom voziš grad, benzinac može biti mirnija odluka, čak i kada troši malo više, jer skupi dizel kvar lako pojede razliku u potrošnji.

Golf 7 je bolji izbor kada hoćeš najlikvidniji polovnjak, lakšu kasniju prodaju i više prostora za poređenje cena. Audi A3 je bolji kada želiš kompaktniji premium osećaj i spreman si da platiš bolji primerak, ne samo oznaku na haubi. Ako su cena, godište i kilometraža slični, prednost daj automobilu sa boljom dokumentacijom, a ne automobilu sa boljim znakom.

Najpametnija kupovina je često dobar Golf umesto prosečnog Audija. Ali ako nađeš A3 sa proverljivom istorijom, korektnom kilometražom i bez tragova zapuštenog održavanja, razlika u ceni može imati smisla. U oba slučaja, pre pregleda napravi listu uporedivih oglasa i ne dozvoli da te oprema ili fotografije odvoje od realnog stanja automobila.
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

Najveća prednost Yarisa Hybrid je jednostavna svakodnevica. Hibridni sistem pomaže u gradskoj vožnji, automatski prenos je prijatan i auto ne traži dizel režim vožnje da bi ostao zdrav. Za vozača koji najviše prelazi kratke gradske relacije, to je često mirnija kupovina od malog dizela sa DPF-om i EGR-om.

Ipak, hibrid ne znači da nema provere. Pre kupovine treba proveriti stanje hibridne baterije, servisnu istoriju, rad klima uređaja, stanje kočnica i trap. Hibridi često manje troše klasične kočnice zbog regeneracije, ali to ne znači da diskovi, čeljusti i ležajevi ne mogu biti zapušteni. Auto koji je dugo stajao ili je vožen samo kratko takođe može imati svoje tragove.

Posebno obrati pažnju na poreklo i realnu kilometražu. Yaris Hybrid je često radio kao gradski auto, službeno vozilo ili vozilo za dostavu u nekim tržištima. Takav primerak ne mora biti loš ako je održavan, ali kabina, sedišta, volan i vrata treba da odgovaraju priči prodavca. Ako oglas tvrdi da je auto malo vožen, stanje enterijera mora to da potvrdi.

Cena je često najveći izazov. Kupci ponekad plate previše samo zato što piše Hybrid i Toyota. Dobar Yaris vredi više od prosečnog malog automobila, ali samo ako istorija i stanje opravdavaju cenu. Ako je razlika u ceni velika, uporedi ga sa benzinskim Yarisom, Hondom Jazz ili drugim manjim gradskim modelima.

Yaris Hybrid je najbolji za kupca koji želi mali, pouzdan i štedljiv gradski auto i spreman je da plati proverljiv primerak. Nije najbolji izbor za nekoga kome treba veliki gepek, česta otvorena putovanja ili najniža moguća kupovna cena. Kod ovog modela pametna kupovina je mirna istorija, ne samo mala potrošnja.
TEXT,
                'highlights' => [
                    'Yaris Hybrid ima najviše smisla za gradsku vožnju i kratke relacije.',
                    'Pre kupovine obavezno proveri hibridnu bateriju, servisnu istoriju, kočnice i realnu namenu prethodnog korišćenja.',
                    'Dobra reputacija ne opravdava svaku cenu; uporedi stanje, ne samo oznaku Hybrid.',
                ],
                'tags' => ['Toyota Yaris Hybrid', 'hibrid', 'gradski auto', 'kupovina polovnjaka'],
                'meta_title' => 'Polovni Toyota Yaris Hybrid: šta proveriti',
                'meta_description' => 'Vodič za kupovinu polovnog Toyota Yaris Hybrid: baterija, gradska vožnja, servisna istorija, kočnice, cena i realna isplativost.',
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

Prvo pitanje je servisna istorija menjača. Rečenica da je ulje "doživotno" ne znači mnogo kada kupuješ polovan auto sa godinama i kilometražom. Traži račun ili dokaz da je servis menjača rađen u preporučenom intervalu. Ako prodavac kaže da nema potrebe za servisom, to nije automatski dokaz problema, ali jeste razlog za dodatnu proveru.

Probna vožnja mora biti hladna i topla. Hladan start često pokaže trzaje, kašnjenje pri ubacivanju u D ili R i nepravilan rad koji nestane kada se sistem zagreje. Tokom vožnje obrati pažnju na glatko menjanje brzina, proklizavanje, vibracije, udarce pri usporavanju i ponašanje u gužvi. Ne testira se samo ubrzanje, nego i normalna svakodnevna vožnja.

Različiti tipovi automatika imaju različite rizike. Klasični automatik, DSG, CVT i robotizovani menjači ne ponašaju se isto i ne koštaju isto za održavanje. Zato nije dovoljno da oglas kaže "automatik". Treba znati koji je tačno menjač u automobilu, šta je njegov tipičan problem i koliko košta servis u Srbiji.

Ako auto vuče prikolicu, često se vozi u gradu ili ima mnogo snage, opterećenje menjača može biti veće. Isto važi za automobile koji su čipovani ili voženi agresivno. Menjač može raditi korektno na kratkoj vožnji, ali dijagnostika i pregled ulja mogu otkriti tragove koje prodavac ne pominje.

Automatik nije razlog da odustaneš od dobrog auta. Naprotiv, dobar menjač može učiniti svakodnevnu vožnju mnogo prijatnijom. Ali ako nema dokaza o održavanju, ako probna vožnja pokazuje trzaje ili ako cena deluje predobro, računaj menjač kao ozbiljan rizik u pregovorima.
TEXT,
                'highlights' => [
                    'Servisna istorija menjača je važnija od tvrdnje da je ulje doživotno.',
                    'Probna vožnja treba da proveri hladan start, gužvu, usporavanje i ubacivanje u D/R.',
                    'Različiti automatici imaju različite rizike, pa proveri tačan tip menjača pre kupovine.',
                ],
                'tags' => ['automatski menjač', 'DSG', 'CVT', 'probna vožnja'],
                'meta_title' => 'Automatski menjač kod polovnjaka: vodič za proveru',
                'meta_description' => 'Kako proveriti automatski menjač pre kupovine polovnog automobila: servis ulja, probna vožnja, trzaji, DSG, CVT i troškovi.',
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

Kuga ima prednost kada ti je prostor visoko na listi. Duže međuosovinsko rastojanje, veći osećaj automobila i klizna zadnja klupa čine je praktičnijom za porodicu koja često putuje, nosi kolica, torbe ili dečja sedišta. Kod polovnih primeraka 2022-2023 najviše pažnje zaslužuju hibridne verzije, posebno 2.5 FHEV i PHEV. Plug-in Kuga može biti odlična ako je prethodni vlasnik stvarno punio bateriju i ako ti imaš gde da puniš, ali nema smisla platiti PHEV cenu ako će auto stalno raditi kao težak benzinac.

Qashqai je bolji izbor ako ti treba kompaktniji SUV za grad i prigradsku vožnju. Treća generacija donela je moderniji enterijer, dobru bezbednosnu opremu i izbor između 1.3 mild hybrid motora i e-POWER pogona. e-POWER je posebno zanimljiv jer se vozi kao električni auto, ali se ne puni na utičnici. Benzinski motor uglavnom služi kao generator, a to znači mirniji osećaj u gradu i dobar odziv, ali kupac mora da razume da to nije plug-in hibrid i da nema isti električni domet kao Kuga PHEV.

Kod poređenja potrošnje ne gledaj samo fabričke brojke. Kuga PHEV može biti izuzetno štedljiva ako se redovno puni i vozi kratke relacije, ali na autoputu i bez punjenja prednost se smanjuje. Kuga FHEV je jednostavnija za kupca koji ne želi kabl i punjenje. Qashqai e-POWER je prijatan za grad, ali na bržem otvorenom putu treba proveriti realnu potrošnju, buku i ponašanje sistema. Običan 1.3 mild hybrid Qashqai je najjednostavniji za razumevanje, ali nije tako poseban u vožnji kao e-POWER.

Bezbednost je jaka strana oba modela, ali proveri konkretan paket opreme. Qashqai treće generacije ima veoma dobre Euro NCAP rezultate iz 2021. godine, dok je aktuelna generacija Kuge ocenjena sa pet zvezdica u ranijem testu. Ipak, kod polovnjaka nije dovoljno da model ima dobar rezultat. Bitno je da konkretan auto nema ozbiljna oštećenja, da su radari i kamere ispravni i da posle eventualne popravke sistemi asistencije rade kako treba.

Troškovi mogu prelomiti odluku. Kuga je veća i često skuplja za gume, osiguranje, potrošnju i eventualne hibridne provere. Qashqai je kompaktniji i lakši za svakodnevicu, ali e-POWER primerci mogu držati višu cenu zbog tehnologije i novijeg imidža. Ako kupuješ uvoz iz EU, obavezno proveri VIN, servisnu istoriju, stanje baterije kod hibrida, rad menjača ili električnog pogona, tragove oštećenja i da li oprema iz oglasa stvarno postoji na automobilu.

Kuga je pametnija kupovina za porodicu kojoj prostor, gepek, udobnost i mogućnost plug-in vožnje stvarno znače. Qashqai je pametniji za kupca koji želi noviji, pregledan i lakši SUV za grad, uz moderan hibridni osećaj kod e-POWER verzije. Ako su oba automobila iste cene, nemoj birati po znački ili fotografijama. Biraj onaj primerak koji ima jasniju istoriju, manje nepoznanica i pogon koji odgovara tvojoj svakodnevici.
TEXT,
                'highlights' => [
                    'Kuga je bolji izbor za porodicu, duže relacije i kupce koji stvarno mogu da koriste PHEV punjenje.',
                    'Qashqai je lakši za grad, posebno kao 1.3 mild hybrid ili e-POWER za vozače koji žele mirniji hibridni osećaj bez punjenja na utičnici.',
                    'Kod oba modela proveri VIN, servisnu istoriju, hibridni sistem, rad asistencija i tragove oštećenja pre kapare.',
                ],
                'tags' => ['Ford Kuga', 'Nissan Qashqai', 'SUV 2022', 'SUV 2023', 'hibrid'],
                'meta_title' => 'Ford Kuga ili Nissan Qashqai 2022-2023: poređenje',
                'meta_description' => 'Poređenje Ford Kuga i Nissan Qashqai 2022-2023 polovnjaka: prostor, PHEV, FHEV, e-POWER, potrošnja, oprema i šta proveriti pre kupovine.',
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
                'meta_title' => 'Peugeot 2008 ili Renault Captur: polovni crossover',
                'meta_description' => 'Poređenje polovnih Peugeot 2008 i Renault Captur modela: grad, porodica, motori, elektronika, gepek i praktičnost.',
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
