@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden px-4 py-12">
    <!-- Premium Background -->
    <div class="absolute inset-0 z-[-1]">
        <img src="{{ asset('public/images/login-bg.png') }}" class="w-full h-full object-cover scale-105 animate-pulse" style="animation-duration: 8s;" alt="Background">
        <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px]"></div>
    </div>
    
    <!-- Animated Blobs for extra depth -->
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary-200/40 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-emerald-200/40 rounded-full blur-[120px] animate-pulse" style="animation-delay: 3s;"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Brand Header -->
        <div class="text-center mb-8 transform transition-all duration-1000 animate-in fade-in slide-in-from-top-6">
            <div class="inline-flex justify-center items-center p-4 bg-white/80 backdrop-blur-md rounded-2xl shadow-xl mb-6 border border-white/60 relative group">
                <div class="absolute inset-0 bg-primary-500/10 rounded-2xl scale-0 group-hover:scale-100 transition-transform duration-500"></div>
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="text-primary-600 relative z-10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Tani<span class="text-primary-600">Sehat</span></h1>
            <p class="text-gray-500 font-bold text-sm tracking-tight">Pantau Kesehatan Terpadu</p>
        </div>

        <!-- Glassmorphism Login Card -->
        <div class="bg-white/75 backdrop-blur-2xl rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] p-8 md:p-10 border border-white/80 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-primary-400 via-primary-600 to-primary-400 bg-[length:200%_100%] animate-[gradient_3s_linear_infinite]"></div>
            
            <form id="loginForm" class="space-y-6">
                <!-- NIK Input -->
                <div class="space-y-1.5">
                    <label for="nik" class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">NIK</label>
                    <div class="relative group/input">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within/input:text-primary-600 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <input type="text" id="nik" class="w-full pl-12 pr-4 py-3.5 bg-white/60 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-gray-800 font-bold placeholder:text-gray-400 placeholder:font-medium" required placeholder="16 Digit NIK">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center ml-1">
                        <label for="password" class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-[9px] font-black text-primary-600 hover:text-primary-700 uppercase tracking-widest">Lupa?</a>
                    </div>
                    <div class="relative group/input">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within/input:text-primary-600 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <input type="password" id="password" class="w-full pl-12 pr-12 py-3.5 bg-white/60 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-gray-800 font-bold placeholder:text-gray-400 placeholder:font-medium" required placeholder="••••••••">
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-br from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-black py-4 px-6 rounded-2xl shadow-xl shadow-primary-500/20 hover:shadow-primary-500/40 transition-all transform active:scale-95 flex justify-center items-center gap-3 group/btn">
                        <span id="btnText" class="text-sm tracking-tight">MASUK SEKARANG</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        <div class="loader !border-t-white !w-5 !h-5 !border-[3px]" id="loader"></div>
                    </button>
                </div>
            </form>
            
         
        </div>
        
        <!-- Subtle Footer -->
        <p class="mt-10 text-center text-[10px] text-gray-400 font-bold tracking-widest uppercase opacity-60">
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
        window.location.href = '/';
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

