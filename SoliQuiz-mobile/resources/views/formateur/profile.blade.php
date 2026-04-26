@extends('components.layout.app')

@section('content')
<div x-data="formateurProfile" x-init="init()" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header App Formateur -->
    @include('components.header.formateur-header')

    <main class="flex-1 overflow-y-auto px-5 py-8 hide-scrollbar pb-24 text-slate-800 relative">
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
            <div class="flex flex-col items-center mb-10 text-slate-800">
                <div class="relative group">
                    <div class="absolute inset-0 bg-slate-900 rounded-full blur-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    <!-- Initials Avatar -->
                    <div class="relative inline-flex items-center justify-center size-28 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white font-bold text-3xl tracking-tight transition-transform group-hover:scale-105"
                         x-text="getInitials(profile.prenom, profile.nom)">
                        FM
                    </div>
                    <button class="absolute bottom-1 right-1 bg-slate-950 text-white p-2.5 rounded-2xl shadow-xl border-2 border-white active:scale-90 transition-all outline-none">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </button>
                </div>
                <h2 class="mt-6 text-2xl font-heading font-extrabold text-slate-900 tracking-tight leading-none" x-text="(profile.prenom || '') + ' ' + (profile.nom || '')">Chargement...</h2>
                <p class="text-[10px] font-black text-primary-600 mt-2 uppercase tracking-[0.25em]">Formateur Référent</p>
            </div>

            <!-- Info List -->
            <div class="space-y-5 px-1">
                <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)]">
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-4">
                            <div class="size-11 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Email Professionnel</p>
                                <p class="text-sm font-extrabold text-slate-900 truncate" x-text="profile.email || '...'"></p>
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
                
                <p class="text-center text-[9px] font-black text-slate-200 uppercase tracking-[0.4em] pt-12 leading-loose">
                    SoliQuiz Formateur v1.0<br/> <span class="text-slate-100">© 2026 Solicode Team</span>
                </p>
            </div>
        </div>
    </main>
    </main>

    <!-- Navigation Mobile Formateur -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formateurProfile', () => ({
            loading: false,
            get profile() {
                return Alpine.store('config').profile || {};
            },
            async init() {
                // Use cached profile if available, otherwise fetch
                if (!Alpine.store('config').profile) {
                    await this.fetchProfile();
                }
            },
            async fetchProfile() {
                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/profile`);
                    const data = await response.json();
                    Alpine.store('config').setProfile(data);
                } catch (e) {
                    console.error('Failed to load profile', e);
                } finally {
                    this.loading = false;
                }
            },
            getInitials(prenom, nom) {
                const p = (prenom || '').charAt(0).toUpperCase();
                const n = (nom || '').charAt(0).toUpperCase();
                return p + n || 'FM';
            }
        }));
    });
</script>
@endsection