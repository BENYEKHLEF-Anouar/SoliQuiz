@extends('layouts.app')

@section('title', 'Progression Étudiant - SoliQuiz')

@section('content')
<div class="space-y-8 fade-in" x-data="{ 
    unites: {{ Js::from($unites) }},
    search: '',
    selectedUaId: null,
    selectedUaTitle: '',
    loading: false,
    chartInstance: null,
    
    allHistory: {{ Js::from($history->map(fn($h) => [
        'id' => $h->id,
        'date_fin_formatted' => $h->date_fin ? $h->date_fin->format('d M Y à H:i') : '-',
        'qcm_titre' => $h->qcm->titre,
        'ua_id' => $h->qcm->unite_apprentissage_id,
        'ua_nom' => $h->qcm->uniteApprentissage->nom ?? 'Indépendant',
        'score' => $h->score_obtenu,
        'seuil' => $h->qcm->score_reussite,
        'statut' => $h->statut,
        'export_url' => route('formateur.resultats.tentative.export', $h->id)
    ])->values()->toArray()) }},

    page: 1,

    init() {
        const renderSafe = () => {
            if (typeof Chart !== 'undefined') {
                this.renderChart();
            } else {
                setTimeout(renderSafe, 50);
            }
        };
        renderSafe();
        this.$watch('search', () => { this.page = 1; this.updateFilters(); });
        this.$watch('selectedUaId', () => { this.page = 1; this.updateFilters(); });
    },
    
    updateFilters() {
        this.loading = true;
        setTimeout(() => {
            this.renderChart();
            this.loading = false;
        }, 300);
    },

    get filteredHistory() {
        return this.allHistory.filter(h => {
            const matchesSearch = !this.search || h.qcm_titre.toLowerCase().includes(this.search.toLowerCase());
            const matchesUa = !this.selectedUaId || h.ua_id == this.selectedUaId;
            return matchesSearch && matchesUa;
        });
    },

    get totalPages() {
        return Math.ceil(this.filteredHistory.length / 8) || 1;
    },

    get paginatedHistory() {
        const start = (this.page - 1) * 8;
        return this.filteredHistory.slice(start, start + 8);
    },

    get filteredChartData() {
        return [...this.filteredHistory].reverse().map(h => ({
            label: h.qcm_titre,
            score: h.score,
            date: h.date_fin_formatted
        }));
    },

    renderChart() {
        const ctx = document.getElementById('progressionChart');
        if (!ctx) return;

        if (this.chartInstance) {
            this.chartInstance.destroy();
        }

        const chartCtx = ctx.getContext('2d');
        const gradient = chartCtx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.00)');

        const data = this.filteredChartData;
        const labels = data.map(d => {
            const label = d.label;
            return label.length > 25 ? label.substring(0, 25) + '...' : label;
        });
        const scores = data.map(d => d.score);
        const dates = data.map(d => d.date);

        this.chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Note obtenue',
                    data: scores,
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        borderWidth: 1,
                        borderColor: '#334155',
                        titleFont: { size: 11, weight: 'bold', family: 'Plus Jakarta Sans, system-ui, sans-serif' },
                        bodyFont: { size: 11, family: 'Plus Jakarta Sans, system-ui, sans-serif' },
                        padding: 10,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ` Note: ${context.parsed.y}/20`;
                            },
                            afterLabel: function(context) {
                                return ` Date: ${dates[context.dataIndex]}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 20,
                        title: {
                            display: true,
                            text: 'Note obtenue / 20',
                            color: '#64748b',
                            font: { family: 'Plus Jakarta Sans, system-ui, sans-serif', size: 10, weight: 'bold' }
                        },
                        ticks: {
                            stepSize: 2,
                            color: '#64748b',
                            font: { family: 'Plus Jakarta Sans, system-ui, sans-serif', size: 10 }
                        },
                        grid: { color: '#f1f5f9', drawTicks: false }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Évaluations (QCM)',
                            color: '#64748b',
                            font: { family: 'Plus Jakarta Sans, system-ui, sans-serif', size: 10, weight: 'bold' }
                        },
                        ticks: {
                            color: '#64748b',
                            font: { family: 'Plus Jakarta Sans, system-ui, sans-serif', size: 9, weight: 'bold' },
                            maxRotation: 15,
                            autoSkip: true,
                            maxTicksLimit: 6
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }
}">

    {{-- Loading Overlay --}}
    <div x-show="loading" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-white/40 z-[200] flex items-center justify-center"
         style="display: none;">
         <div class="flex flex-col items-center gap-3 scale-90">
             <div class="size-10 border-4 border-slate-100 border-t-primary-500 rounded-full animate-spin shadow-sm"></div>
             <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">Filtrage en cours...</p>
         </div>
    </div>

    <!-- Header Section -->
    <div class="relative z-30 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-200">
            <div>
                <!-- Return Button styled like QCM edit page -->
                <div class="mb-5">
                    <a href="{{ route('formateur.resultats') }}" 
                       class="inline-flex items-center gap-2 text-slate-400 hover:text-primary-600 transition-colors group">
                        <div class="size-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:border-primary-200 group-hover:bg-primary-50 transition-all shadow-sm">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
                    </a>
                </div>

                <div class="flex items-center gap-4 mb-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Supervision</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Progression Étudiant</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Progression
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Suivi détaillé de l'apprenant.
                </p>
            </div>
        </div>
    </div>

    {{-- Student Identity Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 min-w-0 flex-1">
            <img class="size-14 rounded-2xl border-2 border-slate-100 shrink-0" 
                 src="https://ui-avatars.com/api/?name={{ urlencode($student->nom_complet ?? 'Student') }}&background=0f172a&color=ffffff&bold=true&size=128" 
                 alt="{{ $student->nom_complet }}">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h4 class="text-lg font-black text-slate-900 tracking-tight truncate">{{ $student->nom_complet }}</h4>
                    <span class="px-2.5 py-0.5 bg-primary-100 text-primary-700 rounded text-[10px] font-bold uppercase tracking-wider shrink-0">Apprenant</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        {{ $student->classe->nom ?? 'Hors cohorte' }}
                    </span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $student->email }}
                    </span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Réf : {{ $student->classe->formateur->nom_complet ?? 'Aucun' }}
                    </span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="flex items-center gap-1.5">
                        <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        {{ count($unites) }} UA
                    </span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="flex items-center gap-1.5 font-mono text-[11px]">
                        #{{ $student->id }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Export Bilan Dropdown -->
        <div x-data="{ open: false }" class="relative shrink-0 w-full sm:w-auto" @click.outside="open = false">
            <button @click="open = !open"
                    class="w-full sm:w-auto h-12 px-6 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-emerald-500 transition-all flex items-center justify-center gap-3 group">
                <svg class="size-4.5 text-emerald-100 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Exporter le bilan</span>
                <svg class="size-4 text-emerald-200 transition-transform shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-cloak x-transition
                 class="absolute right-0 mt-2 w-56 bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden py-1 z-30">
                <a href="{{ route('formateur.resultats.export', ['etudiant_id' => $student->id, 'format' => 'csv']) }}" 
                   class="w-full px-5 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-colors flex items-center gap-3">
                    <div class="size-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-mono">CSV</div>
                    Format CSV (.csv)
                </a>
                <a href="{{ route('formateur.resultats.export', ['etudiant_id' => $student->id, 'format' => 'excel']) }}" 
                   class="w-full px-5 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors flex items-center gap-3">
                    <div class="size-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center font-mono">XLS</div>
                    Format Excel (.xls)
                </a>
                <a href="{{ route('formateur.resultats.export', ['etudiant_id' => $student->id, 'format' => 'pdf']) }}" 
                   class="w-full px-5 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition-colors flex items-center gap-3">
                    <div class="size-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-mono">PDF</div>
                    Format PDF (.pdf)
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-{{ $averageScore >= 10 ? 'emerald' : 'rose' }}-100 rounded-lg flex items-center justify-center text-{{ $averageScore >= 10 ? 'emerald' : 'rose' }}-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2" /></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Moyenne</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black {{ $averageScore >= 10 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $averageScore }}/20</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">Moyenne sur {{ $totalAttempts }} évaluation{{ $totalAttempts > 1 ? 's' : '' }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">QCM Complétés</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $totalAttempts }}</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">Évaluations terminées et notées</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Taux Réussite</span>
                </div>
                <span class="px-2 py-0.5 bg-{{ $successRate >= 50 ? 'emerald' : 'rose' }}-100 text-{{ $successRate >= 50 ? 'emerald' : 'rose' }}-700 rounded text-[10px] font-bold">
                    {{ $successRate >= 50 ? '↑' : '↓' }} {{ $successRate }}%
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $successRate }}%</span>
            </div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-{{ $successRate >= 50 ? 'emerald' : 'rose' }}-500 h-1.5 rounded-full transition-all" style="width: {{ $successRate }}%"></div>
            </div>
        </div>
    </div>

    {{-- Filters Row --}}
    <div class="flex flex-col sm:flex-row gap-4 items-center">
        <!-- Search Input -->
        <div class="relative w-full sm:flex-1">
            <div class="relative group">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-300 pointer-events-none group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" placeholder="Rechercher un QCM..."
                       class="w-full h-14 bg-white border-2 border-slate-100 rounded-2xl pl-12 pr-14 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-primary-500 outline-none transition-all shadow-xs group-hover:shadow-sm">
                
                <button type="button" x-show="search && !loading" @click="search = ''" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors" style="display: none;">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- UA Dropdown -->
        <div x-data="{ open: false }" class="relative w-full sm:w-80" @click.outside="open = false">
            <button type="button" @click="open = !open"
                    class="w-full h-14 px-5 bg-white border-2 border-slate-100 rounded-2xl flex items-center justify-between text-[11px] font-black uppercase tracking-widest text-slate-600 hover:border-primary-300 transition-all shadow-xs">
                <span class="truncate mr-2" x-text="selectedUaTitle || 'Toutes les UA'"></span>
                <svg class="size-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-transition x-cloak
                 class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden py-1">
                <div class="max-h-64 overflow-y-auto custom-scrollbar">
                    <button type="button" @click="selectedUaId = null; selectedUaTitle = ''; open = false" 
                            class="w-full px-5 py-4 text-left text-[11px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 flex items-center justify-between transition-colors border-b border-slate-100">
                        <span>Toutes les UA</span>
                        <span x-show="selectedUaId === null" class="size-2 bg-primary-500 rounded-full"></span>
                    </button>
                    <template x-for="ua in unites" :key="ua.id">
                        <button type="button" @click="selectedUaId = ua.id; selectedUaTitle = ua.nom; open = false" 
                                class="w-full px-5 py-4 text-left text-[11px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 flex items-center justify-between transition-colors">
                            <span class="truncate" x-text="ua.nom"></span>
                            <span x-show="selectedUaId === ua.id" class="size-2 bg-primary-500 rounded-full"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Clear Filters Button -->
        <button x-show="search || selectedUaId" @click="search = ''; selectedUaId = null; selectedUaTitle = ''" 
                class="size-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center hover:bg-rose-100 transition-all shadow-xs shrink-0"
                x-transition>
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Chart Card (Taking full width since Info card is merged into student identity) --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between h-full">
        <div>
            <h3 class="text-base font-bold text-slate-900">Courbe de Progression</h3>
            <p class="text-xs text-slate-500 mt-0.5 mb-6">Évolution chronologique des scores obtenus</p>
        </div>
        <div class="relative w-full flex-1 min-h-[300px] flex items-center justify-center">
            @if($totalAttempts > 0)
                <canvas id="progressionChart"></canvas>
            @else
                <div class="text-center py-10">
                    <div class="size-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">Aucune donnée de progression</p>
                </div>
            @endif
        </div>
        @if($totalAttempts > 0)
        <div class="mt-4 pt-4 border-t border-slate-100">
            <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider">
                <span class="flex items-center gap-2 text-indigo-500">
                    <span class="size-2.5 rounded-full bg-indigo-500 block"></span>
                    Score obtenu / 20
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- Attempts Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Historique des évaluations</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Liste complète des tentatives et résultats</p>
                </div>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold" x-text="filteredHistory.length + ' résultat' + (filteredHistory.length > 1 ? 's' : '')"></span>
            </div>
        </div>
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/80">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Évaluation</th>
                        <th class="px-6 py-4">Unité d'Apprentissage</th>
                        <th class="px-6 py-4 text-right">Score</th>
                        <th class="px-6 py-4 text-center">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="attempt in paginatedHistory" :key="attempt.id">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs text-slate-400 font-medium whitespace-nowrap" x-text="attempt.date_fin_formatted"></td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-800" x-text="attempt.qcm_titre"></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-slate-500" x-text="attempt.ua_nom"></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black" :class="attempt.score >= attempt.seuil ? 'text-emerald-600' : 'text-rose-600'" x-text="attempt.score + '/20'"></span>
                                <p class="text-[10px] text-slate-400 mt-0.5" x-text="'Seuil: ' + attempt.seuil + '/20'"></p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold" 
                                          :class="attempt.statut === 'reussi' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">
                                        <span class="size-1.5 rounded-full" :class="attempt.statut === 'reussi' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                        <span x-text="attempt.statut === 'reussi' ? 'Réussi' : 'Échoué'"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div x-data="{ open: false }" class="inline-block relative" @click.outside="open = false">
                                    <button @click="open = !open"
                                        class="inline-flex size-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm"
                                        title="Exporter la tentative">
                                        <svg class="size-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </button>
                                    
                                    <!-- Beautiful Horizontal Popout Menu -->
                                    <div x-show="open" 
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-x-4 scale-95"
                                         x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                                         class="absolute right-full top-1/2 -translate-y-1/2 mr-3 w-48 bg-white border border-slate-100 rounded-xl shadow-xl z-50 p-1.5 flex items-center gap-1.5"
                                         style="display: none;">
                                        
                                        <button @click="window.location.href = attempt.export_url + '?format=pdf'" 
                                            class="flex-1 py-1.5 text-center text-[9px] font-black uppercase tracking-wider text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-lg transition-all border border-rose-100 hover:border-rose-500">
                                            PDF
                                        </button>
                                        <button @click="window.location.href = attempt.export_url + '?format=excel'" 
                                            class="flex-1 py-1.5 text-center text-[9px] font-black uppercase tracking-wider text-teal-600 bg-teal-50 hover:bg-teal-500 hover:text-white rounded-lg transition-all border border-teal-100 hover:border-teal-500">
                                            XLS
                                        </button>
                                        <button @click="window.location.href = attempt.export_url + '?format=csv'" 
                                            class="flex-1 py-1.5 text-center text-[9px] font-black uppercase tracking-wider text-emerald-600 bg-emerald-50 hover:bg-emerald-500 hover:text-white rounded-lg transition-all border border-emerald-100 hover:border-emerald-500">
                                            CSV
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredHistory.length === 0" style="display: none;">
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="size-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="text-sm text-slate-500 font-medium">Aucune tentative ne correspond aux filtres</p>
                            <p class="text-xs text-slate-400 mt-1">Modifiez vos critères de recherche</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Controls -->
        <div x-show="filteredHistory.length > 8" class="p-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/30">
            <span class="text-xs font-bold text-slate-500">
                Affichage de <span x-text="Math.min((page - 1) * 8 + 1, filteredHistory.length)"></span> à <span x-text="Math.min(page * 8, filteredHistory.length)"></span> sur <span x-text="filteredHistory.length"></span> tentatives
            </span>
            <div class="flex items-center gap-1.5">
                <button @click="page > 1 ? page-- : null" :disabled="page === 1"
                        class="size-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-all disabled:opacity-40 disabled:cursor-not-allowed active:scale-[0.98]">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="px-3 text-xs font-black text-slate-600">Page <span x-text="page"></span> sur <span x-text="totalPages"></span></span>
                <button @click="page < totalPages ? page++ : null" :disabled="page === totalPages"
                        class="size-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-all disabled:opacity-40 disabled:cursor-not-allowed active:scale-[0.98]">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
@endsection
