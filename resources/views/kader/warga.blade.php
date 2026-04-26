@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Status Kesehatan Warga Saya</h1>
    <p class="text-gray-500 text-sm">Ringkasan kondisi kesehatan seluruh warga binaan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Distribusi TD -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Distribusi Tekanan Darah</h3>
                <p class="text-xs text-gray-400">Status terbaru warga</p>
            </div>
            <div class="p-2 bg-red-50 rounded-lg text-red-500">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="w-full md:w-48 h-48">
                <canvas id="tdPieChart"></canvas>
            </div>
            <div class="flex-1 space-y-3 w-full" id="tdStatsList">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Normal
                    </span>
                    <span class="font-bold text-gray-800" id="tdNormalCount">0</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span> Pra-Hipertensi
                    </span>
                    <span class="font-bold text-gray-800" id="tdWaspadaCount">0</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span> Hipertensi
                    </span>
                    <span class="font-bold text-gray-800" id="tdRisikoCount">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribusi GAD7 -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Distribusi GAD-7</h3>
                <p class="text-xs text-gray-400">Kesehatan mental warga</p>
            </div>
            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-500">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="w-full md:w-48 h-48">
                <canvas id="gadPieChart"></canvas>
            </div>
            <div class="flex-1 space-y-3 w-full" id="gadStatsList">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Normal
                    </span>
                    <span class="font-bold text-gray-800" id="gadNormalCount">0</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span> Cemas Ringan
                    </span>
                    <span class="font-bold text-gray-800" id="gadWaspadaCount">0</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span> Sedang/Berat
                    </span>
                    <span class="font-bold text-gray-800" id="gadRisikoCount">0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Bar Chart TD -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Statistik Tekanan Darah</h3>
        <div class="h-64">
            <canvas id="tdBarChart"></canvas>
        </div>
    </div>

    <!-- Bar Chart GAD7 -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Statistik GAD-7</h3>
        <div class="h-64">
            <canvas id="gadBarChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Progres Kesehatan Warga</h2>
            <p class="text-sm text-gray-500">Perbandingan data awal vs terbaru untuk setiap warga</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-200 shadow-sm">
            <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Live Monitoring</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 text-left">Nama Warga</th>
                    <th class="px-6 py-4 text-center border-x border-gray-100/50">TD (Awal vs Terbaru)</th>
                    <th class="px-6 py-4 text-center border-r border-gray-100/50">GAD-7 (Awal vs Terbaru)</th>
                    <th class="px-6 py-4 text-center border-r border-gray-100/50">Analisis Progres</th>
                </tr>
            </thead>
            <tbody id="latestTable" class="divide-y divide-gray-50">
                <tr><td colspan="4" class="px-8 py-12 text-center text-gray-500">Memuat data warga...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
let tdPieChart, gadPieChart, tdBarChart, gadBarChart;

async function loadLatestData() {
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        const res = await apiCall(`/users/kader/${user.id}/warga`);
        if (res && res.success) {
            const wargaData = res.data;
            processStats(wargaData);
            renderLatestTable(wargaData);
        }
    } catch (e) { console.error("Error loading latest data", e); }
}

function processStats(data) {
    let td = { normal: 0, waspada: 0, risiko: 0 };
    let gad = { normal: 0, waspada: 0, risiko: 0 };

    data.forEach(w => {
        const status = w.status_kesehatan;
        if (status) {
            // TD Stats
            if (status.kategori_td === 'normal') td.normal++;
            else if (status.kategori_td === 'pra_hipertensi') td.waspada++;
            else if (status.kategori_td === 'hipertensi') td.risiko++;
            
            // GAD Stats
            if (status.kategori_gad === 'normal') gad.normal++;
            else if (status.kategori_gad === 'ringan') gad.waspada++;
            else if (status.kategori_gad === 'sedang' || status.kategori_gad === 'tinggi') gad.risiko++;
        }
    });

    document.getElementById('tdNormalCount').textContent = td.normal;
    document.getElementById('tdWaspadaCount').textContent = td.waspada;
    document.getElementById('tdRisikoCount').textContent = td.risiko;
    
    document.getElementById('gadNormalCount').textContent = gad.normal;
    document.getElementById('gadWaspadaCount').textContent = gad.waspada;
    document.getElementById('gadRisikoCount').textContent = gad.risiko;

    renderCharts(td, gad);
}

function renderCharts(td, gad) {
    const pieOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '70%'
    };

    const barOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    };

    // TD Pie
    if (tdPieChart) tdPieChart.destroy();
    tdPieChart = new Chart(document.getElementById('tdPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Normal', 'Pra-Hipertensi', 'Hipertensi'],
            datasets: [{
                data: [td.normal, td.waspada, td.risiko],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: pieOptions
    });

    // TD Bar
    if (tdBarChart) tdBarChart.destroy();
    tdBarChart = new Chart(document.getElementById('tdBarChart'), {
        type: 'bar',
        data: {
            labels: ['Normal', 'Pra-Hipertensi', 'Hipertensi'],
            datasets: [{
                data: [td.normal, td.waspada, td.risiko],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderRadius: 8
            }]
        },
        options: barOptions
    });

    // GAD Pie
    if (gadPieChart) gadPieChart.destroy();
    gadPieChart = new Chart(document.getElementById('gadPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Normal', 'Ringan', 'Sedang/Tinggi'],
            datasets: [{
                data: [gad.normal, gad.waspada, gad.risiko],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: pieOptions
    });

    // GAD Bar
    if (gadBarChart) gadBarChart.destroy();
    gadBarChart = new Chart(document.getElementById('gadBarChart'), {
        type: 'bar',
        data: {
            labels: ['Normal', 'Cemas Ringan', 'Sedang/Tinggi'],
            datasets: [{
                data: [gad.normal, gad.waspada, gad.risiko],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderRadius: 8
            }]
        },
        options: barOptions
    });
}

function renderLatestTable(data) {
    const tbody = document.getElementById('latestTable');
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-8 py-12 text-center text-gray-500 italic">Belum ada warga binaan</td></tr>';
        return;
    }

    tbody.innerHTML = data.map(w => {
        const s = w.status_kesehatan;
        const fTd = w.first_td;
        const fGad = w.first_gad;
        
        const tdAwal = fTd ? `${fTd.systolic}/${fTd.diastolic}` : '--/--';
        const tdAkhir = s?.tekanan_darah || '--/--';
        
        const gadAwal = fGad ? fGad.skor : '--';
        const gadAkhir = s?.skor_gad !== null ? s.skor_gad : '--';

        // Analisis Progres logic
        let trendLabel = 'Tidak Ada Perubahan';
        let trendColor = 'bg-gray-100 text-gray-600';
        
        if (s && fTd) {
            const getRank = (cat) => {
                if (cat === 'normal') return 0;
                if (cat === 'pra_hipertensi') return 1;
                return 2;
            };
            
            // Get category of first TD
            const firstCat = getStatusTd(fTd.systolic, fTd.diastolic).category;
            const currentRank = getRank(s.kategori_td);
            const firstRank = getRank(firstCat);
            
            if (currentRank < firstRank) {
                trendLabel = 'Ada Perbaikan';
                trendColor = 'bg-emerald-100 text-emerald-700';
            } else if (currentRank > firstRank) {
                trendLabel = 'Lebih Buruk';
                trendColor = 'bg-rose-100 text-rose-700';
            }
        }

        return `
            <tr class="hover:bg-gray-50/50 transition duration-200">
                <td class="px-8 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                            ${w.nama_lengkap.charAt(0)}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">${w.nama_lengkap}</p>
                            <p class="text-[10px] text-gray-400 font-medium">NIK: ${w.nik || '-'}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5 text-center border-x border-gray-100/50">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">Awal vs Baru</span>
                        <div class="font-mono text-sm">
                            <span class="text-gray-400">${tdAwal}</span>
                            <span class="mx-2 text-primary-300">→</span>
                            <span class="font-bold text-gray-800">${tdAkhir}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5 text-center border-r border-gray-100/50">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">Skor GAD-7</span>
                        <div class="font-mono text-sm">
                            <span class="text-gray-400">${gadAwal}</span>
                            <span class="mx-2 text-primary-300">→</span>
                            <span class="font-bold text-gray-800">${gadAkhir}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5 text-center">
                    <span class="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-full ${trendColor}">
                        ${trendLabel}
                    </span>
                </td>
            </tr>
        `;
    }).join('');
}

function getStatusTd(systolic, diastolic) {
    if (systolic < 120 && diastolic < 80) return { category: 'normal' };
    if (systolic <= 139 && diastolic <= 89) return { category: 'pra_hipertensi' };
    return { category: 'hipertensi' };
}

document.addEventListener('DOMContentLoaded', loadLatestData);
</script>
@endsection