@extends('layouts.guest')

@section('title', 'Réinitialisation - SoliQuiz')

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

        <!-- Reset Card -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
            <div class="absolute top-0 right-0 size-32 bg-primary-50 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="p-8 lg:p-12 relative z-10">
                <div class="text-center mb-10">
                    <div class="size-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                        <svg class="size-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4 uppercase">Mot de passe <span class="text-primary-500">Oublié</span></h1>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Récupération de l'accès</p>
                </div>

                @if (session('status'))
                    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Email Académique</label>
                        <div class="relative group">
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-2xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all placeholder:text-slate-300"
                                placeholder="votre@solicode.co" required autofocus>
                            @error('email')
                                <p class="text-[10px] font-bold text-red-500 mt-2 ml-4 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full h-16 inline-flex justify-center items-center gap-x-2 text-xs font-black rounded-2xl border border-transparent bg-slate-950 text-white shadow-xl shadow-slate-900/10 hover:bg-primary-500 active:scale-[0.98] transition-all uppercase tracking-[0.2em]">
                        Envoyer le lien
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 border-t border-slate-100 p-8 text-center">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    Vous vous en souvenez ? 
                    <a href="{{ route('login') }}" class="text-primary-500 hover:text-primary-600 ml-1 transition-colors">Se connecter</a>
                </p>
            </div>
        </div>
        
        <p class="mt-10 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            © 2026 — SOLIQUIZ ECOSYSTEM
        </p>
    </div>
</div>
@endsection
