/**
 * Регистрация Service Worker
 */

(function() {
    'use strict';
    
    // Проверяем поддержку Service Worker
    if (!('serviceWorker' in navigator)) {
        console.log('Service Worker not supported');
        return;
    }
    
    // Регистрируем Service Worker после загрузки страницы
    window.addEventListener('load', function() {
        registerServiceWorker();
    });
    
    /**
     * Регистрация Service Worker
     */
    async function registerServiceWorker() {
        try {
            const registration = await navigator.serviceWorker.register('/service-worker.js', {
                scope: '/'
            });
            
            console.log('✅ Service Worker registered:', registration.scope);
            
            // Проверяем обновления
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                console.log('🔄 Service Worker update found');
                
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        // Новая версия доступна
                        showUpdateNotification();
                    }
                });
            });
            
            // Проверяем обновления каждый час
            setInterval(() => {
                registration.update();
            }, 60 * 60 * 1000);
            
        } catch (error) {
            console.error('❌ Service Worker registration failed:', error);
        }
    }
    
    /**
     * Показать уведомление об обновлении
     */
    function showUpdateNotification() {
        // Создаем уведомление
        const notification = document.createElement('div');
        notification.className = 'sw-update-notification';
        notification.innerHTML = `
            <div class="sw-update-content">
                <p>🎉 Доступна новая версия!</p>
                <button onclick="window.location.reload()">Обновить</button>
                <button onclick="this.parentElement.parentElement.remove()">Позже</button>
            </div>
        `;
        
        // Добавляем стили
        const style = document.createElement('style');
        style.textContent = `
            .sw-update-notification {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
            }
            
            .sw-update-content p {
                margin: 0 0 10px 0;
                font-weight: bold;
            }
            
            .sw-update-content button {
                margin-right: 10px;
                padding: 8px 16px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 500;
                transition: transform 0.2s;
            }
            
            .sw-update-content button:first-of-type {
                background: white;
                color: #667eea;
            }
            
            .sw-update-content button:last-of-type {
                background: rgba(255,255,255,0.2);
                color: white;
            }
            
            .sw-update-content button:hover {
                transform: scale(1.05);
            }
            
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(notification);
    }
    
    /**
     * Проверить статус Service Worker
     */
    window.checkServiceWorker = async function() {
        if (!('serviceWorker' in navigator)) {
            return { supported: false };
        }
        
        const registration = await navigator.serviceWorker.getRegistration();
        
        if (!registration) {
            return { supported: true, registered: false };
        }
        
        return {
            supported: true,
            registered: true,
            scope: registration.scope,
            active: !!registration.active,
            waiting: !!registration.waiting,
            installing: !!registration.installing
        };
    };
    
    /**
     * Очистить кеш Service Worker
     */
    window.clearServiceWorkerCache = async function() {
        if (!('serviceWorker' in navigator)) {
            return false;
        }
        
        const registration = await navigator.serviceWorker.getRegistration();
        
        if (!registration || !registration.active) {
            return false;
        }
        
        return new Promise((resolve) => {
            const messageChannel = new MessageChannel();
            
            messageChannel.port1.onmessage = (event) => {
                resolve(event.data.success);
            };
            
            registration.active.postMessage(
                { action: 'clearCache' },
                [messageChannel.port2]
            );
        });
    };
    
    /**
     * Получить размер кеша
     */
    window.getServiceWorkerCacheSize = async function() {
        if (!('serviceWorker' in navigator)) {
            return 0;
        }
        
        const registration = await navigator.serviceWorker.getRegistration();
        
        if (!registration || !registration.active) {
            return 0;
        }
        
        return new Promise((resolve) => {
            const messageChannel = new MessageChannel();
            
            messageChannel.port1.onmessage = (event) => {
                resolve(event.data.size);
            };
            
            registration.active.postMessage(
                { action: 'getCacheSize' },
                [messageChannel.port2]
            );
        });
    };
    
    // Экспортируем для использования
    window.ServiceWorkerHelper = {
        check: window.checkServiceWorker,
        clearCache: window.clearServiceWorkerCache,
        getCacheSize: window.getServiceWorkerCacheSize
    };
})();
