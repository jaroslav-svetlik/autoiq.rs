<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/** Deliberately scoped, repeat-safe repair; never seeds the historical catalog. */
class OctoberSixthEditorialRepairSeeder extends Seeder
{
    public function run(): void
    {
        if (gethostname() !== 'prod-web-01'
            || ! str_starts_with((string) realpath(base_path()), '/srv/sites/autoiq/releases/')
            || realpath(storage_path('app/public')) !== '/srv/sites/autoiq/var/storage/app/public'
            || trim(file_get_contents(base_path('VERSION'))) !== '0.1.201') {
            throw new RuntimeException('Repair requires the verified AutoIQ 0.1.201 release on prod-web-01.');
        }
        $posts = (new \ReflectionMethod(new TrendBlogPostSeeder, 'octoberSixthPosts'))->invoke(new TrendBlogPostSeeder);
        $oldHashes = [
            651 => '8e82833a1ba0cb0a4d7c1cc55e144a39e56b96f2469c37dbef828cdf25f2aa9f',
            652 => 'c801b917249fbfd1f54f8537449576337bcc20fe3faaabd7c05193a7a3a90e41',
            653 => '637e54a71960325ec35c65837573df7e8f6a5c006111e159ead8526cbae3070e',
            654 => '408b99e1e663fe6e401c52fc0731cc1fca94745a08f262d499df32f2caf508db',
            655 => '793013a80dd742248940ff19905edca9f5c360722b84721ac9ca882428be405c',
        ];
        $fields = ['title', 'category', 'author_name', 'excerpt', 'content', 'highlights', 'tags', 'meta_title', 'meta_description'];
        $directory = storage_path('app/editorial/private');
        if (! is_dir($directory) && ! mkdir($directory, 0700, true)) {
            throw new RuntimeException('Cannot create private, item-scoped journal directory.');
        }
        $lock = fopen($directory.'/repair-2026-10-06.lock', 'c');
        if (! $lock || ! flock($lock, LOCK_EX | LOCK_NB)) {
            throw new RuntimeException('An editorial repair is already running.');
        }
        chmod($directory.'/repair-2026-10-06.lock', 0600);
        try {
            $result = DB::transaction(function () use ($posts, $oldHashes, $fields, $directory): array {
                $ids = array_keys($oldHashes);
                $digest = fn () => hash('sha256', json_encode(DB::table('blog_posts')->whereNotIn('id', $ids)->orderBy('id')->get(), JSON_THROW_ON_ERROR));
                $beforeDigest = $digest();
                if (BlogPost::count() !== 655 || BlogPost::whereDate('published_at', '2026-10-06')->count() !== 5) {
                    throw new RuntimeException('Unexpected catalog or October 6 publication count; inspect before retry.');
                }
                $changes = [];
                foreach ($posts as $index => $source) {
                    $post = BlogPost::findOrFail($ids[$index]);
                    if ($post->slug !== $source['slug'] || $post->getRawOriginal('published_at') !== '2026-10-06 09:22:53'
                        || $post->cover_image_path !== 'blog/generated/'.$source['slug'].'.webp') {
                        throw new RuntimeException('Article identity/media mismatch: '.$post->id);
                    }
                    $cover = storage_path('app/public/'.$post->cover_image_path);
                    $size = @getimagesize($cover);
                    if (! $size || ($size['mime'] ?? '') !== 'image/webp' || $size[0] < 1200) {
                        throw new RuntimeException('Expected bitmap WebP is missing: '.$post->slug);
                    }
                    $attributes = array_intersect_key($source, array_flip($fields));
                    $attributes['reading_time_minutes'] = (int) ceil(count(preg_split('/\s+/u', trim($source['content']))) / 180);
                    $matches = true;
                    foreach ($attributes as $field => $value) {
                        $matches = $matches && $post->{$field} === $value;
                    }
                    if ($matches) {
                        continue;
                    }
                    if (hash('sha256', $post->content) !== $oldHashes[$post->id]) {
                        throw new RuntimeException('Unreviewed concurrent content change: '.$post->id);
                    }
                    $changes[] = ['post' => $post, 'before' => $post->getAttributes(), 'after' => $attributes];
                }
                if ($changes === []) {
                    return ['changed' => 0, 'unaffected_sha256' => $beforeDigest, 'status' => 'already repaired'];
                }
                $journal = $directory.'/october-sixth-repair-'.gmdate('Ymd\THis\Z').'-'.bin2hex(random_bytes(4)).'.json';
                $handle = fopen($journal, 'x');
                if (! $handle || ! chmod($journal, 0600)) {
                    throw new RuntimeException('Cannot create private rollback journal; no database writes performed.');
                }
                $data = json_encode([
                    'scope' => 'Existing October 6 article repair only', 'version' => '0.1.201', 'created_at' => gmdate(DATE_ATOM),
                    'unaffected_sha256' => $beforeDigest,
                    'rows' => array_map(fn ($change) => ['before' => $change['before'], 'after' => $change['after']], $changes),
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                if (fwrite($handle, $data) !== strlen($data) || ! fflush($handle)) {
                    fclose($handle);
                    throw new RuntimeException('Rollback journal write failed; no database writes performed.');
                }
                fclose($handle);
                foreach ($changes as $change) {
                    // Quiet save deliberately avoids title-driven slug mutation.
                    $change['post']->forceFill($change['after'])->saveQuietly();
                    $post = $change['post']->fresh();
                    foreach (['id', 'slug', 'published_at', 'cover_image_path', 'cover_image_alt', 'created_at', 'is_featured'] as $field) {
                        if ($post->getRawOriginal($field) !== $change['before'][$field]) {
                            throw new RuntimeException('Protected article field changed: '.$field);
                        }
                    }
                }
                if ($digest() !== $beforeDigest || BlogPost::count() !== 655) {
                    throw new RuntimeException('Unaffected catalog changed; repair rolled back.');
                }
                return ['changed' => count($changes), 'journal' => $journal, 'unaffected_sha256' => $beforeDigest];
            });
            $this->command?->info(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
