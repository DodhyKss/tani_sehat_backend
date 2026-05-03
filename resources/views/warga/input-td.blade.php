@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Input Tekanan Darah</h1>
            <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Catat Hasil Pengukuran Tensimeter Anda</p>
        </div>
    </div>
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
                <span>Simpan</span>
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

<div id="resultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-xl">
    <div class="absolute inset-0 bg-primary-950/60" onclick="closeResultModal()"></div>
    <div class="relative bg-white rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl w-full max-w-5xl max-h-[95vh] overflow-hidden border-4 border-white flex flex-col">
        <div class="bg-white p-6 md:p-8 border-b-4 border-slate-50 flex justify-between items-center z-10 shadow-sm">
            <div class="min-w-0">
                <h3 id="resultTitle" class="text-xl md:text-3xl font-black text-black tracking-tighter uppercase truncate leading-tight">Analisa Hasil & Rekomendasi</h3>
                <p class="text-primary-400 text-[10px] md:text-xs font-black uppercase tracking-[0.3em] mt-1">Status Kesehatan Warga</p>
            </div>
            <button onclick="closeResultModal()" class="p-3 bg-slate-50 hover:bg-primary-50 text-primary-800 rounded-2xl transition-all border-2 border-transparent hover:border-primary-100 shadow-sm active:scale-90">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 md:p-10 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/30">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Hasil Stats -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-primary-800 rounded-[2.5rem] p-8 text-center text-white shadow-2xl border-4 border-white">
                        <div id="resultIcon" class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 shadow-xl ring-4 ring-white/10"></div>
                        <div class="text-5xl md:text-6xl font-black tracking-tighter mb-1" id="resultValue">-</div>
                        <p class="text-primary-200 uppercase font-black tracking-widest text-[10px] mb-4">mmHg</p>
                        <div class="inline-block mb-4">
                            <span id="resultStatus" class="px-6 py-2 text-lg font-black rounded-xl shadow-lg ring-2 ring-white/20">-</span>
                        </div>
                        <p class="text-primary-100 text-sm font-bold leading-relaxed" id="resultDesc">-</p>
                    </div>
                    
                    <a href="/warga" class="w-full inline-flex items-center justify-center gap-3 bg-primary-800 hover:bg-black text-white font-black py-5 rounded-2xl shadow-xl transition-all text-sm uppercase tracking-widest border-4 border-white">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        SELESAI
                    </a>
                </div>

                <!-- Rekomendasi Display -->
                <div id="resultRekomendasi" class="lg:col-span-8 space-y-8">
                    <!-- Media will be injected here -->
                </div>
            </div>
        </div>
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

            // Render Rekomendasi
            const recs = res.data.rekomendasi;
            let recHtml = '';
            
            // 1. VIDEO
            if (recs.video?.length) {
                const vid = recs.video[0];
                recHtml += `
                    <div class="space-y-4">
                        <h4 class="text-xl font-black text-black uppercase tracking-widest flex items-center gap-3">
                            <span class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">▶</span>
                            Video Edukasi: ${vid.judul}
                        </h4>
                        <div class="aspect-video w-full rounded-[2rem] overflow-hidden border-8 border-white shadow-2xl bg-black">
                            <iframe src="${vid.link_embed}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                `;
            }


            // 2. MATERI (PDF)
            if (recs.materi?.length) {
                const mat = recs.materi[0];
                recHtml += `
                    <div class="space-y-4">
                        <h4 class="text-xl font-black text-black uppercase tracking-widest flex items-center gap-3">
                            <span class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">■</span>
                            Materi Bacaan: ${mat.judul}
                        </h4>
                        <div class="h-[500px] w-full rounded-[2rem] overflow-hidden border-8 border-white shadow-2xl bg-slate-100 relative">
                            <iframe src="/public/${mat.file_path}#toolbar=0" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                        <a href="/public/${mat.file_path}" target="_blank" class="inline-flex items-center gap-2 text-xs font-black text-primary-700 uppercase tracking-widest hover:underline px-4">
                            Buka di Jendela Baru
                        </a>
                    </div>
                `;
            }

            // 3. GAMBAR
            if (recs.gambar?.length) {
                const gam = recs.gambar[0];
                recHtml += `
                    <div class="space-y-4">
                        <h4 class="text-xl font-black text-black uppercase tracking-widest flex items-center gap-3">
                            <span class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">🖼</span>
                            Infografis: ${gam.judul}
                        </h4>
                        <div class="w-full rounded-[2rem] overflow-hidden border-8 border-white shadow-2xl bg-white">
                            <img src="/public/${gam.file_path}" class="w-full h-auto" alt="${gam.judul}">
                        </div>
                    </div>
                `;
            }

            // 4. OLAHRAGA
            if (recs.olahraga?.length) {
                const olh = recs.olahraga[0];
                recHtml += `
                    <div class="space-y-4">
                        <h4 class="text-xl font-black text-black uppercase tracking-widest flex items-center gap-3">
                            <span class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">●</span>
                            Program Olahraga
                        </h4>
                        <div class="bg-orange-600 rounded-[2.5rem] p-8 text-white shadow-2xl border-4 border-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-10 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v8H2z"/><line x1="6" y1="12" x2="6" y2="12"/><line x1="10" y1="12" x2="10" y2="12"/><line x1="14" y1="12" x2="14" y2="12"/></svg>
                            </div>
                            <h5 class="text-3xl font-black mb-2 uppercase tracking-tight">${olh.nama_olahraga}</h5>
                            <p class="text-orange-100 text-lg font-medium opacity-90">${olh.deskripsi}</p>
                        </div>
                    </div>
                `;
            }

            if (recHtml === '') {
                recHtml = `
                    <div class="text-center py-20 bg-white rounded-[2.5rem] border-4 border-dashed border-slate-200">
                        <p class="text-slate-400 font-black uppercase tracking-widest text-sm">Tidak ada rekomendasi khusus saat ini.<br>Tetap jaga kesehatan!</p>
                    </div>
                `;
            }

            document.getElementById('resultRekomendasi').innerHTML = recHtml;

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