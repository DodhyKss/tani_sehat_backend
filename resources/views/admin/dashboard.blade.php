@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Dashboard Admin</h1>
    <p class="text-gray-500 text-sm">Monitoring seluruh data kesehatan warga</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="p-2 bg-primary-50 rounded-lg">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="text-xs text-gray-400 font-medium">Total</span>
        </div>
        <h3 class="text-gray-500 text-xs md:text-sm font-medium mb-1">Total Warga</h3>
        <div class="text-2xl md:text-3xl font-bold text-primary-600" id="valWarga">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="p-2 bg-green-50 rounded-lg">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <span class="text-xs text-gray-400 font-medium">Hari ini</span>
        </div>
        <h3 class="text-gray-500 text-xs md:text-sm font-medium mb-1">Cek TD</h3>
        <div class="text-2xl md:text-3xl font-bold text-green-600" id="valTd">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="p-2 bg-yellow-50 rounded-lg">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <span class="text-xs text-gray-400 font-medium">Hari ini</span>
        </div>
        <h3 class="text-gray-500 text-xs md:text-sm font-medium mb-1">Cek GAD7</h3>
        <div class="text-2xl md:text-3xl font-bold text-yellow-600" id="valGad">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="p-2 bg-indigo-50 rounded-lg">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <span class="text-xs text-gray-400 font-medium">Total</span>
        </div>
        <h3 class="text-gray-500 text-xs md:text-sm font-medium mb-1">Total Kader</h3>
        <div class="text-2xl md:text-3xl font-bold text-indigo-600" id="valKader">-</div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-8 mb-10">
    <div class="bg-white rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-100 p-5 md:p-10 hover:shadow-2xl hover:shadow-primary-100/10 transition-all duration-500 overflow-hidden">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Tren Tekanan Darah</h2>
                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Analisis 4 Minggu Terakhir</p>
            </div>
            <div class="hidden sm:flex gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-6 md:gap-8 h-auto lg:h-72">
            <div class="flex-1 min-h-[220px] md:min-h-[250px] relative">
                <canvas id="tdBarChart"></canvas>
            </div>
            <div class="w-full lg:w-48 xl:w-56 min-h-[220px] md:min-h-[250px] relative flex justify-center">
                <canvas id="tdPieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-100 p-5 md:p-10 hover:shadow-2xl hover:shadow-indigo-100/10 transition-all duration-500 overflow-hidden">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Tren GAD7</h2>
                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Status Psikologis Warga</p>
            </div>
            <div class="hidden sm:flex gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-6 md:gap-8 h-auto lg:h-72">
            <div class="flex-1 min-h-[220px] md:min-h-[250px] relative">
                <canvas id="gadBarChart"></canvas>
            </div>
            <div class="w-full lg:w-48 xl:w-56 min-h-[220px] md:min-h-[250px] relative flex justify-center">
                <canvas id="gadPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-100 p-5 md:p-10 mb-10 overflow-hidden">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Progres Kesehatan Warga</h2>
            <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Data Real-time Terbaru</p>
        </div>
        <a href="/admin/kesehatan" class="text-primary-600 hover:text-primary-700 text-xs md:text-sm font-bold flex items-center gap-1 group">
            Lihat Semua 
            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
    
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/50 text-gray-400 uppercase text-[10px] font-black tracking-widest border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Warga</th>
                    <th class="px-6 py-4 text-center border-x border-gray-100">TD Awal</th>
                    <th class="px-6 py-4 text-center border-r border-gray-100">TD Akhir</th>
                    <th class="px-6 py-4 text-center border-r border-gray-100">GAD7 Awal</th>
                    <th class="px-6 py-4 text-center">GAD7 Akhir</th>
                </tr>
            </thead>
            <tbody id="progresTable" class="divide-y divide-gray-50">
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Memuat data progres...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="progresCards" class="md:hidden space-y-4">
        <div class="text-center py-12 text-gray-400">Memuat data progres...</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user && user.role === 'admin') {
        loadSummary();
        loadTdCharts();
        loadGadCharts();
        loadProgresWarga();
    }
});

async function loadSummary() {
    try {
        const res = await apiCall('/dashboard/summary');
        if (res && res.success) {
            document.getElementById('valWarga').textContent = res.data.total_warga;
            document.getElementById('valKader').textContent = res.data.total_kader;
            document.getElementById('valTd').textContent = res.data.total_td_hari_ini;
            document.getElementById('valGad').textContent = res.data.total_gad_hari_ini;
        }
    } catch (e) { console.error("Error loading summary", e); }
}

async function loadTdCharts() {
    try {
        const res = await apiCall('/dashboard/tekanan-darah');
        if (res && res.success) {
            const data = res.data;
            const labels = data.bar_chart.map(item => item.label);
            new Chart(document.getElementById('tdBarChart').getContext('2d'), {
                type: 'bar', responsive: true, maintainAspectRatio: false,
                data: { labels, datasets: [
                    { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#10b981', borderRadius: 8 },
                    { label: 'Pra-Hipertensi', data: data.bar_chart.map(i => i.pra_hipertensi), backgroundColor: '#f59e0b', borderRadius: 8 },
                    { label: 'Hipertensi', data: data.bar_chart.map(i => i.hipertensi), backgroundColor: '#ef4444', borderRadius: 8 }
                ]},
                options: { 
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                }
            });
            
            const commonPieOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            };

            new Chart(document.getElementById('tdPieChart').getContext('2d'), {
                type: 'doughnut',
                data: { 
                    labels: data.pie_chart.map(i => i.label), 
                    datasets: [{ 
                        data: data.pie_chart.map(i => i.value), 
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], 
                        borderWidth: 0 
                    }] 
                },
                options: { ...commonPieOptions, cutout: '75%' }
            });
        }
    } catch (e) { console.error("Error loading TD charts", e); }
}

async function loadGadCharts() {
    try {
        const res = await apiCall('/dashboard/gad');
        if (res && res.success) {
            const data = res.data;
            const labels = data.bar_chart.map(item => item.label);
            new Chart(document.getElementById('gadBarChart').getContext('2d'), {
                type: 'bar', responsive: true, maintainAspectRatio: false,
                data: { labels, datasets: [
                    { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#10b981', borderRadius: 8 },
                    { label: 'Ringan', data: data.bar_chart.map(i => i.ringan), backgroundColor: '#f59e0b', borderRadius: 8 },
                    { label: 'Sedang-Tinggi', data: data.bar_chart.map(i => i.sedang_tinggi), backgroundColor: '#ef4444', borderRadius: 8 }
                ]},
                options: { 
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                }
            });

            new Chart(document.getElementById('gadPieChart').getContext('2d'), {
                type: 'doughnut',
                data: { 
                    labels: data.pie_chart.map(i => i.label), 
                    datasets: [{ 
                        data: data.pie_chart.map(i => i.value), 
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], 
                        borderWidth: 0 
                    }] 
                },
                options: { cutout: '75%', plugins: { legend: { position: 'bottom' } } }
            });
        }
    } catch (e) { console.error("Error loading GAD charts", e); }
}

async function loadProgresWarga() {
    try {
        const res = await apiCall('/dashboard/progres-warga');
        const tbody = document.getElementById('progresTable');
        const cards = document.getElementById('progresCards');
        
        if (res && res.success) {
            if (res.data.length === 0) {
                if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada data progres</td></tr>';
                if (cards) cards.innerHTML = '<div class="text-center py-12 text-gray-400">Tidak ada data progres</div>';
                return;
            }

            const getBadge = (status) => {
                if (status === 'normal') return 'bg-green-100 text-green-700';
                if (status === 'pra_hipertensi' || status === 'ringan') return 'bg-yellow-100 text-yellow-700';
                if (status === 'hipertensi' || status === 'sedang' || status === 'tinggi') return 'bg-red-100 text-red-700';
                return 'bg-gray-100 text-gray-600';
            };

            if (tbody) {
                tbody.innerHTML = res.data.map(item => `
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900">${item.nama}</td>
                        <td class="px-6 py-4 text-center border-x border-gray-50">
                            <div class="font-mono text-xs">${item.td.awal}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.td.status_awal)}">${item.td.status_awal.replace('_', ' ')}</span>
                        </td>
                        <td class="px-6 py-4 text-center border-r border-gray-50">
                            <div class="font-mono text-xs font-bold text-primary-700">${item.td.akhir}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.td.status_akhir)}">${item.td.status_akhir.replace('_', ' ')}</span>
                        </td>
                        <td class="px-6 py-4 text-center border-r border-gray-50">
                            <div class="font-mono text-xs">Skor: ${item.gad.awal}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.gad.status_awal)}">${item.gad.status_awal.replace('_', ' ')}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-mono text-xs font-bold text-primary-700">Skor: ${item.gad.akhir}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.gad.status_akhir)}">${item.gad.status_akhir.replace('_', ' ')}</span>
                        </td>
                    </tr>
                `).join('');
            }

            if (cards) {
                cards.innerHTML = res.data.map(item => `
                    <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100">
                        <p class="font-black text-gray-900 text-lg mb-4 border-b border-gray-100 pb-2">${item.nama}</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="bg-white p-3 rounded-xl border border-gray-50 shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase mb-1">TD Awal</p>
                                    <p class="text-sm font-bold text-gray-700">${item.td.awal}</p>
                                    <span class="text-[8px] font-bold ${getBadge(item.td.status_awal)} px-1.5 py-0.5 rounded-full uppercase">${item.td.status_awal.replace('_', ' ')}</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-gray-50 shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase mb-1">GAD Awal</p>
                                    <p class="text-sm font-bold text-gray-700">Skor: ${item.gad.awal}</p>
                                    <span class="text-[8px] font-bold ${getBadge(item.gad.status_awal)} px-1.5 py-0.5 rounded-full uppercase">${item.gad.status_awal.replace('_', ' ')}</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="bg-primary-50/30 p-3 rounded-xl border border-primary-100 shadow-sm">
                                    <p class="text-[9px] font-black text-primary-400 uppercase mb-1">TD Akhir</p>
                                    <p class="text-sm font-black text-primary-700">${item.td.akhir}</p>
                                    <span class="text-[8px] font-bold ${getBadge(item.td.status_akhir)} px-1.5 py-0.5 rounded-full uppercase">${item.td.status_akhir.replace('_', ' ')}</span>
                                </div>
                                <div class="bg-primary-50/30 p-3 rounded-xl border border-primary-100 shadow-sm">
                                    <p class="text-[9px] font-black text-primary-400 uppercase mb-1">GAD Akhir</p>
                                    <p class="text-sm font-black text-primary-700">Skor: ${item.gad.akhir}</p>
                                    <span class="text-[8px] font-bold ${getBadge(item.gad.status_akhir)} px-1.5 py-0.5 rounded-full uppercase">${item.gad.status_akhir.replace('_', ' ')}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (e) { console.error("Error loading progress table", e); }
}
</script>
@endsection