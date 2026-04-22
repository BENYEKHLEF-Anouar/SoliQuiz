@extends('layouts.guest')

@section('title', 'Connexion - SoliQuiz')

@section('content')
<div class="min-h-screen flex font-sans">
    <!-- Left Panel: Immersive Brand Side -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-[#0A0F1C] items-center justify-center p-20">
        <!-- Animated Background Elements -->
        <div class="absolute top-0 right-0 size-[500px] bg-primary-500/20 rounded-full blur-[120px] -mr-64 -mt-64 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 size-[500px] bg-indigo-600/10 rounded-full blur-[120px] -ml-64 -mb-64 animate-pulse" style="animation-delay: 2s"></div>
        
        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="relative z-10 w-full max-w-lg reveal">
            <div class="mb-12">
                <div class="inline-flex p-1 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl mb-8">
                    <div class="size-14 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-all duration-500">
                        <svg class="text-white size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-4xl font-heading font-black text-white leading-tight mb-6 tracking-tight">
                    L'excellence <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-200">pédagogique</span> <br/>
                    commence ici.
                </h2>
                
                <p class="text-slate-400 text-base font-medium leading-relaxed  opacity-70">
                    Accédez à votre espace sécurisé pour piloter vos évaluations et suivre vos performances académiques.
                </p>
            </div>

            <!-- Stats/Indicators -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white/5 border border-white/5 p-6 rounded-3xl transition-all hover:bg-white/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="size-1.5 rounded-full bg-primary-500"></div>
                        <p class="text-2xl font-black text-white">98%</p>
                    </div>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] ">Satisfaction Apprenants</p>
                </div>
                <div class="bg-white/5 border border-white/5 p-6 rounded-3xl transition-all hover:bg-white/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="size-1.5 rounded-full bg-emerald-500"></div>
                        <p class="text-2xl font-black text-white">Live</p>
                    </div>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] ">Synchronisation Temps Réel</p>
                </div>
            </div>
            
            <!-- Bottom Label -->
            <div class="mt-12 pt-6 border-t border-white/5 flex items-center gap-4">
                <span class="text-[8px] font-black text-slate-600 uppercase tracking-[0.4em] ">Propulsé par SoliQuiz v2.0</span>
            </div>
        </div>
    </div>

    <!-- Right Panel: Auth Form -->
    <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8 md:p-12 lg:p-20 relative overflow-hidden">
        <!-- Mobile Logo -->
        <div class="absolute top-8 left-8 lg:hidden">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-primary-500 rounded-xl flex items-center justify-center">
                    <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <span class="text-xl font-heading font-black text-slate-900 tracking-tighter">Soli<span class="text-primary-500">Quiz</span></span>
            </div>
        </div>

        <div class="w-full max-w-[400px] reveal">
            <!-- Header Section -->
            <div class="mb-12">
                <div class="hidden lg:flex items-center gap-4 mb-10">
                    <div class="size-10 bg-slate-900 rounded-xl flex items-center justify-center transition-all hover:bg-primary-500 group">
                        <svg class="text-white size-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-2xl font-heading font-black tracking-tighter text-slate-900 leading-none">Soli<span class="text-primary-500">Quiz</span></span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="size-1 rounded-full bg-emerald-500"></span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ">Système Authentifié</span>
                        </div>
                    </div>
                </div>

                <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4 uppercase">
                    Connectez <span class="text-primary-500">Votre Futur</span>
                </h1>
                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[10px]  flex items-center gap-3">
                    <span class="w-6 h-px bg-slate-200"></span>
                    Authentification Sécurisée
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-8">
                @csrf
                
                <div class="space-y-3">
                    <label for="email" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2  leading-none">Identifiant</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-primary-500 transition-colors">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-14 pr-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all placeholder:text-slate-200 "
                            placeholder="votre@solicode.co" required autofocus>
                    </div>
                    @error('email')
                        <p class="text-[10px] font-black text-rose-500 mt-3 ml-6 uppercase tracking-widest  flex items-center gap-2">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <!-- <div class="flex justify-between items-center px-2">
                        <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]  leading-none">Sécurité</label>
                        @if (Route::has('password.request'))
                            <a class="text-[10px] font-black text-primary-500 hover:text-primary-600 transition-colors uppercase tracking-widest " href="{{ route('password.request') }}">Oublié ?</a>
                        @endif
                    </div> -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-primary-500 transition-colors">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="password" name="password" id="password"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-14 pr-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all placeholder:text-slate-200 "
                            placeholder="············" required>
                    </div>
                </div>

                <div class="flex items-center justify-between px-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="remember" id="remember" class="peer sr-only" {{ old('remember') ? 'checked' : '' }}>
                            <div class="size-6 bg-slate-100 rounded-lg peer-checked:bg-primary-500 transition-all border border-slate-100"></div>
                            <svg class="absolute inset-0 size-6 text-white scale-0 peer-checked:scale-50 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest ">Session Persistante</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-5 px-8 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-500 active:scale-[0.98] transition-all uppercase tracking-widest text-[11px] flex items-center justify-center gap-4  group">
                        Initialiser la Session
                        <svg class="size-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </button>
                </div>
            </form>

            <div class="mt-16 text-center">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em]  leading-none">
                    © 2026 — SOLIQUIZ ECOSYSTEM
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .pl-18 { padding-left: 4.5rem; }
</style>
@endsection
