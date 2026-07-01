const CACHE_NAME = 'wini-pwa-v3';
const scopeUrl = new URL(self.registration.scope);
const appUrl = (path = '') => new URL(path, scopeUrl).href;
const OFFLINE_URL = appUrl('login');
const APP_SHELL = [
    OFFLINE_URL,
    appUrl('manifest.json'),
    appUrl('images/wini-logo.png'),
    appUrl('icons/icon-192.png'),
    appUrl('icons/icon-512.png'),
    appUrl('icons/maskable-512.png')
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(async (cache) => {
            await Promise.allSettled(APP_SHELL.map((url) => cache.add(url)));
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys
                .filter((key) => key !== CACHE_NAME)
                .map((key) => caches.delete(key))
        ))
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const requestUrl = new URL(event.request.url);

    if (event.request.method !== 'GET' || requestUrl.origin !== scopeUrl.origin) {
        return;
    }

    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    const cacheableDestinations = ['style', 'script', 'image', 'font'];
    if (!cacheableDestinations.includes(event.request.destination) && requestUrl.pathname !== new URL(appUrl('manifest.json')).pathname) {
        return;
    }

    event.respondWith((async () => {
        const cached = await caches.match(event.request);
        const networkRequest = fetch(event.request).then((response) => {
            if (response.ok && response.type === 'basic') {
                caches.open(CACHE_NAME).then((cache) => cache.put(event.request, response.clone()));
            }
            return response;
        });

        return cached || networkRequest;
    })());
});
