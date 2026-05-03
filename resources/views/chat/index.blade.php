@extends('layouts.app')

@section('content')
<style>
    .chat-container {
        height: calc(100dvh - 90px);
    }
    @media (min-width: 768px) {
        .chat-container {
            height: calc(100dvh - 120px);
        }
    }
    /* Mobile fullscreen chat */
    .chat-fullscreen {
        position: fixed !important;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 55;
        height: 100dvh !important;
        border-radius: 0 !important;
        border: none !important;
        margin: 0 !important;
    }
    /* Ensure chat window fills properly in fullscreen */
    .chat-fullscreen .chat-window-inner {
        height: 100dvh;
        max-height: 100dvh;
    }
    @media (min-width: 768px) {
        .chat-fullscreen {
            position: static !important;
            height: auto !important;
            border-radius: inherit !important;
            border: inherit !important;
        }
    }
    /* Messages scroll from bottom up */
    .messages-scroll {
        display: flex;
        flex-direction: column-reverse;
        overflow-y: auto;
    }
</style>

<div id="chatMainContainer" class="bg-white rounded-2xl md:rounded-[2.5rem] shadow-2xl border-2 md:border-4 border-primary-100 overflow-hidden chat-container">
    <div class="flex h-full flex-col md:flex-row">
        <!-- Chat List -->
        <div class="w-full md:w-72 lg:w-80 h-full border-b md:border-b-0 md:border-r-2 lg:border-r-4 border-primary-100 flex flex-col bg-white" id="chatListContainer">
            <div class="p-4 md:p-6 lg:p-8 border-b-2 md:border-b-4 border-primary-50 flex justify-between items-center bg-primary-50/50 flex-shrink-0">
                <h3 class="text-base md:text-xl lg:text-2xl font-black text-primary-900 tracking-tight uppercase">Percakapan</h3>
                <button onclick="openNewChatModal()" class="p-2 md:p-4 bg-primary-800 text-white rounded-lg md:rounded-2xl hover:bg-black transition-all shadow-lg shadow-primary-900/40 active:scale-95" title="Tambah Chat Baru">
                    <svg class="w-4 h-4 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto custom-scrollbar" id="chatList">
                <div class="p-8 md:p-12 text-center text-primary-300 font-black animate-pulse uppercase tracking-widest text-[10px] md:text-xs">Memuat data...</div>
            </div>
        </div>
        
        <!-- Chat Window -->
        <div class="flex-1 flex-col hidden bg-slate-50 chat-window-inner" id="chatWindow" style="display:none;">
            <!-- Chat Header -->
            <div class="p-3 md:p-6 border-b-2 md:border-b-4 border-primary-50 flex items-center justify-between gap-2 md:gap-4 bg-primary-800 md:bg-white z-10 shadow-md flex-shrink-0">
                <div class="flex items-center gap-3 md:gap-5 min-w-0">
                    <button onclick="closeChat()" class="md:hidden p-2.5 bg-white/20 text-white rounded-xl hover:bg-white/30 transition-all flex-shrink-0 active:scale-95">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="w-9 h-9 md:w-14 md:h-14 rounded-full bg-white/20 md:bg-primary-800 text-white flex items-center justify-center shadow-xl font-black text-sm md:text-2xl border-2 border-white/30 md:border-white flex-shrink-0" id="partnerAvatar">
                        -
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm md:text-2xl font-black text-white md:text-black tracking-tighter truncate leading-none mb-0.5" id="partnerName">-</p>
                        <div class="flex items-center gap-1 md:gap-2">
                            <span class="w-1.5 h-1.5 md:w-2.5 md:h-2.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                            <p class="text-[8px] md:text-[11px] text-emerald-300 md:text-emerald-700 font-black uppercase tracking-[0.1em] md:tracking-[0.2em]" id="partnerStatus">Terhubung</p>
                        </div>
                    </div>
                </div>
                <button onclick="deleteConversation()" class="p-2 md:p-4 text-white/70 md:text-primary-800 hover:text-rose-400 md:hover:text-rose-600 hover:bg-white/10 md:hover:bg-rose-50 rounded-lg md:rounded-2xl transition-all border-2 border-transparent hover:border-rose-100 flex-shrink-0" title="Hapus Percakapan">
                    <svg class="w-4 h-4 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </div>
            
            <!-- Messages Area - scrollable, newest at bottom -->
            <div class="flex-1 overflow-y-auto p-3 md:p-8 custom-scrollbar bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px] md:[background-size:32px_32px]" id="messagesContainer" style="min-height:0;">
                <div class="text-center text-primary-300 py-12 text-[10px] md:text-sm font-black italic uppercase tracking-widest opacity-50">Memuat pesan...</div>
            </div>
            
            <!-- Input Area - always pinned at bottom -->
            <div class="p-2 md:p-6 bg-white border-t-2 md:border-t-4 border-primary-50 shadow-[0_-5px_20px_rgba(0,0,0,0.02)] flex-shrink-0">
                <form id="messageForm" class="flex items-end gap-2 md:gap-4 max-w-6xl mx-auto">
                    <div class="flex-1 relative">
                        <textarea id="messageInput" placeholder="Ketik pesan..." rows="1"
                                  class="w-full px-4 md:px-8 py-2.5 md:py-5 bg-slate-50 border-2 md:border-4 border-slate-100 rounded-xl md:rounded-[2rem] text-sm md:text-lg font-bold text-black placeholder:text-slate-300 focus:border-primary-800 focus:bg-white outline-none transition-all resize-none shadow-inner max-h-24 md:max-h-40"
                                  oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, window.innerWidth < 768 ? 96 : 160) + 'px'"></textarea>
                    </div>
                    <button type="submit" class="p-2.5 md:p-5 bg-primary-800 text-white rounded-full hover:bg-black transition-all shadow-xl shadow-primary-900/30 active:scale-90 group mb-0.5 border-2 md:border-4 border-white flex-shrink-0">
                        <svg class="w-5 h-5 md:w-7 md:h-7 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <!-- Empty State -->
        <div class="flex-1 hidden md:flex flex-col items-center justify-center p-20 text-center bg-slate-50" id="emptyState">
            {{-- <div class="w-40 h-40 bg-white rounded-full flex items-center justify-center mb-10 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border-4 border-primary-50">
                <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-primary-200"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h3 class="text-4xl font-black text-black mb-4 tracking-tighter">Pusat Konsultasi</h3>
            <p class="text-primary-600 text-xl font-bold max-w-md opacity-70 leading-relaxed uppercase tracking-[0.2em]">Pilih salah satu percakapan di samping untuk mulai mengobrol.</p> --}}
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div id="newChatModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 md:p-8">
    <div class="absolute inset-0 bg-primary-900/60 backdrop-blur-xl" onclick="closeNewChatModal()"></div>
    <div class="relative bg-white rounded-2xl md:rounded-[3rem] shadow-2xl w-full max-w-lg overflow-hidden border-2 md:border-4 border-white">
        <div class="p-6 md:p-10 border-b-2 md:border-b-4 border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl md:text-3xl font-black text-black tracking-tighter uppercase">Mulai Chat Baru</h3>
            <button onclick="closeNewChatModal()" class="p-3 md:p-4 hover:bg-white rounded-2xl md:rounded-3xl transition-all shadow-sm border-2 border-transparent hover:border-slate-100">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 md:space-y-4" id="contactList">
            <div class="p-12 text-center text-primary-300 font-black animate-pulse uppercase tracking-widest text-xs">Memuat daftar kontak...</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentConversationId = null;
let chatInterval = null;

async function loadConversations() {
    try {
        const res = await apiCall('/messages');
        const container = document.getElementById('chatList');
        const list = res?.data || [];
        
        if (list.length > 0) {
            container.innerHTML = list.map(c => {
                const isActive = currentConversationId === c.id;
                const partnerName = c.partner?.nama_lengkap || 'Pengguna Tidak Dikenal';
                const rawMessage = c.latest_detail?.message || 'Klik untuk memulai chat';
                const words = rawMessage.split(' ');
                const truncatedMessage = words.length > 5 ? words.slice(0, 5).join(' ') + '...' : rawMessage;

                return `
                    <div onclick="selectConversation(${c.id}, '${partnerName}')" 
                         class="flex items-center gap-3 md:gap-5 p-3 md:p-5 cursor-pointer border-b-2 border-slate-50 transition-all
                                ${isActive ? 'bg-primary-50 border-l-[6px] md:border-l-[10px] border-l-primary-800 shadow-inner' : 'hover:bg-slate-50'}">
                        <div class="w-10 h-10 md:w-14 md:h-14 rounded-full ${isActive ? 'bg-primary-800' : 'bg-primary-100'} flex items-center justify-center flex-shrink-0 shadow-lg border-2 border-white transition-all">
                            <span class="${isActive ? 'text-white' : 'text-primary-800'} font-black text-sm md:text-xl">${partnerName.charAt(0)}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-0.5">
                                <p class="text-sm md:text-lg font-black text-black truncate tracking-tighter">${partnerName}</p>
                                <span class="text-[8px] md:text-[10px] font-black text-primary-400 ml-2 uppercase tracking-widest bg-white px-1.5 py-0.5 rounded-md border border-slate-100 shadow-sm flex-shrink-0">${new Date(c.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                            </div>
                            <div class="flex justify-between items-center gap-2">
                                <p class="text-[11px] md:text-sm ${isActive ? 'text-primary-800' : 'text-slate-500'} truncate font-bold tracking-tight flex-1 min-w-0">${truncatedMessage}</p>
                                ${c.unread_count > 0 ? `<span class="bg-primary-800 text-white text-[9px] md:text-[11px] font-black px-2 py-0.5 md:px-2.5 md:py-1 rounded-lg ml-1 shadow-xl min-w-[1.25rem] md:min-w-[1.75rem] text-center flex-shrink-0">${c.unread_count}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            container.innerHTML = '<div class="p-16 text-center text-slate-300"><p class="text-xs font-black uppercase tracking-[0.3em]">Belum ada obrolan</p><p class="text-[11px] mt-4 opacity-70 font-bold">Tekan tombol + untuk mulai konsultasi</p></div>';
        }
    } catch (e) { console.error(e); }
}

async function openNewChatModal() {
    document.getElementById('newChatModal').classList.remove('hidden');
    const container = document.getElementById('contactList');
    container.innerHTML = '<div class="p-16 text-center text-primary-300 font-black animate-pulse uppercase tracking-widest text-xs">Mencari kontak...</div>';
    
    const user = JSON.parse(localStorage.getItem('user'));
    try {
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
        
        contacts = contacts.filter(c => c.id !== user.id);
        
        if (contacts.length > 0) {
            container.innerHTML = contacts.map(k => `
                <div onclick="startChatWith(${k.id}, '${k.nama_lengkap}')" class="flex items-center gap-3 md:gap-5 p-4 md:p-6 hover:bg-primary-50 cursor-pointer rounded-2xl md:rounded-[2rem] transition-all group border-2 md:border-4 border-transparent hover:border-primary-100 bg-slate-50/50">
                    <div class="w-10 h-10 md:w-14 md:h-14 rounded-full bg-primary-100 text-primary-800 flex items-center justify-center flex-shrink-0 shadow-md border-2 border-white">
                        <span class="font-black text-sm md:text-xl group-hover:scale-125 transition-transform">${k.nama_lengkap.charAt(0)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm md:text-lg font-black text-black truncate tracking-tight leading-none mb-0.5">${k.nama_lengkap}</p>
                        <p class="text-[8px] md:text-[11px] text-primary-500 font-black uppercase tracking-[0.1em] md:tracking-[0.2em]">${k.role || 'User'}</p>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<div class="p-16 text-center text-primary-300 font-black uppercase tracking-widest text-xs">Kontak tidak ditemukan</div>';
        }
    } catch (e) { 
        console.error(e);
        container.innerHTML = '<div class="p-16 text-center text-rose-400 font-black uppercase tracking-widest text-xs">Gagal mengambil data</div>';
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
        }
    } catch (e) { console.error(e); }
}

function selectConversation(id, name) {
    if (chatInterval) clearInterval(chatInterval);
    currentConversationId = id;
    
    // On mobile: go fullscreen
    if (window.innerWidth < 768) {
        document.getElementById('chatMainContainer').classList.add('chat-fullscreen');
        document.getElementById('chatListContainer').classList.add('hidden');
    }
    
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.style.display = 'flex';
    chatWindow.classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('emptyState').classList.remove('md:flex');
    
    document.getElementById('partnerAvatar').textContent = name.charAt(0);
    document.getElementById('partnerName').textContent = name;
    
    loadMessages();
    // Reset loaded flag so messages auto-scroll to bottom for new conversation
    const msgContainer = document.getElementById('messagesContainer');
    delete msgContainer.dataset.loaded;
    chatInterval = setInterval(loadMessages, 3000);
    loadConversations();
}

function closeChat() {
    if (chatInterval) clearInterval(chatInterval);
    currentConversationId = null;
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.style.display = 'none';
    chatWindow.classList.add('hidden');
    document.getElementById('chatListContainer').classList.remove('hidden');
    document.getElementById('chatMainContainer').classList.remove('chat-fullscreen');
    document.getElementById('emptyState').classList.add('md:flex');
    document.getElementById('emptyState').classList.remove('hidden');
}

async function deleteConversation() {
    if (!currentConversationId) return;
    if (!confirm('Apakah Anda yakin ingin menghapus seluruh riwayat percakapan ini?')) return;
    
    try {
        const res = await apiCall(`/messages/${currentConversationId}`, 'DELETE');
        if (res && res.success) {
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
        container.innerHTML = '<div class="text-center text-slate-300 py-32 text-sm font-black italic uppercase tracking-[0.3em] opacity-40">Kirim pesan pertama Anda</div>';
        return;
    }
    
    const html = '<div class="space-y-3 md:space-y-8">' + messages.map(m => {
        const isMe = m.sender_id === user.id;
        return `
            <div class="flex w-full ${isMe ? 'justify-end' : 'justify-start'} group">
                <div class="max-w-[85%] md:max-w-[70%] relative">
                    <div class="${isMe ? 'bg-primary-800 text-white shadow-[0_8px_25px_rgba(6,95,70,0.2)] rounded-t-2xl rounded-bl-2xl md:rounded-t-[2rem] md:rounded-bl-[2rem] border-2 border-primary-700' : 'bg-white text-black border-2 border-slate-100 shadow-[0_8px_25px_rgba(0,0,0,0.04)] rounded-t-2xl rounded-br-2xl md:rounded-t-[2rem] md:rounded-br-[2rem]'} px-4 py-3 md:px-8 md:py-5 transition-all">
                        <p class="text-sm md:text-lg font-bold leading-relaxed break-words whitespace-pre-wrap">${m.message}</p>
                        <div class="flex items-center justify-end gap-1.5 md:gap-2 mt-2 md:mt-3 pt-1.5 md:pt-2 border-t ${isMe ? 'border-white/20' : 'border-slate-50'}">
                            <span class="text-[9px] md:text-[11px] font-bold ${isMe ? 'text-white/60' : 'text-slate-400'}">${new Date(m.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                            ${isMe && m.is_read ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" class="text-emerald-400"><path d="M20 6L9 17l-5-5"/></svg>' : ''}
                        </div>
                    </div>
                    ${isMe ? `<button onclick="deleteMessage(${m.id})" 
                            class="absolute top-1/2 -translate-y-1/2 -left-10 md:-left-16 p-2 md:p-3 text-slate-300 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition-all bg-white shadow-xl rounded-xl md:rounded-2xl border-2 border-slate-50">
                        <svg class="w-3.5 h-3.5 md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                    </button>` : ''}
                </div>
            </div>
        `;
    }).join('') + '</div>';
    
    const wasAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 150;
    container.innerHTML = html;
    // Auto-scroll to bottom on first load or when already at bottom
    if (wasAtBottom || !container.dataset.loaded) {
        container.scrollTop = container.scrollHeight;
        container.dataset.loaded = 'true';
    }
}

document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('messageInput');
    const pesan = input.value.trim();
    if (!pesan || !currentConversationId) return;
    
    try {
        const res = await apiCall(`/messages/${currentConversationId}/send`, 'POST', { message: pesan });
        if (res && res.success) {
            input.value = '';
            input.style.height = '';
            loadMessages();
            loadConversations();
        }
    } catch (e) { console.error(e); }
});

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return;
    loadConversations();
});
</script>
@endsection