<?php

namespace App\Livewire\Pages;

use App\Models\BlogPost;
use App\Services\BlogSeoLinkService;
use App\Services\MarketInsightsService;
use Illuminate\Contracts\View\View;

class HomePage extends PageComponent
{
    public string $heroSearch = '';

    public function search(): void
    {
        $this->redirectRoute('listings.index', ['search' => trim($this->heroSearch)], navigate: true);
    }

    public function exploreModel(string $brand, string $model): void
    {
        $this->redirectRoute('listings.index', [
            'brand' => $brand,
            'model' => $model,
        ], navigate: true);
    }

    protected function title(): string
    {
        return 'AutoIQ | Pametna kupovina automobila u Srbiji';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => 'AutoIQ analizira tržište polovnih automobila u Srbiji, meri isplativost oglasa i pomaže pri donošenju odluke.',
            'canonical' => route('home'),
        ];
    }

    protected function jsonLd(): array
    {
        return [[
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'AutoIQ',
            'url' => route('home'),
        ]];
    }

    public function render(): View
    {
        $seoLinks = app(BlogSeoLinkService::class);

        return $this->page(view('livewire.pages.home-page', [
            'insights' => app(MarketInsightsService::class)->home(),
            'priorityGuides' => $seoLinks->priorityGuides(6),
            'latestBlogPosts' => BlogPost::query()
                ->published()
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]));
    }
}
