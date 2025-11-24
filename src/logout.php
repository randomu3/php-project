<?php

// НЕ подключаем config.php сразу, чтобы контролировать сессию
session_start();

// Сохраняем user_id для логирования
$userId = $_SESSION['user_id'] ?? null;

// ПОЛНОСТЬЮ очищаем все данные сессии
$_SESSION = array();

// Удаляем cookie сессии на клиенте (КРИТИЧНО!)
$sessionName = session_name();
if (isset($_COOKIE[$sessionName])) {
    // Удаляем cookie несколькими способами для надежности
    setcookie($sessionName, '', time() - 86400, '/');
    setcookie($sessionName, '', time() - 86400, '/', $_SERVER['HTTP_HOST']);
    setcookie($sessionName, '', 1, '/');
    unset($_COOKIE[$sessionName]);
}

// Уничтожаем файл сессии на сервере
session_destroy();

// Теперь подключаем config для логирования
require_once 'config.php';

use AuraUI\Helpers\ActivityActions;
use function logActivity;

// Логируем выход (если был залогинен)
if ($userId) {
    // Создаем временную сессию только для логирования
    session_start();
    logActivity(ActivityActions::USER_LOGOUT, 'Пользователь вышел из системы', 'user', $userId);
    session_destroy();
}

// Добавляем заголовки для предотвращения кеширования
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Редирект на страницу входа
header('Location: /login');
exit;
