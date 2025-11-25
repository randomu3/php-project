<?php

/**
 * Registration Email Verification Page
 *
 * Handles email verification for new user registrations.
 *
 * @package AuraUI
 */

require_once __DIR__ . '/config.php';

$error = '';
$success = '';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    $error = 'Токен подтверждения не указан.';
} else {
    try {
        $db = getDB();
        
        // Ищем пользователя с этим токеном
        $stmt = $db->prepare("
            SELECT id, username, email, email_verified, email_verification_expires 
            FROM users 
            WHERE email_verification_token = ?
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $error = 'Недействительная ссылка подтверждения. Возможно, она уже была использована.';
        } elseif ($user['email_verified']) {
            $error = 'Email уже подтверждён. Вы можете войти в систему.';
        } elseif (strtotime($user['email_verification_expires']) < time()) {
            // Токен истёк - удаляем неподтверждённого пользователя
            $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND email_verified = 0");
            $stmt->execute([$user['id']]);
            $error = 'Срок действия ссылки истёк. Пожалуйста, зарегистрируйтесь заново.';
        } else {
            // Подтверждаем email
            $stmt = $db->prepare("
                UPDATE users 
                SET email_verified = 1, 
                    email_verification_token = NULL, 
                    email_verification_expires = NULL 
                WHERE id = ?
            ");
            $stmt->execute([$user['id']]);
            
            // Отправляем welcome email после успешного подтверждения
            sendWelcomeEmail($user['email'], $user['username']);
            
            $success = 'Email успешно подтверждён! Теперь вы можете войти в систему.';
        }
    } catch (PDOException $e) {
        error_log("Email verification error: " . $e->getMessage());
        $error = 'Произошла ошибка. Попробуйте позже.';
    }
}

$pageTitle = 'Подтверждение email';
require_once __DIR__ . '/templates/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="glass-card p-8 rounded-2xl text-center">
            <?php if ($success): ?>
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-10 h-10 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-4">Успешно!</h1>
                <p class="text-slate-300 mb-6"><?= htmlspecialchars($success) ?></p>
                <a href="/login" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg hover:opacity-90 transition-opacity">
                    <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                    Войти в систему
                </a>
            <?php else: ?>
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-r from-red-500 to-orange-500 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-10 h-10 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-4">Ошибка</h1>
                <p class="text-slate-300 mb-6"><?= htmlspecialchars($error) ?></p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="/register" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg hover:opacity-90 transition-opacity">
                        <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                        Регистрация
                    </a>
                    <a href="/login" class="inline-flex items-center justify-center px-6 py-3 border border-slate-600 text-slate-300 font-semibold rounded-lg hover:bg-slate-800 transition-colors">
                        <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                        Войти
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
