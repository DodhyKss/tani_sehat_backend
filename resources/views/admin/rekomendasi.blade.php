@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-xl md:text-2xl font-bold text-gray-800">Rekomendasi</h1>
    <p class="text-gray-500 text-xs mt-1">Kelola video, PDF, gambar & olahraga</p>
</div>

<!-- Tabs -->
<div class="flex gap-2 mb-4 overflow-x-auto pb-2 -mx-4 px-4 md:mx-0 md:px-0 scrollbar-hide no-scrollbar">
    <button onclick="switchTab('video')" class="tab-btn px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap active bg-primary-600 text-white shadow-lg shadow-primary-100" id="tab-video">Video YT</button>
    <button onclick="switchTab('materi')" class="tab-btn px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap bg-white text-gray-600 border border-gray-100 shadow-sm" id="tab-materi">Materi PDF</button>
    <button onclick="switchTab('gambar')" class="tab-btn px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap bg-white text-gray-600 border border-gray-100 shadow-sm" id="tab-gambar">Gambar Edukasi</button>
    <button onclick="switchTab('olahraga')" class="tab-btn px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap bg-white text-gray-600 border border-gray-100 shadow-sm" id="tab-olahraga">Olahraga</button>
</div>

<!-- Header -->
<div class="flex items-center justify-between mb-4">
    <h2 id="sectionTitle" class="text-base font-bold text-gray-800">Daftar Video</h2>
    <button onclick="openAddModal()" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden sm:inline">Tambah</span>
    </button>
</div>

<!-- Card List (All Screens) -->
<div id="contentList" class="space-y-2">
    <!-- Loaded via JS -->
</div>

<!-- Empty -->
<div id="emptyState" class="hidden text-center py-8">
    <p class="text-gray-400 text-sm">Belum ada data</p>
</div>

<!-- Loading -->
<div id="loadingState" class="hidden text-center py-8">
    <div class="animate-spin w-6 h-6 border-2 border-primary-600 border-t-transparent rounded-full mx-auto"></div>
</div>
</div>

<!-- Modal -->
<div id="formModal" class="hidden fixed inset-0 z-50 flex items-end md:items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeFormModal()"></div>
    <div class="relative w-full md:max-w-lg bg-white rounded-t-3xl md:rounded-3xl shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 translate-y-0">
        <div class="sticky top-0 bg-white/80 backdrop-blur-md border-b p-4 flex items-center justify-between z-10">
            <h3 id="modalTitle" class="font-bold text-gray-800 text-lg">Tambah</h3>
            <button onclick="closeFormModal()" class="p-1.5 hover:bg-gray-100 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="mainForm" class="p-3 space-y-3" onsubmit="saveData(event)">
            <input type="hidden" id="itemId">
            
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Judul</label>
                <input type="text" id="title" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
            </div>

            <div id="linkField" class="hidden">
                <label class="block text-xs font-bold text-gray-500 mb-1">Link YouTube</label>
                <input type="text" id="link_embed" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
            </div>

            <div id="fileField" class="hidden">
                <label class="block text-xs font-bold text-gray-500 mb-1">File</label>
                <input type="file" id="fileInput" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">TD</label>
                    <select id="kategori_td" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
                        <option value="normal">Normal</option>
                        <option value="pre_hipertensi">Pra-Hipertensi</option>
                        <option value="hipertensi">Hipertensi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">GAD</label>
                    <select id="kategori_gad" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
                        <option value="normal">Normal</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-primary-600 text-white py-2 rounded-lg font-bold text-sm">Simpan</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentTab = 'video';
let editMode = false;

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.className = 'tab-btn px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap bg-white text-gray-600 border border-gray-100 shadow-sm';
    });
    document.getElementById('tab-' + tab).className = 'tab-btn px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap active bg-primary-600 text-white shadow-lg shadow-primary-100';
    loadData();
}

function getTitle() {
    const titles = { video: 'Daftar Video', materi: 'Daftar PDF', gambar: 'Daftar Gambar', olahraga: 'Daftar Olahraga' };
    return titles[currentTab] || 'Daftar';
}

async function loadData() {
    const list = document.getElementById('contentList');
    const empty = document.getElementById('emptyState');
    const loading = document.getElementById('loadingState');
    
    document.getElementById('sectionTitle').textContent = getTitle();
    list.innerHTML = '';
    empty.classList.add('hidden');
    loading.classList.remove('hidden');
    
    const endpoint = '/' + currentTab;
    const res = await apiCall(endpoint);
    
    loading.classList.add('hidden');
    
    if (res && res.success) {
        if (res.data.length === 0) {
            empty.classList.remove('hidden');
            return;
        }
        
        list.innerHTML = res.data.map(item => `
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm md:text-base leading-snug">${item.judul || item.nama_olahraga}</p>
                        ${item.link_embed ? `<p class="text-[10px] md:text-xs text-primary-600 font-medium truncate mt-1 bg-primary-50 px-2 py-0.5 rounded inline-block max-w-full">${item.link_embed}</p>` : ''}
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-[9px] font-black uppercase border border-blue-100">TD: ${item.kategori_td}</span>
                            <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-lg text-[9px] font-black uppercase border border-purple-100">GAD: ${item.kategori_gad}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="p-2 text-amber-500 bg-amber-50 hover:bg-amber-100 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="deleteItem(${item.id})" class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
}

function openAddModal() {
    editMode = false;
    document.getElementById('modalTitle').textContent = 'Tambah ' + currentTab.toUpperCase();
    document.getElementById('mainForm').reset();
    document.getElementById('itemId').value = '';
    toggleFields();
    openModal();
}

function openEditModal(item) {
    editMode = true;
    document.getElementById('modalTitle').textContent = 'Edit ' + currentTab.toUpperCase();
    document.getElementById('itemId').value = item.id;
    document.getElementById('title').value = item.judul || item.nama_olahraga;
    document.getElementById('link_embed').value = item.link_embed || '';
    document.getElementById('kategori_td').value = item.kategori_td;
    document.getElementById('kategori_gad').value = item.kategori_gad;
    toggleFields();
    openModal();
}

function openModal() {
    document.getElementById('formModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeFormModal() {
    document.getElementById('formModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function toggleFields() {
    document.getElementById('linkField').classList.add('hidden');
    document.getElementById('fileField').classList.add('hidden');
    
    if (currentTab === 'video') {
        document.getElementById('linkField').classList.remove('hidden');
    } else if (currentTab === 'materi' || currentTab === 'gambar') {
        document.getElementById('fileField').classList.remove('hidden');
    }
}

async function saveData(e) {
    e.preventDefault();
    const id = document.getElementById('itemId').value;
    const formData = new FormData();
    
    formData.append('kategori_td', document.getElementById('kategori_td').value);
    formData.append('kategori_gad', document.getElementById('kategori_gad').value);
    
    if (currentTab === 'video') {
        formData.append('judul', document.getElementById('title').value);
        formData.append('link_embed', document.getElementById('link_embed').value);
    } else if (currentTab === 'olahraga') {
        formData.append('nama_olahraga', document.getElementById('title').value);
    } else {
        formData.append('judul', document.getElementById('title').value);
        if (document.getElementById('fileInput').files[0]) {
            formData.append('file', document.getElementById('fileInput').files[0]);
        }
    }

    const endpoint = currentTab === 'olahraga' ? '/admin/olahraga' : '/admin/' + currentTab;
    const url = editMode ? endpoint + '/' + id : endpoint;
    
    try {
        const currentToken = localStorage.getItem('token');
        const res = await fetch(API_URL + url, {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + currentToken, 'Accept': 'application/json' },
            body: formData
        });
        
        const result = await res.json();
        if (result.success) {
            showAlert('Data disimpan', 'success');
            closeFormModal();
            loadData();
        } else {
            showAlert(result.message || 'Gagal');
        }
    } catch (err) {
        showAlert('Error');
    }
}

async function deleteItem(id) {
    if (!confirm('Hapus?')) return;
    
    const endpoint = currentTab === 'olahraga' ? '/admin/olahraga' : '/admin/' + currentTab;
    const res = await apiCall(endpoint + '/' + id, 'DELETE');
    
    if (res && res.success) {
        showAlert('Dihapus', 'success');
        loadData();
    }
}

document.addEventListener('DOMContentLoaded', loadData);
</script>
@endsection