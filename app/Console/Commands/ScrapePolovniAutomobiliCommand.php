<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Imports\ListingImportService;
use App\Services\Imports\PolovniAutomobiliScraper;
use Illuminate\Console\Command;

class ScrapePolovniAutomobiliCommand extends Command
{
    protected $signature = 'imports:polovni-automobili
        {url : URL oglasa sa PolovniAutomobili}
        {--html= : Putanja do lokalnog HTML snimka za parse test ili odobren export}
        {--store-draft : Kreiraj lokalni draft oglas ako su podaci kompletni}
        {--owner-email= : Email korisnika koji će biti vlasnik draft oglasa}';

    protected $description = 'Kontrolisano parsira PolovniAutomobili oglas i upisuje rezultat u staging import tabelu.';

    public function handle(
        PolovniAutomobiliScraper $scraper,
        ListingImportService $imports,
    ): int {
        $url = (string) $this->argument('url');
        $htmlPath = $this->option('html');

        if (is_string($htmlPath) && $htmlPath !== '') {
            if (! is_file($htmlPath) || ! is_readable($htmlPath)) {
                $this->error('Prosleđeni HTML fajl nije dostupan za čitanje.');

                return self::FAILURE;
            }

            $result = $scraper->parseHtml(
                html: (string) file_get_contents($htmlPath),
                url: $url,
                httpStatus: 200,
            );
        } else {
            $result = $scraper->scrape($url);
        }

        $record = $imports->storeResult($result);

        $this->table(['Polje', 'Vrednost'], [
            ['Status', $record->status],
            ['HTTP status', $record->http_status ?: '-'],
            ['Challenge', $record->challenge_detected ? 'da' : 'ne'],
            ['Naslov', $record->title ?: '-'],
            ['Cena', $record->price ? number_format($record->price, 0, ',', '.').' €' : '-'],
            ['Grad', $record->city ?: '-'],
            ['Napomena', $record->notes ?: '-'],
        ]);

        if ($this->option('store-draft')) {
            $owner = User::query()
                ->where('email', $this->option('owner-email') ?: config('autoiq.imports.polovni_automobili.owner_email'))
                ->first();

            if (! $owner) {
                $this->error('Nije pronađen korisnik koji bi bio vlasnik draft oglasa.');

                return self::FAILURE;
            }

            $record = $imports->createDraftListing($record, $owner);

            $this->info($record->listing_id
                ? 'Lokalni draft oglas je kreiran i povezan sa import zapisom.'
                : 'Import zapis je sačuvan, ali nema dovoljno podataka za draft oglas.');
        }

        if (in_array($record->status, ['blocked', 'failed', 'disabled'], true)) {
            $this->warn('Import nije uspešno parsiran. Pogledaj status i napomenu iznad.');

            return self::FAILURE;
        }

        $this->info('Import zapis je uspešno osvežen.');

        return self::SUCCESS;
    }
}
