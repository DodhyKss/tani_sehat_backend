@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Dashboard Kesehatan</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Selamat Datang, <span id="welcomeName" class="text-primary-900">-</span></p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div id="kondisiCard" class="md:col-span-2 bg-gradient-to-br from-primary-500 to-primary-700 rounded-[2.5rem] p-8 md:p-10 text-white shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-10 opacity-10 group-hover:scale-110 transition-transform duration-700">
            <svg class="w-40 h-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        </div>
        <div class="relative z-10">
            <p class="text-white/80 font-black uppercase tracking-[0.2em] text-xs mb-4">Kondisi Kesehatan Terakhir</p>
            <h2 class="text-4xl md:text-5xl font-black mb-4 tracking-tight" id="kondisiSummary">Memuat...</h2>
            <p class="text-white/90 text-lg font-bold max-w-xl leading-relaxed" id="kondisiDesc">-</p>
        </div>
    </div>
    
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 flex flex-col justify-between">
        <div id="kaderInfo" class="flex-1 flex flex-col items-center justify-center text-center">
            <div class="text-primary-300 font-bold italic">Memuat info kader...</div>
        </div>
        <div class="grid grid-cols-2 gap-3 mt-6">
            <a href="/warga/input-td" class="bg-primary-50 hover:bg-primary-100 p-4 rounded-2xl transition-all group text-center border border-primary-100">
                <div class="text-primary-600 font-black text-xs uppercase tracking-widest mb-1">Cek TD</div>
                <svg class="w-6 h-6 mx-auto text-primary-400 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </a>
            <a href="/warga/input-gad" class="bg-amber-50 hover:bg-amber-100 p-4 rounded-2xl transition-all group text-center border border-amber-100">
                <div class="text-amber-600 font-black text-xs uppercase tracking-widest mb-1">Isi GAD7</div>
                <svg class="w-6 h-6 mx-auto text-amber-400 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </a>
        </div>
    </div>
</div>

<!-- TREND CHARTS -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">
    <div id="capture-td-trend" class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 hover:shadow-2xl transition-all duration-500">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-black tracking-tight">Tren Tekanan Darah</h2>
                <p class="text-xs font-black text-primary-400 uppercase tracking-widest mt-1">7 Pengukuran Terakhir</p>
            </div>
            <button onclick="copyChart('capture-td-trend')" class="p-3 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-2xl transition-all group shadow-sm">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
        <div class="h-[300px] relative">
            <canvas id="tdTrendChart"></canvas>
        </div>
    </div>

    <div id="capture-gad-trend" class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-900/5 border border-indigo-100 p-8 md:p-10 hover:shadow-2xl transition-all duration-500">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-black tracking-tight">Tren GAD-7</h2>
                <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mt-1">7 Pengisian Terakhir</p>
            </div>
            <button onclick="copyChart('capture-gad-trend')" class="p-3 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-2xl transition-all group shadow-sm">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
        <div class="h-[300px] relative">
            <canvas id="gadTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- HISTORY TABLES -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- TD History -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 overflow-hidden">
        <div class="flex items-center justify-between mb-8 pb-4 border-b-2 border-primary-50">
            <h2 class="text-xl font-black text-black uppercase tracking-widest">Riwayat TD</h2>
            {{-- <button onclick="exportTdToExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-black text-[10px] flex items-center gap-2 transition-all uppercase tracking-[0.2em] shadow-lg shadow-emerald-900/10">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                EXCEL
            </button> --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-primary-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="pb-4">Tanggal</th>
                        <th class="pb-4 text-center">Hasil</th>
                        <th class="pb-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="tdHistoryTable" class="divide-y divide-primary-50">
                    <tr><td colspan="3" class="py-8 text-center text-primary-300 font-bold italic">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- GAD History -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-900/5 border border-indigo-100 p-8 md:p-10 overflow-hidden">
        <div class="flex items-center justify-between mb-8 pb-4 border-b-2 border-indigo-50">
            <h2 class="text-xl font-black text-black uppercase tracking-widest">Riwayat GAD-7</h2>
            {{-- <button onclick="exportGadToExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-black text-[10px] flex items-center gap-2 transition-all uppercase tracking-[0.2em] shadow-lg shadow-emerald-900/10">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                EXCEL
            </button> --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-indigo-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="pb-4">Tanggal</th>
                        <th class="pb-4 text-center">Skor</th>
                        <th class="pb-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="gadHistoryTable" class="divide-y divide-indigo-50">
                    <tr><td colspan="3" class="py-8 text-center text-indigo-300 font-bold italic">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 mb-10">
    <h3 class="text-2xl font-black text-black mb-8 border-b-2 border-primary-50 pb-4 tracking-tight">Rekomendasi untuk Anda</h3>
    <div id="rekomendasiContainer" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="col-span-full text-center py-8 text-primary-300 font-bold italic">Memuat...</div>
    </div>
</div>

<!-- Data Missing Popup -->
<div id="reminderModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 p-8 text-white text-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <h3 class="text-2xl font-black tracking-tight">Data Perlu Diperbarui!</h3>
            <p class="text-primary-100 text-sm font-bold opacity-80 mt-2 uppercase tracking-widest">Waktunya melakukan pengecekan rutin</p>
        </div>
        <div class="p-8 space-y-4">
            <div id="tdReminder" class="hidden flex items-center gap-4 p-5 bg-primary-50 rounded-2xl border-2 border-primary-100">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center text-primary-600 flex-shrink-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-black text-primary-900 text-sm uppercase tracking-widest">Tekanan Darah</p>
                    <p class="text-xs text-primary-600 font-bold mt-1">Lakukan pengecekan sekarang</p>
                </div>
                <a href="/warga/input-td" class="bg-primary-600 text-white px-4 py-2 rounded-xl text-xs font-black shadow-lg shadow-primary-900/10 uppercase tracking-widest">ISI</a>
            </div>
            
            <div id="gadReminder" class="hidden flex items-center gap-4 p-5 bg-amber-50 rounded-2xl border-2 border-amber-100">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 flex-shrink-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-black text-amber-900 text-sm uppercase tracking-widest">Kuesioner GAD7</p>
                    <p class="text-xs text-amber-600 font-bold mt-1">Cek kondisi kecemasan Anda</p>
                </div>
                <a href="/warga/input-gad" class="bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-black shadow-lg shadow-amber-900/10 uppercase tracking-widest">ISI</a>
            </div>
        </div>
        <div class="p-6 bg-gray-50 text-center">
            <button onclick="closeReminder()" class="text-gray-400 text-xs font-black uppercase tracking-[0.2em] hover:text-gray-600 transition-colors">NANTI SAJA</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
let tdHistory = [];
let gadHistory = [];

function getStatusTd(systolic, diastolic) {
    if (systolic >= 140 && diastolic >= 90) return { label: 'Hipertensi', color: 'bg-orange-100 text-orange-800', desc: 'Risiko tinggi, segera konsultasikan dengan kader' };
    if (systolic < 120 && diastolic < 80) return { label: 'Normal', color: 'bg-emerald-100 text-emerald-800', desc: 'Kondisi sehat, pertahankan pola hidup baik' };
    return { label: 'Pra-Hipertensi', color: 'bg-amber-100 text-amber-800', desc: 'Perlu waspada, jaga pola makan dan olahraga teratur' };
}

function getStatusGad(skor) {
    if (skor <= 4) return { label: 'Normal', color: 'bg-emerald-100 text-emerald-800', desc: 'Tidak ada gangguan signifikan' };
    if (skor <= 9) return { label: 'Ringan', color: 'bg-amber-100 text-amber-800', desc: 'Pertimbangkan relaksasi dan manajemen stres' };
    return { label: 'Sedang-Berat', color: 'bg-orange-100 text-orange-800', desc: 'Butuh evaluasi profesional segera' };
}

function getCardColor(kategori) {
    if (kategori === 'normal') return 'from-emerald-500 to-emerald-700';
    if (kategori === 'pra_hipertensi' || kategori === 'pre_hipertensi') return 'from-amber-500 to-amber-700';
    if (kategori === 'hipertensi') return 'from-orange-500 to-orange-700';
    return 'from-primary-500 to-primary-700';
}

async function loadDashboard() {
    const userStr = localStorage.getItem('user');
    if (!userStr) { window.location.href = '/login'; return; }
    const user = JSON.parse(userStr);
    document.getElementById('welcomeName').textContent = user?.nama_lengkap || '-';
    
    try {
        const [statusRes, jadwalRes, vidRes, matRes, gamRes, olhRes, tdRes, gadRes] = await Promise.all([
            apiCall('/status-kesehatan'),
            apiCall('/status-kesehatan/cek-jadwal'),
            apiCall('/video'),
            apiCall('/materi'),
            apiCall('/gambar'),
            apiCall('/olahraga'),
            apiCall('/tekanan-darah?per_page=7'),
            apiCall('/gad?per_page=7')
        ]);
        
        if (statusRes && statusRes.success && statusRes.data) {
            const s = statusRes.data;
            const cardColor = getCardColor(s.kategori_td);
            const cardEl = document.getElementById('kondisiCard');
            if (cardEl) {
                cardEl.classList.remove('from-primary-500', 'to-primary-700', 'from-emerald-500', 'to-emerald-700', 'from-amber-500', 'to-amber-700', 'from-orange-500', 'to-orange-700');
                cardEl.classList.add(...cardColor.split(' '));
            }
            
            if (s.tekanan_darah && s.tekanan_darah !== '0/0') {
                const [sys, dias] = s.tekanan_darah.split('/').map(Number);
                const status = getStatusTd(sys, dias);
                document.getElementById('kondisiSummary').textContent = status.label;
                document.getElementById('kondisiDesc').textContent = status.desc;
            } else {
                document.getElementById('kondisiSummary').textContent = 'Data Belum Lengkap';
                document.getElementById('kondisiDesc').textContent = 'Lakukan pengecekan kesehatan pertama Anda sekarang.';
            }

            // Reminders
            let showModal = false;
            if (jadwalRes && jadwalRes.success && jadwalRes.data) {
                const j = jadwalRes.data;
                if (j.td && !j.td.is_waiting) { document.getElementById('tdReminder').classList.remove('hidden'); showModal = true; }
                if (j.gad7 && !j.gad7.is_waiting) { document.getElementById('gadReminder').classList.remove('hidden'); showModal = true; }
            }
            if (showModal) setTimeout(() => {
                const modal = document.getElementById('reminderModal');
                if (modal) modal.classList.remove('hidden');
            }, 1000);
        }

        // Charts & History
        if (tdRes && tdRes.success && tdRes.data) {
            tdHistory = tdRes.data.data || [];
            if (tdHistory.length > 0) {
                renderTdChart(tdHistory);
                renderTdHistory(tdHistory);
            } else {
                document.getElementById('tdHistoryTable').innerHTML = '<tr><td colspan="3" class="py-8 text-center text-primary-300">Belum ada data</td></tr>';
            }
        }
        if (gadRes && gadRes.success && gadRes.data) {
            gadHistory = gadRes.data.data || [];
            if (gadHistory.length > 0) {
                renderGadChart(gadHistory);
                renderGadHistory(gadHistory);
            } else {
                document.getElementById('gadHistoryTable').innerHTML = '<tr><td colspan="3" class="py-8 text-center text-indigo-300">Belum ada data</td></tr>';
            }
        }

        renderRekomendasi({
            videos: vidRes?.data || [],
            materis: matRes?.data || [],
            gambars: gamRes?.data || [],
            olahragas: olhRes?.data || []
        });
    } catch (e) { 
        console.error('Dashboard Error:', e);
        showAlert('Gagal memuat beberapa data dashboard.');
    }
}

function renderTdChart(data) {
    const reversed = [...data].reverse();
    const ctx = document.getElementById('tdTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: reversed.map(d => new Date(d.tgl_cek).toLocaleDateString('id-ID', {day:'numeric', month:'short'})),
            datasets: [
                {
                    label: 'Sistolik',
                    data: reversed.map(d => d.systolic),
                    borderColor: '#ea580c',
                    backgroundColor: '#ea580c',
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: '#ffffff',
                    pointBorderWidth: 3
                },
                {
                    label: 'Diastolik',
                    data: reversed.map(d => d.diastolic),
                    borderColor: '#0891b2',
                    backgroundColor: '#0891b2',
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: '#ffffff',
                    pointBorderWidth: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { font: { weight: 'bold' } } }
            },
            scales: {
                y: { min: 40, max: 200, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });
}

function renderGadChart(data) {
    const reversed = [...data].reverse();
    const ctx = document.getElementById('gadTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: reversed.map(d => new Date(d.tgl_gad).toLocaleDateString('id-ID', {day:'numeric', month:'short'})),
            datasets: [{
                label: 'Skor GAD-7',
                data: reversed.map(d => d.skor),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 4,
                pointRadius: 6,
                pointBackgroundColor: '#ffffff',
                pointBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { min: 0, max: 21, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });
}

function renderTdHistory(data) {
    const tbody = document.getElementById('tdHistoryTable');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="3" class="py-8 text-center text-primary-300">Belum ada data</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(d => {
        const status = getStatusTd(d.systolic, d.diastolic);
        return `
            <tr>
                <td class="py-4 font-bold text-primary-400 text-sm">${new Date(d.tgl_cek).toLocaleDateString('id-ID')}</td>
                <td class="py-4 text-center font-black text-primary-900 text-lg">${d.systolic}/${d.diastolic}</td>
                <td class="py-4 text-center">
                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest ${status.color}">${status.label}</span>
                </td>
            </tr>
        `;
    }).join('');
}

function renderGadHistory(data) {
    const tbody = document.getElementById('gadHistoryTable');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="3" class="py-8 text-center text-indigo-300">Belum ada data</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(d => {
        const status = getStatusGad(d.skor);
        return `
            <tr>
                <td class="py-4 font-bold text-indigo-400 text-sm">${new Date(d.tgl_gad).toLocaleDateString('id-ID')}</td>
                <td class="py-4 text-center font-black text-indigo-900 text-lg">${d.skor}</td>
                <td class="py-4 text-center">
                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest ${status.color}">${status.label}</span>
                </td>
            </tr>
        `;
    }).join('');
}

function renderRekomendasi(data) {
    const container = document.getElementById('rekomendasiContainer');
    const items = [];
    
    if (data.materis?.length) {
        items.push(...data.materis.map(m => `
            <div class="bg-primary-50 rounded-[2rem] p-6 border-2 border-primary-100 hover:shadow-xl transition-all group">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center text-primary-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <p class="font-black text-primary-900 leading-tight mb-2">${m.judul}</p>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-primary-400">Materi Edukasi</span>
            </div>
        `));
    }
    if (data.videos?.length) {
        items.push(...data.videos.map(v => `
            <div class="bg-indigo-50 rounded-[2rem] p-6 border-2 border-indigo-100 hover:shadow-xl transition-all group">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </div>
                <p class="font-black text-indigo-900 leading-tight mb-2">${v.judul}</p>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-indigo-400">Video Panduan</span>
            </div>
        `));
    }
    
    container.innerHTML = items.length ? items.slice(0, 4).join('') : '<p class="col-span-full text-center py-8 text-primary-300 font-bold">Lakukan pengecekan untuk mendapatkan rekomendasi.</p>';
}

async function exportTdToExcel() {
    if (!tdHistory.length) return showAlert('Tidak ada data');
    const excelData = tdHistory.map(d => ({
        'Tanggal': new Date(d.tgl_cek).toLocaleDateString('id-ID'),
        'Systolic': d.systolic,
        'Diastolic': d.diastolic,
        'Status': getStatusTd(d.systolic, d.diastolic).label
    }));
    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Riwayat TD");
    XLSX.writeFile(workbook, `Riwayat_TD_${new Date().toISOString().split('T')[0]}.xlsx`);
}

async function exportGadToExcel() {
    if (!gadHistory.length) return showAlert('Tidak ada data');
    const excelData = gadHistory.map(d => ({
        'Tanggal': new Date(d.tgl_gad).toLocaleDateString('id-ID'),
        'Skor GAD-7': d.skor,
        'Status': getStatusGad(d.skor).label
    }));
    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Riwayat GAD7");
    XLSX.writeFile(workbook, `Riwayat_GAD7_${new Date().toISOString().split('T')[0]}.xlsx`);
}

async function copyChart(sectionId) {
    const element = document.getElementById(sectionId);
    const buttons = element.querySelectorAll('button');
    buttons.forEach(b => b.style.display = 'none');
    try {
        const canvas = await html2canvas(element, { backgroundColor: '#ffffff', scale: 2 });
        canvas.toBlob(async (blob) => {
            await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
            showAlert('Grafik berhasil disalin ke clipboard!', 'success');
        });
    } catch (e) { showAlert('Gagal menyalin grafik'); }
    finally { buttons.forEach(b => b.style.display = ''); }
}

function loadKaderInfo() {
    const container = document.getElementById('kaderInfo');
    apiCall('/users/my-kader').then(res => {
        const k = res?.data;
        if (k) {
            container.innerHTML = `
                <div class="w-20 h-20 bg-primary-100 rounded-3xl flex items-center justify-center mb-4 shadow-inner">
                    <svg class="w-10 h-10 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <p class="font-black text-black text-xl tracking-tight">${k.nama_lengkap}</p>
                <p class="text-[10px] font-black text-primary-400 uppercase tracking-[0.2em] mt-1">Kader Anda</p>
                <a href="https://wa.me/${k.no_hp?.replace(/^0/, '62')}" target="_blank" class="mt-4 text-emerald-600 font-black text-xs uppercase tracking-widest flex items-center gap-2 hover:opacity-70 transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.319 1.592 5.548 0 10.058-4.51 10.06-10.059 0-2.69-.1.046-5.104-1.93-2.926-2.023-5.032-4.949-5.035-7.874 0-5.548-4.512-10.059-10.061-10.059-1.931 0-3.534.505-5.144 1.458l-.369.216-3.36-.88 1.488 3.415-.24.381c-.881 1.4-1.345 3.037-1.346 4.717.001 5.045 4.021 9.145 9.145 9.145z"/></svg>
                    Chat WA
                </a>
            `;
        }
    });
}

function closeReminder() { document.getElementById('reminderModal').classList.add('hidden'); }

document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
    loadKaderInfo();
});
</script>
@endsection