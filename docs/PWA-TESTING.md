# Тестирование PWA функций

## Быстрый тест

1. Откройте в браузере: `http://localhost:8080/pwa-test`
2. Проверьте статус Service Worker
3. Проверьте размер кеша
4. Попробуйте очистить кеш

## Service Worker

### Проверка регистрации

```javascript
// В консоли браузера
await window.ServiceWorkerHelper.check()
```

Ожидаемый результат:
```json
{
  "supported": true,
  "registered": true,
  "scope": "http://localhost:8080/",
  "active": true,
  "waiting": false,
  "installing": false
}
```

### Проверка кеша

```javascript
// Получить размер кеша
await window.ServiceWorkerHelper.getCacheSize()

// Очистить кеш
await window.ServiceWorkerHelper.clearCache()
```

### Проверка в DevTools

1. Откройте DevTools (F12)
2. Перейдите на вкладку **Application**
3. В разделе **Service Workers** должен быть активный worker
4. В разделе **Cache Storage** должен быть кеш `aura-cache-v1.0.0`

## Офлайн режим

### Тест 1: Отключение сети

1. Откройте любую страницу сайта
2. В DevTools → Network → поставьте галочку **Offline**
3. Обновите страницу (F5)
4. Страница должна загрузиться из кеша

### Тест 2: Офлайн страница

1. Отключите сеть (Offline mode)
2. Попробуйте открыть новую страницу, которой нет в кеше
3. Должна показаться страница `/offline.html`

## Web App Manifest

### Проверка манифеста

1. DevTools → Application → Manifest
2. Проверьте:
   - Name: "AuraUI - Современная система аутентификации"
   - Short name: "AuraUI"
   - Display: "standalone"
   - Icons: 8 иконок (72px - 512px)

### Установка PWA

#### Desktop (Chrome/Edge)

1. Откройте сайт
2. В адресной строке справа появится иконка установки ⊕
3. Нажмите на неё
4. Подтвердите установку
5. Приложение откроется в отдельном окне

#### Mobile (Android)

1. Откройте сайт в Chrome
2. Меню → "Добавить на главный экран"
3. Подтвердите
4. Иконка появится на рабочем столе

#### Mobile (iOS)

1. Откройте сайт в Safari
2. Нажмите кнопку "Поделиться" 
3. "На экран Домой"
4. Подтвердите

## Кеширование

### Стратегия: Network First

Service Worker использует стратегию **Network First**:
1. Сначала пытается загрузить из сети
2. Если сеть недоступна → загружает из кеша
3. Если в кеше нет → показывает офлайн страницу

### Что кешируется

**Статические файлы** (при установке):
- `/`
- `/assets/css/style.css`
- `/assets/css/loader.css`
- `/assets/js/app.js`
- `/assets/js/loader.js`
- `/assets/js/lazy-load.js`
- `/manifest.json`

**Динамические файлы** (при посещении):
- Все GET запросы к вашему домену
- Максимум 100 файлов в кеше

**Не кешируется**:
- POST/PUT/DELETE запросы
- API endpoints (`/api/*`)
- Админ-панель (`/admin`)
- Выход (`/logout`)

## Обновление Service Worker

### Автоматическое обновление

Service Worker проверяет обновления:
- При каждом посещении сайта
- Каждый час (автоматически)

### Принудительное обновление

```javascript
// В консоли
const registration = await navigator.serviceWorker.getRegistration();
await registration.update();
```

### Уведомление об обновлении

Когда доступна новая версия:
1. Появится уведомление внизу справа
2. Кнопка "Обновить" → перезагрузит страницу
3. Кнопка "Позже" → закроет уведомление

## Отладка

### Логи Service Worker

```javascript
// В консоли DevTools
// Все логи SW начинаются с [SW]
```

### Удаление Service Worker

```javascript
// В консоли
const registration = await navigator.serviceWorker.getRegistration();
await registration.unregister();
```

### Очистка всех кешей

```javascript
// В консоли
const cacheNames = await caches.keys();
await Promise.all(cacheNames.map(name => caches.delete(name)));
```

## Проблемы и решения

### Service Worker не регистрируется

**Причина**: HTTPS требуется (кроме localhost)
**Решение**: Используйте localhost или настройте HTTPS

### Старая версия не обновляется

**Причина**: Браузер кеширует service-worker.js
**Решение**: 
1. DevTools → Application → Service Workers
2. Поставьте галочку "Update on reload"
3. Обновите страницу

### Кеш не очищается

**Причина**: Service Worker активен
**Решение**:
```javascript
await window.ServiceWorkerHelper.clearCache();
```

### Офлайн страница не показывается

**Причина**: Страница не закеширована
**Решение**: Добавьте `/offline.html` в STATIC_CACHE

## Производительность

### Размер кеша

- Текущий: проверьте на `/pwa-test`
- Максимум: 100 файлов
- Автоматическая очистка старых файлов

### Скорость загрузки

**С кешем**:
- Первая загрузка: ~500ms
- Повторная: ~50ms (из кеша)

**Без кеша**:
- Каждая загрузка: ~500ms

## Чеклист перед продакшеном

- [ ] Service Worker регистрируется
- [ ] Офлайн режим работает
- [ ] Манифест корректный
- [ ] Все иконки на месте (8 штук)
- [ ] PWA устанавливается
- [ ] Обновления работают
- [ ] Кеш очищается
- [ ] HTTPS настроен (для продакшена)
- [ ] Домен добавлен в manifest.json
- [ ] Tailwind CSS заменен на production версию

## Полезные ссылки

- [PWA Checklist](https://web.dev/pwa-checklist/)
- [Service Worker API](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API)
- [Web App Manifest](https://developer.mozilla.org/en-US/docs/Web/Manifest)
- [Workbox](https://developers.google.com/web/tools/workbox) - библиотека для SW
