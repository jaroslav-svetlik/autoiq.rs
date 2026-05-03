<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'author_name',
        'cover_image_path',
        'cover_image_alt',
        'excerpt',
        'content',
        'highlights',
        'tags',
        'meta_title',
        'meta_description',
        'reading_time_minutes',
        'is_featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'tags' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function coverImageUrl(bool $absolute = false): string
    {
        if (! $this->cover_image_path) {
            return 'https://placehold.co/1600x900/0f172a/f8fafc?text='.urlencode($this->title);
        }

        if (str_starts_with($this->cover_image_path, 'http://') || str_starts_with($this->cover_image_path, 'https://')) {
            return $this->cover_image_path;
        }

        $path = ltrim($this->cover_image_path, '/');
        $storagePath = str_starts_with($path, 'storage/') ? substr($path, strlen('storage/')) : $path;
        $url = str_starts_with($path, 'storage/')
            ? '/'.$path
            : '/storage/'.$path;

        if (Storage::disk('public')->exists($storagePath)) {
            $url .= '?v='.Storage::disk('public')->lastModified($storagePath);
        }

        return $absolute ? url($url) : $url;
    }

    public function readingTimeLabel(): string
    {
        return $this->reading_time_minutes.' min čitanja';
    }

    public function excerptText(): string
    {
        return $this->excerpt ?: str($this->content)->squish()->limit(180)->toString();
    }

    /**
     * @return Collection<int, array{type: string, text?: string, level?: int, question?: string, answer?: string}>
     */
    public function contentBlocks(): Collection
    {
        return collect(preg_split('/\n\s*\n/', trim((string) $this->content)))
            ->filter(fn (string $block) => filled($block))
            ->map(fn (string $block) => $this->parseContentBlock($block))
            ->values();
    }

    /**
     * @return Collection<int, array{question: string, answer: string}>
     */
    public function faqItems(): Collection
    {
        return $this->contentBlocks()
            ->where('type', 'faq')
            ->map(fn (array $block) => [
                'question' => $block['question'],
                'answer' => $block['answer'],
            ])
            ->values();
    }

    /**
     * @return array{type: string, text?: string, level?: int, question?: string, answer?: string}
     */
    private function parseContentBlock(string $block): array
    {
        $block = trim($block);

        if (str_starts_with($block, '### ')) {
            return [
                'type' => 'heading',
                'level' => 3,
                'text' => trim(substr($block, 4)),
            ];
        }

        if (str_starts_with($block, '## ')) {
            return [
                'type' => 'heading',
                'level' => 2,
                'text' => trim(substr($block, 3)),
            ];
        }

        if (str_starts_with($block, 'FAQ:')) {
            $lines = collect(preg_split('/\R/', $block))
                ->map(fn (string $line) => trim($line))
                ->filter()
                ->values();

            return [
                'type' => 'faq',
                'question' => trim((string) str($lines->shift() ?? '')->after('FAQ:')),
                'answer' => $lines->implode(' '),
            ];
        }

        return [
            'type' => 'paragraph',
            'text' => $block,
        ];
    }
}
