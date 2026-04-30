@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="/warga" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 transition mb-4">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Input Tekanan Darah</h1>
    <p class="text-gray-600 text-lg">Catat tekanan darah Anda dengan mudah</p>
</div>

<div class="max-w-full mx-auto">
    <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-3xl shadow-xl p-6 md:p-8 text-white border border-primary-500/30">
        <div class="text-center mb-8">
            <div class="inline-flex justify-center items-center p-4 bg-white/20 rounded-full mb-4">
                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-white">Ukur Tekanan Darah Anda</h2>
            <p class="text-primary-100 text-lg font-medium mt-2">Masukkan hasil pengukuran dari alat tensimeter</p>
        </div>

        <form id="tdForm" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-lg font-bold text-white/90 mb-2">Sistole (mmHg)</label>
                    <input type="number" id="systolic" min="60" max="250" required placeholder="120" class="w-full px-4 py-5 rounded-2xl bg-white/10 border-2 border-white/20 text-center text-3xl font-extrabold text-white focus:ring-4 focus:ring-primary-500/30 focus:border-white/50 transition-all placeholder:text-white/30 outline-none">
                </div>
                <div>
                    <label class="block text-lg font-bold text-white/90 mb-2">Diastole (mmHg)</label>
                    <input type="number" id="diastolic" min="40" max="150" required placeholder="80" class="w-full px-4 py-5 rounded-2xl bg-white/10 border-2 border-white/20 text-center text-3xl font-extrabold text-white focus:ring-4 focus:ring-primary-500/30 focus:border-white/50 transition-all placeholder:text-white/30 outline-none">
                </div>
            </div>

            <div class="bg-primary-900/30 rounded-2xl p-4 border border-primary-400/20">
                <p class="text-base font-bold text-primary-100 text-center mb-2">Tanggal Pengukuran</p>
                <input type="date" id="tgl_cek" required class="w-full px-4 py-3 rounded-xl bg-white/10 border-2 border-white/20 text-center font-bold text-lg text-white focus:ring-4 focus:ring-primary-500/30 focus:border-white/50 transition-all outline-none">
            </div>

            <div id="previewResult" class="hidden bg-primary-900/40 rounded-[2rem] p-8 text-center border-2 border-white/20 shadow-2xl backdrop-blur-md">
                <h3 class="font-bold text-white/70 uppercase tracking-widest text-sm mb-4">Analisa Hasil</h3>
                <div class="text-6xl font-black mb-3 text-white tracking-tighter" id="previewSystolic">-</div>
                <div class="text-primary-100 text-lg font-bold mb-6">mmHg</div>
                <div class="inline-block">
                    <span id="previewStatus" class="px-8 py-3 text-xl font-black rounded-2xl shadow-lg ring-4 ring-white/10">-</span>
                </div>
            </div>

            <button type="submit" class="w-full bg-white hover:bg-primary-50 text-primary-800 text-2xl font-black py-6 rounded-[1.5rem] transition-all shadow-2xl flex justify-center items-center gap-3 transform hover:scale-[1.02] active:scale-[0.98] mt-8">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>SIMPAN SEKARANG</span>
                <div class="loader border-primary-800" id="loader"></div>
            </button>
        </form>
    </div>

    <div class="mt-6 bg-gradient-to-br from-primary-600 to-primary-800 rounded-3xl shadow-xl p-6 text-white border border-primary-500/30">
        <h3 class="font-bold text-xl text-white mb-6">Referensi Kategori</h3>
        <div class="space-y-3">
            <div class="flex items-center gap-4 p-4 bg-white/10 rounded-xl border border-white/10">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">N</div>
                <div class="flex-1">
                    <p class="font-bold text-white text-lg">Normal</p>
                    <p class="text-base text-primary-100 font-medium text-xs">Sistole < 120 DAN Diastole < 80</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white/10 rounded-xl border border-white/10">
                <div class="w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">H</div>
                <div class="flex-1">
                    <p class="font-bold text-white text-lg">Hipertensi</p>
                    <p class="text-base text-primary-100 font-medium text-xs">Sistole ≥ 140 DAN Diastole ≥ 90</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white/10 rounded-xl border border-white/10">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">P</div>
                <div class="flex-1">
                    <p class="font-bold text-white text-lg">Pra-Hipertensi</p>
                    <p class="text-base text-primary-100 font-medium text-xs">Kondisi di antara Normal dan Hipertensi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="resultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md">
    <div class="absolute inset-0 bg-black/70" onclick="closeResultModal()"></div>
    <div class="relative bg-gradient-to-br from-primary-700 to-primary-900 rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 text-center text-white border border-white/20">
        <div id="resultIcon" class="w-24 h-24 mx-auto rounded-full flex items-center justify-center mb-6 shadow-2xl ring-4 ring-white/10"></div>
        <h3 class="text-3xl font-black mb-4 tracking-tight" id="resultTitle">Hasil</h3>
        <div class="bg-white/10 rounded-2xl p-8 mb-8">
            <div class="text-7xl font-black tracking-tighter mb-2" id="resultValue">-</div>
            <p class="text-primary-200 uppercase font-black tracking-widest text-sm">mmHg</p>
        </div>
        <div class="inline-block mb-8">
            <span id="resultStatus" class="px-8 py-3 text-2xl font-black rounded-2xl shadow-lg ring-4 ring-white/10">-</span>
        </div>
        <p class="text-primary-100 text-lg font-medium leading-relaxed mb-10" id="resultDesc">-</p>
        <a href="/warga" class="w-full inline-block bg-white text-primary-800 font-black py-5 rounded-2xl shadow-xl hover:bg-primary-50 transition-all text-xl">
            Selesai
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
const today = new Date().toISOString().split('T')[0];
document.getElementById('tgl_cek').value = today;
document.getElementById('tgl_cek').max = today;

function getStatus(systolic, diastolic) {
    if (systolic >= 140 && diastolic >= 90) return { label: 'Hipertensi', color: 'bg-orange-100 text-orange-800', icon: 'bg-orange-100', desc: 'Risiko tinggi. Segera konsultasikan dengan kader kesehatan.' };
    if (systolic < 120 && diastolic < 80) return { label: 'Normal', color: 'bg-emerald-100 text-emerald-700', icon: 'bg-emerald-100', desc: 'Kondisi sehat. Pertahankan pola hidup baik!' };
    return { label: 'Pra-Hipertensi', color: 'bg-amber-100 text-amber-700', icon: 'bg-amber-100', desc: 'Perlu waspada. Jaga pola makan dan olahraga teratur.' };
}

async function checkJadwal() {
    try {
        const res = await apiCall('/status-kesehatan/cek-jadwal');
        console.log('Cek Jadwal Response:', res);
        if (res && res.data && res.data.td && res.data.td.is_waiting) {
            const nextDate = new Date(res.data.td.next_allowed);
            const now = new Date();
            const diffTime = nextDate - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const hours = Math.ceil(diffTime / (1000 * 60 * 60));
            
            let timeLeft = '';
            if (diffDays > 1) timeLeft = `${diffDays} hari`;
            else if (hours > 1) timeLeft = `${hours} jam`;
            else timeLeft = `${Math.ceil(diffTime / (1000 * 60))} menit`;
            
            document.getElementById('tdForm').innerHTML = `
                <div class="text-center py-12 bg-primary-900/30 rounded-3xl border-2 border-primary-400/20 px-6">
                    <div class="w-20 h-20 mx-auto bg-white/10 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3 tracking-tight">Belum Waktunya</h3>
                    <p class="text-primary-100 text-lg mb-6 font-medium">Anda baru saja mengisi data Tekanan Darah.</p>
                    <div class="bg-white/10 p-4 rounded-2xl mb-6">
                        <p class="text-sm text-primary-200 uppercase font-black tracking-widest mb-1">Bisa mengisi lagi dalam</p>
                        <p class="text-3xl font-black text-white">${timeLeft}</p>
                    </div>
                    <a href="/warga" class="inline-block bg-white text-primary-800 font-black text-lg px-10 py-4 rounded-2xl transition-all shadow-xl hover:bg-primary-50">Kembali ke Dashboard</a>
                </div>
            `;
            return false;
        }
    } catch (e) { console.error(e); }
    return true;
}

function updatePreview() {
    const syst = parseInt(document.getElementById('systolic').value);
    const diast = parseInt(document.getElementById('diastolic').value);
    const preview = document.getElementById('previewResult');
    
    if (syst && diast) {
        const status = getStatus(syst, diast);
        preview.classList.remove('hidden');
        document.getElementById('previewSystolic').textContent = `${syst}/${diast}`;
        document.getElementById('previewStatus').textContent = status.label;
        document.getElementById('previewStatus').className = `px-4 py-2 text-sm font-semibold rounded-full ${status.color}`;
    } else {
        preview.classList.add('hidden');
    }
}

document.getElementById('systolic')?.addEventListener('input', updatePreview);
document.getElementById('diastolic')?.addEventListener('input', updatePreview);

document.getElementById('tdForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const loader = document.getElementById('loader');
    loader.style.display = 'block';
    
    const data = {
        systolic: parseInt(document.getElementById('systolic').value),
        diastolic: parseInt(document.getElementById('diastolic').value),
        tgl_cek: document.getElementById('tgl_cek').value
    };
    
    try {
        const res = await apiCall('/status-kesehatan/td', 'POST', data);
        
        if (!res.success) {
            const errorMsg = res.message || 'Belum waktunya mengisi';
            document.getElementById('tdForm').innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Bisa Mengisi</h3>
                    <p class="text-gray-600 mb-4">${errorMsg}</p>
                    <a href="/warga" class="inline-block mt-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg transition">Kembali ke Dashboard</a>
                </div>
            `;
            loader.style.display = 'none';
            return;
        }
        
        if (res && res.success) {
            const status = getStatus(data.systolic, data.diastolic);
            document.getElementById('resultIcon').className = `w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 ${status.icon}`;
            document.getElementById('resultIcon').innerHTML = '<svg class="w-10 h-10 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            document.getElementById('resultValue').textContent = `${data.systolic}/${data.diastolic}`;
            document.getElementById('resultStatus').textContent = status.label;
            document.getElementById('resultStatus').className = `px-4 py-2 text-sm font-semibold rounded-full ${status.color}`;
            document.getElementById('resultDesc').textContent = status.desc;
            document.getElementById('resultModal').classList.remove('hidden');
        } else {
            showAlert(res?.message || 'Gagal menyimpan data', 'error');
        }
    } catch (e) { showAlert('Gagal menyimpan data', 'error'); }
    finally { loader.style.display = 'none'; }
});

function closeResultModal() {
    document.getElementById('resultModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    checkJadwal();
});
</script>
@endsection