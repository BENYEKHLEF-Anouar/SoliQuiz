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
                L'évaluation mobile qui <span class="text-primary-500">booste</span> ton apprentissage.
            </h1>

            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                Prépare tes examens, suis tes notes et connecte-toi avec ta cohorte Solicode en un clin d'œil.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center py-3 px-6 bg-primary-500 text-white font-semibold rounded-xl hover:bg-primary-600 transition-all shadow-lg shadow-primary-500/15 text-center text-xs uppercase tracking-wider group active:scale-[0.98]">
                    Se connecter maintenant
                    <svg class="inline-block ml-3 size-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="#features"
                    class="w-full sm:w-auto inline-flex items-center justify-center py-3 px-6 bg-white text-slate-600 border border-slate-200/80 font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all text-center text-xs uppercase tracking-wider shadow-sm active:scale-[0.98]">
                    Découvrir
                </a>
            </div>

            <div class="mt-12 relative">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=600"
                    class="rounded-3xl shadow-2xl border-4 border-white transform rotate-2" alt="Apprenants">
                <!-- <div
                    class="absolute -bottom-4 -left-4 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-3 animate-bounce-slow">
                    <div class="size-3 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-900 uppercase">SoliLMS Sync</span>
                </div> -->
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
                    <div class="size-6 bg-slate-900 rounded-lg flex items-center justify-center shadow-lg shadow-slate-900/10">
                        <svg class="text-white size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <span class="font-heading font-black text-sm uppercase tracking-widest text-slate-900">SoliQuiz</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] leading-loose">
                    © 2026 — Solicode & OFPPT<br>
                    <span class="text-slate-300">Expérience Mobile First — Minimalist Design</span>
                </p>
            </footer>
        </section>

    </main>

    <!-- SoliBot Concierge IA Chatbot -->
    <div x-data="chatbot()" x-init="tooltipOpen = true" class="fixed bottom-6 right-6 z-[999]">
        <!-- Floating Chat Tooltip (UX Enhancement) -->
        <div x-show="!open && tooltipOpen" 
             x-data="{ tooltipOpen: true }"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-x-4"
             class="absolute right-20 top-1/2 -translate-y-1/2 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl shadow-lg px-5 py-3.5 flex items-center gap-3 whitespace-nowrap z-40"
             x-cloak>
            <div class="flex flex-col text-left">
                <span class="text-[10px] font-black text-primary-500 uppercase tracking-wider">Assistant SoliBot</span>
                <span class="text-xs font-bold text-slate-700 mt-0.5">Une question ? Échangez avec notre IA !</span>
            </div>
            <button @click.stop="tooltipOpen = false" class="text-slate-300 hover:text-slate-500 transition-colors">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Floating Chat Button -->
        <button @click="open = !open; tooltipOpen = false" 
                class="size-16 rounded-full bg-primary-500 text-white shadow-2xl flex items-center justify-center hover:bg-primary-600 transition-all active:scale-95 group relative z-50">
            <!-- Pulsing Rings (Only when chatbot is closed) -->
            <span x-show="!open" class="absolute inset-0 rounded-full bg-primary-400 opacity-75 animate-ping -z-10"></span>
            
            <!-- Chat Icon -->
            <svg x-show="!open" class="size-7 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
            </svg>
            
            <!-- Close Icon -->
            <svg x-show="open" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Floating Chat Panel (Sleek Glassmorphic Design) -->
        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             class="fixed inset-0 sm:absolute sm:inset-auto sm:bottom-20 sm:right-0 w-full sm:w-[360px] h-full sm:h-[500px] bg-white sm:bg-white/95 sm:backdrop-blur-xl border-t sm:border border-slate-200/80 sm:rounded-[2rem] shadow-2xl flex flex-col overflow-hidden z-[1000]">
            
            <!-- Header Section -->
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between relative overflow-hidden shrink-0 pt-10 sm:pt-5">
                <!-- Background Blob -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-500/20 rounded-full blur-2xl -mr-10 -mt-10"></div>
                
                <div class="flex items-center gap-3 relative z-10">
                    <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 text-white font-black text-xs">
                        SB
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider leading-none">SoliBot</h4>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="size-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none">En ligne</span>
                        </div>
                    </div>
                </div>
                <button @click="open = false" class="text-slate-400 hover:text-white transition-colors relative z-10">
                    <svg class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Messages Container -->
            <div x-ref="messagesContainer" id="chat-messages-container" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/30">
                <template x-for="msg in history" :key="msg.content + Math.random()">
                    <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                        <div :class="msg.role === 'user' ? 'bg-primary-500 text-white rounded-t-2xl rounded-l-2xl shadow-md shadow-primary-500/10' : 'bg-white border border-slate-100 text-slate-800 rounded-t-2xl rounded-r-2xl shadow-xs'"
                             class="max-w-[85%] px-4 py-2.5 text-xs leading-relaxed font-medium">
                            <p class="whitespace-pre-line" x-html="msg.content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')"></p>
                        </div>
                    </div>
                </template>
                
                <!-- Typing Spinner -->
                <div x-show="loading" class="flex justify-start animate-pulse" x-cloak>
                    <div class="bg-white border border-slate-100 px-4 py-2.5 rounded-t-2xl rounded-r-2xl shadow-xs flex items-center gap-1">
                        <span class="size-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="size-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="size-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            </div>

            <!-- Suggestions & Footer Input -->
            <div class="p-4 pb-8 sm:pb-4 border-t border-slate-100 bg-white/50 shrink-0">
                <!-- Quick Suggestions -->
                <div class="flex flex-wrap gap-1.5 mb-3" x-show="history.length <= 2">
                    <template x-for="s in suggestions" :key="s">
                        <button type="button" @click="sendMessage(s)"
                                class="px-3 py-1.5 bg-slate-50 border border-slate-200/60 rounded-xl text-[9px] font-bold text-slate-600 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-all text-left">
                            <span x-text="s"></span>
                        </button>
                    </template>
                </div>

                <!-- Input Row -->
                <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                    <input type="text" x-model="message" placeholder="Posez votre question..."
                           class="flex-1 h-10 bg-white border border-slate-200 rounded-xl px-3 text-xs font-bold text-slate-800 placeholder:text-slate-300 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20 transition-all shadow-xs">
                    <button type="submit" :disabled="loading || !message.trim()"
                            class="size-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-slate-800 transition-colors active:scale-95 disabled:opacity-40 disabled:pointer-events-none shrink-0 shadow-lg">
                        <svg class="size-4 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

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