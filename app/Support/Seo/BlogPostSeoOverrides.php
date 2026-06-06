<?php

namespace App\Support\Seo;

class BlogPostSeoOverrides
{
    /**
     * @return array{meta_title?: string, meta_description?: string, brief?: array{label: string, heading: string, items: array<int, string>}}|null
     */
    public static function for(string $slug): ?array
    {
        return self::overrides()[$slug] ?? null;
    }

    public static function metaTitle(string $slug): ?string
    {
        return self::for($slug)['meta_title'] ?? null;
    }

    public static function metaDescription(string $slug): ?string
    {
        return self::for($slug)['meta_description'] ?? null;
    }

    /**
     * @return array{label: string, heading: string, items: array<int, string>}|null
     */
    public static function brief(string $slug): ?array
    {
        return self::for($slug)['brief'] ?? null;
    }

    /**
     * @return array<string, array{meta_title?: string, meta_description?: string, brief?: array{label: string, heading: string, items: array<int, string>}}>
     */
    private static function overrides(): array
    {
        return [
            'automatski-menjac-kod-polovnjaka-sta-proveriti-pre-probne-voznje' => [
                'meta_title' => 'Kako proveriti automatski menjač pre kupovine',
                'meta_description' => 'Checklist za proveru automatskog menjača: hladan start, D/R kašnjenje, trzaji, DSG, CVT, servis ulja, dijagnostika i kada odustati.',
                'brief' => [
                    'label' => 'Brza checklist',
                    'heading' => 'Kako proveriti automatski menjač na probnoj vožnji',
                    'items' => [
                        'Krenite od hladnog starta i proverite da li kasni ubacivanje u D ili R.',
                        'U gradskoj vožnji pratite trzaje, proklizavanje, vibracije i promene brzina pri malom gasu.',
                        'Tražite dokaz o servisu ulja u menjaču; kod DSG, CVT i robotizovanih menjača ne kupujte samo na utisak.',
                    ],
                ],
            ],
            'najbolji-polovni-automobili-do-10000-evra' => [
                'meta_title' => 'Najbolji polovni automobili do 10.000 evra u Srbiji',
                'meta_description' => 'Vodič za polovan auto do 10.000 evra: modeli koji imaju smisla, šta proveriti, kada birati benzin, dizel ili hibrid i gde nastaju skupi rizici.',
                'brief' => [
                    'label' => 'Budžet 10.000 €',
                    'heading' => 'Prvo izdvojite miran primerak, pa tek onda marku',
                    'items' => [
                        'Najbolji izbor nije najopremljeniji auto, nego primerak sa dokazima o održavanju.',
                        'Za grad su često mirniji benzinci i hibridi; dizel ima smisla tek uz otvoren put.',
                        'U cenu odmah uračunajte gume, veliki servis, kočnice i prvi pregled kod majstora.',
                    ],
                ],
            ],
            'polovni-toyota-yaris-hybrid-gradski-hibrid-koji-trazi-mirnu-istoriju' => [
                'meta_title' => 'Polovni Toyota Yaris Hybrid: šta proveriti pre kupovine',
                'meta_description' => 'Toyota Yaris Hybrid kao polovnjak: provera baterije, kočnica, gradske vožnje, servisne istorije, kilometraže i realne cene u oglasu.',
                'brief' => [
                    'label' => 'Yaris Hybrid',
                    'heading' => 'Reputacija Toyote vredi samo uz proverljiv primerak',
                    'items' => [
                        'Dijagnostika hibridnog sistema treba da bude deo pregleda, ne dodatak posle kapare.',
                        'Gradska vožnja može sakriti umorne kočnice, lošu 12V bateriju i zapušten enterijer.',
                        'Viša cena ima smisla samo ako postoje računi, realna kilometraža i jasna istorija.',
                    ],
                ],
            ],
            'kia-ceed-ili-hyundai-i30-kompakt-bez-nemacke-premije' => [
                'meta_title' => 'Kia Ceed ili Hyundai i30: koji polovni kompakt kupiti',
                'meta_description' => 'Poređenje Kia Ceed i Hyundai i30 polovnjaka: motori, oprema, garancija, servisna istorija, cena, dizel rizici i najbolja kupovina.',
            ],
            'pregovaranje-posle-pregleda-kako-spustiti-cenu-bez-svade' => [
                'meta_title' => 'Kako spustiti cenu polovnog auta posle pregleda',
                'meta_description' => 'Pregovaranje posle pregleda polovnog auta: kako koristiti ulaganja, gume, servis, kočnice i dijagnostiku da tražite realan popust bez svađe.',
            ],
            'suv-do-13000-evra-da-li-vredi-juriti-visu-klasu-ili-kupiti-mladi-kompakt' => [
                'meta_title' => 'Polovni SUV do 13.000 evra: šta kupiti u Srbiji',
                'meta_description' => 'Vodič za polovni SUV do 13.000 evra: veća klasa ili mlađi kompakt, koje troškove proveriti, kada cena vara i kako izbeći skup primerak.',
            ],
        ];
    }
}
