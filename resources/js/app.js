import './bootstrap';
import selectField from './select-field';

const dropdownMenus = () => document.querySelectorAll('details[data-nav-menu], details.quick-range');

document.addEventListener('click', (event) => {
    dropdownMenus().forEach((menu) => {
        if (menu.open && !menu.contains(event.target)) menu.open = false;
    });
});

document.addEventListener('toggle', (event) => {
    if (!event.target.matches('details[data-nav-menu], details.quick-range') || !event.target.open) return;
    dropdownMenus().forEach((menu) => { if (menu !== event.target) menu.open = false; });
}, true);

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    dropdownMenus().forEach((menu) => {
        if (!menu.open) return;
        menu.open = false;
        if (menu.contains(document.activeElement)) menu.querySelector('summary')?.focus();
    });
});

document.addEventListener('alpine:init', () => {
    window.Alpine.data('selectField', selectField);
    window.Alpine.data('listingActions', () => ({
        shareStatus: '',
        showShareLink: false,
        async copyLink() {
            const url = this.$root.dataset.listingUrl;
            try {
                await navigator.clipboard.writeText(url);
                this.shareStatus = 'Link oglasa je kopiran.';
                this.showShareLink = false;
            } catch {
                this.showShareLink = true;
                this.shareStatus = 'Kopirajte link oglasa ispod.';
                this.$nextTick(() => this.$refs.shareLink.select());
            }
        },
    }));

    window.Alpine.data('paginationControls', () => ({
        scrollToTarget() {
            const selector = this.$root.dataset.scrollTarget;
            if (selector) {
                document.querySelector(selector)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },
    }));

    window.Alpine.data('catalogBrowser', () => ({
        filtersOpen: false,
        listMode: false,
        get gridMode() { return !this.listMode; },
        get filterPanelClass() { return this.filtersOpen ? 'filters-open' : ''; },
        get resultLayout() { return this.listMode ? 'is-list' : ''; },
        get gridButtonClass() { return this.listMode ? '' : 'is-active'; },
        get listButtonClass() { return this.listMode ? 'is-active' : ''; },
        closeFilters() { this.filtersOpen = false; this.scrollToResults(); },
        scrollToResults() {
            this.$el.querySelectorAll('details.quick-range[open]').forEach((range) => range.removeAttribute('open'));
            document.getElementById('rezultati')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
    }));

    window.Alpine.data('listingGallery', () => ({
        active: 0,
        lightboxOpen: false,
        zoom: 1,
        images: [],
        returnFocus: null,

        init() {
            this.images = this.parseImages();
        },

        get activeImage() {
            return this.images[this.active] || this.images[0] || { url: '', alt: '' };
        },

        get imageCount() {
            return this.images.length;
        },

        get zoomLabel() {
            return `${Math.round(this.zoom * 100)}%`;
        },

        get zoomStyle() {
            return `transform: scale(${this.zoom});`;
        },

        parseImages() {
            try {
                const images = JSON.parse(this.$el.dataset.galleryImages || '[]');

                return Array.isArray(images) ? images : [];
            } catch {
                return [];
            }
        },

        previous() {
            this.setActive(this.active - 1 + this.imageCount);
        },

        next() {
            this.setActive(this.active + 1);
        },

        previousWhenOpen(event) {
            if (this.lightboxOpen) {
                event.preventDefault();
                this.previous();
            }
        },

        nextWhenOpen(event) {
            if (this.lightboxOpen) {
                event.preventDefault();
                this.next();
            }
        },

        setActive(index, options = {}) {
            const nextIndex = Number(index);
            const { scroll = true, behavior = 'smooth' } = options;

            if (!this.imageCount || !Number.isInteger(nextIndex)) {
                return;
            }

            this.active = (nextIndex + this.imageCount) % this.imageCount;
            this.resetZoom();

            if (scroll) {
                this.scrollActiveThumbnail(behavior);
            }
        },

        openLightbox(index = this.active) {
            this.returnFocus = document.activeElement;
            this.setActive(index, { scroll: false });
            this.lightboxOpen = true;
            this.zoom = 1;
            document.body.classList.add('overflow-hidden');
            this.scrollActiveThumbnail('auto');
            this.$nextTick(() => this.$refs.galleryDialog?.querySelector('button[aria-label="Umanji fotografiju"]')?.focus());
        },

        closeLightbox() {
            const wasOpen = this.lightboxOpen;
            this.lightboxOpen = false;
            this.resetZoom();
            document.body.classList.remove('overflow-hidden');
            if (wasOpen) this.returnFocus?.focus();
        },

        destroy() {
            if (this.lightboxOpen) document.body.classList.remove('overflow-hidden');
        },

        trapFocus(event) {
            if (!this.lightboxOpen) return;
            const buttons = [...(this.$refs.galleryDialog?.querySelectorAll('button, a[href], input') || [])]
                .filter((element) => element.getClientRects().length && !element.disabled);
            const first = buttons[0];
            const last = buttons[buttons.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last?.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first?.focus();
            }
        },

        zoomIn() {
            this.zoom = Math.min(this.zoom + 0.25, 3);
        },

        zoomOut() {
            this.zoom = Math.max(this.zoom - 0.25, 1);
        },

        resetZoom() {
            this.zoom = 1;
        },

        scrollActiveThumbnail(behavior = 'smooth') {
            const activeIndex = this.active;

            this.$nextTick(() => {
                window.requestAnimationFrame(() => {
                    const thumbnails = document.querySelectorAll(`[data-gallery-thumbnail="${activeIndex}"]`);

                    thumbnails.forEach((thumbnail) => {
                        const track = thumbnail.closest('[data-gallery-thumbnail-track]');

                        if (!track || !thumbnail.getClientRects().length) {
                            return;
                        }

                        this.scrollThumbnailIntoTrack(thumbnail, track, behavior);
                    });
                });
            });
        },

        scrollThumbnailIntoTrack(thumbnail, track, behavior) {
            const trackRect = track.getBoundingClientRect();
            const thumbnailRect = thumbnail.getBoundingClientRect();
            const nextPosition = { behavior };

            if (track.scrollWidth > track.clientWidth) {
                const centeredLeft = track.scrollLeft
                    + thumbnailRect.left
                    - trackRect.left
                    - ((track.clientWidth - thumbnailRect.width) / 2);

                nextPosition.left = this.clampScroll(centeredLeft, track.scrollWidth - track.clientWidth);
            }

            if (track.scrollHeight > track.clientHeight) {
                const centeredTop = track.scrollTop
                    + thumbnailRect.top
                    - trackRect.top
                    - ((track.clientHeight - thumbnailRect.height) / 2);

                nextPosition.top = this.clampScroll(centeredTop, track.scrollHeight - track.clientHeight);
            }

            track.scrollTo(nextPosition);
        },

        clampScroll(value, max) {
            return Math.min(Math.max(value, 0), Math.max(max, 0));
        },

        handleWheel(event) {
            if (!this.lightboxOpen) {
                return;
            }

            event.preventDefault();

            if (event.deltaY < 0) {
                this.zoomIn();
            } else {
                this.zoomOut();
            }
        },
    }));
});

document.addEventListener('livewire:navigated', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    document.querySelectorAll('details[data-nav-menu][open]').forEach((menu) => {
        menu.removeAttribute('open');
    });

    const pagePath = window.location.pathname + window.location.search;

    if (!window.gtag || window.gtagLastPagePath === pagePath) {
        return;
    }

    window.gtagLastPagePath = pagePath;
    window.gtag('config', window.gtagMeasurementId, {
        page_location: window.location.href,
        page_path: pagePath,
        page_title: document.title,
    });
});
