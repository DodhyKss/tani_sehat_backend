@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Manajemen Kader Warga</h1>
    <p class="text-gray-500 text-sm">Atur relasi kader dan warga</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
        <div class="relative flex-1 max-w-md">
            <input type="text" id="searchWarga" placeholder="Cari nama atau NIK..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <button onclick="openAssignModal()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl font-medium transition">
            + Assign Warga
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">Nama</th>
                    <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">NIK</th>
                    <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">No. HP</th>
                    <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">Kader</th>
                    <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody id="wargaTable">
                <tr><td colspan="5" class="py-8 text-center text-gray-500">Memuat...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div id="assignModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Assign Warga ke Kader</h3>
        <form id="assignForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Warga</label>
                <select id="wargaSelect" class="w-full p-2 border border-gray-200 rounded-xl" required>
                    <option value="">-- Pilih Warga --</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kader</label>
                <select id="kaderSelect" class="w-full p-2 border border-gray-200 rounded-xl" required>
                    <option value="">-- Pilih Kader --</option>
                </select>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeAssignModal()" class="px-4 py-2 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700">Simpan</button>
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
    const filtered = wargaList.filter(w => 
        !filter || w.nama_lengkap?.toLowerCase().includes(filter.toLowerCase()) || w.nik?.includes(filter)
    );
    
    if (!filtered.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = filtered.map(w => `
        <tr class="border-b border-gray-50 hover:bg-gray-50">
            <td class="py-3 px-2">${w.nama_lengkap || '-'}</td>
            <td class="py-3 px-2">${w.nik || '-'}</td>
            <td class="py-3 px-2">${w.no_hp || '-'}</td>
            <td class="py-3 px-2">
                ${w.kader_nama 
                    ? `<span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">${w.kader_nama}</span>` 
                    : `<span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Belum ada</span>`}
            </td>
            <td class="py-3 px-2">
                ${w.kader_id 
                    ? `<button onclick="removeKader(${w.id})" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>`
                    : `<button onclick="selectWarga(${w.id})" class="text-primary-600 hover:text-primary-800 text-sm">Assign</button>`}
            </td>
        </tr>
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
        await apiCall(`/users/remove-kader/${wargaId}`, 'DELETE');
        loadData();
    } catch (e) { console.error(e); alert('Gagal menghapus'); }
}

document.getElementById('assignForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const wargaId = document.getElementById('wargaSelect').value;
    const kaderId = document.getElementById('kaderSelect').value;
    
    if (!wargaId || !kaderId) return alert('Pilih warga dan kader');
    
    try {
        await apiCall('/users/assign-kader', 'POST', {
            warga_id: parseInt(wargaId),
            kader_id: parseInt(kaderId)
        });
        closeAssignModal();
        loadData();
    } catch (e) { console.error(e); alert('Gagal assign'); }
});

document.getElementById('searchWarga').addEventListener('input', (e) => {
    renderTable(e.target.value);
});

document.addEventListener('DOMContentLoaded', loadData);
</script>
@endsection