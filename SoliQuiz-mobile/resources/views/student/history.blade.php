@extends('components.layout.app')

@section('content')
<div x-data="history" x-init="init()" class="h-full flex flex-col relative overflow-hidden bg-slate-50">
    <!-- Header -->
    @include('components.header.student-header')

    <main class="flex-1 overflow-y-auto w-full px-5 py-8 pb-32 hide-scrollbar relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Extraction des scores..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <!-- Empty state (overall history is empty) -->
            <div x-show="history.length === 0" class="text-center py-24 animate-in fade-in duration-700">
                <div class="size-20 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-inner">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-base font-extrabold text-slate-900 tracking-tight">Aucune activité</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Commencez un QCM pour voir vos stats</p>
            </div>

            <div x-show="history.length > 0" class="space-y-6">
                <!-- Title -->
                <div>
                    <h2 class="text-3xl font-heading font-extrabold text-slate-900 tracking-tight">HISTORIQUE</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Parcours individuel</p>
                </div>

                <!-- Progression Chart Card -->
                <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)] relative overflow-hidden group">
                    <!-- Target line overlay (10/20 mark) -->
                    <div class="absolute inset-x-6 top-1/2 border-t border-dashed border-slate-100 flex justify-end items-center z-20 pointer-events-none">
                        <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest bg-white pl-2 mt-[-6px]">Moyenne (10/20)</span>
                    </div>

                    <div class="relative z-10 flex justify-between items-center mb-4">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] block">Évolution des notes</span>
                        <span class="text-[9px] font-black text-primary-500 uppercase tracking-wider" x-text="'Moyenne: ' + (history.length > 0 ? (history.reduce((acc, h) => acc + Number(h.score), 0) / history.length).toFixed(1) : 0) + '/20'"></span>
                    </div>

                    <!-- Bars -->
                    <div class="h-28 flex items-end gap-3 px-1 relative z-10">
                        <template x-for="(attempt, index) in lastAttempts" :key="index">
                            <div class="flex-1 h-full flex items-end relative group/bar">
                                <div class="w-full rounded-t-lg transition-all duration-500"
                                     :class="attempt.score >= 10 ? 'bg-gradient-to-t from-primary-400 to-primary-500 hover:from-primary-500 hover:to-primary-600' : 'bg-gradient-to-t from-rose-400 to-rose-500 hover:from-rose-500 hover:to-rose-600'"
                                     :style="'height: ' + ((attempt.score / 20) * 100) + '%'"></div>
                                
                                <!-- Interactive Tooltip -->
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-slate-900 text-white text-[9px] font-black px-2 py-1 rounded-lg opacity-0 group-hover/bar:opacity-100 transition-opacity duration-200 whitespace-nowrap z-30 shadow-lg flex flex-col items-center">
                                    <span class="text-[7px] text-slate-400 font-bold max-w-[80px] truncate block" x-text="attempt.title"></span>
                                    <span x-text="attempt.score + '/20'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Filter Pills -->
                <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200/40">
                    <button @click="filterStatus = 'all'" 
                            :class="filterStatus === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all outline-none">
                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                        Tous
                    </button>
                    <button @click="filterStatus = 'success'" 
                            :class="filterStatus === 'success' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all outline-none">
                        <span class="size-1.5 rounded-full" :class="filterStatus === 'success' ? 'bg-white' : 'bg-emerald-500'"></span>
                        Réussis
                    </button>
                    <button @click="filterStatus = 'failure'" 
                            :class="filterStatus === 'failure' ? 'bg-rose-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all outline-none">
                        <span class="size-1.5 rounded-full" :class="filterStatus === 'failure' ? 'bg-white' : 'bg-rose-500'"></span>
                        Échoués
                    </button>
                </div>

                <!-- History list -->
                <div class="grid gap-4">
                    <template x-for="item in filteredHistory" :key="item.id">
                        <div class="p-5 flex items-center bg-white border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] rounded-[2rem] cursor-pointer active:scale-[0.98] transition-all hover:border-slate-200 group" 
                             @click="window.location.href = '/student/qcm/' + item.qcm_id + '/result'">
                            <div class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-primary-500 shrink-0 group-hover:bg-primary-50 transition-colors">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ms-5 flex-1">
                                <h3 class="font-extrabold text-slate-900 text-base leading-tight" x-text="item.title"></h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight mt-1" x-text="item.date"></p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1.5 rounded-xl text-xs font-black tracking-tight border shadow-sm transition-all"
                                    :class="item.score >= 10 ? 'bg-primary-50 text-primary-600 border-primary-100' : 'bg-semantic-error/10 text-semantic-error border-semantic-error/10'">
                                    <span x-text="item.score"></span> <span class="opacity-40 text-[9px]">/</span> 20
                                </span>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="filteredHistory.length === 0" class="text-center py-12 animate-in fade-in duration-500">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Aucun résultat trouvé</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Navigation -->
    @include('components.nav.student-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>
@endsection