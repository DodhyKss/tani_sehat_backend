@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Dashboard Kader</h1>
    <p class="text-gray-500 text-sm">Monitoring kesehatan warga</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-3">
            <div class="p-1.5 md:p-2 bg-primary-50 rounded-lg"><svg class="w-4 h-4 md:w-6 md:h-6 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
        </div>
        <h3 class="text-gray-500 text-[10px] md:text-sm uppercase tracking-wider font-semibold">Warga Saya</h3>
        <div class="text-xl md:text-3xl font-bold text-primary-600" id="valWargaSaya">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-3">
            <div class="p-1.5 md:p-2 bg-green-50 rounded-lg"><svg class="w-4 h-4 md:w-6 md:h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
        </div>
        <h3 class="text-gray-500 text-[10px] md:text-sm uppercase tracking-wider font-semibold">Cek TD</h3>
        <div class="text-xl md:text-3xl font-bold text-green-600" id="valTd">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-3">
            <div class="p-1.5 md:p-2 bg-yellow-50 rounded-lg"><svg class="w-4 h-4 md:w-6 md:h-6 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
        </div>
        <h3 class="text-gray-500 text-[10px] md:text-sm uppercase tracking-wider font-semibold">Cek GAD7</h3>
        <div class="text-xl md:text-3xl font-bold text-yellow-600" id="valGad">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-3">
            <div class="p-1.5 md:p-2 bg-indigo-50 rounded-lg"><svg class="w-4 h-4 md:w-6 md:h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
        </div>
        <h3 class="text-gray-500 text-[10px] md:text-sm uppercase tracking-wider font-semibold">Chat Baru</h3>
        <div class="text-xl md:text-3xl font-bold text-indigo-600" id="valChat">-</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Warga Terbaru</h2>
    
    <!-- Table for Desktop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">No HP</th>
                    <th class="px-4 py-3 text-left">Status TD</th>
                    <th class="px-4 py-3 text-left">Status GAD</th>
                </tr>
            </thead>
            <tbody id="wargaTable" class="divide-y divide-gray-100">
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Memuat...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Cards for Mobile -->
    <div id="wargaCards" class="md:hidden space-y-3">
        <div class="text-center py-8 text-gray-500">Memuat...</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function getStatusTd(s, d) {
    if (s < 120 && d < 80) return { label: 'Normal', color: 'bg-green-100 text-green-700' };
    if (s <= 139 && d <= 89) return { label: 'Pra-Hipertensi', color: 'bg-yellow-100 text-yellow-700' };
    return { label: 'Hipertensi', color: 'bg-red-100 text-red-700' };
}

function getStatusGad(skor) {
    if (skor <= 4) return { label: 'Normal', color: 'bg-green-100 text-green-700' };
    if (skor <= 9) return { label: 'Ringan', color: 'bg-yellow-100 text-yellow-700' };
    return { label: 'Sedang-Tinggi', color: 'bg-red-100 text-red-700' };
}

async function loadDashboard() {
    try {
        const user = JSON.parse(localStorage.getItem('user'));
        const res = await apiCall(`/kader/dashboard?kader_id=${user.id}`);
        if (res && res.success) {
            document.getElementById('valWargaSaya').textContent = res.data.warga_count || 0;
            document.getElementById('valTd').textContent = res.data.td_today || 0;
            document.getElementById('valGad').textContent = res.data.gad_today || 0;
            document.getElementById('valChat').textContent = res.data.new_chat || 0;

            renderWarga(res.data.warga_terbaru || []);
        }
    } catch (e) { console.error(e); }
}



function renderWarga(warga) {
    const tbody = document.getElementById('wargaTable');
    const cards = document.getElementById('wargaCards');
    
    if (warga.length === 0) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        if (cards) cards.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada data</div>';
        return;
    }
    
    if (tbody) {
        tbody.innerHTML = warga.map(w => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">${w.nama_lengkap}</td>
                <td class="px-4 py-3">${w.no_hp}</td>
                <td class="px-4 py-3">${w.last_td ? `<span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusTd(w.last_td.systolic, w.last_td.diastolic).color}">${getStatusTd(w.last_td.systolic, w.last_td.diastolic).label}</span>` : '<span class="text-gray-400 text-xs">-</span>'}</td>
                <td class="px-4 py-3">${w.last_gad ? `<span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusGad(w.last_gad.skor).color}">${getStatusGad(w.last_gad.skor).label}</span>` : '<span class="text-gray-400 text-xs">-</span>'}</td>
            </tr>
        `).join('');
    }

    if (cards) {
        cards.innerHTML = warga.map(w => {
            const td = w.last_td ? getStatusTd(w.last_td.systolic, w.last_td.diastolic) : null;
            const gad = w.last_gad ? getStatusGad(w.last_gad.skor) : null;
            return `
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-gray-800">${w.nama_lengkap}</p>
                            <p class="text-xs text-gray-500">${w.no_hp}</p>
                        </div>
                        <div class="flex flex-col gap-1 items-end">
                            ${td ? `<span class="px-2 py-0.5 text-[10px] font-bold rounded-full ${td.color}">TD: ${td.label}</span>` : ''}
                            ${gad ? `<span class="px-2 py-0.5 text-[10px] font-bold rounded-full ${gad.color}">GAD: ${gad.label}</span>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
});
</script>
@endsection