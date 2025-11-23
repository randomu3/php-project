/**
 * Page Loader - Показывается только при первой загрузке (без кеша)
 */

(function() {
    'use strict';
    
    // Проверяем, была ли страница уже загружена в этой сессии
    const isFirstLoad = !sessionStorage.getItem('pageLoaded');
    
    // Если не первая загрузка - не показываем лоадер
    if (!isFirstLoad) {
        const loader = document.getElementById('page-loader');
        if (loader) {
            loader.style.display = 'none';
        }
        return;
    }
    
    // Функция скрытия лоадера
    function hideLoader() {
        const loader = document.getElementById('page-loader');
        if (!loader) return;
        
        // Добавляем класс для плавного исчезновения
        loader.classList.add('fade-out');
        
        // Удаляем элемент после анимации
        setTimeout(() => {
            loader.remove();
        }, 500);
        
        // Отмечаем что страница загружена
        sessionStorage.setItem('pageLoaded', 'true');
    }
    
    // Скрываем лоадер когда всё загружено
    if (document.readyState === 'complete') {
        // Если уже загружено
        setTimeout(hideLoader, 300);
    } else {
        // Ждём полной загрузки
        window.addEventListener('load', function() {
            setTimeout(hideLoader, 300);
        });
    }
    
    // Fallback: скрываем через 5 секунд в любом случае
    setTimeout(hideLoader, 5000);
    
})();
