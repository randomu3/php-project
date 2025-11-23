<?php require __DIR__ . '/../templates/header.php'; ?>
<!-- Основной контент -->
    <div class="min-h-screen pt-24 pb-12 flex items-center justify-center p-4">
        <div class="w-full max-w-4xl animate-fade-in">
            
            <!-- Хлебные крошки -->
            <div class="mb-6">
                <a href="/" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors group">
                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    Вернуться на главную
                </a>
            </div>
            
            <!-- Заголовок -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-800/50 mb-6 border border-white/10 shadow-xl">
                    <i data-lucide="user-circle" class="w-10 h-10 text-purple-400"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2 tracking-tight">
                    Профиль пользователя
                </h1>
                <p class="text-slate-400">Управляйте информацией вашего аккаунта и безопасностью</p>
            </div>

            <!-- Сообщения -->
            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-200 flex items-center gap-3 animate-fade-in">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-200 flex items-center gap-3 animate-fade-in">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Табы -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <button id="btn-info" class="tab-btn <?= $activeTab === 'info' ? 'active' : '' ?>" onclick="switchTab('info')">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    Информация
                </button>
                <button id="btn-edit" class="tab-btn <?= $activeTab === 'edit' ? 'active' : '' ?>" onclick="switchTab('edit')">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Редактировать
                </button>
                <button id="btn-password" class="tab-btn <?= $activeTab === 'password' ? 'active' : '' ?>" onclick="switchTab('password')">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    Безопасность
                </button>
            </div>

            <!-- Контент табов -->
            <div class="space-y-6">
                <?php require __DIR__ . '/partials/profile_info_tab.php'; ?>
                <?php require __DIR__ . '/partials/profile_edit_tab.php'; ?>
                <?php require __DIR__ . '/partials/profile_password_tab.php'; ?>
            </div>

        </div>
    </div>

<?php require __DIR__ . '/../templates/footer.php'; ?>