@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Manajemen Rekomendasi</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Kelola Video, PDF, Gambar, & Program Olahraga</p>
    </div>
</div>

<!-- Category Selector (Responsive Select) -->
<div class="mb-10">
    <div class="relative group">
        <label class="block text-[10px] font-black text-primary-800/40 uppercase tracking-[0.2em] mb-3 ml-2">Pilih Tipe Konten</label>
        <div class="relative">
            <select onchange="switchTab(this.value)" class="w-full appearance-none bg-white border-2 border-primary-800 text-primary-800 font-black text-sm px-8 py-5 rounded-[1.5rem] shadow-xl shadow-primary-900/5 focus:border-primary-800 focus:ring-4 focus:ring-primary-800/5 transition-all outline-none uppercase tracking-widest cursor-pointer">
                <option value="video">Video Edukasi</option>
                <option value="materi">Materi PDF</option>
                <option value="gambar">Gambar Edukasi</option>
                <option value="olahraga">Program Olahraga</option>
            </select>
            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-primary-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
    <div class="flex items-center gap-4">
        <div class="w-2 h-10 bg-primary-800 rounded-full"></div>
        <h2 id="sectionTitle" class="text-2xl font-black text-black tracking-tight uppercase">Daftar Video</h2>
    </div>
    <button onclick="openAddModal()" class="w-full sm:w-auto bg-primary-800 hover:bg-black text-white px-8 py-5 rounded-[1.5rem] font-black text-xs flex items-center justify-center gap-3 transition-all shadow-xl shadow-primary-900/20 uppercase tracking-widest">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Konten
    </button>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 overflow-hidden mb-10">
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto p-8">
        <table class="w-full text-left">
            <thead class="text-primary-400 uppercase text-xs font-black tracking-[0.2em] border-b-2 border-primary-50">
                <tr>
                    <th class="px-6 py-6">Informasi Konten</th>
                    <th class="px-6 py-6 text-center">Target TD</th>
                    <th class="px-6 py-6 text-center">Target GAD</th>
                    <th class="px-6 py-6 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody id="contentTable" class="divide-y-2 divide-primary-50">
                <tr><td colspan="4" class="px-6 py-12 text-center text-primary-300 font-bold italic">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="contentCards" class="md:hidden divide-y-2 divide-primary-50">
        <div class="p-8 text-center text-primary-300 font-bold italic uppercase tracking-widest">Memuat data...</div>
    </div>
</div>

<!-- Empty -->
<div id="emptyState" class="hidden text-center py-8">
    {{-- <p class="text-gray-400 text-sm">Belum ada data</p> --}}
</div>

<!-- Loading -->
<div id="loadingState" class="hidden text-center py-8">
    <div class="animate-spin w-6 h-6 border-2 border-primary-600 border-t-transparent rounded-full mx-auto"></div>
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
                <p id="fileHelp" class="text-[10px] text-gray-400 mt-1"></p>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">TD</label>
                    <select id="kategori_td" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
                        <option value="normal">Normal</option>
                        <option value="pre_hipertensi">Pra-Hipertensi</option>
                        <option value="hipertensi">Hipertensi</option>
                        <option value="semua">Semua</option>
                        <option value="tidak_salah_satunya">Tidak Salah Satunya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">GAD</label>
                    <select id="kategori_gad" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
                        <option value="normal">Normal</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                        <option value="semua">Semua</option>
                        <option value="tidak_salah_satunya">Tidak Salah Satunya</option>
                    </select>
                </div>
            </div>

            <button type="submit" id="btnSave" class="w-full bg-primary-800 text-white py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all hover:bg-black disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <span id="btnSaveText">Simpan Konten</span>
                <div id="btnSaveLoading" class="hidden animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></div>
            </button>
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
    loadData();
}

function getTitle() {
    const titles = { video: 'Daftar Video', materi: 'Daftar PDF', gambar: 'Daftar Gambar', olahraga: 'Daftar Olahraga' };
    return titles[currentTab] || 'Daftar';
}

async function loadData() {
    const tbody = document.getElementById('contentTable');
    const cards = document.getElementById('contentCards');
    const empty = document.getElementById('emptyState');
    const loading = document.getElementById('loadingState');
    
    document.getElementById('sectionTitle').textContent = getTitle();
    if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-12 text-center text-primary-300 font-bold italic">Memuat data...</td></tr>';
    if (cards) cards.innerHTML = '<div class="p-8 text-center text-primary-300 font-bold italic">Memuat data...</div>';
    empty.classList.add('hidden');
    loading.classList.remove('hidden');
    
    const endpoint = '/' + currentTab;
    const res = await apiCall(endpoint);
    
    loading.classList.add('hidden');
    
    if (res && res.success) {
        if (res.data.length === 0) {
            empty.classList.remove('hidden');
            if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">Tidak ada data</td></tr>';
            if (cards) cards.innerHTML = '<div class="p-8 text-center text-gray-400">Tidak ada data</div>';
            return;
        }
        
        const rowsHtml = res.data.map(item => {
            let icon = '';
            if (currentTab === 'video') icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>';
            else if (currentTab === 'materi') icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';
            else if (currentTab === 'gambar') icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
            else icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v8H2z"/><line x1="10" y1="8" x2="10" y2="16"/></svg>';

            return `
            <tr class="hover:bg-primary-50/50 transition-colors group">
                <td class="px-6 py-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-primary-50 rounded-xl text-primary-800 flex-shrink-0 group-hover:scale-110 transition-transform">
                            ${icon}
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-black text-lg tracking-tight leading-tight group-hover:text-primary-800 transition-colors break-words">${item.judul || item.nama_olahraga}</p>
                            ${item.link_embed ? `<p class="text-[10px] text-primary-400 font-bold truncate mt-1 italic">${item.link_embed}</p>` : ''}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-6 text-center">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100">${item.kategori_td.replace('_', ' ')}</span>
                </td>
                <td class="px-6 py-6 text-center">
                    <span class="px-3 py-1 bg-amber-50 text-amber-800 rounded-lg text-[10px] font-black uppercase tracking-widest border border-amber-100">${item.kategori_gad}</span>
                </td>
                <td class="px-6 py-6 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="p-3 bg-primary-50 hover:bg-primary-800 hover:text-white rounded-xl transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="deleteItem(${item.id})" class="p-3 bg-orange-50 hover:bg-orange-600 hover:text-white rounded-xl transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
        }).join('');

        const cardsHtml = res.data.map(item => {
            let icon = '';
            if (currentTab === 'video') icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>';
            else if (currentTab === 'materi') icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';
            else if (currentTab === 'gambar') icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
            else icon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v8H2z"/><line x1="10" y1="8" x2="10" y2="16"/></svg>';

            return `
            <div class="p-6 bg-white hover:bg-primary-50/20 transition-all group">
                <div class="flex items-start gap-4 mb-4">
                    <div class="p-4 bg-primary-50 rounded-2xl text-primary-800 flex-shrink-0">
                        ${icon}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex justify-between items-start">
                            <p class="font-black text-black text-xl tracking-tight leading-tight group-hover:text-primary-800 transition-colors break-words">${item.judul || item.nama_olahraga}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-800 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100">TD: ${item.kategori_td.replace('_', ' ')}</span>
                            <span class="px-3 py-1 bg-amber-50 text-amber-800 rounded-lg text-[9px] font-black uppercase tracking-widest border border-amber-100">GAD: ${item.kategori_gad}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 pt-4 border-t border-primary-50">
                    <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="flex-1 flex items-center justify-center gap-2 py-3 bg-primary-50 text-primary-800 rounded-xl text-xs font-black uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </button>
                    <button onclick="deleteItem(${item.id})" class="flex-1 flex items-center justify-center gap-2 py-3 bg-orange-50 text-orange-600 rounded-xl text-xs font-black uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Hapus
                    </button>
                </div>
            </div>`;
        }).join('');

        if (tbody) tbody.innerHTML = rowsHtml;
        if (cards) cards.innerHTML = cardsHtml;
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
    const linkField = document.getElementById('linkField');
    const fileField = document.getElementById('fileField');
    const fileInput = document.getElementById('fileInput');
    const fileHelp = document.getElementById('fileHelp');
    
    linkField.classList.add('hidden');
    fileField.classList.add('hidden');
    fileInput.removeAttribute('accept');
    fileHelp.textContent = '';
    
    if (currentTab === 'video') {
        linkField.classList.remove('hidden');
    } else if (currentTab === 'materi') {
        fileField.classList.remove('hidden');
        fileInput.setAttribute('accept', 'application/pdf');
        fileHelp.textContent = 'Hanya file PDF (Maks. 10MB)';
    } else if (currentTab === 'gambar') {
        fileField.classList.remove('hidden');
        fileInput.setAttribute('accept', 'image/*');
        fileHelp.textContent = 'Format gambar: JPG, PNG, GIF (Maks. 5MB)';
    }
}

async function saveData(e) {
    e.preventDefault();
    const btnSave = document.getElementById('btnSave');
    const btnSaveText = document.getElementById('btnSaveText');
    const btnSaveLoading = document.getElementById('btnSaveLoading');

    // Start Loading
    btnSave.disabled = true;
    btnSaveText.textContent = 'Menyimpan...';
    btnSaveLoading.classList.remove('hidden');

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
    
    // video & olahraga update routes use PUT; use _method spoofing for FormData
    if (editMode && (currentTab === 'video' || currentTab === 'olahraga')) {
        formData.append('_method', 'PUT');
    }

    try {
        const currentToken = localStorage.getItem('token');
        const res = await fetch(API_URL + url, {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + currentToken, 'Accept': 'application/json' },
            body: formData
        });
        
        const result = await res.json();
        if (result.success) {
            showAlert('Konten berhasil disimpan', 'success');
            closeFormModal();
            loadData();
        } else {
            showAlert(result.message || 'Gagal menyimpan data', 'error');
        }
    } catch (err) {
        console.error(err);
        showAlert('Terjadi kesalahan koneksi', 'error');
    } finally {
        // End Loading
        btnSave.disabled = false;
        btnSaveText.textContent = 'Simpan Konten';
        btnSaveLoading.classList.add('hidden');
    }
}

async function deleteItem(id) {
    if (!confirm('Hapus?')) return;
    
    const endpoint = currentTab === 'olahraga' ? '/admin/olahraga' : '/admin/' + currentTab;
    const res = await apiCall(endpoint + '/' + id, 'DELETE');
    
    if (res && res.success) {
        showAlert('Konten berhasil dihapus', 'success');
        loadData();
    } else {
        showAlert(res?.message || 'Gagal menghapus konten');
    }
}

document.addEventListener('DOMContentLoaded', loadData);
</script>
@endsection