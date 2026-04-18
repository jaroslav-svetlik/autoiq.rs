<?php

namespace App\Observers;

use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogPostObserver
{
    public function saving(BlogPost $blogPost): void
    {
        if (! $blogPost->slug || $blogPost->isDirty('title')) {
            $blogPost->slug = $this->uniqueSlug($blogPost);
        }

        if (! $blogPost->author_name) {
            $blogPost->author_name = 'AutoIQ redakcija';
        }

        if (! $blogPost->excerpt) {
            $blogPost->excerpt = str($blogPost->content)->squish()->limit(190)->toString();
        }

        if (! $blogPost->meta_title) {
            $blogPost->meta_title = str($blogPost->title)->limit(60)->toString();
        }

        if (! $blogPost->meta_description) {
            $blogPost->meta_description = str($blogPost->excerpt)->limit(155)->toString();
        }

        if (! $blogPost->cover_image_alt) {
            $blogPost->cover_image_alt = $blogPost->title;
        }

        $words = str_word_count(strip_tags((string) $blogPost->content));
        $blogPost->reading_time_minutes = max(1, (int) ceil($words / 180));
    }

    protected function uniqueSlug(BlogPost $blogPost): string
    {
        $base = Str::slug($blogPost->title ?: 'autoiq-blog');
        $slug = $base;
        $counter = 1;

        while (
            BlogPost::query()
                ->when($blogPost->exists, fn ($query) => $query->whereKeyNot($blogPost->getKey()))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
