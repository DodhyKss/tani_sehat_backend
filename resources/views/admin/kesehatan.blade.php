@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Data Kesehatan Warga</h1>
    <p class="text-gray-500 text-sm">Monitoring tekanan darah dan GAD7 warga</p>
</div>

<div class="flex flex-col lg:flex-row gap-4 mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
    <div class="flex-1">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Warga</label>
        <select id="filterWarga" class="w-full px-4 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary-500">
            <option value="">Semua Warga</option>
        </select>
    </div>
    <div class="flex flex-col md:flex-row gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Dari Tanggal</label>
            <input type="date" id="startDate" class="w-full px-4 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
            <input type="date" id="endDate" class="w-full px-4 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary-500">
        </div>
    </div>
    <div class="flex items-end gap-2">
        <button onclick="loadData()" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition shadow-sm">Cari</button>
        <button onclick="resetFilter()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition">Reset</button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="text-green-100 text-sm font-medium">Normal</span>
            <svg class="w-8 h-8 text-green-200 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="text-3xl font-bold" id="statNormal">-</div>
        <div class="text-green-100 text-xs mt-1">Kondisi sehat</div>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-5 text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="text-yellow-100 text-sm font-medium">Waspada</span>
            <svg class="w-8 h-8 text-yellow-200 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="text-3xl font-bold" id="statWaspada">-</div>
        <div class="text-yellow-100 text-xs mt-1">Pra-hipertensi / Ringan</div>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="text-red-100 text-sm font-medium">Risiko Tinggi</span>
            <svg class="w-8 h-8 text-red-200 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="text-3xl font-bold" id="statRisiko">-</div>
        <div class="text-red-100 text-xs mt-1">Hipertensi / Sedang-Tinggi</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Tekanan Darah</h2>
    <div class="overflow-x-auto">
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
    <div id="tdPagination" class="mt-4 flex justify-center gap-2"></div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat GAD7</h2>
    <div class="overflow-x-auto">
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
    <div id="gadPagination" class="mt-4 flex justify-center gap-2"></div>
</div>
@endsection

@section('scripts')
<script>
let currentFilter = 'week';

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

function filterData(filter) {
    currentFilter = filter;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active', 'bg-primary-50', 'text-primary-600'));
    event.target.classList.add('active', 'bg-primary-50', 'text-primary-600');
    loadData();
}

async function loadData() {
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    
    try {
        const [tdRes, gadRes] = await Promise.all([
            apiCall(`/tekanan-darah?warga_id=${wargaId}&start_date=${start}&end_date=${end}`),
            apiCall(`/gad?warga_id=${wargaId}&start_date=${start}&end_date=${end}`)
        ]);
        
        if (tdRes && tdRes.success) {
            renderTdTable(tdRes.data.data || []);
            renderPagination(tdRes.data, 'tdPagination', 'loadTdData');
        }
        if (gadRes && gadRes.success) {
            renderGadTable(gadRes.data.data || []);
            renderPagination(gadRes.data, 'gadPagination', 'loadGadData');
        }
    } catch (e) { console.error(e); }
}

function renderTdTable(data) {
    const tbody = document.getElementById('tdTable');
    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        return;
    }
    
    let normal = 0, waspada = 0, risiko = 0;
    tbody.innerHTML = data.map(td => {
        const status = getStatusTd(td.systolic, td.diastolic);
        if (status.label === 'Normal') normal++;
        else if (status.label === 'Pra-Hipertensi') waspada++;
        else risiko++;
        
        return `<tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-medium">${td.warga?.nama_lengkap || '-'}</td>
            <td class="px-4 py-3">${new Date(td.tgl_cek).toLocaleDateString('id-ID')}</td>
            <td class="px-4 py-3 font-mono">${td.systolic}</td>
            <td class="px-4 py-3 font-mono">${td.diastolic}</td>
            <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full ${status.color}">${status.label}</span></td>
        </tr>`;
    }).join('');
    
    document.getElementById('statNormal').textContent = normal;
    document.getElementById('statWaspada').textContent = waspada;
    document.getElementById('statRisiko').textContent = risiko;
}

function renderGadTable(data) {
    const tbody = document.getElementById('gadTable');
    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(gad => {
        const status = getStatusGad(gad.skor);
        return `<tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-medium">${gad.warga?.nama_lengkap || '-'}</td>
            <td class="px-4 py-3">${new Date(gad.tgl_gad).toLocaleDateString('id-ID')}</td>
            <td class="px-4 py-3 font-mono font-bold">${gad.skor}</td>
            <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full ${status.color}">${status.label}</span></td>
        </tr>`;
    }).join('');
}

async function loadWargaList() {
    try {
        const res = await apiCall('/users?role=warga');
        if (res && res.success) {
            const select = document.getElementById('filterWarga');
            res.data.data.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.id;
                opt.textContent = w.nama_lengkap;
                select.appendChild(opt);
            });
        }
    } catch (e) { console.error(e); }
}

document.addEventListener('DOMContentLoaded', () => {
    loadWargaList();
    loadData();
    
    document.getElementById('filterWarga').addEventListener('change', loadData);
});
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

async function loadTdData(page = 1) {
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const res = await apiCall(`/tekanan-darah?page=${page}&warga_id=${wargaId}&start_date=${start}&end_date=${end}`);
    if (res && res.success) {
        renderTdTable(res.data.data || []);
        renderPagination(res.data, 'tdPagination', 'loadTdData');
    }
}

async function loadGadData(page = 1) {
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const res = await apiCall(`/gad?page=${page}&warga_id=${wargaId}&start_date=${start}&end_date=${end}`);
    if (res && res.success) {
        renderGadTable(res.data.data || []);
        renderPagination(res.data, 'gadPagination', 'loadGadData');
    }
}

function resetFilter() {
    document.getElementById('filterWarga').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    loadData();
}
</script>
@endsection