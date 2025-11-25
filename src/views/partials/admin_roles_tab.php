<!-- TAB: ROLES & PERMISSIONS (RBAC) -->
<div id="tab-roles" class="tab-content hidden animate-fade-in">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center">
                <i data-lucide="shield-check" class="w-5 h-5 text-purple-400"></i>
            </div>
            Роли и права доступа
        </h1>
        <p class="text-slate-400 text-sm mt-1 ml-13">Управление ролями пользователей и разрешениями</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Roles List -->
        <div class="glass-panel p-4 sm:p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="shield" class="w-5 h-5 text-purple-400"></i>
                    Роли
                </h3>
                <button onclick="showCreateRoleModal()" class="px-3 py-1.5 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 rounded-lg text-xs sm:text-sm transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 inline"></i> <span class="hidden sm:inline">Добавить</span>
                </button>
            </div>
            <div id="roles-list" class="space-y-3">
                <div class="text-center text-slate-400 py-4 text-sm">Загрузка...</div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="glass-panel p-4 sm:p-6 rounded-2xl">
            <h3 class="text-base sm:text-lg font-semibold flex flex-wrap items-center gap-2 mb-6">
                <i data-lucide="key" class="w-5 h-5 text-emerald-400"></i>
                <span class="hidden sm:inline">Права доступа</span>
                <span class="sm:hidden">Права</span>
                <span id="selected-role-name" class="text-sm text-slate-400"></span>
            </h3>
            <div id="permissions-list" class="space-y-4 max-h-[400px] sm:max-h-[500px] overflow-y-auto">
                <div class="text-center text-slate-400 py-4 text-sm">Выберите роль для редактирования прав</div>
            </div>
            <div id="permissions-actions" class="hidden mt-4 pt-4 border-t border-white/10">
                <button onclick="saveRolePermissions()" class="w-full px-4 py-2 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg transition-colors text-sm">
                    <i data-lucide="save" class="w-4 h-4 inline"></i> Сохранить права
                </button>
            </div>
        </div>
    </div>

    <!-- User Roles Assignment -->
    <div class="glass-panel p-4 sm:p-6 rounded-2xl mt-6">
        <h3 class="text-base sm:text-lg font-semibold flex items-center gap-2 mb-6">
            <i data-lucide="users" class="w-5 h-5 text-blue-400"></i>
            <span class="hidden sm:inline">Назначение ролей пользователям</span>
            <span class="sm:hidden">Назначение ролей</span>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-4">
            <!-- User Search with Autocomplete -->
            <div class="relative">
                <input type="text" id="user-search" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-3 sm:px-4 py-2 text-white text-sm" placeholder="Поиск пользователя..." autocomplete="off">
                <input type="hidden" id="user-select-id">
                <div id="user-search-results" class="absolute top-full left-0 right-0 mt-1 bg-slate-800 border border-white/10 rounded-lg shadow-xl z-50 hidden max-h-60 overflow-y-auto"></div>
            </div>
            <select id="role-select" class="bg-slate-800/50 border border-white/10 rounded-lg px-3 sm:px-4 py-2 text-white text-sm">
                <option value="">Выберите роль</option>
            </select>
            <button onclick="assignRole()" class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 rounded-lg transition-colors text-sm sm:col-span-2 lg:col-span-1">
                <i data-lucide="user-plus" class="w-4 h-4 inline"></i> Назначить
            </button>
        </div>
        <div id="user-roles-list" class="mt-4">
            <div class="text-sm text-slate-400">Найдите пользователя для просмотра его ролей</div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div id="create-role-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Создать роль</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Системное имя</label>
                <input type="text" id="role-name" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="manager">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Отображаемое имя</label>
                <input type="text" id="role-display-name" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="Менеджер">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Описание</label>
                <textarea id="role-description" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" rows="2"></textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('create-role-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="createRole()" class="flex-1 px-4 py-2 bg-purple-500 hover:bg-purple-600 rounded-lg transition-colors">Создать</button>
        </div>
    </div>
</div>

<script>
let selectedRoleId = null;
let allPermissions = [];

function loadRoles() {
    $.get('/api/admin/roles.php?action=get_roles', function(response) {
        if (response.success) {
            let html = '';
            response.roles.forEach(role => {
                html += `
                    <div class="flex items-center justify-between p-3 bg-slate-800/30 rounded-lg hover:bg-slate-800/50 transition-colors cursor-pointer ${selectedRoleId == role.id ? 'ring-2 ring-purple-500' : ''}" onclick="selectRole(${role.id}, '${role.display_name}')">
                        <div>
                            <div class="font-medium">${role.display_name}</div>
                            <div class="text-sm text-slate-400">${role.name} • ${role.users_count} польз. • ${role.permissions_count} прав</div>
                        </div>
                        <div class="flex gap-2">
                            ${role.id > 3 ? `<button onclick="event.stopPropagation(); deleteRole(${role.id})" class="p-1.5 text-red-400 hover:bg-red-500/20 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>` : ''}
                        </div>
                    </div>
                `;
            });
            $('#roles-list').html(html);
            lucide.createIcons();
            
            // Populate role select
            let options = '<option value="">Выберите роль</option>';
            response.roles.forEach(role => {
                options += `<option value="${role.id}">${role.display_name}</option>`;
            });
            $('#role-select').html(options);
        }
    });
}

function loadPermissions() {
    $.get('/api/admin/roles.php?action=get_permissions', function(response) {
        if (response.success) {
            allPermissions = response.permissions;
        }
    });
}

function selectRole(roleId, roleName) {
    selectedRoleId = roleId;
    $('#selected-role-name').text('— ' + roleName);
    loadRoles();
    
    $.get('/api/admin/roles.php?action=get_role_permissions&role_id=' + roleId, function(response) {
        if (response.success) {
            renderPermissions(response.permission_ids);
            $('#permissions-actions').removeClass('hidden');
        }
    });
}

function renderPermissions(selectedIds) {
    let grouped = {};
    allPermissions.forEach(p => {
        if (!grouped[p.category]) grouped[p.category] = [];
        grouped[p.category].push(p);
    });
    
    let html = '';
    for (let category in grouped) {
        html += `
            <div class="mb-4">
                <div class="text-sm font-medium text-slate-300 mb-2 capitalize">${category}</div>
                <div class="space-y-2">
        `;
        grouped[category].forEach(p => {
            const checked = selectedIds.includes(p.id) ? 'checked' : '';
            html += `
                <label class="flex items-center gap-3 p-2 bg-slate-800/30 rounded-lg cursor-pointer hover:bg-slate-800/50">
                    <input type="checkbox" class="perm-checkbox rounded" value="${p.id}" ${checked}>
                    <div>
                        <div class="text-sm">${p.display_name}</div>
                        <div class="text-xs text-slate-500">${p.description}</div>
                    </div>
                </label>
            `;
        });
        html += '</div></div>';
    }
    $('#permissions-list').html(html);
}

function saveRolePermissions() {
    if (!selectedRoleId) return;
    
    const permIds = [];
    $('.perm-checkbox:checked').each(function() {
        permIds.push($(this).val());
    });
    
    $.post('/api/admin/roles.php', {
        action: 'update_role_permissions',
        role_id: selectedRoleId,
        permission_ids: JSON.stringify(permIds)
    }, function(response) {
        if (response.success) {
            showToast('Права сохранены', 'success');
            loadRoles();
        }
    });
}

function showCreateRoleModal() {
    $('#role-name, #role-display-name, #role-description').val('');
    $('#create-role-modal').removeClass('hidden');
}

function createRole() {
    $.post('/api/admin/roles.php', {
        action: 'create_role',
        name: $('#role-name').val(),
        display_name: $('#role-display-name').val(),
        description: $('#role-description').val()
    }, function(response) {
        if (response.success) {
            closeModal('create-role-modal');
            loadRoles();
            showToast('Роль создана', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function deleteRole(id) {
    if (!confirm('Удалить эту роль?')) return;
    $.post('/api/admin/roles.php', { action: 'delete_role', id: id }, function(response) {
        if (response.success) {
            loadRoles();
            showToast('Роль удалена', 'success');
        }
    });
}

let allUsersCache = [];

function loadUsersForRoles() {
    $.get('/api/admin/users.php?action=list', function(response) {
        if (response.success) {
            allUsersCache = response.users;
        }
    });
}

// User search with autocomplete
let searchTimeout;
$('#user-search').on('input', function() {
    const query = $(this).val().toLowerCase().trim();
    clearTimeout(searchTimeout);
    
    if (query.length < 1) {
        $('#user-search-results').addClass('hidden');
        return;
    }
    
    searchTimeout = setTimeout(function() {
        const filtered = allUsersCache.filter(u => 
            u.username.toLowerCase().includes(query) || 
            u.email.toLowerCase().includes(query)
        ).slice(0, 10);
        
        if (filtered.length === 0) {
            $('#user-search-results').html('<div class="p-3 text-slate-400 text-sm">Ничего не найдено</div>').removeClass('hidden');
            return;
        }
        
        let html = '';
        filtered.forEach(u => {
            html += `
                <div class="p-3 hover:bg-slate-700 cursor-pointer transition-colors" onclick="selectUser(${u.id}, '${u.username}', '${u.email}')">
                    <div class="font-medium">${u.username}</div>
                    <div class="text-xs text-slate-400">${u.email}</div>
                </div>
            `;
        });
        $('#user-search-results').html(html).removeClass('hidden');
    }, 200);
});

$('#user-search').on('focus', function() {
    if ($(this).val().length >= 1) {
        $(this).trigger('input');
    }
});

$(document).on('click', function(e) {
    if (!$(e.target).closest('#user-search, #user-search-results').length) {
        $('#user-search-results').addClass('hidden');
    }
});

function selectUser(userId, username, email) {
    $('#user-select-id').val(userId);
    $('#user-search').val(username + ' (' + email + ')');
    $('#user-search-results').addClass('hidden');
    loadUserRoles(userId);
}

function loadUserRoles(userId) {
    $.get('/api/admin/roles.php?action=get_user_roles&user_id=' + userId, function(response) {
        if (response.success) {
            let html = '<div class="flex flex-wrap gap-2">';
            if (response.roles.length === 0) {
                html += '<span class="text-slate-400 text-sm">Нет назначенных ролей</span>';
            } else {
                response.roles.forEach(r => {
                    html += `
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm">
                            ${r.display_name}
                            <button onclick="removeRole(${userId}, ${r.id})" class="hover:text-red-400"><i data-lucide="x" class="w-3 h-3"></i></button>
                        </span>
                    `;
                });
            }
            html += '</div>';
            $('#user-roles-list').html(html);
            lucide.createIcons();
        }
    });
}

function assignRole() {
    const userId = $('#user-select-id').val();
    const roleId = $('#role-select').val();
    if (!userId || !roleId) {
        showToast('Выберите пользователя и роль', 'warning');
        return;
    }
    
    $.post('/api/admin/roles.php', {
        action: 'assign_user_role',
        user_id: userId,
        role_id: roleId
    }, function(response) {
        if (response.success) {
            loadUserRoles(userId);
            loadRoles();
            showToast('Роль назначена', 'success');
        }
    });
}

function removeRole(userId, roleId) {
    $.post('/api/admin/roles.php', {
        action: 'remove_user_role',
        user_id: userId,
        role_id: roleId
    }, function(response) {
        if (response.success) {
            loadUserRoles(userId);
            loadRoles();
            showToast('Роль удалена', 'success');
        }
    });
}

// Init
$(document).ready(function() {
    loadPermissions();
});
</script>
