@extends('layouts.guest')

@section('title', 'Inscription - SoliQuiz')

@section('content')
<div class="min-h-screen bg-mesh flex items-center justify-center p-6 lg:p-12">
    <div class="w-full max-w-[540px] reveal active">
        <!-- Logo Header -->
        <div class="flex justify-center mb-10">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group transition-transform hover:scale-105 active:scale-95 outline-none">
                <div class="size-11 bg-primary-500 rounded-[16px] flex items-center justify-center shadow-xl shadow-primary-500/25">
                    <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <span class="text-2xl font-heading font-black tracking-tight text-slate-900">Soli<span class="text-primary-500">Quiz</span></span>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="bg-white rounded-[40px] shadow-premium border border-slate-100 overflow-hidden relative">
            <div class="p-8 lg:p-10 relative z-10">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none mb-3 uppercase">Créer un <span class="text-primary-500">Profil</span></h1>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[9px]">Rejoignez l'écosystème Solicode</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="prenom" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Prénom</label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-[20px] py-3.5 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                                placeholder="Jean" required autofocus>
                            @error('prenom')
                                <p class="text-[9px] font-bold text-red-500 mt-1 ml-4 uppercase">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="nom" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nom</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-[20px] py-3.5 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                                placeholder="Dupont" required>
                            @error('nom')
                                <p class="text-[9px] font-bold text-red-500 mt-1 ml-4 uppercase">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Académique</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-3.5 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                            placeholder="votre@solicode.co" required>
                        @error('email')
                            <p class="text-[9px] font-bold text-red-500 mt-1 ml-4 uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Mot de passe</label>
                            <input type="password" name="password" id="password"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-[20px] py-3.5 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                                placeholder="········" required>
                        </div>
                        <div class="space-y-2">
                            <label for="password-confirm" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Confirmation</label>
                            <input type="password" name="password_confirmation" id="password-confirm"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-[20px] py-3.5 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all placeholder:text-slate-300"
                                placeholder="········" required>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-[24px] hover:bg-primary-500 shadow-2xl shadow-slate-900/10 active:scale-[0.98] transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-3 mt-4">
                        Créer mon accès
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 border-t border-slate-100 p-8 text-center">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                    Déjà inscrit ? 
                    <a href="{{ route('login') }}" class="text-primary-500 hover:text-primary-600 ml-1 transition-colors">Se connecter</a>
                </p>
            </div>
        </div>
        
        <p class="mt-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            SoliQuiz — Powered by Solicode
        </p>
    </div>
</div>
@endsection