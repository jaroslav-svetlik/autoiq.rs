<?php

namespace App\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Livewire\Component;

abstract class PageComponent extends Component
{
    protected function page(View $view): View
    {
        return $view
            ->layout('components.layouts.app', [
                'meta' => $this->meta(),
                'jsonLd' => $this->jsonLd(),
            ])
            ->title($this->title());
    }

    protected function title(): string
    {
        return 'AutoIQ';
    }

    protected function meta(): array
    {
        return [
            'description' => 'Pametna platforma za auto oglase, analizu cena i procenu isplativosti kupovine u Srbiji.',
            'canonical' => request()->fullUrl(),
            'robots' => 'index,follow',
            'type' => 'website',
        ];
    }

    protected function jsonLd(): array
    {
        return [];
    }
}
