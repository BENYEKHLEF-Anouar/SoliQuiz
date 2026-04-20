@extends('components.layout.app')

@section('content')
    <div class="bg-slate-50 min-h-screen pb-16">
        <main class="max-w-7xl mx-auto px-4 py-12">
            <!-- Hero Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1
                        class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase italic mb-2">
                        Bonjour, {{ Auth::user()->prenom }}</h1>
                    <p class="text-slate-500 mt-1 font-medium text-lg">Voici votre progression pédagogique.</p>
                </div>
                <div class="inline-flex flex-col items-end bg-white p-4 px-6 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Score Moyen Global</span>
                    <span class="text-3xl font-black text-primary-500">{{ $metrics['score_moyen'] }}%</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Tests Effectués
                    </h3>
                    <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_tentatives'] }}</div>
                </div>
                <div class="bg-white border border-emerald-200 shadow-sm rounded-2xl p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2 italic">Taux de
                            Réussite</h3>
                        <div class="text-3xl font-black font-heading text-emerald-700">{{ $metrics['taux_reussite'] }}%
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Meilleur Score
                    </h3>
                    <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['meilleur_score'] }}%</div>
                </div>
            </div>

            <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight italic mb-6">Tests Récemment
                passés</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($historique as $tentative)
                    @php
                        $isSuccess = $tentative->statut === 'reussi';
                    @endphp
                    <div
                        class="bg-white rounded-[2rem] border border-slate-200 p-6 flex flex-col relative overflow-hidden transition-all hover:shadow-lg hover:shadow-slate-200 group">
                        <div class="flex justify-between items-start mb-6">
                            <span
                                class="inline-flex py-1 px-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $isSuccess ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $isSuccess ? 'Validé' : 'Non Validé' }}
                            </span>
                            <div
                                class="size-12 rounded-full border-4 {{ $isSuccess ? 'border-emerald-100' : 'border-rose-100' }} flex items-center justify-center bg-white z-10 shrink-0">
                                <span
                                    class="text-xs font-black {{ $isSuccess ? 'text-emerald-600' : 'text-rose-600' }}">{{ $tentative->score_obtenu }}%</span>
                            </div>
                        </div>

                        <h3
                            class="text-xl font-heading font-black text-slate-900 tracking-tight leading-tight mb-2 pr-4 relative z-10">
                            {{ $tentative->qcm->titre }}</h3>
                        <p class="text-sm font-medium text-slate-500 mb-6 relative z-10">
                            {{ $tentative->qcm->uniteApprentissage ? $tentative->qcm->uniteApprentissage->nom : 'Évaluation générale' }}
                        </p>

                        <div class="mt-auto relative z-10">
                            <a href="{{ route('student.resultats', ['id' => $tentative->qcm_id]) }}"
                                class="inline-flex w-full justify-center items-center gap-x-2 px-4 py-2.5 bg-slate-50 text-slate-700 font-bold rounded-xl hover:bg-slate-100 transition-colors border border-slate-200 text-sm">
                                Voir la correction
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-[2rem] border border-slate-200 text-center">
                        <p class="text-slate-500 font-medium">Vous n'avez passé aucun test pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
@endsection