<!-- TAB: SESSIONS -->
<div id="tab-sessions" class="tab-content hidden animate-fade-in">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6">
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">Активных сессий</div>
            <div id="active-sessions" class="text-xl sm:text-2xl font-bold text-emerald-400">0</div>
        </div>
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">Пользователей</div>
            <div id="active-users" class="text-xl sm:text-2xl font-bold text-blue-400">0</div>
        </div>
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">За 24 часа</div>
            <div id="sessions-24h" class="text-xl sm:text-2xl font-bold text-purple-400">0</div>
        </div>
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">За 7 дней</div>
            <div id="sessions-7d" class="text-xl sm:text-2xl font-bold text-slate-400">0</div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <!-- Devices -->
        <div class="glass-panel p-4 sm:p-6 rounded-2xl">
            <h4 class="text-sm font-medium text-slate-400 mb-4">Устройства</h4>
            <div id="devices-chart" class="space-y-2"></div>
        </div>
        <!-- Browsers -->
        <div class="glass-panel p-4 sm:p-6 rounded-2xl">
            <h4 class="text-sm font-medium text-slate-400 mb-4">Браузеры</h4>
            <div id="browsers-chart" class="space-y-2"></div>
        </div>
        <!-- Actions -->
        <div class="glass-panel p-4 sm:p-6 rounded-2xl sm:col-span-2 lg:col-span-1">
            <h4 class="text-sm font-medium text-slate-400 mb-4">Действия</h4>
            <div class="flex sm:flex-col gap-2 sm:gap-3">
                <button onclick="loadLoginHistory()" class="flex-1 sm:w-full px-3 sm:px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 rounded-lg text-xs sm:text-sm transition-colors">
                    <i data-lucide="history" class="w-4 h-4 inline"></i> <span class="hidden sm:inline">История входов</span><span class="sm:hidden">История</span>
                </button>
                <button onclick="terminateAllInactive()" class="flex-1 sm:w-full px-3 sm:px-4 py-2 bg-orange-500/20 hover:bg-orange-500/30 text-orange-300 rounded-lg text-xs sm:text-sm transition-colors">
                    <i data-lucide="user-x" class="w-4 h-4 inline"></i> <span class="hidden sm:inline">Завершить неактивные</span><span class="sm:hidden">Завершить</span>
                </button>
            </div>
        </div>
    </div>

    <div class="glass-panel p-4 sm:p-6 rounded-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-semibold flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-emerald-400"></i>
                <span class="hidden sm:inline">Активные сессии</span>
                <span class="sm:hidden">Сессии</span>
            </h3>
            <button onclick="loadSessions()" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded-lg text-xs sm:text-sm transition-colors">
                <i data-lucide="refresh-cw" class="w-4 h-4 inline"></i> Обновить
            </button>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
            <table class="w-full min-w-[600px]">
                <thead>
                    <tr class="text-left text-xs sm:text-sm text-slate-400 border-b border-white/10">
                        <th class="pb-3 font-medium">Пользователь</th>
                        <th class="pb-3 font-medium">IP</th>
                        <th class="pb-3 font-medium">Устройство</th>
                        <th class="pb-3 font-medium">Браузер</th>
                        <th class="pb-3 font-medium">Активность</th>
                        <th class="pb-3 font-medium">Действия</th>
                    </tr>
                </thead>
                <tbody id="sessions-table" class="text-xs sm:text-sm">
                    <tr><td colspan="6" class="py-4 text-center text-slate-400">Загрузка...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Login History -->
    <div id="login-history-panel" class="glass-panel p-4 sm:p-6 rounded-2xl mt-6 hidden">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base sm:text-lg font-semibold flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-blue-400"></i>
                <span class="hidden sm:inline">История входов</span>
                <span class="sm:hidden">История</span>
            </h3>
            <button onclick="$('#login-history-panel').addClass('hidden')" class="text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="overflow-x-auto -mx-4 sm:mx-0 px-4 sm:px-0">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-slate-400 border-b border-white/10">
                        <th class="pb-3 font-medium">Пользователь</th>
                        <th class="pb-3 font-medium">IP</th>
                        <th class="pb-3 font-medium">Страна/Город</th>
                        <th class="pb-3 font-medium">Устройство</th>
                        <th class="pb-3 font-medium">Дата</th>
                        <th class="pb-3 font-medium">Статус</th>
                    </tr>
                </thead>
                <tbody id="history-table" class="text-sm"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
function loadSessions() {
    $.get('/api/admin/sessions.php?action=list', function(response) {
        if (response.success) {
            renderSessions(response.sessions);
        }
    });
    
    $.get('/api/admin/sessions.php?action=stats', function(response) {
        if (response.success) {
            $('#active-sessions').text(response.stats.active_sessions);
            $('#active-users').text(response.stats.active_users);
            $('#sessions-24h').text(response.stats.sessions_24h);
            $('#sessions-7d').text(response.stats.sessions_7d);
            
            renderDevicesChart(response.devices);
            renderBrowsersChart(response.browsers);
        }
    });
}

function renderSessions(sessions) {
    if (sessions.length === 0) {
        $('#sessions-table').html('<tr><td colspan="6" class="py-4 text-center text-slate-400">Нет активных сессий</td></tr>');
        return;
    }
    
    let html = '';
    sessions.forEach(s => {
        const isCurrent = s.is_current;
        html += `
            <tr class="border-b border-white/5 ${isCurrent ? 'bg-emerald-500/10' : ''}">
                <td class="py-3">
                    <div class="font-medium">${s.username}</div>
                    <div class="text-xs text-slate-500">${s.email}</div>
                </td>
                <td class="py-3">
                    <span class="font-mono text-xs">${s.ip_address}</span>
                    ${s.country ? `<div class="text-xs text-slate-500">${s.country}${s.city ? ', ' + s.city : ''}</div>` : ''}
                </td>
                <td class="py-3">
                    <span class="px-2 py-0.5 bg-slate-700 rounded text-xs">${s.device_type || 'desktop'}</span>
                </td>
                <td class="py-3 text-slate-400">${s.browser || '-'}</td>
                <td class="py-3">
                    <div class="text-xs">${s.time_ago}</div>
                </td>
                <td class="py-3">
                    ${isCurrent ? 
                        '<span class="text-xs text-emerald-400">Текущая</span>' : 
                        `<button onclick="terminateSession('${s.session_id}')" class="px-2 py-1 bg-red-500/20 hover:bg-red-500/30 text-red-300 rounded text-xs transition-colors">Завершить</button>`
                    }
                </td>
            </tr>
        `;
    });
    
    $('#sessions-table').html(html);
}

function renderDevicesChart(devices) {
    if (!devices || devices.length === 0) {
        $('#devices-chart').html('<div class="text-slate-500 text-sm">Нет данных</div>');
        return;
    }
    
    const total = devices.reduce((sum, d) => sum + parseInt(d.count), 0);
    let html = '';
    
    devices.forEach(d => {
        const percent = Math.round((d.count / total) * 100);
        const icon = d.device_type === 'mobile' ? 'smartphone' : (d.device_type === 'tablet' ? 'tablet' : 'monitor');
        html += `
            <div class="flex items-center gap-3">
                <i data-lucide="${icon}" class="w-4 h-4 text-slate-400"></i>
                <div class="flex-1">
                    <div class="flex justify-between text-sm mb-1">
                        <span>${d.device_type || 'desktop'}</span>
                        <span class="text-slate-400">${d.count} (${percent}%)</span>
                    </div>
                    <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500" style="width: ${percent}%"></div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#devices-chart').html(html);
    lucide.createIcons();
}

function renderBrowsersChart(browsers) {
    if (!browsers || browsers.length === 0) {
        $('#browsers-chart').html('<div class="text-slate-500 text-sm">Нет данных</div>');
        return;
    }
    
    const total = browsers.reduce((sum, b) => sum + parseInt(b.count), 0);
    let html = '';
    
    browsers.forEach(b => {
        const percent = Math.round((b.count / total) * 100);
        html += `
            <div class="flex items-center justify-between text-sm">
                <span>${b.browser || 'Unknown'}</span>
                <span class="text-slate-400">${b.count} (${percent}%)</span>
            </div>
        `;
    });
    
    $('#browsers-chart').html(html);
}

function terminateSession(sessionId) {
    if (!confirm('Завершить эту сессию?')) return;
    
    $.post('/api/admin/sessions.php', {
        action: 'terminate',
        session_id: sessionId
    }, function(response) {
        if (response.success) {
            loadSessions();
            showToast('Сессия завершена', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function loadLoginHistory() {
    $.get('/api/admin/sessions.php?action=login_history&limit=50', function(response) {
        if (response.success) {
            renderLoginHistory(response.history);
            $('#login-history-panel').removeClass('hidden');
        }
    });
}

function renderLoginHistory(history) {
    let html = '';
    history.forEach(h => {
        html += `
            <tr class="border-b border-white/5">
                <td class="py-3">${h.username}</td>
                <td class="py-3 font-mono text-xs">${h.ip_address}</td>
                <td class="py-3 text-slate-400">${h.country || '-'}${h.city ? ', ' + h.city : ''}</td>
                <td class="py-3">${h.device_type || 'desktop'}</td>
                <td class="py-3 text-xs">${h.created_at}</td>
                <td class="py-3">
                    <span class="px-2 py-0.5 rounded text-xs ${h.is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-700 text-slate-400'}">
                        ${h.is_active ? 'Активна' : 'Завершена'}
                    </span>
                </td>
            </tr>
        `;
    });
    $('#history-table').html(html);
}
</script>
