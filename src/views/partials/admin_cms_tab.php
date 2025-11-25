<!-- TAB: CMS -->
<div id="tab-cms" class="tab-content hidden animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pages List -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-blue-400"></i>
                    Страницы
                </h3>
                <div class="flex gap-2">
                    <select id="page-status-filter" class="bg-slate-800/50 border border-white/10 rounded-lg px-3 py-1.5 text-sm">
                        <option value="">Все статусы</option>
                        <option value="published">Опубликованные</option>
                        <option value="draft">Черновики</option>
                        <option value="archived">Архив</option>
                    </select>
                    <button onclick="showPageEditor()" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg text-sm transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 inline"></i> Создать
                    </button>
                </div>
            </div>
            <div id="pages-list" class="space-y-3">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>

        <!-- Menus -->
        <div class="glass-panel p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="menu" class="w-5 h-5 text-purple-400"></i>
                    Меню
                </h3>
                <button onclick="showCreateMenuModal()" class="px-3 py-1.5 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 rounded-lg text-sm transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 inline"></i>
                </button>
            </div>
            <div id="menus-list" class="space-y-3">
                <div class="text-center text-slate-400 py-4">Загрузка...</div>
            </div>
        </div>
    </div>

    <!-- Page Editor -->
    <div id="page-editor" class="glass-panel p-6 rounded-2xl mt-6 hidden">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold" id="editor-title">Новая страница</h3>
            <button onclick="$('#page-editor').addClass('hidden')" class="text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <input type="hidden" id="page-id">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Заголовок</label>
                    <input type="text" id="page-title" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="Заголовок страницы">
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">URL (slug)</label>
                    <input type="text" id="page-slug" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white font-mono" placeholder="page-url">
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Контент</label>
                    <textarea id="page-content" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white font-mono text-sm" rows="12" placeholder="HTML контент страницы"></textarea>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Статус</label>
                    <select id="page-status" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                        <option value="draft">Черновик</option>
                        <option value="published">Опубликовано</option>
                        <option value="archived">Архив</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Meta Title</label>
                    <input type="text" id="page-meta-title" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Meta Description</label>
                    <textarea id="page-meta-desc" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Meta Keywords</label>
                    <input type="text" id="page-meta-keywords" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="keyword1, keyword2">
                </div>
                <button onclick="savePage()" class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors">
                    <i data-lucide="save" class="w-4 h-4 inline"></i> Сохранить
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Items Editor -->
    <div id="menu-items-editor" class="glass-panel p-6 rounded-2xl mt-6 hidden">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold" id="menu-editor-title">Пункты меню</h3>
            <div class="flex gap-2">
                <button onclick="showAddMenuItemModal()" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg text-sm transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 inline"></i> Добавить
                </button>
                <button onclick="$('#menu-items-editor').addClass('hidden')" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        <input type="hidden" id="current-menu-id">
        <div id="menu-items-list" class="space-y-2">
            <div class="text-center text-slate-400 py-4">Загрузка...</div>
        </div>
    </div>
</div>

<!-- Create Menu Modal -->
<div id="create-menu-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Создать меню</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Название</label>
                <input type="text" id="menu-name" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="Главное меню">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Расположение</label>
                <select id="menu-location" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
                    <option value="header">Header</option>
                    <option value="footer">Footer</option>
                    <option value="sidebar">Sidebar</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('create-menu-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="createMenu()" class="flex-1 px-4 py-2 bg-purple-500 hover:bg-purple-600 rounded-lg transition-colors">Создать</button>
        </div>
    </div>
</div>

<!-- Add Menu Item Modal -->
<div id="add-menu-item-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Добавить пункт меню</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Название</label>
                <input type="text" id="menu-item-title" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">URL</label>
                <input type="text" id="menu-item-url" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="/about">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Иконка (Lucide)</label>
                <input type="text" id="menu-item-icon" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="home">
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('add-menu-item-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="addMenuItem()" class="flex-1 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors">Добавить</button>
        </div>
    </div>
</div>

<script>
function loadPages() {
    const status = $('#page-status-filter').val();
    $.get('/api/admin/cms.php?action=get_pages&status=' + status, function(response) {
        if (response.success) {
            renderPages(response.pages);
        }
    });
}

function renderPages(pages) {
    if (pages.length === 0) {
        $('#pages-list').html('<div class="text-center text-slate-400 py-4">Нет страниц</div>');
        return;
    }
    
    let html = '';
    pages.forEach(p => {
        const statusColors = {
            published: 'bg-emerald-500/20 text-emerald-300',
            draft: 'bg-yellow-500/20 text-yellow-300',
            archived: 'bg-slate-500/20 text-slate-300'
        };
        
        html += `
            <div class="flex items-center justify-between p-4 bg-slate-800/30 rounded-lg hover:bg-slate-800/50 transition-colors">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">${p.title}</span>
                        <span class="px-2 py-0.5 rounded text-xs ${statusColors[p.status]}">${p.status}</span>
                    </div>
                    <div class="text-sm text-slate-500">/${p.slug} • ${p.views_count} просмотров</div>
                </div>
                <div class="flex gap-2">
                    <a href="/${p.slug}" target="_blank" class="p-2 text-blue-400 hover:bg-blue-500/20 rounded-lg"><i data-lucide="external-link" class="w-4 h-4"></i></a>
                    <button onclick="editPage(${p.id})" class="p-2 text-slate-400 hover:bg-slate-700 rounded-lg"><i data-lucide="edit" class="w-4 h-4"></i></button>
                    <button onclick="deletePage(${p.id})" class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                </div>
            </div>
        `;
    });
    
    $('#pages-list').html(html);
    lucide.createIcons();
}

function showPageEditor(page = null) {
    $('#page-id').val(page ? page.id : '');
    $('#page-title').val(page ? page.title : '');
    $('#page-slug').val(page ? page.slug : '');
    $('#page-content').val(page ? page.content : '');
    $('#page-status').val(page ? page.status : 'draft');
    $('#page-meta-title').val(page ? page.meta_title : '');
    $('#page-meta-desc').val(page ? page.meta_description : '');
    $('#page-meta-keywords').val(page ? page.meta_keywords : '');
    $('#editor-title').text(page ? 'Редактировать страницу' : 'Новая страница');
    $('#page-editor').removeClass('hidden');
    lucide.createIcons();
}

function editPage(id) {
    $.get('/api/admin/cms.php?action=get_page&id=' + id, function(response) {
        if (response.success) {
            showPageEditor(response.page);
        }
    });
}

function savePage() {
    const id = $('#page-id').val();
    const data = {
        action: id ? 'update_page' : 'create_page',
        id: id,
        title: $('#page-title').val(),
        slug: $('#page-slug').val(),
        content: $('#page-content').val(),
        status: $('#page-status').val(),
        meta_title: $('#page-meta-title').val(),
        meta_description: $('#page-meta-desc').val(),
        meta_keywords: $('#page-meta-keywords').val()
    };
    
    $.post('/api/admin/cms.php', data, function(response) {
        if (response.success) {
            $('#page-editor').addClass('hidden');
            loadPages();
            showToast('Страница сохранена', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function deletePage(id) {
    if (!confirm('Удалить эту страницу?')) return;
    $.post('/api/admin/cms.php', { action: 'delete_page', id: id }, function(response) {
        if (response.success) {
            loadPages();
            showToast('Страница удалена', 'success');
        }
    });
}

// Menus
function loadMenus() {
    $.get('/api/admin/cms.php?action=get_menus', function(response) {
        if (response.success) {
            renderMenus(response.menus);
        }
    });
}

function renderMenus(menus) {
    let html = '';
    menus.forEach(m => {
        html += `
            <div class="flex items-center justify-between p-3 bg-slate-800/30 rounded-lg cursor-pointer hover:bg-slate-800/50" onclick="editMenu(${m.id}, '${m.name}')">
                <div>
                    <div class="font-medium">${m.name}</div>
                    <div class="text-xs text-slate-500">${m.location} • ${m.items_count} пунктов</div>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500"></i>
            </div>
        `;
    });
    $('#menus-list').html(html);
    lucide.createIcons();
}

function editMenu(menuId, menuName) {
    $('#current-menu-id').val(menuId);
    $('#menu-editor-title').text('Меню: ' + menuName);
    
    $.get('/api/admin/cms.php?action=get_menu_items&menu_id=' + menuId, function(response) {
        if (response.success) {
            renderMenuItems(response.items);
            $('#menu-items-editor').removeClass('hidden');
        }
    });
}

function renderMenuItems(items) {
    if (items.length === 0) {
        $('#menu-items-list').html('<div class="text-center text-slate-400 py-4">Нет пунктов меню</div>');
        return;
    }
    
    let html = '';
    items.forEach(item => {
        html += `
            <div class="flex items-center justify-between p-3 bg-slate-800/30 rounded-lg">
                <div class="flex items-center gap-3">
                    ${item.icon ? `<i data-lucide="${item.icon}" class="w-4 h-4 text-slate-400"></i>` : ''}
                    <div>
                        <div class="font-medium">${item.title}</div>
                        <div class="text-xs text-slate-500">${item.url || item.page_title || '-'}</div>
                    </div>
                </div>
                <div class="flex gap-1">
                    <button onclick="toggleMenuItem(${item.id}, ${item.is_active})" class="p-1.5 ${item.is_active ? 'text-emerald-400' : 'text-slate-500'} hover:bg-slate-700 rounded">
                        <i data-lucide="${item.is_active ? 'eye' : 'eye-off'}" class="w-4 h-4"></i>
                    </button>
                    <button onclick="deleteMenuItem(${item.id})" class="p-1.5 text-red-400 hover:bg-red-500/20 rounded">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        `;
    });
    $('#menu-items-list').html(html);
    lucide.createIcons();
}

function showCreateMenuModal() {
    $('#menu-name, #menu-location').val('');
    $('#create-menu-modal').removeClass('hidden');
}

function createMenu() {
    $.post('/api/admin/cms.php', {
        action: 'create_menu',
        name: $('#menu-name').val(),
        location: $('#menu-location').val()
    }, function(response) {
        if (response.success) {
            closeModal('create-menu-modal');
            loadMenus();
            showToast('Меню создано', 'success');
        }
    });
}

function showAddMenuItemModal() {
    $('#menu-item-title, #menu-item-url, #menu-item-icon').val('');
    $('#add-menu-item-modal').removeClass('hidden');
}

function addMenuItem() {
    $.post('/api/admin/cms.php', {
        action: 'create_menu_item',
        menu_id: $('#current-menu-id').val(),
        title: $('#menu-item-title').val(),
        url: $('#menu-item-url').val(),
        icon: $('#menu-item-icon').val()
    }, function(response) {
        if (response.success) {
            closeModal('add-menu-item-modal');
            editMenu($('#current-menu-id').val(), $('#menu-editor-title').text().replace('Меню: ', ''));
            showToast('Пункт добавлен', 'success');
        }
    });
}

function deleteMenuItem(id) {
    if (!confirm('Удалить этот пункт?')) return;
    $.post('/api/admin/cms.php', { action: 'delete_menu_item', id: id }, function(response) {
        if (response.success) {
            editMenu($('#current-menu-id').val(), $('#menu-editor-title').text().replace('Меню: ', ''));
        }
    });
}

function toggleMenuItem(id, currentState) {
    $.post('/api/admin/cms.php', {
        action: 'update_menu_item',
        id: id,
        is_active: currentState ? 0 : 1
    }, function(response) {
        if (response.success) {
            editMenu($('#current-menu-id').val(), $('#menu-editor-title').text().replace('Меню: ', ''));
        }
    });
}

$('#page-status-filter').on('change', loadPages);
</script>
