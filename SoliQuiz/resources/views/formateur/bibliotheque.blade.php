@extends('components.layout.app')

@section('content')
    <div class="bg-slate-50 min-h-screen">
        <main class="max-w-7xl mx-auto px-4 py-12">

            <main class="max-w-7xl mx-auto px-4 py-12">
                @if (session('success'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-600 font-bold text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                    <div>
                        <nav
                            class="flex items-center gap-2 text-primary-500 text-xs font-bold uppercase tracking-widest mb-3">
                            <span class="size-1.5 rounded-full bg-primary-500"></span>
                            Gestionnaire de contenu
                        </nav>
                        <h1 class="text-4xl font-heading font-bold text-slate-900 tracking-tight leading-tight">Bibliothèque
                            de QCM</h1>
                        <p class="text-slate-500 mt-2 font-medium">Visualisez, éditez et archivez vos évaluations créées
                            pour l'ensemble du centre.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($qcms as $qcm)
                        <article
                            class="bg-white border-2 border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-primary-100 transition-all group flex flex-col relative overflow-hidden">
                            <div
                                class="absolute -top-12 -right-12 size-32 bg-emerald-50 rounded-full group-hover:bg-primary-50 transition-colors">
                            </div>

                            <div class="flex justify-between items-start mb-6 relative">
                                @if($qcm->est_publie)
                                    <span
                                        class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-200/50">Publié</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-slate-200">Brouillon</span>
                                @endif

                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-400">{{ $qcm->tentatives_count }} Passages</span>
                                </div>
                            </div>

                            <h3
                                class="text-xl font-heading font-black text-slate-900 group-hover:text-primary-600 transition-colors mb-2">
                                {{ $qcm->titre }}</h3>
                            <p class="text-sm text-slate-500 leading-relaxed font-medium mb-8">Score de réussite requis :
                                {{ $qcm->score_reussite }}% • Durée : {{ $qcm->duree_minutes }} min</p>

                            <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Créé le
                                    {{ $qcm->created_at->format('d/m/Y') }}</span>
                                <div class="flex gap-1.5">
                                    <button type="button"
                                        class="size-9 inline-flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-all active:scale-90 shadow-sm"
                                        onclick="if(confirm('Supprimer ce QCM ?')) { document.getElementById('delete-qcm-{{ $qcm->id }}').submit() }">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.2">
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="delete-qcm-{{ $qcm->id }}" action="{{ route('formateur.qcm.destroy', $qcm->id) }}"
                                        method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <!-- New QCM Card (CTA) -->
                    <a href="{{ route('formateur.qcm.create') }}"
                        class="bg-primary-50/50 border-2 border-dashed border-primary-200 rounded-3xl p-6 flex flex-col items-center justify-center text-center group hover:bg-primary-500 hover:border-primary-500 transition-all duration-300 min-h-[250px]">
                        <div
                            class="size-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-500 mb-6 group-hover:bg-white transition-all shadow-lg shadow-primary-500/10">
                            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h3
                            class="text-xl font-heading font-black text-primary-900 group-hover:text-white transition-colors">
                            Créer un nouveau contenu</h3>
                        <p
                            class="text-xs font-bold text-primary-600 mt-2 group-hover:text-primary-100 uppercase tracking-widest font-sans">
                            Ajouter une évaluation</p>
                    </a>
                </div>
            </main>
    </div>
@endsection