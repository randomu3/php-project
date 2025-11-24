<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Мой сайт' ?></title>
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://unpkg.com">
    <link rel="preconnect" href="https://code.jquery.com">
    
    <!-- CDN preconnect (если включен) -->
    <?= CDN::preconnectTags() ?>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS with cache busting (через CDN если включен) -->
    <link rel="stylesheet" href="<?= CDN::css('assets/css/style.css?v=' . ASSET_VERSION) ?>">
    <link rel="stylesheet" href="<?= CDN::css('assets/css/loader.css?v=' . ASSET_VERSION) ?>">
    
    <!-- jQuery (загружаем ПЕРВЫМ, БЕЗ defer) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Tailwind CSS (после jQuery) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Loader Script (inline для быстрой загрузки) -->
    <script src="<?= CDN::js('assets/js/loader.js?v=' . ASSET_VERSION) ?>"></script>
    
    <!-- Lazy Loading для изображений -->
    <script src="<?= CDN::js('assets/js/lazy-load.js?v=' . ASSET_VERSION) ?>" defer></script>
    
    <!-- Service Worker для PWA -->
    <script src="<?= CDN::js('assets/js/sw-register.js?v=' . ASSET_VERSION) ?>" defer></script>
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#667eea">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AuraUI">
    
    <!-- Дополнительные стили страницы -->
    <?php if (isset($additionalCSS)): ?>
        <style><?= $additionalCSS ?></style>
    <?php endif; ?>
    
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            overflow-y: scroll; /* Всегда показывать скроллбар чтобы избежать прыжков */
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen relative selection:bg-purple-500/30">

    <!-- Page Loader (показывается только при первой загрузке) -->
    <?php if (!isset($disableLoader) || !$disableLoader): ?>
    <div id="page-loader">
        <!-- Анимированные частицы на фоне -->
        <div class="loader-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        
        <!-- Логотип с пульсацией -->
        <div class="loader-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>
        </div>
        
        <!-- Анимированные точки -->
        <div class="loader-dots">
            <div class="loader-dot"></div>
            <div class="loader-dot"></div>
            <div class="loader-dot"></div>
        </div>
        
        <!-- Текст загрузки -->
        <div class="loader-text">Загрузка</div>
        
        <!-- Прогресс бар -->
        <div class="loader-progress">
            <div class="loader-progress-bar"></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Фоновые эффекты -->
    <div class="fixed top-0 left-0 w-[800px] h-[800px] bg-purple-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>
    <div class="fixed bottom-0 right-0 w-[600px] h-[600px] bg-blue-600/10 blur-[120px] rounded-full -z-10 pointer-events-none"></div>

    <!-- Навигация -->
    <nav class="fixed w-full z-50 top-0 start-0 border-b border-white/5 bg-slate-950/70 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/20 group-hover:scale-105 transition-transform">
                        <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight">AuraUI</span>
                </a>
                <div class="flex items-center gap-2">
                    <?php if (isLoggedIn()): ?>
                        <!-- Колокольчик уведомлений -->
                        <?php require __DIR__ . '/../views/partials/notifications_bell.php'; ?>
                        
                        <a href="/profile" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-2 p-2 rounded-lg hover:bg-white/5">
                            <i data-lucide="user-circle" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Профиль</span>
                        </a>
                        <?php if (isAdmin()): ?>
                            <a href="/admin" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-2 p-2 rounded-lg hover:bg-white/5">
                                <i data-lucide="shield" class="w-4 h-4"></i>
                                <span class="hidden sm:inline">Админ</span>
                            </a>
                        <?php endif; ?>
                        <a href="/logout" class="text-sm text-slate-400 hover:text-red-400 transition-colors flex items-center gap-2 p-2 rounded-lg hover:bg-white/5">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Выйти</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
