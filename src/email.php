<?php

function sendEmail($to, $subject, $html)
{
    try {
        $resend = \Resend::client(RESEND_API_KEY);

        $result = $resend->emails->send([
            'from' => FROM_NAME . ' <' . FROM_EMAIL . '>',
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
        ]);

        // Логируем успешную отправку
        error_log("✅ Email sent successfully to: {$to}, Subject: {$subject}, ID: " . ($result->id ?? 'N/A'));
        return true;
    } catch (Exception $e) {
        // Детальное логирование ошибки
        error_log("❌ Email error to {$to}: " . $e->getMessage());
        return false;
    }
}

function sendWelcomeEmail($email, $username)
{
    $subject = 'Добро пожаловать в AuraUI!';

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; background-color: #0f172a; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif;">
        <div style="max-width: 600px; margin: 40px auto; background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%); border-radius: 16px; overflow: hidden;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); padding: 40px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <span style="font-size: 32px;">⚡</span>
                </div>
                <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 700;">Добро пожаловать!</h1>
            </div>
            
            <!-- Content -->
            <div style="background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); padding: 40px 30px;">
                <p style="color: #e2e8f0; font-size: 18px; line-height: 1.6; margin: 0 0 20px 0;">
                    Привет, <strong style="color: #a855f7;">' . htmlspecialchars($username) . '</strong>!
                </p>
                <p style="color: #cbd5e1; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                    Спасибо за регистрацию на <strong>AuraUI</strong>. Ваш аккаунт успешно создан и готов к использованию.
                </p>
                
                <!-- Info Box -->
                <div style="margin: 30px 0; padding: 20px; background: rgba(168, 85, 247, 0.1); border-left: 4px solid #a855f7; border-radius: 8px;">
                    <p style="margin: 0; color: #e2e8f0; font-size: 14px; line-height: 1.6;">
                        💡 <strong>Совет:</strong> Обновите свой профиль и добавьте аватар для лучшего опыта!
                    </p>
                </div>
                
                <!-- Button -->
                <div style="text-align: center; margin: 30px 0;">
                    <a href="http://' . $_SERVER['HTTP_HOST'] . '/profile" style="display: inline-block; background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 20px rgba(168, 85, 247, 0.3);">
                        Перейти в профиль
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div style="background: rgba(15, 23, 42, 0.8); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                <p style="margin: 0; color: #64748b; font-size: 12px; line-height: 1.6;">
                    Если вы не регистрировались на нашем сайте, проигнорируйте это письмо.
                </p>
            </div>
        </div>
    </body>
    </html>';

    return sendEmail($email, $subject, $html);
}

function sendPasswordResetEmail($email, $username, $token)
{
    $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
    $subject = 'Восстановление пароля - AuraUI';

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; background-color: #0f172a; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif;">
        <div style="max-width: 600px; margin: 40px auto; background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%); border-radius: 16px; overflow: hidden;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); padding: 40px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <span style="font-size: 32px;">🔐</span>
                </div>
                <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 700;">Восстановление пароля</h1>
            </div>
            
            <!-- Content -->
            <div style="background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); padding: 40px 30px;">
                <p style="color: #e2e8f0; font-size: 18px; line-height: 1.6; margin: 0 0 20px 0;">
                    Здравствуйте, <strong style="color: #a855f7;">' . htmlspecialchars($username) . '</strong>!
                </p>
                <p style="color: #cbd5e1; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0;">
                    Вы запросили восстановление пароля. Нажмите на кнопку ниже, чтобы создать новый пароль:
                </p>
                
                <!-- Button -->
                <div style="text-align: center; margin: 30px 0;">
                    <a href="' . htmlspecialchars($resetLink) . '" style="display: inline-block; background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); color: white; padding: 16px 40px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 20px rgba(168, 85, 247, 0.3);">
                        Восстановить пароль
                    </a>
                </div>
                
                <!-- Link Box -->
                <div style="margin: 30px 0; padding: 20px; background: rgba(15, 23, 42, 0.6); border-radius: 8px; border: 1px solid rgba(168, 85, 247, 0.2);">
                    <p style="margin: 0 0 10px 0; color: #94a3b8; font-size: 13px;">
                        Или скопируйте эту ссылку в браузер:
                    </p>
                    <p style="margin: 0; color: #a855f7; font-size: 13px; word-break: break-all; font-family: monospace;">
                        ' . htmlspecialchars($resetLink) . '
                    </p>
                </div>
                
                <!-- Warning Box -->
                <div style="margin: 20px 0; padding: 16px; background: rgba(251, 191, 36, 0.1); border-left: 4px solid #fbbf24; border-radius: 8px;">
                    <p style="margin: 0; color: #fbbf24; font-size: 14px; line-height: 1.6;">
                        ⚠️ Ссылка действительна в течение <strong>1 часа</strong>.
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div style="background: rgba(15, 23, 42, 0.8); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                <p style="margin: 0; color: #64748b; font-size: 12px; line-height: 1.6;">
                    Если вы не запрашивали восстановление пароля, проигнорируйте это письмо.<br>
                    Ваш пароль останется без изменений.
                </p>
            </div>
        </div>
    </body>
    </html>';

    return sendEmail($email, $subject, $html);
}

function sendNewsletterEmail($email, $subject, $message)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; background-color: #0f172a; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif;">
        <div style="max-width: 600px; margin: 40px auto; background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%); border-radius: 16px; overflow: hidden;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); padding: 40px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <span style="font-size: 32px;">📢</span>
                </div>
                <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 700;">' . htmlspecialchars($subject) . '</h1>
            </div>
            
            <!-- Content -->
            <div style="background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(12px); padding: 40px 30px;">
                <!-- Message Box -->
                <div style="margin: 0 0 30px 0; padding: 25px; background: rgba(168, 85, 247, 0.1); border-left: 4px solid #a855f7; border-radius: 8px;">
                    <div style="color: #e2e8f0; font-size: 16px; line-height: 1.8;">
                        ' . nl2br(htmlspecialchars($message)) . '
                    </div>
                </div>
                
                <!-- Button -->
                <div style="text-align: center; margin: 30px 0;">
                    <a href="http://' . $_SERVER['HTTP_HOST'] . '" style="display: inline-block; background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 20px rgba(168, 85, 247, 0.3);">
                        Перейти на сайт
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div style="background: rgba(15, 23, 42, 0.8); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                <p style="margin: 0; color: #64748b; font-size: 12px; line-height: 1.6;">
                    Вы получили это письмо, так как зарегистрированы на <strong>AuraUI</strong>.<br>
                    Если вы хотите отписаться от рассылки, свяжитесь с администратором.
                </p>
            </div>
        </div>
    </body>
    </html>';

    return sendEmail($email, $subject, $html);
}
