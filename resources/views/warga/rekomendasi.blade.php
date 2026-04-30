@extends('layouts.app')

@section('content')
<div class="mb-8">
    <a href="/warga" class="inline-flex items-center gap-2 text-primary-800 hover:text-primary-600 transition mb-4 font-bold">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2">Rekomendasi Kesehatan</h1>
    <p class="text-primary-800 text-lg font-bold">Berdasarkan hasil pemantauan kesehatan terbaru Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-6 md:p-8 text-white shadow-xl border border-blue-500/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
            <span class="text-2xl font-black tracking-tight">Video Edukasi</span>
        </div>
        <div id="videosContainer" class="space-y-3">
            <div class="text-center py-6 text-white font-bold italic animate-pulse">Memuat...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-3xl p-6 md:p-8 text-white shadow-xl border border-purple-500/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <span class="text-2xl font-black tracking-tight">Materi Pembelajaran</span>
        </div>
        <div id="materisContainer" class="space-y-3">
            <div class="text-center py-6 text-white font-bold italic animate-pulse">Memuat...</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-3xl p-6 md:p-8 text-white shadow-xl border border-emerald-500/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <span class="text-2xl font-black tracking-tight">Gambar Edukasi</span>
        </div>
        <div id="gambarsContainer" class="space-y-3">
            <div class="text-center py-6 text-white font-bold italic animate-pulse">Memuat...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-3xl p-6 md:p-8 text-white shadow-xl border border-orange-500/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </div>
            <span class="text-2xl font-black tracking-tight">Rekomendasi Olahraga</span>
        </div>
        <div id="olahragasContainer" class="space-y-3">
            <div class="text-center py-6 text-white font-bold italic animate-pulse">Memuat...</div>
        </div>
    </div>
</div>

<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md">
    <div class="absolute inset-0 bg-black/70" onclick="closeDetailModal()"></div>
    <div class="relative bg-gradient-to-br from-primary-700 to-primary-900 rounded-[2.5rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto border border-white/20 text-white">
        <div class="sticky top-0 bg-primary-800/90 backdrop-blur-md p-6 border-b border-white/10 flex justify-between items-center z-10">
            <h3 id="modalTitle" class="text-2xl font-black tracking-tight">Detail</h3>
            <button onclick="closeDetailModal()" class="p-3 hover:bg-white/10 rounded-2xl transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modalContent" class="p-8"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadRekomendasi() {
    try {
        const [vidRes, matRes, gamRes, olhRes] = await Promise.all([
            apiCall('/video'),
            apiCall('/materi'),
            apiCall('/gambar'),
            apiCall('/olahraga')
        ]);
        renderVideos(vidRes?.data || []);
        renderMateris(matRes?.data || []);
        renderGambars(gamRes?.data || []);
        renderOlahragas(olhRes?.data || []);
    } catch (e) { console.error(e); }
}

function renderVideos(videos) {
    const container = document.getElementById('videosContainer');
    if (videos.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-blue-100">Tidak ada video rekomendasi</p>';
        return;
    }
    container.innerHTML = videos.map(v => `
        <div onclick="showVideoDetail(${JSON.stringify(v).replace(/"/g, '&quot;')})" class="bg-white/10 hover:bg-white/20 rounded-2xl p-4 cursor-pointer transition border border-white/10 flex items-center justify-between group">
            <p class="font-bold text-lg text-white group-hover:translate-x-1 transition-transform">${v.judul}</p>
            <svg class="w-6 h-6 text-white/40 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
        </div>
    `).join('');
}

function renderMateris(materis) {
    const container = document.getElementById('materisContainer');
    if (materis.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-purple-100">Tidak ada materi rekomendasi</p>';
        return;
    }
    container.innerHTML = materis.map(m => `
        <div onclick="showMateriDetail(${JSON.stringify(m).replace(/"/g, '&quot;')})" class="bg-white/10 hover:bg-white/20 rounded-2xl p-4 cursor-pointer transition border border-white/10 flex items-center justify-between group">
            <p class="font-bold text-lg text-white group-hover:translate-x-1 transition-transform">${m.judul}</p>
            <svg class="w-6 h-6 text-white/40 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
        </div>
    `).join('');
}

function renderGambars(gambars) {
    const container = document.getElementById('gambarsContainer');
    if (gambars.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-green-100">Tidak ada gambar rekomendasi</p>';
        return;
    }
    container.innerHTML = gambars.map(g => `
        <div onclick="showGambarDetail(${JSON.stringify(g).replace(/"/g, '&quot;')})" class="bg-white/10 hover:bg-white/20 rounded-2xl p-4 cursor-pointer transition border border-white/10 flex items-center justify-between group">
            <p class="font-bold text-lg text-white group-hover:translate-x-1 transition-transform">${g.judul}</p>
            <svg class="w-6 h-6 text-white/40 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
        </div>
    `).join('');
}

function renderOlahragas(olahragas) {
    const container = document.getElementById('olahragasContainer');
    if (olahragas.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-orange-100">Tidak ada olahraga rekomendasi</p>';
        return;
    }
    container.innerHTML = olahragas.map(o => `
        <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
            <p class="font-bold text-lg text-white">${o.nama_olahraga}</p>
            <p class="text-sm text-primary-100 mt-1">${o.deskripsi || 'Lakukan secara rutin'}</p>
        </div>
    `).join('');
}

function showVideoDetail(video) {
    document.getElementById('modalTitle').textContent = video.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="aspect-video bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
            <iframe src="${video.link_embed}" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen></iframe>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showMateriDetail(materi) {
    document.getElementById('modalTitle').textContent = materi.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-10">
            <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <p class="text-xl font-bold text-white mb-8">Materi siap untuk diunduh</p>
            <a href="/public/${materi.file_path}" target="_blank" class="inline-flex items-center gap-3 bg-white text-primary-800 px-10 py-5 rounded-2xl font-black text-xl shadow-xl hover:bg-primary-50 transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                DOWNLOAD MATERI
            </a>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showGambarDetail(gambar) {
    document.getElementById('modalTitle').textContent = gambar.judul;
    document.getElementById('modalContent').innerHTML = `
        <img src="/public/${gambar.file_path}" alt="${gambar.judul}" class="w-full rounded-lg">
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    loadRekomendasi();
});
</script>
@endsection