<!-- TAB: SETTINGS & CONTENT -->
<div id="tab-settings" class="tab-content hidden animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Email Templates Editor -->
        <div class="glass-panel p-6 rounded-2xl lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="mail" class="w-5 h-5 text-purple-400"></i>
                    Редактор Email-шаблонов
                </h3>
                <div class="flex gap-2">
                    <button onclick="toggleVariablesHelp()" class="px-3 py-2 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-lg text-sm transition-colors flex items-center gap-2" title="Справочник переменных">
                        <i data-lucide="help-circle" class="w-4 h-4"></i> Переменные
                    </button>
                    <button onclick="createNewTemplate()" class="px-4 py-2 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 rounded-lg text-sm transition-colors flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> Новый шаблон
                    </button>
                </div>
            </div>
            
            <!-- Variables Help Panel -->
            <div id="variables-help" class="hidden mb-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-blue-300 flex items-center gap-2">
                        <i data-lucide="code" class="w-4 h-4"></i> Доступные переменные
                    </h4>
                    <button onclick="toggleVariablesHelp()" class="text-slate-400 hover:text-white">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{username}}</code>
                        <p class="text-xs text-slate-500 mt-1">Имя пользователя</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{email}}</code>
                        <p class="text-xs text-slate-500 mt-1">Email пользователя</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{link}}</code>
                        <p class="text-xs text-slate-500 mt-1">Ссылка (верификация, сброс)</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{message}}</code>
                        <p class="text-xs text-slate-500 mt-1">Текст сообщения</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{subject}}</code>
                        <p class="text-xs text-slate-500 mt-1">Тема письма</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{site_name}}</code>
                        <p class="text-xs text-slate-500 mt-1">Название сайта</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{site_url}}</code>
                        <p class="text-xs text-slate-500 mt-1">URL сайта</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{date}}</code>
                        <p class="text-xs text-slate-500 mt-1">Текущая дата</p>
                    </div>
                    <div class="p-2 bg-white/5 rounded-lg">
                        <code class="text-purple-400">{{year}}</code>
                        <p class="text-xs text-slate-500 mt-1">Текущий год</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Templates List -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm text-slate-400">Выберите шаблон:</span>
                    </div>
                    <div id="templates-list" class="space-y-2 max-h-[500px] overflow-y-auto pr-2">
                        <div class="text-center text-slate-500 py-4">Загрузка...</div>
                    </div>
                </div>
                
                <!-- Template Editor -->
                <div class="lg:col-span-2">
                    <form id="template-form" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="id" id="template-id" value="0">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-slate-400 mb-1">Название (slug)</label>
                                <input type="text" name="name" id="template-name" 
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg focus:border-purple-500/50 focus:outline-none"
                                    placeholder="welcome_email">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-400 mb-1">Тема письма</label>
                                <input type="text" name="subject" id="template-subject"
                                    class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg focus:border-purple-500/50 focus:outline-none"
                                    placeholder="Добро пожаловать!">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Описание</label>
                            <input type="text" name="description" id="template-description"
                                class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg focus:border-purple-500/50 focus:outline-none"
                                placeholder="Письмо для новых пользователей">
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-sm text-slate-400">HTML содержимое</label>
                                <button type="button" onclick="insertVariable()" class="text-xs text-purple-400 hover:text-purple-300 flex items-center gap-1">
                                    <i data-lucide="plus-circle" class="w-3 h-3"></i> Вставить переменную
                                </button>
                            </div>
                            <textarea name="body" id="template-body" rows="12"
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:border-purple-500/50 focus:outline-none font-mono text-sm"
                                placeholder="<h1>Привет, {{username}}!</h1>"></textarea>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg font-medium hover:opacity-90 transition-opacity">
                                Сохранить шаблон
                            </button>
                            <button type="button" onclick="previewTemplate()" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-colors flex items-center gap-2">
                                <i data-lucide="eye" class="w-4 h-4"></i> Предпросмотр
                            </button>
                            <button type="button" onclick="deleteTemplate()" id="btn-delete-template" class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 rounded-lg text-red-400 transition-colors hidden flex items-center gap-2">
                                <i data-lucide="trash-2" class="w-4 h-4"></i> Удалить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- System Settings -->
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-lg font-semibold flex items-center gap-2 mb-6">
                <i data-lucide="settings" class="w-5 h-5 text-blue-400"></i>
                Системные настройки
            </h3>
            
            <form id="system-settings-form" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <div>
                            <p class="font-medium">Макс. попыток входа</p>
                            <p class="text-xs text-slate-500">До блокировки аккаунта</p>
                        </div>
                        <input type="number" name="max_login_attempts" id="set-max-login-attempts" 
                            class="w-20 px-3 py-1 bg-white/5 border border-white/10 rounded text-center" value="5">
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <div>
                            <p class="font-medium">Время блокировки</p>
                            <p class="text-xs text-slate-500">В минутах</p>
                        </div>
                        <input type="number" name="lockout_duration" id="set-lockout-duration"
                            class="w-20 px-3 py-1 bg-white/5 border border-white/10 rounded text-center" value="15">
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <div>
                            <p class="font-medium">Таймаут сессии</p>
                            <p class="text-xs text-slate-500">В секундах</p>
                        </div>
                        <input type="number" name="session_timeout" id="set-session-timeout"
                            class="w-24 px-3 py-1 bg-white/5 border border-white/10 rounded text-center" value="3600">
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <div>
                            <p class="font-medium">Мин. длина пароля</p>
                            <p class="text-xs text-slate-500">Символов</p>
                        </div>
                        <input type="number" name="password_min_length" id="set-password-min-length"
                            class="w-20 px-3 py-1 bg-white/5 border border-white/10 rounded text-center" value="8">
                    </div>
                </div>
                
                <div class="space-y-3 pt-4 border-t border-white/10">
                    <label class="flex items-center justify-between p-3 bg-white/5 rounded-lg cursor-pointer">
                        <span>Требовать подтверждение email</span>
                        <input type="checkbox" name="require_email_verification" id="set-require-email" class="toggle-checkbox">
                    </label>
                    
                    <label class="flex items-center justify-between p-3 bg-white/5 rounded-lg cursor-pointer">
                        <span>Разрешить регистрацию</span>
                        <input type="checkbox" name="allow_registration" id="set-allow-registration" class="toggle-checkbox">
                    </label>
                    
                    <label class="flex items-center justify-between p-3 bg-white/5 rounded-lg cursor-pointer">
                        <span>Режим обслуживания</span>
                        <input type="checkbox" name="maintenance_mode" id="set-maintenance" class="toggle-checkbox">
                    </label>
                </div>
                
                <button type="submit" class="w-full py-2 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-lg transition-colors">
                    Сохранить настройки
                </button>
            </form>
        </div>

        <!-- Database Backup -->
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-lg font-semibold flex items-center gap-2 mb-6">
                <i data-lucide="database" class="w-5 h-5 text-green-400"></i>
                Резервное копирование
            </h3>
            
            <div class="space-y-4">
                <button onclick="createBackup()" class="w-full py-3 bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    Создать резервную копию
                </button>
                
                <div class="text-sm text-slate-400 mb-2">Последние копии:</div>
                <div id="backups-list" class="space-y-2 max-h-48 overflow-y-auto">
                    <div class="text-center text-slate-500 py-2 text-sm">Загрузка...</div>
                </div>
                
                <button onclick="sendTestEmail()" class="w-full py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-colors text-sm flex items-center justify-center gap-2">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    Отправить тестовое письмо
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Template Preview Modal -->
<div id="template-preview-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-white/10">
            <h3 class="font-semibold">Предпросмотр шаблона</h3>
            <button onclick="closePreviewModal()" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div id="template-preview-content" class="p-4 overflow-y-auto max-h-[60vh] bg-white text-black">
        </div>
    </div>
</div>

<script>
// Email Templates
function loadTemplates() {
    $.get('/api/admin/settings.php?action=get_templates', function(response) {
        if (response.success) {
            renderTemplatesList(response.data);
        }
    });
}

function renderTemplatesList(templates) {
    const container = $('#templates-list');
    if (templates.length === 0) {
        container.html('<div class="text-center text-slate-500 py-4">Нет шаблонов. Создайте первый!</div>');
        return;
    }
    
    let html = '';
    templates.forEach(t => {
        const desc = t.description ? `<div class="text-xs text-slate-500 mt-1 truncate">${escapeHtml(t.description)}</div>` : '';
        html += `
            <div class="p-3 bg-white/5 hover:bg-white/10 rounded-lg transition-all template-item border border-transparent hover:border-purple-500/30" data-id="${t.id}">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0 cursor-pointer template-info" data-template-id="${t.id}">
                        <div class="font-medium text-sm flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-purple-400"></i>
                            ${escapeHtml(t.name)}
                        </div>
                        <div class="text-xs text-slate-400 truncate mt-1">${escapeHtml(t.subject)}</div>
                        ${desc}
                    </div>
                    <div class="flex gap-1 ml-2">
                        <button type="button" class="btn-preview p-2 hover:bg-white/10 rounded-lg transition-colors" data-template-id="${t.id}" title="Быстрый просмотр">
                            <i data-lucide="eye" class="w-4 h-4 text-slate-400 pointer-events-none"></i>
                        </button>
                        <button type="button" class="btn-edit p-2 hover:bg-purple-500/20 rounded-lg transition-colors" data-template-id="${t.id}" title="Редактировать">
                            <i data-lucide="pencil" class="w-4 h-4 text-purple-400 pointer-events-none"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    container.html(html);
    lucide.createIcons();
    
    // Привязываем события через делегирование
    container.off('click').on('click', '.template-info', function() {
        const id = $(this).data('template-id');
        console.log('Template info clicked, id:', id);
        loadTemplate(id);
    });
    
    container.on('click', '.btn-preview', function(e) {
        e.stopPropagation();
        const id = $(this).data('template-id');
        console.log('Preview clicked, id:', id);
        quickPreview(id);
    });
    
    container.on('click', '.btn-edit', function(e) {
        e.stopPropagation();
        const id = $(this).data('template-id');
        console.log('Edit clicked, id:', id);
        loadTemplate(id);
    });
}

function quickPreview(id) {
    $.get('/api/admin/settings.php?action=get_template&id=' + id, function(response) {
        if (response.success) {
            const t = response.data;
            const preview = t.body
                .replace(/\{\{username\}\}/g, 'Иван Петров')
                .replace(/\{\{email\}\}/g, 'user@example.com')
                .replace(/\{\{link\}\}/g, 'https://example.com/verify?token=abc123')
                .replace(/\{\{message\}\}/g, 'Это тестовое сообщение.')
                .replace(/\{\{subject\}\}/g, t.subject)
                .replace(/\{\{site_name\}\}/g, 'AuraUI')
                .replace(/\{\{site_url\}\}/g, 'https://auraui.com')
                .replace(/\{\{date\}\}/g, new Date().toLocaleDateString('ru-RU'))
                .replace(/\{\{year\}\}/g, new Date().getFullYear());
            
            $('#template-preview-content').html(preview);
            $('#template-preview-modal').removeClass('hidden');
            lucide.createIcons();
        }
    });
}

function loadTemplate(id) {
    console.log('loadTemplate called with id:', id);
    
    // Показываем индикатор загрузки
    $('#template-body').val('Загрузка...');
    
    $.ajax({
        url: '/api/admin/settings.php',
        method: 'GET',
        data: { action: 'get_template', id: id },
        dataType: 'json',
        success: function(response) {
            console.log('API response:', response);
            if (response.success && response.data) {
                const t = response.data;
                $('#template-id').val(t.id);
                $('#template-name').val(t.name);
                $('#template-subject').val(t.subject);
                $('#template-description').val(t.description || '');
                $('#template-body').val(t.body);
                $('#btn-delete-template').removeClass('hidden').css('display', 'flex');
                
                // Подсветка выбранного шаблона
                $('.template-item').removeClass('border-purple-500 bg-purple-500/20');
                $('.template-item[data-id="' + id + '"]').addClass('border-purple-500 bg-purple-500/20');
                
                showToast('Шаблон загружен для редактирования', 'info', 'Редактирование');
            } else {
                showToast(response.error || 'Ошибка загрузки шаблона', 'error');
                $('#template-body').val('');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
            showToast('Ошибка соединения: ' + error, 'error');
            $('#template-body').val('');
        }
    });
}

function createNewTemplate() {
    $('#template-id').val(0);
    $('#template-name').val('');
    $('#template-subject').val('');
    $('#template-description').val('');
    $('#template-body').val('');
    $('#btn-delete-template').addClass('hidden');
    $('.template-item').removeClass('border-purple-500/50 bg-purple-500/10');
}

$('#template-form').on('submit', function(e) {
    e.preventDefault();
    
    $.post('/api/admin/settings.php?action=save_template', $(this).serialize(), function(response) {
        if (response.success) {
            showToast('Шаблон сохранён', 'success');
            loadTemplates();
            if (response.id) {
                $('#template-id').val(response.id);
                $('#btn-delete-template').removeClass('hidden');
            }
        } else {
            showToast(response.error, 'error');
        }
    });
});

async function deleteTemplate() {
    const id = $('#template-id').val();
    if (!id || id == 0) return;
    
    const confirmed = await showConfirm({
        title: 'Удаление шаблона',
        message: 'Вы уверены, что хотите удалить этот шаблон? Это действие нельзя отменить.',
        confirmText: 'Удалить',
        type: 'danger'
    });
    
    if (!confirmed) return;
    
    $.post('/api/admin/settings.php?action=delete_template', {
        id: id,
        csrf_token: $('input[name="csrf_token"]').first().val()
    }, function(response) {
        if (response.success) {
            showToast('Шаблон удалён', 'success');
            createNewTemplate();
            loadTemplates();
        } else {
            showToast(response.error, 'error');
        }
    });
}

function previewTemplate() {
    const body = $('#template-body').val();
    if (!body.trim()) {
        showToast('Введите HTML содержимое для предпросмотра', 'warning');
        return;
    }
    
    const preview = body
        .replace(/\{\{username\}\}/g, 'Иван Петров')
        .replace(/\{\{email\}\}/g, 'user@example.com')
        .replace(/\{\{link\}\}/g, 'https://example.com/verify?token=abc123')
        .replace(/\{\{message\}\}/g, 'Это тестовое сообщение для предпросмотра шаблона.')
        .replace(/\{\{subject\}\}/g, $('#template-subject').val() || 'Тема письма')
        .replace(/\{\{site_name\}\}/g, 'AuraUI')
        .replace(/\{\{site_url\}\}/g, 'https://auraui.com')
        .replace(/\{\{date\}\}/g, new Date().toLocaleDateString('ru-RU'))
        .replace(/\{\{year\}\}/g, new Date().getFullYear());
    
    $('#template-preview-content').html(preview);
    $('#template-preview-modal').removeClass('hidden');
    lucide.createIcons();
}

function closePreviewModal() {
    $('#template-preview-modal').addClass('hidden');
}

function toggleVariablesHelp() {
    $('#variables-help').toggleClass('hidden');
    lucide.createIcons();
}

function insertVariable() {
    const variables = [
        { value: '{{username}}', label: 'Имя пользователя' },
        { value: '{{email}}', label: 'Email' },
        { value: '{{link}}', label: 'Ссылка' },
        { value: '{{message}}', label: 'Сообщение' },
        { value: '{{subject}}', label: 'Тема' },
        { value: '{{site_name}}', label: 'Название сайта' },
        { value: '{{site_url}}', label: 'URL сайта' },
        { value: '{{date}}', label: 'Дата' },
        { value: '{{year}}', label: 'Год' }
    ];
    
    let html = '<div class="grid grid-cols-1 gap-2 mt-4">';
    variables.forEach(v => {
        html += `<button type="button" onclick="insertVariableText('${v.value}')" class="p-2 bg-white/5 hover:bg-purple-500/20 rounded-lg text-left transition-colors">
            <code class="text-purple-400">${v.value}</code>
            <span class="text-xs text-slate-500 ml-2">${v.label}</span>
        </button>`;
    });
    html += '</div>';
    
    showConfirm({
        title: 'Вставить переменную',
        message: html,
        confirmText: 'Закрыть',
        cancelText: '',
        type: 'info'
    });
}

function insertVariableText(variable) {
    const textarea = document.getElementById('template-body');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + variable.length;
    
    $('.confirm-modal-overlay').removeClass('active');
    setTimeout(() => $('.confirm-modal-overlay').remove(), 200);
}

// System Settings
function loadSystemSettings() {
    $.get('/api/admin/settings.php?action=get_settings', function(response) {
        if (response.success && response.data) {
            const s = response.data;
            $('#set-max-login-attempts').val(s.max_login_attempts || 5);
            $('#set-lockout-duration').val(s.lockout_duration || 15);
            $('#set-session-timeout').val(s.session_timeout || 3600);
            $('#set-password-min-length').val(s.password_min_length || 8);
            $('#set-require-email').prop('checked', s.require_email_verification == '1');
            $('#set-allow-registration').prop('checked', s.allow_registration == '1');
            $('#set-maintenance').prop('checked', s.maintenance_mode == '1');
        }
    });
}

$('#system-settings-form').on('submit', function(e) {
    e.preventDefault();
    
    $.post('/api/admin/settings.php?action=save_settings', $(this).serialize(), function(response) {
        if (response.success) {
            showToast('Настройки сохранены', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
});

// Backups
function loadBackups() {
    $.get('/api/admin/settings.php?action=get_backups', function(response) {
        if (response.success) {
            renderBackupsList(response.data);
        }
    });
}

function renderBackupsList(backups) {
    const container = $('#backups-list');
    if (backups.length === 0) {
        container.html('<div class="text-center text-slate-500 py-2 text-sm">Нет резервных копий</div>');
        return;
    }
    
    let html = '';
    backups.slice(0, 5).forEach(b => {
        html += `
            <div class="flex items-center justify-between p-2 bg-white/5 rounded text-sm">
                <div>
                    <div class="font-mono text-xs">${escapeHtml(b.name)}</div>
                    <div class="text-xs text-slate-500">${b.size} KB</div>
                </div>
                <span class="text-xs text-slate-500">${b.date}</span>
            </div>
        `;
    });
    container.html(html);
}

function createBackup() {
    showToast('Создание резервной копии...', 'info');
    
    $.post('/api/admin/settings.php?action=backup_db', {
        csrf_token: $('input[name="csrf_token"]').first().val()
    }, function(response) {
        if (response.success) {
            showToast(response.message, 'success');
            loadBackups();
        } else {
            showToast(response.error, 'error');
        }
    });
}

function sendTestEmail() {
    $.post('/api/admin/settings.php?action=test_email', {
        csrf_token: $('input[name="csrf_token"]').first().val()
    }, function(response) {
        if (response.success) {
            showToast(response.message, 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
