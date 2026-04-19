@include('errors.partials.page', [
    'title' => 'Stranica nije pronađena | AutoIQ',
    'description' => 'Tražena AutoIQ stranica nije pronađena. Vratite se na oglase, blog ili početnu stranu.',
    'statusCode' => '404',
    'eyebrow' => 'Putanja nije pronađena',
    'heading' => 'Ova strana nije pronađena',
    'message' => 'Link je možda promenjen, oglas više nije aktivan ili je adresa pogrešno uneta. Najbrže je da nastavite od pretrage oglasa ili se vratite na početnu stranu.',
    'primaryAction' => [
        'label' => 'Pretraži oglase',
        'url' => route('listings.index'),
    ],
    'secondaryAction' => [
        'label' => 'Nazad na početnu',
        'url' => route('home'),
    ],
    'tertiaryAction' => [
        'label' => 'Otvori blog',
        'url' => route('blog.index'),
    ],
    'panelTitle' => 'Brzi put nazad',
    'panelItems' => [
        [
            'title' => 'Tražite automobil',
            'text' => 'Pređite na oglase i filtrirajte ponudu po modelu, ceni, godištu i kilometraži.',
        ],
        [
            'title' => 'Čitate vodiče',
            'text' => 'Blog pokriva proveru vozila, pregovaranje, troškove održavanja i tržišne signale.',
        ],
        [
            'title' => 'Treba vam pomoć',
            'text' => 'Kontakt strana je dostupna za pitanja oko naloga, oglasa i korišćenja platforme.',
        ],
    ],
])
