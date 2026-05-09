@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Status Kesehatan Warga Saya</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Ringkasan Kondisi Kesehatan Seluruh Warga Binaan</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Distribusi TD -->
    <div id="capture-td-pie" class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-[2.5rem] shadow-xl p-6 md:p-10 text-white border border-primary-500/20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-black tracking-tight">Distribusi Tekanan Darah</h3>
                <p class="text-primary-100 text-sm font-bold opacity-60 uppercase tracking-widest mt-1">Status terbaru warga</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="copyChart('capture-td-pie')" class="p-2.5 bg-white/20 text-white hover:bg-white/30 rounded-xl transition-all shadow-sm flex items-center gap-2 group border border-white/10">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest hidden md:inline">Salin</span>
                </button>
                <div class="p-3 bg-white/20 rounded-2xl">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-8 md:gap-12">
            <div class="w-48 h-48 md:w-56 md:h-56">
                <canvas id="tdPieChart"></canvas>
            </div>
            <div class="flex-1 space-y-4 w-full" id="tdStatsList">
                <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/10">
                    <span class="flex items-center gap-3 text-base font-black uppercase tracking-wider">
                        <span class="w-3 h-3 rounded-full bg-emerald-400 shadow-[0_0_10px_#34d399]"></span> Normal
                    </span>
                    <span class="text-2xl font-black" id="tdNormalCount">0</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/10">
                    <span class="flex items-center gap-3 text-base font-black uppercase tracking-wider">
                        <span class="w-3 h-3 rounded-full bg-amber-400 shadow-[0_0_10px_#fbbf24]"></span> Pra-Hipertensi
                    </span>
                    <span class="text-2xl font-black" id="tdWaspadaCount">0</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/10">
                    <span class="flex items-center gap-3 text-base font-black uppercase tracking-wider">
                        <span class="w-3 h-3 rounded-full bg-orange-400 shadow-[0_0_10px_#fb923c]"></span> Hipertensi
                    </span>
                    <span class="text-2xl font-black" id="tdRisikoCount">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribusi GAD7 -->
    <div id="capture-gad-pie" class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[2.5rem] shadow-xl p-6 md:p-10 text-white border border-indigo-500/20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-black tracking-tight">Distribusi GAD-7</h3>
                <p class="text-indigo-100 text-sm font-bold opacity-60 uppercase tracking-widest mt-1">Kesehatan mental warga</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="copyChart('capture-gad-pie')" class="p-2.5 bg-white/20 text-white hover:bg-white/30 rounded-xl transition-all shadow-sm flex items-center gap-2 group border border-white/10">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest hidden md:inline">Salin</span>
                </button>
                <div class="p-3 bg-white/20 rounded-2xl">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-8 md:gap-12">
            <div class="w-48 h-48 md:w-56 md:h-56">
                <canvas id="gadPieChart"></canvas>
            </div>
            <div class="flex-1 space-y-4 w-full" id="gadStatsList">
                <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/10">
                    <span class="flex items-center gap-3 text-base font-black uppercase tracking-wider">
                        <span class="w-3 h-3 rounded-full bg-emerald-400 shadow-[0_0_10px_#34d399]"></span> Normal
                    </span>
                    <span class="text-2xl font-black" id="gadNormalCount">0</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/10">
                    <span class="flex items-center gap-3 text-base font-black uppercase tracking-wider">
                        <span class="w-3 h-3 rounded-full bg-amber-400 shadow-[0_0_10px_#fbbf24]"></span> Cemas Ringan
                    </span>
                    <span class="text-2xl font-black" id="gadWaspadaCount">0</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl border border-white/10">
                    <span class="flex items-center gap-3 text-base font-black uppercase tracking-wider">
                        <span class="w-3 h-3 rounded-full bg-orange-400 shadow-[0_0_10px_#fb923c]"></span> Sedang/Berat
                    </span>
                    <span class="text-2xl font-black" id="gadRisikoCount">0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Bar Chart TD -->
    <div id="capture-td-bar" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">Statistik Tekanan Darah</h3>
            <button onclick="copyChart('capture-td-bar')" class="p-2 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-lg transition-all">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
        <div class="h-64">
            <canvas id="tdBarChart"></canvas>
        </div>
    </div>

    <!-- Bar Chart GAD7 -->
    <div id="capture-gad-bar" class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">Statistik GAD-7</h3>
            <button onclick="copyChart('capture-gad-bar')" class="p-2 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-lg transition-all">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
        <div class="h-64">
            <canvas id="gadBarChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 overflow-hidden mb-12">
    <div class="p-8 border-b-2 border-primary-50 flex flex-col md:flex-row md:items-center justify-between bg-primary-50/20 gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-black tracking-tight">Progres Kesehatan Warga</h2>
            <p class="text-primary-800 text-base font-bold mt-1 uppercase tracking-widest opacity-60">Perbandingan data awal vs terbaru</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- <button onclick="exportToExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 transition-all shadow-lg shadow-emerald-900/10 uppercase tracking-widest">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                EXCEL
            </button> --}}
            <div class="hidden md:flex items-center gap-3 px-5 py-2 bg-white rounded-2xl border border-primary-100 shadow-sm">
                <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-xs font-black text-primary-800 uppercase tracking-widest">Live</span>
            </div>
        </div>
    </div>
    
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto -mx-10 px-10">
        <table class="w-full text-left">
            <thead class="text-primary-400 uppercase text-xs font-black tracking-[0.2em] border-b-2 border-primary-50">
                <tr>
                    <th class="px-8 py-6">Warga (NIK)</th>
                    <th class="px-6 py-6 text-center">Profil</th>
                    <th class="px-6 py-6 text-center border-x border-primary-50">TD (Awal vs Terbaru)</th>
                    <th class="px-6 py-6 text-center border-r border-primary-50">GAD-7 (Awal vs Terbaru)</th>
                    <th class="px-6 py-6 text-center">Analisis Progres</th>
                </tr>
            </thead>
            <tbody id="latestTable" class="divide-y-2 divide-primary-50">
                <tr><td colspan="4" class="px-8 py-20 text-center text-primary-300 font-bold italic animate-pulse text-xl uppercase tracking-widest">Memuat data warga...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="latestCards" class="md:hidden divide-y divide-gray-100">
        <div class="p-8 text-center text-gray-500">Memuat data warga...</div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
let wargaFullData = [];

async function exportToExcel() {
    if (wargaFullData.length === 0) { showAlert('Tidak ada data untuk diekspor'); return; }
    const excelData = wargaFullData.map(w => {
        const s = w.status_kesehatan;
        const fTd = w.first_td;
        const fGad = w.first_gad;
        return {
            'Nama Warga': w.nama_lengkap,
            'NIK': w.nik || '-',
            'Umur': w.tanggal_lahir ? calculateAge(w.tanggal_lahir) : '-',
            'L/P': w.jenis_kelamin || '-',
            'TD Awal': fTd ? `${fTd.systolic}/${fTd.diastolic}` : '-',
            'TD Terbaru': s?.tekanan_darah || '-',
            'Kategori TD': s?.kategori_td?.toUpperCase() || '-',
            'GAD-7 Awal': fGad ? fGad.skor : '-',
            'GAD-7 Terbaru': s?.skor_gad !== null ? s.skor_gad : '-',
            'Kategori GAD-7': s?.kategori_gad?.toUpperCase() || '-'
        };
    });
    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Status Warga");
    XLSX.writeFile(workbook, `Status_Kesehatan_Warga_Kader_${new Date().toISOString().split('T')[0]}.xlsx`);
}

function calculateAge(birthDate) {
    if (!birthDate) return '-';
    const birth = new Date(birthDate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    return age;
}

async function copyChart(sectionId) {
    const element = document.getElementById(sectionId);
    if (!element) return;
    
    // Hide buttons during capture
    const buttons = element.querySelectorAll('button');
    buttons.forEach(b => b.style.display = 'none');

    try {
        const canvas = await html2canvas(element, {
            backgroundColor: null,
            scale: 2, // Higher quality
            logging: false,
            useCORS: true
        });
        
        canvas.toBlob(async (blob) => {
            try {
                await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
                showAlert('Grafik & Total berhasil disalin!', 'success');
            } catch (e) { showAlert('Gagal menyalin ke clipboard'); }
        });
    } catch (err) {
        console.error(err);
        showAlert('Gagal menangkap grafik');
    } finally {
        buttons.forEach(b => b.style.display = '');
    }
}

let tdPieChart, gadPieChart, tdBarChart, gadBarChart;

async function loadLatestData() {
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        const res = await apiCall(`/users/kader/${user.id}/warga`);
        if (res && res.success) {
            wargaFullData = res.data;
            processStats(res.data);
            renderLatestTable(res.data);
        }
    } catch (e) { console.error("Error loading latest data", e); }
}

function processStats(data) {
    let td = { normal: 0, waspada: 0, risiko: 0 };
    let gad = { normal: 0, waspada: 0, risiko: 0 };

    data.forEach(w => {
        const status = w.status_kesehatan;
        if (status) {
            let catTd = status.kategori_td;
            if (catTd === 'pre_hipertensi') catTd = 'pra_hipertensi';
            
            // TD Stats
            if (catTd === 'normal') td.normal++;
            else if (catTd === 'pra_hipertensi') td.waspada++;
            else if (catTd === 'hipertensi') td.risiko++;
            
            // GAD Stats
            if (status.kategori_gad === 'normal') gad.normal++;
            else if (status.kategori_gad === 'ringan') gad.waspada++;
            else if (status.kategori_gad === 'sedang' || status.kategori_gad === 'tinggi') gad.risiko++;
        }
    });

    const tdTotal = td.normal + td.waspada + td.risiko;
    const gadTotal = gad.normal + gad.waspada + gad.risiko;

    document.getElementById('tdNormalCount').textContent = tdTotal > 0 ? (td.normal / tdTotal * 100).toFixed(1) + '%' : '0%';
    document.getElementById('tdWaspadaCount').textContent = tdTotal > 0 ? (td.waspada / tdTotal * 100).toFixed(1) + '%' : '0%';
    document.getElementById('tdRisikoCount').textContent = tdTotal > 0 ? (td.risiko / tdTotal * 100).toFixed(1) + '%' : '0%';
    
    document.getElementById('gadNormalCount').textContent = gadTotal > 0 ? (gad.normal / gadTotal * 100).toFixed(1) + '%' : '0%';
    document.getElementById('gadWaspadaCount').textContent = gadTotal > 0 ? (gad.waspada / gadTotal * 100).toFixed(1) + '%' : '0%';
    document.getElementById('gadRisikoCount').textContent = gadTotal > 0 ? (gad.risiko / gadTotal * 100).toFixed(1) + '%' : '0%';

    renderCharts(td, gad);
}

function renderCharts(td, gad) {
    const tdTotal = td.normal + td.waspada + td.risiko;
    const gadTotal = gad.normal + gad.waspada + gad.risiko;

    const tdData = [
        tdTotal > 0 ? (td.normal / tdTotal * 100).toFixed(1) : 0,
        tdTotal > 0 ? (td.waspada / tdTotal * 100).toFixed(1) : 0,
        tdTotal > 0 ? (td.risiko / tdTotal * 100).toFixed(1) : 0
    ];

    const gadData = [
        gadTotal > 0 ? (gad.normal / gadTotal * 100).toFixed(1) : 0,
        gadTotal > 0 ? (gad.waspada / gadTotal * 100).toFixed(1) : 0,
        gadTotal > 0 ? (gad.risiko / gadTotal * 100).toFixed(1) : 0
    ];

    const pieOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: (context) => ` ${context.label}: ${context.raw}%`
                }
            }
        },
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
            labels: [
                `Normal (${tdData[0]}%)`,
                `Pra-Hipertensi (${tdData[1]}%)`,
                `Hipertensi (${tdData[2]}%)`
            ],
            datasets: [{
                data: tdData,
                backgroundColor: ['#34d399', '#fbbf24', '#fb923c'],
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
                backgroundColor: ['#059669', '#d97706', '#ea580c'],
                borderRadius: 12
            }]
        },
        options: barOptions
    });

    // GAD Pie
    if (gadPieChart) gadPieChart.destroy();
    gadPieChart = new Chart(document.getElementById('gadPieChart'), {
        type: 'doughnut',
        data: {
            labels: [
                `Normal (${gadData[0]}%)`,
                `Ringan (${gadData[1]}%)`,
                `Sedang/Tinggi (${gadData[2]}%)`
            ],
            datasets: [{
                data: gadData,
                backgroundColor: ['#34d399', '#fbbf24', '#fb923c'],
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
                backgroundColor: ['#059669', '#d97706', '#ea580c'],
                borderRadius: 12
            }]
        },
        options: barOptions
    });
}

function renderLatestTable(data) {
    const tbody = document.getElementById('latestTable');
    const cards = document.getElementById('latestCards');
    
    if (data.length === 0) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-8 py-12 text-center text-gray-500 italic">Belum ada warga binaan</td></tr>';
        if (cards) cards.innerHTML = '<div class="p-8 text-center text-gray-500 italic">Belum ada warga binaan</div>';
        return;
    }

    const rowsHtml = data.map(w => {
        const s = w.status_kesehatan;
        const fTd = w.first_td;
        const fGad = w.first_gad;
        
        const tdAwal = fTd ? `${fTd.systolic}/${fTd.diastolic}` : '--/--';
        const tdAkhir = s?.tekanan_darah || '--/--';
        
        const gadAwal = fGad ? fGad.skor : '--';
        const gadAkhir = s?.skor_gad ?? '--';

        let trend = { label: 'Tidak Ada Perubahan', color: 'bg-gray-100 text-gray-600' };
        if (s && fTd) {
            const firstCat = getStatusTd(fTd.systolic, fTd.diastolic).category;
            let currentCatTd = s.kategori_td;
            if (currentCatTd === 'pre_hipertensi') currentCatTd = 'pra_hipertensi';
            
            const currentRank = ['normal', 'pra_hipertensi', 'hipertensi'].indexOf(currentCatTd);
            const firstRank = ['normal', 'pra_hipertensi', 'hipertensi'].indexOf(firstCat);
            if (currentRank < firstRank) trend = { label: 'Ada Perbaikan', color: 'bg-emerald-100 text-emerald-700' };
            else if (currentRank > firstRank) trend = { label: 'Lebih Buruk', color: 'bg-rose-100 text-rose-700' };
        }

        return `
            <tr class="hover:bg-primary-50/50 transition-colors group">
                <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-100 border-2 border-primary-200 flex items-center justify-center text-primary-800 font-black text-xl shadow-sm">${w.nama_lengkap.charAt(0)}</div>
                        <div>
                            <p class="font-black text-black text-lg tracking-tight">${w.nama_lengkap}</p>
                            <p class="text-[10px] text-primary-400 font-black uppercase tracking-[0.1em] mt-0.5">NIK: ${w.nik || '-'}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-6 text-center">
                    <div class="font-black text-primary-800 text-sm">${w.tanggal_lahir ? calculateAge(w.tanggal_lahir) : '-'} Thn</div>
                    <div class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">${(w.jenis_kelamin?.toLowerCase() === 'l' || w.jenis_kelamin?.toLowerCase() === 'laki-laki') ? 'Laki-laki' : 'Perempuan'}</div>
                </td>
                <td class="px-6 py-6 text-center border-x border-primary-50">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] text-primary-300 uppercase font-black tracking-widest mb-2">Awal vs Baru</span>
                        <div class="flex items-center gap-3">
                            <span class="text-base font-bold text-primary-500">${tdAwal}</span>
                            <svg class="w-4 h-4 text-primary-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <span class="text-xl font-black text-primary-700 tracking-tight">${tdAkhir}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-6 text-center border-r border-primary-50">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] text-primary-300 uppercase font-black tracking-widest mb-2">Skor GAD-7</span>
                        <div class="flex items-center gap-3">
                            <span class="text-base font-bold text-primary-500">${gadAwal}</span>
                            <svg class="w-4 h-4 text-primary-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <span class="text-xl font-black text-primary-700 tracking-tight">${gadAkhir}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-6 text-center">
                    <span class="px-5 py-2 text-[10px] font-black uppercase tracking-[0.15em] rounded-xl shadow-sm ${trend.color.replace('emerald', 'emerald').replace('rose', 'orange')}">${trend.label}</span>
                </td>
            </tr>
        `;
    }).join('');

    const cardsHtml = data.map(w => {
        const s = w.status_kesehatan;
        const fTd = w.first_td;
        const fGad = w.first_gad;
        const tdAwal = fTd ? `${fTd.systolic}/${fTd.diastolic}` : '--/--';
        const tdAkhir = s?.tekanan_darah || '--/--';
        const gadAwal = fGad ? fGad.skor : '--';
        const gadAkhir = s?.skor_gad ?? '--';

        let trend = { label: 'Stabil', color: 'bg-gray-100 text-gray-600' };
        if (s && fTd) {
            const firstCat = getStatusTd(fTd.systolic, fTd.diastolic).category;
            let currentCatTd = s.kategori_td;
            if (currentCatTd === 'pre_hipertensi') currentCatTd = 'pra_hipertensi';
            
            const currentRank = ['normal', 'pra_hipertensi', 'hipertensi'].indexOf(currentCatTd);
            const firstRank = ['normal', 'pra_hipertensi', 'hipertensi'].indexOf(firstCat);
            if (currentRank < firstRank) trend = { label: 'Membaik', color: 'bg-emerald-500 text-white' };
            else if (currentRank > firstRank) trend = { label: 'Memburuk', color: 'bg-rose-500 text-white' };
        }

        return `
            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-primary-900/5 border border-primary-100">
                <div class="flex justify-between items-start mb-6 border-b-2 border-primary-50 pb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-100 border-2 border-primary-200 flex items-center justify-center text-primary-800 font-black text-xl shadow-sm">${w.nama_lengkap.charAt(0)}</div>
                        <div>
                            <p class="font-black text-black text-xl tracking-tight">${w.nama_lengkap}</p>
                            <p class="text-[10px] text-primary-400 font-black uppercase tracking-[0.1em] mt-0.5">NIK: ${w.nik || '-'} • ${w.tanggal_lahir ? calculateAge(w.tanggal_lahir) : '-'} Thn • ${w.jenis_kelamin}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.15em] rounded-lg shadow-sm ${trend.color.replace('emerald', 'emerald').replace('rose', 'orange')}">${trend.label}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-primary-50/50 p-4 rounded-2xl border border-primary-100">
                        <p class="text-[9px] font-black text-primary-400 uppercase tracking-widest mb-3">Tekanan Darah</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-primary-300 font-black">${tdAwal}</span>
                            <svg class="w-3 h-3 text-primary-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <span class="text-base font-black text-primary-800">${tdAkhir}</span>
                        </div>
                    </div>
                    <div class="bg-primary-50/50 p-4 rounded-2xl border border-primary-100">
                        <p class="text-[9px] font-black text-primary-400 uppercase tracking-widest mb-3">Skor GAD-7</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-primary-300 font-black">${gadAwal}</span>
                            <svg class="w-3 h-3 text-primary-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <span class="text-base font-black text-primary-800">${gadAkhir}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    if (tbody) tbody.innerHTML = rowsHtml;
    if (cards) cards.innerHTML = cardsHtml;
}

function getStatusTd(systolic, diastolic) {
    if (systolic >= 140 && diastolic >= 90) return { category: 'hipertensi' };
    if (systolic < 120 && diastolic < 80) return { category: 'normal' };
    return { category: 'pra_hipertensi' };
}

document.addEventListener('DOMContentLoaded', loadLatestData);
</script>
@endsection