<?php

// Reviewed source notes: storage/app/editorial/private/2026-11-07-batch-manifest.md
$finish = static function (string $identity): string {
    return <<<TEXT

## Dokumenti i odluka pre kapare

$identity Račun ima težinu samo kada povezuje VIN, datum, kilometražu i stvarno izveden rad. Jedna uredna potvrda nije potpuna istorija, ali niz doslednih zapisa omogućava da se jasno odvoje redovno održavanje, potvrđeni popravci i pitanja bez odgovora. Broj ključeva, datum guma, poslednji račun za redovan servis i stvarno stanje rezervnog točka ili kompleta za popravku proveravaju se na konkretnom automobilu. Stavka koja nije mogla biti potvrđena ostaje nepoznata, ne postaje ni kvar ni prednost.

Nezavisna radionica treba da koristi isti redosled za svaki kandidat: identitet, hladan start, vožnja, karoserija, dizalica i dokumentacija. Pisani nalaz odvaja bezbednosne stavke od potrošnih delova i dodatne dijagnostike. Tako dva oglasa ostaju uporediva i kada imaju različitu boju, opremu ili naslovnu cenu. Nema potrebe da se deo rastavlja ili da se kvar proglašava na parkingu. Dovoljno je zabeležiti tačan simptom i okolnost u kojoj se pojavio, kako bi servis mogao da nastavi pregled.

Posle vožnje proveravaju se instrumenti, zatvaranje vrata i gepeka, svetla, ventilator, klima, brisači, podizači i zaključavanje koje automobil zaista ima. Oprema nije ista na svakom tržištu ni u svakom paketu, pa fotografija drugog primerka nije dokaz. Na dizalici se zatim proveravaju pragovi, tačke dizanja, kočioni vodovi, manžetne, opruge, amortizeri, izduv, donje zaštite i vidljivi tragovi tečnosti. Gume, felne i geometrija posmatraju se zajedno.

Nastavak ima smisla kada identitet, dokumenti, hladan start i nalaz radionice pričaju istu priču. Jasno opisane potrošne stavke mogu biti osnova za predračun i pregovor. Odbijen VIN, hladan start ili pregled na dizalici imaju veću težinu od niske cene, jer sprečavaju proveru onoga što se kupuje. Razumna odluka je zato kupovina proverljivog primerka, pregovor zasnovan na nalazu ili odustajanje bez žurbe.

Beleške sa pregleda čuvaju isti kriterijum i kada je jedan oglas privlačniji bojom, lokacijom ili kratkim obećanjem o hitnoj prodaji. Uredan postupak ne traži savršenstvo, već jasnu granicu između potvrđenog, potrošenog i neproverenog. Time se početni budžet zasniva na činjenicama koje se mogu pokazati, a ne na pretpostavci da će model ili prethodni utisak rešiti otvoreno pitanje.
TEXT;
};

$comparison = <<<'TEXT'
Volkswagen Golf VII 1.2 TSI i Kia cee'd JD 1.4 CVVT mogu delovati kao slični benzinski hečbekovi, ali se porede samo tačne ručne evropske izvedbe. Golfov 1.2 TSI ima 1.197 cm3, 77 kW odnosno 105 KS, 175 Nm i šestostepeni ručni menjač. Kia 1.4 CVVT ima 1.368 cm3, 73,3 kW odnosno 100 KS, 134 Nm i šestostepeni ručni menjač. Dizel, DSG, automatik, 1.0 T-GDi i 1.4 T-GDi nisu predmet ovog poređenja. VIN i saobraćajna dozvola prvo potvrđuju da oglas zaista odgovara ovim činjenicama.

## Motor i šest brzina traže odvojene beleške

Pregled počinje dok je motor hladan. Gledaju se nivo ulja i rashladne tečnosti, vidljivi spojevi, lampice i miran ler. Na bezbednoj ruti prati se ravnomeran odziv na gas, bez zaključivanja unapred o uzroku zvuka, dima ili vibracije. Kod Golfa se beleži ponašanje turbomotora kroz umereno ubrzanje, a kod cee'da odziv atmosferskog 1.4 pri istim uslovima. Ručica oba šestostepena menjača mora uredno birati sve stepene pri polasku, međuubrzanju i usporavanju. Kvačilo se ne ocenjuje samo na parkingu.

## Kabina rešava različite dnevne zadatke

Položaj sedenja, prostor u drugom redu, otvor vrata i oblik gepeka proveravaju se stvarnom upotrebom, ne opisom iz oglasa. Kod oba automobila podiže se podnica prtljažnika radi vlage, alata i stanja obloga. Zatim se proveravaju pojasevi, ventilator kroz sve brzine, klima, podizači, brisači, zaključavanje i svetla. Habanje volana, pedala ili sedišta daje kontekst korišćenja, ali bez VIN-zapisa i računa ne dokazuje kilometražu. Različiti paketi opreme zahtevaju da se svaka funkcija proveri u radu.

## Karoserija i podvozje menjaju vrednost oglasa

Na dnevnom svetlu gledaju se zazori panela, ton laka, datumi stakala, rubovi vrata i gepek. Popravljen panel nije automatski razlog za odustajanje, ali zaslužuje objašnjenje i merenje laka. Na dizalici se proveravaju pod, pragovi, tačke dizanja, manžetne, opruge, amortizeri, kočioni vodovi, izduv i tragovi tečnosti. Neravnomerna šara guma nije dijagnoza, ali je razlog da se proveri felna i geometrija. Prednost u poređenju dobija pregledan primerak, ne oznaka na poklopcu motora.

## Preporuka prati rutu i dokaze

Golf ima smisla kada potvrđeni 1.2 TSI, šest brzina i servisna istorija odgovaraju potrebama rute. Cee'd ima smisla kada 1.4 CVVT, šest brzina i korisna kabina daju jasniji praktični rezultat. Nijedan ne dobija prednost samo zbog reputacije. Kada su nalazi slični, odluka može pratiti prostor i osećaj u vožnji. Kada nisu, dokumentovan automobil sa dozvoljenim pregledom ima prednost nad privlačnijom naslovnom cenom.
TEXT;
$comparison .= "\n\n".$finish('Ovo poređenje važi samo za Golf VII 1.2 TSI 77 kW sa ručnim menjačem i cee\'d JD 1.4 CVVT 100 sa ručnim menjačem.');

$clio = <<<'TEXT'
Renault Clio IV sa benzinskim Energy TCe 90 i petostepenim ručnim menjačem treba kupovati kao tačno određenu izvedbu. Trocilindarski turbo motor ima 898 cm3, 66 kW odnosno 90 KS i ručni menjač sa pet brzina. To nije Clio 1.2 16V, 1.2 TCe, dizel, EDC niti novija generacija. VIN, saobraćajna dozvola i računi moraju potvrditi motor i prenos pre procene opreme, kilometraže ili cene.

## Hladan turbo motor daje početne činjenice

Pre paljenja proveravaju se nivoi tečnosti, vidljivi spojevi, stanje akumulatora i lampice. Hladan rad treba da bude miran, bez dima, jakog mirisa goriva ili aktivnog upozorenja. Na normalnoj ruti prati se ravnomeran odziv na gas i promena ponašanja kada motor dostigne radnu temperaturu. Zvuk, prekid u odzivu ili vibracija beleže se zajedno sa uslovima vožnje za radionicu. Ne određuje se deo za zamenu preko oglasa niti se motor opterećuje nasilnom probom.

## Pet brzina i kvačilo proveravaju se na ruti

Ručica mora proći kroz svih pet stepena pri polasku, umerenoj vožnji, blagom usponu i usporavanju. Kvačilo treba da odvaja predvidljivo bez proklizavanja ili izražene vibracije. Kratak krug bez promene brzina nije dovoljan za zaključak o prenosu. Dokumentacija o ulju, svećicama ili drugim radovima treba da pripada istom VIN-u. Opšta tvrdnja o redovnom servisu nije zamena za datiran račun sa opisom rada.

## Karoserija, kabina i gepek imaju sopstvena pitanja

Proveravaju se zazori panela, rubovi vrata, datumi stakala i ton laka, zatim podnica gepeka, vlaga i zatvaranje petih vrata. U kabini se podešavaju sedište i volan, proveravaju pojasevi, klima, ventilator, podizači, brisači, svetla i zaključavanje. Kod Clia različiti paketi opreme ne dozvoljavaju zaključivanje po drugom automobilu. Svaka funkcija koju konkretan primerak ima mora raditi tokom pregleda, a estetika enterijera ne zamenjuje mehanički nalaz.

## Preporuka za Clio TCe 90 ručni

Nastaviti vredi kada su 898 cm3, 90 KS i petostepeni ručni prenos potvrđeni, kada je hladan start uredan i kada podvozje prođe pregled bez otvorenog bezbednosnog pitanja. Jasno opisani potrošni radovi mogu dobiti predračun. Nejasan identitet motora, odbijen pregled ili promenljiva servisna priča razlog su da se odluka odloži. Ovaj Clio nije zamena za atmosferski motor niti automatik, pa se savet ne prenosi na druge izvedbe.

## Plan sledećeg servisa mora ostati vidljiv

Pre ugovora treba zapisati datum i kilometražu poslednjeg potvrđenog rada, stanje guma i oba ključa. Ako radionica pronađe potrošnu stavku, njen obim se odvaja od pitanja koja zahtevaju dalju dijagnostiku. Tako se ni uredan hladan start ni bogat paket opreme ne koriste kao zamena za plan održavanja. Clio dobija preporuku samo kada se ovaj redosled može završiti bez prečica.
TEXT;
$clio .= "\n\n".$finish('Vodič se odnosi samo na Renault Clio IV Energy TCe 90 od 898 cm3 i 66 kW sa petostepenim ručnim menjačem.');

$fiesta = <<<'TEXT'
Ford Fiesta Mk7 sa benzinskim 1.25 Duratec od 60 kW odnosno 82 KS i petostepenim ručnim menjačem mora se razlikovati od verzije sa 60 KS, trocilindarskih EcoBoost motora, dizela i automatika. Motor ima 1.242 cm3. VIN, saobraćajna dozvola i računi moraju potvrditi baš ovu kombinaciju, jer oznaka 1.25 sama ne govori dovoljno o snazi, opremi ni prenosu.

## Atmosferski motor i menjač prvo se posmatraju hladni

Pre starta pregledaju se tečnosti, spojevi, vidljivi tragovi curenja i lampice. Hladan motor treba da radi ravnomerno bez dima ili upozorenja. Tokom normalne vožnje prati se prihvatanje gasa, ponašanje pri radnoj temperaturi i svaki ponovljiv zvuk. Ručica se proverava kroz svih pet brzina pri polasku, međuubrzanju i usporavanju. Kvačilo se ocenjuje kroz stvarnu vožnju, a ne naglim pokušajem na mestu. Simptom se zapisuje za servis umesto da se proglašava kvarom.

## Gradski tragovi na karoseriji traže kontekst

Branici, felne, pragovi, vrata i unutrašnja plastika često nose tragove gradske upotrebe. Pregledaju se zazori, nijansa laka, stakla, rubovi vrata i gepek, uz merenje laka kada postoji razlog. Popravljen panel može imati razumno objašnjenje, ali ne sme ostati bez njega. U kabini se proveravaju položaj sedenja, pojasevi, ventilator, klima, podizači, brisači, svetla, zaključavanje i audio oprema koju primerak stvarno ima.

## Dizalica odvaja potrošnju od bezbednosti

Na dizalici radionica proverava pragove, pod, tačke dizanja, kočione vodove, opruge, amortizere, manžetne, izduv i tragove tečnosti. Gume se ocenjuju uz felne i geometriju, ne samo po dubini šare. Neravnomerno habanje nije presuda, ali zaslužuje objašnjenje. Pisani nalaz treba da kaže šta je potvrđeno, šta zahteva dodatni pregled i koji radovi mogu da se planiraju, bez obećanja da je automobil savršen.

## Preporuka za Fiestu 1.25 82 ručni

Fiesta ima smisla kada su 1.242 cm3, 82 KS i pet brzina potvrđeni, kada se motor i prenos ponašaju uredno na normalnoj ruti i kada podvozje nema otvorena bezbednosna pitanja. Nije ista kupovina kao EcoBoost ili automatik. Prednost ima primerak sa dostupnim hladnim startom, jasnim računima i dozvolom za nezavisni pregled. Ako se osnovne provere ograniče, niska cena ne popravlja nepoznatu istoriju.

## Sledeći rad mora imati proverljiv razlog

Pre ugovora beleže se datum poslednjeg potvrđenog servisa, stanje guma, oba ključa i funkcija opreme. Kada servis navede potrošnu stavku, treba da objasni njen obim i odnos prema bezbednosti, umesto da se svaki zvuk poveže sa jednom skupom pretpostavkom. Takav nalaz čuva početni budžet od iznenađenja i omogućava da se Fiesta poredi sa drugim kandidatom istim merilom.
TEXT;
$fiesta .= "\n\n".$finish('Vodič se odnosi samo na Ford Fiestu Mk7 1.25 Duratec od 60 kW i 82 KS sa petostepenim ručnim menjačem.');

$inspection = <<<'TEXT'
Pregled ručnog menjača i kvačila polovnog automobila počinje identitetom izvedbe i dokumentima, ne dijagnozom na parkingu. Isti simptom može imati različit kontekst kod benzinca, dizela, petostepenog ili šestostepenog prenosa. VIN, motor, menjač i računi određuju šta radionica zaista proverava. Cilj pregleda je da se zabeleže uslovi u kojima se promena javlja i da se bezbedno organizuje stručni nalaz.

## Pre vožnje proveravaju se osnovne činjenice

Gledaju se nivo tečnosti kada je to bezbedno i dostupno, vidljivi spojevi, instrumenti i papiri koji povezuju raniji rad sa VIN-om. Hladan start daje priliku da se zabeleži miran rad motora i svaka lampica pre zagrevanja. Ne rastavlja se obloga, ne forsira se ručica i ne zaključuje se da je deo za zamenu bez pregleda. Odbijanje normalne vožnje ili radionice je važnija informacija od prividno uredne kabine.

## Vožnja beleži obrazac kroz sve brzine

Na bezbednoj ruti ručica prolazi kroz sve stepene pri polasku, umerenom ubrzanju, blagom usponu i usporavanju. Kvačilo se prati pri odvajanju, uključivanju i promeni opterećenja bez grubih proba. Beleži se da li se otpor, zvuk, vibracija ili proklizavanje javlja samo hladno, tek zagrejano, u određenoj brzini ili pri određenoj radnji. Takva beleška radionici vredi više od opšte ocene da menjač nije dobar.

## Kabina, papučica i oprema ne objašnjavaju same kvar

Položaj papučice, tepih, unutrašnja plastika i habanje komandi daju kontekst, ali sami ne dokazuju stanje kvačila ili kilometražu. Proveravaju se instrumenti, svetla, klima, ventilator, brisači i zaključavanje kako bi se dobila potpuna slika početnog troška. Potvrda o ranijem radu ima vrednost kada se može vezati za datum, kilometražu, VIN i stvarni obim. Jedan nov račun ne daje automatsku ocenu celom prenosu.

## Dizalica i radionica završavaju pregled

Na dizalici servis povezuje tragove tečnosti, donje zaštite, nosače, poluosovine i vidljive delove pogona sa opažanjem iz vožnje. Pregled se ne zaustavlja na jednom delu zato što se izvor simptoma ne sme pretpostaviti. Pisani nalaz treba da odvoji bezbednosne stavke, redovno habanje, potrebu za dodatnom dijagnostikom i ono što nije potvrđeno. Tek tada cena može da se poredi sa stvarnim stanjem, bez obećanja o popravci na osnovu kratkog susreta.
TEXT;
$inspection .= "\n\n".$finish('Pregled važi za konkretan motor i ručni menjač potvrđen VIN-om, ne za opštu pretpostavku o modelu.');

$market = <<<'TEXT'
Tržišna analiza polovnog auta sa urednim opisom i nejasnom opremom počinje razlikovanjem onoga što oglas tvrdi od onoga što se može proveriti. Fotografije i naziv paketa opreme nisu dovoljni kada dva automobila iste generacije imaju različit motor, menjač, tržište ili naknadno ugrađene delove. Cilj nije pogoditi cenu opreme, već sprečiti da nedokazana stavka postane razlog za višu cenu.

## Lista opreme mora pripadati baš tom VIN-u

Za svaki oglas odvojeno se zapisuju generacija, karoserija, motor, snaga, menjač, broj ključeva i oprema koja se može pokazati na licu mesta. Kamera, senzori, navigacija, tempomat, grejanje sedišta ili felne nisu zajednički svim paketima i godinama. Saobraćajna dozvola, VIN i računi određuju identitet automobila, dok fotografija samo usmerava pitanja. Ako specifikacija nije potvrđena, ne ulazi u poređenje kao prednost.

## Funkcija vredi više od naziva paketa

Prilikom pregleda uključuju se konkretni prekidači, klima, ventilator, brisači, podizači, svetla, zaključavanje i multimedija koju vozilo ima. Oprema koja postoji, ali ne radi, nije isto što i oprema koja nije ugrađena. Pregled kabine obuhvata i pojaseve, podešavanje sedišta, stanje gepeka i vlagu ispod podnice. Tako se razdvajaju stvarne dnevne funkcije od oglasa koji samo navodi bogat paket.

## Cena se poredi tek sa stanjem i dokumentima

Hladan start, bezbedna vožnja, karoserija i dizalica procenjuju se nezavisno od opreme. Gume, podvozje, curenja i kočnice traže nalaz radionice, a ne zaključak iz broja fotografija. Račun ima težinu ako povezuje VIN, datum, kilometražu i obim rada. Potvrđene potrošne stavke mogu dobiti predračun. Nejasna oprema ili nepoznat rad ne dobija izmišljenu vrednost koja bi opravdala ili oborila cenu.

## Odluka ne prati najduži spisak dodataka

Automobil sa manje opreme, ali jasnim identitetom, dokumentima i urednim pregledom može biti razumniji izbor od bogatije opisane ponude sa zatvorenim pitanjima. Kada su izvedba i stanje jednaki, funkcionalna oprema može imati stvarnu vrednost za planiranu upotrebu. Kada nisu jednaki, prvo se rešavaju motor, menjač, bezbednost i podvozje. Tako tržišno poređenje ne postaje rang-lista dodataka, već alat za proverljivu odluku.
TEXT;
$market .= "\n\n".$finish('Ova analiza poredi potvrđenu opremu, identitet i stanje konkretnog polovnog automobila, a ne naziv paketa iz oglasa.');

return [
    ['title'=>'Volkswagen Golf VII 1.2 TSI ili Kia cee\'d 1.4: poređenje polovnjaka','slug'=>'volkswagen-golf-vii-12-tsi-kia-ceed-jd-14-polovni-poredjenje','category'=>'Poređenje modela','excerpt'=>'Golf VII 1.2 TSI i Kia cee\'d JD 1.4 CVVT porede se kao tačne ručne benzinske izvedbe.','content'=>$comparison,'highlights'=>['Golf 1.2 TSI ima 105 KS, 175 Nm i šest brzina.','cee\'d 1.4 CVVT ima 100 KS, 134 Nm i šest brzina.','VIN, dizalica i računi imaju prednost nad oglasnom cenom.'],'tags'=>['Volkswagen Golf VII','Kia cee\'d JD','ručni menjač'],'meta_title'=>'Golf VII 1.2 TSI ili cee\'d 1.4: poređenje','meta_description'=>'Golf VII 1.2 TSI i Kia cee\'d 1.4 CVVT: pregled motora, kabine i podvozja.','palette'=>['#0f766e','#64748b','#f8fafc']],
    ['title'=>'Renault Clio IV 0.9 TCe 90 ručni: vodič za polovan primerak','slug'=>'renault-clio-iv-09-tce-90-rucni-polovni-vodic','category'=>'Kupovina polovnjaka','excerpt'=>'Clio IV TCe 90 ručni proverava se kroz potvrđen motor, vožnju, kabinu i dizalicu.','content'=>$clio,'highlights'=>['Energy TCe 90 ima 898 cm3, 66 kW i pet brzina.','Hladan start beleži simptome bez dijagnoze na parkingu.','Dizalica i VIN vezani računi odlučuju pre cene.'],'tags'=>['Renault Clio IV','0.9 TCe','ručni menjač'],'meta_title'=>'Renault Clio IV 0.9 TCe 90: vodič','meta_description'=>'Kako proveriti Renault Clio IV 0.9 TCe 90 sa ručnim menjačem.','palette'=>['#1e3a5f','#64748b','#f8fafc']],
    ['title'=>'Ford Fiesta Mk7 1.25 82 ručni: vodič za polovan primerak','slug'=>'ford-fiesta-mk7-125-82-rucni-polovni-vodic','category'=>'Kupovina polovnjaka','excerpt'=>'Fiesta Mk7 1.25 82 ručni bira se po tačnom motoru, vožnji i nalazu podvozja.','content'=>$fiesta,'highlights'=>['1.25 Duratec ima 1.242 cm3, 60 kW i 82 KS.','Ručni prenos ima pet brzina.','Gradski tragovi i dizalica daju celovitu sliku.'],'tags'=>['Ford Fiesta Mk7','1.25 Duratec','ručni menjač'],'meta_title'=>'Ford Fiesta Mk7 1.25 82: vodič','meta_description'=>'Kako proveriti Ford Fiestu Mk7 1.25 Duratec 82 sa ručnim menjačem.','palette'=>['#14532d','#64748b','#f8fafc']],
    ['title'=>'Ručni menjač i kvačilo polovnog auta: pregled bez nagađanja','slug'=>'rucni-menjac-kvacilo-polovni-auto-pregled','category'=>'Provera vozila','excerpt'=>'Ručni menjač i kvačilo proveravaju se kroz identitet, vožnju, dizalicu i pisani nalaz.','content'=>$inspection,'highlights'=>['VIN određuje motor i prenos koji se pregledaju.','Simptom se beleži kroz sve brzine na bezbednoj ruti.','Radionica odvaja bezbednost, habanje i dodatnu dijagnostiku.'],'tags'=>['ručni menjač','kvačilo','pregled polovnjaka'],'meta_title'=>'Ručni menjač i kvačilo: vodič za pregled','meta_description'=>'Kako proveriti ručni menjač i kvačilo polovnog auta bez parking dijagnoze.','palette'=>['#7f1d1d','#475569','#f8fafc']],
    ['title'=>'Oprema u oglasu polovnog auta: tržišna analiza stvarne vrednosti','slug'=>'oprema-oglas-polovni-auto-trzisna-analiza','category'=>'Analiza tržišta','excerpt'=>'Oprema polovnjaka ima vrednost tek kada su identitet, funkcija, stanje i dokazi potvrđeni.','content'=>$market,'highlights'=>['Naziv paketa nije dokaz opreme konkretnog vozila.','Svaka funkcija se proverava na licu mesta.','Cena se poredi tek sa stanjem, dokumentima i nalazom.'],'tags'=>['oprema automobila','tržišna analiza','oglasi polovnjaka'],'meta_title'=>'Oprema polovnog auta: tržišna analiza oglasa','meta_description'=>'Kako proveriti stvarnu vrednost opreme u oglasu polovnog automobila.','palette'=>['#7c2d12','#64748b','#f8fafc']],
];
