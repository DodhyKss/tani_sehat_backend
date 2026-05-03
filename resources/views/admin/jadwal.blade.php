@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Pengaturan Jadwal</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Atur Interval Waktu Pengisian Data Kesehatan</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-[4rem] -mr-8 -mt-8 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-5 mb-10 relative z-10">
            <div class="p-4 bg-emerald-100 rounded-2xl text-emerald-600">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-black tracking-tight">Tekanan Darah</h2>
                <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">Popup Pengisian Saat Login</p>
            </div>
        </div>
        
        <form id="tdForm" class="space-y-6 relative z-10">
            <div class="flex items-center gap-4 bg-primary-50/50 p-6 rounded-2xl border border-primary-100">
                <label class="text-xs font-black text-primary-800 uppercase tracking-widest w-24">Setiap</label>
                <input type="number" id="tdJumlah" min="1" max="365" class="w-24 px-4 py-3 bg-white border-2 border-primary-100 rounded-xl text-center font-black text-black focus:border-primary-600 outline-none transition-all" value="1">
                <select id="tdTipe" class="flex-1 px-4 py-3 bg-white border-2 border-primary-100 rounded-xl font-black text-black focus:border-primary-600 outline-none transition-all appearance-none">
                    <option value="hours">Jam</option>
                    <option value="day" selected>Hari</option>
                    <option value="week">Minggu</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-black text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-emerald-900/20 uppercase tracking-widest text-sm">
                SIMPAN JADWAL TEKANAN DARAH
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-[4rem] -mr-8 -mt-8 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-5 mb-10 relative z-10">
            <div class="p-4 bg-amber-100 rounded-2xl text-amber-600">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-black tracking-tight">Kuesioner GAD-7</h2>
                <p class="text-xs font-black text-amber-600 uppercase tracking-widest">Popup Evaluasi Kesehatan Mental</p>
            </div>
        </div>
        
        <form id="gadForm" class="space-y-6 relative z-10">
            <div class="flex items-center gap-4 bg-primary-50/50 p-6 rounded-2xl border border-primary-100">
                <label class="text-xs font-black text-primary-800 uppercase tracking-widest w-24">Setiap</label>
                <input type="number" id="gadJumlah" min="1" max="365" class="w-24 px-4 py-3 bg-white border-2 border-primary-100 rounded-xl text-center font-black text-black focus:border-primary-600 outline-none transition-all" value="2">
                <select id="gadTipe" class="flex-1 px-4 py-3 bg-white border-2 border-primary-100 rounded-xl font-black text-black focus:border-primary-600 outline-none transition-all appearance-none">
                    <option value="hours">Jam</option>
                    <option value="day" selected>Hari</option>
                    <option value="week">Minggu</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-amber-600 hover:bg-black text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-amber-900/20 uppercase tracking-widest text-sm">
                SIMPAN JADWAL KUESIONER GAD-7
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 mt-10 overflow-hidden">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl md:text-3xl font-black text-black tracking-tight">Konfigurasi Jadwal Aktif</h2>
        <div class="w-16 h-2 bg-primary-50 rounded-full"></div>
    </div>
    <div class="hidden md:block overflow-x-auto -mx-10 px-10">
        <table class="w-full text-left">
            <thead class="text-primary-400 uppercase text-xs font-black tracking-[0.2em] border-b-2 border-primary-50">
                <tr>
                    <th class="px-6 py-6 text-left">Jenis Pengisian</th>
                    <th class="px-6 py-6 text-left">Interval Waktu</th>
                    <th class="px-6 py-6 text-left">Terakhir Diperbarui</th>
                </tr>
            </thead>
            <tbody id="jadwalTable" class="divide-y-2 divide-primary-50">
                <tr><td colspan="3" class="px-6 py-12 text-center text-primary-300 font-bold italic">Memuat data konfigurasi...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="jadwalCards" class="md:hidden space-y-4">
        <div class="text-center py-8 text-gray-500">Memuat data...</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadJadwal() {
    try {
        const res = await apiCall('/admin/jadwal');
        const tbody = document.getElementById('jadwalTable');
        
        if (res && res.success && res.data && res.data.length > 0) {
            res.data.forEach(j => {
                if (j.jenis_pengisian === 'td') {
                    document.getElementById('tdJumlah').value = j.jumlah;
                    document.getElementById('tdTipe').value = j.tipe;
                } else {
                    document.getElementById('gadJumlah').value = j.jumlah;
                    document.getElementById('gadTipe').value = j.tipe;
                }
            });
            
            tbody.innerHTML = res.data.map(j => {
                const tipeLabel = { hours: 'Jam', day: 'Hari', week: 'Minggu' };
                return `<tr class="hover:bg-primary-50/50 transition-colors">
                    <td class="px-6 py-6 font-black text-black text-xl">${j.jenis_pengisian === 'td' ? 'Tekanan Darah' : 'GAD-7'}</td>
                    <td class="px-6 py-6 font-bold text-primary-800">Setiap <span class="text-xl font-black">${j.jumlah}</span> ${tipeLabel[j.tipe]}</td>
                    <td class="px-6 py-6 font-bold text-primary-400 italic">${new Date(j.updated_at).toLocaleString('id-ID', {day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'})}</td>
                </tr>`;
            }).join('');

            const cards = document.getElementById('jadwalCards');
            cards.innerHTML = res.data.map(j => {
                const tipeLabel = { hours: 'Jam', day: 'Hari', week: 'Minggu' };
                return `
                    <div class="bg-primary-50/30 p-6 rounded-3xl border border-primary-50">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-black text-black text-lg uppercase tracking-tight">${j.jenis_pengisian === 'td' ? 'Tekanan Darah' : 'GAD-7'}</h3>
                            <span class="px-3 py-1 bg-white text-primary-600 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">Aktif</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center bg-white/50 p-4 rounded-xl">
                                <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">Interval</span>
                                <span class="text-base font-black text-primary-800">${j.jumlah} ${tipeLabel[j.tipe]}</span>
                            </div>
                            <div class="text-[9px] text-right text-primary-400 font-bold italic">Update: ${new Date(j.updated_at).toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada pengaturan jadwal</td></tr>';
            document.getElementById('jadwalCards').innerHTML = '<div class="text-center py-8 text-gray-500">Belum ada pengaturan</div>';
        }
    } catch (e) { console.error(e); }
}

document.getElementById('tdForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = { jenis_pengisian: 'td', jumlah: parseInt(document.getElementById('tdJumlah').value), tipe: document.getElementById('tdTipe').value };
    const res = await apiCall('/admin/jadwal', 'POST', data);
    if (res && res.success) showAlert('Pengaturan TD berhasil disimpan', 'success');
    else showAlert(res?.message || 'Gagal menyimpan pengaturan TD');
    loadJadwal();
});

document.getElementById('gadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = { jenis_pengisian: 'gad7', jumlah: parseInt(document.getElementById('gadJumlah').value), tipe: document.getElementById('gadTipe').value };
    const res = await apiCall('/admin/jadwal', 'POST', data);
    if (res && res.success) showAlert('Pengaturan GAD7 berhasil disimpan', 'success');
    else showAlert(res?.message || 'Gagal menyimpan pengaturan GAD7');
    loadJadwal();
});

document.addEventListener('DOMContentLoaded', () => {
    loadJadwal();
});
</script>
@endsection