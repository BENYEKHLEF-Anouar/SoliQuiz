@extends('components.layout.app')

@section('content')
<div x-data="qcmResults" x-init="init()" x-data-qcm-id="{{ $qcmId }}" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    <header class="bg-slate-950 px-5 pt-safe-top pb-4 border-b border-white/5 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="flex items-center gap-4 mt-2">
            <a href="{{ route('formateur.qcms') }}" class="size-9 bg-white/10 rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition-all active:scale-90">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-heading font-bold text-white tracking-tight leading-none" x-text="qcmTitle || 'Chargement...'"></h1>
                <p class="text-[10px] font-black text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">Résultats de passage</p>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full px-5 py-8 pb-32 hide-scrollbar">
        <!-- Summary Stats (Optional but premium feel) -->
        <div x-show="!loading && results.length > 0" class="mb-8 grid grid-cols-2 gap-4 animate-in fade-in slide-in-from-bottom-4 duration-500">
             <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] group transition-all hover:border-primary-100">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Passations</p>
                <div class="flex items-baseline gap-1">
                    <p class="text-3xl font-heading font-extrabold text-slate-950 leading-none" x-text="results.length"></p>
                    <p class="text-[10px] font-bold text-slate-300 uppercase italic">Étudiants</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] group transition-all hover:border-primary-100">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Moyenne</p>
                <div class="flex items-baseline gap-1">
                    <p class="text-3xl font-heading font-extrabold text-primary-500 leading-none" x-text="calculateAverage()"></p>
                    <p class="text-[10px] font-bold text-primary-200 uppercase italic">%</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col mb-6">
            <h2 class="text-lg font-heading font-extrabold text-slate-900 tracking-tight">RÉSULTATS DÉTAILLÉS</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Suivi individuel par étudiant</p>
        </div>

        <div class="grid gap-4">
            <template x-for="res in results" :key="res.id">
                <div class="flex items-center bg-white border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] rounded-[2rem] p-5 transition-all hover:border-slate-200 hover:shadow-md active:scale-[0.98] group">
                    <div class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 font-black text-xs shrink-0 transition-colors group-hover:bg-primary-50 group-hover:text-primary-500" x-text="res.studentName.split(' ').map(n => n[0]).join('')"></div>
                    <div class="ms-5 flex-1">
                        <h4 class="text-base font-extrabold text-slate-950 tracking-tight leading-tight" x-text="res.studentName"></h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-1" x-text="res.date"></p>
                    </div>
                    <div class="text-right">
                        <div class="px-3 py-1.5 rounded-xl text-xs font-black tracking-tight border shadow-sm" 
                             :class="res.score >= (res.totalQuestions / 2) ? 'bg-primary-50 text-primary-600 border-primary-100' : 'bg-semantic-error/10 text-semantic-error border-semantic-error/10'">
                            <span x-text="res.score"></span> <span class="text-[9px] opacity-40">/</span> <span x-text="res.totalQuestions"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="flex flex-col items-center justify-center py-20 gap-6">
            <div class="size-16 border-4 border-slate-100 border-t-primary-500 rounded-full animate-spin"></div>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 italic">Extraction des données...</p>
        </div>

        <!-- Empty state -->
        <div x-show="!loading && results.length === 0" class="text-center py-24 animate-in fade-in duration-700">
            <div class="size-20 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-inner">
                <svg class="size-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M10 13l2 2 4-4"/>
                </svg>
            </div>
            <p class="text-base font-extrabold text-slate-900 tracking-tight">Aucun passager</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">En attente des premières réponses</p>
        </div>
    </main>

    <!-- Navigation -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qcmResults', () => ({
            qcmId: null,
            qcmTitle: '',
            results: [],
            loading: false,
            async init() {
                const el = document.querySelector('[x-data-qcm-id]');
                this.qcmId = el?.getAttribute('x-data-qcm-id') || null;
                if (this.qcmId) {
                    await this.fetchResults();
                }
            },
            async fetchResults() {
                this.loading = true;
                try {
                    const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/qcms/${this.qcmId}/results`);
                    const data = await response.json();
                    this.qcmTitle = data.title;
                    this.results = data.results;
                } catch (e) {
                    console.error('Failed to load results', e);
                } finally {
                    this.loading = false;
                }
            },
            calculateAverage() {
                if (this.results.length === 0) return 0;
                const scores = this.results.map(r => (r.score / r.totalQuestions) * 100);
                const sum = scores.reduce((a, b) => a + b, 0);
                return Math.round(sum / this.results.length);
            }
        }));
    });
</script>
@endsection
