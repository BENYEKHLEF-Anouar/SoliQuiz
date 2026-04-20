@extends('layouts.app')

@section('title', 'Gestion des Cohortes - SoliQuiz')

@section('content')
<div class="reveal active" x-data="{ showClasseModal: false, showFormateurModal: false, activeClasseId: null, activeClasseName: '' }">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Ressources Humaines</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Gestion des Cohortes</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Équipes & <span class="text-primary-500">Promotions</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Supervisez les groupes d'apprentissage, connectez les formateurs et suivez le volume d'apprenants par section.
            </p>
        </div>
        
        <button @click="showClasseModal = true"
            class="group btn-premium px-8 py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all flex items-center justify-center gap-3">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
            Nouvelle Classe
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
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-600 mb-0.5">Erreur de Validation</p>
                    <ul class="text-sm font-bold text-slate-900 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($classes as $classe)
            <div class="glass bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm hover:shadow-premium transition-all relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 size-32 bg-primary-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="flex justify-between items-start mb-8 relative z-10">
                    <span class="inline-flex py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-900 text-white shadow-lg shadow-slate-900/10">
                        {{ $classe->promotion ?? 'Formation Régulière' }}
                    </span>
                    
                    <form action="{{ route('admin.classes.destroy', $classe->id) }}" method="POST"
                          onsubmit="return confirm('Démanteler définitivement cette classe ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </div>

                <div class="relative z-10 mb-8">
                    <h3 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none mb-3 group-hover:text-primary-600 transition-colors">
                        {{ $classe->nom }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <div class="size-6 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600 text-[10px] font-black">
                            {{ $classe->etudiants_count }}
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Effectif Total</span>
                    </div>
                </div>

                <div class="relative z-10 pt-8 border-t border-slate-50 flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Tuteur Académique</span>
                        @if($classe->formateur)
                            <div class="flex items-center gap-3">
                                <img class="size-8 rounded-lg" src="https://ui-avatars.com/api/?name={{ urlencode($classe->formateur->nom_complet) }}&background=f8fafc&color=0f172a&bold=true" alt="">
                                <span class="text-xs font-black text-slate-900">{{ $classe->formateur->nom_complet }}</span>
                            </div>
                        @else
                            <button @click="showFormateurModal = true; activeClasseId = {{ $classe->id }}; activeClasseName = '{{ addslashes($classe->nom) }}';" 
                                    class="group/assign inline-flex items-center gap-2 text-[10px] font-black text-primary-500 uppercase tracking-widest hover:text-primary-700 transition-colors">
                                <div class="size-6 bg-primary-50 rounded-lg flex items-center justify-center group-hover/assign:bg-primary-500 group-hover/assign:text-white transition-all">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                </div>
                                Assigner un expert
                            </button>
                        @endif
                    </div>
                    
                    <a href="{{ route('admin.classes.show', $classe->id) }}" 
                       class="size-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all group/go">
                        <svg class="size-6 group-hover/go:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full glass p-20 rounded-[50px] border-2 border-dashed border-slate-100 text-center">
                <div class="size-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="size-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2 italic">Aucune Cohorte</h3>
                <p class="text-slate-400 font-medium italic">Initialisez votre structure en créant votre premiere classe.</p>
            </div>
        @endforelse
    </div>

    <!-- Modals Layer -->
    
    <!-- Modal: Créer Classe -->
    <div x-show="showClasseModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="showClasseModal = false"></div>
        <div class="relative w-full max-w-[480px] bg-white rounded-[40px] shadow-2xl p-10 overflow-hidden animate-in zoom-in-95 duration-300">
            <div class="absolute top-0 right-0 size-32 bg-primary-50 rounded-full -mr-16 -mt-16"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-3xl font-heading font-black text-slate-900 uppercase">Nouvelle Classe</h3>
                    <button @click="showClasseModal = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Appellation du Groupe</label>
                        <input type="text" name="nom" required placeholder="Ex: Développement Digital 101"
                               class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Promotion <span class="text-slate-300">(Optionnel)</span></label>
                        <input type="text" name="promotion" placeholder="Ex: 2024 / Elite A"
                               class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                    </div>
                    
                    <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 shadow-2xl shadow-slate-900/10 active:scale-95 transition-all uppercase tracking-widest text-sm mt-4">
                        Établir la Séquence
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Assigner Formateur -->
    <div x-show="showFormateurModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="showFormateurModal = false"></div>
        <div class="relative w-full max-w-[480px] bg-white rounded-[40px] shadow-2xl p-10 animate-in zoom-in-95 duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-heading font-black text-slate-900 uppercase">Expertise</h3>
                <button @click="showFormateurModal = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-10">Assignation pour : <span class="text-primary-500" x-text="activeClasseName"></span></p>

            <form :action="`/admin/classes/${activeClasseId}/formateur`" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-4">
                    <label class="text-xs font-black text-slate-900 uppercase tracking-widest ml-4 italic underline underline-offset-4 decoration-primary-500/30">Catalogue des Experts</label>
                    <select name="formateur_id" required
                        class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all appearance-none cursor-pointer">
                        <option value="">Sélectionner un formateur...</option>
                        @foreach($formateurs as $f)
                            <option value="{{ $f->id }}">{{ $f->nom_complet }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm shadow-xl shadow-slate-900/10">
                    Fixer la Responsabilité
                </button>
            </form>
        </div>
    </div>
</div>
@endsection