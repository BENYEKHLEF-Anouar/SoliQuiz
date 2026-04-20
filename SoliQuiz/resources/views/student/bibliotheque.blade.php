@extends('layouts.app')

@section('title', 'Bibliothèque - SoliQuiz')

@section('content')
<div class="space-y-10 reveal active">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-500 transition-colors">Plateforme</a>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Bibliothèque Éducative</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Mes <span class="text-primary-500">Évaluations</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl">
                Consultez votre historique de réussite et accédez aux nouveaux modules d'apprentissage disponibles pour votre cohorte.
            </p>
        </div>
        
        <div class="flex items-center gap-3">
             @if (session('error'))
                <div class="bg-red-50 text-red-600 px-6 py-4 rounded-2xl border border-red-100 text-xs font-black uppercase tracking-widest shadow-sm animate-shake">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Dashboard Sidebar Stats -->
        <aside class="col-span-1 lg:col-span-3 space-y-6">
            <div class="bg-slate-900 p-8 rounded-[32px] shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 size-32 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <h3 class="text-[10px] font-black text-primary-400 uppercase tracking-[0.2em] mb-6 relative z-10">Ma Performance</h3>
                <div class="relative z-10">
                    <span class="text-6xl font-black text-white font-heading tracking-tighter">{{ $moyenne }}<span class="text-primary-500">%</span></span>
                    <p class="text-[10px] text-slate-400 font-black uppercase mt-2 tracking-widest">Score Moyen Global</p>
                </div>
                <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between relative z-10">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Terminé(s)</span>
                    <span class="text-2xl font-black text-white font-heading">{{ $termines->count() }}</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-premium transition-all">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-primary-500 pl-3">À Réaliser</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-slate-900 font-heading">{{ $aFaire->count() }}</span>
                    <span class="text-xs font-bold text-slate-400 uppercase">QCM Restants</span>
                </div>
            </div>
        </aside>

        <!-- Dynamic List -->
        <section class="col-span-1 lg:col-span-9 space-y-8">
            <div class="glass p-4 rounded-[40px] shadow-premium border border-white">
                <div class="bg-white/40 rounded-[32px] overflow-hidden">
                    @if($enCours->count() === 0 && $aFaire->count() === 0 && $termines->count() === 0)
                        <div class="text-center py-20">
                            <div class="size-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="size-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            </div>
                            <h4 class="text-xl font-black text-slate-900 mb-2">Catalogue vide</h4>
                            <p class="text-slate-500 font-medium italic text-sm">Patientez le temps que votre formateur déploie un test.</p>
                        </div>
                    @endif

                    {{-- Priority Items: En Cours / À Faire --}}
                    @foreach(collect()->merge($enCours)->merge($aFaire) as $qcm)
                        <div class="group flex flex-col md:flex-row md:items-center justify-between gap-6 p-8 hover:bg-white transition-all cursor-default border-b border-white last:border-0 relative">
                            <div class="flex items-center gap-6">
                                <div class="size-16 rounded-[24px] bg-primary-50 flex items-center justify-center text-primary-500 group-hover:bg-primary-500 group-hover:text-white transition-all duration-500 group-hover:rotate-6">
                                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none mb-2">{{ $qcm->titre }}</h3>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-600">
                                        {{ $qcm->etat === 'en_cours' ? 'Session active • Reprendre' : 'Nouveau • Prêt au lancement' }}
                                    </p>
                                </div>
                            </div>
                            
                            <a href="{{ route('student.passation', $qcm->id) }}"
                                class="btn-premium px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all flex items-center justify-center gap-3">
                                {{ $qcm->etat === 'en_cours' ? 'Reprendre' : 'Démarrer' }}
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            </a>
                        </div>
                    @endforeach

                    {{-- Completed Items --}}
                    @foreach($termines as $qcm)
                        @php
                            $isSuccess = ($qcm->score ?? 0) >= ($qcm->score_reussite ?? 50);
                        @endphp
                        <div class="group flex flex-col md:flex-row md:items-center justify-between gap-6 p-8 hover:bg-white transition-all border-b border-white last:border-0 opacity-80 hover:opacity-100">
                            <div class="flex items-center gap-6">
                                <div class="size-16 rounded-[24px] {{ $isSuccess ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }} flex items-center justify-center transition-all duration-500 group-hover:scale-105">
                                    @if($isSuccess)
                                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                                    @else
                                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 tracking-tight leading-none mb-2">{{ $qcm->titre }}</h3>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        {{ $isSuccess ? 'Validé' : 'Échec' }} • Résultat du {{ $qcm->date_fin->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-8">
                                <div class="text-right">
                                    <span class="text-3xl font-black font-heading {{ $isSuccess ? 'text-emerald-500' : 'text-rose-500' }}">{{ $qcm->score }}%</span>
                                </div>
                                <a href="{{ route('student.resultats', $qcm->id) }}"
                                   class="size-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-primary-500 hover:text-white transition-all hover:rotate-12">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</div>
@endsection