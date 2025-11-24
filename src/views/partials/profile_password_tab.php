<!-- Таб: Смена пароля -->
<div id="tab-password" class="tab-content <?= $activeTab === 'password' ? '' : 'hidden' ?>">
    <div class="glass-panel p-8 md:p-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-10 opacity-5 pointer-events-none">
            <i data-lucide="lock" class="w-64 h-64 text-white"></i>
        </div>

        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
            <i data-lucide="shield-check" class="w-6 h-6 text-purple-400"></i>
            Безопасность
        </h2>

        <form method="POST" class="space-y-6 max-w-lg relative z-10">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="action" value="change_password">

            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-300 mb-2">
                    Текущий пароль
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="key" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="glass-input w-full pl-10" 
                        required
                    >
                </div>
            </div>

            <div class="pt-4 border-t border-white/5">
                <label for="new_password" class="block text-sm font-medium text-slate-300 mb-2">
                    Новый пароль
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        class="glass-input w-full pl-10" 
                        required
                        minlength="8"
                    >
                </div>
                <p class="mt-1.5 text-xs text-slate-500">Минимум 8 символов</p>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-slate-300 mb-2">
                    Подтвердите новый пароль
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="check" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="glass-input w-full pl-10" 
                        required
                        minlength="8"
                    >
                </div>
            </div>

            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl text-amber-200 text-sm flex gap-3">
                <i data-lucide="info" class="w-5 h-5 flex-shrink-0"></i>
                <span>После смены пароля ваша текущая сессия останется активной.</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" class="w-full sm:flex-1 glass-button justify-center">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Обновить пароль
                </button>
                <button type="button" onclick="switchTab('info')" class="w-full sm:w-auto glass-button-secondary px-6">
                    Отмена
                </button>
            </div>
        </form>
    </div>
</div>
