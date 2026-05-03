@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Kuesioner GAD-7</h1>
            <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Ukur Tingkat Kecemasan Anda Secara Mandiri</p>
        </div>
    </div>
</div>

<div class="max-w-full mx-auto">
    <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-3xl p-6 md:p-8 text-white mb-6 shadow-xl border border-primary-500/30">
        <div class="flex items-center gap-4 md:gap-6">
            <div class="p-4 bg-white/20 rounded-2xl flex-shrink-0">
                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-black tracking-tight">Kuesioner GAD7</h2>
                <p class="text-primary-100 text-lg font-bold mt-1 opacity-90">Selama 2 minggu terakhir, seberapa sering Anda terganggu oleh masalah berikut?</p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-3xl shadow-2xl p-6 md:p-10 text-white border border-primary-500/30">
        <div id="soalContainer" class="space-y-6 mb-10"></div>
        
        <div id="totalScore" class="hidden bg-primary-900/40 rounded-[2.5rem] p-10 text-center text-white mb-10 border-2 border-white/20 shadow-inner">
            <p class="text-primary-100 font-black uppercase tracking-widest text-base mb-4">Total Skor Anda</p>
            <div class="text-8xl font-black tracking-tighter" id="scoreValue">0</div>
            <div class="mt-6">
                <span id="scoreStatus" class="inline-block px-10 py-4 text-2xl font-black bg-white/20 rounded-2xl shadow-lg ring-4 ring-white/5">-</span>
            </div>
        </div>

        <button type="button" id="submitBtn" onclick="submitGad()" class="w-full bg-white hover:bg-primary-50 text-primary-800 text-2xl font-black py-6 rounded-[1.5rem] transition-all shadow-2xl flex justify-center items-center gap-3 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-30 disabled:cursor-not-allowed" disabled>
            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            Kirim
        </button>
    </div>

    <div class="mt-8 bg-gradient-to-br from-primary-600 to-primary-800 rounded-3xl shadow-xl p-8 text-white border border-primary-500/30">
        <h3 class="font-black text-2xl text-white mb-8 tracking-tight">Referensi Skor GAD7</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center gap-4 p-4 bg-white/10 rounded-2xl border border-white/10">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">0-4</div>
                <div>
                    <p class="font-bold text-white text-lg">Normal</p>
                    <p class="text-sm text-primary-100 font-medium">Tidak ada gangguan</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white/10 rounded-2xl border border-white/10">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">5-9</div>
                <div>
                    <p class="font-bold text-white text-lg">Ringan</p>
                    <p class="text-sm text-primary-100 font-medium">Observasi mandiri</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white/10 rounded-2xl border border-white/10">
                <div class="w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">10+</div>
                <div>
                    <p class="font-bold text-white text-lg">Sedang-Berat</p>
                    <p class="text-sm text-primary-100 font-medium">Butuh evaluasi ahli</p>
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
                <h3 class="text-xl md:text-3xl font-black text-black tracking-tighter uppercase truncate leading-tight">Analisa Hasil & Rekomendasi</h3>
                <p class="text-primary-400 text-[10px] md:text-xs font-black uppercase tracking-[0.3em] mt-1">Status Kesehatan Psikologis</p>
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
                        <div class="text-primary-200 uppercase font-black tracking-widest text-[10px] mb-2">Skor Total</div>
                        <div class="text-5xl md:text-7xl font-black tracking-tighter mb-4" id="resultValue">0</div>
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
let questions = [];
const options = [
    { value: 0, label: 'Tidak pernah' },
    { value: 1, label: 'Beberapa hari' },
    { value: 2, label: 'Lebih dari separuh hari' },
    { value: 3, label: 'Hampir setiap hari' }
];

let answers = [];
let totalScore = 0;

function getStatus(skor) {
    if (skor <= 4) return { label: 'Normal', color: 'bg-green-100 text-green-700', icon: 'bg-green-100', desc: 'Tidak ada gangguan signifikan. Pertahankan kondisi mental yang baik.' };
    if (skor <= 9) return { label: 'Ringan', color: 'bg-yellow-100 text-yellow-700', icon: 'bg-yellow-100', desc: 'Pertimbangkan intervensi awal seperti relaksasi dan manajemen stres.' };
    if (skor <= 14) return { label: 'Sedang', color: 'bg-orange-100 text-orange-700', icon: 'bg-orange-100', desc: 'Anjurkan konseling atau terapi.' };
    return { label: 'Berat', color: 'bg-red-100 text-red-700', icon: 'bg-red-100', desc: 'Butuh evaluasi profesional segera.' };
}

async function checkJadwalGad() {
    try {
        const res = await apiCall('/status-kesehatan/cek-jadwal');
        if (res && res.data && res.data.gad7 && res.data.gad7.is_waiting) {
            const nextDate = new Date(res.data.gad7.next_allowed);
            const now = new Date();
            const diffTime = nextDate - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const hours = Math.ceil(diffTime / (1000 * 60 * 60));
            
            let timeLeft = '';
            if (diffDays > 1) timeLeft = `${diffDays} hari`;
            else if (hours > 1) timeLeft = `${hours} jam`;
            else timeLeft = `${Math.ceil(diffTime / (1000 * 60))} menit`;
            
            document.getElementById('soalContainer').innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3 tracking-tight">Belum Waktunya</h3>
                    <p class="text-primary-100 text-lg mb-6 font-medium">Anda baru saja mengisi kuesioner GAD7.</p>
                    <div class="bg-white/10 p-4 rounded-2xl mb-6">
                        <p class="text-sm text-primary-200 uppercase font-black tracking-widest mb-1">Bisa mengisi lagi dalam</p>
                        <p class="text-3xl font-black text-white">${timeLeft}</p>
                    </div>
                    <a href="/warga" class="inline-block bg-white text-primary-800 font-black text-lg px-10 py-4 rounded-2xl transition-all shadow-xl hover:bg-primary-50">Kembali ke Dashboard</a>
                </div>
            `;
            document.getElementById('submitBtn').style.display = 'none';
            document.getElementById('totalScore').style.display = 'none';
            return false;
        }
    } catch (e) { console.error(e); }
    return true;
}


async function renderQuestions() {
    const container = document.getElementById('soalContainer');
    container.innerHTML = '<div class="text-center py-12 text-white font-bold text-xl italic animate-pulse">Memuat kuesioner...</div>';

    const res = await apiCall('/gad/kuesioner');
    if (res && res.success) {
        questions = res.data;
        answers = new Array(questions.length).fill(null);

        if (questions.length === 0) {
            container.innerHTML = '<div class="text-center py-12 text-white font-black text-2xl">Kuesioner belum tersedia.</div>';
            document.getElementById('submitBtn').style.display = 'none';
            return;
        }

        container.innerHTML = questions.map((q, i) => `
            <div class="p-8 bg-primary-900/30 rounded-[2rem] border-2 border-white/10 shadow-lg">
                <p class="font-black text-white mb-6 text-xl md:text-2xl leading-snug"><span class="text-primary-300">#${i + 1}</span> ${q.soal}</p>
                <div class="grid grid-cols-1 gap-3">
                    ${options.map(o => `
                        <button type="button" onclick="selectAnswer(${i}, ${o.value})" class="answer-btn px-6 py-5 rounded-2xl border-2 border-white/10 text-lg md:text-xl font-bold transition-all bg-white/5 hover:bg-white/10 text-left flex items-center gap-4 group" data-q="${i}" data-val="${o.value}">
                            <div class="w-8 h-8 rounded-full border-2 border-white/30 flex items-center justify-center group-hover:border-white transition-all">
                                <div class="w-4 h-4 rounded-full bg-white opacity-0 transition-all check-indicator"></div>
                            </div>
                            ${o.label}
                        </button>
                    `).join('')}
                </div>
            </div>
        `).join('');
    } else {
        container.innerHTML = '<div class="text-center py-12 text-white font-bold">Gagal memuat kuesioner.</div>';
    }
}

function selectAnswer(qIndex, value) {
    answers[qIndex] = value;
    document.querySelectorAll(`[data-q="${qIndex}"]`).forEach(btn => {
        const check = btn.querySelector('.check-indicator');
        btn.classList.remove('bg-primary-500', 'text-white', 'border-primary-400', 'shadow-2xl');
        check.classList.add('opacity-0');
        
        if (parseInt(btn.dataset.val) === value) {
            btn.classList.add('bg-primary-500', 'text-white', 'border-primary-400', 'shadow-2xl');
            check.classList.remove('opacity-0');
        }
    });
    
    totalScore = answers.reduce((a, b) => a + (b || 0), 0);
    const scoreEl = document.getElementById('totalScore');
    const submitBtn = document.getElementById('submitBtn');
    
    if (answers.every(a => a !== null)) {
        scoreEl.classList.remove('hidden');
        submitBtn.disabled = false;
        document.getElementById('scoreValue').textContent = totalScore;
        
        const status = getStatus(totalScore);
        document.getElementById('scoreStatus').textContent = status.label;
        document.getElementById('scoreStatus').className = `inline-block mt-3 px-4 py-2 text-sm font-semibold bg-white/20 rounded-full ${status.bg || ''}`;
    } else {
        scoreEl.classList.add('hidden');
        submitBtn.disabled = true;
    }
}

async function submitGad() {
    const loader = document.getElementById('submitBtn');
    const originalText = loader.textContent;
    loader.disabled = true;
    loader.innerHTML = '<span class="loader mr-2"></span>Mengirim...';
    
    // Prepare answers for GADController@store
    const jawaban = questions.map((q, i) => ({
        kuesioner_id: q.id,
        skor: answers[i]
    }));
    
    try {
        const res = await apiCall('/gad', 'POST', { jawaban });
        
        if (res && res.success) {
            const status = getStatus(totalScore);
            document.getElementById('resultIcon').className = `w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 ${status.icon}`;
            document.getElementById('resultIcon').innerHTML = '<svg class="w-10 h-10 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            document.getElementById('resultValue').textContent = totalScore;
            document.getElementById('resultStatus').textContent = status.label;
            document.getElementById('resultStatus').className = `px-4 py-2 text-sm font-semibold rounded-full ${status.color}`;
            document.getElementById('resultDesc').textContent = status.desc;
            
            // Rekomendasi from response
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
    } catch (e) { 
        console.error(e);
        showAlert('Gagal menyimpan data', 'error'); 
    }
    finally { 
        loader.disabled = false; 
        loader.textContent = originalText; 
    }
}

function closeResultModal() {
    document.getElementById('resultModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', async () => {
    const canFill = await checkJadwalGad();
    if (canFill) {
        renderQuestions();
    }
});
</script>
@endsection