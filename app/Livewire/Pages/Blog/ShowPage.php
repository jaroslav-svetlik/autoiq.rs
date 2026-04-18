<?php

namespace App\Livewire\Pages\Blog;

use App\Livewire\Pages\PageComponent;
use App\Models\BlogPost;
use Illuminate\Contracts\View\View;

class ShowPage extends PageComponent
{
    public BlogPost $blogPost;

    public function mount(BlogPost $blogPost): void
    {
        abort_unless($blogPost->published_at && $blogPost->published_at->isPast(), 404);

        $this->blogPost = $blogPost;
    }

    protected function title(): string
    {
        return ($this->blogPost->meta_title ?: $this->blogPost->title).' | AutoIQ Blog';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => $this->blogPost->meta_description ?: $this->blogPost->excerptText(),
            'canonical' => route('blog.show', $this->blogPost),
            'type' => 'article',
            'image' => $this->blogPost->coverImageUrl(absolute: true),
        ];
    }

    protected function jsonLd(): array
    {
        $articleUrl = route('blog.show', $this->blogPost);
        $blogUrl = route('blog.index');

        return [[
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $this->blogPost->title,
            'description' => $this->blogPost->excerptText(),
            'image' => [$this->blogPost->coverImageUrl(absolute: true)],
            'datePublished' => optional($this->blogPost->published_at)->toIso8601String(),
            'dateModified' => optional($this->blogPost->updated_at)->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => $this->blogPost->author_name,
                'url' => route('home'),
            ],
            'articleSection' => $this->blogPost->category,
            'keywords' => implode(', ', $this->blogPost->tags ?? []),
            'mainEntityOfPage' => $articleUrl,
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'AutoIQ',
                'url' => route('home'),
            ],
        ], [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'AutoIQ',
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Blog',
                    'item' => $blogUrl,
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $this->blogPost->title,
                    'item' => $articleUrl,
                ],
            ],
        ]];
    }

    public function render(): View
    {
        $relatedPosts = BlogPost::query()
            ->published()
            ->whereKeyNot($this->blogPost->id)
            ->where('category', $this->blogPost->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = BlogPost::query()
                ->published()
                ->whereKeyNot($this->blogPost->id)
                ->latest('published_at')
                ->limit(3)
                ->get();
        }

        return $this->page(view('livewire.pages.blog.show-page', [
            'relatedPosts' => $relatedPosts,
        ]));
    }
}
