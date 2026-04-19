@extends('components.layout.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Header / Navbar Apprenant -->
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-white border-b border-slate-200 sticky top-0">
        <nav class="relative max-w-7xl w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3">
            <div class="md:col-span-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('student.dashboard') }}">
                    <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/10 transition-transform group-hover:scale-110">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Apprenant</span>
                    </div>
                </a>
            </div>

            <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
                <a href="{{ route('student.dashboard') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Dashboard</a>
                <a href="{{ route('student.bibliotheque') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Bibliothèque</a>
            </div>

            <div class="flex items-center gap-x-3 ms-auto md:col-span-3 justify-end">
                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-avatar" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 focus:outline-none p-1 pr-3 transition-all active:scale-95">
                        <img class="inline-block size-8 rounded-full shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom . ' ' . Auth::user()->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                        <span class="hidden md:inline-block text-slate-800 font-heading font-bold text-sm">{{ Auth::user()->prenom }}.{{ substr(Auth::user()->nom, 0, 1) }}</span>
                    </button>
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100 z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-600 hover:bg-red-50 focus:outline-none font-bold">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-12">
        @php
            $isSuccess = ($tentative->score_obtenu ?? 0) >= $qcm->score_reussite;
        @endphp

        <!-- Hero Section: Résultats -->
        <section class="bg-white border border-slate-200 rounded-[2rem] p-8 md:p-12 mb-10 overflow-hidden relative shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                <!-- Score Circle -->
                <div class="relative size-48 flex-none flex flex-col items-center justify-center">
                    <svg class="absolute inset-0 size-full -rotate-90" viewBox="0 0 192 192">
                        <circle cx="96" cy="96" r="88" fill="none" stroke="currentColor" stroke-width="8" class="{{ $isSuccess ? 'text-emerald-500/10' : 'text-red-500/10' }}" />
                        <circle cx="96" cy="96" r="88" fill="none" stroke="currentColor" stroke-width="8"
                            class="{{ $isSuccess ? 'text-emerald-500' : 'text-red-500' }}" stroke-dasharray="552.92" stroke-dashoffset="{{ 552.92 - (552.92 * (($tentative->score_obtenu ?? 0) / 100)) }}"
                            stroke-linecap="round" />
                    </svg>
                    <div class="relative z-10 flex flex-col items-center">
                        <span class="text-4xl font-black font-heading text-slate-900 leading-tight">{{ $tentative->score_obtenu ?? 0 }}%</span>
                        <span class="text-[10px] font-bold {{ $isSuccess ? 'text-emerald-600' : 'text-red-600' }} uppercase tracking-widest">{{ $isSuccess ? 'Objectif Validé' : 'Non Validé' }}</span>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-[10px] font-bold uppercase tracking-widest">Évaluation Terminée</span>
                    <h1 class="text-3xl font-heading font-bold text-slate-900 mt-4 leading-tight">{{ $qcm->titre }}</h1>
                    <p class="mt-4 text-slate-500 leading-relaxed font-medium">
                        {{ $isSuccess ? 'Félicitations ' . Auth::user()->prenom . ' ! Vous avez validé '. ($qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'ce test') .' avec succès.' : 'Dommage ' . Auth::user()->prenom . ', l\'objectif n\'a pas été atteint. Revoyez vos erreurs ci-dessous.' }}
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                        <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Terminé le</span>
                            <span class="text-sm font-bold text-slate-800">{{ $tentative->date_fin->format('d M Y à H:i') }}</span>
                        </div>
                        <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Durée approx.</span>
                            <span class="text-sm font-bold text-slate-800">{{ max(1, $tentative->date_fin->diffInMinutes($tentative->date_debut)) }} min</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Liste des Questions / Réponses -->
        <section class="space-y-6">
            <h2 class="text-xl font-heading font-bold text-slate-900 mb-6">Révision des réponses</h2>

            @foreach($questionDetails as $index => $question)
            <div class="bg-white border {{ $question->isCorrect ? 'border-emerald-200' : 'border-red-200' }} rounded-2xl p-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4">
                    <span class="{{ $question->isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} p-1 rounded-full inline-block">
                        @if($question->isCorrect)
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                        @else
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                        @endif
                    </span>
                </div>
                <h3 class="font-bold text-slate-800 pr-10">Q{{ $index + 1 }}. {{ $question->texte }}</h3>
                <div class="mt-6 space-y-2">
                    @foreach($question->options as $option)
                        @php
                            $classes = "bg-white border border-slate-200 p-4 rounded-xl text-sm font-medium text-slate-600"; // default
                            $badge = "";

                            if ($option->isSelected && $option->est_correcte) {
                                $classes = "bg-emerald-50 border border-emerald-500 p-4 rounded-xl text-sm font-bold text-emerald-800 flex justify-between items-center group";
                                $badge = "<span class='text-xs font-bold uppercase tracking-widest text-emerald-600'>Ma réponse — Correcte</span>";
                            } elseif ($option->isSelected && !$option->est_correcte) {
                                $classes = "bg-red-50 border border-red-500 p-4 rounded-xl text-sm font-bold text-red-800 flex justify-between items-center";
                                $badge = "<span class='text-xs font-bold uppercase tracking-widest text-red-600'>Ma réponse — Fausse</span>";
                            } elseif (!$option->isSelected && $option->est_correcte) {
                                $classes = "bg-emerald-50 border border-emerald-500 p-4 rounded-xl text-sm font-bold text-emerald-800 flex justify-between items-center";
                                $badge = "<span class='text-xs font-bold uppercase tracking-widest text-emerald-600'>Réponse attendue</span>";
                            }
                        @endphp
                        <div class="{!! $classes !!}">
                            {{ $option->texte }}
                            {!! $badge !!}
                        </div>
                    @endforeach
                </div>
                
                @if($question->explication)
                <div class="mt-6 pt-4 border-t border-slate-50">
                    <p class="text-xs font-medium text-slate-400 flex items-start gap-2 italic">
                        <svg class="size-4 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Explication : {{ $question->explication }}
                    </p>
                </div>
                @endif
            </div>
            @endforeach
        </section>

        <!-- CTA footer results -->
        <footer class="mt-16 flex justify-center">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-x-2 px-8 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 group">
                <svg class="size-4 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Revenir au tableau de bord
            </a>
        </footer>
    </main>
</div>
@endsection
