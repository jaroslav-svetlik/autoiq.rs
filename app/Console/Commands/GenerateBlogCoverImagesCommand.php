<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\BlogCoverImageGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Throwable;

class GenerateBlogCoverImagesCommand extends Command
{
    protected $signature = 'blog:generate-covers
        {--slug=* : Obradi samo navedene blog slugove}
        {--limit= : Najveći broj članaka za obradu}
        {--force : Generiši sliku i kada članak već ima cover}
        {--dry-run : Prikaži šta bi bilo generisano bez OpenAI poziva}
        {--model= : OpenAI image model za ovaj poziv}
        {--size= : Veličina slike za ovaj poziv}
        {--quality= : Kvalitet slike za ovaj poziv}
        {--format= : Format slike za ovaj poziv}';

    protected $description = 'Generiše moderne OpenAI cover slike za blog postove.';

    public function handle(BlogCoverImageGenerator $generator): int
    {
        $posts = $this->postsForGeneration();

        if ($posts->isEmpty()) {
            $this->info('Nema blog postova za generisanje.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $posts->each(fn (BlogPost $post) => $this->line('[dry-run] '.$post->slug.' - '.$post->title));

            return self::SUCCESS;
        }

        $generated = [];

        foreach ($posts as $post) {
            try {
                $path = $generator->generate(
                    post: $post,
                    model: $this->optionString('model'),
                    size: $this->optionString('size'),
                    quality: $this->optionString('quality'),
                    format: $this->optionString('format'),
                );

                $post->forceFill([
                    'cover_image_path' => $path,
                    'cover_image_alt' => $post->title,
                ])->save();

                $generated[] = [$post->slug, $path];
                $this->info('Generisana slika: '.$post->slug);
            } catch (Throwable $exception) {
                $this->error('Generisanje nije uspelo za '.$post->slug.': '.$exception->getMessage());

                return self::FAILURE;
            }
        }

        $this->table(['Slug', 'Cover path'], $generated);

        return self::SUCCESS;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    private function postsForGeneration(): Collection
    {
        $query = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->latest('id');

        $slugs = collect($this->option('slug'))
            ->filter(fn ($slug) => is_string($slug) && trim($slug) !== '')
            ->map(fn (string $slug) => trim($slug))
            ->values();

        if ($slugs->isNotEmpty()) {
            $query->whereIn('slug', $slugs);
        }

        $posts = $query->get()
            ->filter(fn (BlogPost $post) => $this->shouldGenerateFor($post))
            ->values();

        $limit = $this->option('limit');

        if (is_numeric($limit) && (int) $limit > 0) {
            return $posts->take((int) $limit)->values();
        }

        return $posts;
    }

    private function shouldGenerateFor(BlogPost $post): bool
    {
        if ($this->option('force')) {
            return true;
        }

        $path = trim((string) $post->cover_image_path);

        return $path === ''
            || str_ends_with($path, '.svg')
            || str_starts_with($path, 'demo/blog/')
            || str_starts_with($path, 'blog/trendovi/');
    }

    private function optionString(string $key): ?string
    {
        $value = $this->option($key);

        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }
}
