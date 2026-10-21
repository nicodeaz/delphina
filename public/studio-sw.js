/*
 * Service worker for the installable studio app (scope: admin/).
 * It never caches studio data — the agenda is always loaded live. It only
 * keeps a small "you're offline" page to show when there's no connection.
 * URLs are relative to this file, so a subfolder install works too.
 */
const CACHE = 'studio-v1';
const OFFLINE_URL = new URL('studio-offline.html', self.location).href;

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE).then((cache) => cache.add(OFFLINE_URL)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== CACHE).map((key) => caches.delete(key))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.mode !== 'navigate') {
        return;
    }

    event.respondWith(fetch(event.request).catch(() => caches.match(OFFLINE_URL)));
});
