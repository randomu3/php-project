<div class="glass-panel rounded-2xl overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
        <h2 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-blue-400"></i> Пользователи
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Пользователь</th>
                    <th class="px-6 py-4">Роль</th>
                    <th class="px-6 py-4">Регистрация</th>
                    <th class="px-6 py-4">Активность</th>
                    <th class="px-6 py-4">Статус</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs"><?= $user['id'] ?></td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-white font-medium"><?= htmlspecialchars($user['username']) ?></span>
                            <span class="text-xs text-slate-500"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($user['is_admin']): ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <i data-lucide="crown" class="w-3 h-3"></i> Админ
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                User
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4"><?= date('d.m.y', strtotime($user['created_at'])) ?></td>
                    <td class="px-6 py-4">
                        <?php if ($user['last_login']): ?>
                            <span class="text-slate-300"><?= date('d.m H:i', strtotime($user['last_login'])) ?></span>
                        <?php else: ?>
                            <span class="text-slate-600">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($user['locked_until'] && strtotime($user['locked_until']) > time()): ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                Block
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Active
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
