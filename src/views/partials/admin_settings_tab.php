<!-- TAB: SETTINGS & CONTENT -->
<div id="tab-settings" class="tab-content hidden animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-500/20 rounded-xl flex items-center justify-center">
                <i data-lucide="settings" class="w-5 h-5 text-slate-400"></i>
            </div>
            Настройки
        </h1>
        <p class="text-slate-400 text-sm mt-1 ml-13">Конфигурация системы</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
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

<script>
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
