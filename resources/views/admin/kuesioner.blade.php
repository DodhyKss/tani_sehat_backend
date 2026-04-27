@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Manajemen Kuesioner GAD7</h1>
        <p class="text-gray-500 text-sm">Kelola daftar pertanyaan tingkat kecemasan</p>
    </div>
    <button onclick="openAddModal()" class="flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3.5 rounded-2xl font-bold transition shadow-lg shadow-primary-100 active:scale-95">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span class="md:inline">Tambah Soal Baru</span>
    </button>
</div>

<div class="bg-white md:rounded-[2rem] rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 text-left w-16">No</th>
                    <th class="px-6 py-4 text-left">Pertanyaan / Soal</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="kuesionerTable" class="divide-y divide-gray-50">
                <tr>
                    <td colspan="3" class="px-8 py-12 text-center text-gray-400">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="kuesionerCards" class="md:hidden divide-y divide-gray-50">
        <div class="px-6 py-12 text-center text-gray-400">Memuat data...</div>
    </div>
</div>

<!-- Modal Form -->
<div id="kuesionerModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Soal</h3>
            <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-xl transition">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="kuesionerForm" class="p-8 space-y-5" onsubmit="saveData(event)">
            <input type="hidden" id="soalId">
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Bunyi Pertanyaan</label>
                <textarea id="soalText" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 transition outline-none min-h-[120px]" placeholder="Contoh: Merasa gugup, cemas atau gelisah"></textarea>
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-4 rounded-2xl font-bold transition">Batal</button>
                <button type="submit" class="flex-[2] bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-2xl font-bold transition shadow-lg shadow-primary-100">Simpan Soal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let editMode = false;

async function loadKuesioner() {
    const tbody = document.getElementById('kuesionerTable');
    const cards = document.getElementById('kuesionerCards');
    const res = await apiCall('/admin/kuesioner');
    
    if (res && res.success) {
        if (res.data.length === 0) {
            const emptyHtml = '<div class="px-8 py-12 text-center text-gray-400">Belum ada soal kuesioner</div>';
            if (tbody) tbody.innerHTML = `<tr><td colspan="3" class="text-center py-12">${emptyHtml}</td></tr>`;
            if (cards) cards.innerHTML = emptyHtml;
            return;
        }
        
        const rowsHtml = res.data.map((item, index) => `
            <tr class="hover:bg-gray-50/50 transition duration-200">
                <td class="px-8 py-5 font-bold text-gray-400">${index + 1}</td>
                <td class="px-6 py-5">
                    <p class="text-gray-800 font-medium leading-relaxed">${item.soal}</p>
                </td>
                <td class="px-8 py-5 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="p-2 text-amber-500 hover:bg-amber-50 rounded-xl transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="deleteSoal(${item.id})" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        const cardsHtml = res.data.map((item, index) => `
            <div class="p-5 flex flex-col gap-3">
                <div class="flex items-start justify-between gap-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-xs font-black text-gray-400">${index + 1}</span>
                    <p class="flex-1 text-sm text-gray-800 leading-relaxed font-medium">${item.soal}</p>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-50/50">
                    <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="flex items-center gap-2 px-4 py-2 text-amber-500 bg-amber-50 rounded-xl text-xs font-bold transition">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </button>
                    <button onclick="deleteSoal(${item.id})" class="flex items-center gap-2 px-4 py-2 text-rose-500 bg-rose-50 rounded-xl text-xs font-bold transition">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Hapus
                    </button>
                </div>
            </div>
        `).join('');

        if (tbody) tbody.innerHTML = rowsHtml;
        if (cards) cards.innerHTML = cardsHtml;
    }
}

function openAddModal() {
    editMode = false;
    document.getElementById('modalTitle').textContent = 'Tambah Soal GAD7';
    document.getElementById('kuesionerForm').reset();
    document.getElementById('soalId').value = '';
    document.getElementById('kuesionerModal').classList.remove('hidden');
}

function openEditModal(item) {
    editMode = true;
    document.getElementById('modalTitle').textContent = 'Edit Soal GAD7';
    document.getElementById('soalId').value = item.id;
    document.getElementById('soalText').value = item.soal;
    document.getElementById('kuesionerModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('kuesionerModal').classList.add('hidden');
}

async function saveData(e) {
    e.preventDefault();
    const id = document.getElementById('soalId').value;
    const soal = document.getElementById('soalText').value;
    
    const method = editMode ? 'PUT' : 'POST';
    const url = editMode ? `/admin/kuesioner/${id}` : '/admin/kuesioner';
    
    const res = await apiCall(url, method, { soal });
    
    if (res && res.success) {
        showAlert(editMode ? 'Soal diperbarui' : 'Soal ditambahkan', 'success');
        closeModal();
        loadKuesioner();
    } else {
        showAlert(res?.message || 'Gagal menyimpan soal');
    }
}

async function deleteSoal(id) {
    if (!confirm('Hapus soal ini?')) return;
    const res = await apiCall(`/admin/kuesioner/${id}`, 'DELETE');
    if (res && res.success) {
        showAlert('Soal dihapus', 'success');
        loadKuesioner();
    } else {
        showAlert(res?.message || 'Gagal menghapus soal');
    }
}

document.addEventListener('DOMContentLoaded', loadKuesioner);
</script>
@endsection
