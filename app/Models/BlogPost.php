<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function coverImageUrl(): string
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
            return $url.'?v='.Storage::disk('public')->lastModified($storagePath);
        }

        return $url;
    }

    public function readingTimeLabel(): string
    {
        return $this->reading_time_minutes.' min čitanja';
    }

    public function excerptText(): string
    {
        return $this->excerpt ?: str($this->content)->squish()->limit(180)->toString();
    }
}
