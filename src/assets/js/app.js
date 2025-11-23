// Основная логика приложения на jQuery

$(document).ready(function() {
    
    // Инициализация Lucide иконок
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Автоматическое скрытие сообщений через 5 секунд
    setTimeout(function() {
        $('.message, .alert').fadeOut('slow');
    }, 5000);
    
    // Валидация форм
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Проверка обязательных полей
        $(this).find('[required]').each(function() {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).addClass('border-red-500');
            } else {
                $(this).removeClass('border-red-500');
            }
        });
        
        // Проверка совпадения паролей
        const password = $(this).find('input[name="password"]').val();
        const passwordConfirm = $(this).find('input[name="password_confirm"]').val();
        
        if (password && passwordConfirm && password !== passwordConfirm) {
            isValid = false;
            $(this).find('input[name="password_confirm"]').addClass('border-red-500');
            showNotification('Пароли не совпадают', 'error');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Удаление класса ошибки при вводе
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('border-red-500');
    });
    
});

// Переключение табов
function switchTab(tabId) {
    // Скрыть все табы
    $('.tab-content').addClass('hidden');
    // Убрать активный класс у кнопок
    $('.tab-btn').removeClass('active');
    
    // Показать нужный таб
    $('#tab-' + tabId).removeClass('hidden').addClass('animate-fade-in');
    // Активировать кнопку
    $('#btn-' + tabId).addClass('active');
}

// Редактирование шаблона
function editTemplate(id, name, subject, body) {
    switchTab('templates');
    
    $('#template-name').text(name);
    $('#edit_template_id').val(id);
    $('#edit_template_subject').val(subject);
    $('#edit_template_body').val(body);
    
    $('#template-editor').removeClass('hidden');
    
    $('html, body').animate({
        scrollTop: $('#template-editor').offset().top - 20
    }, 500);
}

// Закрытие редактора
function closeEditor() {
    $('#template-editor').addClass('hidden').fadeOut(300);
}

// Загрузка шаблона в форму отправки
function loadTemplate() {
    const select = $('#template_select');
    const option = select.find('option:selected');
    
    if (option.val()) {
        $('#email_subject').val(option.data('subject'));
        $('#email_body').val(option.data('body'));
    }
}

// Показать уведомление
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

// AJAX отправка форм (опционально)
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
