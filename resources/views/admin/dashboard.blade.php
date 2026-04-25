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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Tren Tekanan Darah <span class="text-gray-400 font-normal text-sm">(4 Minggu)</span></h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8 h-64 md:h-80">
        <div class="relative h-full"><canvas id="tdBarChart"></canvas></div>
        <div class="relative h-full flex justify-center"><canvas id="tdPieChart"></canvas></div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Tren GAD7 <span class="text-gray-400 font-normal text-sm">(4 Minggu)</span></h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8 h-64 md:h-80">
        <div class="relative h-full"><canvas id="gadBarChart"></canvas></div>
        <div class="relative h-full flex justify-center"><canvas id="gadPieChart"></canvas></div>
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
            new Chart(document.getElementById('tdPieChart').getContext('2d'), {
                type: 'doughnut', responsive: true, maintainAspectRatio: false, cutout: '65%',
                data: { labels: data.pie_chart.map(i => `${i.label} (${i.persentase}%)`), datasets: [{ data: data.pie_chart.map(i => i.value), backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }] },
                options: { plugins: { legend: { position: 'right' } } }
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
            new Chart(document.getElementById('gadPieChart').getContext('2d'), {
                type: 'pie', responsive: true, maintainAspectRatio: false,
                data: { labels: data.pie_chart.map(i => `${i.label} (${i.persentase}%)`), datasets: [{ data: data.pie_chart.map(i => i.value), backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }] },
                options: { plugins: { legend: { position: 'right' } } }
            });
        }
    } catch (e) { console.error("Error loading GAD charts", e); }
}
</script>
@endsection