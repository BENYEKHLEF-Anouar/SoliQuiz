@extends('layouts.app')

@section('title', 'Paramètres - SoliQuiz')

@section('content')
<div class="reveal active">
    
    <!-- Header Section -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace {{ $user->type_profil === 'admin' ? 'Administrateur' : ($user->type_profil === 'formateur' ? 'Formateur' : 'Apprenant') }}</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Configuration</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Paramètres
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Gérez vos informations personnelles et configurez la sécurité de votre compte.
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl mb-8">
            <div class="size-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white shrink-0">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
            </div>
            <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)]">
                <div class="text-center mb-6">
                    <img class="w-24 h-24 rounded-[2rem] shadow-xl border-4 border-slate-50 mx-auto mb-4" 
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->nom_complet) }}&background=0f172a&color=fff&size=200&bold=true" alt="">
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight leading-none mb-2">{{ $user->prenom }} {{ $user->nom }}</h2>
                    <span class="inline-flex py-1.5 px-3.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] leading-none border bg-primary-50 text-primary-600 border-primary-100/50">
                        {{ $user->type_profil }}
                    </span>
                </div>
                
                <div class="pt-4 border-t border-slate-50">
                    <div class="flex items-center justify-between py-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase">Membre depuis</span>
                        <span class="text-xs font-bold text-slate-600">{{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Identity Form -->
            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)]">
                <h2 class="text-lg font-black text-slate-900 uppercase mb-6 border-b border-slate-50 pb-4">Identité & Coordonnées</h2>
                
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                            @error('prenom') <p class="text-rose-500 text-[9px] font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                            @error('nom') <p class="text-rose-500 text-[9px] font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2 opacity-60">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email</label>
                        <div class="w-full bg-slate-100 border border-slate-100 rounded-xl py-3 px-4 font-bold text-slate-500 flex items-center gap-3">
                            <svg class="size-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="px-6 py-3.5 bg-slate-950 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-primary-500 hover:shadow-lg hover:shadow-primary-500/20 transition-all duration-300">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Form -->
            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)]">
                <h2 class="text-lg font-black text-slate-900 uppercase mb-6 border-b border-slate-50 pb-4">Sécurité du Compte</h2>
                
                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2" x-data="{ show: false }">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mot de passe actuel</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="current_password" placeholder="••••••••"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all pr-12">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                                <svg x-show="!show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <p class="text-rose-500 text-[9px] font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2" x-data="{ show: false }">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nouveau mot de passe</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••"
                                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all pr-12">
                                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                                    <svg x-show="!show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg x-show="show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password') <p class="text-rose-500 text-[9px] font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2" x-data="{ show: false }">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Confirmation</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••"
                                       class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all pr-12">
                                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                                    <svg x-show="!show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg x-show="show" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="px-6 py-3.5 bg-white border border-slate-200 text-slate-655 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all">
                            Modifier le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection