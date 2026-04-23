<?php

namespace App\Livewire\Pages\Blog;

use App\Livewire\Pages\PageComponent;
use App\Models\BlogPost;
use App\Services\BlogSeoLinkService;
use Illuminate\Contracts\View\View;

class ShowPage extends PageComponent
{
    public BlogPost $blogPost;

    protected BlogSeoLinkService $seoLinks;

    public function boot(BlogSeoLinkService $seoLinks): void
    {
        $this->seoLinks = $seoLinks;
    }

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
        $relatedPosts = $this->seoLinks->relatedPosts($this->blogPost, 3);

        return [[
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'url' => $articleUrl,
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
            'wordCount' => str_word_count(strip_tags((string) $this->blogPost->content)),
            'about' => collect([$this->blogPost->category, ...($this->blogPost->tags ?? [])])
                ->filter()
                ->unique()
                ->map(fn (string $topic) => [
                    '@type' => 'Thing',
                    'name' => $topic,
                ])
                ->values()
                ->all(),
            'mentions' => $relatedPosts
                ->map(fn (BlogPost $post) => [
                    '@type' => 'CreativeWork',
                    'name' => $post->title,
                    'url' => route('blog.show', $post),
                ])
                ->all(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $articleUrl,
            ],
            'isPartOf' => [
                '@type' => 'Blog',
                'name' => 'AutoIQ Blog',
                'url' => $blogUrl,
            ],
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
        return $this->page(view('livewire.pages.blog.show-page', [
            'contextualLinks' => $this->seoLinks->contextualBlogLinks($this->blogPost, 3),
            'marketLinks' => $this->seoLinks->marketLinks($this->blogPost, 3),
            'relatedPosts' => $this->seoLinks->relatedPosts($this->blogPost, 3),
        ]));
    }
}
