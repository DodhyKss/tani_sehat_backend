@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Manajemen Rekomendasi</h1>
        <p class="text-gray-500 text-sm">Kelola video, materi PDF, gambar edukasi, dan olahraga</p>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
    <button onclick="switchTab('video')" class="tab-btn px-6 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap active bg-primary-600 text-white shadow-lg shadow-primary-100" id="tab-video">Video YT</button>
    <button onclick="switchTab('materi')" class="tab-btn px-6 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap bg-white text-gray-600 border border-gray-100" id="tab-materi">Materi PDF</button>
    <button onclick="switchTab('gambar')" class="tab-btn px-6 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap bg-white text-gray-600 border border-gray-100" id="tab-gambar">Gambar Edukasi</button>
    <button onclick="switchTab('olahraga')" class="tab-btn px-6 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap bg-white text-gray-600 border border-gray-100" id="tab-olahraga">Olahraga</button>
</div>

<!-- Content Area -->
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h2 id="sectionTitle" class="text-xl font-bold text-gray-800">Daftar Video</h2>
        <button onclick="openAddModal()" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-primary-100">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Baru
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black tracking-widest">
                <tr id="tableHeaders">
                    <!-- Headers will change based on tab -->
                </tr>
            </thead>
            <tbody id="dataTable" class="divide-y divide-gray-50">
                <!-- Data will load here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeFormModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Item</h3>
            <button onclick="closeFormModal()" class="p-2 hover:bg-gray-100 rounded-xl transition">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="mainForm" class="p-8 space-y-5" onsubmit="saveData(event)">
            <input type="hidden" id="itemId">
            
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Judul / Nama</label>
                <input type="text" id="title" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 transition outline-none">
            </div>

            <div id="linkField" class="hidden">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link Embed YouTube</label>
                <input type="text" id="link_embed" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 transition outline-none" placeholder="https://www.youtube.com/embed/...">
            </div>

            <div id="fileField" class="hidden">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Pilih File</label>
                <input type="file" id="fileInput" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 transition outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Kategori TD</label>
                    <select id="kategori_td" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 transition outline-none">
                        <option value="normal">Normal</option>
                        <option value="pre_hipertensi">Pra-Hipertensi</option>
                        <option value="hipertensi">Hipertensi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Kategori GAD-7</label>
                    <select id="kategori_gad" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 transition outline-none">
                        <option value="normal">Normal</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-2xl font-bold transition shadow-lg shadow-primary-100 flex items-center justify-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
            </div>
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
        btn.className = 'tab-btn px-6 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap bg-white text-gray-600 border border-gray-100';
    });
    document.getElementById(`tab-${tab}`).className = 'tab-btn px-6 py-2.5 rounded-xl font-bold text-sm transition whitespace-nowrap active bg-primary-600 text-white shadow-lg shadow-primary-100';
    
    updateTableHeaders();
    loadData();
}

function updateTableHeaders() {
    const headers = document.getElementById('tableHeaders');
    const title = document.getElementById('sectionTitle');
    
    let html = '';
    if (currentTab === 'video') {
        title.textContent = 'Daftar Video';
        html = `
            <th class="px-8 py-4 text-left">Judul</th>
            <th class="px-6 py-4 text-left">Kategori</th>
            <th class="px-6 py-4 text-right">Aksi</th>
        `;
    } else if (currentTab === 'materi') {
        title.textContent = 'Daftar Materi PDF';
        html = `
            <th class="px-8 py-4 text-left">Judul</th>
            <th class="px-6 py-4 text-left">Kategori</th>
            <th class="px-6 py-4 text-right">Aksi</th>
        `;
    } else if (currentTab === 'gambar') {
        title.textContent = 'Daftar Gambar Edukasi';
        html = `
            <th class="px-8 py-4 text-left">Judul</th>
            <th class="px-6 py-4 text-left">Kategori</th>
            <th class="px-6 py-4 text-right">Aksi</th>
        `;
    } else {
        title.textContent = 'Daftar Rekomendasi Olahraga';
        html = `
            <th class="px-8 py-4 text-left">Nama Olahraga</th>
            <th class="px-6 py-4 text-left">Kategori</th>
            <th class="px-6 py-4 text-right">Aksi</th>
        `;
    }
    headers.innerHTML = html;
}

async function loadData() {
    const tbody = document.getElementById('dataTable');
    tbody.innerHTML = '<tr><td colspan="3" class="px-8 py-12 text-center text-gray-500">Memuat data...</td></tr>';
    
    const endpoint = currentTab === 'olahraga' ? '/olahraga' : `/${currentTab}`;
    const res = await apiCall(endpoint);
    
    if (res && res.success) {
        if (res.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-8 py-12 text-center text-gray-500">Belum ada data</td></tr>';
            return;
        }
        
        tbody.innerHTML = res.data.map(item => `
            <tr class="hover:bg-gray-50/50 transition duration-200">
                <td class="px-8 py-5">
                    <p class="font-bold text-gray-800">${item.judul || item.nama_olahraga}</p>
                    ${item.link_embed ? `<p class="text-[10px] text-primary-500 font-medium truncate max-w-xs">${item.link_embed}</p>` : ''}
                </td>
                <td class="px-6 py-5">
                    <div class="flex flex-wrap gap-1">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[9px] font-black uppercase tracking-wider">TD: ${item.kategori_td}</span>
                        <span class="px-2 py-0.5 bg-purple-50 text-purple-600 rounded-md text-[9px] font-black uppercase tracking-wider">GAD: ${item.kategori_gad}</span>
                    </div>
                </td>
                <td class="px-6 py-5 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="p-2 text-amber-500 hover:bg-amber-50 rounded-xl transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="deleteItem(${item.id})" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
}

function openAddModal() {
    editMode = false;
    document.getElementById('modalTitle').textContent = `Tambah ${currentTab.toUpperCase()}`;
    document.getElementById('mainForm').reset();
    document.getElementById('itemId').value = '';
    
    toggleFields();
    document.getElementById('formModal').classList.remove('hidden');
}

function openEditModal(item) {
    editMode = true;
    document.getElementById('modalTitle').textContent = `Edit ${currentTab.toUpperCase()}`;
    document.getElementById('itemId').value = item.id;
    document.getElementById('title').value = item.judul || item.nama_olahraga;
    document.getElementById('link_embed').value = item.link_embed || '';
    document.getElementById('kategori_td').value = item.kategori_td;
    document.getElementById('kategori_gad').value = item.kategori_gad;
    
    toggleFields();
    document.getElementById('formModal').classList.remove('hidden');
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

    const endpoint = currentTab === 'olahraga' ? '/admin/olahraga' : `/admin/${currentTab}`;
    const url = editMode ? `${endpoint}/${id}` : endpoint;
    
    // For update, if there's a file, we must use POST but with _method PUT/PATCH if using standard Laravel
    // But I used specific POST routes for file updates in api.php
    
    try {
        const currentToken = localStorage.getItem('token');
        const res = await fetch(`${API_URL}${url}`, {
            method: 'POST', // Use POST for FormData support
            headers: {
                'Authorization': `Bearer ${currentToken}`,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await res.json();
        if (result.success) {
            showAlert('Data berhasil disimpan', 'success');
            closeFormModal();
            loadData();
        } else {
            showAlert(result.message || 'Gagal menyimpan data');
        }
    } catch (err) {
        console.error(err);
        showAlert('Terjadi kesalahan jaringan');
    }
}

async function deleteItem(id) {
    if (!confirm('Hapus item ini?')) return;
    
    const endpoint = currentTab === 'olahraga' ? '/admin/olahraga' : `/admin/${currentTab}`;
    const res = await apiCall(`${endpoint}/${id}`, 'DELETE');
    
    if (res && res.success) {
        showAlert('Data berhasil dihapus', 'success');
        loadData();
    } else {
        showAlert(res?.message || 'Gagal menghapus data');
    }
}

function closeFormModal() {
    document.getElementById('formModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    updateTableHeaders();
    loadData();
});
</script>
@endsection
