/**
 * Lazy Loading для изображений
 * Загружает изображения только когда они видны на экране
 */

(function() {
    'use strict';
    
    // Проверяем поддержку IntersectionObserver
    if (!('IntersectionObserver' in window)) {
        // Fallback для старых браузеров - загружаем все сразу
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[data-src]');
            images.forEach(function(img) {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
            });
        });
        return;
    }
    
    // Создаем observer
    const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const img = entry.target;
                
                // Загружаем изображение
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                    img.removeAttribute('data-srcset');
                }
                
                // Добавляем класс loaded
                img.classList.add('lazy-loaded');
                
                // Перестаем наблюдать за этим изображением
                observer.unobserve(img);
            }
        });
    }, {
        // Загружаем за 50px до появления на экране
        rootMargin: '50px 0px',
        threshold: 0.01
    });
    
    // Функция для инициализации lazy loading
    function initLazyLoad() {
        const images = document.querySelectorAll('img[data-src], img[data-srcset]');
        images.forEach(function(img) {
            imageObserver.observe(img);
        });
    }
    
    // Инициализируем при загрузке DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLazyLoad);
    } else {
        initLazyLoad();
    }
    
    // Экспортируем для использования в других скриптах
    window.LazyLoad = {
        init: initLazyLoad,
        observer: imageObserver
    };
})();
