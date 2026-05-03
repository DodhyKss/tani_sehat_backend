@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Manajemen Kader & Warga</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Atur Relasi & Penugasan Kader Terhadap Warga</p>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 mb-10 overflow-hidden">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-10">
        <div class="relative flex-1 max-w-xl">
            <input type="text" id="searchWarga" placeholder="Cari Nama Warga atau NIK..." 
                class="w-full pl-14 pr-6 py-4 bg-primary-50/50 border-2 border-primary-800 focus:border-primary-600 focus:bg-white rounded-2xl transition-all font-black text-black appearance-none outline-none">
            <svg class="w-6 h-6 text-primary-400 absolute left-5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <button onclick="openAssignModal()" class="bg-primary-800 hover:bg-black text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-lg shadow-primary-900/20 uppercase tracking-widest flex items-center justify-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
            Tugaskan Kader
        </button>
    </div>

    <div class="hidden md:block overflow-x-auto -mx-10 px-10">
        <table class="w-full text-left">
            <thead class="text-primary-400 uppercase text-xs font-black tracking-[0.2em] border-b-2 border-primary-50">
                <tr>
                    <th class="px-6 py-6">Informasi Warga</th>
                    <th class="px-6 py-6">NIK / Identitas</th>
                    <th class="px-6 py-6">No. Telepon</th>
                    <th class="px-6 py-6">Kader Penanggung Jawab</th>
                    <th class="px-6 py-6 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody id="wargaTable" class="divide-y-2 divide-primary-50">
                <tr><td colspan="5" class="px-6 py-12 text-center text-primary-300 font-bold italic">Memuat data warga...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="wargaCards" class="md:hidden divide-y-2 divide-primary-50">
        <div class="p-8 text-center text-primary-300 font-bold italic uppercase tracking-widest">Memuat data warga...</div>
    </div>
</div>

<div id="assignModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-primary-900/40 backdrop-blur-md" onclick="closeAssignModal()"></div>
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden border border-primary-100">
        <div class="p-10 border-b-2 border-primary-50 flex justify-between items-center bg-primary-50/30">
            <h3 class="text-2xl font-black text-black tracking-tight">Tugaskan Warga ke Kader</h3>
            <button onclick="closeAssignModal()" class="p-3 hover:bg-primary-100 rounded-2xl transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="assignForm" class="p-10 space-y-8">
            <div class="space-y-3">
                <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Pilih Warga</label>
                <select id="wargaSelect" class="w-full px-6 py-5 bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white rounded-2xl transition-all font-black text-black appearance-none outline-none" required>
                    <option value="">-- Pilih Warga TaniSehat --</option>
                </select>
            </div>
            <div class="space-y-3">
                <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Pilih Kader Penanggung Jawab</label>
                <select id="kaderSelect" class="w-full px-6 py-5 bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white rounded-2xl transition-all font-black text-black appearance-none outline-none" required>
                    <option value="">-- Pilih Kader Penanggung Jawab --</option>
                </select>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeAssignModal()" class="flex-1 px-8 py-5 border-2 border-primary-100 text-primary-800 rounded-2xl font-black transition-all hover:bg-primary-50 uppercase tracking-widest">BATAL</button>
                <button type="submit" class="flex-1 px-8 py-5 bg-primary-800 text-white rounded-2xl font-black transition-all hover:bg-black shadow-xl shadow-primary-900/20 uppercase tracking-widest">SIMPAN PENUGASAN</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let wargaList = [];
let kaderList = [];

async function loadData() {
    try {
        const [wargaRes, kaderRes] = await Promise.all([
            apiCall('/users/warga-kader'),
            apiCall('/kaders')
        ]);
        wargaList = wargaRes?.data || [];
        kaderList = kaderRes?.data || [];
        renderTable();
        populateSelects();
    } catch (e) { console.error(e); }
}

function renderTable(filter = '') {
    const tbody = document.getElementById('wargaTable');
    const cards = document.getElementById('wargaCards');
    const filtered = wargaList.filter(w => 
        !filter || w.nama_lengkap?.toLowerCase().includes(filter.toLowerCase()) || w.nik?.includes(filter)
    );
    
    if (!filtered.length) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        if (cards) cards.innerHTML = '<div class="p-8 text-center text-gray-500 font-bold uppercase tracking-widest">Tidak ada data</div>';
        return;
    }
    
    tbody.innerHTML = filtered.map(w => `
        <tr class="hover:bg-primary-50/50 transition-colors">
            <td class="px-6 py-6 font-black text-black text-xl">${w.nama_lengkap || '-'}</td>
            <td class="px-6 py-6 font-bold text-primary-400 tracking-wider">${w.nik || '-'}</td>
            <td class="px-6 py-6 font-bold text-primary-800">${w.no_hp || '-'}</td>
            <td class="px-6 py-6">
                ${w.kader_nama 
                    ? `<span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">${w.kader_nama}</span>` 
                    : `<span class="px-4 py-1.5 bg-primary-50 text-primary-300 rounded-lg text-[10px] font-black uppercase tracking-widest">Belum Ada Kader</span>`}
            </td>
            <td class="px-6 py-6 text-center">
                ${w.kader_id 
                    ? `<button onclick="removeKader(${w.id})" class="px-4 py-2 bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white rounded-xl font-black text-xs transition-all uppercase tracking-widest">Hapus Penugasan</button>`
                    : `<button onclick="selectWarga(${w.id})" class="px-4 py-2 bg-primary-800 text-white hover:bg-black rounded-xl font-black text-xs transition-all uppercase tracking-widest shadow-lg shadow-primary-900/20">Tugaskan</button>`}
            </td>
        </tr>
    `).join('');

    cards.innerHTML = filtered.map(w => `
        <div class="p-6 bg-white hover:bg-primary-50/20 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="font-black text-black text-xl tracking-tight">${w.nama_lengkap || '-'}</p>
                    <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mt-0.5">NIK: ${w.nik || '-'}</p>
                </div>
                ${w.kader_nama 
                    ? `<span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">${w.kader_nama}</span>` 
                    : `<span class="px-3 py-1.5 bg-primary-50 text-primary-300 rounded-lg text-[9px] font-black uppercase tracking-widest">Belum Ditugaskan</span>`}
            </div>
            <div class="flex justify-between items-center bg-primary-50/50 p-4 rounded-2xl border border-primary-100">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-primary-400 uppercase tracking-widest">No. Telepon</span>
                    <span class="text-base font-bold text-primary-800">${w.no_hp || '-'}</span>
                </div>
                <div class="flex gap-2">
                    ${w.kader_id 
                        ? `<button onclick="removeKader(${w.id})" class="p-3 bg-white border border-orange-100 text-orange-600 rounded-xl shadow-sm">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                           </button>`
                        : `<button onclick="selectWarga(${w.id})" class="p-3 bg-primary-800 text-white rounded-xl shadow-lg">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
                           </button>`}
                </div>
            </div>
        </div>
    `).join('');
}

function populateSelects() {
    const wargaSelect = document.getElementById('wargaSelect');
    const kaderSelect = document.getElementById('kaderSelect');
    
    wargaSelect.innerHTML = '<option value="">-- Pilih Warga --</option>' + 
        wargaList.filter(w => !w.kader_id).map(w => `<option value="${w.id}">${w.nama_lengkap}</option>`).join('');
    
    kaderSelect.innerHTML = '<option value="">-- Pilih Kader --</option>' + 
        kaderList.map(k => `<option value="${k.id}">${k.nama_lengkap}</option>`).join('');
}

function openAssignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
    document.getElementById('assignModal').classList.add('flex');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
    document.getElementById('assignModal').classList.remove('flex');
    document.getElementById('assignForm').reset();
}

function selectWarga(wargaId) {
    document.getElementById('wargaSelect').value = wargaId;
    openAssignModal();
}

async function removeKader(wargaId) {
    if (!confirm('Hapus warga dari kader?')) return;
    try {
        const res = await apiCall(`/users/remove-kader/${wargaId}`, 'DELETE');
        if (res && res.success) {
            showAlert('Penugasan kader berhasil dihapus', 'success');
        } else {
            showAlert(res?.message || 'Gagal menghapus penugasan kader');
        }
        loadData();
    } catch (e) { console.error(e); showAlert('Gagal menghapus penugasan kader'); }
}

document.getElementById('assignForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const wargaId = document.getElementById('wargaSelect').value;
    const kaderId = document.getElementById('kaderSelect').value;
    
    if (!wargaId || !kaderId) return showAlert('Pilih warga dan kader terlebih dahulu');
    
    try {
        const res = await apiCall('/users/assign-kader', 'POST', {
            warga_id: parseInt(wargaId),
            kader_id: parseInt(kaderId)
        });
        if (res && res.success) {
            showAlert('Warga berhasil ditugaskan ke kader', 'success');
        } else {
            showAlert(res?.message || 'Gagal menugaskan warga ke kader');
        }
        closeAssignModal();
        loadData();
    } catch (e) { console.error(e); showAlert('Gagal menugaskan warga ke kader'); }
});

document.getElementById('searchWarga').addEventListener('input', (e) => {
    renderTable(e.target.value);
});

document.addEventListener('DOMContentLoaded', loadData);
</script>
@endsection