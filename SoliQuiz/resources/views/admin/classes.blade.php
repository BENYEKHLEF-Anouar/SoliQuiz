@extends('components.layout.app')

@section('content')
    <div class="bg-slate-50 min-h-screen pb-16"
        x-data="{ showClasseModal: false, showFormateurModal: false, activeClasseId: null, activeClasseName: '' }">
        <main class="max-w-7xl mx-auto px-4 py-12 space-y-8">
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200">
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
                    <h1 class="text-3xl font-heading font-black text-slate-900 uppercase italic tracking-tight">Gestion des
                        Cohortes</h1>
                    <p class="text-slate-500 font-medium">Assignez les formateurs et organisez les étudiants.</p>
                </div>
                <button @click="showClasseModal = true"
                    class="inline-flex justify-center items-center gap-x-2 px-5 py-3 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/20 uppercase tracking-widest text-xs">
                    + Nouvelle Classe
                </button>
            </div>

            <!-- Classes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($classes as $classe)
                    <div
                        class="bg-white rounded-[2rem] border border-slate-200 p-6 flex flex-col relative overflow-hidden transition-all hover:shadow-lg hover:shadow-slate-200">
                        <div class="flex justify-between items-start mb-6">
                            <span
                                class="inline-flex py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600">
                                {{ $classe->promotion ?? 'Formation' }}
                            </span>
                            <form action="{{ route('admin.classes.destroy', $classe->id) }}" method="POST"
                                onsubmit="return confirm('Supprimer définitivement cette classe ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-600 shrink-0"><svg class="size-5"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg></button>
                            </form>
                        </div>

                        <h3 class="text-2xl font-heading font-black text-slate-900 tracking-tight leading-tight mb-2">
                            {{ $classe->nom }}</h3>
                        <p class="text-sm font-medium text-slate-500 mb-6 flex items-center gap-2">
                            <svg class="size-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            <strong>{{ $classe->etudiants_count }}</strong> étudiants
                        </p>

                        <div class="mt-auto pt-8 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] italic block mb-1">Équipe Pédagogique</span>
                                @if($classe->formateur)
                                    <span class="text-xs font-bold text-slate-700">{{ $classe->formateur->prenom }} {{ $classe->formateur->nom }}</span>
                                @else
                                    <button @click="showFormateurModal = true; activeClasseId = {{ $classe->id }}; activeClasseName = '{{ addslashes($classe->nom) }}';" 
                                            class="text-primary-500 hover:text-primary-700 text-[10px] font-black uppercase tracking-widest transition-colors flex items-center gap-1.5">
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
                                        Assigner
                                    </button>
                                @endif
                            </div>
                            
                            <a href="{{ route('admin.classes.show', $classe->id) }}" 
                               class="inline-flex items-center gap-2 py-3 px-5 bg-neutral-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-neutral-900/10 active:scale-95 group/btn">
                                Gérer
                                <svg class="size-4 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-[2rem] border border-slate-200 text-center">
                        <p class="text-slate-500 font-medium">Aucune classe n'a été créée.</p>
                    </div>
                @endforelse
            </div>
        </main>

        <!-- Modal: Créer Classe -->
        <div x-show="showClasseModal" class="fixed inset-0 z-[100] max-h-full overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showClasseModal = false">
            </div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative w-full max-w-md transform overflow-hidden rounded-[2rem] bg-white p-6 md:p-8 text-left shadow-2xl transition-all">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-2xl font-bold font-heading text-slate-900 italic uppercase">Nouvelle Classe</h3>
                        <button @click="showClasseModal = false" class="text-slate-400 hover:text-slate-600"><svg
                                class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg></button>
                    </div>
                    <form action="{{ route('admin.classes.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nom de la Classe</label>
                                <input type="text" name="nom" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Promotion <span
                                        class="font-normal text-slate-400">(optionnel)</span></label>
                                <input type="text" name="promotion" placeholder="Ex: 2024, Promo Dev"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium">
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="w-full py-3 px-4 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-colors uppercase tracking-widest text-xs">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Assigner Formateur -->
        <div x-show="showFormateurModal" class="fixed inset-0 z-[100] max-h-full overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                @click="showFormateurModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative w-full max-w-md transform overflow-hidden rounded-[2rem] bg-white p-6 md:p-8 text-left shadow-2xl transition-all">
                    <div class="mb-2 flex justify-between items-center">
                        <h3 class="text-2xl font-bold font-heading text-slate-900 italic uppercase">Assigner Formateur</h3>
                        <button @click="showFormateurModal = false" class="text-slate-400 hover:text-slate-600"><svg
                                class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg></button>
                    </div>
                    <p class="text-slate-500 mb-6 text-sm">Classe: <strong class="text-slate-800"
                            x-text="activeClasseName"></strong></p>

                    <form :action="`/admin/classes/${activeClasseId}/formateur`" method="POST">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Sélectionnez le Formateur</label>
                            <select name="formateur_id" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-primary-500 focus:ring-primary-500 font-medium text-slate-800">
                                <option value="">Sélectionner...</option>
                                @foreach($formateurs as $formateur)
                                    <option value="{{ $formateur->id }}">{{ $formateur->prenom }} {{ $formateur->nom }}
                                        ({{ $formateur->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="w-full py-3 px-4 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-colors uppercase tracking-widest text-xs">Assigner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection