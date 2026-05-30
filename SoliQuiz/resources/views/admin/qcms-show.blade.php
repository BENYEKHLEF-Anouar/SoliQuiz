@extends('layouts.app')

@section('title', $qcm->titre . ' - Détails QCM')

@section('page-title', 'Détails de l\'Évaluation')

@section('content')
<div class="fade-in space-y-8 pb-20">
    <!-- Header: Navigation & Breadcrumbs -->
    <div class="mb-8 space-y-6">
        <x-ui.breadcrumb :items="[
            'Banque de QCM' => route('admin.qcms'), 
            'Détails QCM' => '#'
        ]" />

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <a href="{{ route('admin.qcms') }}" 
               class="inline-flex items-center gap-2 text-slate-400 hover:text-primary-600 transition-colors group">
                <div class="size-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:border-primary-200 group-hover:bg-primary-50 transition-all">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
            </a>
        </div>
    </div>

    <!-- Title & Identity Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.01)] p-6 md:p-8 relative overflow-hidden mb-8">
        <div class="absolute right-0 -top-6 p-8 opacity-5 pointer-events-none">
            <svg class="size-32 text-slate-900" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="m9 15 2 2 4-4"/></svg>
        </div>
        <div class="relative z-10 flex flex-col gap-2">
            <span class="text-[9px] font-black text-primary-500 uppercase tracking-[0.25em] leading-none mb-1">Identité de l'évaluation</span>
            <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase mb-2">
                {{ $qcm->titre }}
            </h1>
            <p class="text-xs font-bold text-slate-450 uppercase tracking-widest">
                {{ $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Module Transversal' }} · Cohorte : {{ $qcm->classe ? $qcm->classe->nom : 'Indépendant' }}
            </p>
        </div>
    </div>

    <!-- Enhanced Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Taux de Réussite -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Taux de réussite</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-600">{{ $metrics['taux_reussite'] }}%</span>
            </div>
            <div class="mt-4 w-full bg-slate-100 rounded-full h-2">
                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $metrics['taux_reussite'] }}%"></div>
            </div>
        </div>

        <!-- Moyenne Générale -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Moyenne Générale</span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-slate-900">{{ $metrics['moyenne'] }}</span>
                <span class="text-sm font-bold text-slate-400">/20</span>
            </div>
            <p class="mt-3 text-[10px] font-black text-slate-400 uppercase tracking-wider">Sur {{ $metrics['total_tentatives'] }} passages</p>
        </div>

        <!-- Min & Max Scores -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Scores extrêmes</span>
            </div>
            <div class="flex justify-between items-baseline mt-1.5">
                <div>
                    <span class="text-2xl font-black text-slate-900">{{ $metrics['max_score'] }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase block tracking-wider">Max</span>
                </div>
                <div class="h-6 w-px bg-slate-100"></div>
                <div>
                    <span class="text-2xl font-black text-slate-900">{{ $metrics['min_score'] }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase block tracking-wider">Min</span>
                </div>
            </div>
        </div>

        <!-- Configuration -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center text-slate-600">
                    <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Paramètres</span>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-700">Durée : <span class="text-slate-900 font-black">{{ $qcm->duree_minutes }} min</span></p>
                <p class="text-xs font-bold text-slate-700">Seuil : <span class="text-slate-900 font-black">{{ $qcm->score_reussite }}/20</span></p>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Passations List (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 uppercase text-sm tracking-tight">Passages et Résultats</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Historique des étudiants ayant passé le test</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($tentatives as $t)
                        @php $isSuccess = $t->score_obtenu >= $qcm->score_reussite; @endphp
                        <div class="p-4.5 hover:bg-slate-50 transition-colors flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <img class="size-9 rounded-xl border border-slate-100"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($t->etudiant->nom_complet) }}&background=f8fafc&color=64748b&bold=true"
                                     alt="Avatar">
                                <div class="min-w-0">
                                    <p class="font-black text-sm text-slate-900 truncate uppercase leading-none mb-1.5">{{ $t->etudiant->nom_complet }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate leading-none">{{ $t->etudiant->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 shrink-0">
                                <div class="text-right">
                                    <span class="text-sm font-bold text-slate-400 block uppercase tracking-wider leading-none mb-1">Passage</span>
                                    <span class="text-[10px] font-black text-slate-900 tracking-tight">{{ $t->date_debut ? $t->date_debut->format('d/m/Y H:i') : '-' }}</span>
                                </div>

                                <div class="h-8 w-px bg-slate-100"></div>

                                <div class="text-right">
                                    <span class="text-lg font-black {{ $isSuccess ? 'text-emerald-600' : 'text-rose-600' }} leading-none">{{ $t->score_obtenu }}</span>
                                    <span class="text-[10px] font-bold text-slate-400">/20</span>
                                    <span class="block text-[8px] font-black uppercase tracking-wider mt-1 {{ $isSuccess ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ $isSuccess ? 'Validé' : 'Échoué' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="size-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-xs text-slate-400 font-black uppercase tracking-wider">Aucun passage enregistré</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Side: QCM metadata review (1/3 width) -->
        <div class="space-y-6">
            <!-- Author Card -->
            <div class="bg-slate-900 text-white rounded-3xl border border-slate-800 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 size-32 bg-slate-800 rounded-full -mr-16 -mt-16 opacity-30"></div>
                <h3 class="font-black uppercase text-xs tracking-widest text-slate-400 mb-4 relative z-10">Créateur & Statut</h3>
                
                <div class="flex items-center gap-4 relative z-10 mb-6">
                    <img class="size-11 rounded-xl bg-slate-800 border border-slate-700"
                         src="https://ui-avatars.com/api/?name={{ urlencode($qcm->formateur ? $qcm->formateur->nom_complet : 'Unknown') }}&background=1e293b&color=fff&bold=true"
                         alt="">
                    <div class="min-w-0">
                        <p class="font-black text-sm text-white truncate uppercase leading-none mb-1.5">{{ $qcm->formateur ? $qcm->formateur->nom_complet : 'Inconnu' }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate leading-none">Formateur</p>
                    </div>
                </div>

                <div class="space-y-3 relative z-10">
                    <div class="flex items-center justify-between py-2 border-b border-slate-800">
                        <span class="text-xs text-slate-400">Statut actuel</span>
                        <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-wider {{ $qcm->statut === 'public' ? 'bg-emerald-500/20 text-emerald-400' : ($qcm->statut === 'termine' ? 'bg-slate-850 text-slate-350' : 'bg-amber-500/20 text-amber-400') }}">
                            {{ $qcm->statut }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-800">
                        <span class="text-xs text-slate-400">Questions total</span>
                        <span class="text-xs font-black text-white">{{ $qcm->questions_count }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-xs text-slate-400">Créé le</span>
                        <span class="text-xs font-black text-white">{{ $qcm->created_at ? $qcm->created_at->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Checklist (Full Width Section Below) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8 space-y-6">
        <div>
            <h3 class="font-black uppercase text-sm tracking-tight text-slate-900">Aperçu du contenu</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Examinez la structure et les réponses attendues pour chaque question</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($qcm->questions as $index => $q)
                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-wider shrink-0">Question {{ $index + 1 }}</span>
                        </div>
                        <p class="text-sm font-black text-slate-900 leading-tight uppercase mb-4">{{ $q->texte }}</p>
                    </div>
                    
                    <div class="space-y-2 mt-auto">
                        @foreach($q->options as $opt)
                            <div class="flex items-center justify-between p-3 rounded-xl border text-[11px] font-bold transition-all {{ $opt->est_correcte ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-white border-slate-100 text-slate-650' }}">
                                <div class="flex items-center gap-3">
                                    <span class="size-2 rounded-full {{ $opt->est_correcte ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                    <span>{{ $opt->texte }}</span>
                                </div>
                                @if($opt->est_correcte)
                                    <span class="inline-flex items-center justify-center size-5 rounded-full bg-emerald-100 text-emerald-600 shrink-0">
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Aucune question dans ce QCM</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
