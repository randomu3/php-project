<!-- TAB: FILE MANAGER -->
<div id="tab-files" class="tab-content hidden animate-fade-in">
    <div class="glass-panel p-6 rounded-2xl">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="folder" class="w-5 h-5 text-yellow-400"></i>
                    Файловый менеджер
                </h3>
                <span id="current-folder" class="text-sm text-slate-400">/uploads</span>
            </div>
            <div class="flex gap-2">
                <button onclick="showUploadModal()" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-lg text-sm transition-colors">
                    <i data-lucide="upload" class="w-4 h-4 inline"></i> Загрузить
                </button>
                <button onclick="showCreateFolderModal()" class="px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 rounded-lg text-sm transition-colors">
                    <i data-lucide="folder-plus" class="w-4 h-4 inline"></i> Папка
                </button>
            </div>
        </div>

        <!-- Storage Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-slate-800/30 rounded-lg p-4">
                <div class="text-sm text-slate-400">Всего файлов</div>
                <div id="files-count" class="text-2xl font-bold">-</div>
            </div>
            <div class="bg-slate-800/30 rounded-lg p-4">
                <div class="text-sm text-slate-400">Размер uploads</div>
                <div id="uploads-size" class="text-2xl font-bold">-</div>
            </div>
            <div class="bg-slate-800/30 rounded-lg p-4">
                <div class="text-sm text-slate-400">Текущая папка</div>
                <select id="folder-select" class="mt-1 bg-slate-700 border-0 rounded px-2 py-1 text-sm w-full">
                    <option value="uploads">uploads</option>
                </select>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div id="breadcrumb" class="flex items-center gap-2 mb-4 text-sm">
            <button onclick="navigateToFolder('uploads')" class="text-blue-400 hover:text-blue-300">uploads</button>
        </div>

        <!-- Files Grid -->
        <div id="files-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="text-center text-slate-400 py-8 col-span-full">Загрузка...</div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="upload-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Загрузить файл</h3>
        <form id="upload-form" enctype="multipart/form-data">
            <div class="border-2 border-dashed border-white/20 rounded-xl p-8 text-center hover:border-purple-500/50 transition-colors">
                <input type="file" id="file-input" name="file" class="hidden" accept="image/*,.pdf,.doc,.docx,.txt,.json">
                <label for="file-input" class="cursor-pointer">
                    <i data-lucide="upload-cloud" class="w-12 h-12 mx-auto text-slate-400 mb-3"></i>
                    <div class="text-slate-300">Нажмите или перетащите файл</div>
                    <div class="text-sm text-slate-500 mt-1">Макс. 10MB</div>
                </label>
            </div>
            <div id="selected-file" class="mt-3 text-sm text-slate-400 hidden"></div>
            <div id="upload-progress" class="mt-3 hidden">
                <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                    <div id="progress-bar" class="h-full bg-purple-500 transition-all" style="width: 0%"></div>
                </div>
            </div>
        </form>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('upload-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="uploadFile()" id="upload-btn" class="flex-1 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors" disabled>Загрузить</button>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div id="create-folder-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Создать папку</h3>
        <input type="text" id="new-folder-name" class="w-full bg-slate-800/50 border border-white/10 rounded-lg px-4 py-2 text-white" placeholder="Название папки">
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal('create-folder-modal')" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">Отмена</button>
            <button onclick="createFolder()" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">Создать</button>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="preview-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center" onclick="closeModal('preview-modal')">
    <img id="preview-image" src="" class="max-w-[90vw] max-h-[90vh] rounded-lg">
</div>

<script>
let currentFolder = 'uploads';

function loadFiles(folder = 'uploads') {
    currentFolder = folder;
    $('#current-folder').text('/' + folder);
    
    $.get('/api/admin/files.php?action=list&folder=' + folder, function(response) {
        if (response.success) {
            renderFiles(response.files);
            updateBreadcrumb(folder);
        }
    });
    
    // Load stats
    $.get('/api/admin/files.php?action=stats', function(response) {
        if (response.success) {
            $('#files-count').text(response.stats.file_count);
            $('#uploads-size').text(response.stats.total_size_formatted);
        }
    });
}

function renderFiles(files) {
    if (files.length === 0) {
        $('#files-grid').html('<div class="text-center text-slate-400 py-8 col-span-full">Папка пуста</div>');
        return;
    }
    
    let html = '';
    files.forEach(file => {
        if (file.is_dir) {
            html += `
                <div class="group bg-slate-800/30 rounded-xl p-4 hover:bg-slate-800/50 transition-colors cursor-pointer" ondblclick="navigateToFolder('${currentFolder}/${file.name}')">
                    <div class="flex flex-col items-center">
                        <i data-lucide="folder" class="w-12 h-12 text-yellow-400 mb-2"></i>
                        <div class="text-sm text-center truncate w-full">${file.name}</div>
                    </div>
                    <div class="hidden group-hover:flex justify-center gap-1 mt-2">
                        <button onclick="event.stopPropagation(); deleteFile('${file.path}')" class="p-1 text-red-400 hover:bg-red-500/20 rounded"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </div>
            `;
        } else if (file.is_image) {
            html += `
                <div class="group bg-slate-800/30 rounded-xl p-2 hover:bg-slate-800/50 transition-colors">
                    <div class="aspect-square rounded-lg overflow-hidden bg-slate-900 mb-2 cursor-pointer" onclick="previewImage('${file.path}')">
                        <img src="${file.path}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div class="text-xs truncate px-1">${file.name}</div>
                    <div class="text-xs text-slate-500 px-1">${file.size_formatted}</div>
                    <div class="hidden group-hover:flex justify-center gap-1 mt-2">
                        <button onclick="copyPath('${file.path}')" class="p-1 text-blue-400 hover:bg-blue-500/20 rounded"><i data-lucide="copy" class="w-4 h-4"></i></button>
                        <button onclick="deleteFile('${file.path}')" class="p-1 text-red-400 hover:bg-red-500/20 rounded"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </div>
            `;
        } else {
            const icon = getFileIcon(file.mime);
            html += `
                <div class="group bg-slate-800/30 rounded-xl p-4 hover:bg-slate-800/50 transition-colors">
                    <div class="flex flex-col items-center">
                        <i data-lucide="${icon}" class="w-12 h-12 text-slate-400 mb-2"></i>
                        <div class="text-sm text-center truncate w-full">${file.name}</div>
                        <div class="text-xs text-slate-500">${file.size_formatted}</div>
                    </div>
                    <div class="hidden group-hover:flex justify-center gap-1 mt-2">
                        <a href="${file.path}" download class="p-1 text-emerald-400 hover:bg-emerald-500/20 rounded"><i data-lucide="download" class="w-4 h-4"></i></a>
                        <button onclick="copyPath('${file.path}')" class="p-1 text-blue-400 hover:bg-blue-500/20 rounded"><i data-lucide="copy" class="w-4 h-4"></i></button>
                        <button onclick="deleteFile('${file.path}')" class="p-1 text-red-400 hover:bg-red-500/20 rounded"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </div>
            `;
        }
    });
    
    $('#files-grid').html(html);
    lucide.createIcons();
}

function getFileIcon(mime) {
    if (mime.includes('pdf')) return 'file-text';
    if (mime.includes('word') || mime.includes('document')) return 'file-text';
    if (mime.includes('json')) return 'file-json';
    if (mime.includes('text')) return 'file-text';
    return 'file';
}

function updateBreadcrumb(folder) {
    const parts = folder.split('/');
    let html = '';
    let path = '';
    
    parts.forEach((part, i) => {
        path += (i > 0 ? '/' : '') + part;
        const currentPath = path;
        html += `
            <button onclick="navigateToFolder('${currentPath}')" class="text-blue-400 hover:text-blue-300">${part}</button>
            ${i < parts.length - 1 ? '<span class="text-slate-500">/</span>' : ''}
        `;
    });
    
    $('#breadcrumb').html(html);
}

function navigateToFolder(folder) {
    loadFiles(folder);
}

function showUploadModal() {
    $('#file-input').val('');
    $('#selected-file').addClass('hidden');
    $('#upload-progress').addClass('hidden');
    $('#upload-btn').prop('disabled', true);
    $('#upload-modal').removeClass('hidden');
    lucide.createIcons();
}

$('#file-input').on('change', function() {
    const file = this.files[0];
    if (file) {
        $('#selected-file').text('Выбран: ' + file.name + ' (' + formatBytes(file.size) + ')').removeClass('hidden');
        $('#upload-btn').prop('disabled', false);
    }
});

function uploadFile() {
    const fileInput = $('#file-input')[0];
    if (!fileInput.files[0]) return;
    
    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('folder', currentFolder);
    formData.append('action', 'upload');
    
    $('#upload-progress').removeClass('hidden');
    
    $.ajax({
        url: '/api/admin/files.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total) * 100;
                    $('#progress-bar').css('width', percent + '%');
                }
            });
            return xhr;
        },
        success: function(response) {
            if (response.success) {
                closeModal('upload-modal');
                loadFiles(currentFolder);
                showToast('Файл загружен', 'success');
            } else {
                showToast(response.error, 'error');
            }
        }
    });
}

function showCreateFolderModal() {
    $('#new-folder-name').val('');
    $('#create-folder-modal').removeClass('hidden');
}

function createFolder() {
    const name = $('#new-folder-name').val().trim();
    if (!name) return;
    
    $.post('/api/admin/files.php', {
        action: 'create_folder',
        parent: currentFolder,
        name: name
    }, function(response) {
        if (response.success) {
            closeModal('create-folder-modal');
            loadFiles(currentFolder);
            showToast('Папка создана', 'success');
        } else {
            showToast(response.error, 'error');
        }
    });
}

function deleteFile(path) {
    if (!confirm('Удалить этот файл?')) return;
    
    $.post('/api/admin/files.php', { action: 'delete', path: path }, function(response) {
        if (response.success) {
            loadFiles(currentFolder);
            showToast('Файл удален', 'success');
        }
    });
}

function previewImage(path) {
    $('#preview-image').attr('src', path);
    $('#preview-modal').removeClass('hidden');
}

function copyPath(path) {
    navigator.clipboard.writeText(path);
    showToast('Путь скопирован', 'success');
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Load folders
$.get('/api/admin/files.php?action=get_folders', function(response) {
    if (response.success) {
        let options = '';
        response.folders.forEach(f => {
            options += `<option value="${f}">${f}</option>`;
        });
        $('#folder-select').html(options);
    }
});

$('#folder-select').on('change', function() {
    loadFiles($(this).val());
});
</script>
