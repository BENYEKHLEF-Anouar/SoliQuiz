@extends('layouts.app')

@section('title', 'Banque de QCM - SoliQuiz')

@section('page-title', 'Banque de QCM Repository')

@section('content')
<div class="fade-in space-y-12 pb-20" x-data="{ search: '{{ $search }}' }">
    <!-- Header Strategy Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-10 border-b border-slate-100 mb-10">
        <div>
            <p class="text-label mb-1">Ressources Pédagogiques</p>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight italic uppercase">Global <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">Library</span></h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Gestion centralisée des actifs d'évaluation</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-6 px-8 py-4 bg-white rounded-[24px] border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="absolute inset-0 bg-primary-50/30 -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                <div class="relative z-10 text-right">
                    <p class="text-2xl font-black text-slate-900 italic leading-none mb-1">{{ $qcms->total() }}</p>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">QCM Indexés</p>
                </div>
                <div class="relative z-10 size-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-500">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-6 items-center">
        <form action="{{ route('admin.qcms') }}" method="GET" class="flex-1 w-full relative group">
            <label class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary-500 transition-colors pointer-events-none">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </label>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Rechercher une évaluation, un module ou un auteur..."
                   class="w-full h-20 pl-16 pr-8 bg-white border border-slate-100 rounded-[32px] font-bold text-slate-900 placeholder:text-slate-300 focus:border-primary-500 focus:ring-8 focus:ring-primary-500/5 transition-all shadow-sm group-hover:shadow-premium outline-none">
        </form>
        
        <div class="flex items-center self-stretch gap-3">
             <button type="button" class="h-20 px-8 bg-white border border-slate-100 rounded-[32px] text-slate-400 hover:text-primary-600 hover:border-primary-100 transition-all shadow-sm group active:scale-95">
                 <svg class="size-6 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
             </button>
        </div>
    </div>

    <!-- QCM Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-8">
        @forelse($qcms as $qcm)
            <div class="group bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm hover:shadow-premium hover:-translate-y-2 transition-all duration-500 relative overflow-hidden flex flex-col h-full">
                <div class="absolute -right-12 -top-12 size-40 bg-slate-50/50 rounded-full group-hover:bg-primary-50/50 group-hover:scale-125 transition-all duration-700"></div>
                
                <!-- Status Badge -->
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic {{ $qcm->statut === 'public' ? 'bg-emerald-100 text-emerald-600' : ($qcm->statut === 'termine' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-600') }}">
                        {{ $qcm->statut }}
                    </span>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest font-mono">QCM-{{ str_pad($qcm->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="relative z-10 flex-1">
                    <h3 class="text-xl font-heading font-black text-slate-900 group-hover:text-primary-600 transition-colors leading-tight mb-4 uppercase italic">
                        {{ $qcm->titre }}
                    </h3>
                    
                    <div class="space-y-4 mb-10">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-primary-500">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Module</p>
                                <p class="text-xs font-bold text-slate-600 truncate max-w-[200px]">{{ $qcm->uniteApprentissage->nom ?? 'Module Transversal' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 transition-colors group-hover:bg-white group-hover:shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Durée</p>
                                <p class="text-lg font-black text-slate-900 italic leading-none">{{ $qcm->duree_minutes }}<span class="text-[10px] uppercase ml-1">min</span></p>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 transition-colors group-hover:bg-white group-hover:shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Questions</p>
                                <p class="text-lg font-black text-slate-900 italic leading-none">{{ $qcm->questions_count }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-6 border-t border-slate-50 mt-auto flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-3">
                        <img class="size-8 rounded-lg bg-slate-100 border border-slate-200" src="https://ui-avatars.com/api/?name={{ urlencode(optional($qcm->formateur)->nom_complet ?? 'Unknown') }}&background=f8fafc&color=64748b&bold=true" alt="">
                        <div class="flex flex-col">
                            <p class="text-[10px] font-black text-slate-900 uppercase italic leading-none truncate max-w-[100px]">{{ $qcm->formateur->prenom }}</p>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.1em] mt-1">Formateur</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2" x-data="{ options: false }">
                        <a href="{{ route('formateur.qcm.edit', $qcm->id) }}"
                           class="size-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-primary-500 hover:shadow-lg hover:shadow-primary-500/20 transition-all active:scale-95 shadow-sm">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </a>
                        
                        <div class="relative">
                            <button @click="options = !options" type="button"
                                    class="size-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center hover:bg-slate-200 hover:text-slate-900 transition-all active:scale-95">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                            </button>
                            
                            <!-- Options Menu -->
                            <div x-show="options" @click.away="options = false"
                                 class="absolute bottom-full right-0 mb-3 w-56 bg-white rounded-[24px] border border-slate-100 shadow-premium p-2 z-[100]"
                                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                                 style="display: none;">
                                
                                <form action="{{ route('formateur.qcm.toggle', $qcm->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $qcm->statut === 'public' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors group/opt">
                                        <span>{{ $qcm->statut === 'public' ? 'Dépublier' : 'Publier' }}</span>
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </form>

                                <form id="delete-qcm-{{ $qcm->id }}" action="{{ route('formateur.qcm.destroy', $qcm->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            @click.prevent="$dispatch('confirm', { 
                                                title: 'Supprimer ce QCM ?', 
                                                message: 'Cette action est irréversible et supprimera toutes les tentatives liées.', 
                                                onConfirm: 'delete-qcm-{{ $qcm->id }}' 
                                            })"
                                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-50 transition-colors text-[9px] font-black uppercase tracking-widest">
                                        <span>Supprimer</span>
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full glass p-20 text-center rounded-[50px] border-2 border-dashed border-slate-100">
                <div class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 border border-slate-100 shadow-inner">
                    <svg class="size-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-heading font-black text-slate-900 mb-2">Bibliothèque Vierge</h3>
                <p class="text-slate-400 font-medium italic">Aucun actif d'évaluation ne correspond à votre recherche.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $qcms->links() }}
    </div>
</div>
@endsection
