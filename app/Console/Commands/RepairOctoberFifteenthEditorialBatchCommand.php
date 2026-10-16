<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Support\EditorialBatchValidator;
use App\Support\ReviewedEditorialBatch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

/** Repairs only the reviewed October 15 copy; article identity and media are immutable. */
final class RepairOctoberFifteenthEditorialBatchCommand extends Command
{
    protected $signature = 'editorial:repair-october-fifteenth {--check : Inspect without writes}';
    protected $description = 'Repair the five October 15 articles after legacy voice corruption, using a private row journal.';
    private const DATE = '2026-10-15';
    private const EXPECTED = [
        671 => 'a8cc3ca6e4ffdd28235cd49780f7c2abaed2bc34bdf98512e0e5f330e38a6f7d',
        672 => '6346b5583637985ceef1c645f605683691dcf5879c010bb46abe0adf74615816',
        673 => '18ccfdd99fbab6d1f494b54b14780cd56a83bd295edd4137f99e3fbe4775f9a1',
        674 => 'c3f4afebf33cf88196d99bf7bdf70f975b268bf4aba2198c92542ce8c4d08225',
        675 => '84b515401f9ea4a04e3b0c6a8ddb4e15157c4c92b05468490a00dca52fdf14e3',
    ];
    public function handle(): int
    {
        try {
            if (gethostname() !== 'prod-web-01' || ! str_starts_with((string) realpath(base_path()), '/srv/sites/autoiq/releases/') || realpath(storage_path('app/public')) !== '/srv/sites/autoiq/var/storage/app/public') throw new RuntimeException('Repair requires the verified AutoIQ release and persistent media on prod-web-01.');
            $posts = ReviewedEditorialBatch::load(self::DATE); EditorialBatchValidator::validate($posts); $bySlug = collect($posts)->keyBy('slug');
            $rows = BlogPost::whereIn('id', array_keys(self::EXPECTED))->orderBy('id')->get();
            if ($rows->count() !== 5 || BlogPost::whereDate('published_at', self::DATE)->count() !== 5) throw new RuntimeException('Unexpected October 15 scope; no repair performed.');
            foreach ($rows as $row) { $source = $bySlug->get($row->slug); if (! $source || $row->getRawOriginal('published_at') !== '2026-10-15 09:40:04' || $row->cover_image_path !== 'blog/generated/'.$row->slug.'.webp') throw new RuntimeException('Protected identity or media differs: '.$row->id); }
            $pending = $rows->filter(fn (BlogPost $row) => $row->content !== $bySlug[$row->slug]['content']);
            if ($pending->isEmpty()) { $this->line(json_encode(['status' => 'already repaired', 'changed' => 0])); return self::SUCCESS; }
            foreach ($pending as $row) if (hash('sha256', $row->content) !== self::EXPECTED[$row->id]) throw new RuntimeException('Unreviewed concurrent content change: '.$row->id);
            if ($this->option('check')) { $this->line(json_encode(['date' => self::DATE, 'ids' => $pending->pluck('id')->all(), 'changed' => $pending->count()])); return self::SUCCESS; }
            $directory = '/srv/sites/autoiq/var/storage/app/private/editorial/rollback'; if (! is_dir($directory) && ! mkdir($directory, 0700, true)) throw new RuntimeException('Cannot create private repair-journal directory.');
            $lock = fopen($directory.'/repair-2026-10-15.lock', 'c'); if (! $lock || ! chmod($directory.'/repair-2026-10-15.lock', 0600) || ! flock($lock, LOCK_EX | LOCK_NB)) throw new RuntimeException('Another October 15 repair is running.');
            try { $result = DB::transaction(function () use ($pending, $bySlug, $directory): array {
                $journalPath = $directory.'/repair-2026-10-15-'.gmdate('Ymd\\THis\\Z').'-'.bin2hex(random_bytes(4)).'.json'; $journal = fopen($journalPath, 'x'); if (! $journal || ! chmod($journalPath, 0600)) throw new RuntimeException('Cannot create private affected-row repair journal.');
                try { $fields = ['title','category','author_name','excerpt','content','highlights','tags','meta_title','meta_description']; $changes = [];
                    foreach ($pending as $row) { $after = array_intersect_key($bySlug[$row->slug], array_flip($fields)); $after['reading_time_minutes'] = max(1, (int) ceil(count(preg_split('/\\s+/u', trim($after['content']))) / 180)); $changes[] = ['id' => $row->id, 'before' => $row->getAttributes(), 'after' => $after]; }
                    $payload = json_encode(['date' => self::DATE, 'operation' => 'scoped editorial repair only', 'rows' => $changes], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR); if (fwrite($journal, $payload) !== strlen($payload) || ! fflush($journal) || ! fsync($journal)) throw new RuntimeException('Repair journal persistence failed.'); foreach ($changes as $change) BlogPost::findOrFail($change['id'])->forceFill($change['after'])->saveQuietly(); return ['status' => 'repaired','changed' => count($changes),'ids' => array_column($changes, 'id'),'journal' => $journalPath];
                } finally { fclose($journal); }
            }); } finally { flock($lock, LOCK_UN); fclose($lock); }
            $this->line(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)); return self::SUCCESS;
        } catch (Throwable $exception) { $this->error($exception->getMessage()); return self::FAILURE; }
    }
}
