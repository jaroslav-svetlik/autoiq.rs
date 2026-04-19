<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_public_pages_include_security_headers(): void
    {
        $response = $this->get(route('login'));

        $response
            ->assertOk()
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
            ->assertHeader('Content-Security-Policy');

        $csp = $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString('https://static.cloudflareinsights.com', $csp);
        $this->assertStringContainsString('https://cloudflareinsights.com', $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);
    }

    public function test_livewire_uses_csp_safe_assets(): void
    {
        $this->assertTrue(config('livewire.csp_safe'));

        $this->get(route('login'))
            ->assertSee('/livewire.min.js?csp=1&id=', false);
    }

    public function test_www_domain_redirects_to_apex_https_domain(): void
    {
        $this->get('https://www.autoiq.rs/oglasi?brand=BMW')
            ->assertMovedPermanently()
            ->assertRedirect('https://autoiq.rs/oglasi?brand=BMW')
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    public function test_apex_https_domain_does_not_redirect(): void
    {
        $this->get('https://autoiq.rs/nalog/prijava')
            ->assertOk();
    }
}
