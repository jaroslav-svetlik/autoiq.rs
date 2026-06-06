<?php

namespace App\Support\Seo;

use Illuminate\Support\Str;

class VehicleLandingPages
{
    /**
     * @return array<int, array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}>
     */
    public static function all(): array
    {
        return [
            self::page('Volkswagen', 'Golf 7', 'kompakt', 'polovan Golf 7 u Srbiji', ['servisna istorija', 'kilometraža', 'DSG ili kvačilo']),
            self::page('Audi', 'A3', 'premium kompakt', 'polovan Audi A3', ['S tronic', 'enterijer', 'servisni računi']),
            self::page('Audi', 'A4', 'premium limuzina', 'polovan Audi A4', ['TDI motor', 'S tronic', 'trap']),
            self::page('Audi', 'Q3', 'kompaktni premium SUV', 'polovan Audi Q3', ['quattro ako ga ima', 'menjač', 'oprema']),
            self::page('Audi', 'Q5', 'premium SUV', 'polovan Audi Q5', ['quattro pogon', 'menjač', 'veće gume']),
            self::page('BMW', '320d', 'premium dizel limuzina', 'polovan BMW 320d', ['lanac', 'turbina', 'zadnji trap']),
            self::page('BMW', 'X1', 'kompaktni premium SUV', 'polovan BMW X1', ['xDrive', 'menjač', 'servis dizela']),
            self::page('BMW', 'X3', 'premium SUV', 'polovan BMW X3', ['pogon', 'automatski menjač', 'trap']),
            self::page('Škoda', 'Octavia', 'porodični kompakt', 'polovna Škoda Octavia', ['TDI režim vožnje', 'DSG', 'kilometraža']),
            self::page('Škoda', 'Fabia', 'gradski auto', 'polovna Škoda Fabia', ['benzinski motor', 'kvačilo', 'gradska upotreba']),
            self::page('Škoda', 'Kodiaq', 'porodični SUV', 'polovna Škoda Kodiaq', ['sedam sedišta', 'DSG', 'trap']),
            self::page('Opel', 'Astra', 'kompakt', 'polovna Opel Astra', ['motor', 'kvačilo', 'servisna istorija']),
            self::page('Opel', 'Corsa', 'gradski auto', 'polovna Opel Corsa', ['gradska vožnja', 'kvačilo', 'limarija']),
            self::page('Renault', 'Megane', 'kompakt', 'polovan Renault Megane', ['dizel istorija', 'elektronika', 'trap']),
            self::page('Renault', 'Clio', 'gradski auto', 'polovan Renault Clio', ['1.5 dCi', 'kvačilo', 'realna kilometraža']),
            self::page('Renault', 'Austral', 'noviji SUV', 'polovan Renault Austral', ['hibridni pogon', 'garancija', 'oprema']),
            self::page('Toyota', 'Corolla', 'porodični kompakt', 'polovna Toyota Corolla', ['hibridna baterija', 'kočnice', 'servis Toyota sistema']),
            self::page('Toyota', 'Auris', 'gradski hibrid', 'polovna Toyota Auris', ['hibridni sistem', '12V baterija', 'kočnice']),
            self::page('Toyota', 'Yaris', 'gradski hibrid', 'polovna Toyota Yaris', ['hibridna baterija', 'gradska eksploatacija', 'servisna istorija']),
            self::page('Toyota', 'RAV4', 'porodični SUV', 'polovna Toyota RAV4', ['hibridni pogon', 'AWD', 'servisna istorija']),
            self::page('Hyundai', 'Tucson', 'porodični SUV', 'polovan Hyundai Tucson', ['hibrid ili dizel', 'menjač', 'garancija']),
            self::page('Hyundai', 'i30', 'kompakt', 'polovan Hyundai i30', ['motor', 'oprema', 'servisni intervali']),
            self::page('Hyundai', 'Ioniq', 'hibrid', 'polovan Hyundai Ioniq', ['hibridni sistem', 'kočnice', 'potrošnja u gradu']),
            self::page('Kia', 'Ceed', 'kompakt', 'polovna Kia Ceed', ['garancija', 'servisna istorija', 'oprema']),
            self::page('Kia', 'Sportage', 'porodični SUV', 'polovna Kia Sportage', ['garancija', 'motor', 'trap']),
            self::page('Nissan', 'Qashqai', 'crossover', 'polovan Nissan Qashqai', ['CVT ako ga ima', 'trap', 'klima']),
            self::page('Peugeot', '3008', 'porodični crossover', 'polovan Peugeot 3008', ['PureTech ili dizel', 'elektronika', 'menjač']),
            self::page('Peugeot', '2008', 'mali crossover', 'polovan Peugeot 2008', ['motor', 'oprema', 'gradska upotreba']),
            self::page('Mazda', 'CX-5', 'porodični SUV', 'polovna Mazda CX-5', ['korozija', 'benzinski motor', 'servisna istorija']),
            self::page('Ford', 'Kuga', 'porodični SUV', 'polovna Ford Kuga', ['motor', 'automatski menjač', 'hibridni sistem']),
            self::page('Volkswagen', 'Tiguan', 'porodični SUV', 'polovan Volkswagen Tiguan', ['DSG', 'TDI režim vožnje', 'trap']),
        ];
    }

    /**
     * @return array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}|null
     */
    public static function for(string $brand, string $model): ?array
    {
        return collect(self::all())
            ->first(fn (array $page) => self::slug($page['brand']) === self::slug($brand)
                && self::slug($page['model']) === self::slug($model));
    }

    /**
     * @return array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}|null
     */
    public static function fromSlugs(string $brandSlug, string $modelSlug): ?array
    {
        return collect(self::all())
            ->first(fn (array $page) => self::slug($page['brand']) === $brandSlug
                && self::slug($page['model']) === $modelSlug);
    }

    /**
     * @return array{brandSlug: string, modelSlug: string}
     */
    public static function routeParameters(string $brand, string $model): array
    {
        return [
            'brandSlug' => self::slug($brand),
            'modelSlug' => self::slug($model),
        ];
    }

    public static function slug(string $value): string
    {
        return Str::slug(Str::ascii($value));
    }

    /**
     * @param  array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}  $page
     */
    public static function title(array $page): string
    {
        return "Polovni {$page['brand']} {$page['model']} oglasi i analiza cena | AutoIQ";
    }

    /**
     * @param  array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}  $page
     */
    public static function heading(array $page): string
    {
        return "Polovni {$page['brand']} {$page['model']}: oglasi, cena i provera";
    }

    /**
     * @param  array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}  $page
     */
    public static function description(array $page): string
    {
        return "Uporedite {$page['brand']} {$page['model']} oglase kroz cenu, kilometražu, opremu i AutoIQ procenu. Pogledajte ključne provere pre kupovine.";
    }

    /**
     * @param  array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}  $page
     */
    public static function intro(array $page): string
    {
        $checks = implode(', ', $page['checks']);

        return "{$page['brand']} {$page['model']} je {$page['segment']} kod kog cena ima smisla tek kada se uporede stanje, oprema i realna ulaganja. Pre kapare obratite pažnju na ove stavke: {$checks}, pa tek onda poređajte oglase po ceni.";
    }

    /**
     * @param  array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}  $page
     * @return array<int, string>
     */
    public static function highlights(array $page): array
    {
        return [
            "Uporedite {$page['brand']} {$page['model']} oglase po ceni, godištu i kilometraži.",
            'Dajte prednost primerku sa proverljivom servisnom istorijom i jasnim opisom.',
            'AutoIQ ocena pomaže da se brže izdvoje oglasi koji ne traže hladnu glavu samo zbog niske cene.',
        ];
    }

    /**
     * @return array{brand: string, model: string, segment: string, intent: string, checks: array<int, string>}
     */
    private static function page(string $brand, string $model, string $segment, string $intent, array $checks): array
    {
        return compact('brand', 'model', 'segment', 'intent', 'checks');
    }
}
