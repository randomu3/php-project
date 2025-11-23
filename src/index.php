<?php
// --- ТВОЙ PHP БЭКЕНД ---
require_once 'config.php';
requireLogin();

// Получаем данные пользователя для отображения
$db = getDB();
$stmt = $db->prepare("SELECT username, avatar FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch();

$username = htmlspecialchars($currentUser['username']);
$avatar = $currentUser['avatar'];
// Первая буква для аватара
$initial = mb_substr($username, 0, 1);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная панель | AuraUI</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .glass-nav {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            border-color: rgba(168, 85, 247, 0.3); /* Purple tint on hover */
            background: rgba(30, 41, 59, 0.6);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen relative selection:bg-purple-500/30">

    <!-- Фоновые пятна -->
    <div class="fixed top-0 left-1/4 w-[600px] h-[600px] bg-purple-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>
    <div class="fixed bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>

    <!-- Навигация -->
    <nav class="glass-nav fixed w-full z-50 top-0 start-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Логотип -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/20">
                        <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight hidden sm:block">AuraUI</span>
                </div>

                <!-- Правая часть (Профиль и выход) -->
                <div class="flex items-center gap-4">
                    <a href="/profile" class="flex items-center gap-3 px-3 py-1.5 rounded-full bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                        <?php if (!empty($avatar) && file_exists('uploads/avatars/' . $avatar)): ?>
                            <img src="/uploads/avatars/<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-sm font-bold uppercase">
                                <?= $initial ?>
                            </div>
                        <?php endif; ?>
                        <span class="text-sm font-medium pr-2 hidden sm:block"><?= $username ?></span>
                    </a>

                    <a href="/logout" class="group flex items-center gap-2 text-sm text-slate-400 hover:text-red-400 transition-colors p-2 rounded-lg hover:bg-white/5">
                        <i data-lucide="log-out" class="w-5 h-5 transition-transform group-hover:-translate-x-1"></i>
                        <span class="hidden sm:inline">Выйти</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="pt-28 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Заголовок страницы -->
        <div class="mb-10 animate-fade-in-up">
            <h1 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white via-purple-100 to-slate-400 mb-2">
                Добро пожаловать домой!
            </h1>
            <p class="text-slate-400">Это ваша защищенная панель управления.</p>
        </div>

        <!-- Сетка карточек -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Основная карточка (Широкая) -->
            <div class="glass-card rounded-2xl p-8 md:col-span-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i data-lucide="shield-check" class="w-32 h-32 text-purple-500"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center mb-6">
                        <i data-lucide="lock" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-2xl font-bold mb-4">Безопасный доступ</h2>
                    <p class="text-slate-400 leading-relaxed max-w-lg mb-6">
                        Вы успешно авторизовались в системе. Этот контент доступен только пользователям с активной сессией. 
                        В реальном проекте здесь может быть ваша статистика, настройки или рабочая область.
                    </p>
                    <a href="/profile" class="inline-block text-white bg-purple-600 hover:bg-purple-500 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-lg shadow-purple-500/20">
                        Управление профилем
                    </a>
                </div>
            </div>

            <!-- Боковая карточка (Статус) -->
            <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="activity" class="w-5 h-5 text-green-400"></i>
                        Статус системы
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">Сессия активна</span>
                            <span class="text-green-400 font-mono">ONLINE</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">IP адрес</span>
                            <span class="text-slate-200 font-mono"><?= $_SERVER['REMOTE_ADDR'] ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">Версия PHP</span>
                            <span class="text-slate-200 font-mono"><?= phpversion() ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-white/5">
                    <div class="text-xs text-slate-500">Последний вход:</div>
                    <div class="text-sm text-slate-300 font-mono mt-1">
                        <?= date('d.m.Y H:i') ?>
                    </div>
                </div>
            </div>

            <!-- Дополнительные карточки для примера (Placeholder grid) -->
            <div class="glass-card rounded-2xl p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-semibold">Документы</h4>
                    <p class="text-xs text-slate-400 mt-1">3 новых файла</p>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-pink-500/10 text-pink-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-semibold">Уведомления</h4>
                    <p class="text-xs text-slate-400 mt-1">Все прочитано</p>
                </div>
            </div>

            <a href="/profile" class="glass-card rounded-2xl p-6 flex items-center gap-4 cursor-pointer">
                <div class="w-12 h-12 rounded-full bg-orange-500/10 text-orange-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-semibold">Профиль</h4>
                    <p class="text-xs text-slate-400 mt-1">Настройки аккаунта</p>
                </div>
            </a>

        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>