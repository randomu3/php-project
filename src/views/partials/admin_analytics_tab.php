<!-- Таб: Аналитика -->
<div id="tab-analytics" class="tab-content hidden animate-fade-in">
    
    <!-- Карточки статистики -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" id="stats-cards">
        <div class="glass-panel p-4 rounded-xl">
            <p class="text-slate-400 text-xs mb-1">Регистраций сегодня</p>
            <p class="text-2xl font-bold text-white" id="stat-reg-today">-</p>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <p class="text-slate-400 text-xs mb-1">За неделю</p>
            <p class="text-2xl font-bold text-blue-400" id="stat-reg-week">-</p>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <p class="text-slate-400 text-xs mb-1">За месяц</p>
            <p class="text-2xl font-bold text-purple-400" id="stat-reg-month">-</p>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <p class="text-slate-400 text-xs mb-1">Активность</p>
            <p class="text-2xl font-bold text-green-400" id="stat-activity">-</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- График регистраций -->
        <div class="glass-panel rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-5 h-5 text-blue-400"></i> Регистрации
                </h3>
                <select id="chart-period" onchange="loadRegistrationsChart()" class="bg-slate-800 border border-white/10 rounded-lg px-3 py-1 text-sm">
                    <option value="daily">По дням (30д)</option>
                    <option value="weekly">По неделям (12н)</option>
                </select>
            </div>
            <div id="registrations-chart" class="h-48 flex items-end gap-1"></div>
        </div>

        <!-- Активные пользователи -->
        <div class="glass-panel rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-green-400"></i> Активные пользователи
                </h3>
                <select id="active-period" onchange="loadActiveUsers()" class="bg-slate-800 border border-white/10 rounded-lg px-3 py-1 text-sm">
                    <option value="1">Сегодня</option>
                    <option value="7" selected>7 дней</option>
                    <option value="30">30 дней</option>
                </select>
            </div>
            <div id="active-users-stats" class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Активных</span>
                    <span class="text-2xl font-bold text-white" id="active-count">-</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-3">
                    <div id="active-bar" class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all" style="width: 0%"></div>
                </div>
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Входов: <span id="logins-count">-</span></span>
                    <span>Всего: <span id="total-users">-</span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Неподтверждённые аккаунты -->
        <div class="glass-panel rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="mail-warning" class="w-5 h-5 text-yellow-400"></i> Неподтверждённые аккаунты
                    <span class="text-xs bg-yellow-500/20 text-yellow-400 px-2 py-0.5 rounded-full" id="unverified-count">0</span>
                </h3>
            </div>
            <div class="overflow-x-auto max-h-64" id="unverified-list">
                <div class="p-6 text-center text-slate-500">Загрузка...</div>
            </div>
        </div>

        <!-- Топ активных -->
        <div class="glass-panel rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="font-semibold flex items-center gap-2">
                    <i data-lucide="award" class="w-5 h-5 text-purple-400"></i> Топ активных (7 дней)
                </h3>
            </div>
            <div class="overflow-x-auto max-h-64" id="top-active-list">
                <div class="p-6 text-center text-slate-500">Загрузка...</div>
            </div>
        </div>
    </div>
</div>

<script>
function loadAnalytics() {
    loadOverviewStats();
    loadRegistrationsChart();
    loadActiveUsers();
    loadUnverifiedAccounts();
    loadTopActive();
}

function loadOverviewStats() {
    $.get('/api/admin/analytics.php?action=overview', function(r) {
        if (r.success) {
            $('#stat-reg-today').text(r.data.registrations_today);
            $('#stat-reg-week').text(r.data.registrations_week);
            $('#stat-reg-month').text(r.data.registrations_month);
            $('#stat-activity').text(r.data.active_sessions + ' сессий');
            $('#unverified-count').text(r.data.unverified_users);
        }
    });
}

function loadRegistrationsChart() {
    const period = $('#chart-period').val();
    const endpoint = period === 'weekly' ? 'registrations_weekly' : 'registrations_chart';
    
    $.get('/api/admin/analytics.php?action=' + endpoint, function(r) {
        if (r.success && r.data.length) {
            const max = Math.max(...r.data.map(d => d.count));
            let html = '';
            
            r.data.forEach(function(d) {
                const height = max > 0 ? (d.count / max * 100) : 0;
                const label = period === 'weekly' ? 
                    new Date(d.week_start).toLocaleDateString('ru-RU', {day: 'numeric', month: 'short'}) :
                    new Date(d.date).getDate();
                    
                html += `<div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-purple-500 rounded-t transition-all" style="height: ${Math.max(height, 2)}%"></div>
                    <span class="text-xs text-slate-500">${label}</span>
                    <span class="text-xs text-slate-400">${d.count}</span>
                </div>`;
            });
            
            $('#registrations-chart').html(html);
        } else {
            $('#registrations-chart').html('<div class="w-full text-center text-slate-500">Нет данных</div>');
        }
    });
}

function loadActiveUsers() {
    const period = $('#active-period').val();
    $.get('/api/admin/analytics.php?action=active_users&period=' + period, function(r) {
        if (r.success) {
            $('#active-count').text(r.data.active_users);
            $('#total-users').text(r.data.total_users);
            $('#logins-count').text(r.data.logins);
            $('#active-bar').css('width', r.data.activity_rate + '%');
        }
    });
}

function loadUnverifiedAccounts() {
    $.get('/api/admin/analytics.php?action=unverified_accounts', function(r) {
        if (r.success && r.data.length) {
            let html = '<table class="w-full text-sm"><tbody class="divide-y divide-white/5">';
            r.data.forEach(function(u) {
                const hoursAgo = u.hours_ago < 24 ? u.hours_ago + 'ч назад' : Math.floor(u.hours_ago/24) + 'д назад';
                html += `<tr class="hover:bg-white/5">
                    <td class="px-4 py-3">${u.username}</td>
                    <td class="px-4 py-3 text-slate-500 text-xs">${u.email}</td>
                    <td class="px-4 py-3 text-slate-500 text-xs">${hoursAgo}</td>
                    <td class="px-4 py-3"><button onclick="verifyUserEmail(${u.id})" class="text-xs text-blue-400 hover:text-blue-300">Подтвердить</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            $('#unverified-list').html(html);
        } else {
            $('#unverified-list').html('<div class="p-6 text-center text-slate-500">Все аккаунты подтверждены ✓</div>');
        }
    });
}

function loadTopActive() {
    $.get('/api/admin/analytics.php?action=top_active', function(r) {
        if (r.success && r.data.length) {
            let html = '<table class="w-full text-sm"><tbody class="divide-y divide-white/5">';
            let rank = 1;
            r.data.forEach(function(u) {
                const medal = rank <= 3 ? ['🥇','🥈','🥉'][rank-1] : rank;
                html += `<tr class="hover:bg-white/5">
                    <td class="px-4 py-3 w-10">${medal}</td>
                    <td class="px-4 py-3">${u.username}</td>
                    <td class="px-4 py-3 text-right"><span class="text-purple-400 font-medium">${u.actions}</span> действий</td>
                </tr>`;
                rank++;
            });
            html += '</tbody></table>';
            $('#top-active-list').html(html);
        } else {
            $('#top-active-list').html('<div class="p-6 text-center text-slate-500">Нет данных</div>');
        }
    });
}

function verifyUserEmail(userId) {
    if (!confirm('Подтвердить email этого пользователя?')) return;
    $.post('/api/admin/users.php', {
        action: 'verify_email',
        user_id: userId,
        csrf_token: csrfToken
    }, function(r) {
        if (r.success) {
            showNotification(r.message, 'success');
            loadUnverifiedAccounts();
            loadOverviewStats();
        } else {
            showNotification(r.error, 'error');
        }
    });
}
</script>
