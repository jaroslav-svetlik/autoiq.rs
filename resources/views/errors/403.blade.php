@include('errors.partials.page', [
    'title' => 'Pristup nije dozvoljen | AutoIQ',
    'description' => 'Traženi AutoIQ resurs nije dostupan za ovaj zahtev. Vratite se na oglase, blog ili početnu stranu.',
    'statusCode' => '403',
    'eyebrow' => 'Pristup ograničen',
    'heading' => 'Ovaj zahtev nije dozvoljen',
    'message' => 'Adresa koju ste otvorili ne može da se prikaže na taj način. Ako tražite oglas, vodič ili pomoć oko naloga, nastavite kroz glavne AutoIQ stranice.',
    'primaryAction' => [
        'label' => 'Pretraži oglase',
        'url' => route('listings.index'),
    ],
    'secondaryAction' => [
        'label' => 'Nazad na početnu',
        'url' => route('home'),
    ],
    'tertiaryAction' => [
        'label' => 'Kontakt',
        'url' => route('contact'),
    ],
    'panelTitle' => 'Šta možete odmah',
    'panelItems' => [
        [
            'title' => 'Koristite glavne stranice',
            'text' => 'Oglasi, blog i kontakt strana ostaju dostupni kroz standardnu navigaciju.',
        ],
        [
            'title' => 'Proverite adresu',
            'text' => 'Ako je link kopiran ručno, proverite da li u adresi nema greške ili viška karaktera.',
        ],
        [
            'title' => 'Ne šaljite osetljive fajlove',
            'text' => 'AutoIQ nikada ne traži lozinke, kartice ili privatne dokumente kroz ovakve linkove.',
        ],
    ],
])
