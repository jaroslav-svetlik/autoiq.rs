@props(['model', 'value' => '', 'label' => 'Opis'])
<div
    x-data="richTextEditor"
    wire:ignore
    data-model="{{ $model }}"
    data-editor-id="{{ $model }}-editor"
    data-label="{{ $label }}"
    data-content="{{ \App\Support\ListingDescription::html($value) }}"
>
    <label class="field-label" for="{{ $model }}-editor" @click.prevent="focus">{{ $label }}</label>
    <div class="rich-text-shell" :class="{ 'is-over-limit': overLimit }">
        <div class="rich-text-toolbar" role="group" aria-label="Formatiranje opisa" x-show="ready" x-cloak>
            @foreach([
                ['bold', 'bold', 'Podebljano', 'bold'],
                ['italic', 'italic', 'Kurziv', 'italic'],
                ['underline', 'underline', 'Podvučeno', 'underline'],
                ['bulletList', 'list', 'Lista sa tačkama', 'bulletList'],
                ['orderedList', 'list-ordered', 'Numerisana lista', 'orderedList'],
            ] as [$action, $icon, $title, $state])
                @if($action === 'bulletList') <span class="rich-text-divider" aria-hidden="true"></span> @endif
                <button type="button" @mousedown.prevent @click="{{ $action }}" :aria-pressed="active.{{ $state }}" title="{{ $title }}" aria-label="{{ $title }}">
                    <x-lucide-icon :name="$icon" />
                </button>
            @endforeach
            <span class="rich-text-divider" aria-hidden="true"></span>
            <button type="button" @mousedown.prevent @click="clear" title="Ukloni formatiranje" aria-label="Ukloni formatiranje"><x-lucide-icon name="remove-formatting" /></button>
            <div class="rich-text-history">
                <button type="button" @mousedown.prevent @click="undo" :disabled="!canUndo" title="Poništi" aria-label="Poništi"><x-lucide-icon name="undo-2" /></button>
                <button type="button" @mousedown.prevent @click="redo" :disabled="!canRedo" title="Ponovi" aria-label="Ponovi"><x-lucide-icon name="redo-2" /></button>
            </div>
        </div>
        <div class="rich-text-body" x-show="ready" x-cloak>
            <div x-ref="editor"></div>
            <span class="rich-text-placeholder" x-show="placeholderVisible" aria-hidden="true">Opišite stanje vozila, servisnu istoriju i ono što kupac treba da zna…</span>
        </div>
        <textarea x-ref="fallback" x-show="showFallback" @input="updateFallback" id="{{ $model }}-fallback" class="rich-text-fallback" aria-label="{{ $label }}" placeholder="Opišite stanje vozila, servisnu istoriju i ulaganja…">{{ \App\Support\ListingDescription::text($value) }}</textarea>
        <div class="rich-text-footer">
            <span id="{{ $model }}-help">Najmanje 30 karaktera. Kratko, jasno i pregledno.</span>
            <span class="rich-text-counter" :class="{ 'is-over-limit': overLimit }" x-show="ready" x-text="counter" aria-label="Broj karaktera"></span>
        </div>
    </div>
    <p class="mt-2 text-xs text-muted" role="status" x-show="failed" x-cloak>Formatiranje trenutno nije dostupno. Opis možete uneti kao običan tekst.</p>
</div>
