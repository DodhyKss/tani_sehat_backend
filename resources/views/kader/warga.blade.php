@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Data Kesehatan Warga</h1>
    <p class="text-gray-500 text-sm">Monitoring tekanan darah dan GAD7 warga saya</p>
</div>

<div class="flex flex-col md:flex-row gap-4 mb-6">
    <div class="flex-1">
        <select id="filterWarga" class="w-full md:w-64 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-primary-500">
            <option value="">Semua Warga</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button onclick="filterData('week')" class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium hover:bg-gray-50 transition filter-btn active bg-primary-50 text-primary-600">Minggu</button>
        <button onclick="filterData('month')" class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium hover:bg-gray-50 transition filter-btn">Bulan</button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-md">
        <p class="text-green-100 text-sm font-medium">Normal</p>
        <div class="text-3xl font-bold mt-1" id="statNormal">0</div>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-5 text-white shadow-md">
        <p class="text-yellow-100 text-sm font-medium">Waspada</p>
        <div class="text-3xl font-bold mt-1" id="statWaspada">0</div>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 text-white shadow-md">
        <p class="text-red-100 text-sm font-medium">Risiko Tinggi</p>
        <div class="text-3xl font-bold mt-1" id="statRisiko">0</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Total Cek</p>
        <div class="text-3xl font-bold text-primary-600 mt-1" id="statTotal">0</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Grafik Kesehatan</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-64 md:h-80">
        <div><canvas id="tdChart"></canvas></div>
        <div><canvas id="gadChart"></canvas></div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Terakhir</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3 text-left">Warga</th>
                    <th class="px-4 py-3 text-left">TD</th>
                    <th class="px-4 py-3 text-left">GAD7</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody id="historyTable">
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Memuat...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
let tdChart, gadChart;
let currentFilter = 'week';

function filterData(filter) {
    currentFilter = filter;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active', 'bg-primary-50', 'text-primary-600'));
    event.target.classList.add('active', 'bg-primary-50', 'text-primary-600');
    loadData();
}

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

async function loadWargaList() {
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        const res = await apiCall(`/warga/by-kader/${user.id}`);
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

async function loadData() {
    const user = JSON.parse(localStorage.getItem('user'));
    const wargaId = document.getElementById('filterWarga').value;
    
    try {
        const [tdRes, gadRes] = await Promise.all([
            apiCall(`/tekanan-darah?kader_id=${user.id}&warga_id=${wargaId}&filter=${currentFilter}`),
            apiCall(`/gad?kader_id=${user.id}&warga_id=${wargaId}&filter=${currentFilter}`)
        ]);
        
        let normal = 0, waspada = 0, risiko = 0;
        
        if (tdRes?.success) {
            tdRes.data.data?.forEach(td => {
                const s = getStatusTd(td.systolic, td.diastolic);
                if (s.label === 'Normal') normal++;
                else if (s.label === 'Pra-Hipertensi') waspada++;
                else risiko++;
            });
        }
        
        if (gadRes?.success) {
            gadRes.data.data?.forEach(g => {
                const s = getStatusGad(g.skor);
                if (s.label !== 'Normal') {
                    if (s.label === 'Ringan') waspada++;
                    else risiko++;
                }
            });
        }
        
        document.getElementById('statNormal').textContent = normal;
        document.getElementById('statWaspada').textContent = waspada;
        document.getElementById('statRisiko').textContent = risiko;
        document.getElementById('statTotal').textContent = normal + waspada + risiko;
        
        renderHistory(tdRes, gadRes);
    } catch (e) { console.error(e); }
}

function renderHistory(tdRes, gadRes) {
    const tbody = document.getElementById('historyTable');
    const items = [];
    
    if (tdRes?.success) {
        tdRes.data.data?.forEach(td => {
            const status = getStatusTd(td.systolic, td.diastolic);
            items.push({ date: td.tgl_cek, warga: td.warga?.nama_lengkap, td: `${td.systolic}/${td.diastolic}`, tdStatus: status, gad: '-', gadStatus: null });
        });
    }
    
    if (gadRes?.success) {
        gadRes.data.data?.forEach(g => {
            const status = getStatusGad(g.skor);
            items.push({ date: g.tgl_gad, warga: g.warga?.nama_lengkap, td: '-', tdStatus: null, gad: g.skor, gadStatus: status });
        });
    }
    
    items.sort((a, b) => new Date(b.date) - new Date(a.date));
    
    if (items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = items.slice(0, 20).map(item => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 font-medium">${item.warga}</td>
            <td class="px-4 py-3">${item.td !== '-' ? `<span class="px-2 py-1 text-xs font-semibold rounded-full ${item.tdStatus.color}">${item.td}</span>` : '<span class="text-gray-400">-</span>'}</td>
            <td class="px-4 py-3">${item.gad !== '-' ? `<span class="px-2 py-1 text-xs font-semibold rounded-full ${item.gadStatus.color}">${item.gad}</span>` : '<span class="text-gray-400">-</span>'}</td>
            <td class="px-4 py-3 text-gray-500">${new Date(item.date).toLocaleDateString('id-ID')}</td>
        </tr>
    `).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    loadWargaList();
    loadData();
});
</script>
@endsection