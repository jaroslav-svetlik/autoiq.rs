const normalize = (value) => String(value).toLocaleLowerCase('sr-Latn').normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'dj');

export default () => ({
    ready: false,
    open: false,
    disabled: false,
    required: false,
    invalid: false,
    value: '',
    label: '',
    query: '',
    options: [],
    active: -1,
    listId: '',
    popupStyle: '',
    model: null,
    cleanups: [],
    observer: null,
    typeahead: '',
    lastTypedAt: 0,

    init() {
        this.listId = this.$id('select-listbox');
        this.$nextTick(() => {
            const native = this.$refs.native;
            this.model = [...native.attributes].find((attribute) => attribute.name.startsWith('wire:model'))?.value;
            this.refresh();
            this.observer = new MutationObserver(() => this.refresh());
            this.observer.observe(native, { childList: true, subtree: true, characterData: true, attributes: true, attributeFilter: ['disabled', 'selected', 'value', 'label', 'required', 'aria-invalid'] });
            if (this.model) this.cleanups.push(this.$wire.$watch(this.model, () => this.refresh()));
            const listen = (target, event, handler, options) => {
                target.addEventListener(event, handler, options);
                this.cleanups.push(() => target.removeEventListener(event, handler, options));
            };
            listen(native, 'change', () => { this.value = native.value; });
            listen(native, 'invalid', (event) => { event.preventDefault(); this.invalid = true; this.show(); });
            const labels = [...native.labels];
            if (!labels.length) {
                const siblingLabel = this.$root.parentElement.querySelector(':scope > label');
                if (siblingLabel) labels.push(siblingLabel);
            }
            for (const label of labels) {
                listen(label, 'click', (event) => {
                    if (this.$root.contains(event.target)) return;
                    event.preventDefault();
                    this.$refs.trigger.focus();
                });
            }
            listen(document, 'pointerdown', (event) => {
                if (this.open && !this.$root.contains(event.target) && !this.$refs.popup.contains(event.target)) this.close();
            });
            listen(document, 'focusin', (event) => {
                if (this.open && !this.$root.contains(event.target) && !this.$refs.popup.contains(event.target)) this.close();
            });
            listen(window, 'autoiq:select-open', (event) => { if (event.detail !== this.listId) this.close(); });
            listen(document, 'livewire:navigating', () => this.close());
            listen(window, 'resize', () => this.position());
            listen(window, 'scroll', (event) => {
                if (this.open && !this.$refs.popup.contains(event.target)) this.position();
            }, true);
            if (window.visualViewport) {
                listen(window.visualViewport, 'resize', () => this.position());
                listen(window.visualViewport, 'scroll', () => this.position());
            }
            if (native.form) listen(native.form, 'reset', () => this.$nextTick(() => this.refresh()));
            this.ready = true;
        });
    },

    destroy() {
        this.observer?.disconnect();
        this.cleanups.forEach((cleanup) => cleanup());
    },

    get nativeTabIndex() { return this.ready ? -1 : 0; },
    get wrapperClass() { return [this.open ? 'is-open' : '', this.disabled ? 'is-disabled' : '', this.invalid ? 'is-invalid' : ''].join(' '); },
    get selectedLabel() { return this.options.find((option) => option.value === this.value)?.label || this.options[0]?.label || 'Izaberite'; },
    get searchable() { return this.options.length >= 10; },
    get searchLabel() { return `Pretraži opcije: ${this.label}`; },
    get filteredOptions() { const term = normalize(this.query.trim()); return this.options.filter((option) => normalize(option.label).includes(term)); },
    get noResults() { return !this.filteredOptions.length; },
    get activeId() { return this.open && this.active >= 0 ? `${this.listId}-${this.active}` : null; },
    optionId(option) { return `${this.listId}-${option.index}`; },
    isSelected(option) { return option.value === this.value; },
    optionClass(option) { return [this.isSelected(option) ? 'is-selected' : '', option.index === this.active ? 'is-active' : '', option.disabled ? 'is-disabled' : ''].join(' '); },

    refresh() {
        const native = this.$refs.native;
        this.options = [...native.options].filter((option) => !option.hidden).map((option) => ({
            index: option.index, key: `${option.value}-${option.index}`, value: option.value, label: option.label,
            disabled: option.disabled || (option.parentElement.tagName === 'OPTGROUP' && option.parentElement.disabled),
        }));
        if (this.model) native.value = String(this.$wire.$get(this.model) ?? '');
        this.value = native.value;
        this.disabled = native.disabled;
        this.required = native.required;
        this.invalid = native.getAttribute('aria-invalid') === 'true';
        this.label = native.getAttribute('aria-label')
            || [...native.labels].map((label) => label.querySelector('b')?.textContent || label.textContent).join(' ').trim()
            || this.$root.parentElement.querySelector(':scope > label')?.textContent.trim()
            || 'Izaberite opciju';
        if (this.disabled) this.close();
        if (this.open) this.filterChanged();
    },

    toggle() { if (this.open) this.close(); else this.show(); },
    show() {
        if (this.disabled || this.open) return;
        window.dispatchEvent(new CustomEvent('autoiq:select-open', { detail: this.listId }));
        this.query = '';
        this.active = this.options.find((option) => option.value === this.value && !option.disabled)?.index ?? this.options.find((option) => !option.disabled)?.index ?? -1;
        this.open = true;
        this.$nextTick(() => {
            this.position();
            (this.searchable ? this.$refs.search : this.$refs.trigger).focus({ preventScroll: true });
            this.scrollActive();
        });
    },
    close(restoreFocus = false) {
        this.open = false;
        this.typeahead = '';
        if (restoreFocus) this.$refs.trigger.focus({ preventScroll: true });
    },
    choose(index) {
        const option = this.options.find((item) => item.index === index);
        if (!option || option.disabled || this.disabled) return;
        const native = this.$refs.native;
        const changed = native.value !== option.value;
        native.value = option.value;
        this.value = option.value;
        this.close(true);
        if (changed) {
            // Retain Livewire's original model/change/blur timing and wire:change actions.
            native.dispatchEvent(new Event('input', { bubbles: true }));
            native.dispatchEvent(new Event('change', { bubbles: true }));
            native.dispatchEvent(new FocusEvent('blur', { bubbles: true }));
        }
    },
    filterChanged() {
        this.$nextTick(() => {
            const enabled = this.filteredOptions.filter((option) => !option.disabled);
            if (!enabled.some((option) => option.index === this.active)) this.active = enabled[0]?.index ?? -1;
            this.position();
            this.scrollActive();
        });
    },
    highlight(index) { if (!this.options.find((option) => option.index === index)?.disabled) this.active = index; },
    move(direction) {
        const enabled = this.filteredOptions.filter((option) => !option.disabled);
        if (!enabled.length) return;
        const current = enabled.findIndex((option) => option.index === this.active);
        this.active = enabled[Math.max(0, Math.min(enabled.length - 1, current + direction))].index;
        this.scrollActive();
    },
    scrollActive() {
        this.$nextTick(() => {
            const item = document.getElementById(this.activeId);
            const list = this.$refs.list;
            if (!item || !list) return;
            const itemRect = item.getBoundingClientRect();
            const listRect = list.getBoundingClientRect();
            if (itemRect.top < listRect.top) list.scrollTop -= listRect.top - itemRect.top;
            else if (itemRect.bottom > listRect.bottom) list.scrollTop += itemRect.bottom - listRect.bottom;
        });
    },
    onKeydown(event) {
        const inSearch = event.target === this.$refs.search;
        if (event.key === 'Tab') { if (this.open) this.close(inSearch); return; }
        if (event.key === 'Escape' && this.open) { event.preventDefault(); event.stopPropagation(); this.close(true); return; }
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            if (!this.open) this.show(); else this.move(event.key === 'ArrowDown' ? 1 : -1);
            return;
        }
        if (event.key === 'Enter' || (event.key === ' ' && !inSearch)) {
            event.preventDefault();
            if (this.open) this.choose(this.active); else this.show();
            return;
        }
        if (this.open && !inSearch && ['Home', 'End'].includes(event.key)) {
            event.preventDefault();
            const enabled = this.filteredOptions.filter((option) => !option.disabled);
            this.active = (event.key === 'Home' ? enabled[0] : enabled[enabled.length - 1])?.index ?? -1;
            this.scrollActive();
            return;
        }
        if (!inSearch && event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
            event.preventDefault();
            if (!this.open) this.show();
            const now = Date.now();
            this.typeahead = now - this.lastTypedAt > 700 ? event.key : this.typeahead + event.key;
            this.lastTypedAt = now;
            const match = this.options.find((option) => !option.disabled && normalize(option.label).startsWith(normalize(this.typeahead)));
            if (match) { this.active = match.index; this.scrollActive(); }
        }
    },
    position() {
        if (!this.open || !this.$refs.popup) return;
        const rect = this.$refs.trigger.getBoundingClientRect();
        if (!rect.width) { this.close(); return; }
        const viewport = window.visualViewport;
        const viewportTop = viewport?.offsetTop || 0;
        const viewportLeft = viewport?.offsetLeft || 0;
        const viewportHeight = viewport?.height || window.innerHeight;
        const viewportWidth = viewport?.width || document.documentElement.clientWidth;
        const below = viewportTop + viewportHeight - rect.bottom - 14;
        const above = rect.top - viewportTop - 14;
        const desired = Math.min(336, Math.max(54, this.filteredOptions.length * 38 + 12) + (this.searchable ? 49 : 0));
        const upwards = below < desired && above > below;
        const height = Math.min(desired, Math.max(90, upwards ? above : below));
        const width = Math.min(Math.max(rect.width, 220), viewportWidth - 24);
        const left = Math.max(viewportLeft + 12, Math.min(rect.left, viewportLeft + viewportWidth - width - 12));
        const top = upwards ? Math.max(viewportTop + 12, rect.top - height - 6) : rect.bottom + 6;
        this.popupStyle = `left:${left}px;top:${top}px;width:${width}px;max-height:${height}px;`;
    },
});
