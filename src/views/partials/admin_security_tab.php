<!-- Таб: Безопасность -->
<div id="tab-security" class="tab-content hidden animate-fade-in">
    
    <!-- Подтабы -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button onclick="switchSecurityTab('activity')" id="btn-sec-activity" class="sec-tab-btn active px-4 py-2 rounded-lg text-sm font-medium bg-white/5 hover:bg-white/10 border border-white/10 transition-all">
            <i data-lucide="activity" class="w-4 h-4 inline mr-1"></i> Журнал активности
        </button>
        <button onclick="switchSecurityTab('attempts')" id="btn-sec-attempts" class="sec-tab-btn px-4 py-2 rounded-lg text-sm font-medium bg-white/5 hover:bg-white/10 border border-white/10 transition-all">
            <i data-lucide="shield-alert" class="w-4 h-4 inline mr-1"></i> Попытки входа
        </button>
        <button onclick="switchSecurityTab('blocked')" id="btn-sec-blocked" class="sec-tab-btn px-4 py-2 rounded-lg text-sm font-medium bg-white/5 hover:bg-white/10 border border-white/10 transition-all">
            <i data-lucide="ban" class="w-4 h-4 inline mr-1"></i> Заблокированные IP
        </button>
        <button onclick="switchSecurityTab('sessions')" id="btn-sec-sessions" class="sec-tab-btn px-4 py-2 rounded-lg text-sm font-medium bg-white/5 hover:bg-white/10 border border-white/10 transition-all">
            <i data-lucide="monitor" class="w-4 h-4 inline mr-1"></i> Активные сессии
        </button>
    </div>

    <!-- Журнал активности -->
    <div id="sec-activity" class="sec-content">
        <div class="glass-panel rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-blue-400"></i> Журнал активности
                </h3>
                <button onclick="loadActivityLog()" class="text-sm text-slate-400 hover:text-white">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="overflow-x-auto max-h-96" id="activity-log-container">
                <div class="p-8 text-center text-slate-500">Загрузка...</div>
            </div>
        </div>
    </div>

    <!-- Попытки входа -->
    <div id="sec-attempts" class="sec-content hidden">
        <div class="glass-panel rounded-2xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="shield-alert" class="w-5 h-5 text-yellow-400"></i> Подозрительные IP (3+ неудачных попыток за 24ч)
                </h3>
            </div>
            <div class="overflow-x-auto" id="suspicious-ips-container">
                <div class="p-8 text-center text-slate-500">Загрузка...</div>
            </div>
        </div>
        
        <div class="glass-panel rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-semibold">Последние попытки входа</h3>
                <select id="attempts-filter" onchange="loadLoginAttempts()" class="bg-slate-800 border border-white/10 rounded-lg px-3 py-1 text-sm">
                    <option value="all">Все</option>
                    <option value="failed">Неудачные</option>
                    <option value="success">Успешные</option>
                </select>
            </div>
            <div class="overflow-x-auto max-h-96" id="login-attempts-container">
                <div class="p-8 text-center text-slate-500">Загрузка...</div>
            </div>
        </div>
    </div>

    <!-- Заблокированные IP -->
    <div id="sec-blocked" class="sec-content hidden">
        <div class="glass-panel rounded-2xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="font-semibold flex items-center gap-2 mb-4">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-red-400"></i> Заблокировать IP
                </h3>
                <form onsubmit="blockIP(event)" class="flex flex-wrap gap-3">
                    <input type="text" id="block-ip" placeholder="IP адрес" required
                           class="bg-slate-800 border border-white/10 rounded-lg px-3 py-2 text-sm w-40">
                    <input type="text" id="block-reason" placeholder="Причина"
                           class="bg-slate-800 border border-white/10 rounded-lg px-3 py-2 text-sm flex-1 min-w-48">
                    <select id="block-duration" class="bg-slate-800 border border-white/10 rounded-lg px-3 py-2 text-sm">
                        <option value="1">1 час</option>
                        <option value="24" selected>24 часа</option>
                        <option value="168">7 дней</option>
                        <option value="720">30 дней</option>
                        <option value="permanent">Навсегда</option>
                    </select>
                    <button type="submit" class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Заблокировать
                    </button>
                </form>
            </div>
        </div>
        
        <div class="glass-panel rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="font-semibold">Заблокированные IP</h3>
            </div>
            <div class="overflow-x-auto" id="blocked-ips-container">
                <div class="p-8 text-center text-slate-500">Загрузка...</div>
            </div>
        </div>
    </div>

    <!-- Активные сессии -->
    <div id="sec-sessions" class="sec-content hidden">
        <div class="glass-panel rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="monitor" class="w-5 h-5 text-green-400"></i> Активные сессии
                </h3>
                <button onclick="loadSessions()" class="text-sm text-slate-400 hover:text-white">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="overflow-x-auto" id="sessions-container">
                <div class="p-8 text-center text-slate-500">Загрузка...</div>
            </div>
        </div>
    </div>
</div>

<script>
function switchSecurityTab(tab) {
    $('.sec-content').addClass('hidden');
    $('#sec-' + tab).removeClass('hidden');
    $('.sec-tab-btn').removeClass('active bg-purple-500/20 border-purple-500/50');
    $('#btn-sec-' + tab).addClass('active bg-purple-500/20 border-purple-500/50');
    
    // Загружаем данные при переключении
    if (tab === 'activity') loadActivityLog();
    if (tab === 'attempts') { loadLoginAttempts(); loadSuspiciousIPs(); }
    if (tab === 'blocked') loadBlockedIPs();
    if (tab === 'sessions') loadSessions();
}

function loadActivityLog() {
    $.get('/api/admin/security.php?action=get_activity_log&limit=100', function(r) {
        if (r.success && r.data.length) {
            let html = '<table class="w-full text-sm"><thead class="bg-slate-900/50 text-xs uppercase"><tr><th class="px-4 py-3 text-left">Время</th><th class="px-4 py-3 text-left">Пользователь</th><th class="px-4 py-3 text-left">Действие</th><th class="px-4 py-3 text-left">Описание</th></tr></thead><tbody class="divide-y divide-white/5">';
            r.data.forEach(function(log) {
                html += `<tr class="hover:bg-white/5"><td class="px-4 py-3 text-slate-400">${formatDate(log.created_at)}</td><td class="px-4 py-3">${log.username || '-'}</td><td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs bg-blue-500/20 text-blue-400">${log.action}</span></td><td class="px-4 py-3 text-slate-400 truncate max-w-xs">${log.description || '-'}</td></tr>`;
            });
            html += '</tbody></table>';
            $('#activity-log-container').html(html);
        } else {
            $('#activity-log-container').html('<div class="p-8 text-center text-slate-500">Нет записей</div>');
        }
    });
}

function loadLoginAttempts() {
    const filter = $('#attempts-filter').val();
    $.get('/api/admin/security.php?action=get_login_attempts&filter=' + filter + '&limit=100', function(r) {
        if (r.success && r.data.length) {
            let html = '<table class="w-full text-sm"><thead class="bg-slate-900/50 text-xs uppercase"><tr><th class="px-4 py-3">Время</th><th class="px-4 py-3">IP</th><th class="px-4 py-3">Логин</th><th class="px-4 py-3">Статус</th><th class="px-4 py-3">Причина</th></tr></thead><tbody class="divide-y divide-white/5">';
            r.data.forEach(function(a) {
                const statusClass = a.success ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400';
                const statusText = a.success ? 'Успех' : 'Неудача';
                html += `<tr class="hover:bg-white/5"><td class="px-4 py-3 text-slate-400">${formatDate(a.attempted_at)}</td><td class="px-4 py-3 font-mono text-xs">${a.ip_address}</td><td class="px-4 py-3">${a.username || '-'}</td><td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs ${statusClass}">${statusText}</span></td><td class="px-4 py-3 text-slate-500 text-xs">${a.failure_reason || '-'}</td></tr>`;
            });
            html += '</tbody></table>';
            $('#login-attempts-container').html(html);
        } else {
            $('#login-attempts-container').html('<div class="p-8 text-center text-slate-500">Нет записей</div>');
        }
    });
}

function loadSuspiciousIPs() {
    $.get('/api/admin/security.php?action=get_ip_stats', function(r) {
        if (r.success && r.data.length) {
            let html = '<table class="w-full text-sm"><thead class="bg-slate-900/50 text-xs uppercase"><tr><th class="px-4 py-3">IP</th><th class="px-4 py-3">Неудачных</th><th class="px-4 py-3">Успешных</th><th class="px-4 py-3">Последняя</th><th class="px-4 py-3">Действие</th></tr></thead><tbody class="divide-y divide-white/5">';
            r.data.forEach(function(ip) {
                html += `<tr class="hover:bg-white/5"><td class="px-4 py-3 font-mono text-xs">${ip.ip_address}</td><td class="px-4 py-3 text-red-400 font-bold">${ip.failed_attempts}</td><td class="px-4 py-3 text-green-400">${ip.success_attempts}</td><td class="px-4 py-3 text-slate-400">${formatDate(ip.last_attempt)}</td><td class="px-4 py-3"><button onclick="quickBlockIP('${ip.ip_address}')" class="text-xs text-red-400 hover:text-red-300">Заблокировать</button></td></tr>`;
            });
            html += '</tbody></table>';
            $('#suspicious-ips-container').html(html);
        } else {
            $('#suspicious-ips-container').html('<div class="p-6 text-center text-slate-500 text-sm">Подозрительных IP не обнаружено</div>');
        }
    });
}

function loadBlockedIPs() {
    $.get('/api/admin/security.php?action=get_blocked_ips', function(r) {
        if (r.success && r.data.length) {
            let html = '<table class="w-full text-sm"><thead class="bg-slate-900/50 text-xs uppercase"><tr><th class="px-4 py-3">IP</th><th class="px-4 py-3">Причина</th><th class="px-4 py-3">Заблокировал</th><th class="px-4 py-3">Истекает</th><th class="px-4 py-3">Действие</th></tr></thead><tbody class="divide-y divide-white/5">';
            r.data.forEach(function(ip) {
                const expires = ip.is_permanent ? '<span class="text-red-400">Навсегда</span>' : (ip.expires_at ? formatDate(ip.expires_at) : '-');
                html += `<tr class="hover:bg-white/5"><td class="px-4 py-3 font-mono text-xs">${ip.ip_address}</td><td class="px-4 py-3 text-slate-400">${ip.reason || '-'}</td><td class="px-4 py-3">${ip.blocked_by_name || '-'}</td><td class="px-4 py-3">${expires}</td><td class="px-4 py-3"><button onclick="unblockIP(${ip.id})" class="text-xs text-green-400 hover:text-green-300">Разблокировать</button></td></tr>`;
            });
            html += '</tbody></table>';
            $('#blocked-ips-container').html(html);
        } else {
            $('#blocked-ips-container').html('<div class="p-8 text-center text-slate-500">Нет заблокированных IP</div>');
        }
    });
}

function loadSessions() {
    $.get('/api/admin/security.php?action=get_sessions', function(r) {
        if (r.success && r.data.length) {
            let html = '<table class="w-full text-sm"><thead class="bg-slate-900/50 text-xs uppercase"><tr><th class="px-4 py-3">Пользователь</th><th class="px-4 py-3">IP</th><th class="px-4 py-3">Устройство</th><th class="px-4 py-3">Последняя активность</th><th class="px-4 py-3">Действие</th></tr></thead><tbody class="divide-y divide-white/5">';
            r.data.forEach(function(s) {
                html += `<tr class="hover:bg-white/5"><td class="px-4 py-3">${s.username}</td><td class="px-4 py-3 font-mono text-xs">${s.ip_address}</td><td class="px-4 py-3 text-slate-400 text-xs truncate max-w-xs">${s.device_info || 'Unknown'}</td><td class="px-4 py-3 text-slate-400">${formatDate(s.last_activity)}</td><td class="px-4 py-3"><button onclick="terminateSession(${s.id})" class="text-xs text-red-400 hover:text-red-300">Завершить</button></td></tr>`;
            });
            html += '</tbody></table>';
            $('#sessions-container').html(html);
        } else {
            $('#sessions-container').html('<div class="p-8 text-center text-slate-500">Нет активных сессий</div>');
        }
    });
}

function blockIP(e) {
    e.preventDefault();
    const duration = $('#block-duration').val();
    $.post('/api/admin/security.php', {
        action: 'block_ip',
        ip: $('#block-ip').val(),
        reason: $('#block-reason').val(),
        hours: duration === 'permanent' ? 0 : duration,
        permanent: duration === 'permanent' ? 1 : 0,
        csrf_token: csrfToken
    }, function(r) {
        if (r.success) {
            showNotification(r.message, 'success');
            $('#block-ip, #block-reason').val('');
            loadBlockedIPs();
        } else {
            showNotification(r.error, 'error');
        }
    });
}

function quickBlockIP(ip) {
    if (!confirm('Заблокировать IP ' + ip + ' на 24 часа?')) return;
    $.post('/api/admin/security.php', {
        action: 'block_ip',
        ip: ip,
        reason: 'Подозрительная активность',
        hours: 24,
        csrf_token: csrfToken
    }, function(r) {
        if (r.success) {
            showNotification(r.message, 'success');
            loadSuspiciousIPs();
            loadBlockedIPs();
        } else {
            showNotification(r.error, 'error');
        }
    });
}

function unblockIP(id) {
    if (!confirm('Разблокировать этот IP?')) return;
    $.post('/api/admin/security.php', {
        action: 'unblock_ip',
        id: id,
        csrf_token: csrfToken
    }, function(r) {
        if (r.success) {
            showNotification(r.message, 'success');
            loadBlockedIPs();
        } else {
            showNotification(r.error, 'error');
        }
    });
}

function terminateSession(id) {
    if (!confirm('Завершить эту сессию?')) return;
    $.post('/api/admin/security.php', {
        action: 'terminate_session',
        session_id: id,
        csrf_token: csrfToken
    }, function(r) {
        if (r.success) {
            showNotification(r.message, 'success');
            loadSessions();
        } else {
            showNotification(r.error, 'error');
        }
    });
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('ru-RU') + ' ' + d.toLocaleTimeString('ru-RU', {hour: '2-digit', minute: '2-digit'});
}
</script>
