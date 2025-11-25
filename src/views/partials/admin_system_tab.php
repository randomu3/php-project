<!-- TAB: SYSTEM MONITORING -->
<div id="tab-system" class="tab-content hidden animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                <i data-lucide="server" class="w-5 h-5 text-indigo-400"></i>
            </div>
            Мониторинг системы
        </h1>
        <p class="text-slate-400 text-sm mt-1 ml-13">Состояние сервера, ресурсы и производительность</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="glass-panel p-4 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="server" class="w-5 h-5 text-blue-400"></i>
                <span class="text-sm text-slate-400">Uptime</span>
            </div>
            <div id="sys-uptime" class="text-xl font-bold">-</div>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="cpu" class="w-5 h-5 text-purple-400"></i>
                <span class="text-sm text-slate-400">Load Average</span>
            </div>
            <div id="sys-load" class="text-xl font-bold">-</div>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="memory-stick" class="w-5 h-5 text-emerald-400"></i>
                <span class="text-sm text-slate-400">Memory</span>
            </div>
            <div id="sys-memory" class="text-xl font-bold">-</div>
            <div id="sys-memory-bar" class="h-1.5 bg-slate-700 rounded-full mt-2 overflow-hidden">
                <div class="h-full bg-emerald-500 transition-all" style="width: 0%"></div>
            </div>
        </div>
        <div class="glass-panel p-4 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="hard-drive" class="w-5 h-5 text-orange-400"></i>
                <span class="text-sm text-slate-400">Disk</span>
            </div>
            <div id="sys-disk" class="text-xl font-bold">-</div>
            <div id="sys-disk-bar" class="h-1.5 bg-slate-700 rounded-full mt-2 overflow-hidden">
                <div class="h-full bg-orange-500 transition-all" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Services Status -->
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-lg font-semibold flex items-center gap-2 mb-6">
                <i data-lucide="activity" class="w-5 h-5 text-emerald-400"></i>
                Статус сервисов
            </h3>
            <div id="services-list" class="space-y-4">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>

        <!-- PHP Info -->
        <div class="glass-panel p-6 rounded-2xl">
            <h3 class="text-lg font-semibold flex items-center gap-2 mb-6">
                <i data-lucide="code" class="w-5 h-5 text-blue-400"></i>
                PHP Информация
            </h3>
            <div id="php-info" class="space-y-3 text-sm">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>
    </div>

    <!-- Server Info -->
    <div class="glass-panel p-6 rounded-2xl mt-6">
        <h3 class="text-lg font-semibold flex items-center gap-2 mb-6">
            <i data-lucide="info" class="w-5 h-5 text-slate-400"></i>
            Информация о сервере
        </h3>
        <div id="server-info" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <div class="text-center text-slate-400 py-4">Загрузка...</div>
        </div>
    </div>

    <!-- PHP Extensions -->
    <div class="glass-panel p-6 rounded-2xl mt-6">
        <h3 class="text-lg font-semibold flex items-center gap-2 mb-6">
            <i data-lucide="puzzle" class="w-5 h-5 text-purple-400"></i>
            PHP Расширения
        </h3>
        <div id="php-extensions" class="flex flex-wrap gap-2">
            <div class="text-center text-slate-400 py-4 w-full">Загрузка...</div>
        </div>
    </div>
</div>

<script>
function loadSystemStatus() {
    $.get('/api/admin/system.php?action=status', function(response) {
        if (response.success) {
            const s = response.status;
            $('#sys-uptime').text(s.uptime);
            $('#sys-load').text(s.load_average['1min'] + ' / ' + s.load_average['5min'] + ' / ' + s.load_average['15min']);
            $('#sys-memory').text(s.memory.used + ' / ' + s.memory.total);
            $('#sys-memory-bar div').css('width', s.memory.percent + '%');
            
            // Server info
            let infoHtml = `
                <div class="bg-slate-800/30 rounded-lg p-3">
                    <div class="text-slate-400">Время сервера</div>
                    <div class="font-medium">${s.server_time}</div>
                </div>
                <div class="bg-slate-800/30 rounded-lg p-3">
                    <div class="text-slate-400">Timezone</div>
                    <div class="font-medium">${s.timezone}</div>
                </div>
                <div class="bg-slate-800/30 rounded-lg p-3">
                    <div class="text-slate-400">PHP Version</div>
                    <div class="font-medium">${s.php_version}</div>
                </div>
                <div class="bg-slate-800/30 rounded-lg p-3">
                    <div class="text-slate-400">OS</div>
                    <div class="font-medium">${s.os}</div>
                </div>
                <div class="bg-slate-800/30 rounded-lg p-3">
                    <div class="text-slate-400">Web Server</div>
                    <div class="font-medium">${s.server_software}</div>
                </div>
                <div class="bg-slate-800/30 rounded-lg p-3">
                    <div class="text-slate-400">Document Root</div>
                    <div class="font-medium text-xs">${s.document_root}</div>
                </div>
            `;
            $('#server-info').html(infoHtml);
        }
    });
    
    // Disk
    $.get('/api/admin/system.php?action=disk', function(response) {
        if (response.success) {
            const d = response.disk;
            $('#sys-disk').text(d.used_formatted + ' / ' + d.total_formatted);
            $('#sys-disk-bar div').css('width', d.used_percent + '%');
        }
    });
    
    // Services
    $.get('/api/admin/system.php?action=services', function(response) {
        if (response.success) {
            renderServices(response.services);
        }
    });
    
    // PHP Info
    $.get('/api/admin/system.php?action=php_info', function(response) {
        if (response.success) {
            renderPhpInfo(response.info);
        }
    });
}

function renderServices(services) {
    let html = '';
    
    for (let name in services) {
        const s = services[name];
        const statusColor = s.status === 'running' ? 'bg-emerald-500' : (s.status === 'error' ? 'bg-red-500' : 'bg-slate-500');
        const statusText = s.status === 'running' ? 'Работает' : (s.status === 'error' ? 'Ошибка' : 'Остановлен');
        
        html += `
            <div class="flex items-center justify-between p-3 bg-slate-800/30 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full ${statusColor}"></div>
                    <div>
                        <div class="font-medium capitalize">${name}</div>
                        <div class="text-xs text-slate-500">${s.version || ''} ${s.message || ''}</div>
                    </div>
                </div>
                <span class="px-2 py-1 rounded text-xs ${s.status === 'running' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300'}">
                    ${statusText}
                </span>
            </div>
        `;
        
        // Extra info for Redis
        if (name === 'redis' && s.memory) {
            html = html.replace('</div></div></div>', `<div class="text-xs text-slate-500 mt-1">Memory: ${s.memory}, Clients: ${s.clients}</div></div></div></div>`);
        }
    }
    
    $('#services-list').html(html);
}

function renderPhpInfo(info) {
    let html = `
        <div class="flex justify-between">
            <span class="text-slate-400">Version</span>
            <span>${info.version}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-400">SAPI</span>
            <span>${info.sapi}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-400">Memory Limit</span>
            <span>${info.memory_limit}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-400">Max Execution Time</span>
            <span>${info.max_execution_time}s</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-400">Upload Max Size</span>
            <span>${info.upload_max_filesize}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-400">Post Max Size</span>
            <span>${info.post_max_size}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-slate-400">OPcache</span>
            <span class="${info.opcache_enabled ? 'text-emerald-400' : 'text-red-400'}">${info.opcache_enabled ? 'Enabled' : 'Disabled'}</span>
        </div>
    `;
    $('#php-info').html(html);
    
    // Extensions
    let extHtml = '';
    info.extensions.sort().forEach(ext => {
        extHtml += `<span class="px-2 py-1 bg-slate-800 rounded text-xs">${ext}</span>`;
    });
    $('#php-extensions').html(extHtml);
}
</script>
