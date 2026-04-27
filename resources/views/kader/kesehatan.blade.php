@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Data Kesehatan Binaan</h1>
    <p class="text-gray-500 text-sm">Detail riwayat kesehatan warga binaan saya</p>
</div>

<div class="flex flex-col gap-4 mb-6 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 tracking-widest">Pilih Warga</label>
            <select id="filterWarga" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm focus:ring-2 focus:ring-primary-500 transition-all outline-none">
                <option value="">Semua Warga Binaan</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 tracking-widest">Dari Tanggal</label>
            <input type="date" id="startDate" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm focus:ring-2 focus:ring-primary-500 transition-all outline-none">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 tracking-widest">Sampai Tanggal</label>
            <input type="date" id="endDate" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-sm focus:ring-2 focus:ring-primary-500 transition-all outline-none">
        </div>
    </div>
    <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-50">
        <button onclick="loadData()" class="flex-1 md:flex-none bg-primary-600 hover:bg-primary-700 text-white px-8 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-primary-200">Cari Data</button>
        <button onclick="resetFilter()" class="flex-1 md:flex-none bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-bold transition">Reset</button>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <p class="text-gray-400 text-[10px] font-black uppercase mb-1 tracking-widest">Total Cek TD</p>
        <div class="text-2xl md:text-3xl font-black text-gray-900" id="statTotalTd">0</div>
    </div>
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
        <p class="text-gray-400 text-[10px] font-black uppercase mb-1 tracking-widest">Total GAD7</p>
        <div class="text-2xl md:text-3xl font-black text-gray-900" id="statTotalGad">0</div>
    </div>
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm col-span-2 md:col-span-1">
        <p class="text-gray-400 text-[10px] font-black uppercase mb-1 tracking-widest">Warga Berisiko</p>
        <div class="text-2xl md:text-3xl font-black text-red-600" id="statRisiko">0</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        Riwayat Tekanan Darah
    </h2>
    
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3">Warga</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Systole</th>
                    <th class="px-4 py-3">Diastole</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody id="tdTable" class="divide-y divide-gray-100">
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="tdCards" class="md:hidden space-y-3">
        <div class="text-center py-8 text-gray-500">Memuat data...</div>
    </div>
    
    <div id="tdPagination" class="mt-4 flex justify-center gap-2"></div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-500"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        Riwayat GAD7
    </h2>
    
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3">Warga</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Skor</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody id="gadTable" class="divide-y divide-gray-100">
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="gadCards" class="md:hidden space-y-3">
        <div class="text-center py-8 text-gray-500">Memuat data...</div>
    </div>

    <div id="gadPagination" class="mt-4 flex justify-center gap-2"></div>
</div>
@endsection

@section('scripts')
<script>
function getStatusTd(systolic, diastolic) {
    if (systolic < 120 && diastolic < 80) return { label: 'Normal', color: 'bg-green-100 text-green-700' };
    if (systolic <= 139 && diastolic <= 89) return { label: 'Pra-Hipertensi', color: 'bg-yellow-100 text-yellow-700' };
    return { label: 'Hipertensi', color: 'bg-red-100 text-red-700' };
}

function getStatusGad(skor) {
    if (skor <= 4) return { label: 'Normal', color: 'bg-green-100 text-green-700' };
    if (skor <= 9) return { label: 'Ringan', color: 'bg-yellow-100 text-yellow-700' };
    return { label: 'Sedang-Tinggi', color: 'bg-red-100 text-red-700' };
}

async function loadData(page = 1) {
    const user = JSON.parse(localStorage.getItem('user'));
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    
    try {
        const [tdRes, gadRes] = await Promise.all([
            apiCall(`/tekanan-darah?kader_id=${user.id}&warga_id=${wargaId}&start_date=${start}&end_date=${end}&page=${page}`),
            apiCall(`/gad?kader_id=${user.id}&warga_id=${wargaId}&start_date=${start}&end_date=${end}&page=${page}`)
        ]);
        
        if (tdRes && tdRes.success) {
            renderTdTable(tdRes.data.data || []);
            renderPagination(tdRes.data, 'tdPagination', 'loadTdData');
            document.getElementById('statTotalTd').textContent = tdRes.data.total || 0;
        }
        if (gadRes && gadRes.success) {
            renderGadTable(gadRes.data.data || []);
            renderPagination(gadRes.data, 'gadPagination', 'loadGadData');
            document.getElementById('statTotalGad').textContent = gadRes.data.total || 0;
        }
        
        // Calculate risks
        updateRiskStats(tdRes?.data?.data || [], gadRes?.data?.data || []);
        
    } catch (e) { console.error(e); }
}

function updateRiskStats(tdData, gadData) {
    let risiko = 0;
    tdData.forEach(td => {
        if (getStatusTd(td.systolic, td.diastolic).label === 'Hipertensi') risiko++;
    });
    gadData.forEach(g => {
        if (getStatusGad(g.skor).label === 'Sedang-Tinggi') risiko++;
    });
    document.getElementById('statRisiko').textContent = risiko;
}

function renderTdTable(data) {
    const tbody = document.getElementById('tdTable');
    const cards = document.getElementById('tdCards');
    
    if (!data || data.length === 0) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        if (cards) cards.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada data</div>';
        return;
    }
    
    const rowsHtml = data.map(td => {
        const status = getStatusTd(td.systolic, td.diastolic);
        return `<tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-medium">${td.warga?.nama_lengkap || '-'}</td>
            <td class="px-4 py-3">${new Date(td.tgl_cek).toLocaleDateString('id-ID')}</td>
            <td class="px-4 py-3 font-mono">${td.systolic}</td>
            <td class="px-4 py-3 font-mono">${td.diastolic}</td>
            <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full ${status.color}">${status.label}</span></td>
        </tr>`;
    }).join('');

    const cardsHtml = data.map(td => {
        const status = getStatusTd(td.systolic, td.diastolic);
        return `
            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                <div class="flex justify-between items-start mb-2">
                    <p class="font-bold text-gray-800">${td.warga?.nama_lengkap || '-'}</p>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full ${status.color}">${status.label}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>${new Date(td.tgl_cek).toLocaleDateString('id-ID')}</span>
                    <span class="font-mono font-bold text-gray-700">${td.systolic}/${td.diastolic} mmHg</span>
                </div>
            </div>
        `;
    }).join('');
    
    if (tbody) tbody.innerHTML = rowsHtml;
    if (cards) cards.innerHTML = cardsHtml;
}

function renderGadTable(data) {
    const tbody = document.getElementById('gadTable');
    const cards = document.getElementById('gadCards');
    
    if (!data || data.length === 0) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        if (cards) cards.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada data</div>';
        return;
    }
    
    const rowsHtml = data.map(gad => {
        const status = getStatusGad(gad.skor);
        return `<tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-medium">${gad.warga?.nama_lengkap || '-'}</td>
            <td class="px-4 py-3">${new Date(gad.tgl_gad).toLocaleDateString('id-ID')}</td>
            <td class="px-4 py-3 font-mono font-bold">${gad.skor}</td>
            <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full ${status.color}">${status.label}</span></td>
        </tr>`;
    }).join('');

    const cardsHtml = data.map(gad => {
        const status = getStatusGad(gad.skor);
        return `
            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                <div class="flex justify-between items-start mb-2">
                    <p class="font-bold text-gray-800">${gad.warga?.nama_lengkap || '-'}</p>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full ${status.color}">${status.label}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>${new Date(gad.tgl_gad).toLocaleDateString('id-ID')}</span>
                    <span class="font-mono font-bold text-gray-700">Skor: ${gad.skor}</span>
                </div>
            </div>
        `;
    }).join('');
    
    if (tbody) tbody.innerHTML = rowsHtml;
    if (cards) cards.innerHTML = cardsHtml;
}

async function loadWargaList() {
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        const res = await apiCall(`/users/kader/${user.id}/warga`);
        if (res && res.success) {
            const select = document.getElementById('filterWarga');
            res.data.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.id;
                opt.textContent = w.nama_lengkap;
                select.appendChild(opt);
            });
        }
    } catch (e) { console.error(e); }
}

function renderPagination(data, containerId, loadFn) {
    const container = document.getElementById(containerId);
    if (!data.last_page || data.last_page <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let html = '';
    for (let i = 1; i <= data.last_page; i++) {
        const isActive = i === data.current_page;
        html += `<button onclick="${loadFn}(${i})" class="px-3 py-1 rounded-md ${isActive ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'} transition text-xs font-medium">${i}</button>`;
    }
    container.innerHTML = html;
}

async function loadTdData(page) { loadData(page); }
async function loadGadData(page) { loadData(page); }

function resetFilter() {
    document.getElementById('filterWarga').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    loadData();
}

document.addEventListener('DOMContentLoaded', () => {
    loadWargaList();
    loadData();
    
    document.getElementById('filterWarga').addEventListener('change', () => loadData());
});
</script>
@endsection
