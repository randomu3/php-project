<!-- Таб: Редактирование профиля -->
<div id="tab-edit" class="tab-content <?= $activeTab === 'edit' ? '' : 'hidden' ?>">
    <div class="glass-panel p-8 md:p-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-10 opacity-5 pointer-events-none">
            <i data-lucide="edit" class="w-64 h-64 text-white"></i>
        </div>
        
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
            <i data-lucide="edit" class="w-6 h-6 text-purple-400"></i>
            Редактирование профиля
        </h2>

        <form method="POST" class="space-y-6 max-w-lg relative z-10">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="action" value="update_profile">

            <div>
                <label for="username" class="block text-sm font-medium text-slate-300 mb-2">
                    Имя пользователя
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="<?= htmlspecialchars($user['username']) ?>"
                        class="glass-input w-full pl-10" 
                        required
                        minlength="3"
                        maxlength="50"
                    >
                </div>
                <p class="mt-1.5 text-xs text-slate-500">От 3 до 50 символов</p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                    Email адрес
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= htmlspecialchars($user['email']) ?>"
                        class="glass-input w-full pl-10" 
                        required
                    >
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" class="w-full sm:flex-1 glass-button justify-center">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Сохранить
                </button>
                <button type="button" onclick="switchTab('info')" class="w-full sm:w-auto glass-button-secondary px-6">
                    Отмена
                </button>
            </div>
        </form>
    </div>
</div>
