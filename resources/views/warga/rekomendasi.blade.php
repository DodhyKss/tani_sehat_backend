@extends('layouts.app')

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Rekomendasi Kesehatan</h1>
            <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Saran & Panduan Berdasarkan Hasil Pemeriksaan Anda</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Video Edukasi</span>
        </div>
        <div id="videosContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-blue-100 font-bold italic opacity-60 animate-pulse uppercase tracking-widest text-xs">Memuat video...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Materi PDF</span>
        </div>
        <div id="materisContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-indigo-100 font-bold italic opacity-60 animate-pulse uppercase tracking-widest text-xs">Memuat materi...</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Gambar Edukasi</span>
        </div>
        <div id="gambarsContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-emerald-100 font-bold italic opacity-60 animate-pulse uppercase tracking-widest text-xs">Memuat gambar...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Program Olahraga</span>
        </div>
        <div id="olahragasContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-orange-100 font-bold italic opacity-60 animate-pulse uppercase tracking-widest text-xs">Memuat program...</div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 md:p-10">
    <div class="absolute inset-0 bg-primary-950/60 backdrop-blur-xl" onclick="closeDetailModal()"></div>
    <div class="relative bg-white rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl w-full max-w-5xl max-h-[95vh] overflow-hidden border-4 border-white flex flex-col">
        <div class="bg-white p-8 md:p-10 border-b-4 border-slate-50 flex justify-between items-center z-10 shadow-sm">
            <div class="min-w-0">
                <h3 id="modalTitle" class="text-2xl md:text-4xl font-black text-black tracking-tighter uppercase truncate leading-tight">Detail Rekomendasi</h3>
                <p class="text-primary-400 text-[10px] md:text-xs font-black uppercase tracking-[0.3em] mt-1">Informasi & Edukasi Kesehatan</p>
            </div>
            <button onclick="closeDetailModal()" class="p-4 bg-slate-50 hover:bg-primary-50 text-primary-800 rounded-3xl transition-all border-2 border-transparent hover:border-primary-100 shadow-sm active:scale-90">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modalContent" class="p-6 md:p-10 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/30"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadAllRekomendasi() {
    try {
        const [vidRes, matRes, gamRes, olhRes] = await Promise.all([
            apiCall('/video'),
            apiCall('/materi'),
            apiCall('/gambar'),
            apiCall('/olahraga')
        ]);
        renderItems('videosContainer', vidRes?.data || [], 'blue', showVideoDetail);
        renderItems('materisContainer', matRes?.data || [], 'indigo', showMateriDetail);
        renderItems('gambarsContainer', gamRes?.data || [], 'emerald', showGambarDetail);
        renderOlahragas(olhRes?.data || []);
    } catch (e) { console.error(e); }
}

function renderItems(containerId, items, color, detailFn) {
    const container = document.getElementById(containerId);
    if (items.length === 0) {
        container.innerHTML = `<p class="text-center py-6 text-${color}-100 font-black uppercase tracking-widest text-xs italic opacity-60">Tidak ada data tersedia</p>`;
        return;
    }
    container.innerHTML = items.map(item => `
        <div onclick='${detailFn.name}(${JSON.stringify(item).replace(/'/g, "&apos;")})' 
             class="bg-white/10 hover:bg-white/30 rounded-2xl p-6 cursor-pointer transition-all flex items-center justify-between group border border-white/5">
            <p class="font-black text-xl truncate mr-6 uppercase tracking-tight">${item.judul}</p>
            <div class="p-2.5 bg-white/20 rounded-xl group-hover:bg-white group-hover:text-black transition-all transform group-hover:translate-x-2">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><path d="M9 18l6-6-6-6"/></svg>
            </div>
        </div>
    `).join('');
}

function renderOlahragas(olahragas) {
    const container = document.getElementById('olahragasContainer');
    if (olahragas.length === 0) {
        container.innerHTML = '<p class="text-center py-6 text-orange-100 font-black uppercase tracking-widest text-xs italic opacity-60">Tidak ada rekomendasi</p>';
        return;
    }
    container.innerHTML = olahragas.map(o => `
        <div class="bg-white/10 rounded-2xl p-6 flex items-center gap-5 border border-white/5 hover:bg-white/20 transition-all group">
            <div class="p-4 bg-white/20 rounded-2xl group-hover:scale-110 transition-transform">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M12 2v20M2 12h20"/></svg>
            </div>
            <div>
                <p class="font-black text-xl uppercase tracking-tight leading-tight">${o.nama_olahraga}</p>
                <p class="text-xs font-bold text-orange-100/70 uppercase tracking-widest mt-1">${o.deskripsi || 'Lakukan secara rutin'}</p>
            </div>
        </div>
    `).join('');
}

function showVideoDetail(video) {
    document.getElementById('modalTitle').textContent = video.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="aspect-video bg-black rounded-[2rem] md:rounded-[3rem] overflow-hidden shadow-2xl mb-10 border-8 border-white">
            <iframe src="${video.link_embed}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex flex-wrap gap-3">
                <span class="px-6 py-2.5 bg-blue-50 text-blue-700 rounded-xl text-xs font-black uppercase tracking-widest border-2 border-blue-100">Kategori TD: ${video.kategori_td}</span>
                <span class="px-6 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-black uppercase tracking-widest border-2 border-indigo-100">Kategori GAD: ${video.kategori_gad}</span>
            </div>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showMateriDetail(materi) {
    document.getElementById('modalTitle').textContent = materi.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="mb-10 rounded-[2rem] md:rounded-[3rem] overflow-hidden border-8 border-white shadow-2xl bg-slate-200 flex flex-col h-[65vh] md:h-[75vh]">
            <iframe src="/public/${materi.file_path}#toolbar=0" class="w-full h-full border-none" type="application/pdf"></iframe>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex flex-wrap gap-3">
                <span class="px-6 py-2.5 bg-blue-50 text-blue-700 rounded-xl text-xs font-black uppercase tracking-widest border-2 border-blue-100">Kategori TD: ${materi.kategori_td}</span>
                <span class="px-6 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-black uppercase tracking-widest border-2 border-indigo-100">Kategori GAD: ${materi.kategori_gad}</span>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <a href="/public/${materi.file_path}" download class="flex-1 md:flex-none inline-flex items-center justify-center gap-3 bg-primary-800 hover:bg-black text-white px-10 py-5 rounded-2xl font-black text-xs transition-all shadow-xl shadow-primary-900/20 uppercase tracking-widest border-4 border-white">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    UNDUH PDF
                </a>
            </div>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showGambarDetail(gambar) {
    document.getElementById('modalTitle').textContent = gambar.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="bg-white rounded-[2rem] md:rounded-[3rem] overflow-hidden mb-10 border-8 border-white shadow-2xl p-4">
            <img src="/public/${gambar.file_path}" alt="${gambar.judul}" class="w-full rounded-[1.5rem]">
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex flex-wrap gap-3">
                <span class="px-6 py-2.5 bg-blue-50 text-blue-700 rounded-xl text-xs font-black uppercase tracking-widest border-2 border-blue-100">Kategori TD: ${gambar.kategori_td}</span>
                <span class="px-6 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-black uppercase tracking-widest border-2 border-indigo-100">Kategori GAD: ${gambar.kategori_gad}</span>
            </div>
            <a href="/public/${gambar.file_path}" download class="w-full md:w-auto inline-flex items-center justify-center gap-3 bg-primary-800 hover:bg-black text-white px-10 py-5 rounded-2xl font-black text-xs transition-all shadow-xl shadow-primary-900/20 uppercase tracking-widest border-4 border-white">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                UNDUH GAMBAR
            </a>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', loadAllRekomendasi);
</script>
@endsection