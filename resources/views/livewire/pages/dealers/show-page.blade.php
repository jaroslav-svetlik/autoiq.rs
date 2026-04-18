<div class="space-y-8">
    <section class="panel p-8 sm:p-10">
        <div class="grid gap-8 lg:grid-cols-[auto_1fr_auto] lg:items-center">
            <img src="{{ $dealerProfile->logoUrl() }}" alt="{{ $dealerProfile->company_name }}" class="h-24 w-24 rounded-3xl object-cover">

            <div>
                <div class="data-kicker">Dilerski profil</div>
                <h1 class="font-display mt-2 text-4xl font-bold text-white">{{ $dealerProfile->company_name }}</h1>
                <p class="mt-3 max-w-3xl text-sm leading-8 text-slate-300">{{ $dealerProfile->description ?: 'Diler još nije uneo detaljan opis poslovanja.' }}</p>
            </div>

            <div class="space-y-2 text-sm text-slate-300">
                @if($dealerProfile->phone)
                    <div>Telefon: {{ $dealerProfile->phone }}</div>
                @endif
                @if($dealerProfile->email)
                    <div>Email: {{ $dealerProfile->email }}</div>
                @endif
                @if($dealerProfile->city)
                    <div>Grad: {{ $dealerProfile->city }}</div>
                @endif
            </div>
        </div>
    </section>

    <section class="space-y-6">
        <div>
            <div class="data-kicker">Ponuda</div>
            <h2 class="section-title mt-2">Aktivni oglasi dilera</h2>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @forelse($dealerProfile->listings as $listing)
                <x-listing-card :listing="$listing" />
            @empty
                <div class="panel p-8 text-slate-300 lg:col-span-3">Ovaj diler još nema aktivne oglase.</div>
            @endforelse
        </div>
    </section>
</div>
