@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">API Visualizer</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Visualisasi Real-Time Endpoint Dashboard</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10" id="summaryContainer">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Total Warga</h3>
        <div class="text-3xl font-bold text-primary-600" id="valWarga">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Total Kader</h3>
        <div class="text-3xl font-bold text-primary-600" id="valKader">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Cek TD Hari Ini</h3>
        <div class="text-3xl font-bold text-primary-600" id="valTd">-</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Cek GAD7 Hari Ini</h3>
        <div class="text-3xl font-bold text-primary-600" id="valGad">-</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tren Tekanan Darah <span class="text-gray-400 font-normal text-sm ml-2">(4 Minggu Terakhir)</span></h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-80">
        <div class="relative h-full w-full">
            <canvas id="tdBarChart"></canvas>
        </div>
        <div class="relative h-full w-full flex justify-center">
            <canvas id="tdPieChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tren GAD7 Kecemasan <span class="text-gray-400 font-normal text-sm ml-2">(4 Minggu Terakhir)</span></h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-80">
        <div class="relative h-full w-full">
            <canvas id="gadBarChart"></canvas>
        </div>
        <div class="relative h-full w-full flex justify-center">
            <canvas id="gadPieChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6b7280';
    
    let tdBar, tdPie, gadBar, gadPie;

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
                const barData = {
                    labels: labels,
                    datasets: [
                        { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#10b981', borderRadius: 4 },
                        { label: 'Pra-Hipertensi', data: data.bar_chart.map(i => i.pra_hipertensi), backgroundColor: '#f59e0b', borderRadius: 4 },
                        { label: 'Hipertensi', data: data.bar_chart.map(i => i.hipertensi), backgroundColor: '#ef4444', borderRadius: 4 }
                    ]
                };

                const barCtx = document.getElementById('tdBarChart').getContext('2d');
                tdBar = new Chart(barCtx, {
                    type: 'bar',
                    data: barData,
                    options: { responsive: true, maintainAspectRatio: false, plugins: { title: { display: true, text: 'Distribusi Kategori per Minggu' }, legend: { position: 'bottom' } } }
                });

                const pieLabels = data.pie_chart.map(item => `${item.label} (${item.persentase}%)`);
                const pieValues = data.pie_chart.map(item => item.value);
                const pieCtx = document.getElementById('tdPieChart').getContext('2d');
                tdPie = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{ data: pieValues, backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0, hoverOffset: 4 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'right' } } }
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
                const barData = {
                    labels: labels,
                    datasets: [
                        { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#10b981', borderRadius: 4 },
                        { label: 'Ringan', data: data.bar_chart.map(i => i.ringan), backgroundColor: '#f59e0b', borderRadius: 4 },
                        { label: 'Sedang-Tinggi', data: data.bar_chart.map(i => i.sedang_tinggi), backgroundColor: '#ef4444', borderRadius: 4 }
                    ]
                };

                const barCtx = document.getElementById('gadBarChart').getContext('2d');
                gadBar = new Chart(barCtx, {
                    type: 'bar',
                    data: barData,
                    options: { responsive: true, maintainAspectRatio: false, plugins: { title: { display: true, text: 'Distribusi GAD7 per Minggu' }, legend: { position: 'bottom' } } }
                });

                const pieLabels = data.pie_chart.map(item => `${item.label} (${item.persentase}%)`);
                const pieValues = data.pie_chart.map(item => item.value);
                const pieCtx = document.getElementById('gadPieChart').getContext('2d');
                gadPie = new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: pieLabels,
                        datasets: [{ data: pieValues, backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0, hoverOffset: 4 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
                });
            }
        } catch (e) { console.error("Error loading GAD charts", e); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('token')) {
            loadSummary();
            loadTdCharts();
            loadGadCharts();
        }
    });
</script>
@endsection
