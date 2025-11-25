<div class="glass-panel rounded-2xl overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
        <h2 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-blue-400"></i> Пользователи
        </h2>
        <span class="text-sm text-slate-500"><?= count($users) ?> пользователей</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                <tr>
                    <th class="px-4 py-4">ID</th>
                    <th class="px-4 py-4">Пользователь</th>
                    <th class="px-4 py-4">Роль</th>
                    <th class="px-4 py-4">Email</th>
                    <th class="px-4 py-4">Статус</th>
                    <th class="px-4 py-4 text-right">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php foreach ($users as $u): 
                    $isBlocked = $u['locked_until'] && strtotime($u['locked_until']) > time();
                    $isCurrentUser = $u['id'] == $_SESSION['user_id'];
                    $emailVerified = $u['email_verified'] ?? 1;
                ?>
                <tr class="hover:bg-white/5 transition-colors" 
                    id="user-row-<?= $u['id'] ?>"
                    data-user-id="<?= $u['id'] ?>"
                    data-is-admin="<?= $u['is_admin'] ?>"
                    data-is-blocked="<?= $isBlocked ? '1' : '0' ?>"
                    data-email-verified="<?= $emailVerified ?>">
                    <td class="px-4 py-4 font-mono text-xs"><?= $u['id'] ?></td>
                    <td class="px-4 py-4">
                        <div class="flex flex-col">
                            <span class="text-white font-medium user-name">
                                <?= htmlspecialchars($u['username']) ?>
                                <?php if ($isCurrentUser): ?>
                                    <span class="text-xs text-purple-400">(вы)</span>
                                <?php endif; ?>
                            </span>
                            <span class="text-xs text-slate-500"><?= htmlspecialchars($u['email']) ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-4 user-role-cell">
                        <?php if ($u['is_admin']): ?>
                            <span class="user-role-badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <i data-lucide="crown" class="w-3 h-3"></i> Админ
                            </span>
                        <?php else: ?>
                            <span class="user-role-badge inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                User
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 email-status-cell">
                        <?php if ($emailVerified): ?>
                            <span class="email-status inline-flex items-center gap-1 text-xs text-emerald-400">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Подтверждён
                            </span>
                        <?php else: ?>
                            <span class="email-status inline-flex items-center gap-1 text-xs text-yellow-400">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> Не подтверждён
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 user-status-cell">
                        <?php if ($isBlocked): ?>
                            <span class="user-status inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                <i data-lucide="lock" class="w-3 h-3"></i> Заблокирован
                            </span>
                        <?php else: ?>
                            <span class="user-status inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <i data-lucide="check" class="w-3 h-3"></i> Активен
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 user-actions-cell">
                        <div class="flex items-center justify-end gap-1">
                            <?php if (!$isCurrentUser): ?>
                                <!-- Блокировка/Разблокировка -->
                                <button onclick="userAction(<?= $u['id'] ?>, '<?= $isBlocked ? 'unblock' : 'block' ?>')" 
                                        class="btn-block p-2 <?= $isBlocked ? 'text-emerald-400 hover:bg-emerald-500/10' : 'text-yellow-400 hover:bg-yellow-500/10' ?> rounded-lg transition-colors" 
                                        title="<?= $isBlocked ? 'Разблокировать' : 'Заблокировать' ?>">
                                    <i data-lucide="<?= $isBlocked ? 'unlock' : 'lock' ?>" class="w-4 h-4"></i>
                                </button>
                                
                                <!-- Переключение роли админа -->
                                <button onclick="userAction(<?= $u['id'] ?>, 'toggle_admin')" 
                                        class="btn-admin p-2 text-purple-400 hover:bg-purple-500/10 rounded-lg transition-colors" 
                                        title="<?= $u['is_admin'] ? 'Снять права админа' : 'Назначить админом' ?>">
                                    <i data-lucide="<?= $u['is_admin'] ? 'shield-off' : 'shield' ?>" class="w-4 h-4"></i>
                                </button>
                                
                                <!-- Верификация email -->
                                <button onclick="userAction(<?= $u['id'] ?>, 'verify_email')" 
                                        class="btn-verify p-2 text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors <?= $emailVerified ? 'hidden' : '' ?>" 
                                        title="Подтвердить email">
                                    <i data-lucide="mail-check" class="w-4 h-4"></i>
                                </button>
                                
                                <!-- Сброс пароля -->
                                <button onclick="userAction(<?= $u['id'] ?>, 'reset_password')" 
                                        class="p-2 text-orange-400 hover:bg-orange-500/10 rounded-lg transition-colors" 
                                        title="Сбросить пароль">
                                    <i data-lucide="key" class="w-4 h-4"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-xs text-slate-600">—</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const csrfToken = '<?= $csrf_token ?>';

function userAction(userId, action) {
    const confirmMessages = {
        'block': 'Заблокировать этого пользователя?',
        'unblock': 'Разблокировать этого пользователя?',
        'toggle_admin': 'Изменить права администратора?',
        'reset_password': 'Сбросить пароль? Новый пароль будет отправлен на email.',
        'verify_email': 'Подтвердить email этого пользователя?'
    };
    
    if (!confirm(confirmMessages[action])) return;
    
    const row = $('#user-row-' + userId);
    
    $.post('/api/admin/users.php', {
        action: action,
        user_id: userId,
        csrf_token: csrfToken
    }, function(response) {
        if (response.success) {
            showNotification(response.message, 'success');
            updateUserRow(userId, action, response);
        } else {
            showNotification(response.error, 'error');
        }
    }).fail(function() {
        showNotification('Ошибка сервера', 'error');
    });
}

function updateUserRow(userId, action, response) {
    const row = $('#user-row-' + userId);
    
    switch(action) {
        case 'block':
            row.attr('data-is-blocked', '1');
            row.find('.user-status-cell').html(`
                <span class="user-status inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                    <i data-lucide="lock" class="w-3 h-3"></i> Заблокирован
                </span>
            `);
            row.find('.btn-block')
                .attr('onclick', 'userAction(' + userId + ', \'unblock\')')
                .attr('title', 'Разблокировать')
                .removeClass('text-yellow-400 hover:bg-yellow-500/10')
                .addClass('text-emerald-400 hover:bg-emerald-500/10')
                .html('<i data-lucide="unlock" class="w-4 h-4"></i>');
            break;
            
        case 'unblock':
            row.attr('data-is-blocked', '0');
            row.find('.user-status-cell').html(`
                <span class="user-status inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <i data-lucide="check" class="w-3 h-3"></i> Активен
                </span>
            `);
            row.find('.btn-block')
                .attr('onclick', 'userAction(' + userId + ', \'block\')')
                .attr('title', 'Заблокировать')
                .removeClass('text-emerald-400 hover:bg-emerald-500/10')
                .addClass('text-yellow-400 hover:bg-yellow-500/10')
                .html('<i data-lucide="lock" class="w-4 h-4"></i>');
            break;
            
        case 'toggle_admin':
            const isNowAdmin = response.is_admin;
            row.attr('data-is-admin', isNowAdmin ? '1' : '0');
            
            if (isNowAdmin) {
                row.find('.user-role-cell').html(`
                    <span class="user-role-badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                        <i data-lucide="crown" class="w-3 h-3"></i> Админ
                    </span>
                `);
                row.find('.btn-admin')
                    .attr('title', 'Снять права админа')
                    .html('<i data-lucide="shield-off" class="w-4 h-4"></i>');
            } else {
                row.find('.user-role-cell').html(`
                    <span class="user-role-badge inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                        User
                    </span>
                `);
                row.find('.btn-admin')
                    .attr('title', 'Назначить админом')
                    .html('<i data-lucide="shield" class="w-4 h-4"></i>');
            }
            break;
            
        case 'verify_email':
            row.attr('data-email-verified', '1');
            row.find('.email-status-cell').html(`
                <span class="email-status inline-flex items-center gap-1 text-xs text-emerald-400">
                    <i data-lucide="check-circle" class="w-3 h-3"></i> Подтверждён
                </span>
            `);
            row.find('.btn-verify').addClass('hidden');
            break;
    }
    
    // Переинициализируем иконки Lucide
    lucide.createIcons();
}
</script>
