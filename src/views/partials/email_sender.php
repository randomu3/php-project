<div class="max-w-3xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-bold mb-6 flex items-center gap-2">
        <i data-lucide="send" class="text-blue-400"></i> <span class="hidden sm:inline">Ручная рассылка</span><span class="sm:hidden">Рассылка</span>
    </h2>

    <?php if ($emailSent): ?>
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center gap-3 text-green-200 animate-fade-in">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i> 
            <span><?= htmlspecialchars($emailError) ?></span>
        </div>
    <?php endif; ?>
    <?php if (!$emailSent && $emailError): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center gap-3 text-red-200 animate-fade-in">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i> 
            <span><?= htmlspecialchars($emailError) ?></span>
        </div>
    <?php endif; ?>

    <!-- Табы для выбора типа рассылки -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <button onclick="switchEmailMode('single')" id="btn-single" class="email-mode-btn active px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all whitespace-nowrap">
            <i data-lucide="user" class="w-4 h-4 inline-block mr-1 sm:mr-2"></i>
            <span class="hidden sm:inline">Одному пользователю</span><span class="sm:hidden">Одному</span>
        </button>
        <button onclick="switchEmailMode('newsletter')" id="btn-newsletter" class="email-mode-btn px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all whitespace-nowrap">
            <i data-lucide="users" class="w-4 h-4 inline-block mr-1 sm:mr-2"></i>
            <span class="hidden sm:inline">Рассылка новостей</span><span class="sm:hidden">Рассылка</span>
        </button>
    </div>

    <!-- Форма для одного пользователя -->
    <div id="single-email-form" class="glass-panel p-4 sm:p-8 rounded-2xl">
        <form method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="send_email" value="1">
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Получатель (Email)</label>
                <input type="email" name="email_to" required placeholder="user@example.com" value="<?= htmlspecialchars($_POST['email_to'] ?? '') ?>" class="w-full glass-input rounded-xl px-4 py-3 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Тема</label>
                <input type="text" name="email_subject" required placeholder="Тема письма" value="<?= htmlspecialchars($_POST['email_subject'] ?? '') ?>" class="w-full glass-input rounded-xl px-4 py-3 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Сообщение</label>
                <textarea name="email_body" rows="6" required placeholder="Ваше сообщение..." class="w-full glass-input rounded-xl px-4 py-3 text-sm"><?= htmlspecialchars($_POST['email_body'] ?? '') ?></textarea>
                <p class="text-xs text-slate-500 mt-2">Будет отправлено в красивом шаблоне с градиентом</p>
            </div>
            
            <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                <i data-lucide="send" class="w-5 h-5"></i> Отправить письмо
            </button>
        </form>
    </div>

    <!-- Форма для рассылки новостей -->
    <div id="newsletter-form" class="glass-panel p-4 sm:p-8 rounded-2xl hidden">
        <form method="POST" class="space-y-6" id="newsletterFormElement">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="send_newsletter" value="1">
            
            <div class="p-4 bg-blue-900/20 border border-blue-500/30 rounded-lg mb-4">
                <p class="text-sm text-blue-200">
                    <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                    Рассылка будет отправлена всем зарегистрированным пользователям
                </p>
            </div>

            <!-- Выбор шаблона -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    <i data-lucide="layout-template" class="w-4 h-4 inline-block mr-1"></i>
                    Шаблон письма
                </label>
                <select name="template_id" id="templateSelect" onchange="loadTemplate()" class="w-full glass-input rounded-xl px-4 py-3 text-sm">
                    <option value="">Без шаблона (обычная рассылка)</option>
                    <?php foreach ($templates as $template): ?>
                        <option value="<?= $template['id'] ?>" 
                                data-subject="<?= htmlspecialchars($template['subject'], ENT_QUOTES, 'UTF-8') ?>"
                                data-body="<?= htmlspecialchars($template['body'], ENT_QUOTES, 'UTF-8') ?>"
                                data-description="<?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-slate-500 mt-2">Выберите готовый шаблон или создайте свой текст</p>
            </div>

            <!-- Описание выбранного шаблона -->
            <div id="templateInfo" class="hidden p-4 bg-purple-900/20 border border-purple-500/30 rounded-lg">
                <p class="text-sm text-purple-200">
                    <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                    <span id="templateDescription"></span>
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Тема новости</label>
                <input type="text" name="newsletter_subject" id="newsletterSubject" required placeholder="Важные новости!" class="w-full glass-input rounded-xl px-4 py-3 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Текст новости</label>
                <textarea name="newsletter_message" id="newsletterMessage" rows="8" required placeholder="Расскажите пользователям о новостях..." class="w-full glass-input rounded-xl px-4 py-3 text-sm"></textarea>
                <p class="text-xs text-slate-500 mt-2" id="templateHint">Будет отправлено в красивом шаблоне новостей с иконкой 📢</p>
            </div>
            
            <div class="p-4 bg-amber-900/20 border border-amber-500/30 rounded-lg">
                <p class="text-sm text-amber-200">
                    <i data-lucide="alert-triangle" class="w-4 h-4 inline-block mr-2"></i>
                    <strong>Внимание:</strong> Это отправит письмо ВСЕМ пользователям. Убедитесь что текст правильный!
                </p>
            </div>

            <!-- Кнопка предпросмотра -->
            <button type="button" onclick="showPreview()" class="w-full px-6 py-3 bg-slate-700 hover:bg-slate-600 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                <i data-lucide="eye" class="w-5 h-5"></i> Предварительный просмотр
            </button>
            
            <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 rounded-xl font-bold shadow-lg shadow-purple-500/20 transition-all flex items-center justify-center gap-2">
                <i data-lucide="send" class="w-5 h-5"></i> Отправить рассылку всем
            </button>
        </form>
    </div>

    <!-- Модальное окно предпросмотра -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50 p-4" style="overflow-y: auto;">
        <div class="bg-slate-800 rounded-2xl max-w-3xl w-full mx-auto my-8 border border-slate-700">
            <div class="p-6 border-b border-slate-700 flex items-center justify-between sticky top-0 bg-slate-800 rounded-t-2xl z-10">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i data-lucide="eye" class="w-5 h-5 text-purple-400"></i>
                    Предварительный просмотр письма
                </h3>
                <button onclick="closePreview()" class="text-slate-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4 p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                    <p class="text-sm text-blue-200">
                        <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                        Так будет выглядеть письмо для пользователя <strong>Иван</strong>
                    </p>
                </div>
                <div id="previewContent" class="bg-white rounded-lg p-4" style="min-height: 400px;">
                    <!-- Здесь будет предпросмотр -->
                </div>
            </div>
            <div class="p-6 border-t border-slate-700 flex gap-3">
                <button onclick="closePreview()" class="flex-1 px-4 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">
                    Закрыть
                </button>
                <button onclick="closePreview()" class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white rounded-lg transition-colors font-bold">
                    Все верно, отправить
                </button>
            </div>
        </div>
    </div>

    <style>
        .email-mode-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
        }
        .email-mode-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .email-mode-btn.active {
            background: rgba(79, 172, 254, 0.2);
            border-color: rgba(79, 172, 254, 0.5);
            color: #4facfe;
        }
    </style>

    <script>
        function switchEmailMode(mode) {
            // Переключаем кнопки
            document.querySelectorAll('.email-mode-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('btn-' + mode).classList.add('active');
            
            // Переключаем формы
            if (mode === 'single') {
                document.getElementById('single-email-form').classList.remove('hidden');
                document.getElementById('newsletter-form').classList.add('hidden');
            } else {
                document.getElementById('single-email-form').classList.add('hidden');
                document.getElementById('newsletter-form').classList.remove('hidden');
            }
            
            // Обновляем иконки
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function loadTemplate() {
            const select = document.getElementById('templateSelect');
            const option = select.options[select.selectedIndex];
            const templateInfo = document.getElementById('templateInfo');
            const templateDescription = document.getElementById('templateDescription');
            const subjectInput = document.getElementById('newsletterSubject');
            const messageInput = document.getElementById('newsletterMessage');
            const templateHint = document.getElementById('templateHint');
            
            if (option.value) {
                // Загружаем данные шаблона
                const subject = option.getAttribute('data-subject');
                const body = option.getAttribute('data-body');
                const description = option.getAttribute('data-description');
                
                // Показываем информацию о шаблоне
                templateInfo.classList.remove('hidden');
                templateDescription.textContent = description;
                
                // Заполняем поля (тема из шаблона, текст можно редактировать)
                subjectInput.value = subject;
                
                // Подсказка что используется шаблон
                templateHint.innerHTML = '<i data-lucide="layout-template" class="w-3 h-3 inline-block mr-1"></i> Используется шаблон из базы данных. Текст ниже будет вставлен в шаблон.';
                
                // Обновляем иконки
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            } else {
                // Скрываем информацию
                templateInfo.classList.add('hidden');
                subjectInput.value = '';
                messageInput.value = '';
                templateHint.textContent = 'Будет отправлено в красивом шаблоне новостей с иконкой 📢';
            }
        }

        function showPreview() {
            const select = document.getElementById('templateSelect');
            const option = select.options[select.selectedIndex];
            const subject = document.getElementById('newsletterSubject').value;
            const message = document.getElementById('newsletterMessage').value;
            
            if (!subject || !message) {
                alert('Заполните тему и текст сообщения');
                return;
            }
            
            let html = '';
            
            if (option.value) {
                // Используем выбранный шаблон
                html = option.getAttribute('data-body');
                html = html.replace(/\{\{username\}\}/g, 'Иван');
                html = html.replace(/\{\{subject\}\}/g, escapeHtml(subject));
                html = html.replace(/\{\{message\}\}/g, escapeHtml(message).replace(/\n/g, '<br>'));
            } else {
                // Используем стандартный шаблон рассылки
                html = `
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px;">
                    <div style="background: white; padding: 30px; border-radius: 8px;">
                        <div style="text-align: center; margin-bottom: 30px;">
                            <div style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px;">
                                <h2 style="color: white; margin: 0; font-size: 18px;">📢 Новости</h2>
                            </div>
                        </div>
                        
                        <div style="margin: 20px 0; padding: 25px; background: #f8f9fa; border-left: 4px solid #4facfe; border-radius: 4px;">
                            ${escapeHtml(message).replace(/\n/g, '<br>')}
                        </div>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="http://localhost:8080" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);">
                                Перейти на сайт
                            </a>
                        </div>
                        
                        <p style="margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
                            Вы получили это письмо, так как зарегистрированы на нашем сайте.
                        </p>
                    </div>
                </div>`;
            }
            
            document.getElementById('previewContent').innerHTML = html;
            document.getElementById('previewModal').classList.remove('hidden');
            document.getElementById('previewModal').classList.add('flex');
            
            // Обновляем иконки
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('previewModal').classList.remove('flex');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Закрытие модального окна по клику вне его
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('previewModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closePreview();
                    }
                });
            }
        });
    </script>

        <div class="mt-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-start gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-400 shrink-0 mt-0.5"></i>
            <div class="text-sm text-amber-200">
                <strong>Важно:</strong> Resend отправляет письма только на <strong>demiz99@mail.ru</strong> в тестовом режиме. 
                <a href="https://resend.com/domains" target="_blank" class="underline hover:text-white">Верифицируйте домен</a> для полной работы.
            </div>
        </div>
    </div>
</div>
