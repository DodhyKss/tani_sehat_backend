@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="/warga" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 transition mb-4">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Kuesioner GAD7</h1>
    <p class="text-gray-500 text-sm">Ukur tingkat kecemasan Anda</p>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 md:p-8 text-white mb-6 shadow-lg">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white/20 rounded-xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">Generalized Anxiety Disorder 7</h2>
                <p class="text-yellow-100 text-sm">Selama 2 minggu terakhir, seberapa sering Anda mengalami hal berikut?</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <div id="soalContainer" class="space-y-4 mb-6"></div>
        
        <div id="totalScore" class="hidden bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl p-6 text-center text-white mb-6">
            <p class="text-primary-100 text-sm mb-1">Total Skor Anda</p>
            <div class="text-5xl font-bold" id="scoreValue">0</div>
            <span id="scoreStatus" class="inline-block mt-3 px-4 py-2 text-sm font-semibold bg-white/20 rounded-full">-</span>
        </div>

        <button type="button" id="submitBtn" onclick="submitGad()" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 rounded-xl transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            Kirim Jawaban
        </button>
    </div>

    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Referensi Skor GAD7</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">0-4</div>
                <div>
                    <p class="font-semibold text-gray-800">Normal</p>
                    <p class="text-sm text-gray-500">Tidak ada gangguan</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm">5-9</div>
                <div>
                    <p class="font-semibold text-gray-800">Ringan</p>
                    <p class="text-sm text-gray-500">Pertimbangkan intervensi</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm">10+</div>
                <div>
                    <p class="font-semibold text-gray-800">Sedang-Tinggi</p>
                    <p class="text-sm text-gray-500">Butuh evaluasi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="resultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeResultModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center">
        <div id="resultIcon" class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4"></div>
        <h3 class="text-2xl font-bold mb-2">Hasil Kuesioner GAD7</h3>
        <div class="text-5xl font-bold font-mono mb-2" id="resultValue">0</div>
        <span id="resultStatus" class="px-4 py-2 text-sm font-semibold rounded-full">-</span>
        <p class="text-gray-600 mt-4" id="resultDesc">-</p>
        <div id="resultRekomendasi" class="mt-6 p-4 bg-gray-50 rounded-xl text-left"></div>
        <a href="/warga" class="inline-block mt-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition">Kembali ke Dashboard</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
const questions = [
    'Merasa gelisah, cemas, atau sangat tegang',
    'Sulit untuk berhenti khawatir',
    'Terlalu khawatir tentang berbagai hal',
    'Sulit untuk rileks',
    'Sangat gelisah sampai sulit untuk duduk diam',
    'Mudah tersinggung atau mudah marah',
    'Merasa takut, seperti ada sesuatu yang buruk akan terjadi'
];

const options = [
    { value: 0, label: 'Tidak pernah' },
    { value: 1, label: 'Beberapa hari' },
    { value: 2, label: 'Lebih dari separuh hari' },
    { value: 3, label: 'Hampir setiap hari' }
];

let answers = new Array(7).fill(null);
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
        console.log('Cek Jadwal GAD Response:', res);
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
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Waktunya</h3>
                    <p class="text-gray-600 mb-4">Anda sudah mengisi kuesioner GAD7.</p>
                    <p class="text-sm text-gray-500">Bisa mengisi lagi dalam <span class="font-bold text-primary-600">${timeLeft}</span></p>
                    <p class="text-xs text-gray-400 mt-2">(${nextDate.toLocaleString('id-ID')})</p>
                    <a href="/warga" class="inline-block mt-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg transition">Kembali ke Dashboard</a>
                </div>
            `;
            document.getElementById('submitBtn').style.display = 'none';
            document.getElementById('totalScore').style.display = 'none';
            return false;
        }
    } catch (e) { console.error(e); }
    return true;
}


function renderQuestions() {
    const container = document.getElementById('soalContainer');
    container.innerHTML = questions.map((q, i) => `
        <div class="p-4 bg-gray-50 rounded-xl">
            <p class="font-medium text-gray-800 mb-3">${i + 1}. ${q}</p>
            <div class="flex flex-wrap gap-2">
                ${options.map(o => `
                    <button type="button" onclick="selectAnswer(${i}, ${o.value})" class="answer-btn flex-1 min-w-[80px] px-4 py-2.5 rounded-lg border-2 border-gray-200 text-sm font-medium transition hover:border-primary-500 hover:bg-primary-50" data-q="${i}" data-val="${o.value}">
                        ${o.label}
                    </button>
                `).join('')}
            </div>
        </div>
    `).join('');
}

function selectAnswer(qIndex, value) {
    answers[qIndex] = value;
    document.querySelectorAll(`[data-q="${qIndex}"]`).forEach(btn => {
        btn.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-700');
        if (parseInt(btn.dataset.val) === value) {
            btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-700');
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
    
    const data = { skor: totalScore };
    
    try {
        const res = await apiCall('/status-kesehatan/gad', 'POST', data);
        
        if (!res.success) {
            const errorMsg = res.message || 'Belum waktunya mengisi kuesioner GAD7';
            document.getElementById('soalContainer').innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Bisa Mengisi</h3>
                    <p class="text-gray-600 mb-4">${errorMsg}</p>
                    <a href="/warga" class="inline-block mt-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg transition">Kembali ke Dashboard</a>
                </div>
            `;
            loader.disabled = false;
            loader.textContent = originalText;
            return;
        }
        
        if (res && res.success) {
            const status = getStatus(totalScore);
            document.getElementById('resultIcon').className = `w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4 ${status.icon}`;
            document.getElementById('resultIcon').innerHTML = '<svg class="w-10 h-10 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            document.getElementById('resultValue').textContent = totalScore;
            document.getElementById('resultStatus').textContent = status.label;
            document.getElementById('resultStatus').className = `px-4 py-2 text-sm font-semibold rounded-full ${status.color}`;
            document.getElementById('resultDesc').textContent = status.desc;
            
            const [vidRes, olhRes, matRes] = await Promise.all([
                apiCall('/video'),
                apiCall('/olahraga'),
                apiCall('/materi')
            ]);
            let recHtml = '<p class="font-semibold text-gray-700 mb-2">Rekomendasi untuk Anda:</p><ul class="text-sm text-gray-600 space-y-1">';
            if (vidRes?.data?.length) recHtml += `<li class="flex items-center gap-2"><span class="text-blue-500">▶</span> Video: ${vidRes.data[0].judul}</li>`;
            if (olhRes?.data?.length) recHtml += `<li class="flex items-center gap-2"><span class="text-orange-500">●</span> Olahraga: ${olhRes.data[0].nama_olahraga}</li>`;
            if (matRes?.data?.length) recHtml += `<li class="flex items-center gap-2"><span class="text-purple-500">■</span> Materi: ${matRes.data[0].judul}</li>`;
            recHtml += '</ul>';
            document.getElementById('resultRekomendasi').innerHTML = recHtml;
            document.getElementById('resultModal').classList.remove('hidden');
        } else {
            showAlert(res?.message || 'Gagal menyimpan data', 'error');
        }
    } catch (e) { showAlert('Gagal menyimpan data', 'error'); }
    finally { loader.disabled = false; loader.textContent = originalText; }
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