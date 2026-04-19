@extends('components.layout.app')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Header / Navbar Apprenant -->
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-white border-b border-slate-200 sticky top-0">
        <nav class="relative max-w-7xl w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3">
            <!-- Logo -->
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

            <!-- Navbar Center -->
            <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
                <a href="{{ route('student.dashboard') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Dashboard</a>
                <a href="{{ route('student.bibliotheque') }}" class="font-bold text-primary-600 focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Bibliothèque</a>
            </div>

            <!-- User Dropdown -->
            <div class="flex items-center gap-x-3 ms-auto md:col-span-3 justify-end">
                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-avatar" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 focus:outline-none p-1 pr-3 transition-all active:scale-95">
                        <img class="inline-block size-8 rounded-full shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom . ' ' . Auth::user()->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                        <span class="hidden md:inline-block text-slate-800 font-heading font-bold text-sm">{{ Auth::user()->prenom }}.{{ substr(Auth::user()->nom, 0, 1) }}</span>
                    </button>
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100 z-50">
                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-800 hover:bg-slate-50 font-medium" href="#">Paramètres</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-600 hover:bg-red-50 focus:outline-none font-bold">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-heading font-bold text-slate-900 tracking-tight leading-tight">Mes Évaluations</h1>
                <p class="text-slate-500 mt-2 font-medium">Retrouvez l'historique complet de vos passages et vos QCM à réaliser.</p>
            </div>
            @if (session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 text-sm font-bold shadow-sm">
                {{ session('error') }}
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Stats -->
            <aside class="col-span-1 lg:col-span-3 space-y-8">
                <div class="bg-primary-900 p-6 rounded-2xl shadow-xl shadow-primary-900/10 text-white">
                    <h3 class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-4">Statistiques Globales</h3>
                    <div class="space-y-6">
                        <div>
                            <span class="text-4xl font-black font-heading tracking-tighter">{{ $moyenne }}<span class="text-primary-400">%</span></span>
                            <p class="text-xs text-primary-300/80 font-bold uppercase mt-1">Ma moyenne</p>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                            <span class="text-xs font-bold text-primary-200">QCM Terminés</span>
                            <span class="text-xl font-bold font-heading">{{ $termines->count() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">À réaliser</h3>
                    <div class="text-3xl font-black text-slate-900 font-heading">{{ $aFaire->count() }}</div>
                </div>
            </aside>

            <!-- Main History List -->
            <section class="col-span-1 lg:col-span-9">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-10 shadow-sm">
                    <div class="space-y-8">
                        @if($enCours->count() === 0 && $aFaire->count() === 0 && $termines->count() === 0)
                            <div class="text-center py-10">
                                <p class="text-slate-500">Aucun QCM disponible pour le moment.</p>
                            </div>
                        @endif

                        {{-- QCMs à faire ou en cours --}}
                        @foreach(collect()->merge($enCours)->merge($aFaire) as $qcm)
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 rounded-2xl hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 border-l-4 border-amber-500 bg-amber-50/10 mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col text-amber-900/70">
                                        <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ $qcm->titre }}</h3>
                                        <span class="text-xs text-amber-600 font-medium italic">
                                            {{ $qcm->etat === 'en_cours' ? 'Reprise possible (En cours)' : 'Nouveau QCM disponible' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6 justify-between md:justify-end">
                                    <span class="px-4 py-1.5 bg-white border border-amber-200 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                                        {{ $qcm->etat === 'en_cours' ? 'En attente' : 'À faire' }}
                                    </span>
                                    <a href="{{ route('student.passation', $qcm->id) }}" class="px-5 py-2 inline-flex items-center gap-x-2 bg-amber-500 text-white font-bold rounded-xl text-xs hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m5 3 14 9-14 9V3z" />
                                        </svg>
                                        {{ $qcm->etat === 'en_cours' ? 'Reprendre' : 'Commencer' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        {{-- QCMs terminés --}}
                        @foreach($termines as $qcm)
                            @php
                                $isSuccess = ($qcm->score ?? 0) >= ($qcm->score_reussite ?? 50);
                                $borderColor = $isSuccess ? 'border-emerald-500' : 'border-red-500';
                                $bgColor = $isSuccess ? 'bg-emerald-50/10' : 'bg-red-50/10';
                                $iconBg = $isSuccess ? 'bg-emerald-100' : 'bg-red-100';
                                $iconColor = $isSuccess ? 'text-emerald-600' : 'text-red-600';
                                $badgeClass = $isSuccess ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-red-500 shadow-red-500/20';
                            @endphp
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 rounded-2xl hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 border-l-4 {{ $borderColor }} {{ $bgColor }} mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-12 rounded-xl {{ $iconBg }} flex items-center justify-center {{ $iconColor }} shrink-0">
                                        @if($isSuccess)
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M5 13l4 4L19 7" /></svg>
                                        @else
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ $qcm->titre }}</h3>
                                        <span class="text-xs text-slate-400 font-medium">Validé le {{ $qcm->date_fin->format('d M Y à H:i') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6 justify-between md:justify-end">
                                    <span class="px-4 py-1.5 {{ $badgeClass }} text-white rounded-full text-xs font-black shadow-lg">{{ $qcm->score }}%</span>
                                    <a href="{{ route('student.resultats', $qcm->id) }}" class="flex items-center gap-1.5 text-slate-400 font-bold text-sm hover:text-primary-500 transition-colors uppercase tracking-widest">
                                        Détails <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </section>
        </div>
    </main>
</div>
@endsection
