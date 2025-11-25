<!-- TAB: CRON & QUEUE -->
<div id="tab-cron" class="tab-content hidden animate-fade-in">
    <!-- Sub-tabs -->
    <div class="flex gap-2 mb-6">
        <button onclick="switchCronTab('jobs')" id="cron-tab-jobs" class="cron-tab-btn px-4 py-2 rounded-lg text-sm bg-purple-500/20 text-purple-300">Cron задачи</button>
        <button onclick="switchCronTab('queue')" id="cron-tab-queue" class="cron-tab-btn px-4 py-2 rounded-lg text-sm bg-slate-700 text-slate-300">Email очередь</button>
    </div>

    <!-- Cron Jobs -->
    <div id="cron-jobs-container">
        <div class="glass-panel p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-orange-400"></i>
                    Cron задачи
                </h3>
                <button onclick="showCreateJobModal()" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg text-sm transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 inline"></i> Добавить
                </button>
            </div>
            <div id="cron-jobs-list" class="space-y-3">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>

        <!-- Job Logs -->
        <div id="job-logs-panel" class="glass-panel p-6 rounded-2xl mt-6 hidden">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold" id="job-logs-title">История выполнения</h4>
                <button onclick="$('#job-logs-panel').addClass('hidden')" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="job-logs-list" class="space-y-2 max-h-[300px] overflow-y-auto"></div>
        </div>
    </div>

    <!-- Email Queue -->
    <div id="email-queue-container" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="glass-panel p-4 rounded-xl">
                <div class="text-sm text-slate-400">Ожидают</div>
                <div id="queue-pending" class="text-2xl font-bold text-yellow-400">0</div>
            </div>
            <div class="glass-panel p-4 rounded-xl">
                <div class="text-sm text-slate-400">Отправлено</div>
                <div id="queue-sent" class="text-2xl font-bold text-emerald-400">0</div>
            </div>
            <div class="glass-panel p-4 rounded-xl">
                <div class="text-sm text-slate-400">Ошибки</div>
                <div id="queue-failed" class="text-2xl font-bold text-red-400">0</div>
            </div>
            <div class="glass-panel p-4 rounded-xl">
                <div class="text-sm text-slate-400">В обработке</div>
                <div id="queue-processing" class="text-2xl font-bold text-blue-400">0</div>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="mail" class="w-5 h-5 text-blue-400"></i>
                    Очередь email
                </h3>
                <div class="flex gap-2">
                    <select id="queue-status-filter" class="bg-slate-800/50 border border-white/10 rounded-lg px-3 py-1.5 text-sm">
                        <option value="">Все</option>
                        <option value="pending">Ожидают</option>
                        <option value="sent">Отправлено</option>
                        <option value="failed">Ошибки</option>
                    </select>
                    <button onclick="processEmailQueue()" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg text-sm transition-colors">
                        <i data-lucide="play" class="w-4 h-4 inline"></i> Обработать
                    </button>
                    <button onclick="showQueueEmailModal()" class="px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 rounded-lg text-sm transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 inline"></i> Добавить
                    </button>
                </div>
            </div>
            <div id="email-queue-list" class="space-y-2 max-h-[500px] overflow-y-auto">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Job Modal -->
<div id="create-job-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-lg mx-4">
        <h3 class="text-lg font-semibold mb-4">Создать задачу</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Название</label>
                <input type="text" id="job-name" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="cleanup_old_data">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Команда</label>
                <input type="text" id="job-command" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white font-mono" placeholder="php /var/www/html/cron/task.php">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Расписание (cron формат)</label>
                <input type="text" id="job-schedule" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white font-mono" placeholder="0 * * * *">
                <div class="text-xs text-slate-500 mt-1">Примеры: */5 * * * * (каждые 5 мин), 0 3 * * * (в 3:00)</div>
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Описание</label>
                <textarea id="job-description" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" rows="2"></textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('create-job-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="createJob()" class="flex-1 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors">Создать</button>
        </div>
    </div>
</div>

<!-- Queue Email Modal -->
<div id="queue-email-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-lg mx-4">
        <h3 class="text-lg font-semibold mb-4">Добавить в очередь</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Email</label>
                <input type="email" id="queue-to-email" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Тема</label>
                <input type="text" id="queue-subject" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Сообщение</label>
                <textarea id="queue-body" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" rows="4"></textarea>
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Приоритет</label>
                <select id="queue-priority" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                    <option value="0">Обычный</option>
                    <option value="1">Высокий</option>
                    <option value="2">Срочный</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('queue-email-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="queueEmail()" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">Добавить</button>
        </div>
    </div>
</div>

<script>
function switchCronTab(tab) {
    $('.cron-tab-btn').removeClass('bg-purple-500/20 text-purple-300').addClass('bg-slate-700 text-slate-300');
    $('#cron-tab-' + tab).removeClass('bg-slate-700 text-slate-300').addClass('bg-purple-500/20 text-purple-300');
    
    if (tab === 'jobs') {
        $('#cron-jobs-container').removeClass('hidden');
        $('#email-queue-container').addClass('hidden');
        loadCronJobs();
    } else {
        $('#cron-jobs-container').addClass('hidden');
        $('#email-queue-container').removeClass('hidden');
        loadEmailQueue();
    }
}

function loadCronJobs() {
    $.get('/api/admin/cron.php?action=get_jobs', function(response) {
        if (response.success) {
            renderCronJobs(response.jobs);
        }
    });
}

function renderCronJobs(jobs) {
    if (jobs.length === 0) {
        $('#cron-jobs-list').html('<div class="text-center text-slate-400 py-4">Нет задач</div>');
        return;
    }
    
    let html = '';
    jobs.forEach(job => {
        const statusColors = {
            success: 'bg-emerald-500/20 text-emerald-300',
            failed: 'bg-red-500/20 text-red-300',
            running: 'bg-blue-500/20 text-blue-300'
        };
        const statusColor = statusColors[job.last_status] || 'bg-slate-500/20 text-slate-300';
        
        html += `
            <div class="p-4 bg-slate-800/30 rounded-lg">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">${job.name}</span>
                            <span class="px-2 py-0.5 rounded text-xs ${job.is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-500/20 text-slate-400'}">${job.is_active ? 'Активна' : 'Отключена'}</span>
                            ${job.last_status ? `<span class="px-2 py-0.5 rounded text-xs ${statusColor}">${job.last_status}</span>` : ''}
                        </div>
                        <div class="text-sm text-slate-500 mt-1">${job.description || ''}</div>
                        <div class="text-xs text-slate-600 font-mono mt-1">${job.command}</div>
                        <div class="flex gap-4 text-xs text-slate-500 mt-2">
                            <span><i data-lucide="clock" class="w-3 h-3 inline"></i> ${job.schedule}</span>
                            <span>Запусков: ${job.run_count}</span>
                            <span>Ошибок: ${job.fail_count}</span>
                            ${job.last_run_at ? `<span>Последний: ${job.last_run_at}</span>` : ''}
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button onclick="runJob(${job.id})" class="p-2 text-emerald-400 hover:bg-emerald-500/20 rounded-lg" title="Запустить">
                            <i data-lucide="play" class="w-4 h-4"></i>
                        </button>
                        <button onclick="toggleJob(${job.id})" class="p-2 text-slate-400 hover:bg-slate-700 rounded-lg" title="${job.is_active ? 'Отключить' : 'Включить'}">
                            <i data-lucide="${job.is_active ? 'pause' : 'play-circle'}" class="w-4 h-4"></i>
                        </button>
                        <button onclick="showJobLogs(${job.id}, '${job.name}')" class="p-2 text-blue-400 hover:bg-blue-500/20 rounded-lg" title="История">
                            <i data-lucide="history" class="w-4 h-4"></i>
                        </button>
                        <button onclick="deleteJob(${job.id})" class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg" title="Удалить">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#cron-jobs-list').html(html);
    lucide.createIcons();
}

function showCreateJobModal() {
    $('#job-name, #job-command, #job-schedule, #job-description').val('');
    $('#create-job-modal').removeClass('hidden');
}

function createJob() {
    $.post('/api/admin/cron.php', {
        action: 'create_job',
        name: $('#job-name').val(),
        command: $('#job-command').val(),
        schedule: $('#job-schedule').val(),
        description: $('#job-description').val()
    }, function(response) {
        if (response.success) {
            closeModal('create-job-modal');
            loadCronJobs();
            showToast('Задача создана', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function runJob(id) {
    showToast('Запуск задачи...', 'info');
    $.post('/api/admin/cron.php', { action: 'run_job', id: id }, function(response) {
        if (response.success) {
            loadCronJobs();
            showToast('Задача выполнена: ' + response.status, response.status === 'success' ? 'success' : 'error');
        }
    });
}

function toggleJob(id) {
    $.post('/api/admin/cron.php', { action: 'toggle_job', id: id }, function(response) {
        if (response.success) {
            loadCronJobs();
        }
    });
}

function deleteJob(id) {
    if (!confirm('Удалить эту задачу?')) return;
    $.post('/api/admin/cron.php', { action: 'delete_job', id: id }, function(response) {
        if (response.success) {
            loadCronJobs();
            showToast('Задача удалена', 'success');
        }
    });
}

function showJobLogs(jobId, jobName) {
    $('#job-logs-title').text('История: ' + jobName);
    $.get('/api/admin/cron.php?action=get_job_logs&job_id=' + jobId, function(response) {
        if (response.success) {
            let html = '';
            response.logs.forEach(log => {
                html += `
                    <div class="p-2 rounded ${log.status === 'success' ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-red-500/10 border border-red-500/20'}">
                        <div class="flex justify-between text-xs">
                            <span>${log.started_at}</span>
                            <span>${log.duration_ms}ms</span>
                        </div>
                        ${log.output ? `<div class="text-xs text-slate-400 mt-1 font-mono">${log.output.substring(0, 200)}</div>` : ''}
                    </div>
                `;
            });
            $('#job-logs-list').html(html || '<div class="text-slate-400 text-sm">Нет записей</div>');
            $('#job-logs-panel').removeClass('hidden');
        }
    });
}

// Email Queue
function loadEmailQueue() {
    const status = $('#queue-status-filter').val();
    $.get('/api/admin/cron.php?action=get_queue&status=' + status, function(response) {
        if (response.success) {
            renderEmailQueue(response.queue);
        }
    });
    
    $.get('/api/admin/cron.php?action=queue_stats', function(response) {
        if (response.success) {
            response.by_status.forEach(s => {
                $('#queue-' + s.status).text(s.count);
            });
        }
    });
}

function renderEmailQueue(queue) {
    if (queue.length === 0) {
        $('#email-queue-list').html('<div class="text-center text-slate-400 py-4">Очередь пуста</div>');
        return;
    }
    
    let html = '';
    queue.forEach(item => {
        const statusColors = {
            pending: 'bg-yellow-500/20 text-yellow-300',
            processing: 'bg-blue-500/20 text-blue-300',
            sent: 'bg-emerald-500/20 text-emerald-300',
            failed: 'bg-red-500/20 text-red-300'
        };
        
        html += `
            <div class="flex items-center justify-between p-3 bg-slate-800/30 rounded-lg">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-medium truncate">${item.to_email}</span>
                        <span class="px-2 py-0.5 rounded text-xs ${statusColors[item.status]}">${item.status}</span>
                    </div>
                    <div class="text-sm text-slate-500 truncate">${item.subject}</div>
                    <div class="text-xs text-slate-600">${item.created_at} • Попыток: ${item.attempts}/${item.max_attempts}</div>
                </div>
                <button onclick="deleteQueuedEmail(${item.id})" class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        `;
    });
    
    $('#email-queue-list').html(html);
    lucide.createIcons();
}

function processEmailQueue() {
    showToast('Обработка очереди...', 'info');
    $.post('/api/admin/cron.php', { action: 'process_queue', limit: 10 }, function(response) {
        if (response.success) {
            loadEmailQueue();
            showToast(`Отправлено: ${response.sent}, Ошибок: ${response.failed}`, 'success');
        }
    });
}

function showQueueEmailModal() {
    $('#queue-to-email, #queue-subject, #queue-body').val('');
    $('#queue-priority').val('0');
    $('#queue-email-modal').removeClass('hidden');
}

function queueEmail() {
    $.post('/api/admin/cron.php', {
        action: 'queue_email',
        to_email: $('#queue-to-email').val(),
        subject: $('#queue-subject').val(),
        body: $('#queue-body').val(),
        priority: $('#queue-priority').val()
    }, function(response) {
        if (response.success) {
            closeModal('queue-email-modal');
            loadEmailQueue();
            showToast('Email добавлен в очередь', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function deleteQueuedEmail(id) {
    $.post('/api/admin/cron.php', { action: 'delete_queued', id: id }, function(response) {
        if (response.success) {
            loadEmailQueue();
        }
    });
}

$('#queue-status-filter').on('change', loadEmailQueue);
</script>
