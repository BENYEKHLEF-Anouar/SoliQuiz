@extends('layouts.app')

@section('content')
<div x-data="formateurQcms()" x-init="init()">
    <!-- Header -->
    <header class="bg-white px-5 pt-safe-top pb-4 border-b border-slate-200 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex items-center justify-between mt-2">
            <a href="{{ route('formateur.profile') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1 text-center">
                <span class="text-sm font-bold text-slate-800">Mes QCMs</span>
            </div>
            <div class="w-6"></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 px-5 py-6">
        <!-- Search bar -->
        <div class="relative mb-6">
            <input type="text" placeholder="Rechercher un QCM..."
                class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                x-model="search">
            <svg class="absolute left-3 top-3.5 size-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <!-- QCM list -->
        <div class="space-y-4">
            <template x-for="qcm in filteredQcms" :key="qcm.id">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md"
                            :class="qcm.status === 'Actif' ? 'bg-success-100 text-success-700' : 'bg-slate-100 text-slate-500'"
                            x-text="qcm.status"></span>
                        <div class="hs-dropdown relative inline-flex">
                            <button class="hs-dropdown-toggle w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100">
                                <svg class="size-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100 z-50"
                                role="menu">
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-800 hover:bg-slate-50" href="#">Modifier</a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-800 hover:bg-slate-50" href="#">Dupliquer</a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-danger-500 hover:bg-danger-50" href="#">Archiver</a>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 leading-snug font-heading mb-2" x-text="qcm.title"></h3>
                    <div class="flex items-center text-xs text-slate-400 space-x-4">
                        <span><span class="font-bold text-slate-600" x-text="qcm.questionsCount"></span> questions</span>
                        <span><span class="font-bold text-slate-600" x-text="qcm.resultsCount"></span> résultats</span>
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

    <!-- FAB button -->
    <button class="fixed bottom-24 right-6 w-14 h-14 bg-primary-500 text-white rounded-full shadow-lg shadow-primary-500/30 flex items-center justify-center active:scale-95 transition-transform"
        @click="showCreateModal = true">
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
    </button>
</div>

<script>
function formateurQcms() {
    return {
        qcms: [],
        loading: false,
        search: '',
        showCreateModal: false,
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