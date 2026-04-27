@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Manajemen User</h1>
        <p class="text-gray-500 text-sm">Tambah, edit, dan kelola data warga & kader</p>
    </div>
    <button onclick="openUserModal()" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-sm transition flex items-center gap-2">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        Tambah User
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-100">
                <tr>
                    <th class="px-4 md:px-6 py-4">NIK</th>
                    <th class="px-4 md:px-6 py-4">Nama</th>
                    <th class="px-4 md:px-6 py-4">Role</th>
                    <th class="px-4 md:px-6 py-4">No HP</th>
                    <th class="px-4 md:px-6 py-4 hidden md:table-cell">Tgl Lahir</th>
                    <th class="px-4 md:px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="usersTableBody" class="divide-y divide-gray-100">
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500 flex justify-center items-center gap-3"><div class="w-5 h-5 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>Memuat...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="usersCards" class="md:hidden divide-y divide-gray-100">
        <div class="p-8 text-center text-gray-500">Memuat...</div>
    </div>
    <div id="pagination" class="p-4 border-t border-gray-100 flex flex-wrap justify-center gap-2"></div>
</div>

<!-- User Modal -->
<div id="userModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeUserModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Tambah User</h3>
            <button onclick="closeUserModal()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="userForm" class="p-6 space-y-4">
            <input type="hidden" id="userId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" id="nik" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="role" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500" required>
                        <option value="warga">Warga</option>
                        <option value="kader">Kader</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="text" id="no_hp" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select id="jenis_kelamin" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeUserModal()" class="flex-1 px-4 py-3 border border-gray-200 rounded-lg text-gray-600 font-medium hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-lg transition shadow-sm">Simpan</button>
            </div>
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
            if (data.last_page) renderPagination(data);
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
        const badgeClass = u.role === 'admin' ? 'bg-indigo-100 text-indigo-700' : u.role === 'kader' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
        return `<tr class="hover:bg-gray-50 transition">
            <td class="px-4 md:px-6 py-4 font-mono text-gray-600 text-xs md:text-sm">${u.nik}</td>
            <td class="px-4 md:px-6 py-4 font-medium text-gray-800">${u.nama_lengkap}</td>
            <td class="px-4 md:px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full uppercase ${badgeClass}">${u.role}</span></td>
            <td class="px-4 md:px-6 py-4 text-gray-600">${u.no_hp}</td>
            <td class="px-4 md:px-6 py-4 text-gray-600 hidden md:table-cell">${new Date(u.tanggal_lahir).toLocaleDateString('id-ID')}</td>
            <td class="px-4 md:px-6 py-4 text-center">
                <button onclick='openUserModal(${JSON.stringify(u)})' class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-primary-600 transition" title="Edit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button onclick="deleteUser(${u.id})" class="p-2 hover:bg-red-50 rounded-lg text-gray-500 hover:text-red-600 transition" title="Hapus">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </td>
        </tr>`;
    }).join('');

    const cardsHtml = users.map(u => {
        const badgeClass = u.role === 'admin' ? 'bg-indigo-100 text-indigo-700' : u.role === 'kader' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
        return `
            <div class="p-4 bg-white hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-bold text-gray-800">${u.nama_lengkap}</p>
                        <p class="text-xs font-mono text-gray-500">${u.nik}</p>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase ${badgeClass}">${u.role}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>${u.no_hp}</span>
                    <div class="flex gap-1">
                        <button onclick='openUserModal(${JSON.stringify(u)})' class="p-2 bg-primary-50 text-primary-600 rounded-lg">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="deleteUser(${u.id})" class="p-2 bg-red-50 text-red-600 rounded-lg">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    if (tbody) tbody.innerHTML = rowsHtml;
    if (cards) cards.innerHTML = cardsHtml;
}

function renderPagination(data) {
    const pag = document.getElementById('pagination');
    let html = '';
    if (data.prev_page_url) html += `<button onclick="loadUsers(${data.current_page - 1})" class="px-4 py-2 border border-gray-200 bg-white rounded-lg text-sm font-medium hover:bg-gray-50">Sebelumnya</button>`;
    html += `<span class="px-4 py-2 text-sm text-gray-600 font-medium">${data.current_page} / ${data.last_page}</span>`;
    if (data.next_page_url) html += `<button onclick="loadUsers(${data.current_page + 1})" class="px-4 py-2 border border-gray-200 bg-white rounded-lg text-sm font-medium hover:bg-gray-50">Selanjutnya</button>`;
    pag.innerHTML = html;
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
    }
});

async function deleteUser(id) {
    if (!confirm('Yakin hapus user ini?')) return;
    const res = await apiCall(`/users/${id}`, 'DELETE');
    if (res && res.success) {
        showAlert('User berhasil dihapus', 'success');
        loadUsers(currentPage);
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