<?php

namespace Tests\Feature;

use App\Livewire\Pages\ContactPage;
use App\Mail\ContactMessageMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_renders_livewire_form_with_bot_protection_and_loading_state(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Pošaljite upit')
            ->assertSee('name="website"', false)
            ->assertSee('wire:loading.attr="disabled"', false)
            ->assertSee('animate-spin', false);
    }

    public function test_contact_form_sends_email(): void
    {
        Mail::fake();
        config()->set('autoiq.contact.recipient_email', 'kontakt@autoiq.rs');

        Livewire::test(ContactPage::class)
            ->set('name', 'Milan Petrović')
            ->set('email', 'milan@example.com')
            ->set('phone', '+381 64 123 4567')
            ->set('topic', 'Pitanje o oglasu')
            ->set('message', 'Zanima me kako mogu da prijavim neispravan oglas na platformi.')
            ->set('formRenderedAt', (string) now()->subSeconds(5)->timestamp)
            ->call('send')
            ->assertHasNoErrors()
            ->assertSet('name', '')
            ->assertSet('email', '')
            ->assertSet('message', '');

        Mail::assertSent(ContactMessageMail::class, function (ContactMessageMail $mail) {
            return $mail->hasTo('kontakt@autoiq.rs')
                && $mail->messageData['email'] === 'milan@example.com'
                && $mail->messageData['topic'] === 'Pitanje o oglasu';
        });
    }

    public function test_contact_form_honeypot_accepts_without_sending_email(): void
    {
        Mail::fake();

        Livewire::test(ContactPage::class)
            ->set('name', 'Spam Bot')
            ->set('email', 'bot@example.com')
            ->set('topic', 'Drugo')
            ->set('message', 'Ovo izgleda kao poruka, ali honeypot polje je popunjeno.')
            ->set('website', 'https://spam.example.com')
            ->set('formRenderedAt', (string) now()->subSeconds(5)->timestamp)
            ->call('send')
            ->assertHasNoErrors()
            ->assertSet('website', '');

        Mail::assertNothingSent();
    }

    public function test_contact_form_blocks_too_fast_submissions(): void
    {
        Mail::fake();

        Livewire::test(ContactPage::class)
            ->set('name', 'Milan Petrović')
            ->set('email', 'milan@example.com')
            ->set('topic', 'Tehnička podrška')
            ->set('message', 'Potrebna mi je pomoć oko korišćenja kontakt forme na platformi.')
            ->set('formRenderedAt', (string) now()->timestamp)
            ->call('send')
            ->assertHasErrors(['rate_limit']);

        Mail::assertNothingSent();
    }
}
