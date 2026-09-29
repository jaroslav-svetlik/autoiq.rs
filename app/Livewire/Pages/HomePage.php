<?php

namespace App\Livewire\Pages;

use App\Livewire\Pages\Listings\IndexPage;

/** The home page and the listings directory share one searchable catalog. */
class HomePage extends IndexPage
{
    protected function meta(): array
    {
        return [...parent::meta(), 'canonical' => route('home')];
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
}
