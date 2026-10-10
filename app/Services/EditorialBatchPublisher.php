<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Support\EditorialBatchValidator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/** Create-only, bounded publication. Existing rows are verified, never overwritten. */
final class EditorialBatchPublisher
{
    public function publish(array $posts, string $date, string $journalDirectory): array
    {
        EditorialBatchValidator::validate($posts);
        if ($date !== CarbonImmutable::now('Europe/Belgrade')->toDateString()) {
            throw new RuntimeException('Publish only for the current Europe/Belgrade date; do not backdate missed runs.');
        }
        foreach ($posts as $source) {
            $bytes = Storage::disk('public')->get('blog/generated/'.$source['slug'].'.webp');
            $size = @getimagesizefromstring($bytes);
            $decoded = @imagecreatefromwebp(Storage::disk('public')->path('blog/generated/'.$source['slug'].'.webp'));
            if (! $size || ($size['mime'] ?? '') !== 'image/webp' || $size[0] !== 1280 || $size[1] !== 720 || ! $decoded) {
                throw new RuntimeException('Missing/invalid reviewed 1280x720 WebP: '.$source['slug']);
            }
            imagedestroy($decoded);
        }
        if (! is_dir($journalDirectory) && ! mkdir($journalDirectory, 0700, true)) {
            throw new RuntimeException('Cannot create persistent private journal directory.');
        }
        $lockPath = $journalDirectory.'/publication.lock';
        $lock = fopen($lockPath, 'c');
        if (! $lock || ! chmod($lockPath, 0600) || ! flock($lock, LOCK_EX | LOCK_NB)) {
            throw new RuntimeException('Another publication is running; inspect and resume later.');
        }
        try {
            return DB::transaction(function () use ($posts, $date, $journalDirectory): array {
                // published_at is stored in the application's Europe/Belgrade timezone.
                $start = CarbonImmutable::parse($date, 'Europe/Belgrade')->startOfDay();
                $end = $start->addDay();
                $daily = BlogPost::where('published_at', '>=', $start->toDateTimeString())
                    ->where('published_at', '<', $end->toDateTimeString())->get();
                $existing = BlogPost::whereIn('slug', array_column($posts, 'slug'))->get()->keyBy('slug');
                $fields = ['title', 'category', 'author_name', 'excerpt', 'content', 'highlights', 'tags', 'meta_title', 'meta_description', 'is_featured'];
                foreach ($existing as $post) {
                    $source = collect($posts)->firstWhere('slug', $post->slug);
                    foreach ($fields as $field) {
                        if ($post->{$field} !== $source[$field]) {
                            throw new RuntimeException('Existing content differs; scoped repair review required: '.$post->slug);
                        }
                    }
                    if (! $post->published_at || $post->published_at->setTimezone('Europe/Belgrade')->toDateString() !== $date
                        || $post->cover_image_path !== 'blog/generated/'.$post->slug.'.webp') {
                        throw new RuntimeException('Existing identity/date/media differs: '.$post->slug);
                    }
                }
                if ($daily->pluck('slug')->diff(array_column($posts, 'slug'))->isNotEmpty()) {
                    throw new RuntimeException('Another batch already occupies this date; do not duplicate it.');
                }
                if ($daily->count() + 5 - $existing->count() > 5) {
                    throw new RuntimeException('Europe/Belgrade daily limit would be exceeded.');
                }
                if ($existing->count() === 5) {
                    return ['status' => 'already published', 'created' => 0, 'ids' => $existing->pluck('id')->values()->all()];
                }
                $missing = array_values(array_filter($posts, fn ($post) => ! $existing->has($post['slug'])));
                if (BlogPost::whereIn('title', array_column($missing, 'title'))->exists()) {
                    throw new RuntimeException('An intended title already exists under another slug.');
                }
                $path = $journalDirectory.'/batch-'.$date.'-'.gmdate('Ymd\THis\Z').'-'.bin2hex(random_bytes(4)).'.json';
                $journal = fopen($path, 'x');
                if (! $journal || ! chmod($path, 0600)) {
                    throw new RuntimeException('Cannot write private affected-row rollback journal.');
                }
                $record = [
                    'date' => $date, 'created_at' => gmdate(DATE_ATOM),
                    'operation' => 'create only; rollback only listed new slugs after checking content hashes',
                    'rows' => array_map(fn ($post) => ['before' => null, 'slug' => $post['slug'], 'content_sha256' => hash('sha256', $post['content'])], $missing),
                ];
                try {
                    $json = json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                    if (fwrite($journal, $json) !== strlen($json) || ! fflush($journal) || ! fsync($journal)) {
                        throw new RuntimeException('Journal could not be persisted; no publication performed.');
                    }
                    $createdIds = [];
                    foreach ($missing as $source) {
                        $attributes = array_intersect_key($source, array_flip($fields));
                        $attributes += [
                            'slug' => $source['slug'], 'published_at' => now(),
                            'cover_image_path' => 'blog/generated/'.$source['slug'].'.webp',
                            'cover_image_alt' => $source['title'].' — AI urednička ilustracija',
                            'reading_time_minutes' => max(1, (int) ceil(count(preg_split('/\s+/u', trim($source['content']))) / 180)),
                        ];
                        $post = new BlogPost;
                        $post->forceFill($attributes)->saveQuietly();
                        $createdIds[] = $post->id;
                    }
                    if (BlogPost::where('published_at', '>=', $start->toDateTimeString())->where('published_at', '<', $end->toDateTimeString())->count() !== 5) {
                        throw new RuntimeException('Final daily count differs from five; transaction rolled back.');
                    }
                    $record['created_ids'] = $createdIds;
                    $json = json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                    rewind($journal);
                    if (! ftruncate($journal, 0) || fwrite($journal, $json) !== strlen($json) || ! fflush($journal) || ! fsync($journal)) {
                        throw new RuntimeException('Journal completion failed; transaction rolled back.');
                    }
                    return ['status' => 'published', 'created' => count($createdIds), 'ids' => BlogPost::whereIn('slug', array_column($posts, 'slug'))->pluck('id')->all(), 'journal' => $path];
                } finally {
                    fclose($journal);
                }
            });
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
