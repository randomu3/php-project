<!-- TAB: SYSTEM LOGS -->
<div id="tab-logs" class="tab-content hidden animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <div class="glass-panel p-4 rounded-xl">
            <div class="text-sm text-slate-400">Critical</div>
            <div id="logs-critical" class="text-2xl font-bold text-red-400">0</div>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <div class="text-sm text-slate-400">Errors</div>
            <div id="logs-error" class="text-2xl font-bold text-orange-400">0</div>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <div class="text-sm text-slate-400">Warnings</div>
            <div id="logs-warning" class="text-2xl font-bold text-yellow-400">0</div>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <div class="text-sm text-slate-400">Info</div>
            <div id="logs-info" class="text-2xl font-bold text-blue-400">0</div>
        </div>
    </div>

    <div class="glass-panel p-6 rounded-2xl">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-semibold flex items-center gap-2">
                <i data-lucide="scroll-text" class="w-5 h-5 text-orange-400"></i>
                Системные логи
            </h3>
            <div class="flex gap-2">
                <select id="log-level-filter" class="bg-slate-800/50 border border-white/10 rounded-lg px-3 py-1.5 text-sm">
                    <option value="">Все уровни</option>
                    <option value="critical">Critical</option>
                    <option value="error">Error</option>
                    <option value="warning">Warning</option>
                    <option value="info">Info</option>
                    <option value="debug">Debug</option>
                </select>
                <button onclick="exportLogs()" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg text-sm transition-colors">
                    <i data-lucide="download" class="w-4 h-4 inline"></i> Экспорт
                </button>
                <button onclick="showClearLogsModal()" class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-300 rounded-lg text-sm transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4 inline"></i> Очистить
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-2 mb-4">
            <button onclick="switchLogTab('db')" id="log-tab-db" class="log-tab-btn px-4 py-2 rounded-lg text-sm bg-purple-500/20 text-purple-300">БД логи</button>
            <button onclick="switchLogTab('php')" id="log-tab-php" class="log-tab-btn px-4 py-2 rounded-lg text-sm bg-slate-700 text-slate-300">PHP ошибки</button>
        </div>

        <!-- DB Logs -->
        <div id="db-logs-container">
            <div id="logs-list" class="space-y-2 max-h-[600px] overflow-y-auto">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>

        <!-- PHP Errors -->
        <div id="php-logs-container" class="hidden">
            <div id="php-errors-list" class="space-y-2 max-h-[600px] overflow-y-auto font-mono text-sm">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div id="clear-logs-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Очистить логи</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Удалить логи старше</label>
                <select id="clear-days" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                    <option value="7">7 дней</option>
                    <option value="14">14 дней</option>
                    <option value="30" selected>30 дней</option>
                    <option value="60">60 дней</option>
                    <option value="90">90 дней</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Уровень (опционально)</label>
                <select id="clear-level" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                    <option value="">Все уровни</option>
                    <option value="debug">Только debug</option>
                    <option value="info">Только info</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('clear-logs-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="clearLogs()" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg transition-colors">Очистить</button>
        </div>
    </div>
</div>

<script>
let currentLogTab = 'db';

function loadLogs() {
    const level = $('#log-level-filter').val();
    
    $.get('/api/admin/logs.php?action=list&level=' + level + '&limit=200', function(response) {
        if (response.success) {
            renderLogs(response.logs);
        }
    });
    
    // Load stats
    $.get('/api/admin/logs.php?action=stats', function(response) {
        if (response.success) {
            response.by_level.forEach(item => {
                $('#logs-' + item.level).text(item.count);
            });
        }
    });
}

function renderLogs(logs) {
    if (logs.length === 0) {
        $('#logs-list').html('<div class="text-center text-slate-400 py-4">Нет логов</div>');
        return;
    }
    
    let html = '';
    logs.forEach(log => {
        const levelColors = {
            critical: 'bg-red-500/20 text-red-300 border-red-500/30',
            error: 'bg-orange-500/20 text-orange-300 border-orange-500/30',
            warning: 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
            info: 'bg-blue-500/20 text-blue-300 border-blue-500/30',
            debug: 'bg-slate-500/20 text-slate-300 border-slate-500/30'
        };
        const color = levelColors[log.level] || levelColors.info;
        
        html += `
            <div class="p-3 rounded-lg border ${color}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-xs font-medium uppercase">${log.level}</span>
                            <span class="text-xs text-slate-500">${log.channel}</span>
                            <span class="text-xs text-slate-500">${log.created_at}</span>
                        </div>
                        <div class="text-sm break-words">${escapeHtml(log.message)}</div>
                        ${log.file ? `<div class="text-xs text-slate-500 mt-1">${log.file}:${log.line}</div>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#logs-list').html(html);
}

function loadPhpErrors() {
    $.get('/api/admin/logs.php?action=php_errors&lines=100', function(response) {
        if (response.success) {
            renderPhpErrors(response.errors);
        } else {
            $('#php-errors-list').html('<div class="text-center text-slate-400 py-4">' + response.error + '</div>');
        }
    });
}

function renderPhpErrors(errors) {
    if (errors.length === 0) {
        $('#php-errors-list').html('<div class="text-center text-slate-400 py-4">Нет ошибок</div>');
        return;
    }
    
    let html = '';
    errors.forEach(err => {
        const levelColors = {
            critical: 'border-red-500/30 bg-red-500/10',
            error: 'border-orange-500/30 bg-orange-500/10',
            warning: 'border-yellow-500/30 bg-yellow-500/10',
            info: 'border-blue-500/30 bg-blue-500/10'
        };
        const color = levelColors[err.level] || levelColors.info;
        
        html += `
            <div class="p-2 rounded border ${color} text-xs">
                <span class="text-slate-500">${err.date || ''}</span>
                <div class="break-all">${escapeHtml(err.message)}</div>
            </div>
        `;
    });
    
    $('#php-errors-list').html(html);
}

function switchLogTab(tab) {
    currentLogTab = tab;
    $('.log-tab-btn').removeClass('bg-purple-500/20 text-purple-300').addClass('bg-slate-700 text-slate-300');
    $('#log-tab-' + tab).removeClass('bg-slate-700 text-slate-300').addClass('bg-purple-500/20 text-purple-300');
    
    if (tab === 'db') {
        $('#db-logs-container').removeClass('hidden');
        $('#php-logs-container').addClass('hidden');
        loadLogs();
    } else {
        $('#db-logs-container').addClass('hidden');
        $('#php-logs-container').removeClass('hidden');
        loadPhpErrors();
    }
}

function showClearLogsModal() {
    $('#clear-logs-modal').removeClass('hidden');
}

function clearLogs() {
    $.post('/api/admin/logs.php', {
        action: 'clear',
        days: $('#clear-days').val(),
        level: $('#clear-level').val()
    }, function(response) {
        if (response.success) {
            closeModal('clear-logs-modal');
            loadLogs();
            showToast('Удалено ' + response.deleted + ' записей', 'success');
        }
    });
}

function exportLogs() {
    const level = $('#log-level-filter').val();
    window.location.href = '/api/admin/logs.php?action=export&level=' + level + '&days=7';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

$('#log-level-filter').on('change', function() {
    loadLogs();
});
</script>
