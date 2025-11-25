<!-- TAB: ADMIN NOTIFICATIONS -->
<div id="tab-notifications" class="tab-content hidden animate-fade-in">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
        
        <!-- Notifications List -->
        <div class="xl:col-span-2 glass-panel p-4 sm:p-6 rounded-2xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="bell" class="w-5 h-5 text-yellow-400"></i>
                    Уведомления
                    <span id="unread-badge" class="px-2 py-0.5 bg-red-500 text-white text-xs rounded-full hidden">0</span>
                </h3>
                <div class="flex gap-2">
                    <select id="notif-filter" onchange="loadAdminNotifications()" class="px-2 sm:px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-xs sm:text-sm">
                        <option value="">Все</option>
                        <option value="registration">Регистрации</option>
                        <option value="security">Безопасность</option>
                        <option value="system">Система</option>
                        <option value="report">Отчёты</option>
                    </select>
                    <button onclick="markAllRead()" class="px-2 sm:px-3 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-xs sm:text-sm transition-colors whitespace-nowrap">
                        <span class="hidden sm:inline">Прочитать все</span><span class="sm:hidden">✓</span>
                    </button>
                </div>
            </div>
            
            <div id="admin-notifications-list" class="space-y-3 max-h-[600px] overflow-y-auto pr-2">
                <div class="text-center text-slate-500 py-8">Загрузка...</div>
            </div>
            
            <div class="mt-4 flex justify-between items-center">
                <button onclick="clearOldNotifications()" class="text-sm text-slate-500 hover:text-red-400 transition-colors">
                    Очистить старые (30+ дней)
                </button>
                <button onclick="loadMoreNotifications()" id="btn-load-more" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-sm transition-colors hidden">
                    Загрузить ещё
                </button>
            </div>
        </div>
        
        <!-- Notification Settings -->
        <div class="glass-panel p-4 sm:p-6 rounded-2xl h-fit">
            <h3 class="text-base sm:text-lg font-semibold flex items-center gap-2 mb-6">
                <i data-lucide="settings-2" class="w-5 h-5 text-purple-400"></i>
                <span class="hidden sm:inline">Настройки уведомлений</span>
                <span class="sm:hidden">Настройки</span>
            </h3>
            
            <form id="notif-settings-form" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 bg-white/5 rounded-lg cursor-pointer hover:bg-white/10 transition-colors">
                        <input type="checkbox" name="notify_new_registration" id="notif-registration" class="toggle-checkbox">
                        <div>
                            <p class="font-medium text-sm">Новые регистрации</p>
                            <p class="text-xs text-slate-500">Уведомлять о новых пользователях</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-3 bg-white/5 rounded-lg cursor-pointer hover:bg-white/10 transition-colors">
                        <input type="checkbox" name="notify_suspicious_activity" id="notif-suspicious" class="toggle-checkbox">
                        <div>
                            <p class="font-medium text-sm">Подозрительная активность</p>
                            <p class="text-xs text-slate-500">Множественные неудачные входы</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-3 bg-white/5 rounded-lg cursor-pointer hover:bg-white/10 transition-colors">
                        <input type="checkbox" name="notify_failed_logins" id="notif-failed" class="toggle-checkbox">
                        <div>
                            <p class="font-medium text-sm">Неудачные входы</p>
                            <p class="text-xs text-slate-500">Логировать все неудачные попытки</p>
                        </div>
                    </label>
                </div>
                
                <div class="pt-4 border-t border-white/10 space-y-3">
                    <label class="flex items-center gap-3 p-3 bg-white/5 rounded-lg cursor-pointer hover:bg-white/10 transition-colors">
                        <input type="checkbox" name="email_reports" id="notif-email-reports" class="toggle-checkbox">
                        <div>
                            <p class="font-medium text-sm">Email отчёты</p>
                            <p class="text-xs text-slate-500">Получать отчёты на почту</p>
                        </div>
                    </label>
                    
                    <div class="p-3 bg-white/5 rounded-lg">
                        <p class="text-sm mb-2">Частота отчётов</p>
                        <select name="email_report_frequency" id="notif-frequency" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-sm">
                            <option value="daily">Ежедневно</option>
                            <option value="weekly">Еженедельно</option>
                            <option value="monthly">Ежемесячно</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="w-full py-2 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 rounded-lg transition-colors">
                    Сохранить настройки
                </button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-white/10">
                <button onclick="sendAdminReport()" class="w-full py-2 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Отправить отчёт сейчас
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let notifOffset = 0;
const notifLimit = 20;

function loadAdminNotifications(reset = true) {
    if (reset) notifOffset = 0;
    
    const type = $('#notif-filter').val();
    let url = `/api/admin/notifications.php?action=get&limit=${notifLimit}&offset=${notifOffset}`;
    if (type) url += `&type=${type}`;
    
    $.get(url, function(response) {
        if (response.success) {
            if (reset) {
                renderAdminNotifications(response.data);
            } else {
                appendAdminNotifications(response.data);
            }
            
            // Update unread badge
            if (response.unread_count > 0) {
                $('#unread-badge').text(response.unread_count).removeClass('hidden');
            } else {
                $('#unread-badge').addClass('hidden');
            }
            
            // Show/hide load more button
            if (response.data.length >= notifLimit) {
                $('#btn-load-more').removeClass('hidden');
            } else {
                $('#btn-load-more').addClass('hidden');
            }
        }
    });
}

function renderAdminNotifications(notifications) {
    const container = $('#admin-notifications-list');
    
    if (notifications.length === 0) {
        container.html('<div class="text-center text-slate-500 py-8">Нет уведомлений</div>');
        return;
    }
    
    container.html(notifications.map(n => notificationHtml(n)).join(''));
    lucide.createIcons();
}

function appendAdminNotifications(notifications) {
    const container = $('#admin-notifications-list');
    container.append(notifications.map(n => notificationHtml(n)).join(''));
    lucide.createIcons();
}

function notificationHtml(n) {
    const icons = {
        registration: 'user-plus',
        security: 'shield-alert',
        system: 'info',
        report: 'file-text'
    };
    const colors = {
        registration: 'text-green-400',
        security: 'text-red-400',
        system: 'text-blue-400',
        report: 'text-purple-400'
    };
    
    const unreadClass = n.is_read ? 'opacity-60' : 'border-l-2 border-purple-500';
    
    return `
        <div class="p-4 bg-white/5 rounded-lg ${unreadClass} hover:bg-white/10 transition-colors" data-id="${n.id}">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-white/5 rounded-lg">
                    <i data-lucide="${icons[n.type] || 'bell'}" class="w-5 h-5 ${colors[n.type] || 'text-slate-400'}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h4 class="font-medium text-sm">${escapeHtml(n.title)}</h4>
                        <span class="text-xs text-slate-500 whitespace-nowrap">${formatDate(n.created_at)}</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-1">${escapeHtml(n.message)}</p>
                </div>
                <div class="flex gap-1">
                    ${!n.is_read ? `<button onclick="markNotifRead(${n.id})" class="p-1.5 hover:bg-white/10 rounded transition-colors" title="Прочитано">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </button>` : ''}
                    <button onclick="deleteNotification(${n.id})" class="p-1.5 hover:bg-red-500/20 rounded transition-colors text-slate-500 hover:text-red-400" title="Удалить">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function loadMoreNotifications() {
    notifOffset += notifLimit;
    loadAdminNotifications(false);
}

function markNotifRead(id) {
    $.post('/api/admin/notifications.php?action=mark_read', { id: id }, function(response) {
        if (response.success) {
            loadAdminNotifications();
        }
    });
}

function markAllRead() {
    $.post('/api/admin/notifications.php?action=mark_read', { id: 0 }, function(response) {
        if (response.success) {
            showToast('Все уведомления прочитаны', 'success');
            loadAdminNotifications();
        }
    });
}

function deleteNotification(id) {
    $.post('/api/admin/notifications.php?action=delete', { id: id }, function(response) {
        if (response.success) {
            $(`[data-id="${id}"]`).fadeOut(200, function() { $(this).remove(); });
        }
    });
}

async function clearOldNotifications() {
    const confirmed = await showConfirm({
        title: 'Очистка уведомлений',
        message: 'Удалить все уведомления старше 30 дней? Это действие нельзя отменить.',
        confirmText: 'Удалить',
        type: 'danger'
    });
    
    if (!confirmed) return;
    
    $.post('/api/admin/notifications.php?action=clear_old', { days: 30 }, function(response) {
        if (response.success) {
            showToast(response.message, 'success');
            loadAdminNotifications();
        }
    });
}

// Notification Settings
function loadNotifSettings() {
    $.get('/api/admin/notifications.php?action=get_settings', function(response) {
        if (response.success && response.data) {
            const s = response.data;
            $('#notif-registration').prop('checked', s.notify_new_registration == 1);
            $('#notif-suspicious').prop('checked', s.notify_suspicious_activity == 1);
            $('#notif-failed').prop('checked', s.notify_failed_logins == 1);
            $('#notif-email-reports').prop('checked', s.email_reports == 1);
            $('#notif-frequency').val(s.email_report_frequency || 'daily');
        }
    });
}

$('#notif-settings-form').on('submit', function(e) {
    e.preventDefault();
    
    $.post('/api/admin/notifications.php?action=save_settings', $(this).serialize(), function(response) {
        if (response.success) {
            showToast('Настройки сохранены', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
});

function sendAdminReport() {
    $.post('/api/admin/notifications.php?action=send_report', {
        csrf_token: $('input[name="csrf_token"]').first().val()
    }, function(response) {
        if (response.success) {
            showToast(response.message, 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'только что';
    if (diff < 3600000) return Math.floor(diff / 60000) + ' мин назад';
    if (diff < 86400000) return Math.floor(diff / 3600000) + ' ч назад';
    
    return date.toLocaleDateString('ru-RU', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
}
</script>
