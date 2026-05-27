@extends('components.layout.app')

@section('content')
<div x-data="library" x-init="init()" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    @include('components.header.student-header')

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-28 relative">
        <!-- Global Loading State -->
        <div x-show="loading" class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Chargement de la bibliothèque..." />
        </div>

        <div x-show="!loading" class="px-5 pt-6 space-y-6">
            <!-- Title -->
            <div>
                <h2 class="text-3xl font-heading font-extrabold text-slate-900 tracking-tight">BIBLIOTHÈQUE</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Catalogue de vos QCMs</p>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-3 gap-3 bg-white p-4 rounded-3xl border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)]">
                <div class="text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Moyenne</p>
                    <p class="text-lg font-black text-slate-900" x-text="moyenne + '/20'"></p>
                </div>
                <div class="border-l border-slate-100 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Terminés</p>
                    <p class="text-lg font-black text-emerald-500" x-text="terminesCount"></p>
                </div>
                <div class="border-l border-slate-100 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">À Faire</p>
                    <p class="text-lg font-black text-primary-500" x-text="aFaireCount"></p>
                </div>
            </div>

            <!-- Search and Filter controls -->
            <div class="space-y-3">
                <!-- Search Input -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" x-model="search" @input="filterQcms()"
                           placeholder="Rechercher un QCM..."
                           class="w-full h-12 bg-white border border-slate-100 rounded-2xl pl-11 pr-4 text-xs font-bold text-slate-950 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all shadow-sm">
                </div>

                <!-- Filters Row -->
                <div class="flex gap-2">
                    <!-- UA Filter Dropdown -->
                    <div x-data="{ open: false }" class="relative flex-1">
                        <button @click="open = !open" @click.away="open = false"
                                class="w-full h-11 px-4 bg-white border border-slate-100 rounded-2xl flex items-center justify-between text-[11px] font-black uppercase tracking-wider text-slate-600 shadow-sm">
                            <span class="truncate pr-1" x-text="uaLabel">Toutes les UAs</span>
                            <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-2 max-h-48 overflow-y-auto">
                            <button @click="selectUa('', 'Toutes les UAs'); open = false" class="w-full px-4 py-2.5 text-left text-[11px] font-black uppercase text-slate-400 hover:bg-slate-50 rounded-lg">Toutes les UAs</button>
                            <template x-for="ua in unites" :key="ua.id">
                                <button @click="selectUa(ua.id, ua.nom); open = false" class="w-full px-4 py-2.5 text-left text-[11px] font-black uppercase text-slate-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg truncate" x-text="ua.nom"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Status Filter Dropdown -->
                    <div x-data="{ open: false }" class="relative flex-1">
                        <button @click="open = !open" @click.away="open = false"
                                class="w-full h-11 px-4 bg-white border border-slate-100 rounded-2xl flex items-center justify-between text-[11px] font-black uppercase tracking-wider text-slate-600 shadow-sm">
                            <span class="truncate" x-text="statusLabel">Statut : Tous</span>
                            <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-2">
                            <button @click="selectStatus('', 'Statut : Tous'); open = false" class="w-full px-4 py-2.5 text-left text-[11px] font-black uppercase text-slate-400 hover:bg-slate-50 rounded-lg">Tous</button>
                            <button @click="selectStatus('a_faire', 'À faire'); open = false" class="w-full px-4 py-2.5 text-left text-[11px] font-black uppercase text-primary-600 hover:bg-primary-50 rounded-lg">À faire</button>
                            <button @click="selectStatus('en_cours', 'En cours'); open = false" class="w-full px-4 py-2.5 text-left text-[11px] font-black uppercase text-amber-600 hover:bg-amber-50 rounded-lg">En cours</button>
                            <button @click="selectStatus('reussi', 'Réussis'); open = false" class="w-full px-4 py-2.5 text-left text-[11px] font-black uppercase text-emerald-600 hover:bg-emerald-50 rounded-lg">Réussis</button>
                            <button @click="selectStatus('echoue', 'Échoués'); open = false" class="w-full px-4 py-2.5 text-left text-[11px] font-black uppercase text-rose-600 hover:bg-rose-50 rounded-lg">Échoués</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QCM Cards List -->
            <div class="space-y-4">
                <template x-for="qcm in filteredQcms" :key="qcm.id">
                    <div class="bg-white border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)] rounded-[2rem] p-5 relative overflow-hidden group">
                        
                        <!-- Top header card line -->
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest truncate max-w-[200px]" x-text="qcm.unite_nom"></span>
                            
                            <!-- Badges -->
                            <template x-if="qcm.etat === 'reussi'">
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-[9px] font-black uppercase tracking-wider">Réussi</span>
                            </template>
                            <template x-if="qcm.etat === 'echoue'">
                                <span class="px-2 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl text-[9px] font-black uppercase tracking-wider">Échoué</span>
                            </template>
                            <template x-if="qcm.etat === 'en_cours'">
                                <span class="px-2 py-1 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl text-[9px] font-black uppercase tracking-wider">En cours</span>
                            </template>
                            <template x-if="qcm.etat === 'a_faire'">
                                <span class="px-2 py-1 bg-slate-50 text-slate-400 border border-slate-100 rounded-xl text-[9px] font-black uppercase tracking-wider">À faire</span>
                            </template>
                        </div>

                        <!-- QCM Title -->
                        <h3 class="text-lg font-heading font-extrabold text-slate-900 leading-tight mb-4" x-text="qcm.titre"></h3>

                        <!-- Info details row -->
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-400 mb-5">
                            <div class="flex items-center gap-1.5">
                                <svg class="size-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span x-text="qcm.questions_count + ' Qs'"></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="size-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span x-text="qcm.duree_minutes + ' Min'"></span>
                            </div>
                            <template x-if="qcm.score !== null">
                                <div class="text-slate-900 text-sm font-black">
                                    <span x-text="qcm.score"></span><span class="text-[9px] text-slate-400">/20</span>
                                </div>
                            </template>
                        </div>

                        <!-- Action Button -->
                        <div class="border-t border-slate-50 pt-4 flex justify-between items-center">
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest" x-text="'Par : ' + qcm.formateur_nom"></span>

                            <template x-if="qcm.etat === 'a_faire'">
                                <a :href="'/student/qcm/' + qcm.id" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-wider rounded-xl hover:bg-primary-600 transition-colors">
                                    Démarrer
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path d="m9 18 6-6-6-6"/></svg>
                                </a>
                            </template>

                            <template x-if="qcm.etat === 'reussi' || qcm.etat === 'echoue'">
                                <a :href="'/student/qcm/' + qcm.id + '/result'" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-slate-50 text-slate-900 border border-slate-100 text-[10px] font-black uppercase tracking-wider rounded-xl hover:bg-slate-100 transition-colors">
                                    Bilan
                                    <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredQcms.length === 0" class="text-center py-16 px-4">
                    <div class="size-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-300 mb-4">
                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 mb-1">Aucun QCM trouvé</h3>
                    <p class="text-xs text-slate-400">Essayez de modifier vos filtres ou termes de recherche.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation -->
    @include('components.nav.student-bottom-nav')
</div>


@endsection
