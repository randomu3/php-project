-- Email Templates Seed Data
-- Красивые шаблоны для тестирования

-- Очистка существующих шаблонов (опционально)
-- DELETE FROM email_templates;

-- Шаблон приветствия
INSERT INTO email_templates (name, subject, body, description) VALUES
('welcome', 'Добро пожаловать в AuraUI! 🎉', '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: ''Segoe UI'', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f172a; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.98)); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #a855f7, #ec4899); padding: 30px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 28px;">🎉 Добро пожаловать!</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #f1f5f9; margin: 0 0 20px;">Привет, {{username}}!</h2>
                            <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Мы рады приветствовать вас в нашем сообществе! Ваш аккаунт успешно создан и готов к использованию.
                            </p>
                            <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                                Теперь вы можете пользоваться всеми возможностями платформы AuraUI.
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #a855f7, #ec4899); border-radius: 8px;">
                                        <a href="{{site_url}}" style="display: inline-block; padding: 14px 32px; color: white; text-decoration: none; font-weight: 600; font-size: 16px;">
                                            Перейти в личный кабинет →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background: rgba(0,0,0,0.2); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                            <p style="color: #64748b; font-size: 14px; margin: 0;">
                                © {{year}} {{site_name}}. Все права защищены.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>', 'Приветственное письмо для новых пользователей')
ON DUPLICATE KEY UPDATE name = name;

-- Шаблон подтверждения email
INSERT INTO email_templates (name, subject, body, description) VALUES
('verify_email', 'Подтвердите ваш email ✉️', '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: ''Segoe UI'', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f172a; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.98)); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6, #06b6d4); padding: 30px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 28px;">✉️ Подтверждение Email</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #f1f5f9; margin: 0 0 20px;">Здравствуйте, {{username}}!</h2>
                            <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Для завершения регистрации необходимо подтвердить ваш email адрес. Нажмите на кнопку ниже:
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin: 30px auto;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #3b82f6, #06b6d4); border-radius: 8px;">
                                        <a href="{{link}}" style="display: inline-block; padding: 14px 32px; color: white; text-decoration: none; font-weight: 600; font-size: 16px;">
                                            Подтвердить Email →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 20px 0 0;">
                                Если кнопка не работает, скопируйте эту ссылку в браузер:<br>
                                <a href="{{link}}" style="color: #3b82f6; word-break: break-all;">{{link}}</a>
                            </p>
                            <p style="color: #64748b; font-size: 14px; margin: 20px 0 0;">
                                ⏰ Ссылка действительна 24 часа.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background: rgba(0,0,0,0.2); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                            <p style="color: #64748b; font-size: 14px; margin: 0;">
                                Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>', 'Письмо для подтверждения email адреса')
ON DUPLICATE KEY UPDATE name = name;

-- Шаблон сброса пароля
INSERT INTO email_templates (name, subject, body, description) VALUES
('password_reset', 'Сброс пароля 🔐', '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: ''Segoe UI'', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f172a; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.98)); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f59e0b, #ef4444); padding: 30px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 28px;">🔐 Сброс пароля</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #f1f5f9; margin: 0 0 20px;">Здравствуйте, {{username}}!</h2>
                            <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Мы получили запрос на сброс пароля для вашего аккаунта. Если это были вы, нажмите на кнопку ниже:
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin: 30px auto;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #f59e0b, #ef4444); border-radius: 8px;">
                                        <a href="{{link}}" style="display: inline-block; padding: 14px 32px; color: white; text-decoration: none; font-weight: 600; font-size: 16px;">
                                            Сбросить пароль →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 15px; margin: 20px 0;">
                                <p style="color: #fca5a5; font-size: 14px; margin: 0;">
                                    ⚠️ Если вы не запрашивали сброс пароля, проигнорируйте это письмо. Ваш пароль останется прежним.
                                </p>
                            </div>
                            <p style="color: #64748b; font-size: 14px; margin: 20px 0 0;">
                                ⏰ Ссылка действительна 1 час.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background: rgba(0,0,0,0.2); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                            <p style="color: #64748b; font-size: 14px; margin: 0;">
                                © {{year}} {{site_name}}. Все права защищены.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>', 'Письмо для сброса пароля')
ON DUPLICATE KEY UPDATE name = name;

-- Шаблон рассылки/новостей
INSERT INTO email_templates (name, subject, body, description) VALUES
('newsletter', '{{subject}}', '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: ''Segoe UI'', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f172a; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.98)); border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #8b5cf6, #a855f7); padding: 30px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 28px;">📬 {{subject}}</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #f1f5f9; margin: 0 0 20px;">Привет, {{username}}!</h2>
                            <div style="color: #94a3b8; font-size: 16px; line-height: 1.8;">
                                {{message}}
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background: rgba(0,0,0,0.2); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                            <p style="color: #64748b; font-size: 14px; margin: 0 0 10px;">
                                © {{year}} {{site_name}}. Все права защищены.
                            </p>
                            <p style="color: #475569; font-size: 12px; margin: 0;">
                                Вы получили это письмо, потому что подписаны на рассылку.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>', 'Шаблон для массовой рассылки и новостей')
ON DUPLICATE KEY UPDATE name = name;

-- Шаблон уведомления о безопасности
INSERT INTO email_templates (name, subject, body, description) VALUES
('security_alert', 'Уведомление безопасности ⚠️', '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: ''Segoe UI'', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0f172a; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.98)); border-radius: 16px; overflow: hidden; border: 1px solid rgba(239, 68, 68, 0.3);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #dc2626, #ef4444); padding: 30px; text-align: center;">
                            <h1 style="color: white; margin: 0; font-size: 28px;">⚠️ Уведомление безопасности</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #f1f5f9; margin: 0 0 20px;">Здравствуйте, {{username}}!</h2>
                            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 20px; margin: 0 0 20px;">
                                <p style="color: #fca5a5; font-size: 16px; line-height: 1.6; margin: 0;">
                                    {{message}}
                                </p>
                            </div>
                            <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Если это были вы, можете проигнорировать это сообщение. В противном случае рекомендуем немедленно сменить пароль.
                            </p>
                            <table cellpadding="0" cellspacing="0" style="margin: 20px auto;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #dc2626, #ef4444); border-radius: 8px;">
                                        <a href="{{site_url}}/profile" style="display: inline-block; padding: 14px 32px; color: white; text-decoration: none; font-weight: 600; font-size: 16px;">
                                            Проверить аккаунт →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background: rgba(0,0,0,0.2); padding: 20px 30px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                            <p style="color: #64748b; font-size: 14px; margin: 0;">
                                Дата: {{date}} | © {{year}} {{site_name}}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>', 'Уведомление о подозрительной активности')
ON DUPLICATE KEY UPDATE name = name;
