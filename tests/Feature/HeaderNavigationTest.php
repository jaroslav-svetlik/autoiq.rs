<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeaderNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_keeps_contact_last_and_add_listing_as_separate_cta(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('data-desktop-primary-nav', false)
            ->assertSee('data-header-add-listing', false)
            ->assertSee('data-mobile-menu', false)
            ->assertSee('data-mobile-add-listing', false);

        $html = $response->getContent();
        $desktopNav = $this->extractAttributeBlock($html, 'data-desktop-primary-nav');
        $mobileNav = $this->extractAttributeBlock($html, 'data-mobile-primary-nav');

        $this->assertStringContainsString('Početna', $desktopNav);
        $this->assertStringContainsString('Blog', $desktopNav);
        $this->assertStringContainsString('Oglasi', $desktopNav);
        $this->assertStringEndsWith('</nav>', trim($desktopNav));
        $this->assertLessThan(strpos($desktopNav, 'Kontakt'), strpos($desktopNav, 'Oglasi'));
        $this->assertLessThan(strpos($mobileNav, 'Kontakt'), strpos($mobileNav, 'Oglasi'));
        $this->assertStringNotContainsString('Dodaj oglas', $desktopNav);
        $this->assertStringContainsString('Dodaj oglas', $html);
    }

    public function test_authenticated_user_controls_are_inside_account_dropdown(): void
    {
        $user = User::factory()->create([
            'name' => 'Milan Petrović',
            'email' => 'milan@example.com',
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('data-user-menu', false)
            ->assertSee('Milan Petrović')
            ->assertSee('milan@example.com')
            ->assertSee('Korisnik')
            ->assertSee('Profil i oglasi')
            ->assertSee('Odjava')
            ->assertSee('data-mobile-menu', false);
    }

    private function extractAttributeBlock(string $html, string $attribute): string
    {
        $start = strpos($html, $attribute);

        $this->assertIsInt($start, "Missing {$attribute} block.");

        $navStart = strrpos(substr($html, 0, $start), '<nav');
        $navEnd = strpos($html, '</nav>', $start);

        $this->assertIsInt($navStart, "Missing {$attribute} opening nav.");
        $this->assertIsInt($navEnd, "Missing {$attribute} closing nav.");

        return substr($html, $navStart, $navEnd - $navStart + strlen('</nav>'));
    }
}
