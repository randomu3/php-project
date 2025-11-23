<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доступ запрещен</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-950 text-white font-[Inter] flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-slate-900/50 border border-red-500/20 rounded-2xl p-8 text-center backdrop-blur-xl">
        <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="shield-alert" class="w-8 h-8 text-red-500"></i>
        </div>
        <h1 class="text-2xl font-bold mb-2">Доступ запрещен</h1>
        <p class="text-slate-400 mb-6">У вашего аккаунта нет прав администратора для просмотра этой страницы.</p>
        <div class="bg-slate-800/50 rounded-lg p-4 mb-6 text-left text-xs font-mono text-slate-500">
            <p>User ID: <?= $user_id ?></p>
            <p>Role: <?= $is_admin ? 'Admin' : 'User' ?></p>
        </div>
        <div class="flex gap-3 justify-center">
            <a href="/" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors">На главную</a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
