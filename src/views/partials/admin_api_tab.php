<!-- TAB: API KEYS -->
<div id="tab-api" class="tab-content hidden animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                <i data-lucide="key" class="w-5 h-5 text-cyan-400"></i>
            </div>
            API ключи
        </h1>
        <p class="text-slate-400 text-sm mt-1 ml-13">Управление ключами доступа к API</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6">
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">Активных ключей</div>
            <div id="api-active-keys" class="text-xl sm:text-2xl font-bold text-emerald-400">0</div>
        </div>
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">Запросов 24ч</div>
            <div id="api-requests-24h" class="text-xl sm:text-2xl font-bold text-blue-400">0</div>
        </div>
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">Запросов 7д</div>
            <div id="api-requests-7d" class="text-xl sm:text-2xl font-bold text-purple-400">0</div>
        </div>
        <div class="glass-panel p-3 sm:p-4 rounded-xl">
            <div class="text-xs sm:text-sm text-slate-400">Время ответа</div>
            <div id="api-avg-time" class="text-xl sm:text-2xl font-bold text-orange-400">0ms</div>
        </div>
    </div>

    <div class="glass-panel p-4 sm:p-6 rounded-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-semibold flex items-center gap-2">
                <i data-lucide="key" class="w-5 h-5 text-yellow-400"></i>
                API Ключи
            </h3>
            <button onclick="showCreateApiKeyModal()" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg text-xs sm:text-sm transition-colors">
                <i data-lucide="plus" class="w-4 h-4 inline"></i> <span class="hidden sm:inline">Создать ключ</span><span class="sm:hidden">Создать</span>
            </button>
        </div>

        <div id="api-keys-list" class="space-y-3">
            <div class="text-center text-slate-400 py-4">Загрузка...</div>
        </div>
    </div>

    <!-- API Key Stats -->
    <div id="api-key-stats" class="glass-panel p-4 sm:p-6 rounded-2xl mt-6 hidden">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base sm:text-lg font-semibold" id="api-stats-title">Статистика ключа</h3>
            <button onclick="$('#api-key-stats').addClass('hidden')" class="text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <h4 class="text-sm font-medium text-slate-400 mb-3">По эндпоинтам</h4>
                <div id="api-stats-endpoints" class="space-y-2 max-h-[300px] overflow-y-auto"></div>
            </div>
            <div>
                <h4 class="text-sm font-medium text-slate-400 mb-3">По статус-кодам</h4>
                <div id="api-stats-status" class="space-y-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Create API Key Modal -->
<div id="create-api-key-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-lg mx-4">
        <h3 class="text-lg font-semibold mb-4">Создать API ключ</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Название</label>
                <input type="text" id="api-key-name" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="Mobile App">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Rate Limit</label>
                    <input type="number" id="api-key-rate-limit" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" value="1000">
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Период</label>
                    <select id="api-key-rate-period" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                        <option value="minute">В минуту</option>
                        <option value="hour" selected>В час</option>
                        <option value="day">В день</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Срок действия (опционально)</label>
                <input type="date" id="api-key-expires" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('create-api-key-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="createApiKey()" class="flex-1 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors">Создать</button>
        </div>
    </div>
</div>

<!-- Show API Key Modal -->
<div id="show-api-key-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-lg mx-4">
        <h3 class="text-lg font-semibold mb-4 text-emerald-400">
            <i data-lucide="check-circle" class="w-5 h-5 inline"></i> API ключ создан
        </h3>
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-4">
            <div class="flex items-center gap-2 text-yellow-300 text-sm mb-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                Сохраните секретный ключ! Он больше не будет показан.
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">API Key</label>
                <div class="flex gap-2">
                    <input type="text" id="new-api-key" class="flex-1 bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white font-mono text-sm" readonly>
                    <button onclick="copyToClipboard('new-api-key')" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Secret Key</label>
                <div class="flex gap-2">
                    <input type="text" id="new-api-secret" class="flex-1 bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white font-mono text-sm" readonly>
                    <button onclick="copyToClipboard('new-api-secret')" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <button onclick="closeModal('show-api-key-modal'); loadApiKeys();" class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors">Готово</button>
        </div>
    </div>
</div>

<script>
function loadApiKeys() {
    $.get('/api/admin/api-keys.php?action=list', function(response) {
        if (response.success) {
            renderApiKeys(response.keys);
        }
    });
    
    $.get('/api/admin/api-keys.php?action=usage', function(response) {
        if (response.success) {
            $('#api-active-keys').text(response.stats.active_keys);
            $('#api-requests-24h').text(response.stats.requests_24h || 0);
            $('#api-requests-7d').text(response.stats.requests_7d || 0);
            $('#api-avg-time').text(Math.round(response.stats.avg_response_time || 0) + 'ms');
        }
    });
}

function renderApiKeys(keys) {
    if (keys.length === 0) {
        $('#api-keys-list').html('<div class="text-center text-slate-400 py-4">Нет API ключей</div>');
        return;
    }
    
    let html = '';
    keys.forEach(key => {
        html += `
            <div class="p-4 bg-slate-800/30 rounded-lg">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">${key.name}</span>
                            <span class="px-2 py-0.5 rounded text-xs ${key.is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300'}">${key.is_active ? 'Активен' : 'Отключен'}</span>
                        </div>
                        <div class="text-sm text-slate-500 mt-1">
                            <span class="font-mono">${key.api_key_masked}</span>
                            <span class="mx-2">•</span>
                            <span>${key.username}</span>
                        </div>
                        <div class="flex gap-4 text-xs text-slate-500 mt-2">
                            <span>Лимит: ${key.rate_limit}/${key.rate_period}</span>
                            <span>Запросов: ${key.total_requests}</span>
                            <span>За 24ч: ${key.requests_24h}</span>
                            ${key.expires_at ? `<span>Истекает: ${key.expires_at}</span>` : ''}
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button onclick="showApiKeyStats(${key.id}, '${key.name}')" class="p-2 text-blue-400 hover:bg-blue-500/20 rounded-lg" title="Статистика">
                            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        </button>
                        <button onclick="toggleApiKey(${key.id})" class="p-2 text-slate-400 hover:bg-slate-700 rounded-lg" title="${key.is_active ? 'Отключить' : 'Включить'}">
                            <i data-lucide="${key.is_active ? 'pause' : 'play'}" class="w-4 h-4"></i>
                        </button>
                        <button onclick="revokeApiKey(${key.id})" class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg" title="Отозвать">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#api-keys-list').html(html);
    lucide.createIcons();
}

function showCreateApiKeyModal() {
    $('#api-key-name').val('');
    $('#api-key-rate-limit').val('1000');
    $('#api-key-rate-period').val('hour');
    $('#api-key-expires').val('');
    $('#create-api-key-modal').removeClass('hidden');
}

function createApiKey() {
    $.post('/api/admin/api-keys.php', {
        action: 'create',
        name: $('#api-key-name').val(),
        rate_limit: $('#api-key-rate-limit').val(),
        rate_period: $('#api-key-rate-period').val(),
        expires_at: $('#api-key-expires').val() || null
    }, function(response) {
        if (response.success) {
            closeModal('create-api-key-modal');
            $('#new-api-key').val(response.api_key);
            $('#new-api-secret').val(response.secret);
            $('#show-api-key-modal').removeClass('hidden');
            lucide.createIcons();
        } else {
            showToast(response.error, 'error');
        }
    });
}

function toggleApiKey(id) {
    $.post('/api/admin/api-keys.php', { action: 'toggle', id: id }, function(response) {
        if (response.success) {
            loadApiKeys();
        }
    });
}

function revokeApiKey(id) {
    if (!confirm('Отозвать этот API ключ? Это действие необратимо.')) return;
    $.post('/api/admin/api-keys.php', { action: 'revoke', id: id }, function(response) {
        if (response.success) {
            loadApiKeys();
            showToast('API ключ отозван', 'success');
        }
    });
}

function showApiKeyStats(id, name) {
    $('#api-stats-title').text('Статистика: ' + name);
    
    $.get('/api/admin/api-keys.php?action=stats&id=' + id, function(response) {
        if (response.success) {
            // Endpoints
            let endpointsHtml = '';
            response.by_endpoint.forEach(e => {
                endpointsHtml += `
                    <div class="flex items-center justify-between p-2 bg-slate-800/30 rounded">
                        <div>
                            <span class="px-1.5 py-0.5 bg-blue-500/20 text-blue-300 rounded text-xs font-mono">${e.method}</span>
                            <span class="text-sm ml-2">${e.endpoint}</span>
                        </div>
                        <div class="text-sm text-slate-400">
                            ${e.count} • ${Math.round(e.avg_time)}ms
                        </div>
                    </div>
                `;
            });
            $('#api-stats-endpoints').html(endpointsHtml || '<div class="text-slate-500 text-sm">Нет данных</div>');
            
            // Status codes
            let statusHtml = '';
            response.by_status.forEach(s => {
                const color = s.status_code < 300 ? 'emerald' : (s.status_code < 400 ? 'yellow' : 'red');
                statusHtml += `
                    <div class="flex items-center justify-between p-2 bg-slate-800/30 rounded">
                        <span class="px-2 py-0.5 bg-${color}-500/20 text-${color}-300 rounded text-sm">${s.status_code}</span>
                        <span class="text-sm text-slate-400">${s.count} запросов</span>
                    </div>
                `;
            });
            $('#api-stats-status').html(statusHtml || '<div class="text-slate-500 text-sm">Нет данных</div>');
            
            $('#api-key-stats').removeClass('hidden');
        }
    });
}

function copyToClipboard(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    document.execCommand('copy');
    showToast('Скопировано', 'success');
}
</script>
