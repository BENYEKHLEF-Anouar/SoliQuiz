@extends('components.layout.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div x-data="formateurResults" x-init="init()" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    @include('components.header.formateur-header')

    <main class="flex-1 overflow-y-auto w-full px-5 py-8 pb-32 hide-scrollbar relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Supervision des cohortes..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <!-- Page Title -->
            <div class="mb-6">
                <h2 class="text-3xl font-heading font-extrabold text-slate-900 tracking-tight">SUPERVISION</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Résultats de vos apprenants</p>
            </div>

            <!-- Stats Overview Bar -->
            <div class="grid grid-cols-3 gap-3 bg-white p-4 rounded-3xl border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)] mb-6">
                <div class="text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Moyenne</p>
                    <p class="text-lg font-black text-slate-900" x-text="statsGlobale.moyenne + '/20'"></p>
                </div>
                <div class="border-l border-slate-100 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Réussite</p>
                    <p class="text-lg font-black text-emerald-500" x-text="statsGlobale.reussite + '%'"></p>
                </div>
                <div class="border-l border-slate-100 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Passages</p>
                    <p class="text-lg font-black text-primary-500" x-text="statsGlobale.passages"></p>
                </div>
            </div>

            <!-- Performances Globales Chart -->
            <div x-show="classes.length > 0" class="bg-white p-5 rounded-3xl border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)] mb-6">
                <div class="mb-4">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Performances Globales</h3>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Moyenne générale et réussite par cohorte</p>
                </div>
                
                <div class="relative w-full h-44 flex items-center justify-center">
                    <canvas id="performanceChart"></canvas>
                </div>

                <div class="mt-4 flex items-center justify-center gap-6 border-t border-slate-50 pt-3 text-[8px] font-black uppercase tracking-wider">
                    <span class="flex items-center gap-1.5 text-indigo-500">
                        <span class="size-2 rounded-full bg-indigo-500 block"></span>
                        Moyenne (/20)
                    </span>
                    <span class="flex items-center gap-1.5 text-emerald-500">
                        <span class="size-2 rounded-full bg-emerald-400 block"></span>
                        Réussite (%)
                    </span>
                </div>
            </div>

            <!-- Search and Filter Controls -->
            <div class="space-y-3 mb-6">
                <!-- Search Input -->
                <div class="relative group" x-data="{ open: false }" @click.outside="open = false">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" x-model="search" @focus="open = true" @input="filterResults"
                           placeholder="Rechercher un apprenant..."
                           class="w-full h-12 bg-white border border-slate-100 rounded-2xl pl-11 pr-10 text-xs font-bold text-slate-950 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all shadow-sm">
                    <button x-show="search" @click="search = ''; filterResults()" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <!-- Suggestions Dropdown -->
                    <div x-show="open && studentsList.filter(n => !search || n.toLowerCase().includes(search.toLowerCase())).length > 0"
                         style="display: none;"
                         class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-[70] p-2 max-h-48 overflow-y-auto">
                        <template x-for="name in studentsList.filter(n => !search || n.toLowerCase().includes(search.toLowerCase()))" :key="name">
                            <button @click="search = name; filterResults(); open = false"
                                    class="w-full px-4 py-2.5 text-left text-[10px] font-black uppercase text-slate-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg truncate flex items-center justify-between">
                                <span x-text="name"></span>
                                <span x-show="search === name" class="size-1.5 bg-primary-500 rounded-full"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Filters Row -->
                <div class="flex gap-2">
                    <!-- QCM Filter Dropdown -->
                    <div x-data="{ open: false }" class="relative flex-1" @click.outside="open = false">
                        <button @click="open = !open"
                                class="w-full h-11 px-4 bg-white border border-slate-100 rounded-2xl flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-600 shadow-sm outline-none">
                            <span class="truncate pr-1" x-text="qcmLabel">Tous les QCMs</span>
                            <svg class="size-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-2 max-h-48 overflow-y-auto">
                            <button @click="selectQcm('', 'Tous les QCMs'); open = false" class="w-full px-4 py-2 text-left text-[10px] font-black uppercase text-slate-400 hover:bg-slate-50 rounded-lg">Tous les QCMs</button>
                            <template x-for="q in qcms" :key="q.id">
                                <button @click="selectQcm(q.id, q.title); open = false" class="w-full px-4 py-2 text-left text-[10px] font-black uppercase text-slate-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg truncate" x-text="q.title"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Clear Filters Button -->
                    <button x-show="search || qcmId" @click="clearFilters()" style="display: none;"
                            class="size-11 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center hover:bg-rose-100 active:scale-95 transition-all shadow-sm shrink-0">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- QCM & Attempts List -->
            <div class="space-y-6">
                <template x-for="qcm in filteredQcms" :key="qcm.id">
                    <div class="bg-white border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)] rounded-[2rem] p-5 relative overflow-hidden group">
                        
                        <!-- QCM Info Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <h3 class="text-base font-heading font-extrabold text-slate-900 leading-tight" x-text="qcm.title"></h3>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1" x-text="'Seuil de réussite: ' + qcm.score_reussite + '/20'"></span>
                            </div>
                        </div>

                        <!-- Card Stats Summary for this QCM -->
                        <div class="grid grid-cols-3 gap-2 bg-slate-50/50 p-3 rounded-2xl border border-slate-100/50 my-4 text-center">
                            <div>
                                <p class="text-[7px] font-black text-slate-400 uppercase tracking-wider">Moyenne</p>
                                <p class="text-xs font-black text-slate-900" x-text="getQcmStats(qcm).avg + '/20'"></p>
                            </div>
                            <div class="border-l border-slate-100">
                                <p class="text-[7px] font-black text-slate-400 uppercase tracking-wider">Réussite</p>
                                <p class="text-xs font-black text-emerald-500" x-text="getQcmStats(qcm).rate + '%'"></p>
                            </div>
                            <div class="border-l border-slate-100">
                                <p class="text-[7px] font-black text-slate-400 uppercase tracking-wider">Passages</p>
                                <p class="text-xs font-black text-primary-500" x-text="getQcmStats(qcm).count"></p>
                            </div>
                        </div>

                        <!-- Tentatives list for this QCM -->
                        <div class="space-y-3 mt-4">
                            <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Tentatives</h4>
                            
                            <template x-for="t in getFilteredTentatives(qcm)" :key="t.id">
                                <div @click="openStudentChart(t.etudiant_nom)" class="p-4 bg-slate-50/30 border border-slate-100 rounded-2xl flex items-center justify-between cursor-pointer active:scale-[0.98] hover:border-primary-200 hover:bg-slate-50/50 transition-all">
                                    <div class="flex-1 min-w-0 pr-3">
                                        <p class="text-xs font-bold text-slate-800 truncate flex items-center gap-1.5">
                                            <span x-text="t.etudiant_nom"></span>
                                            <svg class="size-3 text-primary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        </p>
                                        <div class="flex items-center gap-1.5 mt-1 text-[9px] font-bold text-slate-400 uppercase">
                                            <span class="truncate" x-text="t.etudiant_classe"></span>
                                            <span class="size-1 bg-slate-200 rounded-full"></span>
                                            <span x-text="t.date"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end shrink-0 gap-1.5">
                                        <!-- Score -->
                                        <div class="text-xs font-black" :class="t.score >= qcm.score_reussite ? 'text-slate-900' : 'text-slate-400'">
                                            <span x-text="t.score !== null ? t.score : '-'"></span><span class="text-[9px] text-slate-400">/20</span>
                                        </div>

                                        <!-- Badge Status -->
                                        <template x-if="t.statut === 'reussi'">
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[8px] font-black uppercase tracking-wider">Validé</span>
                                        </template>
                                        <template x-if="t.statut === 'echoue'">
                                            <span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg text-[8px] font-black uppercase tracking-wider">Échec</span>
                                        </template>
                                        <template x-if="t.statut === 'abandonne'">
                                            <span class="px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-lg text-[8px] font-black uppercase tracking-wider">Abandon</span>
                                        </template>
                                        <template x-if="!t.statut || t.statut === 'en_cours'">
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 border border-slate-200 rounded-lg text-[8px] font-black uppercase tracking-wider">En cours</span>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <div x-show="getFilteredTentatives(qcm).length === 0" class="text-center py-4 bg-slate-50/20 border border-dashed border-slate-100 rounded-2xl">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Aucune tentative correspondante</p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredQcms.length === 0" class="text-center py-16 px-4">
                    <div class="size-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-300 mb-4">
                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" /></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 mb-1">Aucun résultat trouvé</h3>
                    <p class="text-xs text-slate-400">Essayez de modifier vos filtres ou termes de recherche.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Animated Bottom Sheet for Chart.js -->
    <div x-show="showChartSheet" 
         class="fixed inset-0 z-[80] flex items-end justify-center bg-slate-950/40 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showChartSheet = false"
         style="display: none;"
         x-cloak>
        
        <div class="bg-white w-full max-w-[430px] rounded-t-[2.5rem] border-t border-slate-100 p-6 flex flex-col max-h-[85vh] shadow-[0_-10px_40px_rgba(0,0,0,0.08)]"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             @click.stop>
            
            <!-- Handle -->
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-5 shrink-0" @click="showChartSheet = false"></div>
            
            <!-- Sheet Header -->
            <div class="flex justify-between items-start mb-6 shrink-0">
                <div>
                    <h3 class="text-lg font-heading font-extrabold text-slate-900 leading-tight" x-text="'Progression de ' + selectedStudentNom"></h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Évolution des notes sur tous les QCMs</p>
                </div>
                <button @click="showChartSheet = false" class="size-8 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Chart Canvas Container -->
            <div class="relative w-full h-48 bg-slate-50/50 rounded-3xl border border-slate-100/50 p-3 mb-6 flex-shrink-0">
                <canvas id="studentProgressChart"></canvas>
            </div>

            <!-- Attempt Details List inside Bottom Sheet -->
            <div class="flex-1 overflow-y-auto space-y-2 pr-1 hide-scrollbar">
                <template x-for="attempt in studentAttempts" :key="attempt.id">
                    <div class="p-4 bg-slate-50/30 border border-slate-100/50 rounded-2xl flex items-center justify-between">
                        <div class="min-w-0 pr-3">
                            <h4 class="text-xs font-bold text-slate-800 truncate" x-text="attempt.qcm_titre"></h4>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5" x-text="attempt.date"></p>
                        </div>
                        <div class="flex items-center gap-2.5 shrink-0">
                            <span class="text-xs font-black text-slate-900 font-mono" x-text="attempt.score + '/20'"></span>
                            
                            <template x-if="attempt.status === 'reussi'">
                                <span class="size-2 bg-emerald-500 rounded-full"></span>
                            </template>
                            <template x-if="attempt.status === 'echoue'">
                                <span class="size-2 bg-rose-500 rounded-full"></span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formateurResults', () => ({
            qcms: [],
            filteredQcms: [],
            classes: [],
            loading: true,

            search: '',
            qcmId: '',
            qcmLabel: 'Tous les QCMs',

            statsGlobale: {
                moyenne: 0,
                reussite: 0,
                passages: 0
            },

            selectedStudentNom: '',
            studentAttempts: [],
            chartInstance: null,
            globalChartInstance: null,
            showChartSheet: false,

            renderGlobalChart() {
                this.$nextTick(() => {
                    const ctxGlobal = document.getElementById('performanceChart');
                    if (!ctxGlobal || this.classes.length === 0) return;

                    if (this.globalChartInstance) {
                        this.globalChartInstance.destroy();
                    }

                    const classNames = this.classes.map(c => c.nom);
                    const averages = this.classes.map(c => c.moyenne);
                    const successRates = this.classes.map(c => c.taux_reussite);

                    this.globalChartInstance = new Chart(ctxGlobal, {
                        type: 'bar',
                        data: {
                            labels: classNames,
                            datasets: [
                                {
                                    label: 'Moyenne (/20)',
                                    data: averages,
                                    backgroundColor: 'rgba(79, 70, 229, 0.85)',
                                    borderColor: '#4f46e5',
                                    borderWidth: 1.5,
                                    borderRadius: 6,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Taux de réussite (%)',
                                    data: successRates,
                                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                                    borderColor: '#10b981',
                                    borderWidth: 1.5,
                                    borderRadius: 6,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    titleColor: '#fff',
                                    bodyColor: '#cbd5e1',
                                    padding: 8,
                                    bodyFont: { size: 9 },
                                    titleFont: { size: 10, weight: 'bold' }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        font: { size: 9, weight: 'bold' },
                                        color: '#64748b'
                                    }
                                },
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    max: 20,
                                    min: 0,
                                    grid: { color: '#f1f5f9' },
                                    ticks: {
                                        stepSize: 5,
                                        font: { size: 8 },
                                        color: '#64748b'
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    max: 100,
                                    min: 0,
                                    grid: { drawOnChartArea: false },
                                    ticks: {
                                        stepSize: 25,
                                        font: { size: 8 },
                                        color: '#64748b'
                                    }
                                }
                            }
                        }
                    });
                });
            },

            openStudentChart(studentNom) {
                this.selectedStudentNom = studentNom;
                
                const rawAttempts = [];
                this.qcms.forEach(qcm => {
                    qcm.tentatives.forEach(t => {
                        if (t.etudiant_nom === studentNom && t.score !== null) {
                            rawAttempts.push({
                                id: t.id,
                                qcm_titre: qcm.title,
                                score: parseFloat(t.score),
                                date: t.date,
                                status: t.statut
                            });
                        }
                    });
                });

                this.studentAttempts = rawAttempts.sort((a, b) => a.id - b.id);
                this.showChartSheet = true;

                this.$nextTick(() => {
                    const ctx = document.getElementById('studentProgressChart');
                    if (!ctx) return;

                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    const labels = this.studentAttempts.map(a => a.qcm_titre);
                    const data = this.studentAttempts.map(a => a.score);

                    this.chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Note obtenue (/20)',
                                data: data,
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.05)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.35,
                                pointBackgroundColor: '#4f46e5',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    padding: 10,
                                    titleFont: { size: 11, weight: 'bold' },
                                    bodyFont: { size: 11 },
                                    callbacks: {
                                        label: function(context) {
                                            return ` Note : ${context.parsed.y}/20`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    min: 0,
                                    max: 20,
                                    ticks: {
                                        stepSize: 5,
                                        font: { size: 9, weight: 'bold' },
                                        color: '#94a3b8'
                                    },
                                    grid: {
                                        color: '#f1f5f9'
                                    }
                                },
                                x: {
                                    ticks: {
                                        display: false
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                });
            },

            async init() {
                this.$watch('loading', (isLoading) => {
                    if (!isLoading) {
                        this.renderGlobalChart();
                    }
                });

                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/results`);
                    const data = await response.json();

                    this.qcms = data.qcms || [];
                    this.classes = data.classes || [];
                    
                    this.calculateGlobalStats();
                    this.filterResults();
                } catch (e) {
                    console.error('Failed to fetch formateur results', e);
                } finally {
                    setTimeout(() => { this.loading = false; }, 400);
                }
            },

            get studentsList() {
                const names = new Set();
                this.qcms.forEach(qcm => {
                    qcm.tentatives.forEach(t => {
                        if (t.etudiant_nom) {
                            names.add(t.etudiant_nom);
                        }
                    });
                });
                return [...names].sort();
            },

            calculateGlobalStats() {
                let allScores = [];
                let passages = 0;
                let reussis = 0;

                this.qcms.forEach(qcm => {
                    qcm.tentatives.forEach(t => {
                        if (t.score !== null) {
                            allScores.push(parseFloat(t.score));
                            passages++;
                            if (t.statut === 'reussi') {
                                reussis++;
                            }
                        }
                    });
                });

                const count = allScores.length;
                const sum = allScores.reduce((a, b) => a + b, 0);
                const avg = count > 0 ? (sum / count).toFixed(1) : '0';
                const rate = count > 0 ? Math.round((reussis / count) * 100) : 0;

                this.statsGlobale = {
                    moyenne: avg,
                    reussite: rate,
                    passages: passages
                };
            },

            selectQcm(id, title) {
                this.qcmId = id;
                this.qcmLabel = title;
                this.filterResults();
            },

            clearFilters() {
                this.search = '';
                this.qcmId = '';
                this.qcmLabel = 'Tous les QCMs';
                this.filterResults();
            },

            filterResults() {
                let filtered = [...this.qcms];

                // Filter QCM list if a specific QCM is selected
                if (this.qcmId) {
                    filtered = filtered.filter(q => q.id == this.qcmId);
                }

                this.filteredQcms = filtered;
            },

            getFilteredTentatives(qcm) {
                return qcm.tentatives.filter(t => {
                    return !this.search.trim() || t.etudiant_nom.toLowerCase().includes(this.search.toLowerCase());
                });
            },

            getQcmStats(qcm) {
                const tentatives = this.getFilteredTentatives(qcm);
                const scores = tentatives.map(t => parseFloat(t.score)).filter(s => !isNaN(s) && s !== null);
                const count = scores.length;
                const sum = scores.reduce((a, b) => a + b, 0);
                const avg = count > 0 ? (sum / count).toFixed(1) : '0';
                const reussis = tentatives.filter(t => t.statut === 'reussi').length;
                const rate = count > 0 ? Math.round((reussis / count) * 100) : 0;
                return { avg, rate, count };
            }
        }));
    });
</script>
@endsection
