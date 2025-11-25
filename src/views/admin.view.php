<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-slate-950 text-white min-h-screen relative selection:bg-purple-500/30 pb-20">
    <div class="fixed top-0 right-0 w-[800px] h-[800px] bg-purple-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 w-[600px] h-[600px] bg-blue-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>

    <header class="fixed top-0 w-full z-40 bg-slate-950/80 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/20">
                    <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                </div>
                <span class="font-bold text-xl tracking-tight">Aura Admin</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="/" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Панель пользователя</span>
                </a>
                <a href="/profile" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-2">
                    <i data-lucide="user-circle" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Профиль</span>
                </a>
                <a href="?refresh=1" class="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors" title="Обновить данные">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24">
        <div class="flex flex-wrap gap-2 mb-8">
            <button onclick="switchTab('database')" id="btn-database" class="tab-btn active flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium border border-white/5 bg-white/5 hover:bg-white/10 transition-all">
                <i data-lucide="database" class="w-4 h-4"></i> База данных
            </button>
            <button onclick="switchTab('analytics')" id="btn-analytics" class="tab-btn flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium border border-white/5 bg-white/5 hover:bg-white/10 transition-all">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Аналитика
            </button>
            <button onclick="switchTab('security')" id="btn-security" class="tab-btn flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium border border-white/5 bg-white/5 hover:bg-white/10 transition-all">
                <i data-lucide="shield" class="w-4 h-4"></i> Безопасность
            </button>
            <button onclick="switchTab('email')" id="btn-email" class="tab-btn flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium border border-white/5 bg-white/5 hover:bg-white/10 transition-all">
                <i data-lucide="send" class="w-4 h-4"></i> Рассылка
            </button>
        </div>

        <!-- TAB 1: DATABASE & STATS -->
        <div id="tab-database" class="tab-content animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i data-lucide="users" class="w-24 h-24 text-blue-500"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Всего пользователей</p>
                    <h3 class="text-4xl font-bold text-white"><?= $stats['total_users'] ?></h3>
                </div>
                <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i data-lucide="key" class="w-24 h-24 text-green-500"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Активные токены</p>
                    <h3 class="text-4xl font-bold text-emerald-400"><?= $stats['active_tokens'] ?></h3>
                </div>
                <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i data-lucide="check-circle" class="w-24 h-24 text-purple-500"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Использовано токенов</p>
                    <h3 class="text-4xl font-bold text-purple-400"><?= $stats['used_tokens'] ?></h3>
                </div>
            </div>

            <?php require __DIR__ . '/partials/users_table.php'; ?>
            <?php require __DIR__ . '/partials/tokens_table.php'; ?>
        </div>

        <!-- TAB 2: ANALYTICS -->
        <?php require __DIR__ . '/partials/admin_analytics_tab.php'; ?>

        <!-- TAB 3: SECURITY -->
        <?php require __DIR__ . '/partials/admin_security_tab.php'; ?>

        <!-- TAB 3: EMAIL SENDER -->
        <div id="tab-email" class="tab-content hidden animate-fade-in">
            <?php require __DIR__ . '/partials/email_sender.php'; ?>
        </div>
    </main>

    <script src="/assets/js/app.js"></script>
    <script>
        lucide.createIcons();
        
        // Переключение вкладок
        function switchTab(tab) {
            // Скрываем все вкладки
            $('.tab-content').addClass('hidden');
            // Показываем нужную
            $('#tab-' + tab).removeClass('hidden');
            // Убираем активный класс со всех кнопок
            $('.tab-btn').removeClass('active bg-purple-500/20 border-purple-500/50 text-purple-300');
            // Добавляем активный класс нужной кнопке
            $('#btn-' + tab).addClass('active bg-purple-500/20 border-purple-500/50 text-purple-300');
            
            // Загружаем данные для вкладки
            if (tab === 'analytics') loadAnalytics();
            if (tab === 'security') { loadActivityLog(); }
        }

        // Очистка URL от GET параметров после показа уведомления
        if (window.location.search.includes('email_sent') || 
            window.location.search.includes('newsletter_sent') || 
            window.location.search.includes('email_error') || 
            window.location.search.includes('newsletter_error')) {
            
            // Автоматически скрываем уведомление через 5 секунд
            setTimeout(function() {
                // Очищаем URL без перезагрузки страницы
                const url = new URL(window.location);
                url.searchParams.delete('email_sent');
                url.searchParams.delete('newsletter_sent');
                url.searchParams.delete('email_error');
                url.searchParams.delete('newsletter_error');
                url.searchParams.delete('message');
                window.history.replaceState({}, document.title, url.pathname + url.search);
                
                // Плавно скрываем уведомление
                const notifications = document.querySelectorAll('.animate-fade-in');
                notifications.forEach(function(notif) {
                    notif.style.transition = 'opacity 0.5s';
                    notif.style.opacity = '0';
                    setTimeout(function() {
                        notif.remove();
                    }, 500);
                });
            }, 5000);
        }
    </script>
    <script src="/assets/js/cookie-consent.js?v=<?= ASSET_VERSION ?>"></script>
</body>
</html>
