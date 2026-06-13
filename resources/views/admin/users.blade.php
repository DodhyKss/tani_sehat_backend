@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Manajemen User</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Kelola Data Warga, Kader, & Admin Sistem</p>
    </div>
    <button onclick="openUserModal()" class="bg-primary-800 hover:bg-black text-white font-black py-4 px-8 rounded-2xl shadow-xl shadow-primary-900/20 transition-all flex items-center gap-3 group uppercase tracking-widest text-sm">
        <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        Tambah User Baru
    </button>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 overflow-hidden mb-10">
    <div class="hidden md:block overflow-x-auto p-8">
        <table class="border-collapse">
            <thead>
                <tr>
                    <th>Identitas User</th>
                    <th>Peran (Role)</th>
                    <th>Kontak</th>
                    <th class="hidden md:table-cell">Lahir</th>
                    <th class="text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <tr><td colspan="5" class="px-6 py-20 text-center text-primary-300 font-bold italic animate-pulse text-xl uppercase tracking-widest">Memuat data user...</td></tr>
            </tbody>
        </table>
    </div>

    <div id="usersCards" class="md:hidden divide-y-2 divide-primary-50">
        <div class="p-12 text-center text-primary-300 font-bold italic uppercase tracking-widest">Memuat data user...</div>
    </div>
    <div id="pagination" class="p-8 border-t-2 border-primary-50 flex flex-wrap justify-center gap-3"></div>
</div>

<div id="userModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-primary-900/80 backdrop-blur-sm" onclick="closeUserModal()"></div>
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white/95 backdrop-blur-md p-8 border-b-2 border-primary-50 flex justify-between items-center z-10">
            <div>
                <h3 id="modalTitle" class="text-2xl font-black text-black tracking-tight">Tambah User</h3>
                <p class="text-primary-800 text-xs font-bold uppercase tracking-widest mt-1">Formulir Data User TaniSehat</p>
            </div>
            <button onclick="closeUserModal()" class="p-3 hover:bg-primary-50 rounded-2xl text-primary-300 hover:text-primary-800 transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="userForm" class="p-8 space-y-6">
            <input type="hidden" id="userId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Nomor NIK</label>
                    <input type="text" id="nik" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-bold text-black" required placeholder="Masukkan 16 digit NIK">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" id="password" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-bold text-black" placeholder="Minimal 8 karakter">
                    <p class="text-[10px] text-primary-400 font-bold italic mt-1 ml-1">*Kosongkan jika tidak ingin mengubah</p>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Nama Lengkap Sesuai KTP</label>
                <input type="text" id="nama_lengkap" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-black text-black text-lg" required placeholder="Contoh: Budi Santoso">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Hak Akses (Role)</label>
                    <select id="role" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-black text-black appearance-none" required>
                        <option value="warga">WARGA NEGARA</option>
                        <option value="kader">KADER KESEHATAN</option>
                        <option value="admin">ADMINISTRATOR SISTEM</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                    <input type="text" id="no_hp" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-bold text-black" required placeholder="0812xxxxxxxx">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-bold text-black" required>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                    <select id="jenis_kelamin" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-black text-black appearance-none" required>
                        <option value="Laki-laki">LAKI-LAKI</option>
                        <option value="Perempuan">PEREMPUAN</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-4 pt-6">
                <button type="button" onclick="closeUserModal()" class="flex-1 px-8 py-4 border-2 border-primary-100 rounded-2xl text-primary-800 font-black hover:bg-primary-50 transition-all uppercase tracking-widest text-sm">BATAL</button>
                <button type="submit" class="flex-1 bg-primary-800 hover:bg-black text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-primary-900/20 uppercase tracking-widest text-sm">SIMPAN DATA</button>
            </div>
        </form>
        </form>
    </div>
</div>


@endsection

@section('scripts')
<script>
let currentPage = 1;

function openUserModal(user = null) {
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = user ? 'Edit User' : 'Tambah User';
    if (user) {
        document.getElementById('userId').value = user.id;
        document.getElementById('nik').value = user.nik;
        document.getElementById('nama_lengkap').value = user.nama_lengkap;
        document.getElementById('role').value = user.role;
        document.getElementById('no_hp').value = user.no_hp;
        document.getElementById('tanggal_lahir').value = user.tanggal_lahir;
        document.getElementById('jenis_kelamin').value = user.jenis_kelamin;
    } else {
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
    }
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
}

async function loadUsers(page = 1) {
    currentPage = page;
    try {
        const res = await apiCall(`/users?page=${page}`);
        if (res && res.data) {
            const data = res.data;
            const users = data.data || [];
            renderUsers(users);
            if (data.last_page) window.renderTablePagination(data, 'pagination', 'loadUsers');
        }
    } catch (e) { console.error(e); showAlert('Gagal memuat data user', 'error'); }
}

function renderUsers(users) {
    const tbody = document.getElementById('usersTableBody');
    const cards = document.getElementById('usersCards');
    
    if (!users || users.length === 0) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada user</td></tr>';
        if (cards) cards.innerHTML = '<div class="p-8 text-center text-gray-500">Tidak ada user</div>';
        return;
    }
    
    const rowsHtml = users.map(u => {
        const badgeClass = u.role === 'admin' ? 'bg-indigo-100 text-indigo-800' : u.role === 'kader' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800';
        return `<tr class="hover:bg-primary-50/50 transition-colors group">
            <td class="px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center text-primary-800 font-black text-lg border-2 border-primary-100">${u.nama_lengkap.charAt(0)}</div>
                    <div>
                        <p class="font-black text-black text-lg tracking-tight">${u.nama_lengkap}</p>
                        <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mt-0.5">NIK: ${u.nik}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-6"><span class="px-4 py-1.5 text-[10px] font-black rounded-lg uppercase tracking-widest shadow-sm ${badgeClass}">${u.role}</span></td>
            <td class="px-6 py-6 font-bold text-primary-800">${u.no_hp}</td>
            <td class="px-6 py-6 text-primary-400 font-bold hidden md:table-cell">${new Date(u.tanggal_lahir).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}</td>
            <td class="px-6 py-6">
                <div class="flex items-center justify-center gap-2">
                    <button onclick='openUserModal(${JSON.stringify(u)})' class="p-3 bg-primary-50 hover:bg-primary-100 rounded-xl text-primary-600 transition-all transform hover:scale-110" title="Edit Data">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button onclick="deleteUser(${u.id})" class="p-3 bg-orange-50 hover:bg-orange-100 rounded-xl text-orange-600 transition-all transform hover:scale-110" title="Hapus User">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');

    const cardsHtml = users.map(u => {
        const badgeClass = u.role === 'admin' ? 'bg-indigo-100 text-indigo-800' : u.role === 'kader' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800';
        return `
            <div class="p-6 bg-white hover:bg-primary-50/20 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center text-primary-800 font-black text-xl border-2 border-primary-100">${u.nama_lengkap.charAt(0)}</div>
                        <div>
                            <p class="font-black text-black text-lg tracking-tight">${u.nama_lengkap}</p>
                            <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mt-0.5">NIK: ${u.nik}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1.5 text-[9px] font-black rounded-lg uppercase tracking-widest shadow-sm ${badgeClass}">${u.role}</span>
                </div>
                <div class="flex justify-between items-center bg-primary-50/50 p-4 rounded-2xl border border-primary-100">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-primary-400 uppercase tracking-widest">Nomor WhatsApp</span>
                        <span class="text-base font-bold text-primary-800">${u.no_hp}</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick='openUserModal(${JSON.stringify(u)})' class="p-3 bg-white border border-primary-100 text-primary-600 rounded-xl shadow-sm">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="deleteUser(${u.id})" class="p-3 bg-white border border-orange-100 text-orange-600 rounded-xl shadow-sm">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    if (tbody) tbody.innerHTML = rowsHtml;
    if (cards) cards.innerHTML = cardsHtml;
}


document.getElementById('userForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('userId').value;
    const data = {
        nik: document.getElementById('nik').value,
        nama_lengkap: document.getElementById('nama_lengkap').value,
        role: document.getElementById('role').value,
        no_hp: document.getElementById('no_hp').value,
        tanggal_lahir: document.getElementById('tanggal_lahir').value,
        jenis_kelamin: document.getElementById('jenis_kelamin').value
    };
    const password = document.getElementById('password').value;
    if (password) data.password = password;
    
    const res = await apiCall(id ? `/users/${id}` : '/users', id ? 'PUT' : 'POST', data);
    if (res && res.success) {
        showAlert(id ? 'User berhasil diupdate' : 'User berhasil ditambahkan', 'success');
        closeUserModal();
        loadUsers(currentPage);
    } else {
        showAlert(res?.message || 'Gagal menyimpan data user');
    }
});

async function deleteUser(id) {
    if (!confirm('Yakin hapus user ini?')) return;
    const res = await apiCall(`/users/${id}`, 'DELETE');
    if (res && res.success) {
        showAlert('User berhasil dihapus', 'success');
        loadUsers(currentPage);
    } else {
        showAlert(res?.message || 'Gagal menghapus user');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user && user.role === 'admin') {
        loadUsers();
    }
});
</script>
@endsection