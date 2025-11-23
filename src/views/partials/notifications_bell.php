<?php
require_once __DIR__ . '/../../helpers/NotificationManager.php';

$unreadCount = 0;
$notifications = [];

if (isLoggedIn()) {
    $nm = new NotificationManager();
    $unreadCount = $nm->getUnreadCount($_SESSION['user_id']);
    $notifications = $nm->getUserNotifications($_SESSION['user_id'], 5);
}
?>

<!-- Колокольчик уведомлений -->
<div class="relative" id="notifications-dropdown">
    <button 
        onclick="toggleNotifications()" 
        class="relative p-2 text-slate-400 hover:text-white transition-colors rounded-lg hover:bg-white/5"
        id="notifications-button"
    >
        <i data-lucide="bell" class="w-5 h-5"></i>
        <?php if ($unreadCount > 0): ?>
            <span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                <?= $unreadCount > 9 ? '9+' : $unreadCount ?>
            </span>
        <?php endif; ?>
    </button>
    
    <!-- Dropdown меню -->
    <div 
        id="notifications-menu" 
        class="hidden absolute right-0 mt-2 w-80 bg-slate-800/95 backdrop-blur-xl rounded-xl shadow-2xl border border-white/10 z-50"
    >
        <!-- Заголовок -->
        <div class="flex items-center justify-between p-4 border-b border-white/10">
            <h3 class="font-semibold text-white">Уведомления</h3>
            <?php if ($unreadCount > 0): ?>
                <button 
                    id="mark-all-read-btn"
                    onclick="markAllAsRead()" 
                    class="text-xs text-purple-400 hover:text-purple-300 transition-colors"
                >
                    Прочитать все
                </button>
            <?php endif; ?>
        </div>
        
        <!-- Список уведомлений -->
        <div class="max-h-96 overflow-y-auto">
            <?php if (empty($notifications)): ?>
                <div class="p-8 text-center text-slate-400">
                    <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">Нет уведомлений</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <div 
                        class="notification-item p-4 border-b border-white/5 hover:bg-white/5 transition-colors cursor-pointer <?= !$notification['is_read'] ? 'bg-purple-500/5' : '' ?>"
                        data-notification-id="<?= $notification['id'] ?>"
                        onclick="markAsRead(<?= $notification['id'] ?>)"
                    >
                        <div class="flex items-start gap-3">
                            <!-- Иконка -->
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                <?php
                                    switch($notification['type']) {
                                        case 'success': echo 'bg-green-500/20 text-green-400'; break;
                                        case 'warning': echo 'bg-yellow-500/20 text-yellow-400'; break;
                                        case 'error': echo 'bg-red-500/20 text-red-400'; break;
                                        default: echo 'bg-blue-500/20 text-blue-400';
                                    }
                                ?>
                            ">
                                <i data-lucide="<?= htmlspecialchars($notification['icon'] ?? 'bell') ?>" class="w-4 h-4"></i>
                            </div>
                            
                            <!-- Контент -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white mb-1">
                                    <?= htmlspecialchars($notification['title']) ?>
                                </p>
                                <p class="text-xs text-slate-400 line-clamp-2">
                                    <?= htmlspecialchars($notification['message']) ?>
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    <?= timeAgo($notification['created_at']) ?>
                                </p>
                            </div>
                            
                            <!-- Индикатор непрочитанного -->
                            <?php if (!$notification['is_read']): ?>
                                <div class="notification-unread-dot flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Футер -->
        <div class="p-3 border-t border-white/10 text-center">
            <a href="/notifications" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                Все уведомления
            </a>
        </div>
    </div>
</div>

<script>
// Переключение dropdown
function toggleNotifications() {
    const menu = document.getElementById('notifications-menu');
    menu.classList.toggle('hidden');
}

// Закрытие при клике вне dropdown
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notifications-dropdown');
    const button = document.getElementById('notifications-button');
    
    if (!dropdown.contains(event.target)) {
        document.getElementById('notifications-menu').classList.add('hidden');
    }
});

// Отметить как прочитанное
function markAsRead(notificationId) {
    $.post('/api/notifications/mark-read.php', { id: notificationId }, function(response) {
        if (response.success) {
            // Удаляем индикатор непрочитанного
            $('[data-notification-id="' + notificationId + '"]').removeClass('bg-purple-500/5');
            $('[data-notification-id="' + notificationId + '"] .notification-unread-dot').remove();
            
            // Обновляем счетчик
            updateNotificationCount();
            
            // Показываем уведомление
            showNotification('Отмечено как прочитанное', 'success');
        }
    }).fail(function() {
        showNotification('Ошибка при отметке', 'error');
    });
}

// Отметить все как прочитанные
function markAllAsRead() {
    $.post('/api/notifications/mark-all-read.php', function(response) {
        if (response.success) {
            // Убираем все индикаторы непрочитанных
            $('.notification-item').removeClass('bg-purple-500/5');
            $('.notification-unread-dot').remove();
            
            // Обновляем счетчик
            updateNotificationCount();
            
            // Скрываем кнопку "Прочитать все"
            $('#mark-all-read-btn').hide();
            
            // Показываем уведомление
            showNotification('Все уведомления прочитаны', 'success');
        }
    }).fail(function() {
        showNotification('Ошибка при отметке', 'error');
    });
}

// Обновить счетчик уведомлений
function updateNotificationCount() {
    $.get('/api/notifications/count.php', function(response) {
        const count = response.count || 0;
        const badge = $('#notifications-button .notification-badge');
        
        if (count > 0) {
            if (badge.length) {
                badge.text(count > 9 ? '9+' : count);
            } else {
                $('#notifications-button').append(
                    '<span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">' +
                    (count > 9 ? '9+' : count) +
                    '</span>'
                );
            }
        } else {
            badge.remove();
        }
    });
}
</script>

<?php
// Вспомогательная функция для отображения времени
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) return 'только что';
    if ($diff < 3600) return floor($diff / 60) . ' мин назад';
    if ($diff < 86400) return floor($diff / 3600) . ' ч назад';
    if ($diff < 604800) return floor($diff / 86400) . ' д назад';
    
    return date('d.m.Y', $time);
}
?>
