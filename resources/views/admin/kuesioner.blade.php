@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Manajemen GAD-7</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Kelola Instrumen Evaluasi Kecemasan Warga</p>
    </div>
    <button onclick="openAddModal()" class="flex items-center justify-center gap-4 bg-primary-800 hover:bg-black text-white px-8 py-5 rounded-[2rem] font-black transition-all shadow-xl shadow-primary-900/20 uppercase tracking-widest text-sm active:scale-95">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>Tambah Soal Kuesioner</span>
    </button>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 overflow-hidden">
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="text-primary-400 uppercase text-xs font-black tracking-[0.2em] border-b-2 border-primary-50">
                <tr>
                    <th class="px-10 py-6 w-24">Urutan</th>
                    <th class="px-6 py-6">Bunyi Pertanyaan / Soal GAD-7</th>
                    <th class="px-10 py-6 text-right">Manajemen</th>
                </tr>
            </thead>
            <tbody id="kuesionerTable" class="divide-y-2 divide-primary-50">
                <tr><td colspan="3" class="px-10 py-12 text-center text-primary-300 font-bold italic">Memuat data kuesioner...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="kuesionerCards" class="md:hidden divide-y divide-gray-50">
        <div class="px-6 py-12 text-center text-gray-400">Memuat data...</div>
    </div>
</div>

<!-- Modal Form -->
<div id="kuesionerModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-primary-900/40 backdrop-blur-md" onclick="closeModal()"></div>
    <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-xl overflow-hidden transform transition-all border border-primary-100">
        <div class="p-10 border-b-2 border-primary-50 flex justify-between items-center bg-primary-50/30">
            <h3 id="modalTitle" class="text-2xl font-black text-black tracking-tight">Manajemen Soal GAD-7</h3>
            <button onclick="closeModal()" class="p-3 hover:bg-primary-100 rounded-2xl transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="kuesionerForm" class="p-10 space-y-8" onsubmit="saveData(event)">
            <input type="hidden" id="soalId">
            <div class="space-y-3">
                <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Deskripsi Pertanyaan</label>
                <textarea id="soalText" required class="w-full px-6 py-5 rounded-[1.5rem] bg-primary-50/50 border-2 border-primary-800 focus:border-primary-600 focus:bg-white transition-all font-bold text-black outline-none min-h-[160px] text-lg" placeholder="Contoh: Merasa gugup, cemas atau gelisah"></textarea>
            </div>
            <div class="pt-4 flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 bg-primary-50 hover:bg-primary-100 text-primary-800 py-5 rounded-2xl font-black transition-all uppercase tracking-widest text-xs">BATAL</button>
                <button type="submit" class="flex-[2] bg-primary-800 hover:bg-black text-white py-5 rounded-2xl font-black transition-all shadow-xl shadow-primary-900/20 uppercase tracking-widest text-xs">SIMPAN PERTANYAAN</button>
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
            <tr class="hover:bg-primary-50/50 transition-colors">
                <td class="px-10 py-6 font-black text-primary-300 text-xl">${index + 1}</td>
                <td class="px-6 py-6">
                    <p class="text-black font-bold text-xl leading-relaxed">${item.soal}</p>
                </td>
                <td class="px-10 py-6 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="p-4 text-primary-600 bg-primary-50 hover:bg-primary-100 rounded-2xl transition-all transform hover:scale-110">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="deleteSoal(${item.id})" class="p-4 text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-2xl transition-all transform hover:scale-110">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        const cardsHtml = res.data.map((item, index) => `
            <div class="p-8 flex flex-col gap-6">
                <div class="flex items-start gap-5">
                    <span class="flex-shrink-0 w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center text-lg font-black text-primary-300">${index + 1}</span>
                    <p class="flex-1 text-xl text-black leading-relaxed font-bold">${item.soal}</p>
                </div>
                <div class="flex justify-end gap-4 pt-6 border-t-2 border-primary-50">
                    <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&apos;")})' class="flex-1 flex items-center justify-center gap-3 px-6 py-4 text-primary-800 bg-primary-50 rounded-2xl text-xs font-black transition-all uppercase tracking-widest shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </button>
                    <button onclick="deleteSoal(${item.id})" class="flex-1 flex items-center justify-center gap-3 px-6 py-4 text-orange-600 bg-orange-50 rounded-2xl text-xs font-black transition-all uppercase tracking-widest shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
