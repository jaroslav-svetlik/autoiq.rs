<?php

namespace App\Console\Commands;

use App\Services\BlogCoverImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class OptimizeBlogCoverImagesCommand extends Command
{
    protected $signature = 'blog:optimize-covers
        {--slug=* : Obradi samo navedene blog slugove}
        {--limit= : Najveći broj slika za obradu}
        {--dry-run : Prikaži uštedu bez upisa optimizovanih fajlova}
        {--max-width= : Najveća širina optimizovane slike}
        {--max-height= : Najveća visina optimizovane slike}
        {--max-kb= : Preskoči slike koje su već u dimenzijama i ispod ove veličine}
        {--quality= : Kvalitet izlazne WebP/JPEG slike od 1 do 100}';

    protected $description = 'Optimizuje postojeće OpenAI blog cover slike bez brisanja fajlova.';

    public function handle(BlogCoverImageOptimizer $optimizer): int
    {
        $paths = $this->pathsForOptimization();

        if ($paths->isEmpty()) {
            $this->info('Nema generisanih blog cover slika za optimizaciju.');

            return self::SUCCESS;
        }

        $rows = [];
        $totalBefore = 0;
        $totalAfter = 0;
        $optimizedCount = 0;

        foreach ($paths as $path) {
            try {
                $original = Storage::disk('public')->get($path);

                if ($this->isAlreadyOptimized($original)) {
                    $dimensions = getimagesizefromstring($original);
                    $bytes = strlen($original);
                    $totalBefore += $bytes;
                    $totalAfter += $bytes;
                    $rows[] = [
                        $path,
                        $this->humanBytes($bytes),
                        'bez promene',
                        '0%',
                        $dimensions[0].'x'.$dimensions[1],
                    ];

                    continue;
                }

                $result = $optimizer->optimize(
                    contents: $original,
                    format: $this->formatForPath($path),
                    maxWidth: $this->optionInteger('max-width'),
                    maxHeight: $this->optionInteger('max-height'),
                    quality: $this->optionInteger('quality'),
                );
            } catch (Throwable $exception) {
                $this->warn('Preskačem '.$path.': '.$exception->getMessage());

                continue;
            }

            $shouldWrite = $result->optimizedBytes < $result->originalBytes;
            $totalBefore += $result->originalBytes;
            $totalAfter += $shouldWrite ? $result->optimizedBytes : $result->originalBytes;

            if ($shouldWrite) {
                $optimizedCount++;

                if (! $this->option('dry-run')) {
                    Storage::disk('public')->put($path, $result->contents);
                }
            }

            $rows[] = [
                $path,
                $this->humanBytes($result->originalBytes),
                $shouldWrite ? $this->humanBytes($result->optimizedBytes) : 'bez promene',
                $shouldWrite ? number_format($result->savedPercentage(), 1, ',', '.').'%' : '0%',
                $result->originalWidth.'x'.$result->originalHeight.' -> '.$result->width.'x'.$result->height,
            ];
        }

        $this->table(['Fajl', 'Pre', 'Posle', 'Ušteda', 'Dimenzije'], $rows);
        $this->info(($this->option('dry-run') ? 'Dry-run: ' : '').'Optimizovano slika: '.$optimizedCount);

        if ($totalBefore > 0) {
            $this->info('Ukupno: '.$this->humanBytes($totalBefore).' -> '.$this->humanBytes($totalAfter));
        }

        return self::SUCCESS;
    }

    /**
     * @return Collection<int, string>
     */
    private function pathsForOptimization(): Collection
    {
        $slugs = collect($this->option('slug'))
            ->filter(fn ($slug) => is_string($slug) && trim($slug) !== '')
            ->map(fn (string $slug) => trim($slug))
            ->values();

        $paths = collect(Storage::disk('public')->files('blog/generated'))
            ->filter(fn (string $path) => $this->isSupportedImage($path))
            ->when($slugs->isNotEmpty(), function ($paths) use ($slugs) {
                return $paths->filter(fn (string $path) => $slugs->contains(pathinfo($path, PATHINFO_FILENAME)));
            })
            ->sort()
            ->values();

        $limit = $this->option('limit');

        if (is_numeric($limit) && (int) $limit > 0) {
            return $paths->take((int) $limit)->values();
        }

        return $paths;
    }

    private function isSupportedImage(string $path): bool
    {
        return Str::startsWith($path, 'blog/generated/')
            && in_array($this->formatForPath($path), ['jpeg', 'png', 'webp'], true);
    }

    private function formatForPath(string $path): string
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'jpeg',
            'png' => 'png',
            default => 'webp',
        };
    }

    private function optionInteger(string $key): ?int
    {
        $value = $this->option($key);

        return is_numeric($value) ? (int) $value : null;
    }

    private function isAlreadyOptimized(string $contents): bool
    {
        $dimensions = getimagesizefromstring($contents);

        if (! is_array($dimensions)) {
            return false;
        }

        $maxWidth = $this->optionInteger('max-width') ?? (int) config('services.openai.image_max_width', 1280);
        $maxHeight = $this->optionInteger('max-height') ?? (int) config('services.openai.image_max_height', 854);
        $maxKilobytes = $this->optionInteger('max-kb') ?? (int) config('services.openai.image_target_max_kb', 350);

        return $dimensions[0] <= $maxWidth
            && $dimensions[1] <= $maxHeight
            && strlen($contents) <= $maxKilobytes * 1024;
    }

    private function humanBytes(int $bytes): string
    {
        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / 1024 / 1024, 2, ',', '.').' MB';
        }

        return number_format($bytes / 1024, 1, ',', '.').' KB';
    }
}
