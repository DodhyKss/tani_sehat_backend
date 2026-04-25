@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="/warga" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 transition mb-4">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Input Tekanan Darah</h1>
    <p class="text-gray-500 text-sm">Catat tekanan darah Anda hari ini</p>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="text-center mb-8">
            <div class="inline-flex justify-center items-center p-4 bg-red-50 rounded-full mb-4">
                <svg class="w-10 h-10 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Ukur Tekanan Darah Anda</h2>
            <p class="text-gray-500 text-sm mt-2">Masukkan hasil pengukuran dari alat tensimeter</p>
        </div>

        <form id="tdForm" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sistole (mmHg)</label>
                    <input type="number" id="systolic" min="60" max="250" required placeholder="120" class="w-full px-4 py-4 rounded-xl border border-gray-200 text-center text-2xl font-bold focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diastole (mmHg)</label>
                    <input type="number" id="diastolic" min="40" max="150" required placeholder="80" class="w-full px-4 py-4 rounded-xl border border-gray-200 text-center text-2xl font-bold focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500 text-center mb-2">Tanggal Pengukuran</p>
                <input type="date" id="tgl_cek" required class="w-full px-4 py-3 rounded-lg border border-gray-200 text-center font-semibold focus:ring-2 focus:ring-primary-500">
            </div>

            <div id="previewResult" class="hidden bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 text-center border border-green-200">
                <h3 class="font-semibold text-gray-700 mb-2">Preview Hasil</h3>
                <div class="text-3xl font-bold mb-2" id="previewSystolic">-</div>
                <div class="text-gray-500 text-sm mb-3">mmHg</div>
                <span id="previewStatus" class="px-4 py-2 text-sm font-semibold rounded-full">-</span>
            </div>

            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 rounded-xl transition shadow-lg flex justify-center items-center gap-2">
                <span>Simpan Hasil</span>
                <div class="loader" id="loader"></div>
            </button>
        </form>
    </div>

    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Referensi Kategori</h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Normal</p>
                    <p class="text-sm text-gray-500">Sistole &lt;120 dan Diastole &lt;80</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Pra-Hipertensi</p>
                    <p class="text-sm text-gray-500">Sistole 120-139 atau Diastole 80-89</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg">
                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Hipertensi</p>
                    <p class="text-sm text-gray-500">Sistole ≥140 atau Diastole ≥90</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="resultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeResultModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center">
        <div id="resultIcon" class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4"></div>
        <h3 class="text-2xl font-bold mb-2" id="resultTitle">Hasil</h3>
        <div class="text-4xl font-bold font-mono mb-2" id="resultValue">-</div>
        <p class="text-gray-500 mb-2">mmHg</p>
        <span id="resultStatus" class="px-4 py-2 text-sm font-semibold rounded-full">-</span>
        <p class="text-gray-600 mt-4" id="resultDesc">-</p>
        <a href="/warga" class="inline-block mt-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition">Kembali ke Dashboard</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
const today = new Date().toISOString().split('T')[0];
document.getElementById('tgl_cek').value = today;
document.getElementById('tgl_cek').max = today;

function getStatus(systolic, diastolic) {
    if (systolic < 120 && diastolic < 80) return { label: 'Normal', color: 'bg-green-100 text-green-700', icon: 'bg-green-100', desc: 'Kondisi sehat. Pertahankan pola hidup baik!' };
    if (systolic <= 139 && diastolic <= 89) return { label: 'Pra-Hipertensi', color: 'bg-yellow-100 text-yellow-700', icon: 'bg-yellow-100', desc: 'Perlu waspada. Jaga pola makan dan olahraga teratur.' };
    return { label: 'Hipertensi', color: 'bg-red-100 text-red-700', icon: 'bg-red-100', desc: 'Risiko tinggi. Segera konsultasikan dengan kader kesehatan.' };
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
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Waktunya</h3>
                    <p class="text-gray-600 mb-4">Anda sudah mengisi Tekanan Darah.</p>
                    <p class="text-sm text-gray-500">Bisa mengisi lagi dalam <span class="font-bold text-primary-600">${timeLeft}</span></p>
                    <p class="text-xs text-gray-400 mt-2">(${nextDate.toLocaleString('id-ID')})</p>
                    <a href="/warga" class="inline-block mt-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg transition">Kembali ke Dashboard</a>
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