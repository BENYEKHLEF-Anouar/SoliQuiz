@extends('layouts.base')

@section('title', 'SoliQuiz - L\'évaluation interactive par Solicode')

@section('body-class', 'bg-white text-slate-800 font-sans antialiased overflow-x-hidden relative')

@section('body')
    <!-- Background Decoration -->
    <div class="blob -top-48 -left-48 animate-pulse" style="position: absolute; width: 600px; height: 600px; background: linear-gradient(to right, hsla(190, 80%, 45%, 0.15), transparent); filter: blur(100px); border-radius: 50%; z-index: -1;"></div>
    <div class="blob top-1/2 right-0 opacity-50" style="position: absolute; width: 600px; height: 600px; background: linear-gradient(to left, hsla(190, 80%, 45%, 0.1), transparent); filter: blur(100px); border-radius: 50%; z-index: -1;"></div>

    <!-- Interactive Navbar -->
    <nav x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
         class="fixed top-0 left-0 w-full z-[100] transition-all duration-700 py-6"
         :class="scrolled ? 'bg-white/95 backdrop-blur-lg border-b border-slate-50 py-3 shadow-sm' : ''">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            
            <a href="#" class="flex items-center gap-3 transition-all hover:scale-105 active:scale-95 outline-none group text-left">
                <div class="size-10 bg-primary-500 rounded-[14px] flex items-center justify-center shadow-xl shadow-primary-500/25">
                    <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-xl font-heading font-black text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">LMS Companion</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-10">
                <a href="#features" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-primary-500 transition-colors">Features</a>
                <a href="#roles" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-primary-500 transition-colors">Usage</a>
                
                <div class="h-5 w-px bg-slate-100"></div>

                @auth
                    <!-- Premium Profile Dropdown (Solid White) -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" 
                                class="flex items-center gap-3 p-1 rounded-2xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 active:scale-95 bg-white shadow-sm pr-4">
                            <div class="size-9 bg-primary-500 text-white rounded-[14px] flex items-center justify-center p-2 shadow-lg shadow-primary-500/10">
                                <span class="font-black text-xs">{{ substr(Auth::user()->prenom, 0, 1) }}</span>
                            </div>
                            <div class="flex flex-col items-start leading-tight">
                                <span class="text-[9px] font-black uppercase tracking-[0.1em] text-slate-400">Compte</span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-700">{{ Auth::user()->prenom }}</span>
                            </div>
                            <svg class="size-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             class="absolute right-0 mt-3 w-64 bg-white border border-slate-100 shadow-2xl rounded-[32px] py-3 z-[110] overflow-hidden">
                            <div class="px-6 py-4 border-b border-primary-50 mb-2">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Session Active</p>
                                <p class="text-xs font-black text-slate-900 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-600 hover:text-primary-600 hover:bg-primary-50/50 transition-all">
                                <div class="size-8 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                Dashboard
                            </a>
                            <div class="h-px bg-primary-50 mx-4 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-red-500 hover:bg-red-50/50 transition-all text-left font-bold">
                                    <div class="size-8 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    </div>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="py-3 px-8 bg-slate-900 text-white font-black rounded-[16px] hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/10 active:scale-95 text-[10px] uppercase tracking-[0.2em]">
                        Se Connecter
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-20">
        <!-- Hero Section (Adjusted Spacing) -->
        <section class="relative py-12 lg:py-24 reveal">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative z-10 text-left">
                    <span class="inline-flex items-center gap-2.5 bg-primary-50 text-primary-600 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-[0.2em] mb-8 border border-primary-100 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        SYNC SOLILMS v1.0 ACTIVE
                    </span>
                    
                    <h1 class="text-5xl md:text-7xl lg:text-[90px] font-heading font-bold text-slate-950 leading-[0.95] mb-8 tracking-tighter italic">
                        L'évaluation <br> qui <span class="text-primary-500">booste</span> <br> le futur.
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-500 mb-12 leading-relaxed max-w-xl font-medium">
                        SoliQuiz transforme vos sessions d'évaluation en expériences interactives. Synchronisé en temps
                        réel avec SoliLMS.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-5">
                        <a href="{{ Auth::check() ? route('dashboard') : route('login') }}"
                            class="py-4 px-10 bg-primary-500 text-white font-black rounded-2xl hover:bg-primary-600 transition-all shadow-2xl shadow-primary-500/30 text-center uppercase tracking-widest text-xs active:scale-95">
                            Démarrer l'expérience
                        </a>
                        <a href="#features"
                            class="py-4 px-10 bg-white text-slate-700 border border-slate-200 font-bold rounded-2xl hover:bg-slate-50 transition-all text-center uppercase tracking-widest text-xs active:scale-95 shadow-sm">
                            En savoir plus
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-primary-500/10 rounded-full blur-[100px] scale-125 -z-10"></div>
                    
                    <div class="glass bg-white/40 rounded-[48px] border-[10px] border-white p-3 shadow-premium transform rotate-2 hover:rotate-0 transition-transform duration-700 overflow-hidden group">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&q=80&w=1200"
                            alt="Collaboration" class="rounded-[36px] shadow-2xl group-hover:scale-105 transition-transform duration-1000">
                    </div>

                    <!-- Float Badge -->
                    <div class="absolute bottom-6 -left-6 bg-white p-6 rounded-[28px] shadow-premium border border-slate-100 hidden sm:flex items-center gap-4 animate-in fade-in slide-in-from-left-6 duration-1000">
                        <div class="size-3.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_20px_rgba(16,185,129,0.5)]"></div>
                        <div class="flex flex-col leading-none">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Status</span>
                            <span class="text-xs font-black text-slate-900 italic uppercase mt-1">Sync Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 bg-slate-50/30 reveal border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <div class="max-w-3xl mx-auto mb-20">
                    <h2 class="text-[9px] font-black text-primary-500 uppercase tracking-[0.4em] mb-6">Écosystème Digital</h2>
                    <p class="text-4xl font-heading font-black text-slate-900 tracking-tighter italic">Des outils puissants pour une éducation moderne.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-10">
                    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-50 hover:shadow-premium hover:-translate-y-2 transition-all duration-500 group">
                        <div class="size-16 bg-primary-50 text-primary-500 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary-500 group-hover:text-white transition-all shadow-sm">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black mb-4 italic uppercase tracking-tight">Temps Réel</h3>
                        <p class="text-slate-500 leading-relaxed font-medium text-sm">Visualisez les réponses de vos apprenants en direct et réajustez votre cours instantanément.</p>
                    </div>
                    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-50 hover:shadow-premium hover:-translate-y-2 transition-all duration-500 group">
                        <div class="size-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-sm">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black mb-4 italic uppercase tracking-tight">Sync SoliLMS</h3>
                        <p class="text-slate-500 leading-relaxed font-medium text-sm">Plus besoin de copier les notes. Tout est exporté automatiquement vers votre plateforme LMS.</p>
                    </div>
                    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-50 hover:shadow-premium hover:-translate-y-2 transition-all duration-500 group">
                        <div class="size-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black mb-4 italic uppercase tracking-tight text-slate-800">Analyses</h3>
                        <p class="text-slate-500 leading-relaxed font-medium text-sm">Identifiez les compétences non acquises par cohorte grâce à nos rapports détaillés.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Roles Section -->
        <section id="roles" class="py-24 reveal overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-20 items-center">
                    <div>
                        <h2 class="text-5xl font-heading font-black text-slate-950 mb-10 tracking-tighter italic leading-tight">Un outil, <br> deux univers.</h2>
                        <div class="space-y-10">
                            <div class="flex gap-6 group">
                                <div class="size-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 font-black group-hover:bg-primary-500 group-hover:text-white transition-all shadow-sm shrink-0">01</div>
                                <div>
                                    <h4 class="font-black text-xl mb-3 italic uppercase tracking-tight">Pour l'Apprenant</h4>
                                    <p class="text-slate-500 font-medium whitespace-pre-line">Espace personnel, suivi de progression, historique de résultats et gamification via podium.</p>
                                </div>
                            </div>
                            <div class="flex gap-6 group">
                                <div class="size-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 font-black group-hover:bg-primary-500 group-hover:text-white transition-all shadow-sm shrink-0">02</div>
                                <div>
                                    <h4 class="font-black text-xl mb-3 italic uppercase tracking-tight">Pour le Formateur</h4>
                                    <p class="text-slate-500 font-medium whitespace-pre-line">Éditeur de QCM intuitif, gestion de cohortes, supervision en direct et rapports automatisés.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-950 rounded-[64px] p-16 text-center relative overflow-hidden shadow-2xl">
                        <div class="absolute -top-12 -right-12 w-64 h-64 bg-primary-500/10 rounded-full blur-[80px]"></div>
                        <h3 class="text-3xl font-heading font-black text-white mb-8 italic uppercase tracking-tighter">Prêt à évaluer ?</h3>
                        <p class="text-slate-400 mb-12 font-medium leading-relaxed">Rejoignez les centres qui utilisent SoliQuiz pour dynamiser leurs formations.</p>
                        <a href="{{ Auth::check() ? route('dashboard') : route('login') }}"
                            class="inline-block py-5 px-12 bg-primary-500 text-white font-black rounded-[24px] hover:bg-primary-600 transition-all uppercase tracking-[0.3em] text-[10px] shadow-2xl shadow-primary-500/25 active:scale-95">
                            Accéder à mon espace
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-100 py-24">
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center">
            <div class="flex flex-col items-center gap-3 mb-8">
                <div class="size-10 bg-slate-950 rounded-[12px] flex items-center justify-center p-2 shadow-xl">
                    <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="text-center">
                    <span class="text-2xl font-heading font-black text-slate-950 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">LMS Companion</p>
                </div>
            </div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em]">© 2026 — SOLICODE DIGITAL ECOSYSTEM</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        });
    </script>
@endsection
