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
