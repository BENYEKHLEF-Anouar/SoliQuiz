@extends('components.layout.app')

@section('content')
    <div class="h-[44px] w-full shrink-0"></div> <!-- iOS Safe Area -->

    <!-- Header -->
    <header
        class="sticky top-0 w-full bg-white/80 backdrop-blur-md z-50 border-b border-slate-100 px-6 py-4 flex justify-between items-center transition-all">
        <div class="flex items-center gap-2 group">
            <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/10">
                <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <path d="m9 15 2 2 4-4" />
                </svg>
            </div>
            <span class="text-xl font-heading font-black tracking-tight text-slate-900 leading-none">Soli<span
                    class="text-primary-500">Quiz</span></span>
        </div>
        <a href="{{ route('login') }}" class="p-2 text-slate-400 hover:text-primary-500 transition-colors">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
        </a>
    </header>

    <main class="flex-1 overflow-y-auto scroll-smooth">

        <!-- Hero Section -->
        <section class="px-6 py-12 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 size-40 bg-primary-500/5 rounded-full blur-3xl"></div>

            <span
                class="inline-flex items-center bg-primary-50 text-primary-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                Première version 1.0
            </span>

            <h1 class="text-4xl font-heading font-bold text-slate-900 leading-[1.1] mb-6">
                L'évaluation mobile qui <span class="text-primary-500 italic">booste</span> ton apprentissage.
            </h1>

            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                Prépare tes examens, suis tes notes et connecte-toi avec ta cohorte Solicode en un clin d'œil.
            </p>

            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}"
                    class="w-full py-4 bg-primary-500 text-white font-black rounded-2xl text-center uppercase tracking-widest text-xs shadow-xl shadow-primary-500/30 active:scale-[0.98] transition-all">
                    Se Connecter maintenant
                </a>
                <a href="#features"
                    class="w-full py-4 bg-white border border-slate-200 text-slate-700 font-bold rounded-2xl text-center uppercase tracking-widest text-xs active:scale-[0.98] transition-all">
                    Découvrir plus
                </a>
            </div>

            <div class="mt-12 relative">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=600"
                    class="rounded-3xl shadow-2xl border-4 border-white transform rotate-2" alt="Apprenants">
                <div
                    class="absolute -bottom-4 -left-4 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-3 animate-bounce-slow">
                    <div class="size-3 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-900 uppercase">SoliLMS Sync</span>
                </div>
            </div>
        </section>

        <!-- Dynamic Stats Section -->
        <section id="features" class="px-6 py-12 bg-slate-50">
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
                    <div
                        class="size-12 bg-primary-50 text-primary-500 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Passation en Direct</h3>
                        <p class="text-slate-500 text-xs">Vitesse de réponse optimisée pour mobile.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
                    <div
                        class="size-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Notes instantanées</h3>
                        <p class="text-slate-500 text-xs">Reçois tes résultats immédiatement après validation.</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
                    <div class="size-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path
                                d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Suivi de cohorte</h3>
                        <p class="text-slate-500 text-xs">Compare tes performances et progresse avec tes collègues.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA & Footer -->
        <section class="px-6 py-12 text-center">
            <h2 class="text-2xl font-heading font-bold text-slate-900 mb-4">Prêt à briller ?</h2>
            <p class="text-slate-500 text-sm mb-8">Rejoins plus de 500 apprenants Solicode déjà connectés.</p>
            <a href="{{ route('login') }}"
                class="inline-flex py-3 px-8 bg-slate-900 text-white font-black rounded-xl text-xs uppercase tracking-widest active:scale-95 transition-all">
                Accéder à mon espace
            </a>

            <footer class="mt-20 pt-10 border-t border-slate-100 pb-12">
                <div class="flex items-center justify-center gap-2 opacity-50 mb-4 transition-opacity hover:opacity-100">
                    <div class="size-6 bg-slate-900 rounded-lg shadow-lg shadow-slate-900/10"></div>
                    <span class="font-heading font-black text-sm uppercase tracking-widest text-slate-900">SoliQuiz</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] leading-loose">
                    © 2026 — Solicode & OFPPT<br>
                    <span class="text-slate-300">Expérience Mobile First — Minimalist Design</span>
                </p>
            </footer>
        </section>

    </main>

    <style>
        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(-1deg);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 4s ease-in-out infinite;
        }

        /* Hide scrollbar but keep functionality */
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
    </style>
@endsection