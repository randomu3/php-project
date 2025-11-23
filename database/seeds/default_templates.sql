-- Seed: Дефолтные шаблоны email
-- Кодировка: UTF-8

SET NAMES utf8mb4;

INSERT INTO email_templates (name, subject, body, description) VALUES
('welcome', 'Добро пожаловать!', '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
    <div style="background: white; padding: 30px; border-radius: 8px;">
        <h2 style="color: #667eea; margin-bottom: 20px;">🎉 Добро пожаловать, {{username}}!</h2>
        <p style="color: #333; font-size: 16px; line-height: 1.6;">Спасибо за регистрацию на нашем сайте.</p>
        <p style="color: #333; font-size: 16px; line-height: 1.6;">Ваш аккаунт успешно создан и готов к использованию.</p>
        <div style="margin: 30px 0; padding: 20px; background: #f8f9fa; border-left: 4px solid #667eea; border-radius: 4px;">
            <p style="margin: 0; color: #666; font-size: 14px;">💡 Совет: Обновите свой профиль и добавьте аватар для лучшего опыта!</p>
        </div>
        <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px;">
            Если вы не регистрировались на нашем сайте, проигнорируйте это письмо.
        </p>
    </div>
</div>', 'Приветственное письмо при регистрации'),

('password_reset', 'Восстановление пароля', '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px;">
    <div style="background: white; padding: 30px; border-radius: 8px;">
        <h2 style="color: #f5576c; margin-bottom: 20px;">🔐 Восстановление пароля</h2>
        <p style="color: #333; font-size: 16px; line-height: 1.6;">Здравствуйте, {{username}}!</p>
        <p style="color: #333; font-size: 16px; line-height: 1.6;">Вы запросили восстановление пароля. Нажмите на кнопку ниже, чтобы создать новый пароль:</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{reset_link}}" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);">
                Восстановить пароль
            </a>
        </div>
        <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 4px;">
            <p style="margin: 0; color: #666; font-size: 14px;">
                Или скопируйте эту ссылку в браузер:<br>
                <a href="{{reset_link}}" style="color: #f5576c; word-break: break-all;">{{reset_link}}</a>
            </p>
        </div>
        <div style="margin: 20px 0; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
            <p style="margin: 0; color: #856404; font-size: 14px;">
                ⏰ Ссылка действительна в течение 1 часа.
            </p>
        </div>
        <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px;">
            Если вы не запрашивали восстановление пароля, проигнорируйте это письмо.
        </p>
    </div>
</div>', 'Письмо для восстановления пароля'),

('notification', 'Уведомление', '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px;">
    <div style="background: white; padding: 30px; border-radius: 8px;">
        <h2 style="color: #4facfe; margin-bottom: 20px;">🔔 Уведомление</h2>
        <p style="color: #333; font-size: 16px; line-height: 1.6;">Здравствуйте!</p>
        <div style="margin: 20px 0; padding: 20px; background: #f8f9fa; border-left: 4px solid #4facfe; border-radius: 4px;">
            <p style="margin: 0; color: #333; font-size: 16px; line-height: 1.6;">{{message}}</p>
        </div>
    </div>
</div>', 'Общее уведомление'),

('newsletter', 'Новости', '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
    <div style="background: white; padding: 30px; border-radius: 8px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 25px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                <h2 style="color: white; margin: 0; font-size: 20px;">📢 {{subject}}</h2>
            </div>
        </div>
        
        <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Здравствуйте, {{username}}!</p>
        
        <div style="margin: 25px 0; padding: 25px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 5px solid #667eea; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div style="color: #333; font-size: 16px; line-height: 1.8;">{{message}}</div>
        </div>
        
        <div style="text-align: center; margin: 35px 0;">
            <a href="http://localhost:8080" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 35px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s;">
                🌐 Перейти на сайт
            </a>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #f0f4ff; border-radius: 8px; text-align: center;">
            <p style="margin: 0; color: #667eea; font-size: 14px; font-weight: 500;">
                💡 Следите за обновлениями на нашем сайте!
            </p>
        </div>
        
        <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
            Вы получили это письмо, так как зарегистрированы на нашем сайте.<br>
            Если вы хотите отписаться от рассылки, свяжитесь с администратором.
        </p>
    </div>
</div>', 'Рассылка новостей всем пользователям'),

('announcement', 'Важное объявление', '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px;">
    <div style="background: white; padding: 30px; border-radius: 8px;">
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px;">
                <h2 style="color: white; margin: 0; font-size: 18px;">⚠️ Важное объявление</h2>
            </div>
        </div>
        
        <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Уважаемый {{username}},</p>
        
        <div style="margin: 25px 0; padding: 25px; background: #fff3cd; border-left: 5px solid #ffc107; border-radius: 8px;">
            <div style="color: #856404; font-size: 16px; line-height: 1.8; font-weight: 500;">{{message}}</div>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="http://localhost:8080" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);">
                Подробнее
            </a>
        </div>
        
        <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
            Это важное сообщение для всех пользователей нашего сервиса.
        </p>
    </div>
</div>', 'Важные объявления и уведомления'),

('promo', 'Специальное предложение', '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px;">
    <div style="background: white; padding: 30px; border-radius: 8px;">
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px;">
                <h2 style="color: white; margin: 0; font-size: 18px;">🎁 Специальное предложение</h2>
            </div>
        </div>
        
        <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">Привет, {{username}}!</p>
        
        <div style="margin: 25px 0; padding: 25px; background: linear-gradient(135deg, #e0f7ff 0%, #b3e5fc 100%); border-radius: 12px; text-align: center; border: 2px dashed #4facfe;">
            <div style="color: #0277bd; font-size: 18px; line-height: 1.8; font-weight: 600;">{{message}}</div>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="http://localhost:8080" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 14px 35px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4); font-size: 16px;">
                🚀 Воспользоваться предложением
            </a>
        </div>
        
        <div style="margin-top: 25px; padding: 15px; background: #fff9e6; border-radius: 8px; text-align: center;">
            <p style="margin: 0; color: #f57c00; font-size: 13px; font-weight: 500;">
                ⏰ Предложение ограничено по времени!
            </p>
        </div>
        
        <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
            Не упустите возможность воспользоваться нашим специальным предложением!
        </p>
    </div>
</div>', 'Промо-акции и специальные предложения')
ON DUPLICATE KEY UPDATE 
    subject = VALUES(subject),
    body = VALUES(body),
    description = VALUES(description);
