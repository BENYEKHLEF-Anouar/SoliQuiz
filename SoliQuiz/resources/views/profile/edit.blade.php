@extends('layouts.app')

@section('title', 'Paramètres du Profil - SoliQuiz')

@section('content')
<div class="reveal active">
    
    <!-- Header -->
    <div class="mb-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase italic">Mon Profil</h1>
                <p class="text-sm text-slate-400 mt-1">Gérez vos informations personnelles et votre mot de passe</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-100 rounded-xl mb-8">
            <div class="size-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
            </div>
            <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-100 p-6">
                <div class="text-center mb-6">
                    <img class="w-20 h-20 rounded-2xl shadow-lg mx-auto mb-4" 
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->nom_complet) }}&background=0f172a&color=fff&size=200&bold=true" alt="">
                    <h2 class="text-lg font-black text-slate-900 uppercase italic">{{ $user->prenom }} {{ $user->nom }}</h2>
                    <p class="text-[10px] font-black text-primary-500 uppercase tracking-widest mt-1">{{ $user->type_profil }}</p>
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
        <div class="lg:col-span-3 space-y-6">
            <!-- Identity Form -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6">
                <h2 class="text-lg font-black text-slate-900 uppercase italic mb-6">Identité & Coordonnées</h2>
                
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
                        <button type="submit" class="px-6 py-3 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Form -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6">
                <h2 class="text-lg font-black text-slate-900 uppercase italic mb-6">Sécurité du Compte</h2>
                
                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2" x-data="{ show: false }">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mot de passe actuel</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="current_password" placeholder="••••••••"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 px-4 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all pr-12">
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
                        <button type="submit" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all">
                            Modifier le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection