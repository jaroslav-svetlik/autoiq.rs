@props(['blog' => true])
<section class="drive-banner">
    <div class="drive-banner-content">
        <span class="feature-icon"><x-icon :name="$blog ? 'book' : 'car'" /></span>
        <div>
            <span class="text-brand text-xs font-semibold">{{ $blog ? 'AutoIQ.rs savetuje' : 'Vaša sledeća vožnja' }}</span>
            <h2>{{ $blog ? 'Niste sigurni koji automobil je pravi za vas?' : 'Spreman za sledeću vožnju?' }}</h2>
            <p>{{ $blog ? 'Pročitajte naše vodiče, poređenja i savete za lakši izbor.' : 'Pronađite automobil koji odgovara vašem životu.' }}</p>
            <a href="{{ $blog ? route('blog.index') : route('home') }}" wire:navigate class="btn-primary">{{ $blog ? 'Posetite naš blog' : 'Pregledaj oglase' }} <x-icon name="arrow" /></a>
        </div>
    </div>
</section>
