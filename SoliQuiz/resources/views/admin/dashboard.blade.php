@extends('components.layout.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Header / Navbar Admin -->
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-slate-900 border-b border-slate-800 sticky top-0">
        <nav class="relative max-w-7xl w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3">
            <div class="md:col-span-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('admin.dashboard') }}">
                    <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-white tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-0.5">Admin</span>
                    </div>
                </a>
            </div>

            <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-white focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Opérations</a>
                <a href="{{ route('admin.utilisateurs') }}" class="font-bold text-slate-400 hover:text-white transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Utilisateurs</a>
                <a href="{{ route('admin.pedagogie') }}" class="font-bold text-slate-400 hover:text-white transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Pédagogie</a>
                <a href="{{ route('admin.classes') }}" class="font-bold text-slate-400 hover:text-white transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Classes</a>
            </div>

            <div class="flex items-center gap-x-3 md:gap-x-4 ms-auto md:col-span-3 justify-end relative text-white">
                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-avatar" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-slate-700 bg-slate-800 text-white shadow-sm hover:bg-slate-700 focus:outline-none p-1 pr-3 transition-all active:scale-95">
                        <img class="inline-block size-8 rounded-full shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom . ' ' . Auth::user()->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                        <span class="hidden md:inline-block font-heading font-bold text-sm">{{ Auth::user()->prenom }}.{{ substr(Auth::user()->nom, 0, 1) }}</span>
                    </button>
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-slate-900 shadow-xl rounded-2xl p-2 mt-2 border border-slate-800 z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-400 hover:bg-red-400/10 focus:outline-none font-bold">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-12">
        <!-- Dashboard Header -->
        <div class="mb-12 relative overflow-hidden bg-white border border-slate-200 p-10 rounded-[2.5rem] shadow-sm">
            <div class="absolute -top-10 -right-10 size-64 bg-slate-50 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase italic mb-2">Tableau de Bord</h1>
                    <p class="text-slate-500 font-medium text-lg">Indicateurs clés et opérations pédagogiques.</p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-indigo-50 p-4 px-6 rounded-2xl border border-indigo-100 flex flex-col">
                        <span class="text-[10px] font-black tracking-widest text-indigo-400 uppercase">Score Moyen</span>
                        <span class="text-2xl font-black text-indigo-900">{{ $kpis['score_moyen'] }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Formateurs</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $kpis['nb_formateurs'] }}</div>
            </div>
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Étudiants</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $kpis['nb_etudiants'] }}</div>
            </div>
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Classes Actives</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $kpis['nb_classes'] }}</div>
            </div>
            <div class="bg-white border border-emerald-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2 italic">QCM Publiés</h3>
                <div class="text-3xl font-black font-heading text-emerald-700">{{ $kpis['nb_qcms_publie'] }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Top QCMs -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight italic mb-6">Top QCM Actifs</h2>
                <div class="space-y-4">
                    @forelse($topQcms as $qcm)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-900">{{ $qcm->titre }}</span>
                            <span class="text-xs text-slate-500 font-medium">Par {{ $qcm->formateur->prenom }} {{ $qcm->formateur->nom }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-xl font-black text-primary-600">{{ $qcm->tentatives_count }}</span>
                            <span class="text-[9px] uppercase font-black text-slate-400 tracking-widest">Passages</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-sm text-slate-500 italic">Aucun QCM trouvé.</div>
                    @endforelse
                </div>
            </div>

            <!-- Tentatives récentes -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
                <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight italic mb-6">Passages Récents</h2>
                <div class="space-y-4">
                    @forelse($recentTentatives as $t)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-900">{{ $t->etudiant->prenom }} {{ $t->etudiant->nom }}</span>
                            <span class="text-xs text-slate-500 font-medium">{{ $t->qcm->titre }}</span>
                        </div>
                        <div class="text-right flex flex-col items-end">
                            @if($t->statut === 'reussi')
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider mb-1">Réussi</span>
                                <span class="font-bold text-sm text-slate-900">{{ $t->score_obtenu }}%</span>
                            @elseif($t->statut === 'echoue')
                                <span class="px-2 py-0.5 rounded-md bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-wider mb-1">Échoué</span>
                                <span class="font-bold text-sm text-slate-900">{{ $t->score_obtenu }}%</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-wider">En Cours</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-sm text-slate-500 italic">Aucune tentative récente.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Access Modules -->
        <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight italic mb-6 mt-12">Modules d'Administration</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <a href="{{ route('admin.pedagogie') }}" class="group bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-lg hover:border-primary-200 transition-all flex items-center gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 size-32 bg-indigo-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:bg-indigo-100 transition-colors"></div>
                <div class="size-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 relative z-10 group-hover:bg-indigo-500 group-hover:text-white transition-colors shadow-md">
                    <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xl font-heading font-black text-slate-900 uppercase italic group-hover:text-primary-600 transition-colors">Ingénierie Pédagogique</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">Gérez les Séances, UA et Compétences.</p>
                </div>
                <svg class="size-5 text-slate-300 ms-auto group-hover:text-primary-500 group-hover:translate-x-1 transition-all" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('admin.classes') }}" class="group bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-lg hover:border-primary-200 transition-all flex items-center gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 size-32 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:bg-emerald-100 transition-colors"></div>
                <div class="size-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 relative z-10 group-hover:bg-emerald-500 group-hover:text-white transition-colors shadow-md">
                    <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xl font-heading font-black text-slate-900 uppercase italic group-hover:text-primary-600 transition-colors">Gestion des Cohortes</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">Assignez les Formateurs et organisez les étudiants.</p>
                </div>
                <svg class="size-5 text-slate-300 ms-auto group-hover:text-primary-500 group-hover:translate-x-1 transition-all" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

    </main>
</div>
@endsection
