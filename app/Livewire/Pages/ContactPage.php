<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\ThrottlesRequests;
use App\Mail\ContactMessageMail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContactPage extends PageComponent
{
    use ThrottlesRequests;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $topic = '';

    public string $message = '';

    public string $website = '';

    public string $formRenderedAt = '';

    public function mount(): void
    {
        $this->refreshBotTimer();
    }

    public function send(): void
    {
        $this->throttle('contact-form', 3, 600);

        if ($this->hasTriggeredHoneypot()) {
            $this->pretendMessageWasAccepted();

            return;
        }

        if ($this->submittedTooQuickly()) {
            throw ValidationException::withMessages([
                'rate_limit' => 'Sačekajte nekoliko sekundi pre slanja poruke.',
            ]);
        }

        $validated = $this->validate($this->rules(), $this->messages());

        if ($this->containsTooManyLinks($validated['message'])) {
            throw ValidationException::withMessages([
                'message' => 'Poruka sadrži previše linkova. Pošaljite kraću poruku bez više linkova.',
            ]);
        }

        Mail::to(config('autoiq.contact.recipient_email'))->send(new ContactMessageMail([
            ...$validated,
            'submitted_at' => now()->format('d.m.Y. H:i'),
            'ip' => request()->ip(),
            'user_agent' => str((string) request()->userAgent())->limit(180)->toString(),
        ]));

        session()->flash('status', 'Poruka je poslata. Javićemo vam se čim proverimo zahtev.');
        $this->resetForm();
    }

    protected function title(): string
    {
        return 'Kontakt | AutoIQ';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => 'Kontaktirajte AutoIQ tim za pitanja o oglasima, nalogu, dilerima, saradnji i podršci pri korišćenju platforme.',
            'canonical' => route('contact'),
        ];
    }

    protected function jsonLd(): array
    {
        return [[
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'Kontakt | AutoIQ',
            'url' => route('contact'),
            'description' => 'Kontakt forma za AutoIQ podršku i pitanja korisnika.',
        ]];
    }

    public function render(): View
    {
        return $this->page(view('livewire.pages.contact-page', [
            'topics' => $this->topicOptions(),
        ]));
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'topic' => ['required', Rule::in($this->topicOptions())],
            'message' => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Unesite ime i prezime.',
            'name.min' => 'Ime mora imati bar dva karaktera.',
            'email.required' => 'Unesite email adresu.',
            'email.email' => 'Email adresa nije ispravna.',
            'topic.required' => 'Izaberite temu poruke.',
            'topic.in' => 'Izaberite jednu od ponuđenih tema.',
            'message.required' => 'Unesite poruku.',
            'message.min' => 'Poruka mora imati bar 20 karaktera.',
            'message.max' => 'Poruka može imati najviše 2000 karaktera.',
        ];
    }

    protected function topicOptions(): array
    {
        return [
            'Pitanje o kupovini vozila',
            'Pitanje o oglasu',
            'Dilerski nalog',
            'Tehnička podrška',
            'Saradnja',
            'Drugo',
        ];
    }

    protected function hasTriggeredHoneypot(): bool
    {
        return filled($this->website);
    }

    protected function submittedTooQuickly(): bool
    {
        return now()->timestamp - (int) $this->formRenderedAt < 2;
    }

    protected function containsTooManyLinks(string $message): bool
    {
        preg_match_all('/https?:\/\/|www\./i', $message, $matches);

        return count($matches[0]) > 2;
    }

    protected function pretendMessageWasAccepted(): void
    {
        session()->flash('status', 'Poruka je poslata. Javićemo vam se čim proverimo zahtev.');
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset('name', 'email', 'phone', 'topic', 'message', 'website');
        $this->refreshBotTimer();
    }

    protected function refreshBotTimer(): void
    {
        $this->formRenderedAt = (string) now()->timestamp;
    }
}
