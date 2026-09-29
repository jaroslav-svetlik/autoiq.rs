@props(['variant' => 'default'])
@php
    $wrapperClasses = trim(str_replace('input-shell', '', $attributes->get('class', '')));
    $model = collect($attributes->getAttributes())->filter(fn ($value, $key) => str_starts_with($key, 'wire:model'))->first();
@endphp
<div class="select-field {{ $wrapperClasses }} {{ $variant === 'compact' ? 'select-field-compact' : '' }}"
    x-data="selectField" x-id="['select-listbox']" x-bind:class="wrapperClass">
    {{-- Keep the native control as the Livewire/form binding and progressive fallback. --}}
    <select {{ $attributes->except('class') }} class="input-shell w-full" x-ref="native"
        x-bind:hidden="ready" x-bind:aria-hidden="ready" x-bind:tabindex="nativeTabIndex"
        @if($model && $errors->has($model)) aria-invalid="true" @endif>
        {{ $slot }}
    </select>
    <div wire:ignore class="select-ui" x-cloak x-show="ready">
        <button type="button" class="select-trigger" x-ref="trigger" role="combobox" aria-haspopup="listbox"
            x-bind:aria-label="label" x-bind:aria-expanded="open" x-bind:aria-controls="listId"
            x-bind:aria-activedescendant="activeId" x-bind:aria-invalid="invalid" x-bind:aria-required="required"
            x-bind:disabled="disabled" x-on:click="toggle()" x-on:keydown="onKeydown($event)">
            <span class="select-value" x-text="selectedLabel"></span><x-icon name="chevron" />
        </button>
        <template x-teleport="body">
            <div x-cloak x-show="open" x-ref="popup" class="select-popup" x-bind:style="popupStyle">
                <div class="select-search" x-show="searchable">
                    <x-icon name="search" />
                    <input type="search" x-ref="search" x-model="query" x-on:input="filterChanged()" x-on:keydown="onKeydown($event)"
                        role="combobox" aria-autocomplete="list" aria-expanded="true" autocomplete="off" placeholder="Pretraži opcije..."
                        x-bind:aria-label="searchLabel" x-bind:aria-controls="listId" x-bind:aria-activedescendant="activeId">
                </div>
                <ul class="select-options" role="listbox" x-ref="list" x-bind:id="listId" x-bind:aria-label="label">
                    <template x-for="option in filteredOptions" x-bind:key="option.key">
                        <li role="option" class="select-option" x-bind:id="optionId(option)" x-bind:aria-selected="isSelected(option)"
                            x-bind:aria-disabled="option.disabled" x-bind:class="optionClass(option)"
                            x-on:pointerdown.prevent x-on:mousemove="highlight(option.index)" x-on:click="choose(option.index)">
                            <span x-text="option.label"></span><x-icon name="check" x-show="isSelected(option)" />
                        </li>
                    </template>
                </ul>
                <p class="select-empty" x-show="noResults" role="status">Nema rezultata za ovu pretragu.</p>
            </div>
        </template>
    </div>
</div>
