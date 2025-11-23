<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/NotificationManager.php';

class NotificationsController {
    
    public function index() {
        requireLogin();
        
        $nm = new NotificationManager();
        $notifications = $nm->getUserNotifications($_SESSION['user_id'], 50);
        $unreadCount = $nm->getUnreadCount($_SESSION['user_id']);
        
        $pageTitle = 'Уведомления | AuraUI';
        $disableLoader = true; // Отключаем лоадер
        require __DIR__ . '/../views/notifications.view.php';
    }
}
