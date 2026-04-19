<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    public function test_missing_page_uses_branded_404_view(): void
    {
        $this->get('/nepostojeca-auto-iq-strana')
            ->assertNotFound()
            ->assertSee('Stranica nije pronađena | AutoIQ')
            ->assertSee('Ova strana nije pronađena')
            ->assertSee('Pretraži oglase')
            ->assertSee('Brzi put nazad')
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
    }

    public function test_service_unavailable_uses_branded_503_view(): void
    {
        Route::get('/__test-503', fn () => abort(503));

        $this->get('/__test-503')
            ->assertStatus(503)
            ->assertSee('Kratko održavanje | AutoIQ')
            ->assertSee('AutoIQ se trenutno osvežava')
            ->assertSee('Pokušaj ponovo')
            ->assertSee('Šta se dešava')
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
    }
}
