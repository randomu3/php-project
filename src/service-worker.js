/**
 * Service Worker для PWA
 * Обеспечивает офлайн работу и кеширование
 */

const CACHE_VERSION = 'v1.0.0';
const CACHE_NAME = `aura-cache-${CACHE_VERSION}`;

// Файлы для кеширования при установке
const STATIC_CACHE = [
    '/',
    '/assets/css/style.css',
    '/assets/css/loader.css',
    '/assets/js/app.js',
    '/assets/js/loader.js',
    '/assets/js/lazy-load.js',
    '/manifest.json'
];

// Файлы которые всегда берем из сети
const NETWORK_ONLY = [
    '/api/',
    '/admin',
    '/logout'
];

// Максимальный размер кеша (100 файлов)
const MAX_CACHE_SIZE = 100;

/**
 * Установка Service Worker
 */
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker...', CACHE_VERSION);
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Caching static assets');
                return cache.addAll(STATIC_CACHE);
            })
            .then(() => {
                console.log('[SW] Installation complete');
                return self.skipWaiting(); // Активировать сразу
            })
            .catch((error) => {
                console.error('[SW] Installation failed:', error);
            })
    );
});

/**
 * Активация Service Worker
 */
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker...', CACHE_VERSION);
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                // Удаляем старые кеши
                return Promise.all(
                    cacheNames
                        .filter((name) => name.startsWith('aura-cache-') && name !== CACHE_NAME)
                        .map((name) => {
                            console.log('[SW] Deleting old cache:', name);
                            return caches.delete(name);
                        })
                );
            })
            .then(() => {
                console.log('[SW] Activation complete');
                return self.clients.claim(); // Контролировать все страницы сразу
            })
    );
});

/**
 * Перехват запросов
 * Стратегия: Network First с Fallback на Cache
 */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Пропускаем запросы к другим доменам
    if (url.origin !== location.origin) {
        return;
    }
    
    // Пропускаем POST запросы
    if (request.method !== 'GET') {
        return;
    }
    
    // Network Only для определенных путей
    if (NETWORK_ONLY.some(path => url.pathname.startsWith(path))) {
        return;
    }
    
    event.respondWith(
        networkFirstStrategy(request)
    );
});

/**
 * Стратегия: Network First (сеть приоритетнее)
 * Пытаемся загрузить из сети, если не получается - из кеша
 */
async function networkFirstStrategy(request) {
    try {
        // Пытаемся загрузить из сети
        const networkResponse = await fetch(request);
        
        // Если успешно - кешируем и возвращаем
        if (networkResponse && networkResponse.status === 200) {
            const cache = await caches.open(CACHE_NAME);
            
            // Кешируем только GET запросы
            if (request.method === 'GET') {
                cache.put(request, networkResponse.clone());
                
                // Ограничиваем размер кеша
                limitCacheSize(CACHE_NAME, MAX_CACHE_SIZE);
            }
            
            return networkResponse;
        }
        
        // Если ответ не 200 - пробуем кеш
        return await caches.match(request) || networkResponse;
        
    } catch (error) {
        // Сеть недоступна - пробуем кеш
        console.log('[SW] Network failed, trying cache:', request.url);
        
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Если в кеше нет - возвращаем офлайн страницу
        if (request.destination === 'document') {
            return caches.match('/offline.html') || new Response(
                '<h1>Офлайн</h1><p>Нет подключения к интернету</p>',
                { headers: { 'Content-Type': 'text/html' } }
            );
        }
        
        // Для остальных ресурсов - ошибка
        return new Response('Offline', { status: 503 });
    }
}

/**
 * Стратегия: Cache First (кеш приоритетнее)
 * Для статических ресурсов
 */
async function cacheFirstStrategy(request) {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse && networkResponse.status === 200) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        return new Response('Offline', { status: 503 });
    }
}

/**
 * Ограничить размер кеша
 */
async function limitCacheSize(cacheName, maxSize) {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    
    if (keys.length > maxSize) {
        // Удаляем самые старые записи
        const toDelete = keys.slice(0, keys.length - maxSize);
        await Promise.all(toDelete.map(key => cache.delete(key)));
    }
}

/**
 * Push уведомления
 */
self.addEventListener('push', (event) => {
    console.log('[SW] Push notification received');
    
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'Уведомление';
    const options = {
        body: data.body || 'У вас новое уведомление',
        icon: '/assets/images/icon-192.png',
        badge: '/assets/images/badge-72.png',
        vibrate: [200, 100, 200],
        data: data.url || '/',
        actions: [
            { action: 'open', title: 'Открыть' },
            { action: 'close', title: 'Закрыть' }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

/**
 * Клик по уведомлению
 */
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked');
    
    event.notification.close();
    
    if (event.action === 'open' || !event.action) {
        const url = event.notification.data || '/';
        
        event.waitUntil(
            clients.openWindow(url)
        );
    }
});

/**
 * Фоновая синхронизация
 */
self.addEventListener('sync', (event) => {
    console.log('[SW] Background sync:', event.tag);
    
    if (event.tag === 'sync-data') {
        event.waitUntil(syncData());
    }
});

/**
 * Синхронизировать данные
 */
async function syncData() {
    try {
        // Здесь можно синхронизировать данные с сервером
        console.log('[SW] Syncing data...');
        
        // Пример: отправить отложенные запросы
        const cache = await caches.open('pending-requests');
        const requests = await cache.keys();
        
        for (const request of requests) {
            try {
                await fetch(request);
                await cache.delete(request);
            } catch (error) {
                console.error('[SW] Sync failed for:', request.url);
            }
        }
        
        console.log('[SW] Sync complete');
    } catch (error) {
        console.error('[SW] Sync error:', error);
    }
}

/**
 * Сообщения от клиента
 */
self.addEventListener('message', (event) => {
    console.log('[SW] Message received:', event.data);
    
    if (event.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
    
    if (event.data.action === 'clearCache') {
        event.waitUntil(
            caches.delete(CACHE_NAME).then(() => {
                console.log('[SW] Cache cleared');
                event.ports[0].postMessage({ success: true });
            })
        );
    }
    
    if (event.data.action === 'getCacheSize') {
        event.waitUntil(
            caches.open(CACHE_NAME).then(cache => {
                return cache.keys();
            }).then(keys => {
                event.ports[0].postMessage({ size: keys.length });
            })
        );
    }
});

console.log('[SW] Service Worker loaded');
