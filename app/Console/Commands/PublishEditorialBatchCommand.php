<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\EditorialBatchPublisher;
use App\Support\ReviewedEditorialBatch;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

final class PublishEditorialBatchCommand extends Command
{
    protected $signature = 'editorial:publish {date : Reviewed YYYY-MM-DD batch} {--check : Validate and inspect without database writes}';

    protected $description = 'Publish only one reviewed five-article daily batch, with bounded retry and private rollback journal.';

    public function handle(EditorialBatchPublisher $publisher): int
    {
        try {
            $date = (string) $this->argument('date');
            $posts = ReviewedEditorialBatch::load($date);
            if ($this->option('check')) {
                $this->line(json_encode([
                    'date' => $date, 'slugs' => array_column($posts, 'slug'),
                    'catalog_count' => BlogPost::count(),
                    'daily_count' => BlogPost::whereDate('published_at', $date)->count(),
                    'existing' => BlogPost::whereIn('slug', array_column($posts, 'slug'))->get(['id', 'slug', 'published_at']),
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
                return self::SUCCESS;
            }
            if (gethostname() !== 'prod-web-01'
                || ! str_starts_with((string) realpath(base_path()), '/srv/sites/autoiq/releases/')
                || realpath(storage_path('app/public')) !== '/srv/sites/autoiq/var/storage/app/public'
                || config('app.timezone') !== 'Europe/Belgrade') {
                throw new RuntimeException('Publication requires the verified AutoIQ release and persistent media on prod-web-01.');
            }
            $this->line(json_encode($publisher->publish($posts, $date, '/srv/sites/autoiq/var/storage/app/private/editorial/rollback'),
                JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        }
    }
}
