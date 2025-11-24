<?php

require_once __DIR__ . '/config.php';

use AuraUI\Helpers\ActivityActions;
use AuraUI\Helpers\NotificationIcons;
use AuraUI\Helpers\NotificationTypes;

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if (empty($token)) {
    $error = 'Недействительная ссылка подтверждения.';
} else {
    try {
        $db = getDB();

        // Проверяем токен
        $stmt = $db->prepare("
            SELECT ev.id, ev.user_id, ev.new_email, u.username, u.email as old_email
            FROM email_verifications ev
            JOIN users u ON ev.user_id = u.id
            WHERE ev.token = ? AND ev.expires_at > NOW()
        ");
        $stmt->execute([$token]);
        $verification = $stmt->fetch();

        if (!$verification) {
            $error = 'Ссылка подтверждения недействительна или истекла.';
        } else {
            // Проверяем, не занят ли уже новый email
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$verification['new_email'], $verification['user_id']]);

            if ($stmt->fetch()) {
                $error = 'Этот email уже используется другим пользователем.';
            } else {
                // Обновляем email
                $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
                $stmt->execute([$verification['new_email'], $verification['user_id']]);

                // Удаляем использованный токен
                $stmt = $db->prepare("DELETE FROM email_verifications WHERE id = ?");
                $stmt->execute([$verification['id']]);

                // Логируем
                \logActivity(
                    ActivityActions::USER_UPDATE_PROFILE,
                    sprintf('Email изменен с %s на %s', $verification['old_email'], $verification['new_email']),
                    'user',
                    $verification['user_id']
                );

                // Уведомление
                \notify(
                    $verification['user_id'],
                    NotificationTypes::SUCCESS,
                    'Email подтвержден',
                    'Ваш email адрес успешно изменен',
                    '/profile',
                    NotificationIcons::SUCCESS
                );

                $success = 'Email успешно подтвержден и изменен!';
            }
        }
    } catch (PDOException $e) {
        error_log("Email verification error: " . $e->getMessage());
        $error = 'Ошибка при подтверждении email.';
    }
}

$pageTitle = 'Подтверждение email | AuraUI';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/30">
                <i data-lucide="mail-check" class="w-10 h-10 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">Подтверждение email</h1>
        </div>

        <div class="bg-slate-900/50 backdrop-blur-sm border border-slate-800 rounded-2xl p-8">
            <?php if ($success): ?>
                <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-green-400 text-sm"><?= htmlspecialchars($success) ?></p>
                    </div>
                </div>
                <a href="/profile" class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-medium py-3 px-4 rounded-lg transition-all text-center">
                    Перейти в профиль
                </a>
            <?php elseif ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-red-400 text-sm"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
                <a href="/" class="block w-full bg-slate-800 hover:bg-slate-700 text-white font-medium py-3 px-4 rounded-lg transition-all text-center">
                    На главную
                </a>
            <?php endif; ?>
        </div>
    </div>

    <script src="/assets/js/cookie-consent.js?v=<?= ASSET_VERSION ?>"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
