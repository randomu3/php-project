<?php

namespace AuraUI\Helpers;

use PDO;
use PDOException;

/**
 * Admin Notifier Helper
 *
 * Creates notifications for admins about important events.
 *
 * @package AuraUI\Helpers
 */
class AdminNotifier
{
    /** @var PDO Database connection */
    private PDO $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Create admin notification
     *
     * @param string $type Notification type (registration, security, system, report)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array|null $data Additional data as JSON
     *
     * @return bool True on success
     */
    public function create(string $type, string $title, string $message, ?array $data = null): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO admin_notifications (type, title, message, data)
                VALUES (?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $type,
                $title,
                $message,
                $data ? json_encode($data) : null
            ]);
        } catch (PDOException $e) {
            error_log("AdminNotifier error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify about new user registration
     *
     * @param int $userId New user ID
     * @param string $username Username
     * @param string $email User email
     *
     * @return bool True on success
     */
    public function notifyNewRegistration(int $userId, string $username, string $email): bool
    {
        return $this->create(
            'registration',
            'Новая регистрация',
            "Зарегистрирован новый пользователь: {$username} ({$email})",
            ['user_id' => $userId, 'username' => $username, 'email' => $email]
        );
    }

    /**
     * Notify about suspicious login activity
     *
     * @param string $ip IP address
     * @param int $attempts Number of failed attempts
     * @param string|null $username Target username if known
     *
     * @return bool True on success
     */
    public function notifySuspiciousActivity(string $ip, int $attempts, ?string $username = null): bool
    {
        $message = "Обнаружена подозрительная активность с IP: {$ip}. ";
        $message .= "Неудачных попыток входа: {$attempts}.";
        
        if ($username) {
            $message .= " Целевой аккаунт: {$username}";
        }

        return $this->create(
            'security',
            'Подозрительная активность',
            $message,
            ['ip' => $ip, 'attempts' => $attempts, 'username' => $username]
        );
    }

    /**
     * Notify about blocked IP
     *
     * @param string $ip Blocked IP address
     * @param string $reason Block reason
     *
     * @return bool True on success
     */
    public function notifyIPBlocked(string $ip, string $reason): bool
    {
        return $this->create(
            'security',
            'IP заблокирован',
            "IP адрес {$ip} был заблокирован. Причина: {$reason}",
            ['ip' => $ip, 'reason' => $reason]
        );
    }

    /**
     * Notify about account lockout
     *
     * @param int $userId User ID
     * @param string $username Username
     * @param int $duration Lockout duration in minutes
     *
     * @return bool True on success
     */
    public function notifyAccountLocked(int $userId, string $username, int $duration): bool
    {
        return $this->create(
            'security',
            'Аккаунт заблокирован',
            "Аккаунт {$username} заблокирован на {$duration} минут из-за множественных неудачных попыток входа",
            ['user_id' => $userId, 'username' => $username, 'duration' => $duration]
        );
    }

    /**
     * Notify about system event
     *
     * @param string $title Event title
     * @param string $message Event description
     *
     * @return bool True on success
     */
    public function notifySystem(string $title, string $message): bool
    {
        return $this->create('system', $title, $message);
    }

    /**
     * Send email notification to admins if enabled
     *
     * @param string $type Notification type
     * @param string $subject Email subject
     * @param string $message Email body
     *
     * @return void
     */
    public function sendEmailToAdmins(string $type, string $subject, string $message): void
    {
        try {
            // Получаем админов с включёнными email-уведомлениями
            $stmt = $this->db->query("
                SELECT u.email, ans.* 
                FROM users u
                JOIN admin_notification_settings ans ON u.id = ans.admin_id
                WHERE u.is_admin = 1 AND ans.email_reports = 1
            ");
            
            $admins = $stmt->fetchAll();
            
            foreach ($admins as $admin) {
                // Проверяем, включён ли этот тип уведомлений
                $shouldSend = match($type) {
                    'registration' => $admin['notify_new_registration'],
                    'security' => $admin['notify_suspicious_activity'],
                    default => true
                };
                
                if ($shouldSend) {
                    sendEmail($admin['email'], $subject, $message);
                }
            }
        } catch (PDOException $e) {
            error_log("AdminNotifier email error: " . $e->getMessage());
        }
    }
}
