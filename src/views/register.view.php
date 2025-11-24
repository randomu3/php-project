<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | AuraUI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VERSION ?>">
</head>
<body class="bg-slate-950 text-white min-h-screen flex flex-col relative overflow-hidden">
    <div class="fixed top-[-10%] right-[-10%] w-[500px] h-[500px] bg-pink-600/20 blur-[120px] rounded-full -z-10 pointer-events-none"></div>
    <div class="fixed bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-orange-600/20 blur-[120px] rounded-full -z-10 pointer-events-none"></div>

    <nav class="absolute top-0 w-full p-6 flex justify-between items-center z-10">
        <a href="/" class="flex items-center space-x-2 group">
            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                <i data-lucide="zap" class="w-5 h-5 text-white"></i>
            </div>
            <span class="font-bold text-xl tracking-wide">AuraUI</span>
        </a>
        <a href="/login" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-2">
            Войти <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </nav>

    <main class="flex-grow flex items-center justify-center px-4 py-20">
        <div class="w-full max-w-md glass-panel rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-pink-500 via-orange-500 to-yellow-500"></div>

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">Создать аккаунт</h1>
                <p class="text-slate-400 text-sm">Присоединяйтесь к нам, это займет меньше минуты</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3 animate-pulse">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm text-red-200"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-start gap-3">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-green-200"><?= htmlspecialchars($success) ?></p>
                        <a href="/login" class="text-xs text-green-400 underline mt-1 block hover:text-green-300">Перейти ко входу</a>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-400 ml-1 uppercase tracking-wider">Имя пользователя</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="user" class="w-5 h-5 text-slate-500 group-focus-within:text-pink-400 transition-colors"></i>
                        </div>
                        <input type="text" name="username" required minlength="3" maxlength="50"
                            class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent block w-full pl-12 p-3.5 transition-all placeholder-slate-600 hover:border-slate-600" 
                            placeholder="username" value="<?= htmlspecialchars($formData['username']) ?>">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-400 ml-1 uppercase tracking-wider">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-slate-500 group-focus-within:text-pink-400 transition-colors"></i>
                        </div>
                        <input type="email" name="email" required 
                            class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent block w-full pl-12 p-3.5 transition-all placeholder-slate-600 hover:border-slate-600" 
                            placeholder="name@example.com" value="<?= htmlspecialchars($formData['email']) ?>">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-400 ml-1 uppercase tracking-wider">Пароль</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-500 group-focus-within:text-pink-400 transition-colors"></i>
                        </div>
                        <input type="password" name="password" required minlength="8"
                            class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent block w-full pl-12 p-3.5 transition-all placeholder-slate-600 hover:border-slate-600" 
                            placeholder="Минимум 8 символов">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-400 ml-1 uppercase tracking-wider">Повторите пароль</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="shield-check" class="w-5 h-5 text-slate-500 group-focus-within:text-pink-400 transition-colors"></i>
                        </div>
                        <input type="password" name="password_confirm" required minlength="8"
                            class="w-full bg-slate-900/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent block w-full pl-12 p-3.5 transition-all placeholder-slate-600 hover:border-slate-600" 
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full text-white bg-gradient-to-r from-pink-600 to-orange-500 hover:from-pink-500 hover:to-orange-400 focus:ring-4 focus:outline-none focus:ring-pink-800 font-medium rounded-xl text-sm px-5 py-3.5 text-center shadow-lg shadow-pink-500/25 transition-all transform hover:-translate-y-0.5 mt-4">
                    Зарегистрироваться
                </button>
            </form>

            <div class="mt-8 text-center border-t border-slate-800 pt-6">
                <p class="text-sm text-slate-400">
                    Уже есть аккаунт? 
                    <a href="/login" class="text-pink-400 hover:text-pink-300 font-medium transition-colors">Войти</a>
                </p>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-slate-600 text-xs">
        &copy; <?= date('Y') ?> AuraUI Inc. Все права защищены.
    </footer>

    <script src="/assets/js/app.js?v=<?= ASSET_VERSION ?>"></script>
    <script src="/assets/js/cookie-consent.js?v=<?= ASSET_VERSION ?>"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
