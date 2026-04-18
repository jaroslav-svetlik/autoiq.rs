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

            Storage::disk('public')->put($path, $this->coverSvg($blogPost, $palette, $index));

            $blogPost->forceFill([
                'cover_image_path' => $path,
                'cover_image_alt' => $blogPost->title,
            ])->saveQuietly();
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
        ];
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
