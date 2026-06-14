@extends('components.layout.app')

@section('content')
<div x-data="qcmResults" x-init="init()" x-data-qcm-id="{{ $qcmId }}" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    <header class="bg-slate-950/95 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-white/[0.03] shadow-lg shrink-0 sticky top-0 z-40">
        <div class="flex items-center gap-4 mt-2">
            <a href="{{ route('formateur.qcms') }}" class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 active:scale-95 transition-transform outline-none border-none text-white">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <h1 class="text-lg font-heading font-extrabold text-white tracking-tight leading-none truncate" x-text="qcmTitle || 'Chargement...'"></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1.5">
                    Résultats de passage <span x-show="classeName" class="text-primary-400" x-text="'• ' + classeName"></span>
                </p>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full px-5 py-6 pb-32 hide-scrollbar relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Extraction des résultats..." />
        </div>
        <!-- Search & Filters Row -->
        <div class="flex flex-col gap-4 mb-6">
            <!-- Search Input -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none transition-colors group-focus-within:text-primary-500">
                    <svg class="shrink-0 size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <input type="text" x-model="search" @input="applyFilters"
                    class="py-3.5 px-4 pl-11 block w-full border-slate-100 bg-white shadow-sm rounded-2xl text-sm font-medium focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                    placeholder="Rechercher un étudiant...">
                <!-- Clear button -->
                <button x-show="search" x-cloak @click="search = ''; applyFilters()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <!-- Custom Styled Filters -->
            <div class="flex gap-3">
                <!-- Score Filter Dropdown -->
                <div class="relative flex-1" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" type="button"
                        class="w-full flex justify-between items-center bg-white border border-slate-200 text-slate-700 rounded-xl py-3 px-4 text-xs font-bold shadow-sm outline-none transition-all hover:border-primary-300">
                        <span class="flex items-center gap-2">
                            <svg class="size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 3v18h18" /><path d="m19 9-5 5-4-4-3 3" />
                            </svg>
                            <span x-text="scoreFilter === 'all' ? 'Toutes notes' : scoreFilter === 'pass' ? 'Réussi (≥10)' : 'Échec (<10)'"></span>
                        </span>
                        <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl p-2 z-50" style="display: none;">
                        <button @click="scoreFilter = 'all'; open = false; applyFilters()"
                            class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-xs font-bold transition-colors"
                            :class="scoreFilter === 'all' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50'">
                            <span class="size-2 rounded-full bg-slate-400"></span>
                            Toutes notes
                        </button>
                        <button @click="scoreFilter = 'pass'; open = false; applyFilters()"
                            class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-xs font-bold transition-colors"
                            :class="scoreFilter === 'pass' ? 'bg-emerald-50 text-emerald-600' : 'text-slate-600 hover:bg-slate-50'">
                            <span class="size-2 rounded-full bg-emerald-500"></span>
                            Réussi (≥10/20)
                        </button>
                        <button @click="scoreFilter = 'fail'; open = false; applyFilters()"
                            class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-xs font-bold transition-colors"
                            :class="scoreFilter === 'fail' ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:bg-slate-50'">
                            <span class="size-2 rounded-full bg-red-500"></span>
                            Échec (<10/20)
                        </button>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="relative flex-1" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" type="button"
                        class="w-full flex justify-between items-center bg-white border border-slate-200 text-slate-700 rounded-xl py-3 px-4 text-xs font-bold shadow-sm outline-none transition-all hover:border-primary-300">
                        <span class="flex items-center gap-2">
                            <svg class="size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m3 16 4 4 4-4" /><path d="M7 20V4" /><path d="m21 8-4-4-4 4" /><path d="M17 4v16" />
                            </svg>
                            <span x-text="sortBy === 'score' ? 'Note' : sortBy === 'name' ? 'Nom' : 'Date'"></span>
                        </span>
                        <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl p-2 z-50" style="display: none;">
                        <button @click="sortBy = 'score'; open = false; applyFilters()"
                            class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-xs font-bold transition-colors"
                            :class="sortBy === 'score' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50'">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20" /><path d="m17 7-5-5-5 5" /></svg>
                            Par note (meilleur)
                        </button>
                        <button @click="sortBy = 'name'; open = false; applyFilters()"
                            class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-xs font-bold transition-colors"
                            :class="sortBy === 'name' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50'">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4" /><path d="M5 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2" /></svg>
                            Par nom (A-Z)
                        </button>
                        <button @click="sortBy = 'date'; open = false; applyFilters()"
                            class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-xs font-bold transition-colors"
                            :class="sortBy === 'date' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50'">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                            Par date (récent)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Filters Count & Reset -->
            <div class="flex justify-between items-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">
                    <span x-text="filteredResults.length"></span> résultat<span x-show="filteredResults.length !== 1">s</span>
                </p>
                <button x-show="search || scoreFilter !== 'all'" x-cloak @click="resetFilters"
                    class="text-[10px] font-bold text-primary-600 uppercase tracking-widest hover:text-primary-700 transition-colors flex items-center gap-1">
                    <svg class="size-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 12" /><path d="M3 3v9h9" /></svg>
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Summary Stats -->
        <div x-show="!loading" class="mb-6 grid grid-cols-3 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Total</p>
                <p class="text-2xl font-heading font-extrabold text-slate-900" x-text="results.length"></p>
            </div>
            <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100">
                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-[0.15em] mb-1">Réussite</p>
                <p class="text-2xl font-heading font-extrabold text-emerald-600" x-text="passCount"></p>
            </div>
            <div class="bg-red-50 p-4 rounded-2xl border border-red-100">
                <p class="text-[9px] font-black text-red-600 uppercase tracking-[0.15em] mb-1">Échec</p>
                <p class="text-2xl font-heading font-extrabold text-red-600" x-text="failCount"></p>
            </div>
        </div>

        <!-- Results List -->
        <div class="flex flex-col mb-4">
            <h2 class="text-sm font-heading font-extrabold text-slate-900 uppercase tracking-tight">DÉTAILS</h2>
        </div>

        <div class="grid gap-3">
            <template x-for="res in filteredResults" :key="res.id">
                <div class="flex items-center bg-white border border-slate-100 shadow-sm rounded-2xl p-4 transition-all hover:border-primary-200 hover:shadow-md active:scale-[0.98] group"
                    :class="getScoreClass(res)">
                    <!-- Rank -->
                    <div class="size-10 rounded-xl flex items-center justify-center text-xs font-black shrink-0 mr-4"
                        :class="res.rank <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500'">
                        <span x-text="res.rank"></span>
                    </div>

                    <!-- Avatar with initials -->
                    <div class="size-12 rounded-xl flex items-center justify-center text-white font-black text-sm shrink-0 mr-4 transition-transform group-hover:scale-105"
                        :class="getScoreColorClass(res)"
                        x-text="getInitials(res.studentName)">
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight truncate" x-text="res.studentName"></h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5" x-text="formatDate(res.date)"></p>
                    </div>

                    <!-- Score Badge -->
                    <div class="text-right ml-3">
                        <div class="px-3 py-1.5 rounded-xl text-sm font-black tracking-tight border shadow-sm whitespace-nowrap"
                            :class="getScoreBadgeClass(res)">
                            <span x-text="res.score ?? 0"></span>/<span x-text="res.maxScore || (res.totalQuestions * 2) || 20"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>


        <!-- Empty state -->
        <div x-show="!loading && filteredResults.length === 0" class="text-center py-16 animate-in fade-in duration-500">
            <div class="size-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                <svg class="size-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />
                </svg>
            </div>
            <p class="text-sm font-extrabold text-slate-700">Aucun résultat</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-show="search || scoreFilter !== 'all'">Essayez d'autres filtres</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-show="!search && scoreFilter === 'all'">En attente des premières réponses</p>
        </div>
    </main>

    <!-- Navigation -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }
    </style>
</div>
@endsection
