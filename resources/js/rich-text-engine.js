import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';

export function createEditor(options) {
    return new Editor({
        ...options,
        injectCSS: false,
        extensions: [StarterKit.configure({
            blockquote: false,
            code: false,
            codeBlock: false,
            heading: false,
            horizontalRule: false,
            link: false,
            strike: false,
            dropcursor: false,
            gapcursor: false,
            trailingNode: false,
        })],
    });
}
