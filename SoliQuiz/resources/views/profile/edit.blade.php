@extends('layouts.app')

@section('title', 'Paramètres du Profil - SoliQuiz')

@section('content')
<div class="reveal active">
    <!-- Header Hero -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Espace Personnel</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Configurations</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Paramètres <span class="text-primary-500">Profil</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Personnalisez votre identité numérique sur la plateforme et renforcez la sécurité de votre compte SoliQuiz.
            </p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="glass border-emerald-100 bg-emerald-50/50 p-6 rounded-[24px] flex items-center gap-4 mb-10 animate-in slide-in-from-top duration-500">
            <div class="size-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
            </div>
            <p class="text-sm font-bold text-slate-900">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">
        <!-- Sidebar Navigation (Mockup behavior) -->
        <div class="xl:col-span-1 space-y-6">
            <div class="glass bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-6 mb-10">
                    <div class="relative">
                        <img class="size-20 rounded-[28px] shadow-xl border-4 border-white" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->nom_complet) }}&background=0f172a&color=fff&size=200&bold=true" alt="">
                        <div class="absolute -bottom-1 -right-1 size-6 bg-emerald-500 rounded-full border-4 border-white"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black text-slate-900 leading-tight uppercase italic">{{ $user->prenom }} {{ $user->nom }}</span>
                        <span class="text-[10px] font-black text-primary-500 uppercase tracking-widest">{{ $user->type_profil }} Identification</span>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Membre depuis</span>
                        <span class="text-xs font-bold text-slate-700">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms Section -->
        <div class="xl:col-span-2 space-y-10">
            <!-- Basic Info Form -->
            <div class="glass bg-white rounded-[40px] p-8 lg:p-12 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 size-64 bg-slate-50 rounded-full -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <h2 class="text-2xl font-black font-heading text-slate-900 tracking-tight uppercase italic mb-10">Identité & Coordonnées</h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 italic">Prénom d'usage</label>
                                <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}"
                                       class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all shadow-sm">
                                @error('prenom') <p class="text-rose-500 text-[10px] font-bold ml-4">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 italic">Nom de famille</label>
                                <input type="text" name="nom" value="{{ old('nom', $user->nom) }}"
                                       class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all shadow-sm">
                                @error('nom') <p class="text-rose-500 text-[10px] font-bold ml-4">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-3 opacity-60">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 italic">Adresse de communication (Lecture seule)</label>
                            <div class="w-full bg-slate-100 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-500 flex items-center gap-4">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <span>{{ $user->email }}</span>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="btn-premium py-5 px-12 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-xs shadow-xl shadow-slate-900/10">
                                Synchroniser les Infos
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Form -->
            <div class="glass bg-white rounded-[40px] p-8 lg:p-12 border border-slate-100 shadow-sm">
                <h2 class="text-2xl font-black font-heading text-slate-900 tracking-tight uppercase italic mb-10">Protocole de Sécurité</h2>
                
                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 italic">Clef d'accès actuelle</label>
                        <input type="password" name="current_password" placeholder="••••••••"
                               class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all shadow-sm">
                        @error('current_password') <p class="text-rose-500 text-[10px] font-bold ml-4">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 italic">Nouvelle Clef</label>
                            <input type="password" name="password" placeholder="••••••••"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all shadow-sm">
                            @error('password') <p class="text-rose-500 text-[10px] font-bold ml-4">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 italic">Confirmation</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="btn-premium py-5 px-12 bg-white border-2 border-slate-100 text-slate-600 font-black rounded-3xl hover:bg-slate-50 active:scale-95 transition-all uppercase tracking-widest text-xs">
                            Renouveler le Chiffrement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection