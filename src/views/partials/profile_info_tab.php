<!-- Таб: Информация о профиле -->
<div id="tab-info" class="tab-content <?= $activeTab === 'info' ? '' : 'hidden' ?>">
    <div class="glass-panel p-8 md:p-10">
        <div class="space-y-8">
            
            <!-- Аватар -->
            <div class="flex justify-center">
                <div class="relative group" id="avatar-container">
                    <?php if (!empty($user['avatar']) && file_exists('uploads/avatars/' . $user['avatar'])): ?>
                        <!-- Загруженный аватар -->
                        <img 
                            id="user-avatar-img"
                            src="/uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>?t=<?= time() ?>" 
                            alt="Avatar" 
                            class="w-32 h-32 rounded-full object-cover shadow-2xl shadow-purple-500/30 ring-4 ring-slate-900"
                        >
                    <?php else: ?>
                        <!-- Дефолтный аватар -->
                        <div id="user-avatar-default" class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-5xl font-bold shadow-2xl shadow-purple-500/30 ring-4 ring-slate-900">
                            <?= strtoupper(mb_substr($user['username'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Индикатор загрузки -->
                    <div id="avatar-loading" class="hidden absolute inset-0 bg-black/50 rounded-full flex items-center justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                    </div>
                    
                    <!-- Кнопка изменения аватара -->
                    <button 
                        onclick="document.getElementById('avatar-upload-input').click()" 
                        class="absolute bottom-0 right-0 w-10 h-10 bg-purple-600 hover:bg-purple-500 rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100"
                        title="Изменить аватар"
                    >
                        <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                    </button>
                </div>
            </div>
            
            <!-- Форма загрузки аватара (скрытая) -->
            <form id="avatar-upload-form" class="hidden">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input 
                    type="file" 
                    id="avatar-upload-input" 
                    name="avatar" 
                    accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                    onchange="uploadAvatar()"
                >
            </form>
            
            <!-- Кнопка удаления аватара -->
            <div class="text-center mt-4" id="delete-avatar-btn" style="display: <?= !empty($user['avatar']) ? 'block' : 'none' ?>">
                <button onclick="deleteAvatar()" class="text-sm text-red-400 hover:text-red-300 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4 inline-block mr-1"></i>
                    Удалить аватар
                </button>
            </div>
            
            <script>
            // Загрузка аватара через AJAX
            function uploadAvatar() {
                const fileInput = document.getElementById('avatar-upload-input');
                const file = fileInput.files[0];
                
                if (!file) return;
                
                // Показать индикатор загрузки
                $('#avatar-loading').removeClass('hidden');
                
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('csrf_token', '<?= $csrf_token ?>');
                
                $.ajax({
                    url: '/api/profile/upload-avatar.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#avatar-loading').addClass('hidden');
                        
                        if (response.success) {
                            // Обновляем аватар
                            updateAvatarDisplay(response.avatar_url);
                            
                            // Показываем уведомление
                            showNotification(response.message, 'success');
                            
                            // Показываем кнопку удаления
                            $('#delete-avatar-btn').show();
                            
                            // Обновляем аватар в навигации
                            updateNavigationAvatar(response.avatar_url);
                        } else {
                            showNotification(response.error, 'error');
                        }
                        
                        // Очищаем input
                        fileInput.value = '';
                    },
                    error: function() {
                        $('#avatar-loading').addClass('hidden');
                        showNotification('Ошибка загрузки аватара', 'error');
                        fileInput.value = '';
                    }
                });
            }
            
            // Удаление аватара через AJAX
            function deleteAvatar() {
                if (!confirm('Удалить аватар?')) return;
                
                $.post('/api/profile/delete-avatar.php', {
                    csrf_token: '<?= $csrf_token ?>'
                }, function(response) {
                    if (response.success) {
                        // Показываем дефолтный аватар
                        updateAvatarDisplay(null, response.initial);
                        
                        // Показываем уведомление
                        showNotification(response.message, 'success');
                        
                        // Скрываем кнопку удаления
                        $('#delete-avatar-btn').hide();
                        
                        // Обновляем аватар в навигации
                        updateNavigationAvatar(null, response.initial);
                    } else {
                        showNotification(response.error, 'error');
                    }
                }).fail(function() {
                    showNotification('Ошибка удаления аватара', 'error');
                });
            }
            
            // Обновить отображение аватара
            function updateAvatarDisplay(avatarUrl, initial) {
                const container = $('#avatar-container');
                
                if (avatarUrl) {
                    // Показываем загруженный аватар
                    container.html(`
                        <img 
                            id="user-avatar-img"
                            src="${avatarUrl}" 
                            alt="Avatar" 
                            class="w-32 h-32 rounded-full object-cover shadow-2xl shadow-purple-500/30 ring-4 ring-slate-900"
                        >
                        <div id="avatar-loading" class="hidden absolute inset-0 bg-black/50 rounded-full flex items-center justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                        </div>
                        <button 
                            onclick="document.getElementById('avatar-upload-input').click()" 
                            class="absolute bottom-0 right-0 w-10 h-10 bg-purple-600 hover:bg-purple-500 rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100"
                            title="Изменить аватар"
                        >
                            <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                        </button>
                    `);
                } else {
                    // Показываем дефолтный аватар
                    container.html(`
                        <div id="user-avatar-default" class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-5xl font-bold shadow-2xl shadow-purple-500/30 ring-4 ring-slate-900">
                            ${initial}
                        </div>
                        <div id="avatar-loading" class="hidden absolute inset-0 bg-black/50 rounded-full flex items-center justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                        </div>
                        <button 
                            onclick="document.getElementById('avatar-upload-input').click()" 
                            class="absolute bottom-0 right-0 w-10 h-10 bg-purple-600 hover:bg-purple-500 rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100"
                            title="Изменить аватар"
                        >
                            <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                        </button>
                    `);
                }
                
                // Переинициализируем иконки
                lucide.createIcons();
            }
            
            // Обновить аватар в навигации (если есть)
            function updateNavigationAvatar(avatarUrl, initial) {
                // Обновляем на главной странице если она открыта
                const navAvatar = $('a[href="/profile"] img, a[href="/profile"] div.rounded-full').first();
                if (navAvatar.length) {
                    if (avatarUrl) {
                        navAvatar.replaceWith(`<img src="${avatarUrl}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">`);
                    } else if (initial) {
                        navAvatar.replaceWith(`<div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-sm font-bold uppercase">${initial}</div>`);
                    }
                }
            }
            </script>

            <!-- Информация -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-purple-300 uppercase tracking-wider ml-1">
                        Имя пользователя
                    </label>
                    <div class="glass-input flex items-center gap-3 text-slate-300 cursor-default">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                        <?= htmlspecialchars($user['username']) ?>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold text-purple-300 uppercase tracking-wider ml-1">
                        Email
                    </label>
                    <div class="glass-input flex items-center gap-3 text-slate-300 cursor-default">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>
                        <?= htmlspecialchars($user['email']) ?>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold text-purple-300 uppercase tracking-wider ml-1">
                        Дата регистрации
                    </label>
                    <div class="glass-input flex items-center gap-3 text-slate-300 cursor-default">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                        <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold text-purple-300 uppercase tracking-wider ml-1">
                        Последний вход
                    </label>
                    <div class="glass-input flex items-center gap-3 text-slate-300 cursor-default">
                        <i data-lucide="clock" class="w-4 h-4 text-slate-500"></i>
                        <?= $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Никогда' ?>
                    </div>
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-xs font-semibold text-purple-300 uppercase tracking-wider ml-1">
                        Роль в системе
                    </label>
                    <div class="glass-input flex items-center gap-3 cursor-default">
                        <?php if ($user['is_admin']): ?>
                            <i data-lucide="crown" class="w-4 h-4 text-amber-400"></i>
                            <span class="text-amber-400 font-medium">Администратор</span>
                        <?php else: ?>
                            <i data-lucide="shield" class="w-4 h-4 text-blue-400"></i>
                            <span class="text-blue-400 font-medium">Пользователь</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-white/5">
                <button onclick="switchTab('edit')" class="flex-1 glass-button justify-center">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    Изменить данные
                </button>
                <button onclick="switchTab('password')" class="flex-1 glass-button-secondary justify-center">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    Сменить пароль
                </button>
            </div>
        </div>
    </div>
</div>
