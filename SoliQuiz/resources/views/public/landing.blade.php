<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SoliQuiz - L'évaluation interactive par Solicode</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-white text-slate-800 font-sans antialiased overflow-x-hidden relative" x-data="{ profileOpen: false }">

    <!-- Subtle Background Elements -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] size-[500px] bg-primary-500/5 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[40%] -right-[5%] size-[400px] bg-indigo-500/5 rounded-full blur-[100px]"></div>
    </div>

    <!-- Header / Nav -->
    <header class="fixed top-0 w-full bg-white/70 backdrop-blur-xl z-50 border-b border-slate-100">
        <nav class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="#" class="flex items-center gap-3 group">
                <div class="size-10 bg-slate-900 rounded-xl flex items-center justify-center transition-all group-hover:bg-primary-500">
                    <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <span class="text-2xl font-heading font-black tracking-tighter text-slate-900">Soli<span class="text-primary-500">Quiz</span></span>
            </a>

            <div class="flex items-center gap-4 md:gap-10">
                <a href="#features" class="hidden md:block text-[10px] font-black text-slate-500 hover:text-primary-500 transition-colors uppercase tracking-[0.2em] ">Fonctionnalités</a>
                <a href="#roles" class="hidden md:block text-[10px] font-black text-slate-500 hover:text-primary-500 transition-colors uppercase tracking-[0.2em] ">Écosystème</a>
                
                @auth
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" 
                            class="flex items-center gap-3 p-2 bg-slate-900 rounded-2xl group transition-all hover:bg-primary-500 shadow-xl shadow-slate-900/10">
                        <div class="size-8 bg-white/10 rounded-xl flex items-center justify-center text-white">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <span class="text-[10px] font-black text-white uppercase tracking-[0.2em]  pr-2">
                            {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute right-0 mt-4 w-56 bg-white rounded-3xl shadow-2xl border border-slate-100 p-3 overflow-hidden z-[60]" x-cloak>
                        
                        <div class="p-4 mb-2 border-b border-slate-50">
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-tight">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest  mt-1">{{ Auth::user()->type_profil }}</p>
                        </div>

                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-all text-slate-600 hover:text-primary-500 group">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            <span class="text-[9px] font-black uppercase tracking-widest ">Dashboard</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-rose-50 transition-all text-slate-400 hover:text-rose-500 group">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                <span class="text-[9px] font-black uppercase tracking-widest ">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ Route::has('login') ? route('login') : '#' }}" class="py-3 px-8 bg-slate-900 text-white text-[10px] font-black rounded-xl hover:bg-primary-500 transition-all uppercase tracking-[0.2em]  active:scale-95 shadow-xl shadow-slate-900/10">
                    Se Connecter
                </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="pt-20">
        <!-- Hero Section -->
        <section class="relative py-24 lg:py-40 reveal">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative z-10">
                    <!-- <div class="inline-flex items-center gap-3 bg-slate-50 border border-slate-100 px-4 py-2 rounded-full mb-8">
                        <span class="size-2 rounded-full bg-primary-500 animate-ping"></span>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ">Version 2.0 Now Live</span>
                    </div> -->
                    
                    <h1 class="text-5xl lg:text-6xl font-heading font-black text-slate-900 leading-[1.05] tracking-tight mb-10 uppercase">
                        L'évaluation <br/>
                        qui <span class="text-primary-500">propulse</span> <br/>
                        le savoir.
                    </h1>
                    
                    <p class="text-lg text-slate-500 mb-12 leading-relaxed max-w-lg  font-medium">
                        SoliQuiz transforme vos sessions d'évaluation en expériences immersives. Synchronisation en temps réel avec votre LMS pour un suivi académique sans compromis.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-5">
                        <a href="{{ Route::has('login') ? route('login') : '#' }}" class="py-5 px-10 bg-primary-500 text-white text-xs font-black rounded-2xl hover:bg-slate-900 transition-all shadow-xl shadow-primary-500/20 text-center uppercase tracking-widest  group">
                            Initialiser la Session
                            <svg class="inline-block size-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                        <a href="#features" class="py-5 px-10 bg-white text-slate-900 border border-slate-200 text-xs font-black rounded-2xl hover:bg-slate-50 transition-all text-center uppercase tracking-widest ">
                            Explorer
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-primary-500/5 rounded-full blur-[100px] scale-125 -z-10"></div>
                    <div class="relative bg-white p-4 rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=1200"
                            alt="Innovation" class="rounded-[2.5rem] grayscale-[0.2] hover:grayscale-0 transition-all duration-700">
                        
                        <!-- Floating Stats -->
                        <!-- <div class="absolute -bottom-10 -left-10 bg-white/80 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-white/20 hidden sm:block">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="size-2 rounded-full bg-emerald-500"></div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 ">Core Sync Active</span>
                            </div>
                            <p class="text-xl font-black text-slate-900">SoliLMS Integrated</p>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 bg-slate-50/50 reveal">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-24">
                    <h2 class="text-[10px] font-black text-primary-500 uppercase tracking-[0.4em] mb-6 ">Ingénierie de pointe</h2>
                    <p class="text-4xl font-heading font-black text-slate-900 uppercase tracking-tight">Des outils puissants pour une éducation moderne.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-10">
                    <!-- Feature 1 -->
                    <div class="group bg-white p-12 rounded-[2.5rem] border border-slate-100 transition-all hover:border-primary-500/20 hover:shadow-2xl hover:shadow-primary-500/5">
                        <div class="size-14 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary-500 group-hover:text-white transition-all">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black mb-4 uppercase tracking-tight">Live Feedback</h3>
                        <p class="text-slate-500 leading-relaxed text-sm  font-medium">Visualisez les réponses de vos apprenants en direct et réajustez votre pédagogie instantanément.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="group bg-white p-12 rounded-[2.5rem] border border-slate-100 transition-all hover:border-primary-500/20 hover:shadow-2xl hover:shadow-primary-500/5">
                        <div class="size-14 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black mb-4 uppercase tracking-tight">Native Sync</h3>
                        <p class="text-slate-500 leading-relaxed text-sm  font-medium">Fini la saisie manuelle. Vos résultats sont exportés automatiquement vers SoliLMS via API sécurisée.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group bg-white p-12 rounded-[2.5rem] border border-slate-100 transition-all hover:border-primary-500/20 hover:shadow-2xl hover:shadow-primary-500/5">
                        <div class="size-14 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-500 group-hover:text-white transition-all">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black mb-4 uppercase tracking-tight">Data Analytics</h3>
                        <p class="text-slate-500 leading-relaxed text-sm  font-medium">Identifiez les compétences à renforcer grâce à nos rapports de performance automatisés par cohorte.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ecosystem Section -->
        <section id="roles" class="py-32 reveal">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-24 items-center">
                    <div>
                        <h2 class="text-4xl font-heading font-black text-slate-900 mb-10 uppercase tracking-tight leading-none">Un écosystème, <br/> deux expériences.</h2>
                        <div class="space-y-12">
                            <div class="flex gap-8 group">
                                <div class="size-12 rounded-2xl bg-slate-50 text-slate-400 font-black flex items-center justify-center shrink-0 group-hover:bg-primary-500 group-hover:text-white transition-all ">01</div>
                                <div>
                                    <h4 class="font-black text-lg mb-2 uppercase tracking-tight">Pour l'Apprenant</h4>
                                    <p class="text-slate-500 text-sm leading-relaxed  font-medium">Tableau de bord personnel, suivi de progression en temps réel et podium interactif pour stimuler l'engagement.</p>
                                </div>
                            </div>
                            <div class="flex gap-8 group">
                                <div class="size-12 rounded-2xl bg-slate-50 text-slate-400 font-black flex items-center justify-center shrink-0 group-hover:bg-primary-500 group-hover:text-white transition-all ">02</div>
                                <div>
                                    <h4 class="font-black text-lg mb-2 uppercase tracking-tight">Pour le Formateur</h4>
                                    <p class="text-slate-500 text-sm leading-relaxed  font-medium">Studio de création de QCM, gestion granulaire des cohortes et outils de supervision en direct des sessions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-[#0A0F1C] rounded-[3rem] p-16 text-center relative overflow-hidden group">
                        <div class="absolute top-0 right-0 size-80 bg-primary-500/10 rounded-full blur-[100px] group-hover:bg-primary-500/20 transition-all"></div>
                        <h3 class="text-3xl font-heading font-black text-white mb-8 uppercase tracking-tight leading-none">Prêt à digitaliser <br/> vos évaluations ?</h3>
                        <p class="text-slate-400 mb-12  font-medium">Rejoignez les centres d'excellence qui utilisent SoliQuiz pour piloter leurs formations.</p>
                        <a href="{{ Route::has('login') ? route('login') : '#' }}" class="inline-flex py-5 px-14 bg-primary-500 text-white text-xs font-black rounded-2xl hover:bg-white hover:text-slate-900 transition-all uppercase tracking-[0.2em]  active:scale-95">
                            Accéder à mon espace
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-100 py-20">
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center text-center">
            <div class="flex items-center gap-3 mb-10">
                <div class="size-8 bg-slate-900 rounded-lg flex items-center justify-center">
                    <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <span class="text-xl font-heading font-black tracking-tighter text-slate-900">SoliQuiz</span>
            </div>
            
            <div class="flex gap-10 mb-10">
                <a href="#" class="text-[9px] font-black text-slate-400 hover:text-primary-500 transition-colors uppercase tracking-[0.3em] ">Confidentialité</a>
                <a href="#" class="text-[9px] font-black text-slate-400 hover:text-primary-500 transition-colors uppercase tracking-[0.3em] ">Conditions</a>
                <a href="#" class="text-[9px] font-black text-slate-400 hover:text-primary-500 transition-colors uppercase tracking-[0.3em] ">Support</a>
            </div>

            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] ">© 2026 — Une réalisation Solicode Ecosystem</p>
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

    <style>
        .reveal { opacity: 0; transform: translateY(30px); transition: all 1s cubic-bezier(0.4, 0, 0.2, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
