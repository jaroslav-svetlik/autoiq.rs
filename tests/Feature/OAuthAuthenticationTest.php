<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class OAuthAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_register_pages_show_google_option_only(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Nastavi preko Google')
            ->assertDontSee('Nastavi preko Facebook')
            ->assertSee(route('oauth.redirect', 'google'), false)
            ->assertDontSee(route('oauth.redirect', 'facebook'), false);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Nastavi preko Google')
            ->assertDontSee('Nastavi preko Facebook');
    }

    public function test_oauth_redirect_uses_configured_provider(): void
    {
        config()->set('services.google.client_id', 'google-client');
        config()->set('services.google.client_secret', 'google-secret');
        config()->set('services.google.redirect', 'https://autoiq.test/nalog/google/povratak');

        $driver = Mockery::mock();
        $driver->shouldReceive('scopes')->once()->with(['email'])->andReturnSelf();
        $driver->shouldReceive('redirect')->once()->andReturn(new RedirectResponse('https://accounts.google.com/oauth'));

        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($driver);

        $this->get(route('oauth.redirect', 'google'))
            ->assertRedirect('https://accounts.google.com/oauth');
    }

    public function test_oauth_callback_creates_user_and_social_account(): void
    {
        config()->set('services.google.client_id', 'google-client');
        config()->set('services.google.client_secret', 'google-secret');
        config()->set('services.google.redirect', 'https://autoiq.test/nalog/google/povratak');

        $providerUser = $this->providerUser(
            id: 'google-123',
            name: 'Milan Petrović',
            email: 'milan@example.com',
            avatar: 'https://lh3.googleusercontent.com/avatar.jpg',
        );

        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($providerUser);

        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($driver);

        $this->get(route('oauth.callback', 'google'))
            ->assertRedirect(route('account.dashboard'));

        $user = User::query()->where('email', 'milan@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->email_verified_at);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'google-123',
            'email' => 'milan@example.com',
        ]);
    }

    public function test_oauth_callback_links_existing_user_by_email(): void
    {
        config()->set('services.google.client_id', 'google-client');
        config()->set('services.google.client_secret', 'google-secret');
        config()->set('services.google.redirect', 'https://autoiq.test/nalog/google/povratak');

        $existingUser = User::factory()->create([
            'email' => 'jelena@example.com',
            'email_verified_at' => null,
        ]);

        $providerUser = $this->providerUser(
            id: 'google-456',
            name: 'Jelena Petrović',
            email: 'jelena@example.com',
            avatar: 'https://lh3.googleusercontent.com/avatar.jpg',
        );

        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($providerUser);

        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($driver);

        $this->get(route('oauth.callback', 'google'))
            ->assertRedirect(route('account.dashboard'));

        $this->assertAuthenticatedAs($existingUser);
        $this->assertNotNull($existingUser->fresh()->email_verified_at);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $existingUser->id,
            'provider' => 'google',
            'provider_id' => 'google-456',
        ]);
    }

    public function test_facebook_oauth_routes_are_not_available(): void
    {
        $this->get('/nalog/facebook/preusmeri')->assertNotFound();
        $this->get('/nalog/facebook/povratak')->assertNotFound();
    }

    public function test_unconfigured_provider_redirects_back_to_login(): void
    {
        config()->set('services.google.client_id', null);
        config()->set('services.google.client_secret', null);

        $this->get(route('oauth.redirect', 'google'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Google prijava trenutno nije dostupna.');
    }

    protected function providerUser(string $id, string $name, string $email, string $avatar): SocialiteUser
    {
        $user = Mockery::mock(SocialiteUser::class);
        $user->shouldReceive('getId')->andReturn($id);
        $user->shouldReceive('getNickname')->andReturn(null);
        $user->shouldReceive('getName')->andReturn($name);
        $user->shouldReceive('getEmail')->andReturn($email);
        $user->shouldReceive('getAvatar')->andReturn($avatar);

        return $user;
    }
}
