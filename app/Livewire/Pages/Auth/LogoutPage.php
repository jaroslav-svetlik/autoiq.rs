<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Pages\PageComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class LogoutPage extends PageComponent
{
    public function mount(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirectRoute('home', navigate: true);
    }

    protected function title(): string
    {
        return 'Odjava | AutoIQ';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'robots' => 'noindex,nofollow',
        ];
    }

    public function render(): View
    {
        return $this->page(view('livewire.pages.auth.logout-page'));
    }
}
