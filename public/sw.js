/*
 * Zeitlos service worker
 * Caches stable PWA assets and serves an offline fallback for failed
 * navigation requests. Application (Inertia) data is intentionally left
 * to the network so stale match data is never served.
 */
const CACHE_VERSION = 'zeitlos-v1';
const CACHE_NAME = `${CACHE_VERSION}-shell`;
const OFFLINE_URL = '/offline.html';

const SHELL_ASSETS = [
    '/manifest.webmanifest',
    '/offline.html',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/apple-touch-icon.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        (async () => {
            const cache = await caches.open(CACHE_NAME);
            // Add assets individually so one failure doesn't abort the whole install.
            await Promise.allSettled(SHELL_ASSETS.map((url) => cache.add(url)));
            await cache.put(OFFLINE_URL, (await fetch(OFFLINE_URL)).clone());
            self.skipWaiting();
        })(),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        (async () => {
            const keys = await caches.keys();
            await Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key)),
            );
            await self.clients.claim();
        })(),
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(
            (async () => {
                try {
                    const networkResponse = await fetch(request);
                    return networkResponse;
                } catch (err) {
                    const cache = await caches.open(CACHE_NAME);
                    const fallback = await cache.match(OFFLINE_URL);
                    return fallback || Response.error();
                }
            })(),
        );
        return;
    }

    event.respondWith(
        (async () => {
            const cache = await caches.open(CACHE_NAME);
            const cached = await cache.match(request);
            if (cached) {
                return cached;
            }
            try {
                const networkResponse = await fetch(request);
                if (
                    networkResponse &&
                    networkResponse.status === 200 &&
                    networkResponse.type === 'basic'
                ) {
                    cache.put(request, networkResponse.clone());
                }
                return networkResponse;
            } catch (err) {
                return cached || Response.error();
            }
        })(),
    );
});
