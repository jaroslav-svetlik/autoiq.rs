import './bootstrap';

document.addEventListener('livewire:navigated', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });

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
