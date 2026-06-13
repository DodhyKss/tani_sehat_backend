@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Dashboard Admin</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Monitoring Real-Time Kesehatan Warga TaniSehat</p>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 mb-10">
    <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-[2rem] p-6 md:p-8 text-white shadow-xl shadow-primary-900/10 border border-primary-500/20">
        <div class="flex items-center justify-between mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="text-xs font-black uppercase tracking-widest opacity-60">Total</span>
        </div>
        <h3 class="text-primary-100 text-sm font-bold uppercase tracking-wider mb-2">Total Warga</h3>
        <div class="text-3xl md:text-5xl font-black" id="valWarga">-</div>
    </div>
    
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-[2rem] p-6 md:p-8 text-white shadow-xl shadow-blue-900/10 border border-blue-500/20">
        <div class="flex items-center justify-between mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <span class="text-xs font-black uppercase tracking-widest opacity-60">Hari Ini</span>
        </div>
        <h3 class="text-blue-100 text-sm font-bold uppercase tracking-wider mb-2">Cek TD</h3>
        <div class="text-3xl md:text-5xl font-black" id="valTd">-</div>
    </div>

    <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-[2rem] p-6 md:p-8 text-white shadow-xl shadow-orange-900/10 border border-orange-500/20">
        <div class="flex items-center justify-between mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <span class="text-xs font-black uppercase tracking-widest opacity-60">Hari Ini</span>
        </div>
        <h3 class="text-orange-100 text-sm font-bold uppercase tracking-wider mb-2">Cek GAD7</h3>
        <div class="text-3xl md:text-5xl font-black" id="valGad">-</div>
    </div>

    <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-[2rem] p-6 md:p-8 text-white shadow-xl shadow-purple-900/10 border border-purple-500/20">
        <div class="flex items-center justify-between mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <span class="text-xs font-black uppercase tracking-widest opacity-60">Total</span>
        </div>
        <h3 class="text-purple-100 text-sm font-bold uppercase tracking-wider mb-2">Total Kader</h3>
        <div class="text-3xl md:text-5xl font-black" id="valKader">-</div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-8 mb-10">
    <div id="capture-td-status" class="bg-white rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-100 p-5 md:p-10 hover:shadow-2xl hover:shadow-primary-100/10 transition-all duration-500 overflow-hidden">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Status Tekanan Darah</h2>
                <p class="text-[9px] md:text-[10px] font-black text-primary-400 uppercase tracking-[0.2em] mt-1">Distribusi Keseluruhan Warga</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex gap-2 mr-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                </div>
                <button onclick="copyChart('capture-td-status')" class="p-2.5 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-xl transition-all shadow-sm flex items-center gap-2 group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest hidden md:inline">Salin</span>
                </button>
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

    <div id="capture-gad-status" class="bg-white rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-100 p-5 md:p-10 hover:shadow-2xl hover:shadow-indigo-100/10 transition-all duration-500 overflow-hidden">
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <div>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Status GAD-7</h2>
                <p class="text-[9px] md:text-[10px] font-black text-primary-400 uppercase tracking-[0.2em] mt-1">Kesehatan Psikologis Keseluruhan</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex gap-2 mr-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                </div>
                <button onclick="copyChart('capture-gad-status')" class="p-2.5 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-xl transition-all shadow-sm flex items-center gap-2 group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest hidden md:inline">Salin</span>
                </button>
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

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-6 md:p-10 mb-10 overflow-hidden">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-black tracking-tight">Progres Kesehatan Warga</h2>
            <p class="text-primary-800 text-base font-bold mt-1 uppercase tracking-widest opacity-60">Data Real-time Terbaru</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="exportToExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 transition-all shadow-lg shadow-emerald-900/10 uppercase tracking-widest">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                EXCEL
            </button>
            <a href="/admin/kesehatan" class="bg-primary-50 text-primary-800 hover:bg-primary-100 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 transition-all group uppercase tracking-widest">
                LIHAT RIWAYAT
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    
    <div class="hidden md:block overflow-x-auto p-8">
        <table class="border-collapse">
            <thead>
                <tr>
                    <th>Warga (NIK)</th>
                    <th class="text-center">Profil</th>
                    <th class="text-center">TD Awal</th>
                    <th class="text-center">TD Akhir</th>
                    <th class="text-center">GAD7 Awal</th>
                    <th class="text-center">GAD7 Akhir</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tindak Lanjut Terbaru</th>
                    <th class="text-center">Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody id="progresTable">
                <tr><td colspan="9" class="px-6 py-20 text-center text-primary-300 font-bold italic animate-pulse text-xl">Memuat data progres...</td></tr>
            </tbody>
        </table>
    </div>

    <div id="progresPagination" class="mt-4 flex justify-center gap-2"></div>

    <div id="progresCards" class="md:hidden space-y-6">
        <div class="text-center py-12 text-primary-300 font-bold italic">Memuat data progres...</div>
    </div>
</div>

<!-- Modal Tindak Lanjut -->
<div id="tindakLanjutModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-primary-900/80 backdrop-blur-sm" onclick="closeTindakLanjutModal()"></div>
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg">
        <div class="p-8 border-b-2 border-primary-50 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-black text-black">Tindak Lanjut</h3>
                <p id="tlUserName" class="text-primary-800 text-sm font-bold uppercase"></p>
            </div>
            <button onclick="closeTindakLanjutModal()" class="p-3 hover:bg-primary-50 rounded-2xl text-primary-300 transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="tindakLanjutForm" class="p-8 space-y-6">
            <input type="hidden" id="tlUserId">
            <div class="space-y-2">
                <label class="text-xs font-black text-primary-800 uppercase">Jenis Evaluasi</label>
                <select id="tlJenis" onchange="updateTindakLanjutOptions()" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-black" required>
                    <option value="td">Tekanan Darah (TD)</option>
                    <option value="gad7">Kuesioner GAD-7</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-xs font-black text-primary-800 uppercase">Pilih Tindakan</label>
                <select id="tlMasterId" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-black" required>
                    <option value="">Pilih Tindakan...</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-primary-800 hover:bg-black text-white font-black py-4 rounded-2xl transition-all shadow-xl uppercase">SIMPAN TINDAKAN</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
let progresData = [];

async function exportToExcel() {
    if (progresData.length === 0) {
        showAlert('Tidak ada data untuk diekspor');
        return;
    }
    
    const excelData = progresData.map(item => ({
        'Nama Warga': item.nama,
        'NIK': item.nik,
        'Umur': item.umur,
        'L/P': item.jenis_kelamin,
        'TD Awal': item.td.awal,
        'Status TD Awal': item.td.status_awal.toUpperCase().replace('_', ' '),
        'TD Akhir': item.td.akhir,
        'Status TD Akhir': item.td.status_akhir.toUpperCase().replace('_', ' '),
        'GAD7 Awal (Skor)': item.gad.awal,
        'Status GAD7 Awal': item.gad.status_awal.toUpperCase().replace('_', ' '),
        'GAD7 Akhir (Skor)': item.gad.akhir,
        'Status GAD7 Akhir': item.gad.status_akhir.toUpperCase().replace('_', ' '),
        'Status Perubahan': item.status_perubahan,
        'Tindak Lanjut Terbaru': item.tindak_lanjut
    }));

    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Progres Kesehatan");
    XLSX.writeFile(workbook, `Progres_Kesehatan_TaniSehat_${new Date().toISOString().split('T')[0]}.xlsx`);
}

async function copyChart(sectionId) {
    const element = document.getElementById(sectionId);
    if (!element) return;
    
    // Hide buttons during capture
    const buttons = element.querySelectorAll('button');
    buttons.forEach(b => b.style.display = 'none');

    try {
        const canvas = await html2canvas(element, {
            backgroundColor: '#ffffff',
            scale: 2,
            logging: false,
            useCORS: true
        });

        canvas.toBlob(async (blob) => {
            try {
                await navigator.clipboard.write([
                    new ClipboardItem({ 'image/png': blob })
                ]);
                showAlert('Grafik & Data berhasil disalin ke clipboard!', 'success');
            } catch (err) {
                console.error(err);
                showAlert('Gagal menyalin ke clipboard.');
            }
        });
    } catch (err) {
        console.error(err);
        showAlert('Gagal menangkap gambar.');
    } finally {
        buttons.forEach(b => b.style.display = '');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadSummary();
    loadTdCharts();
    loadGadCharts();
    loadProgresWarga();
    loadMasterTindakLanjut();
});

let masterTindakLanjut = [];
async function loadMasterTindakLanjut() {
    try {
        const res = await apiCall('/master-tindak-lanjut');
        if (res && res.data) {
            masterTindakLanjut = res.data;
        }
    } catch (e) { console.error(e); }
}

function openTindakLanjutModal(user) {
    document.getElementById('tlUserId').value = user.id;
    document.getElementById('tlUserName').textContent = user.nama_lengkap;
    updateTindakLanjutOptions();
    document.getElementById('tindakLanjutModal').classList.remove('hidden');
}

function closeTindakLanjutModal() {
    document.getElementById('tindakLanjutModal').classList.add('hidden');
}

function updateTindakLanjutOptions() {
    const jenis = document.getElementById('tlJenis').value;
    const select = document.getElementById('tlMasterId');
    select.innerHTML = '<option value="">Pilih Tindakan...</option>';
    
    const filtered = masterTindakLanjut.filter(item => item.jenis_tindakan === jenis);
    filtered.forEach(item => {
        select.innerHTML += `<option value="${item.id}">[${item.kategori.replace('_',' ')}] ${item.nama_tindakan}</option>`;
    });
}

document.getElementById('tindakLanjutForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        user_id: document.getElementById('tlUserId').value,
        tindak_lanjut_id: document.getElementById('tlMasterId').value
    };
    
    const res = await apiCall('/tindak-lanjut', 'POST', data);
    if (res && res.success) {
        showAlert('Tindak Lanjut berhasil ditambahkan', 'success');
        closeTindakLanjutModal();
    } else {
        showAlert(res?.message || 'Gagal menyimpan tindak lanjut');
    }
});

async function loadSummary() {
    try {
        const user = JSON.parse(localStorage.getItem('user'));
        const endpoint = user.role === 'kader' ? `/kader/dashboard?kader_id=${user.id}` : '/dashboard/summary';
        const res = await apiCall(endpoint);
        
        if (res && res.success) {
            if (user.role === 'kader') {
                document.getElementById('valWarga').textContent = res.data.warga_count;
                document.getElementById('valTd').textContent = res.data.td_today;
                document.getElementById('valGad').textContent = res.data.gad_today;
                document.getElementById('valChat').textContent = res.data.new_chat;
            } else {
                document.getElementById('valWarga').textContent = res.data.total_warga;
                document.getElementById('valTd').textContent = res.data.total_td_hari_ini;
                document.getElementById('valGad').textContent = res.data.total_gad_hari_ini;
                document.getElementById('valKader').textContent = res.data.total_kader;
            }
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
                    { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#059669', borderRadius: 12 },
                    { label: 'Pra-Hipertensi', data: data.bar_chart.map(i => i.pra_hipertensi), backgroundColor: '#d97706', borderRadius: 12 },
                    { label: 'Hipertensi', data: data.bar_chart.map(i => i.hipertensi), backgroundColor: '#ea580c', borderRadius: 12 }
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
                    labels: data.pie_chart.map(i => `${i.label} (${i.persentase}%)`), 
                    datasets: [{ 
                        data: data.pie_chart.map(i => i.persentase), 
                        backgroundColor: ['#10b981', '#f59e0b', '#ea580c'], 
                        borderWidth: 0 
                    }] 
                },
                options: { 
                    ...commonPieOptions, 
                    cutout: '75%',
                    plugins: {
                        ...commonPieOptions.plugins,
                        tooltip: {
                            callbacks: {
                                label: (context) => ` ${context.label}: ${context.raw}%`
                            }
                        }
                    }
                }
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
                    { label: 'Normal', data: data.bar_chart.map(i => i.normal), backgroundColor: '#059669', borderRadius: 12 },
                    { label: 'Ringan', data: data.bar_chart.map(i => i.ringan), backgroundColor: '#d97706', borderRadius: 12 },
                    { label: 'Sedang-Tinggi', data: data.bar_chart.map(i => i.sedang_tinggi), backgroundColor: '#ea580c', borderRadius: 12 }
                ]},
                options: { 
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                }
            });

            new Chart(document.getElementById('gadPieChart').getContext('2d'), {
                type: 'doughnut',
                data: { 
                    labels: data.pie_chart.map(i => `${i.label} (${i.persentase}%)`), 
                    datasets: [{ 
                        data: data.pie_chart.map(i => i.persentase), 
                        backgroundColor: ['#10b981', '#f59e0b', '#ea580c'], 
                        borderWidth: 0 
                    }] 
                },
                options: { 
                    cutout: '75%', 
                    plugins: { 
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => ` ${context.label}: ${context.raw}%`
                            }
                        }
                    } 
                }
            });
        }
    } catch (e) { console.error("Error loading GAD charts", e); }
}

async function loadProgresWarga(page = 1) {
    try {
        const res = await apiCall(`/dashboard/progres-warga?page=${page}`);
        const tbody = document.getElementById('progresTable');
        const cards = document.getElementById('progresCards');
        
        if (res && res.success) {
            progresData = res.data;
            if (res.data.length === 0) {
                if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada data progres</td></tr>';
                if (cards) cards.innerHTML = '<div class="text-center py-12 text-gray-400">Tidak ada data progres</div>';
                return;
            }

            const getBadge = (status) => {
                if (status === 'normal') return 'bg-emerald-100 text-emerald-800';
                if (status === 'pra_hipertensi' || status === 'pre_hipertensi' || status === 'ringan') return 'bg-amber-100 text-amber-800';
                if (status === 'hipertensi' || status === 'sedang' || status === 'tinggi') return 'bg-orange-100 text-orange-800';
                return 'bg-primary-50 text-primary-400';
            };

            if (tbody) {
                tbody.innerHTML = (res.data.data || res.data).map(item => `
                    <tr class="hover:bg-primary-50/50 transition-colors group">
                        <td class="px-6 py-4 border border-primary-50">
                            <div class="font-bold text-black">${item.nama}</div>
                            <div class="text-[10px] text-primary-400 font-medium">NIK: ${item.nik || '-'}</div>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50">
                            <div class="font-bold text-primary-800 text-sm">${item.umur} Thn</div>
                            <div class="text-[10px] text-primary-400 font-medium uppercase">${(item.jenis_kelamin?.toLowerCase() === 'l' || item.jenis_kelamin?.toLowerCase() === 'laki-laki') ? 'L' : 'P'}</div>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50">
                            <div class="font-bold text-sm text-gray-600">${item.td.awal}</div>
                            <div class="text-[9px] font-black uppercase tracking-tighter ${getBadge(item.td.status_awal)} px-2 rounded">${item.td.status_awal.replace('pre_hipertensi', 'Pra-Hiper').replace('_', ' ')}</div>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50 bg-primary-50/20">
                            <div class="font-black text-base text-primary-600">${item.td.akhir}</div>
                            <div class="text-[9px] font-black uppercase tracking-tighter ${getBadge(item.td.status_akhir)} px-2 rounded">${item.td.status_akhir.replace('pre_hipertensi', 'Pra-Hiper').replace('_', ' ')}</div>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50">
                            <div class="font-bold text-sm text-gray-600">${item.gad.awal}</div>
                            <div class="text-[9px] font-black uppercase tracking-tighter ${getBadge(item.gad.status_awal)} px-2 rounded">${item.gad.status_awal.replace('_', ' ')}</div>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50 bg-primary-50/20">
                            <div class="font-black text-base text-primary-600">${item.gad.akhir}</div>
                            <div class="text-[9px] font-black uppercase tracking-tighter ${getBadge(item.gad.status_akhir)} px-2 rounded">${item.gad.status_akhir.replace('_', ' ')}</div>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50">
                            <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-tighter ${item.status_perubahan === 'Ada Perubahan' ? 'bg-amber-100 text-amber-800' : (item.status_perubahan === 'Tetap' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-400')}">
                                ${item.status_perubahan}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50">
                            <span class="text-xs font-bold text-gray-600">${item.tindak_lanjut}</span>
                        </td>
                        <td class="px-6 py-4 text-center border border-primary-50">
                            <button onclick='openTindakLanjutModal(${JSON.stringify(item)})' class="p-2 bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-600 transition-all transform hover:scale-110 shadow-sm" title="Tindak Lanjut">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
            window.renderTablePagination(res.data, 'progresPagination', 'loadProgresWarga');

            if (cards) {
                cards.innerHTML = res.data.map(item => `
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-primary-900/5 border border-primary-100 mb-4">
                        <div class="border-b-2 border-primary-50 pb-4 mb-6 flex justify-between items-start">
                            <div>
                                <p class="font-black text-black text-2xl leading-tight">${item.nama}</p>
                                <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">NIK: ${item.nik || '-'} • ${item.umur} Thn • ${item.jenis_kelamin}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div class="bg-primary-50/50 p-4 rounded-2xl border border-primary-100">
                                    <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1">TD Awal</p>
                                    <p class="text-lg font-black text-primary-900">${item.td.awal}</p>
                                    <span class="text-[9px] font-black ${getBadge(item.td.status_awal)} px-2 py-1 rounded-lg uppercase tracking-wider">${item.td.status_awal.replace('pre_hipertensi', 'Pra-Hipertensi').replace('_', ' ')}</span>
                                </div>
                                <div class="bg-primary-50/50 p-4 rounded-2xl border border-primary-100">
                                    <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1">GAD Awal</p>
                                    <p class="text-lg font-black text-primary-900">Skor: ${item.gad.awal}</p>
                                    <span class="text-[9px] font-black ${getBadge(item.gad.status_awal)} px-2 py-1 rounded-lg uppercase tracking-wider">${item.gad.status_awal.replace('_', ' ')}</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-primary-800 p-4 rounded-2xl shadow-lg">
                                    <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1">TD Akhir</p>
                                    <p class="text-xl font-black text-white">${item.td.akhir}</p>
                                    <span class="text-[9px] font-black ${getBadge(item.td.status_akhir)} px-2 py-1 rounded-lg uppercase tracking-wider shadow-sm">${item.td.status_akhir.replace('pre_hipertensi', 'Pra-Hipertensi').replace('_', ' ')}</span>
                                </div>
                                <div class="bg-primary-800 p-4 rounded-2xl shadow-lg">
                                    <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1">GAD Akhir</p>
                                    <p class="text-xl font-black text-white">Skor: ${item.gad.akhir}</p>
                                    <span class="text-[9px] font-black ${getBadge(item.gad.status_akhir)} px-2 py-1 rounded-lg uppercase tracking-wider shadow-sm">${item.gad.status_akhir.replace('_', ' ')}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-primary-50 text-center">
                            <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mb-1">Tindak Lanjut Terbaru</p>
                            <p class="text-sm font-bold text-gray-800">${item.tindak_lanjut}</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-primary-50">
                            <button onclick='openTindakLanjutModal(${JSON.stringify(item)})' class="w-full py-2 bg-blue-50 hover:bg-blue-100 rounded-xl text-blue-600 transition-all font-black text-xs shadow-sm flex items-center justify-center gap-2 uppercase tracking-widest">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                TINDAK LANJUT
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (e) { console.error("Error loading progress table", e); }
}
</script>
@endsection