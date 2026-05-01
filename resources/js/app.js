import './bootstrap';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('listingGallery', () => ({
        active: 0,
        lightboxOpen: false,
        zoom: 1,
        images: [],

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

        previousWhenOpen() {
            if (this.lightboxOpen) {
                this.previous();
            }
        },

        nextWhenOpen() {
            if (this.lightboxOpen) {
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
            this.setActive(index, { scroll: false });
            this.lightboxOpen = true;
            this.zoom = 1;
            document.body.classList.add('overflow-hidden');
            this.scrollActiveThumbnail('auto');
        },

        closeLightbox() {
            this.lightboxOpen = false;
            this.resetZoom();
            document.body.classList.remove('overflow-hidden');
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
                        if (!thumbnail.getClientRects().length) {
                            return;
                        }

                        thumbnail.scrollIntoView({
                            behavior,
                            block: 'nearest',
                            inline: 'center',
                        });
                    });
                });
            });
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
