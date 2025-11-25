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

// Toast Container
function ensureToastContainer() {
    if (!$('#toast-container').length) {
        $('body').append('<div id="toast-container" class="toast-container"></div>');
    }
    return $('#toast-container');
}

// Показать Toast уведомление
function showToast(message, type = 'info', title = null) {
    const container = ensureToastContainer();
    
    const icons = {
        success: '<svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        error: '<svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        warning: '<svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        info: '<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };
    
    const titles = {
        success: 'Успешно',
        error: 'Ошибка',
        warning: 'Внимание',
        info: 'Информация'
    };
    
    const toast = $(`
        <div class="toast ${type}">
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">
                <div class="toast-title">${title || titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="$(this).parent().remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    `);
    
    container.append(toast);
    
    setTimeout(() => toast.addClass('show'), 10);
    
    setTimeout(() => {
        toast.removeClass('show');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Старая функция для совместимости
function showNotification(message, type = 'info') {
    showToast(message, type);
}

// Confirm Modal
function showConfirm(options) {
    return new Promise((resolve) => {
        const {
            title = 'Подтверждение',
            message = 'Вы уверены?',
            confirmText = 'Подтвердить',
            cancelText = 'Отмена',
            type = 'warning', // warning, danger, info
            onConfirm = null,
            onCancel = null
        } = options;
        
        const icons = {
            warning: '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
            danger: '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
            info: '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        };
        
        // Remove existing modal
        $('.confirm-modal-overlay').remove();
        
        const modal = $(`
            <div class="confirm-modal-overlay">
                <div class="confirm-modal">
                    <div class="confirm-modal-icon ${type}">
                        ${icons[type]}
                    </div>
                    <h3 class="confirm-modal-title">${title}</h3>
                    <p class="confirm-modal-message">${message}</p>
                    <div class="confirm-modal-buttons">
                        <button class="confirm-modal-cancel">${cancelText}</button>
                        <button class="confirm-modal-confirm ${type === 'danger' ? 'danger' : ''}">${confirmText}</button>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(modal);
        
        setTimeout(() => modal.addClass('active'), 10);
        
        const closeModal = (result) => {
            modal.removeClass('active');
            setTimeout(() => modal.remove(), 200);
            resolve(result);
        };
        
        modal.find('.confirm-modal-cancel').on('click', () => {
            if (onCancel) onCancel();
            closeModal(false);
        });
        
        modal.find('.confirm-modal-confirm').on('click', () => {
            if (onConfirm) onConfirm();
            closeModal(true);
        });
        
        modal.on('click', (e) => {
            if ($(e.target).hasClass('confirm-modal-overlay')) {
                if (onCancel) onCancel();
                closeModal(false);
            }
        });
        
        $(document).on('keydown.confirmModal', (e) => {
            if (e.key === 'Escape') {
                $(document).off('keydown.confirmModal');
                if (onCancel) onCancel();
                closeModal(false);
            }
        });
    });
}

// Async confirm helper
async function confirmAction(message, title = 'Подтверждение', type = 'warning') {
    return await showConfirm({ title, message, type });
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
