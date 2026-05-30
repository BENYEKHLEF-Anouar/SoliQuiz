@extends('components.layout.app')

@section('content')
    <div class="h-[44px] w-full shrink-0"></div> <!-- iOS Safe Area -->

    <main x-data="loginForm" class="w-full max-w-md mx-auto p-6 flex flex-col justify-center flex-1 animate-in fade-in duration-1000 relative">
        <!-- Return Button -->
        <div class="mb-4">
            <a href="{{ route('landing') }}" 
               class="inline-flex items-center gap-2 text-slate-400 hover:text-primary-600 transition-colors group">
                <div class="size-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:border-primary-200 group-hover:bg-primary-50 transition-all">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
            </a>
        </div>

        <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="text-center mb-10">
                    <div
                        class="size-20 bg-primary-500 rounded-[2rem] flex items-center justify-center shadow-2xl shadow-primary-500/30 mx-auto mb-6 transition-transform hover:scale-105">
                        <svg class="text-white size-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-heading font-extrabold text-slate-900 tracking-tighter">Soli<span
                            class="text-primary-500">Quiz</span></h1>
                    <p class="mt-2 text-xs text-slate-400 font-bold uppercase tracking-widest">
                        Portail d'évaluation mobile
                    </p>
                </div>

                <div class="mt-8">
                    <form @submit.prevent="handleLogin" class="grid gap-y-6">
                        
                        <!-- Error Alert -->
                        <div x-show="error" x-cloak class="p-4 bg-red-50 border border-red-100 rounded-2xl text-[10px] font-black text-red-600 uppercase tracking-widest text-center" x-text="error"></div>

                        <!-- Input Email -->
                        <div>
                            <label for="email"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Email
                                Solicode</label>
                            <div class="relative group">
                                <input type="email" id="email" name="email" x-model="email"
                                    class="py-4.5 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                                    placeholder="prenom@solicode.co" required>
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <label for="password"
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Mot
                                    de
                                    passe</label>
                                <button type="button" @click="showResetModal = true" class="text-[10px] text-primary-600 font-black uppercase tracking-widest outline-none border-none bg-transparent cursor-pointer">Oublié ?</button>
                            </div>
                            <div class="relative group">
                                <input type="password" id="password" name="password" x-model="password"
                                    class="py-4.5 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Checkbox Remember Me -->
                        <div class="flex items-center mb-2">
                            <div class="flex">
                                <input id="remember-me" name="remember-me" type="checkbox"
                                    class="shrink-0 size-4 mt-0.5 border-slate-200 rounded-md text-primary-600 focus:ring-primary-500 transition-all">
                            </div>
                            <div class="ms-3">
                                <label for="remember-me"
                                    class="text-xs font-bold text-slate-500 uppercase tracking-tight">Se souvenir de
                                    moi</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :disabled="loading"
                            class="w-full h-16 inline-flex justify-center items-center gap-x-2 text-xs font-black rounded-2xl border border-transparent bg-slate-950 text-white shadow-xl shadow-slate-900/10 hover:bg-slate-900 active:scale-[0.98] transition-all uppercase tracking-[0.2em] disabled:opacity-70 disabled:pointer-events-none">
                            <span x-show="!loading">Connexion</span>
                            <span x-show="loading" class="flex items-center gap-2" x-cloak>
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Connexion en cours...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reset Password Modal (Sleek Glassmorphic) -->
        <div x-show="showResetModal" 
             x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/50 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div @click.away="showResetModal = false"
                 class="w-full max-w-sm bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl p-8 relative"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                 
                <div class="text-center mb-6">
                    <h3 class="text-xl font-heading font-black text-slate-900">Mot de passe oublié</h3>
                    <p class="mt-2 text-xs text-slate-400 font-bold uppercase tracking-wider">
                        Saisis ton adresse e-mail pour réinitialiser
                    </p>
                </div>
                
                <form @submit.prevent="handleResetPassword" class="grid gap-y-4">
                
                    <!-- Alerts -->
                    <div x-show="resetError" class="p-3 bg-red-50 border border-red-100 rounded-xl text-[10px] font-black text-red-600 uppercase tracking-widest text-center" x-text="resetError" x-cloak></div>
                    <div x-show="resetSuccess" class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center" x-text="resetSuccess" x-cloak></div>
                    
                    <div>
                        <label for="resetEmail" class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">E-mail</label>
                        <input type="email" id="resetEmail" x-model="resetEmail" required
                               class="py-3 px-5 block w-full border-slate-100 bg-slate-50 rounded-xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                               placeholder="prenom@solicode.co">
                    </div>
                    
                    <button type="submit" :disabled="resetLoading"
                            class="w-full h-12 inline-flex justify-center items-center text-xs font-black rounded-xl border border-transparent bg-slate-950 text-white shadow-xl hover:bg-slate-900 active:scale-[0.98] transition-all uppercase tracking-wider disabled:opacity-70">
                        <span x-show="!resetLoading">Envoyer la demande</span>
                        <span x-show="resetLoading" class="flex items-center gap-1.5" x-cloak>
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Envoi...
                        </span>
                    </button>
                    
                    <button type="button" @click="showResetModal = false; resetError = null; resetSuccess = null;"
                            class="w-full h-12 inline-flex justify-center items-center text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 active:scale-[0.98] transition-all uppercase tracking-wider">
                        Fermer
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection