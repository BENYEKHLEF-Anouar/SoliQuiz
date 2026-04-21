@extends('layouts.guest')

@section('title', 'Connexion - SoliQuiz')

@section('content')
<div class="min-h-screen bg-mesh flex items-center justify-center p-6 lg:p-12">
    <div class="w-full max-w-[480px] reveal active">
        <!-- Logo Header -->
        <div class="flex justify-center mb-12">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group transition-transform hover:scale-105 active:scale-95 outline-none">
                <div class="size-12 bg-primary-500 rounded-[18px] flex items-center justify-center shadow-xl shadow-primary-500/25">
                    <svg class="text-white size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <span class="text-3xl font-heading font-black tracking-tight text-slate-900">Soli<span class="text-primary-500">Quiz</span></span>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="bg-white rounded-[40px] shadow-premium border border-slate-100 overflow-hidden relative">
            <div class="absolute top-0 right-0 size-32 bg-primary-50 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="p-8 lg:p-12 relative z-10">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4 uppercase">Content de vous <span class="text-primary-500">revoir</span></h1>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Identifiez-vous pour continuer</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Académique</label>
                        <div class="relative group">
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                                placeholder="votre@solicode.co" required autofocus>
                            @error('email')
                                <p class="text-[10px] font-bold text-red-500 mt-2 ml-4 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2 text-right">
                        <div class="flex justify-between items-center px-4">
                            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sécurité</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-black text-primary-500 hover:text-primary-600 transition-colors uppercase tracking-widest" href="{{ route('password.request') }}">Oublié ?</a>
                            @endif
                        </div>
                        <div class="relative group">
                            <input type="password" name="password" id="password"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                                placeholder="············" required>
                            @error('password')
                                <p class="text-[10px] font-bold text-red-500 mt-2 ml-4 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-4">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="remember" id="remember" class="peer sr-only" {{ old('remember') ? 'checked' : '' }}>
                                <div class="size-6 bg-slate-100 rounded-lg group-hover:bg-slate-200 peer-checked:bg-primary-500 transition-colors"></div>
                                <svg class="absolute inset-0 size-6 text-white scale-0 peer-checked:scale-50 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-500">Rester connecté</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-[24px] hover:bg-primary-500 shadow-2xl shadow-slate-900/10 active:scale-[0.98] transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-3">
                        Connexion Immédiate
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </button>
                </form>
            </div>

            <!-- Removing registration footer as per production requirements (Admin-only) -->

        </div>
        
        <p class="mt-10 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            © 2026 — SOLIQUIZ ECOSYSTEM
        </p>
    </div>
</div>
@endsection