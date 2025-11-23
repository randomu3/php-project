# Руководство по jQuery в проекте

## Обзор

Проект использует jQuery 3.7.1 для упрощения работы с DOM, событиями и AJAX запросами.

## Подключение

jQuery подключен в каждом view файле:

```html
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/assets/js/app.js"></script>
```

## Основной файл: app.js

### Структура

```javascript
// Инициализация при загрузке страницы
$(document).ready(function() {
    // Код выполняется когда DOM готов
});

// Глобальные функции
function switchTab(tabId) { ... }
function editTemplate(id, name, subject, body) { ... }
```

## Основные функции

### 1. Валидация форм

```javascript
$('form').on('submit', function(e) {
    let isValid = true;
    
    // Проверка обязательных полей
    $(this).find('[required]').each(function() {
        if ($(this).val().trim() === '') {
            isValid = false;
            $(this).addClass('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
    }
});
```

### 2. Переключение табов

```javascript
function switchTab(tabId) {
    $('.tab-content').addClass('hidden');
    $('#tab-' + tabId).removeClass('hidden');
    
    $('.tab-btn').removeClass('active');
    $('#btn-' + tabId).addClass('active');
}
```

Использование в HTML:

```html
<button onclick="switchTab('database')">База данных</button>
<div id="tab-database" class="tab-content">...</div>
```

### 3. Работа с шаблонами

```javascript
function editTemplate(id, name, subject, body) {
    $('#template-name').text(name);
    $('#edit_template_id').val(id);
    $('#edit_template_subject').val(subject);
    $('#edit_template_body').val(body);
    
    $('#template-editor').removeClass('hidden');
    
    // Плавная прокрутка
    $('html, body').animate({
        scrollTop: $('#template-editor').offset().top - 20
    }, 500);
}
```

### 4. Уведомления

```javascript
function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500/10 border-green-500/20 text-green-200',
        error: 'bg-red-500/10 border-red-500/20 text-red-200',
        warning: 'bg-yellow-500/10 border-yellow-500/20 text-yellow-200',
        info: 'bg-blue-500/10 border-blue-500/20 text-blue-200'
    };
    
    const notification = $('<div>')
        .addClass('fixed top-4 right-4 p-4 rounded-xl border z-50 ' + colors[type])
        .text(message)
        .appendTo('body');
    
    setTimeout(function() {
        notification.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}
```

Использование:

```javascript
showNotification('Данные сохранены!', 'success');
showNotification('Ошибка сохранения', 'error');
```

### 5. AJAX запросы (опционально)

```javascript
function submitFormAjax(formId, successCallback) {
    $(formId).on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const action = $(this).attr('action') || window.location.href;
        
        $.ajax({
            url: action,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (successCallback) {
                    successCallback(response);
                }
            },
            error: function() {
                showNotification('Ошибка отправки формы', 'error');
            }
        });
    });
}
```

## Полезные jQuery методы

### Селекторы

```javascript
$('#id')              // По ID
$('.class')           // По классу
$('tag')              // По тегу
$('[name="field"]')   // По атрибуту
$('div.class')        // Комбинация
```

### Манипуляция DOM

```javascript
$('#element').text('Текст')           // Установить текст
$('#element').html('<b>HTML</b>')     // Установить HTML
$('#element').val('значение')         // Установить значение input
$('#element').addClass('active')      // Добавить класс
$('#element').removeClass('active')   // Удалить класс
$('#element').toggleClass('active')   // Переключить класс
$('#element').show()                  // Показать
$('#element').hide()                  // Скрыть
$('#element').fadeIn()                // Плавное появление
$('#element').fadeOut()               // Плавное исчезновение
```

### События

```javascript
$('#button').on('click', function() { ... })
$('input').on('input', function() { ... })
$('form').on('submit', function(e) { ... })
$('select').on('change', function() { ... })
```

### Анимации

```javascript
$('#element').animate({
    opacity: 0.5,
    left: '50px'
}, 500);

$('html, body').animate({
    scrollTop: $('#target').offset().top
}, 500);
```

## Примеры использования

### Динамическая загрузка шаблона

```javascript
function loadTemplate() {
    const select = $('#template_select');
    const option = select.find('option:selected');
    
    if (option.val()) {
        $('#email_subject').val(option.data('subject'));
        $('#email_body').val(option.data('body'));
    }
}
```

HTML:

```html
<select id="template_select" onchange="loadTemplate()">
    <option value="1" data-subject="Тема" data-body="Текст">Шаблон 1</option>
</select>
```

### Автоматическое скрытие сообщений

```javascript
setTimeout(function() {
    $('.message, .alert').fadeOut('slow');
}, 5000);
```

### Удаление класса ошибки при вводе

```javascript
$('input, textarea, select').on('input change', function() {
    $(this).removeClass('border-red-500');
});
```

## Best Practices

1. **Используйте делегирование событий** для динамических элементов:
   ```javascript
   $(document).on('click', '.dynamic-button', function() { ... });
   ```

2. **Кэшируйте селекторы** если используете их многократно:
   ```javascript
   const $form = $('#myForm');
   $form.find('input').val('');
   $form.submit();
   ```

3. **Используйте цепочки методов**:
   ```javascript
   $('#element')
       .addClass('active')
       .fadeIn()
       .text('Готово');
   ```

4. **Проверяйте существование элементов**:
   ```javascript
   if ($('#element').length) {
       // Элемент существует
   }
   ```

## Отладка

```javascript
// Вывод в консоль
console.log($('#element').val());

// Проверка существования
console.log($('#element').length);

// Просмотр всех data-атрибутов
console.log($('#element').data());
```

## Ресурсы

- [jQuery API Documentation](https://api.jquery.com/)
- [jQuery Learning Center](https://learn.jquery.com/)
