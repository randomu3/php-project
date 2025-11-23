<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сброс пароля | AuraUI</title>
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
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 via-blue-500 to-purple-500"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-800/50 mb-4 border border-slate-700">
                    <i data-lucide="shield-check" class="w-6 h-6 text-green-400"></i>
                </div>
                <h1 class="text-2xl font-bold mb-2">Новый пароль</h1>
                <p class="text-slate-400 text-sm">Создайте новый надежный пароль для вашего аккаунта</p>
            </div>

            <!-- Блок Успеха -->
            <?php if ($success): ?>
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-start gap-3">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-green-200"><?= htmlspecialchars($success) ?></p>
                        <a href="/login" class="text-xs text-green-400 underline mt-1 block hover:text-green-300">Перейти ко входу</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Блок Ошибки -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3 animate-pulse">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-red-200"><?= htmlspecialchars($error) ?></p>
                        <?php if (strpos($error, 'недействительна') !== false || strpos($error, 'истекла') !== false): ?>
                            <a href="/forgot_password" class="text-xs text-red-400 underline mt-1 block hover:text-red-300">Запросить новую ссылку</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($valid_token && !$success): ?>
                <form method="POST" class="space-y-5">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <!-- Поле Пароль -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-300 ml-1">Новый пароль</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-slate-500 group-focus-within:text-green-400 transition-colors"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                required 
                                minlength="8"
                                class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent block w-full pl-12 p-3.5 transition-all placeholder-slate-600 hover:border-slate-600" 
                                placeholder="Минимум 8 символов"
                            >
                        </div>
                    </div>

                    <!-- Поле Подтверждение пароля -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-300 ml-1">Подтвердите пароль</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="shield-check" class="w-5 h-5 text-slate-500 group-focus-within:text-green-400 transition-colors"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password_confirm" 
                                required 
                                minlength="8"
                                class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent block w-full pl-12 p-3.5 transition-all placeholder-slate-600 hover:border-slate-600" 
                                placeholder="Повторите пароль"
                            >
                        </div>
                    </div>

                    <!-- Подсказка -->
                    <div class="p-3 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-xs text-blue-200">Пароль должен содержать минимум 8 символов</p>
                    </div>

                    <!-- Кнопка сохранения -->
                    <button type="submit" class="w-full text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-500 hover:to-blue-500 focus:ring-4 focus:outline-none focus:ring-green-800 font-medium rounded-xl text-sm px-5 py-3.5 text-center shadow-lg shadow-green-500/25 transition-all transform hover:-translate-y-0.5 mt-2">
                        Сохранить новый пароль
                    </button>
                </form>
            <?php else: ?>
                <div class="text-center py-4">
                    <a href="/login" class="text-sm text-purple-400 hover:text-purple-300 font-medium transition-colors inline-flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Вернуться к входу
                    </a>
                </div>
            <?php endif; ?>

            <div class="mt-8 text-center border-t border-slate-800 pt-6">
                <p class="text-sm text-slate-400">
                    Нужна помощь? 
                    <a href="/forgot_password" class="text-purple-400 hover:text-purple-300 font-medium transition-colors">Запросить новую ссылку</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-slate-600 text-xs">
        &copy; <?= date('Y') ?> AuraUI Inc. Все права защищены.
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
