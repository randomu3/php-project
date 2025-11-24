<?php

namespace AuraUI\Controllers;

use AuraUI\Helpers\NotificationManager;

/**
 *  Notifications Controller
 *
 * @package AuraUI\Controllers
 */
class NotificationsController
{
    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
        requireLogin();

        $notificationManager = new NotificationManager();
        $notifications = $notificationManager->getUserNotifications($_SESSION['user_id'], 50);
        $unreadCount = $notificationManager->getUnreadCount($_SESSION['user_id']);

        $pageTitle = 'Уведомления | AuraUI';
        $disableLoader = true; // Отключаем лоадер
        require __DIR__ . '/../views/notifications.view.php';
    }
}
