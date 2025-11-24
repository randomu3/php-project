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
    $subject = 'Добро пожаловать!';

    $html = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
        <div style="background: white; padding: 30px; border-radius: 8px;">
            <h2 style="color: #667eea; margin-bottom: 20px;">Добро пожаловать, ' . htmlspecialchars($username) . '!</h2>
            <p style="color: #333; font-size: 16px; line-height: 1.6;">Спасибо за регистрацию на нашем сайте.</p>
            <p style="color: #333; font-size: 16px; line-height: 1.6;">Ваш аккаунт успешно создан и готов к использованию.</p>
            <div style="margin: 30px 0; padding: 20px; background: #f8f9fa; border-left: 4px solid #667eea; border-radius: 4px;">
                <p style="margin: 0; color: #666; font-size: 14px;">Совет: Обновите свой профиль и добавьте аватар для лучшего опыта!</p>
            </div>
            <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px;">
                Если вы не регистрировались на нашем сайте, проигнорируйте это письмо.
            </p>
        </div>
    </div>';

    return sendEmail($email, $subject, $html);
}

function sendPasswordResetEmail($email, $username, $token)
{
    $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
    $subject = 'Восстановление пароля';

    $html = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px;">
        <div style="background: white; padding: 30px; border-radius: 8px;">
            <h2 style="color: #f5576c; margin-bottom: 20px;">Восстановление пароля</h2>
            <p style="color: #333; font-size: 16px; line-height: 1.6;">Здравствуйте, ' . htmlspecialchars($username) . '!</p>
            <p style="color: #333; font-size: 16px; line-height: 1.6;">Вы запросили восстановление пароля. Нажмите на кнопку ниже, чтобы создать новый пароль:</p>
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . htmlspecialchars($resetLink) . '" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);">
                    Восстановить пароль
                </a>
            </div>
            <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                <p style="margin: 0; color: #666; font-size: 14px;">
                    Или скопируйте эту ссылку в браузер:<br>
                    <a href="' . htmlspecialchars($resetLink) . '" style="color: #f5576c; word-break: break-all;">' . htmlspecialchars($resetLink) . '</a>
                </p>
            </div>
            <div style="margin: 20px 0; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                <p style="margin: 0; color: #856404; font-size: 14px;">
                    Ссылка действительна в течение 1 часа.
                </p>
            </div>
            <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px;">
                Если вы не запрашивали восстановление пароля, проигнорируйте это письмо.
            </p>
        </div>
    </div>';

    return sendEmail($email, $subject, $html);
}

function sendNewsletterEmail($email, $subject, $message)
{
    // Красивый шаблон для рассылки новостей
    $html = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px;">
        <div style="background: white; padding: 30px; border-radius: 8px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px;">
                    <h2 style="color: white; margin: 0; font-size: 18px;">📢 Новости</h2>
                </div>
            </div>
            
            <div style="margin: 20px 0; padding: 25px; background: #f8f9fa; border-left: 4px solid #4facfe; border-radius: 4px;">
                ' . nl2br(htmlspecialchars($message)) . '
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="http://' . $_SERVER['HTTP_HOST'] . '" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);">
                    Перейти на сайт
                </a>
            </div>
            
            <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
                Вы получили это письмо, так как зарегистрированы на нашем сайте.<br>
                Если вы хотите отписаться от рассылки, свяжитесь с администратором.
            </p>
        </div>
    </div>';

    return sendEmail($email, $subject, $html);
}
