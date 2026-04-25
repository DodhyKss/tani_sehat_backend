@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Dashboard Warga</h1>
    <p class="text-gray-500 text-sm">Selamat datang, <span id="welcomeName">-</span></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
    <div id="kondisiCard" class="md:col-span-2 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-primary-100 text-sm mb-1">Kondisi Kesehatan Anda</p>
                <h2 class="text-2xl font-bold mb-2" id="kondisiSummary">Memuat...</h2>
                <p class="text-primary-100 text-sm opacity-80" id="kondisiDesc">-</p>
            </div>
            <div class="p-3 bg-white/20 rounded-xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="space-y-3">
            <a href="/warga/input-td" class="flex items-center gap-3 p-3 bg-red-50 hover:bg-red-100 rounded-xl transition">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-800 text-sm">Input Tekanan Darah</p>
                    <p class="text-xs text-gray-500">Cek TD hari ini</p>
                </div>
            </a>
            <a href="/warga/input-gad" class="flex items-center gap-3 p-3 bg-yellow-50 hover:bg-yellow-100 rounded-xl transition">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-800 text-sm">Kuesioner GAD7</p>
                    <p class="text-xs text-gray-500">Isi setiap 2 minggu</p>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-red-400 rounded-full"></span>
            Tekanan Darah Terakhir
        </h3>
        <div id="lastTd" class="text-center py-8 text-gray-500">Memuat...</div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
            GAD7 Terakhir
        </h3>
        <div id="lastGad" class="text-center py-8 text-gray-500">Memuat...</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-semibold text-gray-800 mb-4">Rekomendasi untuk Anda</h3>
    <div id="rekomendasiContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="col-span-full text-center py-8 text-gray-500">Memuat...</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function getStatusTd(systolic, diastolic) {
    if (systolic < 120 && diastolic < 80) return { label: 'Normal', color: 'bg-green-100 text-green-700', desc: 'Kondisi sehat, pertahankan pola hidup baik', category: 'normal' };
    if (systolic <= 139 && diastolic <= 89) return { label: 'Pra-Hipertensi', color: 'bg-yellow-100 text-yellow-700', desc: 'Perlu waspada, jaga pola makan dan olahraga teratur', category: 'pra_hipertensi' };
    return { label: 'Hipertensi', color: 'bg-red-100 text-red-700', desc: 'Risiko tinggi, konsultasikan dengan kader kesehatan', category: 'hipertensi' };
}

function getCardColor(kategori) {
    if (kategori === 'normal') return 'bg-gradient-to-br from-green-500 to-green-700';
    if (kategori === 'pre_hipertensi') return 'bg-gradient-to-br from-yellow-500 to-yellow-700';
    if (kategori === 'hipertensi') return 'bg-gradient-to-br from-red-500 to-red-700';
    return 'bg-gradient-to-br from-primary-500 to-primary-700';
}

function getStatusGad(skor) {
    if (skor <= 4) return { label: 'Normal', color: 'bg-green-100 text-green-700', desc: 'Tidak ada gangguan signifikan' };
    if (skor <= 9) return { label: 'Ringan', color: 'bg-yellow-100 text-yellow-700', desc: 'Pertimbangkan relaksasi dan manajemen stres' };
    if (skor <= 14) return { label: 'Sedang', color: 'bg-orange-100 text-orange-700', desc: 'Anjurkan konseling atau terapi' };
    return { label: 'Berat', color: 'bg-red-100 text-red-700', desc: 'Butuh evaluasi profesional segera' };
}

async function loadDashboard() {
    const user = JSON.parse(localStorage.getItem('user'));
    document.getElementById('welcomeName').textContent = user?.nama_lengkap || '-';
    
    try {
        const [statusRes, vidRes, matRes, gamRes, olhRes] = await Promise.all([
            apiCall('/status-kesehatan'),
            apiCall('/video'),
            apiCall('/materi'),
            apiCall('/gambar'),
            apiCall('/olahraga')
        ]);
        
        if (statusRes?.data) {
            const status = statusRes.data;
            
            if (status.tekanan_darah) {
                const [sys, dias] = status.tekanan_darah.split('/').map(Number);
                const tdStatus = getStatusTd(sys, dias);
                const cardColor = getCardColor(status.kategori_td);
                document.getElementById('kondisiCard').className = `md:col-span-2 ${cardColor} rounded-2xl p-6 text-white shadow-lg`;
                document.getElementById('lastTd').innerHTML = `
                    <div class="text-4xl font-bold font-mono mb-2">${status.tekanan_darah}</div>
                    <span class="px-3 py-1.5 text-sm font-semibold rounded-full ${tdStatus.color}">${tdStatus.label}</span>
                    <p class="text-sm text-gray-500 mt-2">${new Date(status.tgl_update).toLocaleDateString('id-ID')}</p>
                `;
                document.getElementById('kondisiSummary').textContent = tdStatus.label;
                document.getElementById('kondisiDesc').textContent = tdStatus.desc;
            } else {
                document.getElementById('lastTd').innerHTML = '<p class="text-gray-500">Belum ada data TD. <a href="/warga/input-td" class="text-primary-600 font-medium">Input sekarang</a></p>';
            }
            
            if (status.skor_gad) {
                const gadStatus = getStatusGad(status.skor_gad);
                document.getElementById('lastGad').innerHTML = `
                    <div class="text-4xl font-bold font-mono mb-2">${status.skor_gad}</div>
                    <span class="px-3 py-1.5 text-sm font-semibold rounded-full ${gadStatus.color}">${gadStatus.label}</span>
                    <p class="text-sm text-gray-500 mt-2">${new Date(status.tgl_update).toLocaleDateString('id-ID')}</p>
                `;
            } else {
                document.getElementById('lastGad').innerHTML = '<p class="text-gray-500">Belum ada data GAD7. <a href="/warga/input-gad" class="text-primary-600 font-medium">Isi kuesioner</a></p>';
            }
        } else {
            document.getElementById('lastTd').innerHTML = '<p class="text-gray-500">Belum ada data</p>';
            document.getElementById('lastGad').innerHTML = '<p class="text-gray-500">Belum ada data</p>';
        }
        
        renderRekomendasi({
            videos: vidRes?.data || [],
            materis: matRes?.data || [],
            gambars: gamRes?.data || [],
            olahragas: olhRes?.data || []
        });
    } catch (e) { console.error('Error:', e); }
}

function renderRekomendasi(data) {
    const container = document.getElementById('rekomendasiContainer');
    const items = [];
    
    if (data.videos?.length) {
        items.push(...data.videos.map(v => `
            <div class="bg-blue-50 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    <span class="text-xs font-semibold text-blue-600 uppercase">Video</span>
                </div>
                <p class="font-medium text-gray-800 text-sm">${v.judul}</p>
            </div>
        `));
    }
    if (data.materis?.length) {
        items.push(...data.materis.map(m => `
            <div class="bg-purple-50 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-purple-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span class="text-xs font-semibold text-purple-600 uppercase">Materi</span>
                </div>
                <p class="font-medium text-gray-800 text-sm">${m.judul}</p>
            </div>
        `));
    }
    if (data.gambars?.length) {
        items.push(...data.gambars.map(g => `
            <div class="bg-green-50 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span class="text-xs font-semibold text-green-600 uppercase">Gambar</span>
                </div>
                <p class="font-medium text-gray-800 text-sm">${g.judul}</p>
            </div>
        `));
    }
    if (data.olahragas?.length) {
        items.push(...data.olahragas.map(o => `
            <div class="bg-orange-50 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                    <span class="text-xs font-semibold text-orange-600 uppercase">Olahraga</span>
                </div>
                <p class="font-medium text-gray-800 text-sm">${o.nama_olahraga}</p>
            </div>
        `));
    }
    
    container.innerHTML = items.length ? items.join('') : '<p class="col-span-full text-center py-8 text-gray-500">Tidak ada rekomendasi</p>';
}

function loadKaderInfo() {
    const user = JSON.parse(localStorage.getItem('user'));
    const container = document.getElementById('kaderInfo');
    
    apiCall('/users/warga-kader').then(res => {
        const warga = res?.data?.find(w => w.id === user?.id);
        if (warga?.kader_nama) {
            container.innerHTML = `
                <div class="w-12 h-12 mx-auto bg-primary-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <p class="font-semibold text-gray-800">${warga.kader_nama}</p>
                <p class="text-sm text-gray-500">Kader Anda</p>
            `;
        } else {
            container.innerHTML = `
                <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <p class="font-semibold text-gray-400">Belum Ada Kader</p>
                <p class="text-xs text-gray-400">Hubungi admin</p>
            `;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
    loadKaderInfo();
});
</script>
@endsection