@extends('layouts.app')

@section('title', 'Modifier QCM - SoliQuiz')

@section('page-title', 'Studio de Modification')

@section('content')
<div class="fade-in pb-32" x-data="qcmBuilder({{ Js::from($unites) }}, {{ Js::from($classes) }}, {{ Js::from($qcm) }}, {{ Js::from(old()) }})">
    
    <!-- Header: Navigation & Breadcrumbs -->
    <div class="mb-8 space-y-6">
        <x-ui.breadcrumb :items="[
            'Bibliothèque' => (auth()->user()->isAdmin() ? route('admin.qcms') : route('formateur.bibliotheque')), 
            'Modification QCM' => '#'
        ]" />

        <a href="{{ auth()->user()->isAdmin() ? route('admin.qcms') : route('formateur.bibliotheque') }}" 
           class="inline-flex items-center gap-2 text-slate-400 hover:text-primary-600 transition-colors group">
            <div class="size-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:border-primary-200 group-hover:bg-primary-50 transition-all">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
        </a>
    </div>

    <!-- Action Form -->
    <form id="qcmForm" action="{{ route('formateur.qcm.update', $qcm->id) }}" method="POST" @submit.prevent="handleSubmit" class="flex flex-col lg:flex-row gap-6 xl:gap-8 items-start" novalidate>
        @csrf
        @method('PUT')
        
        @if($errors->any() || session('error'))
            <div x-data x-init="$nextTick(() => { $dispatch('open-modal', 'validation-errors'); })"></div>
            <template x-teleport="body">
                <x-ui.modal name="validation-errors" maxWidth="md">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-rose-100 rounded-xl flex items-center justify-center">
                            <svg class="size-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800">Erreurs de validation</h3>
                    </div>
                    <div class="bg-rose-50 rounded-xl p-4 border border-rose-100 mb-6">
                        <ul class="text-[11px] font-bold text-rose-600 space-y-2 list-disc list-inside">
                            @if(session('error'))
                                <li>{{ session('error') }}</li>
                            @endif
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" @click="$dispatch('close-modal', 'validation-errors')"
                        class="w-full py-3 rounded-lg font-bold text-xs uppercase tracking-wider bg-slate-900 text-white hover:bg-slate-800 transition-all">
                        Compris
                    </button>
                </x-ui.modal>
            </template>
        @endif
        
        <!-- Main Column: Content (Questions) -->
        <div class="flex-1 w-full space-y-8">
            <!-- Main Title Box -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 relative overflow-hidden">
                <div class="-top-6 absolute right-0 p-8 opacity-5">
                    <svg class="size-32" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="m9 15 2 2 4-4"/></svg>
                </div>
                <div class="relative z-10 space-y-3">
                    <label class="text-label ml-1">Identité de l'évaluation</label>
                    <input type="text" name="titre" required x-model="titre" 
                           class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-3 px-4 text-xl font-black text-slate-900 placeholder:text-slate-200 focus:bg-white focus:border-primary-500 transition-all outline-none uppercase" 
                           placeholder="Saisir le titre du QCM...">
                    @error('titre')
                        <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                            <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

        <!-- Questions Dynamic Section -->
        <div class="space-y-5">
            <template x-for="(question, qIndex) in questions" :key="qIndex">
                <article class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-premium transition-all duration-500 overflow-hidden" 
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8">
                    
                    <!-- Question Sub-Header -->
                    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                        <div class="flex items-center gap-6">
                            <div class="size-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-sm font-black" x-text="qIndex + 1"></div>
                            <div class="flex flex-col">
                                <span class="text-label">Configuration de l'item</span>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-[10px] font-black text-primary-500 uppercase tracking-widest" x-text="question.type === 'choix_unique' ? 'Réponse Unique' : 'Réponses Multiples'"></span>
                                    <span class="size-1 rounded-full bg-slate-200"></span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="`${question.points} Points`"></span>
                                </div>
                            </div>
                        </div>
                        <button @click="removeQuestion(qIndex)" type="button" class="p-3 text-slate-300 hover:text-rose-500 transition-colors">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>

                    <div class="p-4 md:p-6 space-y-5">
                        <!-- Points & Statement -->
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-24 shrink-0 space-y-3">
                                <label class="text-label ml-1">Points</label>
                                <input type="number" x-model.number="question.points" :name="'questions[' + qIndex + '][points]'" 
                                       class="w-full py-3 rounded-2xl border-none bg-slate-50 font-black text-xl text-slate-900 text-center focus:bg-white transition-all outline-none">
                            </div>
                            <div class="flex-1 space-y-3">
                                <label class="text-label ml-1">Énoncé Pédagogique</label>
                                <textarea x-model="question.texte" :name="'questions[' + qIndex + '][texte]'" required 
                                          class="w-full py-2.5 px-4 rounded-xl bg-slate-50 border-none font-bold text-sm text-slate-900 placeholder:text-slate-200 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all outline-none resize-none" 
                                          rows="2" placeholder="Formuler la question..."></textarea>
                            </div>
                        </div>

<!-- Options Management -->
                        <div class="space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                                <div class="flex items-center gap-3">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Options Strategiques</h4>
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase" 
                                          :class="question.type === 'choix_unique' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600'"
                                          x-text="question.type === 'choix_unique' ? 'UNIQUE' : 'MULTIPLE'"></span>
                                </div>
                                <div class="relative" x-data="{ 
                                        open: false,
                                        options: [
                                            { value: 'choix_unique', label: 'Réponse Unique' },
                                            { value: 'choix_multiple', label: 'Réponses Multiples' }
                                        ],
                                        get selectedOption() {
                                            return this.options.find(opt => opt.value === question.type) || this.options[0];
                                        },
                                        select(value) {
                                            question.type = value;
                                            open = false;
                                        }
                                    }" @click.away="open = false">
                                    <!-- Hidden input for form submission -->
                                    <input type="hidden" :name="'questions[' + qIndex + '][type]'" x-model="question.type">
                                    
                                    <!-- Dropdown Trigger -->
                                    <button @click="open = !open" 
                                            type="button"
                                            :class="open ? 'border-primary-500 bg-white ring-4 ring-primary-500/10 text-primary-600' : 'border-slate-100 bg-slate-50/50 text-slate-700'"
                                            class="w-56 border-2 rounded-xl py-3 px-5 text-[11px] font-black uppercase tracking-wider outline-none active:scale-98 transition-all flex items-center justify-between group hover:bg-slate-50 hover:border-slate-200 shadow-sm">
                                        <span x-text="selectedOption.label"></span>
                                        <svg class="size-4 text-slate-400 group-hover:text-primary-500 transition-transform duration-300 shrink-0 ml-2" 
                                             :class="open ? 'rotate-180 text-primary-500' : ''" 
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                         class="absolute z-50 w-56 mt-2 bg-white border border-slate-100/80 rounded-2xl shadow-2xl shadow-slate-200/60 overflow-hidden py-1.5">
                                        
                                        <div>
                                            <template x-for="option in options" :key="option.value">
                                                <button @click.stop="question.type = option.value; open = false"
                                                        type="button"
                                                        class="w-full px-4 py-3 text-left flex items-center gap-3 transition-colors text-[11px] font-bold tracking-wider uppercase"
                                                        :class="question.type === option.value ? 'bg-primary-50/80 text-primary-600 font-black' 
                                                            : 'text-slate-600 hover:bg-slate-50/80 hover:text-slate-900'">
                                                    <span x-text="option.label"></span>
                                                    <svg x-show="question.type === option.value" 
                                                         class="size-4 text-primary-500 ml-auto animate-in zoom-in duration-300" 
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-[9px] font-black uppercase tracking-[0.25em]" 
                                      :class="question.type === 'choix_unique' ? 'text-blue-500' : 'text-purple-500'"
                                      x-text="question.type === 'choix_unique' ? 'Selectionnez la reponse correcte (radio)' : 'Selectionnez TOUTES les reponses correctes (checkbox)'">
                                </span>
                                <span x-show="question.type === 'choix_multiple'" class="text-[9px] text-purple-500 font-bold animate-pulse">
                                    (Plusieurs choix possibles)
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <template x-for="(opt, oIndex) in question.options" :key="oIndex">
                                    <div class="group/opt p-6 bg-slate-50/50 rounded-2xl border-2 transition-all duration-300 space-y-4 shadow-xs"
                                         :class="opt.est_correcte ? 'border-emerald-400 bg-emerald-50/50' : 'border-transparent hover:border-primary-100 hover:bg-white'">
                                        <div class="flex items-center gap-4">
                                            <!-- Dynamic correct answer button - changes behavior based on question type -->
                                            <button @click="toggleCorrectOption(qIndex, oIndex)" type="button"
                                                    class="shrink-0 transition-all"
                                                    :class="question.type === 'choix_unique' ? 'w-7 h-7 rounded-full' : 'w-7 h-7 rounded-lg'">
                                                <div class="w-full h-full flex items-center justify-center transition-all"
                                                     :class="opt.est_correcte ? 'bg-emerald-500 text-white' : 'bg-white border-2 border-slate-200 text-transparent'">
                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                            </button>
                                            
                                            <input type="hidden" :name="'questions[' + qIndex + '][options][' + oIndex + '][est_correcte]'" :value="opt.est_correcte ? '1' : '0'">
                                            
                                            <input type="text" required x-model="opt.texte" :name="'questions[' + qIndex + '][options][' + oIndex + '][texte]'" 
                                                   placeholder="Option de reponse..." class="flex-1 bg-transparent border-none p-0 font-bold text-slate-800 text-base placeholder:text-slate-200 focus:ring-0 outline-none">
                                            
                                            <button @click="removeOption(qIndex, oIndex)" type="button" class="text-slate-200 hover:text-rose-500 transition-colors opacity-0 group-hover/opt:opacity-100">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                        <input type="text" x-model="opt.feedback_specifique" :name="'questions[' + qIndex + '][options][' + oIndex + '][feedback_specifique]'" 
                                               placeholder="Feedback correctif (optionnel)..." 
                                               class="w-full bg-white/50 border border-slate-100 rounded-xl py-2 px-4 text-[10px] font-bold text-slate-500 placeholder:text-slate-200 focus:bg-white transition-all outline-none">
                                    </div>
                                </template>

                                <button @click="addOption(qIndex)" type="button" 
                                        class="h-full min-h-[80px] border-2 border-dashed border-slate-100 rounded-2xl flex flex-col items-center justify-center gap-2 text-slate-300 hover:text-primary-500 hover:border-primary-200 hover:bg-primary-50/30 transition-all group">
                                    <svg class="size-6 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                    <span class="text-[9px] font-black uppercase tracking-[0.2em]">Inserer Option</span>
                                </button>
                            </div>
                        </div>

                            

                        <!-- Global Question Feedback -->
                        <div class="pt-8 border-t border-slate-50 flex gap-6 items-start">
                            <div class="size-10 rounded-2xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="flex-1 space-y-3">
                                <label class="text-label ml-1">Analyse Post-Passation</label>
                                <textarea x-model="question.explication_feedback" :name="'questions[' + qIndex + '][explication_feedback]'" 
                                          class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-slate-600 focus:bg-white transition-all outline-none resize-none" 
                                          rows="2" placeholder="Expliquer le raisonnement correct..."></textarea>
                            </div>
                        </div>
                    </div>
                </article>
            </template>

            <!-- Final Actions -->
            <div class="flex flex-col items-center gap-10 py-8">
                <button @click="addQuestion()" type="button" 
                        class="h-20 px-12 bg-white border border-slate-100 rounded-[2.5rem] font-black text-slate-900 uppercase tracking-[0.3em] shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all flex items-center gap-4">
                    <svg class="size-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                    Nouvelle Question
                </button>
            </div>
        </div>

        <!-- Sidebar Column: Configuration -->
        <div class="w-full lg:w-[640px] xl:w-[760px] shrink-0 flex flex-col lg:flex-row gap-6 sticky top-8 items-start">
            
            <!-- Settings Card -->
            <div class="w-full lg:w-1/2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-8">
                <div class="flex items-center justify-between pb-6 border-b border-slate-50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-900">Configuration</h3>
                </div>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="text-label ml-1">Disponibilité</label>
                        <x-ui.select 
                            name="statut" 
                            x-model="statut"
                            :selected="$qcm->statut"
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-3 !px-5 w-full"
                            :options="[
                                ['value' => 'brouillon', 'label' => 'Brouillon'],
                                ['value' => 'public', 'label' => 'Public'],
                                ['value' => 'termine', 'label' => 'Terminé'],
                            ]"
                        />
                        @error('statut')
                            <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                                <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="text-label ml-1">Module UA</label>
                        <x-ui.select 
                            name="unite_apprentissage_id" 
                            x-model="selectedUniteId" 
                            :selected="$qcm->unite_apprentissage_id"
                            required 
                            placeholder="Choisir l'UA"
                            jsOptions="allUnites.map(u => ({value: u.id, label: u.nom}))"
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-3 !px-5 w-full"
                        />
                        @error('unite_apprentissage_id')
                            <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                                <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="text-label ml-1">Cohorte Cible</label>
                        <x-ui.select 
                            name="classe_id" 
                            x-model="selectedClasseId"
                            :selected="$qcm->classe_id"
                            required
                            placeholder="Choisir une classe"
                            jsOptions="allClasses.map(c => ({value: c.id, label: c.nom}))"
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-3 !px-5 w-full"
                        />
                        @error('classe_id')
                            <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                                <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <label class="relative group cursor-pointer w-full flex items-center gap-3 bg-slate-50/50 hover:bg-slate-100/50 p-4 rounded-2xl border border-transparent transition-all">
                            <input type="checkbox" x-model="hasTimer" class="hidden">
                            <div :class="hasTimer ? 'border-primary-600 bg-primary-600' : 'border-slate-300 bg-white'"
                                 class="shrink-0 size-5 rounded flex items-center justify-center border-2 transition-all duration-300">
                                <svg :class="hasTimer ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                                     class="size-3 text-white transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                    <path d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-700 leading-snug">Limite de temps</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider mt-0.5" x-text="hasTimer ? 'Activée' : 'Désactivée (Temps Illimité)'"></span>
                            </div>
                        </label>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <label class="text-label ml-1">Durée (min)</label>
                                <input type="hidden" name="duree_minutes" :value="hasTimer ? dureeMinutes : 0">
                                <input type="number" x-model="dureeMinutes" :disabled="!hasTimer"
                                       class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-3 px-4 font-black text-slate-900 text-center text-lg focus:bg-white focus:border-primary-500 disabled:opacity-50 transition-all outline-none">
                                @error('duree_minutes')
                                    <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                                        <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label class="text-label ml-1">Réussite (pts)</label>
                                <input type="number" name="score_reussite" x-model="scoreReussite" required step="0.5"
                                       class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-3 px-4 font-black text-slate-900 text-center text-lg focus:bg-white focus:border-primary-500 transition-all outline-none">
                                @error('score_reussite')
                                    <p class="text-[9px] font-black text-rose-500 mt-2 ml-2 uppercase tracking-widest flex items-center">
                                        <svg class="size-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competences Card -->
            <div class="w-full lg:w-1/2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-900">Compétences</h3>
                    <span x-show="selectedUniteId" class="text-[10px] font-black text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg" x-text="`${filteredCompetences.length}`"></span>
                </div>
                
                <!-- Empty State: No UA selected -->
                <div x-show="!selectedUniteId" class="text-center py-8 px-4 text-slate-400 text-xs font-bold border-2 border-dashed border-slate-100 rounded-xl">
                    <svg class="size-8 mx-auto mb-2 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Sélectionnez d'abord une Unité d'Apprentissage (UA) pour afficher ses compétences associées.
                </div>

                <!-- Competences List -->
                <div x-show="selectedUniteId" class="flex flex-col gap-2.5 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="comp in filteredCompetences" :key="comp.id">
                        <label class="relative group cursor-pointer w-full">
                            <input type="checkbox" name="competence_ids[]" :value="comp.id" 
                                   :checked="selectedCompetences.includes(comp.id)"
                                   @click="if (selectedCompetences.includes(comp.id)) { selectedCompetences = selectedCompetences.filter(id => id != comp.id) } else { selectedCompetences.push(comp.id) }"
                                   class="hidden">
                            <div :class="selectedCompetences.includes(comp.id) ? 'border-primary-500 bg-primary-50/30' : 'border-transparent bg-slate-50/80'"
                                 class="w-full px-4 py-3 hover:bg-slate-100 rounded-2xl border transition-all flex items-start gap-3">
                                <div :class="selectedCompetences.includes(comp.id) ? 'border-primary-500 bg-primary-500' : 'border-slate-300 bg-white'"
                                     class="shrink-0 size-5 mt-0.5 rounded flex items-center justify-center border-2 transition-colors">
                                    <svg :class="selectedCompetences.includes(comp.id) ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
                                         class="size-3 text-white transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                        <path d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span :class="selectedCompetences.includes(comp.id) ? 'text-primary-600' : 'text-slate-400'"
                                          class="text-[10px] font-black transition-colors" x-text="comp.code"></span>
                                    <span class="text-xs font-bold text-slate-700 leading-snug mt-0.5 group-hover:text-slate-900 transition-colors" x-text="comp.libelle"></span>
                                </div>
                            </div>
                        </label>
                    </template>
                    <div x-show="filteredCompetences.length === 0" class="text-center py-6 text-slate-400 text-xs font-bold">
                        Aucune compétence disponible pour cette UA
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Floating Submit Button -->
        <div class="fixed bottom-4 left-1/2 -translate-x-1/2 z-[100] w-[95%] max-w-2xl animate-in slide-in-from-bottom duration-500">
            <div class="bg-slate-900/95 backdrop-blur-xl text-white rounded-[24px] py-3 px-6 shadow-2xl shadow-slate-900/40 flex items-center justify-between border border-white/10">
                <div class="flex items-center gap-6">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">État du Barème</span>
                        <span class="text-lg font-black leading-none" :class="totalPoints === 20 ? 'text-emerald-400' : 'text-amber-400'" x-text="`${totalPoints} / 20 PTS`"></span>
                    </div>
                </div>
                <button type="submit" class="h-12 px-10 bg-primary-500 text-white rounded-xl font-black uppercase tracking-widest text-sm hover:bg-primary-400 shadow-lg shadow-primary-500/20 transition-all flex items-center gap-3">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>

    <!-- Points Warning Modal -->
    <x-ui.modal name="points-warning" x-model:show="showPointsWarning" maxWidth="md">
        <div class="text-center p-8">
            <div class="size-20 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-8">
                <svg class="size-10 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-4">Total non-conforme</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-10">
                La somme actuelle est de <strong class="text-rose-600" x-text="totalPoints"></strong> points. 
                Le système requiert exactement <span class="font-black text-slate-900">20 points</span> pour validation.
            </p>
            
            <div class="grid grid-cols-1 gap-3">
                <button @click="showPointsWarning = false; equalizePoints()" type="button"
                        class="h-16 rounded-2xl bg-slate-900 text-white font-black uppercase tracking-widest text-xs hover:bg-primary-600 transition-all">
                    Équilibrer Automatiquement
                </button>
                <button @click="isSubmitting = true; window.onbeforeunload = null; showPointsWarning = false; $nextTick(() => document.getElementById('qcmForm').submit())" type="button"
                        class="h-14 rounded-2xl bg-white border border-slate-100 font-black text-rose-500 uppercase tracking-widest text-[10px] hover:bg-rose-50 transition-all">
                    Ignorer et Enregistrer
                </button>
            </div>
        </div>
    </x-ui.modal>
</div>


@endsection
