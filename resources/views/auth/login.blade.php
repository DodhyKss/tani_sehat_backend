@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 py-12 bg-primary-50/50">
    <!-- Premium Dynamic Background -->
    <div class="absolute inset-0 z-[-1]">
        <img src="{{ asset('public/images/login-bg.png') }}" class="w-full h-full object-cover scale-105 opacity-30 brightness-110" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-tr from-primary-900/30 via-white/40 to-emerald-900/10 backdrop-blur-[3px]"></div>
    </div>
    
    <!-- Floating Luxury Blobs -->
    <div class="absolute top-[-10%] left-[-5%] w-[60%] h-[60%] bg-primary-300/20 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-[60%] h-[60%] bg-emerald-300/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 4s;"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Brand Signature -->
        <div class="text-center mb-8 animate-in fade-in slide-in-from-top-6 duration-1000">
            <div class="inline-flex justify-center items-center p-3.5 bg-white/90 backdrop-blur-xl rounded-[2rem] shadow-xl mb-6 border border-white/60 relative group">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="text-primary-600 relative z-10" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Tani<span class="text-primary-600">Sehat</span></h1>
            <p class="text-primary-800/60 font-black text-[9px] uppercase tracking-[0.25em]">Health Management System</p>
        </div>

        <!-- Ultra-Premium Glass Card -->
        <div class="bg-white/65 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] p-8 md:p-10 border border-white/80 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-400 via-primary-600 to-primary-400 bg-[length:200%_100%] animate-[gradient_4s_linear_infinite]"></div>
            
            <div class="mb-8">
                <h2 class="text-xl font-black text-gray-900 tracking-tight mb-1">Selamat Datang</h2>
                <p class="text-gray-500 font-bold text-xs">Silakan masuk ke akun Anda.</p>
            </div>

            <form id="loginForm" class="space-y-5">
                <!-- NIK Input -->
                <div class="space-y-1.5">
                    <label for="nik" class="text-[9px] font-black text-primary-800/50 uppercase tracking-widest ml-1">NIK</label>
                    <div class="relative group/input">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-800/30 group-focus-within/input:text-primary-600 transition-all">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <input type="text" id="nik" class="w-full pl-11 pr-4 py-3 bg-white/40 border border-gray-100 focus:bg-white focus:border-primary-500 rounded-2xl shadow-sm focus:ring-8 focus:ring-primary-500/5 transition-all outline-none text-gray-800 font-bold text-sm placeholder:text-primary-300 placeholder:font-medium" required placeholder="16 Digit NIK">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="text-[9px] font-black text-primary-800/50 uppercase tracking-widest">Password</label>
                    </div>
                    <div class="relative group/input">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-800/30 group-focus-within/input:text-primary-600 transition-all">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <input type="password" id="password" class="w-full pl-11 pr-11 py-3 bg-white/40 border border-gray-100 focus:bg-white focus:border-primary-500 rounded-2xl shadow-sm focus:ring-8 focus:ring-primary-500/5 transition-all outline-none text-gray-800 font-bold text-sm placeholder:text-primary-300 placeholder:font-medium" required placeholder="••••••••">
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-primary-800/30 hover:text-primary-600 p-1">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Premium Submit Button -->
                <div class="pt-3">
                    <button type="submit" class="w-full bg-gradient-to-br from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-black py-4 px-6 rounded-2xl shadow-xl shadow-primary-900/10 hover:shadow-primary-900/20 transition-all transform active:scale-95 flex justify-center items-center gap-3 group/btn relative overflow-hidden">
                        <span id="btnText" class="text-sm uppercase tracking-widest relative z-10">MASUK SEKARANG</span>
                        <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        <div class="loader !border-t-white !w-5 !h-5 !border-[3px] relative z-10" id="loader"></div>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Elegant Footer -->
        <p class="mt-8 text-center text-[10px] text-primary-900/30 font-black tracking-[0.3em] uppercase">
            &copy; 2026 Tani Sehat
        </p>
    </div>
</div>

<style>
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-in {
        animation-duration: 1s;
        animation-fill-mode: both;
    }
</style>
@endsection

@section('scripts')
<script>
    if (localStorage.getItem('token')) {
        const savedUser = JSON.parse(localStorage.getItem('user'));
        if (savedUser && savedUser.role === 'warga') {
            window.location.href = '/warga';
        } else if (savedUser && savedUser.role === 'kader') {
            window.location.href = '/kader';
        } else {
            window.location.href = '/dashboard';
        }
    }

    // Modern Password Toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', () => {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        
        if (isPassword) {
            eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    });

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const nik = document.getElementById('nik').value;
        const password = document.getElementById('password').value;
        const btnText = document.getElementById('btnText');
        const loader = document.getElementById('loader');
        const submitBtn = e.target.querySelector('button[type="submit"]');
        
        btnText.style.display = 'none';
        loader.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ nik, password })
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                localStorage.setItem('token', result.data.token);
                localStorage.setItem('user', JSON.stringify(result.data.user));
                
                // Visual Success Feedback
                btnText.textContent = 'Otentikasi Berhasil...';
                btnText.style.display = 'block';
                loader.style.display = 'none';
                
                setTimeout(() => {
                    const user = result.data.user;
                    if (user.role === 'warga') {
                        window.location.href = '/warga';
                    } else if (user.role === 'kader') {
                        window.location.href = '/kader';
                    } else {
                        window.location.href = '/dashboard';
                    }
                }, 600);
            } else {
                showAlert(result.message || 'NIK atau Password tidak valid.', 'error');
                resetBtn();
            }
        } catch (err) {
            showAlert('Gagal terhubung ke server.', 'error');
            resetBtn();
        }

        function resetBtn() {
            btnText.style.display = 'block';
            loader.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
        }
    });

    // High-end Alert System
    window.showAlert = function(message, type = 'error') {
        const alertEl = document.getElementById('alertMessage');
        if (!alertEl) return;
        
        alertEl.classList.remove('hidden', 'bg-red-50', 'text-red-900', 'border-red-500', 'bg-emerald-50', 'text-emerald-900', 'border-emerald-500', 'animate-in', 'fade-in', 'slide-in-from-top-8');
        
        const isError = type === 'error';
        alertEl.classList.add(
            isError ? 'bg-red-50' : 'bg-emerald-50',
            isError ? 'text-red-900' : 'text-emerald-900',
            isError ? 'border-red-500' : 'border-emerald-500',
            'block', 'animate-in', 'fade-in', 'slide-in-from-top-8'
        );
        
        alertEl.innerHTML = `
            <div class="flex items-center gap-3 p-1">
                <div class="p-2 rounded-full ${isError ? 'bg-red-100' : 'bg-emerald-100'}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        ${isError 
                            ? '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>' 
                            : '<polyline points="20 6 9 17 4 12"/>'}
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-0.5 opacity-60">${isError ? 'Terjadi Kesalahan' : 'Sukses'}</p>
                    <p class="font-bold text-sm leading-tight">${message}</p>
                </div>
            </div>
        `;
        
        setTimeout(() => {
            alertEl.classList.add('hidden');
            alertEl.classList.remove('block');
        }, 5000);
    }
</script>
@endsection

