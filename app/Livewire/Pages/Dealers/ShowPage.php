<?php

namespace App\Livewire\Pages\Dealers;

use App\Livewire\Pages\PageComponent;
use App\Models\DealerProfile;
use Illuminate\Contracts\View\View;

class ShowPage extends PageComponent
{
    public DealerProfile $dealerProfile;

    public function mount(DealerProfile $dealerProfile): void
    {
        $this->dealerProfile = $dealerProfile->load([
            'user',
            'listings' => fn ($query) => $query->published()->with(['images', 'priceHistories'])->orderByDesc('published_at'),
        ]);
    }

    protected function title(): string
    {
        return "{$this->dealerProfile->company_name} | Diler | AutoIQ";
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => str($this->dealerProfile->description ?: "Pregledajte AutoIQ diler profil {$this->dealerProfile->company_name}.")->limit(155)->toString(),
            'canonical' => route('dealers.show', $this->dealerProfile),
        ];
    }

    public function render(): View
    {
        return $this->page(view('livewire.pages.dealers.show-page'));
    }
}
