<?php require __DIR__ . '/../templates/header.php'; ?>

<div class="min-h-screen pt-24 pb-12 px-4">
    <div class="max-w-4xl mx-auto">
        
        <!-- Заголовок -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Уведомления</h1>
                <p class="text-slate-400">
                    <?php if ($unreadCount > 0): ?>
                        У вас <?= $unreadCount ?> непрочитанных уведомлений
                    <?php else: ?>
                        Все уведомления прочитаны
                    <?php endif; ?>
                </p>
            </div>
            
            <?php if ($unreadCount > 0): ?>
                <button onclick="markAllAsRead()" class="glass-button">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Прочитать все
                </button>
            <?php endif; ?>
        </div>

        <!-- Список уведомлений -->
        <div class="space-y-3">
            <?php if (empty($notifications)): ?>
                <div class="glass-panel p-12 text-center">
                    <i data-lucide="bell-off" class="w-16 h-16 mx-auto mb-4 text-slate-600"></i>
                    <h3 class="text-xl font-semibold text-white mb-2">Нет уведомлений</h3>
                    <p class="text-slate-400">Здесь будут отображаться ваши уведомления</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="glass-panel p-6 <?= !$notification['is_read'] ? 'border-l-4 border-purple-500' : '' ?>" data-notification-id="<?= $notification['id'] ?>">
                        <div class="flex items-start gap-4">
                            <!-- Иконка -->
                            <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center
                                <?php
                                    switch($notification['type']) {
                                        case 'success': echo 'bg-green-500/20 text-green-400'; break;
                                        case 'warning': echo 'bg-yellow-500/20 text-yellow-400'; break;
                                        case 'error': echo 'bg-red-500/20 text-red-400'; break;
                                        default: echo 'bg-blue-500/20 text-blue-400';
                                    }
                                ?>
                            ">
                                <i data-lucide="<?= htmlspecialchars($notification['icon'] ?? 'bell') ?>" class="w-6 h-6"></i>
                            </div>
                            
                            <!-- Контент -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <h3 class="text-lg font-semibold text-white">
                                        <?= htmlspecialchars($notification['title']) ?>
                                    </h3>
                                    <?php if (!$notification['is_read']): ?>
                                        <span class="badge-new flex-shrink-0 px-2 py-1 text-xs font-medium bg-purple-500/20 text-purple-300 rounded-full">
                                            Новое
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <p class="text-slate-300 mb-3">
                                    <?= htmlspecialchars($notification['message']) ?>
                                </p>
                                
                                <div class="flex items-center gap-4 text-sm text-slate-400">
                                    <span>
                                        <i data-lucide="clock" class="w-4 h-4 inline-block mr-1"></i>
                                        <?= date('d.m.Y H:i', strtotime($notification['created_at'])) ?>
                                    </span>
                                    
                                    <?php if ($notification['link']): ?>
                                        <a href="<?= htmlspecialchars($notification['link']) ?>" class="text-purple-400 hover:text-purple-300 transition-colors">
                                            Перейти →
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!$notification['is_read']): ?>
                                        <button onclick="markAsRead(<?= $notification['id'] ?>)" class="text-purple-400 hover:text-purple-300 transition-colors">
                                            Отметить прочитанным
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Кнопка назад -->
        <div class="mt-8 text-center">
            <a href="/" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Вернуться на главную
            </a>
        </div>

    </div>
</div>

<script>
function markAsRead(notificationId) {
    $.post('/api/notifications/mark-read.php', { id: notificationId }, function(response) {
        if (response.success) {
            // Убираем индикатор непрочитанного
            $('[data-notification-id="' + notificationId + '"]').removeClass('border-l-4 border-purple-500');
            $('[data-notification-id="' + notificationId + '"] .badge-new').remove();
            
            // Обновляем счетчик в заголовке
            updateUnreadCount();
            
            showNotification('Отмечено как прочитанное', 'success');
        }
    }).fail(function() {
        showNotification('Ошибка при отметке', 'error');
    });
}

function markAllAsRead() {
    $.post('/api/notifications/mark-all-read.php', function(response) {
        if (response.success) {
            // Убираем все индикаторы
            $('.glass-panel').removeClass('border-l-4 border-purple-500');
            $('.badge-new').remove();
            
            // Скрываем кнопку
            $('button[onclick="markAllAsRead()"]').parent().hide();
            
            // Обновляем текст
            $('p:contains("непрочитанных")').text('Все уведомления прочитаны');
            
            showNotification('Все уведомления прочитаны', 'success');
        }
    }).fail(function() {
        showNotification('Ошибка при отметке', 'error');
    });
}

function updateUnreadCount() {
    $.get('/api/notifications/count.php', function(response) {
        const count = response.count || 0;
        if (count > 0) {
            $('p:contains("прочитаны")').text('У вас ' + count + ' непрочитанных уведомлений');
        } else {
            $('p:contains("непрочитанных")').text('Все уведомления прочитаны');
            $('button[onclick="markAllAsRead()"]').parent().hide();
        }
    });
}
</script>

<?php require __DIR__ . '/../templates/footer.php'; ?>
