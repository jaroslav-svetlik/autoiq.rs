<?php

// Reviewed source and intent notes: storage/app/editorial/private/2026-10-22-run-manifest.md
$comparison = <<<'TEXT'
Honda Jazz druge generacije 1.4 i-VTEC i Suzuki Splash 1.2 nisu zamene samo zato što oba izgledaju kao kratki gradski automobili. Ovo poređenje važi za benzince sa ručnim menjačem; VIN, saobraćajna i računi moraju potvrditi tačan motor i opremu. Jazz je zanimljiv kupcu kome raspored kabine i preklapanje sedišta menjaju svakodnevicu, dok Splash više odgovara kada je mala spoljašnja mera važnija od teretne fleksibilnosti.

## Pogon se proverava na konkretnom primerku

Dogovori hladan start, poslušaj ler nakon zagrevanja i promeni sve brzine tokom normalne vožnje. Ni jedan atmosferski benzinac ne treba oceniti po obećanju iz oglasa. Dim, lampica, curenje, preskakanje ili neobičan zvuk nisu osobina grada nego razlog za očitavanje grešaka u nezavisnom servisu.

Računi treba da prate broj šasije, datume i kilometražu. Savet za automatik, dizel ili drugi motor ne prenosi se na ručni benzinac. Ako postoji prekid u istoriji, kupac ga ne popunjava pretpostavkom, već pregledom i početnim budžetom.

## Kabina rešava dva različita zadatka

U Jazzu namesti sedište, sedi iza vozača i zaista probaj sklapanje zadnjih sedišta bez sile. Proveri gepek, podnicu, kopče, pojaseve i da li teret koji se svakodnevno vozi stvarno staje. Njegova prednost nije teorijska ako se mehanizmi zaglavljuju ili je enterijer vlažan.

Kod Splasha proveri ulazak, položaj za volanom, preglednost pri parkiranju i prostor za putnike. Kratka karoserija može biti praktična u gradu, ali ne pretvara automobil u drugi tip vozila kada su zadnja klupa i prtljag stalno puni.

## Podvozje čita oglas iz druge perspektive

Na dizalici pogledaj pragove, tačke dizanja, manžetne, kočione vodove, izduv i tragove curenja. Gume, felne i obrazac habanja posmatraj zajedno. Merenje laka i datumi stakala daju kontekst, ali ne zamenjuju pisani nalaz za kočnice i oslanjanje.

Isprobaj klimu, ventilator, prozore, brave, svetla, brisače i oba ključa. Naziv paketa opreme nije dokaz rada, naročito kod uvezenog vozila ili naknadno ugrađenog ekrana.

## Preporuka zavisi od namene

Jazz ima prednost kada njegova kabina rešava stvarni porodični ili teretni zadatak, a Splash kada su gradske dimenzije i potvrđeno stanje važniji od većeg unutrašnjeg trika. Oba kandidata zaslužuju isti redosled: dokumenti, hladan start, normalna vožnja, kabina i dizalica.

Izaberi primerak sa jasnim identitetom, suvim enterijerom, funkcionalnom opremom i dozvoljenim nezavisnim pregledom. Ako prodavac ograničava pregled ili važan račun nema vezu s vozilom, nijedna praktična osobina nije dovoljna nadoknada za nepoznat rizik.
TEXT;

$guide = function (string $car, string $engine, string $purpose): string {
    return "$car sa $engine treba kupovati kao tačno određenu izvedbu, ne kao opštu reputaciju marke. $purpose VIN, saobraćajna i računi moraju potvrditi motor, menjač i godište, jer oznake u oglasima često obuhvataju različite verzije.\n\n## Motor i menjač prvo\n\nDogovori hladan start. Posmatraj paljenje, miran ler po zagrevanju, odziv na gas i poruke na instrument tabli. Tokom normalne vožnje proveri kvačilo i sve stepene prenosa bez forsiranja. Dim, lampica, curenje, vibracija ili otpor ručice nisu stvar za procenu na parkingu: zabeleži simptom i traži dijagnostiku.\n\nRačuni za ulje, svećice, tečnosti i prethodne radove vrede kada pripadaju konkretnom vozilu. Lanac, atmosferski motor ili uredan poklopac motora nisu dozvola da se preskoči istorija. Prekid u dokumentaciji je stavka za pregled i realan početni budžet.\n\n## Kabina mora opravdati izbor\n\nNamesti sedište za vozača, zatim sedi iza njega i proveri ulazak, prostor za kolena, pojaseve i preglednost. Ponesi dečje sedište ili torbu koja se stvarno vozi. Otvori gepek, podigni podnicu i pogledaj vlagu, alat, obloge i zatvaranje vrata.\n\nIsprobaj klimu, ventilator, prozore, brave, radio, svetla, brisače i oba ključa. Oprema zavisi od tržišta i paketa; ekran ili kamera mogu biti naknadni dodatak, pa naziv oglasa nije dokaz funkcije.\n\n## Karoserija i podvozje bez pretpostavki\n\nNa dizalici proveri pragove, tačke dizanja, donje zaštite, manžetne, izduv, kočione vodove i tragove curenja. Gume, felne i geometrija čitaju se zajedno. Jedna nova guma ili prefarban branik nisu presuda, ali zaslužuju objašnjenje i pisani nalaz.\n\nMerenje laka, datumi stakala i zazori pomažu da se razume istorija karoserije. Ne testiraj bezbednosne sisteme stvaranjem opasne situacije; aktivnu lampicu i nejasnu funkciju prepusti servisu pre kapare.\n\n## Preporuka za provereni primerak\n\nOva izvedba ima smisla samo kada potvrđeni pogon, hladan start, kabina i istorija odgovaraju stvarnoj ruti kupca. Kupac kome treba drugačiji odziv, automatik ili više prostora treba da bira takvu verziju, ne da očekuje da naziv modela promeni kompromis.\n\nPrednost ima primerak koji dozvoljava isti redosled pregleda i ostavlja najmanje neobjašnjenih pitanja. Ako važan nalaz ostane bez uzroka ili prodavac ne dozvoli pregled, bolje je sačekati sledeći oglas nego kupiti obećanje.";
};

$inspection = <<<'TEXT'
Proklizavanje kvačila u polovnom automobilu nije dijagnoza koju treba postaviti po jednoj rečenici prodavca. Simptom može biti promena odnosa obrtaja i ubrzanja, miris ili drugačiji osećaj papučice, ali uzrok i hitnost traže pregled. Kupac ne treba da izvodi grube probe niti da na cesti pokušava da proizvede kvar.

## Zabeleži simptom bez nasilnog testa

Na normalnoj vožnji obrati pažnju da li se broj obrtaja menja nesrazmerno brzini i da li se pojava ponavlja pod sličnim opterećenjem. Zapiši brzinu, stepen prenosa, temperaturu i lampice. To je korisna informacija za servis, a nije dokaz koji zamenjuje pregled.

Ne drži automobil na kvačilu, ne pokušavaj nagla kretanja i ne testiraj granice uspona. Takvi postupci mogu pogoršati stanje i ne daju pouzdan nalaz.

## Dokumenti daju kontekst, ne presudu

Pitaj da li je kvačilo menjano i zatraži račun sa datumom, kilometražom i identitetom vozila. Račun bez veze sa brojem šasije nije potvrda, ali je pitanje za servis. Razdvoji dokumentovan redovan rad od neobjašnjenog simptoma.

Pogledaj i da li postoje curenja, miris paljevine ili druge promene koje zahtevaju hitnu procenu. Nemoj cenu kompleta sa interneta pretvarati u zaključak o konkretnom automobilu.

## Servis pretvara opažanje u odluku

Nezavisan servis može proceniti pogon, hidraulični sistem, stanje kvačila i druge moguće uzroke bez nagađanja. Traži pisani nalaz koji razlikuje viđeno, preporučen dalji korak i hitnost. Ako je potreban rad, ponuda treba da se odnosi na konkretan VIN.

Kupovina ima smisla samo kada je nalaz objašnjen i uračunat u početni budžet. Ako se pregled odbija, simptom je nejasan ili bezbednost nije potvrđena, odloži odluku. Otvoren nalaz je vredniji od obećanja da je osećaj „normalan za taj model“.
TEXT;

$market = <<<'TEXT'
Oglas koji naglašava „prvog vlasnika“ može biti koristan početak, ali nije sam dokaz uredne istorije polovnog automobila. Kupac treba da traži identitet vozila, sled dokumenata i pregled stanja, umesto da jednu marketinšku oznaku pretvori u vrednost.

## Vlasništvo je trag, ne kompletna slika

Saobraćajna, broj šasije i računi mogu pokazati kontinuitet koji vredi proveriti. Pitaj koji dokument pripada kom radu i da li datumi i kilometraža imaju smislen sled. Jedan vlasnik ne garantuje režim održavanja, kao što više vlasnika ne dokazuje problem.

Sačuvaj oglas i zapiši šta je prodavac rekao pre pregleda. Tako se kasnije porede činjenice, a ne utisak sa prvog razgovora.

## Stanje određuje prvi budžet

Primeni istu listu na svaki kandidat: hladan start, lampice, menjač, klima, oba ključa, gepek, gume i podvozje. Niža cena nije povoljnija ako joj nedostaje objašnjenje za servis ili nezavisan pregled.

Poznati potrošni deo sa pisanom ponudom nije isto što i neobjašnjena lampica ili prekid istorije. Ne izmišljaj prosečnu cenu kvara radi pregovora; servis treba da proceni konkretan automobil.

## Pregovor dolazi nakon nalaza

Na dizalici servis može pogledati kočnice, manžetne, curenja i oslanjanje. Traži nalaz koji razlikuje bezbednosnu stavku, preporuku i otvoreno pitanje. Uredna dokumentacija pomaže, ali ne ukida potrebu za tim pregledom.

Najbolji tržišni izbor nije etiketa vlasništva već primerak sa jasnim identitetom, proverljivim tragom i najmanje nepoznatih stavki. Ako prodavac dozvoljava pregled i dokumenti prate vozilo, razgovor o ceni postaje racionalan. Ako ne, sačekaj drugi oglas.
TEXT;

$longer = <<<'TEXT'

Probna vožnja služi za beleške.

Vozi rutu na kojoj se normalno menjaju brzine, skreće, koči i prelazi preko neravnine. Ne traži maksimalne performanse. Obrati pažnju na položaj tela, preglednost, zvuke, ponašanje volana i kočnice, zatim zapiši kada se nešto pojavilo. Takva beleška pomaže servisu da razlikuje opažanje od nagađanja.

Posle vožnje pogledaj motorni prostor i pod automobila samo sa bezbedne udaljenosti. Ne diraj vruće delove i ne pokušavaj samostalnu popravku. Ako se pojavi curenje, miris ili lampica, dogovori pregled pre kapare. Prodavčevo objašnjenje može biti koristan kontekst, ali ne zamenjuje nalaz.

Dokumentovana odluka je jeftinija.

Uporedi nalaz sa onim što je oglas obećao. Za svaku stavku zapiši da li je potvrđena, da li zahteva ponudu ili ostaje otvorena. Tako se ne mešaju potrošni delovi sa rizikom čiji se uzrok ne zna. Kupac tada procenjuje početni budžet bez izmišljanja cena i bez oslanjanja na tuđe iskustvo sa drugim automobilom.

Ako se dve ponude razlikuju, ne odlučuje samo godina, boja ili broj dodataka. Prednost dobija jasnija istorija, dozvoljen pregled i automobil koji odgovara stvarnoj ruti. Pisani nalaz ostavlja prostor za razumnu odluku, dok pritisak da se pregleda ne dozvoli govori da treba stati.
TEXT;

$shorter = <<<'TEXT'

## Razdvoji činjenice od pretpostavki

Fotografija, uredan enterijer ili siguran nastup prodavca mogu ostaviti dobar utisak, ali ne menjaju ono što dokumenti i pregled pokažu. Sačuvaj oglase kandidata i poredi ih po istoj listi. Tako jedna oznaka ili nov deo ne dobija veću težinu od identiteta vozila, servisnog traga i bezbednosti.

Kada servis pronađe stavku, traži da objasni šta je viđeno, šta je preporuka i šta zahteva hitnu odluku. Ta podela je bolja od opšte priče o reputaciji modela. Ako ostane otvoreno pitanje, mora ostati u budžetu ili biti razjašnjeno pre kupovine.

Kupovina ostaje mirna samo kada kupac može da objasni zašto bira baš taj primerak. Svaku neizvesnost stavi u belešku i ne dozvoli da rok oglasa ili tuđe mišljenje zamene proveru.
TEXT;

$more = <<<'TEXT'

## Odluka posle pregleda

Na kraju uporedi beleške bez preskakanja neprijatnih stavki. Da li je motor potvrđen dokumentima, da li je start bio uredan, da li sva oprema radi i da li je pregled obavljen na dizalici? Odgovori moraju pripadati baš tom automobilu, ne sličnom oglasu ili obećanju da će se nešto proveriti kasnije.

Kupac koji planira porodičnu upotrebu treba da ponovi proveru sa stvarnim putnicima i teretom. Kupac koji pretežno vozi grad treba da proveri parking, preglednost i ventilaciju u uslovima koje poznaje. Ako pregled pokaže početni posao, servisu prepusti da odredi redosled i hitnost.

Jasno obrazložen provereni primerak vredi više od auta koji deluje povoljno samo dok se pitanja ne postave. Svaka nepoznata stavka mora imati mesto u budžetu ili biti razjašnjena pre kupovine.

Kupac zato ne treba da bira između osećaja, papira i nezavisnog stručnog pisanog potpunog nalaza. Dobra odluka povezuje dokumente, opažanje na vožnji i servisnu proveru. Kada se ta tri izvora slažu, izbor je mirniji; kada se sukobe, razjašnjenje ima prednost nad žurbom.
TEXT;

$comparison .= "\n";
$longer = "\n".$longer."\n";
$shorter .= "\n";
$more .= "\n";

return [
 ['title'=>'Honda Jazz 1.4 ili Suzuki Splash 1.2: dva mala auta, dve namene','slug'=>'honda-jazz-ge-suzuki-splash-polovni-poredjenje','category'=>'Poređenje modela','excerpt'=>'Jazz i Splash porede se kao ručni benzinski gradski automobili, uz proveru kabine i stanja.','content'=>$comparison.$longer.$more,'highlights'=>['Kabina određuje različitu namenu.','Dokumenti potvrđuju izvedbu.','Isti pregled važi za oba kandidata.'],'tags'=>['Honda Jazz GE','Suzuki Splash','polovni auto'],'meta_title'=>'Honda Jazz ili Suzuki Splash: polovni vodič','meta_description'=>'Poređenje Hondinog Jazza i Suzukijevog Splasha za kupovinu polovnog auta.','palette'=>['#334155','#e2e8f0','#0f172a']],
 ['title'=>'Renault Mégane III 1.6 16V ručni: vodič za polovan primerak','slug'=>'renault-megane-iii-16-16v-rucni-polovni-vodic','category'=>'Kupovina polovnjaka','excerpt'=>'Kako proveriti Mégane III 1.6 16V sa ručnim menjačem pre kupovine.','content'=>$guide('Renault Mégane III 1.6 16V ručni','atmosferskim benzincem','Pogodan je samo ako kompaktnu kabinu i njegov karakter kupac zaista traži.').$longer.$more.$more,'highlights'=>['Potvrdi tačan pogon.','Probaj kabinu i opremu.','Traži pisani nalaz.'],'tags'=>['Renault Mégane III','1.6 16V','polovni auto'],'meta_title'=>'Renault Mégane III 1.6: polovni vodič','meta_description'=>'Provera Renaulta Mégane III 1.6 pre kupovine polovnog auta.','palette'=>['#1e3a5f','#dbeafe','#0f172a']],
 ['title'=>'Hyundai ix20 1.4 ručni: praktičnost koja mora proći pregled','slug'=>'hyundai-ix20-14-rucni-polovni-vodic','category'=>'Kupovina polovnjaka','excerpt'=>'Hyundai ix20 1.4 ručni bira se po potvrđenom pogonu, kabini i stanju primerka.','content'=>$guide('Hyundai ix20 1.4 ručni','benzinskim motorom','Povišena kabina ima vrednost kada odgovara putnicima i teretu konkretnog domaćinstva.').$longer.$more.$more,'highlights'=>['VIN ima prednost nad oglasom.','Kabina se proverava stvarnim teretom.','Podvozje traži dizalicu.'],'tags'=>['Hyundai ix20','1.4','polovni auto'],'meta_title'=>'Hyundai ix20 1.4 ručni: polovni vodič','meta_description'=>'Kako proveriti Hyundai ix20 1.4 sa ručnim menjačem pre kupovine.','palette'=>['#475569','#f1f5f4','#1e293b']],
 ['title'=>'Proklizavanje kvačila: kako proveriti polovan auto bez rizičnog testa','slug'=>'proklizavanje-kvacila-provera-polovni-auto','category'=>'Provera vozila','excerpt'=>'Simptom kvačila treba zabeležiti i proveriti u servisu, bez grubih proba.','content'=>$inspection."\n".$shorter."\n".$more,'highlights'=>['Ne izazivaj kvar probom.','Račun daje kontekst.','Pisani nalaz određuje odluku.'],'tags'=>['kvačilo','provera vozila','polovni auto'],'meta_title'=>'Proklizavanje kvačila: provera polovnog auta','meta_description'=>'Bezbedan redosled provere simptoma kvačila kod polovnog automobila.','palette'=>['#3f3f46','#e4e4e7','#18181b']],
 ['title'=>'„Prvi vlasnik“ u oglasu: koliko ta oznaka stvarno vredi kupcu','slug'=>'prvi-vlasnik-oglas-vrednost-polovni-auto','category'=>'Analiza tržišta','excerpt'=>'Oznaka prvog vlasnika vredi tek uz proverljive dokumente i nezavisan pregled.','content'=>$market."\n".$shorter."\n".$more,'highlights'=>['Vlasništvo nije dokaz stanja.','Ista lista čini cenu uporedivom.','Pregled prethodi pregovoru.'],'tags'=>['tržište polovnjaka','prvi vlasnik','polovni auto'],'meta_title'=>'Prvi vlasnik u oglasu: vrednost polovnog auta','meta_description'=>'Šta oznaka prvog vlasnika stvarno govori pri kupovini polovnog auta.','palette'=>['#44403c','#f5f5f4','#1c1917']],
];
