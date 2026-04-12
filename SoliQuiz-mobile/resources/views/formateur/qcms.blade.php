@extends('components.layout.app')

@section('content')
<div x-data="formateurQcms()" x-init="init()" class="h-[100dvh] flex flex-col relative overflow-hidden font-sans">
    <!-- Header -->
    @include('components.header.formateur-header')

    <main class="flex-1 overflow-y-auto w-full px-4 py-6 pb-24 hide-scrollbar">
        <div class="flex flex-col gap-4 mb-5">
            <!-- Search Input -->
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                    <svg class="shrink-0 size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <input type="text" x-model="search" class="py-3 px-4 ps-11 block w-full border-slate-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Rechercher un QCM...">
            </div>
            <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Mes Créations (<span x-text="filteredQcms.length"></span>)</h2>
        </div>

        <div class="space-y-4">
            <template x-for="qcm in filteredQcms" :key="qcm.id">
                <div class="flex flex-col bg-white border border-slate-200 shadow-sm rounded-xl">
                    <div class="p-4 md:p-5">
                        <div class="flex justify-between items-start mb-2">
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium" :class="qcm.status === 'Actif' ? 'bg-primary-100 text-primary-800' : 'bg-slate-100 text-slate-800'" x-text="qcm.status"></span>

                            <div class="hs-dropdown relative inline-flex">
                                <button id="hs-dropdown-card" type="button" class="hs-dropdown-toggle py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-40 bg-white shadow-md rounded-lg p-2 mt-2 z-[60]" role="menu">
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-slate-800 hover:bg-slate-100" href="#">Modifier</a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-slate-800 hover:bg-slate-100" href="#">Dupliquer</a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-semantic-error hover:bg-slate-100" href="#">Archiver</a>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800" x-text="qcm.title"></h3>
                        <p class="mt-1 text-sm text-slate-500">Questions: <span x-text="qcm.questionsCount"></span> • Résultats: <span x-text="qcm.resultsCount"></span></p>
                        <div class="mt-4 flex gap-2">
                            <button type="button" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-slate-800 text-white hover:bg-slate-900">
                                Résultats (<span x-text="qcm.resultsCount"></span>)
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="flex justify-center py-10">
            <svg class="animate-spin size-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Empty state -->
        <div x-show="!loading && qcms.length === 0" class="text-center py-10 text-slate-400">
            Aucun QCM trouvé.
        </div>
    </main>

    <!-- FAB Button -->
    <button type="button" class="fixed bottom-24 right-5 w-14 h-14 bg-primary-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-primary-700 active:bg-primary-800 transition z-40">
        <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
    </button>

    <!-- Navigation -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); }
        .pt-safe-top { padding-top: env(safe-area-inset-top, 0px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
function formateurQcms() {
    return {
        qcms: [],
        loading: false,
        search: '',
        get filteredQcms() {
            if (!this.search) return this.qcms;
            const term = this.search.toLowerCase();
            return this.qcms.filter(q => q.title.toLowerCase().includes(term));
        },
        async init() {
            await this.fetchQcms();
        },
        async fetchQcms() {
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/qcms`);
                this.qcms = await response.json();
            } catch (e) {
                console.error('Failed to load QCMs', e);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection