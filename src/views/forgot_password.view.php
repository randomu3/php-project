<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля | AuraUI</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        /* Fix for Chrome autocomplete background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #0f172a inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex flex-col relative overflow-hidden">

    <!-- Фоновые эффекты -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-600/20 blur-[120px] rounded-full -z-10 pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/20 blur-[120px] rounded-full -z-10 pointer-events-none"></div>

    <!-- Навигация -->
    <nav class="absolute top-0 w-full p-6 flex justify-between items-center z-10">
        <a href="/" class="flex items-center space-x-2 group">
            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                <i data-lucide="zap" class="w-5 h-5 text-white"></i>
            </div>
            <span class="font-bold text-xl tracking-wide">AuraUI</span>
        </a>
        <a href="/login" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Вход
        </a>
    </nav>

    <!-- Основной контент -->
    <main class="flex-grow flex items-center justify-center px-4 py-20">
        
        <div class="w-full max-w-md glass-panel rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
            
            <!-- Декоративная линия сверху -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-800/50 mb-4 border border-slate-700">
                    <i data-lucide="key-round" class="w-6 h-6 text-purple-400"></i>
                </div>
                <h1 class="text-2xl font-bold mb-2">Забыли пароль?</h1>
                <p class="text-slate-400 text-sm">Введите email, привязанный к аккаунту, и мы отправим ссылку для сброса.</p>
            </div>

            <!-- Предупреждение для авторизованных -->
            <?php if (isLoggedIn()): ?>
                <div class="mb-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm text-blue-200">Вы авторизованы. После сброса пароля вам нужно будет войти заново.</p>
                </div>
            <?php endif; ?>

            <!-- Блок Ошибки -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3 animate-pulse">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm text-red-200"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <!-- Блок Успеха -->
            <?php if ($success): ?>
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-start gap-3">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm text-green-200"><?= htmlspecialchars($success) ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                <!-- Поле Email -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-300 ml-1">Ваш Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-slate-500 group-focus-within:text-purple-400 transition-colors"></i>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            required 
                            class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent block w-full pl-12 p-3.5 transition-all placeholder-slate-600 hover:border-slate-600" 
                            placeholder="name@example.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        >
                    </div>
                </div>

                <!-- Кнопка отправки -->
                <button type="submit" class="w-full text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 focus:ring-4 focus:outline-none focus:ring-blue-800 font-medium rounded-xl text-sm px-5 py-3.5 text-center shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 mt-2">
                    Отправить ссылку
                </button>
            </form>

            <div class="mt-8 text-center border-t border-slate-800 pt-6">
                <p class="text-sm text-slate-400">
                    Вспомнили пароль? 
                    <a href="/login" class="text-purple-400 hover:text-purple-300 font-medium transition-colors">Войти</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-slate-600 text-xs">
        &copy; <?= date('Y') ?> AuraUI Inc. Все права защищены.
    </footer>

    <script src="/assets/js/cookie-consent.js?v=<?= ASSET_VERSION ?>"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
