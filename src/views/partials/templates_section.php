<h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
    <i data-lucide="file-text" class="text-purple-400"></i> Шаблоны писем
</h2>

<?php if ($templateSaved): ?>
    <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center gap-3 text-green-200">
        <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i> Шаблон успешно сохранен!
    </div>
<?php endif; ?>
<?php if ($templateError): ?>
    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center gap-3 text-red-200">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i> <?= htmlspecialchars($templateError) ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <?php foreach ($templates as $template): ?>
    <div class="glass-panel p-6 rounded-2xl flex flex-col justify-between hover:bg-white/5 transition-colors">
        <div>
            <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center mb-4 text-purple-400">
                <i data-lucide="layout-template" class="w-5 h-5"></i>
            </div>
            <h3 class="text-lg font-bold mb-2"><?= htmlspecialchars($template['name']) ?></h3>
            <p class="text-sm text-slate-400 mb-4"><?= htmlspecialchars($template['description']) ?></p>
        </div>
        <button onclick='editTemplate(<?= $template['id'] ?>, <?= json_encode($template['name']) ?>, <?= json_encode($template['subject']) ?>, <?= json_encode($template['body']) ?>)' 
                class="w-full py-2 px-4 rounded-lg border border-white/10 text-sm font-medium hover:bg-purple-600 hover:border-purple-600 hover:text-white transition-all flex items-center justify-center gap-2">
            <i data-lucide="edit-3" class="w-4 h-4"></i> Редактировать
        </button>
    </div>
    <?php endforeach; ?>
</div>

<div id="template-editor" class="hidden glass-panel p-8 rounded-2xl border-l-4 border-l-purple-500">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold flex items-center gap-2">
            <span class="text-purple-400">Редактирование:</span> <span id="template-name"></span>
        </h3>
        <button onclick="closeEditor()" class="text-slate-400 hover:text-white transition-colors">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>
    
    <div class="bg-blue-900/20 border border-blue-500/20 p-4 rounded-lg mb-6 text-sm text-blue-200">
        <strong class="block mb-2 text-blue-400">Доступные переменные:</strong>
        <div class="flex flex-wrap gap-2">
            <code class="bg-blue-900/40 px-2 py-1 rounded text-xs font-mono">{{username}}</code>
            <code class="bg-blue-900/40 px-2 py-1 rounded text-xs font-mono">{{reset_link}}</code>
            <code class="bg-blue-900/40 px-2 py-1 rounded text-xs font-mono">{{message}}</code>
        </div>
    </div>

    <form method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="save_template" value="1">
        <input type="hidden" name="template_id" id="edit_template_id">
        
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Тема письма</label>
            <input type="text" name="template_subject" id="edit_template_subject" required class="w-full glass-input rounded-xl px-4 py-3 text-sm">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">HTML тело письма</label>
            <textarea name="template_body" id="edit_template_body" rows="10" required class="w-full glass-input rounded-xl px-4 py-3 text-sm font-mono"></textarea>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 rounded-xl font-medium shadow-lg shadow-purple-500/20 transition-all text-sm">
                Сохранить шаблон
            </button>
            <button type="button" onclick="closeEditor()" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 rounded-xl font-medium text-slate-300 transition-all text-sm">
                Отмена
            </button>
        </div>
    </form>
</div>
