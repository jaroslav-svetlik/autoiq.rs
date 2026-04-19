@include('errors.partials.page', [
    'title' => 'Kratko održavanje | AutoIQ',
    'description' => 'AutoIQ je trenutno u kratkom održavanju. Pokušajte ponovo za nekoliko minuta.',
    'statusCode' => '503',
    'eyebrow' => 'Kratko održavanje',
    'heading' => 'AutoIQ se trenutno osvežava',
    'message' => 'Radimo kratko održavanje kako bi oglasi, nalozi i analiza cena nastavili da rade stabilno. Pokušajte ponovo za nekoliko minuta.',
    'primaryAction' => [
        'label' => 'Pokušaj ponovo',
        'url' => url()->current(),
    ],
    'secondaryAction' => [
        'label' => 'Početna strana',
        'url' => route('home'),
    ],
    'tertiaryAction' => [
        'label' => 'Kontakt',
        'url' => route('contact'),
    ],
    'panelTitle' => 'Šta se dešava',
    'panelItems' => [
        [
            'title' => 'Podaci ostaju sačuvani',
            'text' => 'Nalozi, favoriti, oglasi i poruke ostaju u sistemu dok se servis vraća online.',
        ],
        [
            'title' => 'Pauza je privremena',
            'text' => 'Održavanje najčešće traje kratko i ne zahteva nikakvu dodatnu akciju sa vaše strane.',
        ],
        [
            'title' => 'Vratite se uskoro',
            'text' => 'Osvežite stranicu za nekoliko minuta i nastavite tamo gde ste stali.',
        ],
    ],
])
