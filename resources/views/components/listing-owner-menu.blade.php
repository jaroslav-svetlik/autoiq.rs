@props(['listing'])
<details class="listing-owner-menu" data-listing-menu wire:ignore.self>
    <summary class="listing-menu-trigger" aria-label="Opcije oglasa: {{ $listing->title }}">
        <x-lucide-icon name="ellipsis" />
    </summary>
    <div class="listing-menu-panel">
        <a href="{{ route('listings.edit', $listing) }}" wire:navigate data-listing-action><x-lucide-icon name="pencil" />Izmeni oglas</a>
        @if($listing->status === \App\Enums\ListingStatus::Published)
            <button type="button" wire:click="setListingStatus({{ $listing->id }}, 'paused')" wire:loading.attr="disabled" wire:target="setListingStatus,deleteListing" data-listing-action><x-lucide-icon name="pause" />Pauziraj oglas</button>
        @elseif(in_array($listing->status, [\App\Enums\ListingStatus::Paused, \App\Enums\ListingStatus::Sold], true) && $listing->published_at)
            <button type="button" wire:click="setListingStatus({{ $listing->id }}, 'published')" wire:loading.attr="disabled" wire:target="setListingStatus,deleteListing" data-listing-action><x-lucide-icon name="play" />Ponovo aktiviraj</button>
        @endif
        @if(in_array($listing->status, [\App\Enums\ListingStatus::Published, \App\Enums\ListingStatus::Paused], true))
            <button type="button" wire:click="setListingStatus({{ $listing->id }}, 'sold')" wire:loading.attr="disabled" wire:target="setListingStatus,deleteListing" data-listing-action><x-lucide-icon name="badge-check" />Označi kao prodato</button>
        @endif
        <div class="listing-menu-divider"></div>
        <button type="button" wire:click="requestListingDeletion({{ $listing->id }})" wire:loading.attr="disabled" wire:target="setListingStatus,requestListingDeletion,deleteListing" class="listing-menu-delete" data-listing-action><x-lucide-icon name="trash-2" />Obriši oglas</button>
    </div>
</details>
