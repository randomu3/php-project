# Структура страницы профиля

## Обзор

Страница профиля полностью структурирована согласно MVC архитектуре проекта с разделением на компоненты (partials).

## Файловая структура

```
src/
├── controllers/
│   └── ProfileController.php          # Контроллер профиля
├── views/
│   ├── profile.view.php              # Основной view (layout)
│   └── partials/
│       ├── profile_info_tab.php      # Таб "Информация"
│       ├── profile_edit_tab.php      # Таб "Редактирование"
│       └── profile_password_tab.php  # Таб "Безопасность"
├── assets/
│   ├── js/
│   │   └── app.js                    # Функция switchTab()
│   └── css/
│       └── style.css                 # Стили для профиля
├── templates/
│   ├── header.php                    # Общий header
│   └── footer.php                    # Общий footer
└── profile.php                        # Точка входа

```

## Компоненты

### 1. ProfileController.php

**Ответственность:**
- Проверка авторизации
- Обработка POST запросов
- Валидация данных
- Обновление профиля
- Смена пароля
- Подготовка данных для view

**Методы:**
- `index()` - главный метод, рендерит view
- `getCurrentUser()` - получает данные пользователя
- `handleUpdateProfile()` - обрабатывает обновление профиля
- `handleChangePassword()` - обрабатывает смену пароля

**Передаваемые переменные:**
```php
$user         // Данные пользователя
$success      // Сообщение об успехе
$error        // Сообщение об ошибке
$activeTab    // Активный таб ('info', 'edit', 'password')
$csrf_token   // CSRF токен
```

### 2. profile.view.php

**Ответственность:**
- Layout страницы
- Подключение header/footer
- Отображение сообщений
- Навигация по табам
- Подключение partials

**Структура:**
```php
<?php require header.php ?>

<!-- Основной контент -->
<div class="container">
    <!-- Заголовок -->
    <!-- Сообщения (success/error) -->
    <!-- Кнопки табов -->
    
    <!-- Контент табов -->
    <?php require profile_info_tab.php ?>
    <?php require profile_edit_tab.php ?>
    <?php require profile_password_tab.php ?>
    
    <!-- Кнопка назад -->
</div>

<?php require footer.php ?>
```

### 3. profile_info_tab.php

**Ответственность:**
- Отображение информации профиля
- Аватар пользователя
- Поля: username, email, дата регистрации, последний вход, роль
- Кнопки перехода к редактированию

**Используемые переменные:**
- `$user` - данные пользователя
- `$activeTab` - для определения видимости

### 4. profile_edit_tab.php

**Ответственность:**
- Форма редактирования профиля
- Поля: username, email
- Валидация на клиенте
- CSRF защита

**Используемые переменные:**
- `$user` - текущие данные для заполнения формы
- `$csrf_token` - токен безопасности
- `$activeTab` - для определения видимости

### 5. profile_password_tab.php

**Ответственность:**
- Форма смены пароля
- Поля: текущий пароль, новый пароль, подтверждение
- Валидация на клиенте
- CSRF защита
- Информационное сообщение

**Используемые переменные:**
- `$csrf_token` - токен безопасности
- `$activeTab` - для определения видимости

## Взаимодействие компонентов

```
1. Пользователь → /profile
2. profile.php → ProfileController::index()
3. Controller:
   - Проверяет авторизацию
   - Обрабатывает POST (если есть)
   - Получает данные пользователя
   - Генерирует CSRF токен
   - Передает данные в view
4. profile.view.php:
   - Подключает header
   - Отображает layout
   - Подключает partials
   - Подключает footer
5. Partials:
   - Рендерят свои секции
   - Используют переданные переменные
```

## JavaScript

### Функция switchTab()

**Расположение:** `src/assets/js/app.js`

**Функциональность:**
- Скрывает все табы
- Показывает выбранный таб
- Переключает активный класс на кнопках
- Добавляет анимацию fade-in
- Прокручивает к началу таба

**Использование:**
```javascript
switchTab('info');      // Показать информацию
switchTab('edit');      // Показать редактирование
switchTab('password');  // Показать смену пароля
```

## CSS

### Основные классы

**Расположение:** `src/assets/css/style.css`

**Классы:**
- `.glass-panel` - стеклянный эффект для панелей
- `.glass-input` - стиль для input полей
- `.glass-button` - основная кнопка (градиент)
- `.glass-button-secondary` - вторичная кнопка
- `.tab-btn` - кнопка таба
- `.tab-btn.active` - активная кнопка таба
- `.tab-content` - контейнер таба
- `.animate-fade-in` - анимация появления

## Преимущества структуры

### 1. Разделение ответственности
- Controller - логика
- View - layout
- Partials - компоненты
- JS - интерактивность
- CSS - стили

### 2. Переиспользование
- Partials можно использовать в других местах
- Функции JS доступны глобально
- CSS классы универсальны

### 3. Легкая поддержка
- Каждый компонент в отдельном файле
- Понятная структура
- Легко найти нужный код

### 4. Масштабируемость
- Легко добавить новый таб (создать partial)
- Легко изменить один компонент без влияния на другие
- Можно добавлять новые функции

## Добавление нового таба

### Шаг 1: Создать partial

```php
// src/views/partials/profile_new_tab.php
<div id="tab-new" class="tab-content <?= $activeTab === 'new' ? '' : 'hidden' ?>">
    <div class="glass-panel p-8">
        <!-- Контент таба -->
    </div>
</div>
```

### Шаг 2: Подключить в profile.view.php

```php
<?php require __DIR__ . '/partials/profile_new_tab.php'; ?>
```

### Шаг 3: Добавить кнопку

```html
<button id="btn-new" class="tab-btn" onclick="switchTab('new')">
    <i data-lucide="icon-name" class="w-4 h-4"></i>
    Новый таб
</button>
```

### Шаг 4: Добавить обработку в контроллер (если нужно)

```php
if ($action === 'new_action') {
    $activeTab = 'new';
    // Обработка
}
```

## Безопасность

### CSRF защита
- Токен генерируется в контроллере
- Передается во все формы
- Проверяется при POST запросах

### Валидация
- На клиенте (HTML5 + jQuery)
- На сервере (в контроллере)
- Sanitization всех входных данных

### Авторизация
- Проверка `requireLogin()` в контроллере
- Редирект на /login если не авторизован

## Тестирование

### Проверка структуры

```powershell
# Проверить наличие всех файлов
Get-ChildItem -Path "src/views/partials" -Filter "profile_*.php"

# Проверить синтаксис
.\scripts\test-profile.ps1
```

### Ручное тестирование

1. Открыть http://localhost:8080/profile
2. Проверить отображение всех табов
3. Попробовать переключение между табами
4. Протестировать формы
5. Проверить валидацию
6. Проверить сообщения об ошибках/успехе

## Документация

- **Техническая:** `docs/PROFILE_FEATURE.md`
- **Пользовательская:** `docs/PROFILE_USAGE.md`
- **Структура:** `docs/PROFILE_STRUCTURE.md` (этот файл)
- **Реализация:** `PROFILE_IMPLEMENTATION.md`
- **Быстрый старт:** `PROFILE_QUICKSTART.md`

## Соответствие стандартам проекта

✅ **MVC архитектура** - Controller, View, Partials
✅ **Naming conventions** - PascalCase для контроллеров, lowercase для views
✅ **Security** - CSRF, валидация, sanitization
✅ **Code style** - Соответствует проекту
✅ **Documentation** - Полная документация
✅ **Reusability** - Компоненты переиспользуемы
