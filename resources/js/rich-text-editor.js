// Keep ProseMirror outside Alpine's reactive proxy to preserve transaction identity.
export default () => {
    let editor;
    let disposed = false;

    return {
        ready: false,
        failed: false,
        count: 0,
        empty: true,
        active: {},
        canUndo: false,
        canRedo: false,

        async init() {
            try {
                const { createEditor } = await import('./rich-text-engine');
                if (disposed) return;

                // Keep any text entered while the editor bundle was loading.
                const fallback = this.$refs.fallback;
                const content = fallback.value === fallback.defaultValue
                    ? this.$root.dataset.content
                    : fallback.value.split(/\r?\n/).map((text) => ({
                        type: 'paragraph', content: text ? [{ type: 'text', text }] : [],
                    }));

                editor = createEditor({
                    element: this.$refs.editor,
                    content,
                    editorProps: {
                        attributes: {
                            id: this.$root.dataset.editorId,
                            class: 'rich-text-content',
                            role: 'textbox',
                            'aria-label': this.$root.dataset.label,
                            'aria-multiline': 'true',
                            'aria-required': 'true',
                            'aria-describedby': `${this.$root.dataset.model}-help ${this.$root.dataset.model}-error`,
                            spellcheck: 'true',
                        },
                    },
                    onUpdate: ({ editor: current }) => {
                        this.$wire.$set(this.$root.dataset.model, current.isEmpty ? '' : current.getHTML(), false);
                    },
                    onTransaction: () => this.refresh(),
                });
                this.ready = true;
                this.refresh();
            } catch {
                this.failed = true;
            }
        },

        refresh() {
            if (!editor || disposed) return;
            const text = editor.getText({ blockSeparator: ' ' })
                .replace(/[\u200B\uFEFF]/g, '').replace(/\s+/gu, ' ').trim();
            this.count = Array.from(text).length;
            this.empty = editor.isEmpty;
            this.active = Object.fromEntries(['bold', 'italic', 'underline', 'bulletList', 'orderedList']
                .map((name) => [name, editor.isActive(name)]));
            this.canUndo = editor.can().undo();
            this.canRedo = editor.can().redo();
        },

        get counter() { return `${this.count.toLocaleString('sr-Latn-RS')} / 5.000`; },
        get overLimit() { return this.count > 5000; },
        get showFallback() { return !this.ready; },
        get placeholderVisible() { return this.ready && this.empty; },
        updateFallback() { this.$wire.$set(this.$root.dataset.model, this.$refs.fallback.value, false); },
        focus() { if (editor) editor.commands.focus(); else this.$refs.fallback.focus(); },
        bold() { editor?.chain().focus().toggleBold().run(); },
        italic() { editor?.chain().focus().toggleItalic().run(); },
        underline() { editor?.chain().focus().toggleUnderline().run(); },
        bulletList() { editor?.chain().focus().toggleBulletList().run(); },
        orderedList() { editor?.chain().focus().toggleOrderedList().run(); },
        clear() { editor?.chain().focus().unsetAllMarks().clearNodes().run(); },
        undo() { editor?.chain().focus().undo().run(); },
        redo() { editor?.chain().focus().redo().run(); },
        destroy() {
            disposed = true;
            editor?.destroy();
        },
    };
};
