@extends('layouts.guest')

@section('title', 'Connexion - SoliQuiz')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Panel: Immersive Brand Side (Fixed) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-slate-900 items-center justify-center p-20 sticky top-0 h-screen">
        <!-- Background mesh/blobs -->
        <div class="absolute top-0 right-0 size-96 bg-primary-500/20 rounded-full blur-[120px] -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 size-96 bg-primary-600/10 rounded-full blur-[120px] -ml-48 -mb-48"></div>
        
        <div class="relative z-10 w-full max-w-lg reveal active">
            <div class="flex flex-col">
                <div class="size-16 bg-primary-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-primary-500/40 mb-10">
                    <svg class="text-white size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <h2 class="text-5xl font-heading font-black text-white leading-[1.1] mb-8">
                    L'excellence <span class="text-primary-500">pédagogique</span> commence ici.
                </h2>
                <p class="text-slate-400 text-xl font-medium leading-relaxed opacity-80">
                    Accédez à votre espace sécurisé pour piloter vos évaluations et suivre vos performances académiques.
                </p>
            </div>
        </div>
    </div>

    <!-- Right Panel: Auth Form -->
    <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8 md:p-12 lg:p-20 relative">
        <!-- Mobile Logo -->
        <div class="absolute top-8 left-8 lg:hidden">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                    <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <span class="text-xl font-heading font-black text-slate-900">SoliQuiz</span>
            </div>
        </div>

        <div class="w-full max-w-[440px] reveal active">
            <!-- Logo Desktop (only visible on large screens inside this container) -->
            <div class="hidden lg:flex flex-col mb-16">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-primary-500 rounded-2xl flex items-center justify-center shadow-xl shadow-primary-500/20">
                        <svg class="text-white size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-3xl font-heading font-black tracking-tighter text-slate-900 leading-none">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1.5">Plateforme d'Évaluation</span>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4 uppercase">
                    Connectez <span class="text-primary-500">Votre Futur</span>
                </h1>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Espace d'Authentification Sécurisé</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-8">
                @csrf
                
                <div class="space-y-3">
                    <label for="email" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1 leading-none">Identifiant de Connexion</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-primary-500 transition-colors">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full bg-slate-50 border-2 border-transparent rounded-2xl py-3 pl-12 pr-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all placeholder:text-slate-300"
                            placeholder="votre@solicode.co" required autofocus>
                    </div>
                    @error('email')
                        <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                            <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-3" x-data="{ show: false }">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] leading-none">Clé de Sécurité</label>
                        @if (Route::has('password.request'))
                            <a class="text-[9px] font-black text-primary-500 hover:text-primary-600 transition-colors uppercase tracking-widest" href="{{ route('password.request') }}">Oublié ?</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-primary-500 transition-colors">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" id="password"
                            class="w-full bg-slate-50 border-2 border-transparent rounded-2xl py-3 pl-12 pr-12 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all placeholder:text-slate-300"
                            placeholder="············" required>
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                            <!-- Eye Icon (Show) -->
                            <svg x-show="!show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <!-- Eye Off Icon (Hide) -->
                            <svg x-show="show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                            <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between px-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="remember" id="remember" class="peer sr-only" {{ old('remember') ? 'checked' : '' }}>
                            <div class="size-6 bg-slate-100 rounded-lg group-hover:bg-slate-200 peer-checked:bg-primary-500 transition-colors shadow-inner"></div>
                            <svg class="absolute inset-0 size-6 text-white scale-0 peer-checked:scale-50 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Session Persistante</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-4 px-8 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-[0.98] transition-all uppercase tracking-[0.2em] text-[10px] flex items-center justify-center gap-3 group mt-4">
                    Initialiser la Session
                    <svg class="size-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </button>
            </form>

            <p class="mt-20 text-center text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] leading-none">
                © 2026 — SOLIQUIZ ECOSYSTEM
            </p>
        </div>
    </div>
</div>
@endsection