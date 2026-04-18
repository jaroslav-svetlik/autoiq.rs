<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Imports\ListingImportService;
use App\Services\Imports\MojAutoScraper;
use Illuminate\Console\Command;

class ScrapeMojAutoCommand extends Command
{
    protected $signature = 'imports:mojauto
        {url : URL oglasa sa MojAuto}
        {--html= : Putanja do lokalnog HTML snimka za proveru ili odobren izvoz}
        {--store-draft : Kreiraj nacrt oglasa ako su podaci kompletni}
        {--owner-email= : Email korisnika koji će biti vlasnik nacrta oglasa}';

    protected $description = 'Kontrolisano učitava MojAuto oglas i upisuje rezultat u evidenciju uvoza.';

    public function handle(
        MojAutoScraper $scraper,
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
            ['Izvor', $record->source_name],
            ['Status', $record->status],
            ['HTTP status', $record->http_status ?: '-'],
            ['Blokada', $record->challenge_detected ? 'da' : 'ne'],
            ['Naslov', $record->title ?: '-'],
            ['Cena', $record->price ? number_format($record->price, 0, ',', '.').' €' : '-'],
            ['Grad', $record->city ?: '-'],
            ['Napomena', $record->notes ?: '-'],
        ]);

        if ($this->option('store-draft')) {
            $owner = User::query()
                ->where('email', $this->option('owner-email') ?: config('autoiq.imports.mojauto.owner_email'))
                ->first();

            if (! $owner) {
                $this->error('Nije pronađen korisnik koji bi bio vlasnik nacrta oglasa.');

                return self::FAILURE;
            }

            $record = $imports->createDraftListing($record, $owner);

            $this->info($record->listing_id
                ? 'Nacrt oglasa je kreiran i povezan sa uvezenim zapisom.'
                : 'Uvezeni zapis je sačuvan, ali nema dovoljno podataka za nacrt oglasa.');
        }

        if (in_array($record->status, ['blocked', 'failed', 'disabled'], true)) {
            $this->warn('Uvoz nije uspešno obrađen. Pogledajte status i napomenu iznad.');

            return self::FAILURE;
        }

        $this->info('Uvezeni zapis je uspešno osvežen.');

        return self::SUCCESS;
    }
}
