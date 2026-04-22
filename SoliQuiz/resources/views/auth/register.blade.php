@extends('layouts.app')

@section('content')
<div class="min-h-[90dvh] flex items-center justify-center p-6">
    <main class="w-full max-w-md">
        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="text-center mb-10">
                    <div class="flex justify-center mb-8">
                        <div class="flex items-center gap-3 group outline-none">
                            <div class="size-14 bg-primary-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-primary-500/20 transition-transform group-hover:scale-110">
                                <svg class="text-white size-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <path d="m9 15 2 2 4-4" />
                                </svg>
                            </div>
                            <span class="text-4xl font-heading font-black text-slate-900 tracking-tighter">Soli<span class="text-primary-500">Quiz</span></span>
                        </div>
                    </div>
                    <h1 class="block text-2xl font-black font-heading text-slate-900 tracking-tight uppercase ">Rejoindre SoliQuiz</h1>
                    <p class="mt-3 text-sm text-slate-400 font-bold uppercase tracking-widest ">Créez votre compte apprenant</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Input Nom -->
                        <div class="space-y-2">
                            <label for="nom" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]  ml-1">Nom</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                class="py-4 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none @error('nom') border-red-500 @enderror"
                                placeholder="Nom">
                        </div>

                        <!-- Input Prénom -->
                        <div class="space-y-2">
                            <label for="prenom" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]  ml-1">Prénom</label>
                            <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required
                                class="py-4 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none @error('prenom') border-red-500 @enderror"
                                placeholder="Prénom">
                        </div>
                    </div>

                    <!-- Input Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]  ml-1">Adresse Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="py-4 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none @error('email') border-red-500 @enderror"
                            placeholder="Email institutionnel">
                        @error('email')
                            <p class="mt-2 text-xs text-red-500 font-bold ">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]  ml-1">Mot de passe</label>
                        <input type="password" id="password" name="password" required
                            class="py-4 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none @error('password') border-red-500 @enderror"
                            placeholder="8 caractères minimum">
                    </div>

                    <!-- Input Confirm Password -->
                    <div class="space-y-2">
                        <label for="password-confirm" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]  ml-1">Confirmation</label>
                        <input type="password" id="password-confirm" name="password_confirmation" required
                            class="py-4 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                            placeholder="Répétez le mot de passe">
                    </div>

                    <!-- Action Button -->
                    <button type="submit"
                        class="w-full h-16 inline-flex justify-center items-center gap-x-3 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl border border-transparent bg-slate-900 text-white hover:bg-slate-950 shadow-2xl shadow-slate-900/10 active:scale-[0.98] transition-all">
                        Créer mon compte
                        <svg class="shrink-0 size-4 group-hover:translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 border-t border-slate-100 p-6 text-center">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest ">
                    Déjà inscrit ? <a href="{{ route('login') }}" class="text-primary-500 hover:text-primary-600 transition-colors">Se connecter</a>
                </p>
            </div>
        </div>
    </main>
</div>
@endsection
