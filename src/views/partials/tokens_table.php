<div class="glass-panel rounded-2xl overflow-hidden">
    <div class="px-6 py-5 border-b border-white/5">
        <h2 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="key" class="w-5 h-5 text-green-400"></i> Токены сброса (Последние 10)
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Пользователь</th>
                    <th class="px-6 py-4">Токен (Preview)</th>
                    <th class="px-6 py-4">Создан</th>
                    <th class="px-6 py-4">Истекает</th>
                    <th class="px-6 py-4">Статус</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($tokens)): ?>
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">Нет токенов</td></tr>
                <?php else: ?>
                    <?php foreach ($tokens as $token): ?>
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs"><?= $token['id'] ?></td>
                        <td class="px-6 py-4 text-slate-300"><?= htmlspecialchars($token['username']) ?></td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-500"><?= $token['token_preview'] ?>...</td>
                        <td class="px-6 py-4"><?= date('H:i d.m', strtotime($token['created_at'])) ?></td>
                        <td class="px-6 py-4"><?= date('H:i d.m', strtotime($token['expires_at'])) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($token['used']): ?>
                                <span class="text-slate-500">Использован</span>
                            <?php elseif (strtotime($token['expires_at']) < time()): ?>
                                <span class="text-amber-500">Истёк</span>
                            <?php else: ?>
                                <span class="text-emerald-400">Активен</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
