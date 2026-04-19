@extends('components.layout.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16" x-data="{ 
    showSeanceModal: false, 
    showUaModal: false, 
    showCompModal: false, 
    activeSeanceId: null, 
    activeUaId: null 
}">
    <!-- Header Admin -->
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
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-slate-400 hover:text-white focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Opérations</a>
                <a href="{{ route('admin.utilisateurs') }}" class="font-bold text-slate-400 hover:text-white transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Utilisateurs</a>
                <a href="{{ route('admin.pedagogie') }}" class="font-bold text-white transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Pédagogie</a>
            </div>

            <div class="flex items-center gap-x-3 ms-auto md:col-span-3 justify-end relative text-white">
                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-avatar" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-slate-700 bg-slate-800 text-white shadow-sm hover:bg-slate-700 p-1 pr-3">
                        <img class="inline-block size-8 rounded-full shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom . ' ' . Auth::user()->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                        <span class="hidden md:inline-block font-heading font-bold text-sm">{{ Auth::user()->prenom }}.{{ substr(Auth::user()->nom, 0, 1) }}</span>
                    </button>
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-slate-900 shadow-xl rounded-2xl p-2 mt-2 border border-slate-800 z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-400 hover:bg-red-400/10 font-bold">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-12 space-y-8">

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200" role="alert">
            <span class="font-bold">Succès !</span> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 mb-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200">
            <ul class="list-disc pl-5 font-medium">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-heading font-black text-slate-900 uppercase italic tracking-tight">Ingénierie Pédagogique</h1>
                <p class="text-slate-500 font-medium">Structurez les séances, unités d'apprentissage et compétences.</p>
            </div>
            <button @click="showSeanceModal = true" class="inline-flex justify-center items-center gap-x-2 px-5 py-2.5 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/20 uppercase tracking-widest text-xs">
                + Nouvelle Séance
            </button>
        </div>

        <!-- Master Data Tree -->
        <div class="space-y-4">
            @forelse($seances as $seance)
            <!-- Seance Item -->
            <div x-data="{ expanded: false }" class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden transition-all">
                <div class="p-6 flex items-center justify-between cursor-pointer hover:bg-slate-50" @click="expanded = !expanded">
                    <div class="flex items-center gap-4">
                        <div class="size-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400" :class="expanded ? 'bg-primary-100 text-primary-600' : ''">
                            <svg class="size-5 transition-transform" :class="expanded ? 'rotate-90' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">SÉANCE</span>
                            <h2 class="text-xl font-heading font-bold text-slate-900 leading-none mt-1">{{ $seance->nom }}</h2>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-slate-500">{{ $seance->unitesApprentissage->count() }} UA</span>
                        
                        <!-- Delete Seance -->
                        <form action="{{ route('admin.pedagogie.seance.destroy', $seance->id) }}" method="POST" @click.stop onsubmit="return confirm('Supprimer la séance ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-600 p-2"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                        </form>
                    </div>
                </div>

                <!-- UAs List -->
                <div x-show="expanded" x-collapse class="border-t border-slate-100 bg-slate-50/50">
                    <div class="p-6 pl-16 space-y-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest">Unités d'Apprentissage (UA)</h3>
                            <button @click="showUaModal = true; activeSeanceId = {{ $seance->id }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest">+ Ajouter UA</button>
                        </div>

                        @forelse($seance->unitesApprentissage as $ua)
                        <div x-data="{ expUa: false }" class="bg-white rounded-2xl border border-slate-200">
                            <div class="p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50" @click="expUa = !expUa">
                                <div class="flex items-center gap-3">
                                    <div class="text-slate-400 transition-transform" :class="expUa ? 'rotate-90' : ''"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg></div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-indigo-50 text-indigo-600 border border-indigo-100">{{ $ua->code }}</span>
                                    <span class="font-bold text-slate-800">{{ $ua->nom }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold text-slate-500">{{ $ua->competences->count() }} Compétences</span>
                                    <form action="{{ route('admin.pedagogie.ua.destroy', $ua->id) }}" method="POST" @click.stop onsubmit="return confirm('Supprimer l\'UA ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-600"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Competences List -->
                            <div x-show="expUa" x-collapse class="border-t border-slate-100 bg-slate-50 p-4 pl-10">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Compétences visées</span>
                                    <button @click="showCompModal = true; activeUaId = {{ $ua->id }}" class="text-[10px] font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest">+ Ajouter Compétence</button>
                                </div>
                                <ul class="space-y-2">
                                    @forelse($ua->competences as $comp)
                                    <li class="bg-white px-4 py-3 rounded-xl border border-slate-200 flex justify-between items-start gap-4">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100">{{ $comp->code }}</span>
                                                <span class="font-bold text-slate-800 text-sm">{{ $comp->libelle }}</span>
                                            </div>
                                            @if($comp->description)
                                            <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $comp->description }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('admin.pedagogie.competence.destroy', $comp->id) }}" method="POST" onsubmit="return confirm('Supprimer la compétence ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-600 shrink-0"><svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                        </form>
                                    </li>
                                    @empty
                                    <li class="text-xs text-slate-500 italic">Aucune compétence définie.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        @empty
                        <div class="text-sm text-slate-500 italic p-4">Aucune unité d'apprentissage.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 bg-white rounded-[2rem] border border-slate-200 text-center">
                <p class="text-slate-500 font-medium">Aucune séance pédagogique n'a été créée.</p>
            </div>
            @endforelse
        </div>
    </main>

    <!-- Modals (Alpine based) -->
    
    <!-- Modal: Seance -->
    <div x-show="showSeanceModal" class="fixed inset-0 z-[100] max-h-full overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showSeanceModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md transform overflow-hidden rounded-[2rem] bg-white p-6 md:p-8 text-left shadow-2xl transition-all" @click.stop>
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-2xl font-bold font-heading text-slate-900 italic uppercase">Créer Séance</h3>
                    <button @click="showSeanceModal = false" class="text-slate-400 hover:text-slate-600"><svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
                </div>
                <form action="{{ route('admin.pedagogie.seance.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nom de la Séance</label>
                            <input type="text" name="nom" required class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Date prévue</label>
                            <input type="date" name="date" required class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="w-full py-3 px-4 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-colors uppercase tracking-widest text-xs">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: UA -->
    <div x-show="showUaModal" class="fixed inset-0 z-[100] max-h-full overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showUaModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-[2rem] bg-white p-6 md:p-8 text-left shadow-2xl">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-xl font-bold font-heading text-slate-900 italic uppercase">Ajouter UA</h3>
                    <button @click="showUaModal = false" class="text-slate-400 hover:text-slate-600"><svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
                </div>
                <!-- Dynamic form action relying on activeSeanceId -->
                <form :action="`/admin/pedagogie/seance/${activeSeanceId}/ua`" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Code de l'UA</label>
                            <input type="text" name="code" placeholder="Ex: UA-01" required class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium font-mono uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nom de l'UA</label>
                            <input type="text" name="nom" placeholder="Ex: Développement Front-end" required class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="w-full py-3 px-4 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-colors uppercase tracking-widest text-xs">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Competence -->
    <div x-show="showCompModal" class="fixed inset-0 z-[100] max-h-full overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showCompModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-[2rem] bg-white p-6 md:p-8 text-left shadow-2xl">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-xl font-bold font-heading text-slate-900 italic uppercase">Ajouter Compétence</h3>
                    <button @click="showCompModal = false" class="text-slate-400 hover:text-slate-600"><svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
                </div>
                <form :action="`/admin/pedagogie/ua/${activeUaId}/competence`" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Code</label>
                            <input type="text" name="code" placeholder="Ex: COMP-1" required class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium font-mono uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Libellé</label>
                            <input type="text" name="libelle" required class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Description <span class="font-normal text-slate-400">(optionnel)</span></label>
                            <textarea name="description" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium"></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="w-full py-3 px-4 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-colors uppercase tracking-widest text-xs">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
