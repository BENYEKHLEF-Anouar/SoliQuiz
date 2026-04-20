@extends('layouts.app')

@section('title', 'Ingénierie Pédagogique - Formateur')

@section('content')
<div class="reveal active" x-data="{ 
    showSeanceModal: false, 
    showUaModal: false, 
    showCompModal: false, 
    activeSeanceId: null, 
    activeUaId: null 
}">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Espace Formateur</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Ingénierie Pédagogique</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Architecture <span class="text-primary-500">Pédagogique</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Organisez vos sessions, définissez vos unités d'apprentissage et fixez les compétences que vos étudiants devront maîtriser.
            </p>
        </div>
        
        <button @click="showSeanceModal = true"
            class="group btn-premium px-8 py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all flex items-center justify-center gap-3">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
            Nouvelle Session
        </button>
    </div>

    <!-- Alert Messages -->
    <div class="space-y-4 mb-10">
        @if(session('success'))
            <div class="glass border-emerald-100 bg-emerald-50/50 p-6 rounded-[24px] flex items-center gap-4 animate-in slide-in-from-top duration-500">
                <div class="size-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-0.5">Opération Réussie</p>
                    <p class="text-sm font-bold text-slate-900">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="glass border-rose-100 bg-rose-50/50 p-6 rounded-[24px] flex items-center gap-4 animate-in slide-in-from-top duration-500">
                <div class="size-10 bg-rose-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-600 mb-0.5">Attention</p>
                    <ul class="text-sm font-bold text-slate-900 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <!-- Hierarchy Tree -->
    <div class="space-y-6">
        @forelse($seances as $seance)
            <div x-data="{ expanded: false }" class="group" :key="{{ $seance->id }}">
                <div class="glass bg-white p-8 md:p-10 rounded-[48px] border border-slate-100 shadow-premium group-hover:shadow-2xl transition-all duration-500 relative overflow-hidden"
                     @click="expanded = !expanded" :class="expanded ? 'border-primary-200' : ''">
                    
                    <div class="flex items-center justify-between relative z-10 cursor-pointer">
                        <div class="flex items-center gap-10">
                            <div class="size-20 rounded-[28px] bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-500 transition-all duration-700"
                                 :class="expanded ? 'bg-primary-500 text-white rotate-180 scale-110 shadow-2xl shadow-primary-500/30' : ''">
                                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7" /></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-primary-500 uppercase tracking-[0.4em] block mb-3">Session Administrative</span>
                                <h2 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none group-hover:text-primary-600 transition-colors">
                                    {{ $seance->nom }}
                                </h2>
                                <div class="flex items-center gap-2 mt-4">
                                    <span class="size-1.5 rounded-full bg-slate-200"></span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $seance->date->format('d F Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-10">
                            <div class="text-right hidden sm:block">
                                <span class="block text-2xl font-black font-heading text-slate-900 leading-none">{{ $seance->unitesApprentissage->count() }}</span>
                                <span class="text-[9px] uppercase font-black text-slate-400 tracking-widest">Séquences UA</span>
                            </div>
                            
                            <form action="{{ route('formateur.pedagogie.seance.destroy', $seance->id) }}" method="POST"
                                  @click.stop onsubmit="return confirm('Supprimer cette session ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="size-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all hover:rotate-12">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Expansion: UAs -->
                    <div x-show="expanded" x-collapse>
                        <div class="mt-12 space-y-6 pl-0 md:pl-12 border-l-2 border-slate-50 ml-0 md:ml-8">
                            <div class="flex justify-between items-center mb-8">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Unités d'Apprentissage</h3>
                                <button @click.stop="showUaModal = true; activeSeanceId = {{ $seance->id }}"
                                        class="bg-white px-5 py-2 rounded-xl border border-slate-100 text-[10px] font-black text-primary-500 uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-sm">
                                    + Ajouter UA
                                </button>
                            </div>

                            @forelse($seance->unitesApprentissage as $ua)
                                <div x-data="{ expUa: false }" class="group/ua">
                                    <div class="bg-slate-50/70 p-8 rounded-[40px] border-2 border-transparent hover:border-primary-100 hover:bg-white transition-all duration-500 cursor-pointer shadow-sm hover:shadow-premium"
                                         @click.stop="expUa = !expUa">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-6">
                                                <div class="size-12 rounded-2xl bg-white border-2 border-slate-100 flex items-center justify-center text-slate-400 transition-all duration-500" :class="expUa ? 'bg-slate-900 border-slate-900 text-white scale-110' : ''">
                                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] font-black text-primary-500 uppercase tracking-[0.3em] mb-1 block">{{ $ua->code }}</span>
                                                    <h4 class="text-xl font-heading font-black text-slate-900 group-hover/ua:text-primary-600 transition-colors">{{ $ua->nom }}</h4>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-8">
                                                <div class="text-right hidden sm:block">
                                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] block">Contenu</span>
                                                    <span class="text-xs font-black text-slate-900">{{ $ua->competences->count() }} Compétences</span>
                                                </div>
                                                <form action="{{ route('formateur.pedagogie.ua.destroy', $ua->id) }}" method="POST"
                                                      @click.stop onsubmit="return confirm('Supprimer cette UA ?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="size-10 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Competences -->
                                        <div x-show="expUa" x-collapse>
                                            <div class="mt-8 pt-8 border-t border-slate-100 flex justify-between items-center mb-6">
                                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Maîtrise & Piliers</span>
                                                <button @click.stop="showCompModal = true; activeUaId = {{ $ua->id }}"
                                                        class="text-[9px] font-black text-emerald-500 hover:text-emerald-700 uppercase tracking-widest">
                                                    + Fixer Compétence
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                @forelse($ua->competences as $comp)
                                                    <div class="bg-white p-5 rounded-[24px] border border-slate-100 flex justify-between shadow-sm group/comp relative overflow-hidden">
                                                        <div class="relative z-10">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <span class="size-2 rounded-full bg-emerald-400"></span>
                                                                <span class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-lg border border-emerald-100">{{ $comp->code }}</span>
                                                            </div>
                                                            <p class="font-bold text-slate-900 text-sm leading-snug">{{ $comp->libelle }}</p>
                                                        </div>
                                                        <form action="{{ route('formateur.pedagogie.competence.destroy', $comp->id) }}"
                                                              method="POST" @click.stop onsubmit="return confirm('Retirer cette compétence ?');" class="relative z-10 self-start">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-slate-300 hover:text-rose-500 p-1 transition-colors">
                                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @empty
                                                    <p class="col-span-full text-center text-[10px] font-bold text-slate-300 italic uppercase py-4">Aucune compétence</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50/50 rounded-[32px] border-2 border-dashed border-slate-100">
                                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Aucune UA enregistrée</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass p-20 rounded-[50px] border-2 border-dashed border-slate-200 text-center">
                <div class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 border border-slate-100 shadow-inner">
                    <svg class="size-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">Ossature Vierge</h3>
                <p class="text-slate-500 font-medium italic">Commencez par créer votre première session pédagogique.</p>
            </div>
        @endforelse
    </div>

    <!-- Modals -->
    
    <!-- Modal: Seance -->
    <div x-show="showSeanceModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="showSeanceModal = false"></div>
        <div class="relative w-full max-w-[480px] bg-white rounded-[40px] shadow-2xl p-10 overflow-hidden animate-in zoom-in-95 duration-300">
            <div class="absolute top-0 right-0 size-32 bg-primary-50 rounded-full -mr-16 -mt-16"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-3xl font-heading font-black text-slate-900 uppercase leading-none">Nouvelle <span class="text-primary-500">Session</span></h3>
                    <button @click="showSeanceModal = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('formateur.pedagogie.seance.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Identification</label>
                        <input type="text" name="nom" required placeholder="Ex: Masterclass Laravel Architecture"
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-[28px] py-5 px-8 font-bold text-slate-900 focus:bg-white transition-all text-sm">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Planification</label>
                        <input type="date" name="date" required
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-[28px] py-5 px-8 font-bold text-slate-900 focus:bg-white transition-all text-sm">
                    </div>
                    
                    <button type="submit" class="w-full py-6 px-8 bg-slate-900 text-white font-black rounded-[32px] hover:bg-primary-500 shadow-2xl shadow-slate-900/10 active:scale-95 transition-all uppercase tracking-[0.3em] text-xs mt-4">
                        Consigner la Session
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: UA -->
    <div x-show="showUaModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="showUaModal = false"></div>
        <div class="relative w-full max-w-[480px] bg-white rounded-[40px] shadow-2xl p-10 animate-in zoom-in-95 duration-300">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-3xl font-heading font-black text-slate-900 uppercase leading-none">Ajouter <span class="text-primary-500">UA</span></h3>
                <button @click="showUaModal = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('formateur.pedagogie.ua.store') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="seance_id" :value="activeSeanceId">
                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Architecture</label>
                        <input type="text" name="code" placeholder="UA-XXX" required
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-[28px] py-5 px-8 font-bold text-slate-900 focus:bg-white transition-all text-sm uppercase font-mono tracking-widest">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Libellé Technique</label>
                        <input type="text" name="nom" placeholder="Ex: Middlewares & Auth" required
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-[28px] py-5 px-8 font-bold text-slate-900 focus:bg-white transition-all text-sm">
                    </div>
                </div>
                
                <button type="submit" class="w-full py-6 px-8 bg-slate-900 text-white font-black rounded-[32px] hover:bg-primary-500 shadow-2xl shadow-slate-900/10 active:scale-95 transition-all uppercase tracking-[0.3em] text-xs mt-4">
                    Fixer l'Unité
                </button>
            </form>
        </div>
    </div>

    <!-- Modal: Competence -->
    <div x-show="showCompModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="showCompModal = false"></div>
        <div class="relative w-full max-w-[520px] bg-white rounded-[40px] shadow-2xl p-10 animate-in zoom-in-95 duration-300">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-3xl font-heading font-black text-slate-900 uppercase leading-none">Nouvelle <span class="text-emerald-500">Compétence</span></h3>
                <button @click="showCompModal = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('formateur.pedagogie.competence.store') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="unite_apprentissage_id" :value="activeUaId">
                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Identification</label>
                        <input type="text" name="code" placeholder="C-XXX" required
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-[28px] py-5 px-8 font-bold text-slate-900 focus:bg-white transition-all text-sm uppercase font-mono tracking-widest">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Désignation du Pilier</label>
                        <input type="text" name="nom" placeholder="Ex: Implémenter un JWT" required
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-[28px] py-5 px-8 font-bold text-slate-900 focus:bg-white transition-all text-sm">
                    </div>
                </div>
                
                <button type="submit" class="w-full py-6 px-8 bg-emerald-500 text-white font-black rounded-[32px] hover:bg-emerald-600 shadow-2xl shadow-emerald-500/10 active:scale-95 transition-all uppercase tracking-[0.3em] text-xs mt-4">
                    Enregistrer le Pilier
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
