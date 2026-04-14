@extends('components.layout.app')

@section('content')
<div x-data="profile" x-init="init()" class="h-full flex flex-col relative overflow-hidden bg-slate-50">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-slate-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('student.dashboard') }}">
                    <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 active:scale-95 transition-transform">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                </a>
                <h1 class="text-xl font-heading font-extrabold text-slate-900 tracking-tight">Mon Profil</h1>
            </div>
            <div class="inline-flex items-center gap-x-2 py-1.5 px-3.5 rounded-xl bg-slate-50 text-slate-400 border border-slate-100">
                <span class="text-[9px] font-black uppercase tracking-[0.2em]">RÉGLAGES</span>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-5 py-8 hide-scrollbar pb-24 relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Chargement du profil..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Profile Card -->
            <div class="flex flex-col items-center mb-10">
                <div class="relative group">
                    <div class="absolute inset-0 bg-primary-500 rounded-full blur-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    <img class="relative inline-block size-28 rounded-full border-4 border-white shadow-xl object-cover transition-transform group-hover:scale-105" 
                        src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80" 
                        alt="Avatar">
                    <button class="absolute bottom-1 right-1 bg-slate-950 text-white p-2.5 rounded-2xl shadow-xl border-2 border-white active:scale-90 transition-all outline-none">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </button>
                </div>
                <h2 class="mt-6 text-2xl font-heading font-extrabold text-slate-900 tracking-tight leading-none" x-text="profile.nom + ' ' + (profile.prenom || '')">Chargement...</h2>
                <p class="text-[10px] font-black text-primary-600 mt-2 uppercase tracking-[0.2em]">
                    <span x-text="profile.role || 'Apprenant'"></span> <span class="mx-1 text-slate-200">/</span> <span x-text="profile.cohort || 'Cohorte'"></span>
                </p>
            </div>

            <!-- Info List -->
            <div class="space-y-5">
                <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)]">
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-4">
                            <div class="size-11 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Email</p>
                                <p class="text-sm font-extrabold text-slate-900" x-text="profile.email || '...'"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="size-11 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Mot de passe</p>
                                <p class="text-sm font-extrabold text-slate-900 italic tracking-widest">••••••••</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="{{ route('landing') }}"
                    class="w-full h-16 inline-flex justify-center items-center gap-x-2 text-[10px] font-black uppercase tracking-[0.25em] rounded-2xl border-2 border-slate-100 bg-white text-slate-900 hover:bg-slate-950 hover:text-white hover:border-slate-950 transition-all active:scale-[0.98]">
                    Déconnexion
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </a>
                
                <p class="text-center text-[9px] font-black text-slate-200 uppercase tracking-[0.4em] pt-10 leading-loose">
                    SoliQuiz Mobile v1.0<br/> <span class="text-slate-100">© 2026 Solicode</span>
                </p>
            </div>
        </div>
    </main>
    </main>

    <!-- Navigation -->
    @include('components.nav.student-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profile', () => ({
            profile: {},
            loading: false,
            async init() {
                await this.fetchProfile();
            },
            async fetchProfile() {
                this.loading = true;
                try {
                    const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/profile`);
                    this.profile = await response.json();
                } catch (e) {
                    console.error('Failed to load profile', e);
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endsection