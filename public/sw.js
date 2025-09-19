// Service Worker untuk TimeKeeper
const CACHE_NAME = 'timekeeper-v1.0.0';
const STATIC_CACHE = 'timekeeper-static-v1.0.0';
const DYNAMIC_CACHE = 'timekeeper-dynamic-v1.0.0';

// Assets yang akan di-cache
const STATIC_ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/favicon.ico',
    '/manifest.json',
];

// API endpoints yang akan di-cache
const CACHEABLE_APIS = [
    '/api/settings',
    '/api/messages',
];

// Install event - cache static assets
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Service Worker: Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                console.log('Service Worker: Static assets cached');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('Service Worker: Error caching static assets', error);
            })
    );
});

// Activate event - cleanup old caches
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('Service Worker: Deleting old cache', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker: Activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip external requests
    if (url.origin !== location.origin) {
        return;
    }

    // Handle different types of requests
    if (isStaticAsset(request.url)) {
        event.respondWith(handleStaticAsset(request));
    } else if (isAPIRequest(request.url)) {
        event.respondWith(handleAPIRequest(request));
    } else if (isPageRequest(request)) {
        event.respondWith(handlePageRequest(request));
    }
});

// Check if request is for static asset
function isStaticAsset(url) {
    return url.includes('/css/') || 
           url.includes('/js/') || 
           url.includes('/images/') || 
           url.includes('/fonts/') ||
           url.endsWith('.ico') ||
           url.endsWith('.png') ||
           url.endsWith('.jpg') ||
           url.endsWith('.svg');
}

// Check if request is for API
function isAPIRequest(url) {
    return url.includes('/api/') || 
           url.includes('/livewire/');
}

// Check if request is for page
function isPageRequest(request) {
    return request.headers.get('accept').includes('text/html');
}

// Handle static assets - cache first strategy
async function handleStaticAsset(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(STATIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error('Service Worker: Error handling static asset', error);
        return new Response('Asset not available offline', { status: 503 });
    }
}

// Handle API requests - network first with cache fallback
async function handleAPIRequest(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            // Cache successful API responses
            if (CACHEABLE_APIS.some(api => request.url.includes(api))) {
                const cache = await caches.open(DYNAMIC_CACHE);
                cache.put(request, networkResponse.clone());
            }
        }
        return networkResponse;
    } catch (error) {
        console.log('Service Worker: Network failed, trying cache for API request');
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        return new Response(JSON.stringify({ 
            error: 'Service unavailable', 
            message: 'Please check your internet connection' 
        }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Handle page requests - network first with offline fallback
async function handlePageRequest(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.log('Service Worker: Network failed, trying cache for page request');
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline page
        return caches.match('/') || new Response('Offline - Please check your internet connection', {
            status: 503,
            headers: { 'Content-Type': 'text/html' }
        });
    }
}

// Background sync for failed requests
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        console.log('Service Worker: Background sync triggered');
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    // Implement background sync logic here
    console.log('Service Worker: Performing background sync');
}

// Push notifications
self.addEventListener('push', event => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: data.primaryKey || 1
            },
            actions: [
                {
                    action: 'explore',
                    title: 'View',
                    icon: '/favicon.ico'
                },
                {
                    action: 'close',
                    title: 'Close',
                    icon: '/favicon.ico'
                }
            ]
        };

        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});