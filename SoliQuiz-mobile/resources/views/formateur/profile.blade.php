@extends('components.layout.app')

@section('content')
<div x-data="formateurProfile()" x-init="init()" class="h-[100dvh] flex flex-col relative overflow-hidden font-sans">
    <!-- Header App Formateur -->
    <header class="bg-slate-900 px-5 pt-safe-top pb-4 border-b border-white/10 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 group outline-none" href="#" aria-label="SoliQuiz Accueil">
                    <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-white tracking-tight">Soli<span class="text-primary-400">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Formateur</span>
                    </div>
                </a>
            </div>
            <h1 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Mon Profil</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-5 py-8 hide-scrollbar pb-24 text-slate-800">
        <!-- Profile Card -->
        <div class="flex flex-col items-center mb-8">
            <div class="relative">
                <img class="inline-block size-24 rounded-full border-4 border-white shadow-md" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Avatar">
                <button class="absolute bottom-0 right-0 bg-primary-600 text-white p-2 rounded-full shadow-lg border-2 border-white active:scale-90 transition-transform">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </button>
            </div>
            <h2 class="mt-4 text-xl font-heading font-bold text-slate-900 leading-none" x-text="profile.nom + ' ' + profile.prenom"></h2>
            <p class="text-sm text-primary-600 font-bold mt-1 uppercase tracking-widest" x-text="profile.role"></p>
        </div>

        <!-- Info List -->
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <div class="size-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Email Professionnel</p>
                            <p class="text-sm font-semibold text-slate-800" x-text="profile.email"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logout Button -->
            <a href="{{ route('landing') }}" class="w-full py-4 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-2xl border-2 border-semantic-error/20 bg-white text-semantic-error hover:bg-semantic-error hover:text-white transition-all shadow-sm">
                Déconnexion
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
            </a>
        </div>
    </main>

    <!-- Navigation Mobile Formateur -->
    <nav class="fixed bottom-0 w-full max-w-[430px] bg-slate-900 border-t border-white/10 z-50 rounded-b-[2.5rem]">
        <div class="flex justify-around items-center h-[72px] px-4 pb-safe text-white">
            <a href="{{ route('formateur.qcms') }}" class="flex flex-col items-center justify-center text-slate-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="9" />
                    <rect x="14" y="3" width="7" height="5" />
                    <rect x="14" y="12" width="7" height="9" />
                    <rect x="3" y="16" width="7" height="5" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">QCMs</span>
            </a>
            <a href="{{ route('formateur.class-notes') }}" class="flex flex-col items-center justify-center text-slate-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Classe</span>
            </a>
            <a href="{{ route('formateur.profile') }}" class="flex flex-col items-center justify-center text-primary-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Profil</span>
            </a>
        </div>
    </nav>

    <style>
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); }
        .pt-safe-top { padding-top: env(safe-area-inset-top, 0px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
function formateurProfile() {
    return {
        profile: {},
        loading: false,
        async init() {
            await this.fetchProfile();
        },
        async fetchProfile() {
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/profile`);
                this.profile = await response.json();
            } catch (e) {
                console.error('Failed to load profile', e);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection