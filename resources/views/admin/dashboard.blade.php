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

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-10 hover:shadow-2xl hover:shadow-primary-100/10 transition-all duration-500 overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Tren Tekanan Darah</h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Analisis 4 Minggu Terakhir</p>
            </div>
            <div class="hidden sm:flex gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 h-auto lg:h-72">
            <div class="flex-1 min-h-[250px] relative">
                <canvas id="tdBarChart"></canvas>
            </div>
            <div class="w-full lg:w-48 xl:w-56 min-h-[250px] relative flex justify-center">
                <canvas id="tdPieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-10 hover:shadow-2xl hover:shadow-indigo-100/10 transition-all duration-500 overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Tren GAD7</h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Status Psikologis Warga</p>
            </div>
            <div class="hidden sm:flex gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 h-auto lg:h-72">
            <div class="flex-1 min-h-[250px] relative">
                <canvas id="gadBarChart"></canvas>
            </div>
            <div class="w-full lg:w-48 xl:w-56 min-h-[250px] relative flex justify-center">
                <canvas id="gadPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">Progres Kesehatan Warga</h2>
        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Awal vs Akhir</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3">Nama Warga</th>
                    <th class="px-4 py-3 text-center border-x border-gray-100">TD Awal</th>
                    <th class="px-4 py-3 text-center border-r border-gray-100">TD Akhir</th>
                    <th class="px-4 py-3 text-center border-r border-gray-100">GAD7 Awal</th>
                    <th class="px-4 py-3 text-center">GAD7 Akhir</th>
                </tr>
            </thead>
            <tbody id="progresTable" class="divide-y divide-gray-100">
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Memuat data progres...</td></tr>
            </tbody>
        </table>
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
            const labels = data.bar_chart.map(item => item.minggu);
            new Chart(document.getElementById('tdBarChart').getContext('2d'), {
                type: 'bar', responsive: true, maintainAspectRatio: false,
                data: { labels, datasets: [
                    { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#10b981', borderRadius: 4 },
                    { label: 'Pra-Hipertensi', data: data.bar_chart.map(i => i.pra_hipertensi), backgroundColor: '#f59e0b', borderRadius: 4 },
                    { label: 'Hipertensi', data: data.bar_chart.map(i => i.hipertensi), backgroundColor: '#ef4444', borderRadius: 4 }
                ]},
                options: { plugins: { legend: { position: 'bottom' } } }
            });
            
            const commonPieOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: { size: 10, weight: '700' },
                            color: '#9ca3af'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#111827',
                        bodyColor: '#4b5563',
                        borderColor: '#f3f4f6',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                    }
                },
                layout: { padding: 10 }
            };

            new Chart(document.getElementById('tdPieChart').getContext('2d'), {
                type: 'doughnut',
                data: { 
                    labels: data.pie_chart.map(i => i.label), 
                    datasets: [{ 
                        data: data.pie_chart.map(i => i.value), 
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], 
                        hoverOffset: 20,
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
            const labels = data.bar_chart.map(item => item.minggu);
            new Chart(document.getElementById('gadBarChart').getContext('2d'), {
                type: 'bar', responsive: true, maintainAspectRatio: false,
                data: { labels, datasets: [
                    { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#10b981', borderRadius: 4 },
                    { label: 'Ringan', data: data.bar_chart.map(i => i.ringan), backgroundColor: '#f59e0b', borderRadius: 4 },
                    { label: 'Sedang-Tinggi', data: data.bar_chart.map(i => i.sedang_tinggi), backgroundColor: '#ef4444', borderRadius: 4 }
                ]},
                options: { plugins: { legend: { position: 'bottom' } } }
            });

            const commonPieOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: { size: 10, weight: '700' },
                            color: '#9ca3af'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#111827',
                        bodyColor: '#4b5563',
                        borderColor: '#f3f4f6',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                    }
                },
                layout: { padding: 10 }
            };

            new Chart(document.getElementById('gadPieChart').getContext('2d'), {
                type: 'pie',
                data: { 
                    labels: data.pie_chart.map(i => i.label), 
                    datasets: [{ 
                        data: data.pie_chart.map(i => i.value), 
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], 
                        hoverOffset: 20,
                        borderWidth: 0 
                    }] 
                },
                options: commonPieOptions
            });
        }
    } catch (e) { console.error("Error loading GAD charts", e); }
}

async function loadProgresWarga() {
    try {
        const res = await apiCall('/dashboard/progres-warga');
        const tbody = document.getElementById('progresTable');
        if (res && res.success) {
            if (res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data progres</td></tr>';
                return;
            }

            tbody.innerHTML = res.data.map(item => {
                const getBadge = (status) => {
                    if (status === 'normal') return 'bg-green-100 text-green-700';
                    if (status === 'pra_hipertensi' || status === 'ringan') return 'bg-yellow-100 text-yellow-700';
                    if (status === 'hipertensi' || status === 'sedang_tinggi') return 'bg-red-100 text-red-700';
                    return 'bg-gray-100 text-gray-600';
                };

                return `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 font-semibold text-gray-800">${item.nama}</td>
                        <td class="px-4 py-4 text-center border-x border-gray-100">
                            <div class="font-mono text-xs">${item.td.awal}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.td.status_awal)}">${item.td.status_awal.replace('_', ' ')}</span>
                        </td>
                        <td class="px-4 py-4 text-center border-r border-gray-100">
                            <div class="font-mono text-xs font-bold text-primary-700">${item.td.akhir}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.td.status_akhir)}">${item.td.status_akhir.replace('_', ' ')}</span>
                        </td>
                        <td class="px-4 py-4 text-center border-r border-gray-100">
                            <div class="font-mono text-xs">Skor: ${item.gad.awal}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.gad.status_awal)}">${item.gad.status_awal.replace('_', ' ')}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="font-mono text-xs font-bold text-primary-700">Skor: ${item.gad.akhir}</div>
                            <span class="px-2 py-0.5 text-[10px] rounded-full uppercase font-bold ${getBadge(item.gad.status_akhir)}">${item.gad.status_akhir.replace('_', ' ')}</span>
                        </td>
                    </tr>
                `;
            }).join('');
        }
    } catch (e) { console.error("Error loading progress table", e); }
}
</script>
@endsection