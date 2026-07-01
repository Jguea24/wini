import '../css/app.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const serviceWorkerUrl = document
            .querySelector('meta[name="pwa-service-worker"]')
            ?.getAttribute('content');

        if (!serviceWorkerUrl) {
            return;
        }

        const workerUrl = new URL(serviceWorkerUrl, window.location.href);
        const scope = workerUrl.pathname.substring(0, workerUrl.pathname.lastIndexOf('/') + 1);

        navigator.serviceWorker.register(workerUrl.href, { scope }).catch((error) => {
            console.error('No se pudo registrar la PWA de Wini:', error);
        });
    });
}
