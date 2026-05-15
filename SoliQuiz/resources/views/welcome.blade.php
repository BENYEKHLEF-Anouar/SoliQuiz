<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SoliQuiz') }} - L'évaluation interactive par Solicode</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="SoliQuiz transforme vos sessions d'évaluation en expériences interactives. Synchronisé en temps réel avec SoliLMS.">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden relative" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Premium Background Blobs -->
    <div class="blob opacity-20 -top-24 -left-24 animate-pulse-soft"></div>
    <div class="blob opacity-10 top-1/2 right-0 animate-float"></div>
    <div class="blob opacity-15 top-[70%] -left-48 animate-pulse-soft"
        style="background: linear-gradient(to left, var(--color-primary-400), transparent)"></div>

    <!-- Header / Nav -->
    <header :class="scrolled ? 'bg-white/90 shadow-lg' : 'bg-white/40'" class="fixed top-0 w-full backdrop-blur-md z-50 border-b border-slate-100 transition-all duration-300">
        <nav class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group outline-none">
                <div
                    class="size-11 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                    <svg class="text-white size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-heading font-black tracking-tight text-slate-900 leading-none">Soli<span class="text-primary-500">Quiz</span></span>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1 italic">Plateforme d'Évaluation</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-10">
                <a href="#features" class="text-[11px] font-bold text-slate-500 hover:text-primary-500 transition-colors uppercase tracking-widest">Fonctionnalités</a>
                <a href="#roles" class="text-[11px] font-bold text-slate-500 hover:text-primary-500 transition-colors uppercase tracking-widest">Utilisation</a>
                
                @if (Route::has('login'))
                    @auth
                        <div x-data="{ userOpen: false }" class="relative">
                            <button @click="userOpen = !userOpen" 
                                    class="flex items-center gap-3 p-1.5 pr-6 bg-slate-900 text-white rounded-2xl hover:bg-slate-800 transition-all shadow-lg active:scale-95 group">
                                <img class="size-9 rounded-xl border border-white/20" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nom_complet) }}&background=17a2b8&color=fff&bold=true" 
                                     alt="Avatar">
                                <div class="flex flex-col items-start leading-none">
                                    <span class="text-[11px] font-black uppercase tracking-widest">{{ Auth::user()->prenom }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Accès Dashboard</span>
                                </div>
                                <svg class="size-4 text-slate-500 group-hover:text-white transition-transform" :class="userOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu: Premium Glassmorphism -->
                            <div x-show="userOpen" 
                                 @click.away="userOpen = false"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                 class="absolute top-full right-0 mt-4 w-64 bg-white rounded-[2rem] border border-slate-200 shadow-premium p-3 z-[100]"
                                 x-cloak>
                                
                                <a href="{{ route('dashboard') }}" 
                                    class="flex items-center gap-3 px-5 py-4 rounded-2xl text-slate-600 hover:bg-primary-50 hover:text-primary-600 transition-all text-[11px] font-black uppercase tracking-widest italic group/item">
                                    <div class="size-8 bg-primary-50 text-primary-500 rounded-xl flex items-center justify-center group-hover/item:bg-primary-500 group-hover/item:text-white transition-all shadow-sm">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                    </div>
                                    Tableau de Bord
                                </a>

                                <div class="h-px bg-slate-100/50 my-2 mx-4"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-rose-500 hover:bg-rose-50 transition-all text-[11px] font-black uppercase tracking-widest italic group/item">
                                        <div class="size-8 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center group-hover/item:bg-rose-500 group-hover/item:text-white transition-all shadow-sm">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        </div>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="py-3 px-8 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-all shadow-lg shadow-primary-500/25 active:scale-95 text-[11px] uppercase tracking-widest">
                            Connexion
                        </a>
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <main class="pt-20">
        <!-- Hero Section -->
        <section class="relative py-20 lg:py-32 overflow-hidden reveal">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-3 bg-primary-50/50 backdrop-blur-sm border border-primary-100 text-primary-600 px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-8">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Production Ready v1.0
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-heading font-black text-slate-900 leading-[1.05] mb-8">
                        L'évaluation qui <span class="text-primary-500">booste</span> l'apprentissage.
                    </h1>
                    <p class="text-lg text-slate-500 mb-12 leading-relaxed max-w-xl">
                        SoliQuiz transforme vos sessions d'évaluation en expériences interactives et immersives. 
                        Analyses automatiques et synchronisation fluide pour un suivi pédagogique d'excellence.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}"
                            class="py-3.5 px-10 bg-primary-500 text-white font-bold rounded-2xl hover:bg-primary-600 transition-all shadow-xl shadow-primary-500/30 text-center uppercase tracking-widest group">
                            Commencer maintenant
                            <svg class="inline-block ml-2 size-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <a href="#features"
                            class="py-3.5 px-10 bg-white text-slate-700 border border-slate-200 font-bold rounded-2xl hover:bg-slate-50 transition-all text-center uppercase tracking-widest shadow-sm">
                            Découvrir
                        </a>
                    </div>
                </div>
                <div class="relative lg:-mr-20">
                    <div class="absolute inset-x-0 top-0 h-[500px] bg-primary-500/10 rounded-full blur-[120px] scale-125 -z-10 translate-y-20"></div>
                    <div class="relative group">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=1200"
                            alt="Innovation Solicode" class="rounded-[3rem] shadow-2xl border-8 border-white/50 backdrop-blur-sm transition-transform duration-700 group-hover:scale-[1.02]">
                        
                        <!-- Floating Glass Card -->
                        <!-- <div class="absolute -bottom-12 -left-12 bg-white/80 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-white/60 hidden sm:block max-w-[240px] animate-bounce-slow">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="size-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sync Active</span>
                            </div>
                            <p class="text-sm font-bold text-slate-900">SoliLMS Connecté</p>
                            <p class="text-[11px] text-slate-500 mt-1">Données sécurisées et synchronisées</p>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 bg-white/40 backdrop-blur-sm reveal">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto mb-24">
                    <h2 class="text-[11px] font-black text-primary-500 uppercase tracking-[0.4em] mb-6">Expertise Pédagogique</h2>
                    <p class="text-4xl md:text-5xl font-heading font-black text-slate-900 mb-8 leading-tight">
                        Des outils conçus pour l'excellence académique.
                    </p>
                    <div class="h-1.5 w-24 bg-primary-500 rounded-full mx-auto"></div>
                </div>

                <div class="grid md:grid-cols-3 gap-10">
                    <!-- Feature 1 -->
                    <div class="bg-white/60 backdrop-blur-md p-12 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 group">
                        <div class="size-16 bg-primary-50 text-primary-500 rounded-2xl flex items-center justify-center mb-8 transition-transform group-hover:scale-110 group-hover:rotate-6">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black mb-5 text-slate-900">Performance Live</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            Suivez la progression de vos cohortes en temps réel. Identifiez instantanément les points de blocage et réagissez.
                        </p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="bg-white/60 backdrop-blur-md p-12 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 group">
                        <div class="size-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-8 transition-transform group-hover:scale-110 group-hover:-rotate-6">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black mb-5 text-slate-900">Architecture UA</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            Structurez vos évaluations selon le référentiel : Sessions, Unités d'Apprentissage et Compétences spécifiques.
                        </p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="bg-white/60 backdrop-blur-md p-12 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 group">
                        <div class="size-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-8 transition-transform group-hover:scale-110 group-hover:rotate-6">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black mb-5 text-slate-900">Analyses Data</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            Visualisez les écarts de performance par compétence pour orienter vos remédiations pédagogiques avec précision.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Roles Section -->
        <section id="roles" class="py-32 reveal">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-24 items-center">
                    <div>
                        <h2 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 mb-10 leading-tight">Un outil, deux univers harmonisés.</h2>
                        <div class="space-y-12">
                            <div class="flex gap-8 group">
                                <div class="size-14 rounded-2xl bg-white shadow-xl shadow-slate-200/50 text-primary-500 font-bold flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-primary-500 group-hover:text-white group-hover:rotate-12">
                                    <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-xl mb-3 text-slate-900">Pour l'Étudiant</h4>
                                    <p class="text-slate-500 text-sm leading-relaxed">
                                        Interface épurée focalisée sur la réussite. Suivez l'historique de vos tentatives et visualisez vos progrès par compétence.
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-8 group">
                                <div class="size-14 rounded-2xl bg-white shadow-xl shadow-slate-200/50 text-emerald-500 font-bold flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-emerald-500 group-hover:text-white group-hover:-rotate-12">
                                    <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.435 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0l4.858 1.308m10.624-1.308l-4.858 1.308m0 0a50.503 50.503 0 00-3.328 4.419m3.328-4.419l3.328 4.419m-3.328-4.419V17.5" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-xl mb-3 text-slate-900">Pour le Formateur</h4>
                                    <p class="text-slate-500 text-sm leading-relaxed">
                                        Éditeur de QCM intelligent, gestion fine de la pédagogie et supervision en direct des sessions d'examen.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-900 rounded-[4rem] p-16 text-center relative overflow-hidden group">
                        <div class="absolute inset-0 bg-primary-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        <div class="absolute top-0 right-0 w-80 h-80 bg-primary-500/20 rounded-full blur-[100px] -mr-20 -mt-20"></div>
                        
                        <h3 class="text-3xl font-heading font-black text-white mb-8 relative z-10">Rejoignez l'écosystème Solicode.</h3>
                        <p class="text-slate-400 mb-12 relative z-10 max-w-sm mx-auto leading-relaxed">
                            Accédez à votre espace sécurisé pour évaluer, progresser et exceller ensemble.
                        </p>
                        <a href="{{ route('login') }}"
                            class="inline-flex py-5 px-14 bg-primary-500 text-white font-bold rounded-2xl hover:bg-primary-600 transition-all uppercase tracking-[0.2em] text-[11px] shadow-2xl shadow-primary-500/30 active:scale-95 relative z-10">
                            Espace personnel
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-100 py-20">
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center">
            <div class="flex items-center gap-3 mb-10 group cursor-default">
                <div class="size-8 bg-slate-900 rounded-xl flex items-center justify-center transition-transform group-hover:rotate-12">
                    <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <span class="text-2xl font-heading font-black text-slate-900 tracking-tighter">SoliQuiz</span>
            </div>
            <div class="flex gap-10 mb-12">
                <a href="#" class="text-xs font-bold text-slate-400 hover:text-primary-500 transition-colors uppercase tracking-widest">Confidentialité</a>
                <a href="#" class="text-xs font-bold text-slate-400 hover:text-primary-500 transition-colors uppercase tracking-widest">Mentions Légales</a>
            </div>
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">© 2026 — Une réalisation Solicode</p>
        </div>
    </footer>

    <style>
        .blob {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, var(--color-primary-200), transparent 70%);
            filter: blur(120px);
            border-radius: 50%;
            z-index: -10;
            pointer-events: none;
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            50% { transform: translate(20px, 30px); }
            100% { transform: translate(0, 0); }
        }

        @keyframes pulse-soft {
            0%, 100% { opacity: 0.15; transform: scale(1); }
            50% { opacity: 0.2; transform: scale(1.05); }
        }

        .animate-bounce-slow { animation: bounce-slow 5s ease-in-out infinite; }
        .animate-float { animation: float 12s ease-in-out infinite; }
        .animate-pulse-soft { animation: pulse-soft 10s ease-in-out infinite; }
    </style>

    <script shadow>
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
</body>
</html>
