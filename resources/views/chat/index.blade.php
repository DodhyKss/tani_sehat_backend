@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2">Chat Konsultasi</h1>
    <p class="text-primary-800 text-lg font-bold">Konsultasi kesehatan langsung dengan kader Anda</p>
</div>

<div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-[2.5rem] shadow-2xl overflow-hidden h-[calc(100vh-140px)] md:h-[calc(100vh-220px)] min-h-[600px] border border-primary-500/30">
    <div class="flex h-full flex-col md:flex-row">
        <!-- Chat List -->
        <div class="w-full md:w-96 h-full border-b md:border-b-0 md:border-r border-white/10 flex flex-col bg-primary-900/20" id="chatListContainer">
            <div class="p-6 border-b border-white/10 flex justify-between items-center bg-primary-900/40">
                <h3 class="text-xl font-black text-white tracking-tight uppercase">Percakapan</h3>
                <button onclick="openNewChatModal()" class="p-3 bg-white text-primary-800 rounded-2xl hover:bg-primary-50 transition shadow-xl" title="Tambah Chat Baru">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto" id="chatList">
                <div class="p-6 text-center text-primary-200 font-bold animate-pulse">Memuat...</div>
            </div>
        </div>
        
        <!-- Chat Window -->
        <div class="flex-1 flex flex-col hidden bg-primary-900/10" id="chatWindow">
            <div class="p-4 md:p-6 border-b border-white/10 flex items-center justify-between gap-3 bg-primary-800/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <button onclick="closeChat()" class="md:hidden p-2 hover:bg-white/10 rounded-xl text-white">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="w-12 h-12 rounded-full bg-white/20 border-2 border-white/20 flex items-center justify-center shadow-lg">
                        <span id="kaderAvatar" class="text-white font-black text-xl">-</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xl font-black text-white tracking-tight truncate" id="kaderName">-</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            <p class="text-sm text-primary-100 font-bold uppercase tracking-widest" id="kaderStatus">Online</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="deleteConversation()" class="p-3 text-white/40 hover:text-white hover:bg-white/10 rounded-xl transition-all" title="Hapus Percakapan">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-primary-900/10" id="messagesContainer">
                <div class="text-center text-primary-200 py-12 text-xl font-black italic opacity-50 uppercase tracking-widest">Memuat pesan...</div>
            </div>
            
            <div class="p-4 md:p-8 bg-primary-800/40 border-t border-white/10 backdrop-blur-md">
                <form id="messageForm" class="flex items-end gap-4">
                    <div class="flex-1 relative">
                        <textarea id="messageInput" placeholder="Ketik pesan konsultasi..." rows="1"
                                  class="w-full px-8 py-5 bg-white/10 border-2 border-white/20 rounded-[2rem] text-white font-black text-xl placeholder:text-white/30 focus:ring-8 focus:ring-white/5 outline-none transition-all shadow-inner resize-none overflow-hidden"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>
                    <button type="submit" class="p-6 bg-white text-primary-800 rounded-[2rem] hover:bg-primary-50 transition-all shadow-2xl active:scale-95 group mb-1">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" class="group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <!-- Empty State -->
        <div class="flex-1 hidden md:flex flex-col items-center justify-center p-12 text-center bg-primary-900/10" id="emptyState">
            <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center mb-8 shadow-2xl border border-white/10">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-white/60"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h3 class="text-3xl font-black text-white mb-3 tracking-tight">Chat Konsultasi</h3>
            <p class="text-primary-100 text-xl font-medium max-w-sm opacity-80 leading-relaxed">Pilih salah satu percakapan di samping untuk mulai berkonsultasi dengan kader Anda.</p>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div id="newChatModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md">
    <div class="absolute inset-0 bg-black/70" onclick="closeNewChatModal()"></div>
    <div class="relative bg-gradient-to-br from-primary-700 to-primary-900 rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden border border-white/20 text-white">
        <div class="p-8 border-b border-white/10 flex justify-between items-center bg-primary-800/50">
            <h3 class="text-2xl font-black tracking-tight uppercase">Cari Kontak</h3>
            <button onclick="closeNewChatModal()" class="p-3 hover:bg-white/10 rounded-2xl transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 max-h-[60vh] overflow-y-auto" id="contactList">
            <div class="p-8 text-center text-primary-200 font-bold">Memuat daftar kontak...</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentKaderId = null;
let currentConversationId = null;
let chatInterval = null;

// Mengambil daftar percakapan aktif
async function loadConversations() {
    try {
        const res = await apiCall('/messages');
        const container = document.getElementById('chatList');
        
        const list = res?.data || [];
        if (list.length > 0) {
            container.innerHTML = list.map(c => {
                const isActive = currentConversationId === c.id;
                const partnerName = c.partner?.nama_lengkap || 'Unknown';
                return `
                    <div onclick="selectConversation(${c.id}, '${partnerName}')" 
                         class="flex items-center gap-4 p-5 cursor-pointer border-b border-white/5 transition-all
                                ${isActive ? 'bg-white/20 shadow-inner' : 'hover:bg-white/10'}">
                        <div class="w-14 h-14 rounded-full ${c.admin_id ? 'bg-secondary-500' : 'bg-white/20'} border-2 border-white/10 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <span class="text-white font-black text-xl">${partnerName.charAt(0)}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-lg font-black text-white truncate tracking-tight">${partnerName}</p>
                                <span class="text-[11px] font-black text-primary-200 ml-2 uppercase">${new Date(c.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-base ${isActive ? 'text-white' : 'text-primary-100/70'} truncate font-bold">${c.latest_detail?.message || 'Klik untuk memulai chat'}</p>
                                ${c.unread_count > 0 ? `<span class="bg-white text-primary-800 text-xs font-black px-2 py-1 rounded-lg ml-2 shadow-lg">${c.unread_count}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.innerHTML = '<div class="p-8 text-center text-gray-400"><p class="text-sm">Belum ada percakapan aktif.</p><p class="text-xs mt-1">Klik tombol + untuk memulai.</p></div>';
        }
    } catch (e) { console.error(e); }
}

async function openNewChatModal() {
    document.getElementById('newChatModal').classList.remove('hidden');
    const container = document.getElementById('contactList');
    container.innerHTML = '<div class="p-4 text-center text-gray-500">Memuat kontak...</div>';
    
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        // Fetch contacts based on role
        const endpoints = [];
        if (user.role === 'admin') {
            endpoints.push('/users?role=kader');
            endpoints.push('/users?role=warga');
        } else if (user.role === 'kader') {
            endpoints.push('/users/kader/' + user.id + '/warga');
            endpoints.push('/admins');
        } else if (user.role === 'warga') {
            endpoints.push('/kaders');
            endpoints.push('/admins');
        }
        
        const responses = await Promise.all(endpoints.map(e => apiCall(e)));
        let contacts = [];
        responses.forEach(res => {
            const data = res?.data?.data || res?.data || [];
            contacts = [...contacts, ...data];
        });
        
        // Remove self
        contacts = contacts.filter(c => c.id !== user.id);
        
        if (contacts.length > 0) {
            container.innerHTML = contacts.map(k => `
                <div onclick="startChatWith(${k.id}, '${k.nama_lengkap}')" class="flex items-center gap-4 p-5 hover:bg-white/10 cursor-pointer rounded-[1.5rem] transition-all group mb-2 border border-white/5">
                    <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 border-2 border-white/10 shadow-lg">
                        <span class="text-white font-black text-xl group-hover:scale-110 transition-transform">${k.nama_lengkap.charAt(0)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-black text-white truncate tracking-tight">${k.nama_lengkap}</p>
                        <p class="text-sm text-primary-100 font-bold uppercase tracking-widest">${k.role || 'User'}</p>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<div class="p-4 text-center text-gray-500">Tidak ada kontak tersedia.</div>';
        }
    } catch (e) { 
        console.error(e);
        container.innerHTML = '<div class="p-4 text-center text-red-500">Gagal memuat kontak.</div>';
    }
}

function closeNewChatModal() {
    document.getElementById('newChatModal').classList.add('hidden');
}

async function startChatWith(userId, name) {
    closeNewChatModal();
    try {
        const res = await apiCall('/messages/start', 'POST', { receiver_id: userId });
        if (res && res.success) {
            currentConversationId = res.data.id;
            selectConversation(res.data.id, name);
            loadConversations();
        } else {
            showAlert(res.message || 'Gagal memulai chat');
        }
    } catch (e) { console.error(e); }
}

function selectConversation(id, name) {
    if (chatInterval) clearInterval(chatInterval);
    currentConversationId = id;
    
    document.getElementById('chatListContainer').classList.add('hidden', 'md:block');
    document.getElementById('chatWindow').classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    
    document.getElementById('kaderAvatar').textContent = name.charAt(0);
    document.getElementById('kaderName').textContent = name;
    
    loadMessages();
    chatInterval = setInterval(loadMessages, 3000);
    
    // Update active state in sidebar
    loadConversations();
}

function closeChat() {
    if (chatInterval) clearInterval(chatInterval);
    currentConversationId = null;
    document.getElementById('chatWindow').classList.add('hidden');
    document.getElementById('chatListContainer').classList.remove('hidden');
    document.getElementById('emptyState').classList.remove('hidden', 'md:flex');
    document.getElementById('emptyState').classList.add('md:flex');
}

async function deleteConversation() {
    if (!currentConversationId) return;
    if (!confirm('Hapus seluruh percakapan ini?')) return;
    
    try {
        const res = await apiCall(`/messages/${currentConversationId}`, 'DELETE');
        if (res && res.success) {
            showAlert('Percakapan dihapus', 'success');
            closeChat();
            loadConversations();
        }
    } catch (e) { console.error(e); }
}

async function deleteMessage(id) {
    if (!confirm('Hapus pesan ini?')) return;
    try {
        const res = await apiCall(`/messages/detail/${id}`, 'DELETE');
        if (res && res.success) {
            loadMessages();
        }
    } catch (e) { console.error(e); }
}

async function loadMessages() {
    if (!currentConversationId) return;
    try {
        const res = await apiCall(`/messages/${currentConversationId}`);
        if (res && res.success) {
            renderMessages(res.data.details || []);
        }
    } catch (e) { console.error(e); }
}

function renderMessages(messages) {
    const container = document.getElementById('messagesContainer');
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (messages.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-600 py-12 text-lg font-bold italic">Belum ada pesan. Ketik sesuatu untuk memulai!</div>';
        return;
    }
    
    const html = messages.map(m => {
        const isMe = m.sender_id === user.id;
        return `
            <div class="flex w-full ${isMe ? 'justify-end' : 'justify-start'} mb-8 group px-2">
                <div class="max-w-[85%] md:max-w-[70%] relative">
                    <div class="${isMe ? 'bg-white text-primary-900 shadow-2xl rounded-t-[2rem] rounded-bl-[2rem]' : 'bg-white/10 text-white border-2 border-white/20 rounded-t-[2rem] rounded-br-[2rem]'} px-5 md:px-8 py-4 md:py-6 shadow-xl">
                        <p class="text-lg md:text-xl font-black leading-relaxed tracking-tight break-all whitespace-pre-wrap overflow-hidden">${m.message}</p>
                        <div class="flex items-center justify-end gap-3 mt-4 pt-3 border-t border-current opacity-10">
                            <span class="text-[10px] md:text-xs font-black uppercase tracking-widest">${new Date(m.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                            ${isMe && m.is_read ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" class="text-primary-600"><path d="M20 6L9 17l-5-5"/></svg>' : ''}
                        </div>
                    </div>
                    <button onclick="deleteMessage(${m.id})" 
                            class="absolute top-1/2 -translate-y-1/2 ${isMe ? '-left-12' : '-right-12'} p-3 text-white/30 hover:text-white opacity-0 group-hover:opacity-100 transition-all bg-white/10 rounded-xl">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    // Only scroll if content changed
    const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;
    container.innerHTML = html;
    if (isAtBottom) container.scrollTop = container.scrollHeight;
}

document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('messageInput');
    const pesan = input.value.trim();
    if (!pesan || !currentConversationId) return;
    
    try {
        const res = await apiCall(`/messages/${currentConversationId}/send`, 'POST', {
            message: pesan
        });
        
        if (res && res.success) {
            input.value = '';
            loadMessages();
            loadConversations();
        }
    } catch (e) { console.error(e); }
});

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return;
    
    document.getElementById('emptyState').classList.remove('hidden', 'md:flex');
    loadConversations();
});
</script>
@endsection