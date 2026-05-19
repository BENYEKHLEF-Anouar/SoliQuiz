@extends('layouts.app')

@section('title', 'Créer un QCM - SoliQuiz')

@section('page-title', 'Studio de Création')

@section('content')
<div class="fade-in pb-32" x-data="qcmBuilder({{ Js::from($unites) }}, {{ Js::from($classes) }})">
    
    <!-- Header: Navigation -->
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.qcms') : route('formateur.bibliotheque') }}" 
           class="inline-flex items-center gap-2 text-slate-400 hover:text-primary-600 transition-colors group">
            <div class="size-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:border-primary-200 group-hover:bg-primary-50 transition-all">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
        </a>
    </div>

    <!-- Action Form -->
    <form id="qcmForm" action="{{ route('formateur.qcm.store') }}" method="POST" @submit.prevent="handleSubmit" class="flex flex-col lg:flex-row gap-6 xl:gap-8 items-start">
        @csrf
        
        @if($errors->any())
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[110] w-[90%] max-w-2xl animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-rose-50 border-2 border-rose-100 rounded-2xl p-4 flex items-start gap-3 shadow-xl">
                <svg class="size-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-xs font-black text-rose-900 uppercase tracking-widest mb-1">Erreurs de validation</h4>
                    <ul class="text-[10px] font-bold text-rose-600 space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" @click="$el.closest('.fixed').remove()" class="text-rose-400 hover:text-rose-600 p-1">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endif
        
        <!-- Main Column: Content (Questions) -->
        <div class="flex-1 w-full space-y-8">
            <!-- Main Title Box -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 md:p-6 relative overflow-hidden">
                <div class="-top-8 absolute right-0 p-8 opacity-5">
                    <svg class="size-32" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="m9 15 2 2 4-4"/></svg>
                </div>
                <div class="relative z-10 space-y-3">
                    <label class="text-label ml-1">Identité de l'évaluation</label>
                    <input type="text" name="titre" required x-model="titre" 
                           class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-3 px-4 text-xl font-black text-slate-900 placeholder:text-slate-200 focus:bg-white focus:border-primary-500 transition-all outline-none uppercase italic" 
                           placeholder="Saisir le titre du QCM...">
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
                            <div class="size-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-sm font-black italic" x-text="qIndex + 1"></div>
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

                    <div class="p-4 md:p-6 space-y-8">
                        <!-- Points & Statement -->
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-24 shrink-0 space-y-3">
                                <label class="text-label ml-1">Barème</label>
                                <input type="number" x-model.number="question.points" :name="'questions[' + qIndex + '][points]'" 
                                       @input="totalPoints !== 20"
                                       class="w-full py-3 rounded-2xl border-none bg-slate-50 font-black text-xl text-slate-900 text-center focus:bg-white transition-all outline-none"
                                       title="Nombre de points attribués à cette question">
                                <p class="text-[8px] text-center font-bold text-slate-400 uppercase tracking-tighter">/ 20 total</p>
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
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Options Strategiques</h4>
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
                                            class="w-52 bg-slate-50 border-2 border-transparent rounded-xl py-2.5 px-4 text-xs font-black uppercase text-slate-700 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all flex items-center justify-between group">
                                        <span x-text="selectedOption.label"></span>
                                        <svg class="size-4 text-slate-400 group-hover:text-primary-500 transition-transform duration-300 shrink-0 ml-2" 
                                             :class="open ? 'rotate-180' : ''" 
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
                                         class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-premium overflow-hidden">
                                        
                                        <div class="py-2">
                                            <template x-for="option in options" :key="option.value">
                                                <button @click="select(option.value)"
                                                        type="button"
                                                        class="w-full px-4 py-3 text-left flex items-center gap-3 transition-all"
                                                        :class="question.type === option.value 
                                                            ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-500' 
                                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                                    <span class="text-xs font-black uppercase tracking-wider" x-text="option.label"></span>
                                                    <svg x-show="question.type === option.value" 
                                                         class="size-4 text-primary-500 ml-auto" 
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
                                                    <svg x-show="question.type === 'choix_unique'" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                                                    <svg x-show="question.type === 'choix_multiple'" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
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
                                               class="w-full bg-white/50 border border-slate-100 rounded-xl py-2 px-4 text-[10px] font-bold text-slate-500 italic placeholder:text-slate-200 focus:bg-white transition-all outline-none">
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
                        class="h-14 px-8 bg-white border border-slate-100 rounded-2xl font-black text-slate-900 uppercase tracking-[0.2em] italic shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-3">
                    <svg class="size-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                    Nouvelle Question
                </button>
            </div>
        </div>

        <!-- Sidebar Column: Configuration -->
        <div class="w-full lg:w-[320px] xl:w-[380px] shrink-0 space-y-6 sticky top-8">
            
            <!-- Settings Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-8">
                <div class="flex items-center justify-between pb-6 border-b border-slate-50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-900">Configuration</h3>
                </div>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="text-label ml-1">Disponibilité</label>
                        <x-ui.select 
                            name="statut" 
                            x-model="statut"
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-3 !px-5 w-full"
                            :options="[
                                ['value' => 'brouillon', 'label' => 'Brouillon'],
                                ['value' => 'public', 'label' => 'Public'],
                                ['value' => 'termine', 'label' => 'Terminé'],
                            ]"
                        />
                    </div>

                    <div class="space-y-3">
                        <label class="text-label ml-1">Unité d'Apprentissage (UA)</label>
                        <x-ui.select 
                            name="unite_apprentissage_id" 
                            x-model="selectedUniteId" 
                            required 
                            placeholder="Choisir l'UA"
                            jsOptions="allUnites.map(u => ({value: u.id, label: u.nom}))"
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-3 !px-5 w-full"
                        />
                    </div>

                    <div class="space-y-3">
                        <label class="text-label ml-1">Cohorte Cible</label>
                        <x-ui.select 
                            name="classe_id" 
                            x-model="selectedClasseId"
                            required
                            placeholder="Choisir une classe"
                            jsOptions="allClasses.map(c => ({value: c.id, label: c.nom}))"
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-3 !px-5 w-full"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <label class="text-label ml-1">Durée (min)</label>
                            <input type="number" name="duree_minutes" required value="30"
                                   class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-3 px-4 font-black text-slate-900 text-center text-lg focus:bg-white focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="space-y-3">
                            <label class="text-label ml-1">Réussite (pts)</label>
                            <input type="number" name="score_reussite" required step="0.5" value="10"
                                   class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-3 px-4 font-black text-slate-900 text-center text-lg focus:bg-white focus:border-primary-500 transition-all outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competences Card -->
            <div x-show="selectedUniteId" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-900">Compétences</h3>
                    <span class="text-[10px] font-black text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg" x-text="`${filteredCompetences.length}`"></span>
                </div>
                <div class="flex flex-col gap-2.5 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="comp in filteredCompetences" :key="comp.id">
                        <label class="relative group cursor-pointer w-full">
                            <input type="checkbox" name="competence_ids[]" :value="comp.id" class="peer hidden">
                            <div class="w-full px-4 py-3 bg-slate-50/80 hover:bg-slate-100 rounded-2xl border border-transparent peer-checked:border-primary-500 peer-checked:bg-primary-50/30 transition-all flex items-start gap-3">
                                <div class="shrink-0 size-5 mt-0.5 rounded flex items-center justify-center border-2 border-slate-300 peer-checked:border-primary-500 peer-checked:bg-primary-500 transition-colors">
                                    <svg class="size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 peer-checked:text-primary-600 transition-colors" x-text="comp.code"></span>
                                    <span class="text-xs font-bold text-slate-700 leading-snug mt-0.5 group-hover:text-slate-900 transition-colors" x-text="comp.libelle"></span>
                                </div>
                            </div>
                        </label>
                    </template>
                    <div x-show="filteredCompetences.length === 0" class="text-center py-6 text-slate-400 text-xs font-bold italic">
                        Aucune compétence disponible
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Floating Submit Button -->
        <div class="fixed bottom-4 left-1/2 -translate-x-1/2 z-[100] w-[95%] max-w-2xl animate-in slide-in-from-bottom duration-500">
            <div class="bg-slate-900/95 backdrop-blur-xl text-white rounded-[24px] py-3 px-6 shadow-2xl shadow-slate-900/40 flex items-center justify-between border border-white/10">
                <div class="flex items-center gap-6">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Score Total QCM</span>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-black italic leading-none" :class="totalPoints === 20 ? 'text-emerald-400' : 'text-amber-400'" x-text="`${totalPoints} / 20`"></span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Points</span>
                        </div>
                    </div>
                    <div x-show="totalPoints !== 20" class="hidden md:block">
                        <button @click="equalizePoints()" type="button" class="text-[9px] font-black bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg transition-all uppercase tracking-widest">
                            Rééquilibrer à 20
                        </button>
                    </div>
                </div>
                <button type="submit" class="h-12 px-10 bg-primary-500 text-white rounded-xl font-black uppercase tracking-widest text-sm italic hover:bg-primary-400 shadow-lg shadow-primary-500/20 transition-all flex items-center gap-3">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                    Finaliser
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
            <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-4">Total non-conforme</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-10">
                La somme actuelle est de <strong class="text-rose-600" x-text="totalPoints"></strong> points. 
                Le système requiert exactement <span class="font-black text-slate-900 italic">20 points</span> pour validation.
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

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qcmBuilder', (initialUnites, initialClasses) => ({
        titre: '',
        statut: 'brouillon',
        showPointsWarning: false,
        allUnites: initialUnites,
        allClasses: initialClasses,
        selectedUniteId: '',
        selectedClasseId: '',
        
        init() {
            // Watch for question type changes to auto-normalize unique questions
            this.$watch('questions', (questions) => {
                questions.forEach((q, idx) => {
                    // Ensure at least one correct answer for unique questions
                    if (q.type === 'choix_unique') {
                        const hasCorrect = q.options.some(o => o.est_correcte);
                        if (!hasCorrect && q.options.length > 0) {
                            q.options[0].est_correcte = true;
                        }
                    }
                });
            });
        },
        
        get filteredCompetences() {
            if (!this.selectedUniteId) return [];
            const unite = this.allUnites.find(u => u.id == this.selectedUniteId);
            return unite ? unite.competences : [];
        },

        get totalPoints() {
            return this.questions.reduce((sum, q) => sum + (parseFloat(q.points) || 0), 0);
        },

        equalizePoints() {
            const n = this.questions.length;
            if (n === 0) return;
            const base = Math.floor(20 / n);
            const remainder = 20 - base * n;
            this.questions.forEach((q, i) => {
                q.points = base + (i === n - 1 ? remainder : 0);
            });
        },

        questions: [
            {
                texte: '',
                type: 'choix_unique',
                points: 20,
                explication_feedback: '',
                options: [
                    { texte: '', est_correcte: true, feedback_specifique: '' },
                    { texte: '', est_correcte: false, feedback_specifique: '' }
                ]
            }
        ],

        addQuestion() {
            this.questions.push({
                texte: '',
                type: 'choix_unique',
                points: 0,
                explication_feedback: '',
                options: [
                    { texte: '', est_correcte: true, feedback_specifique: '' },
                    { texte: '', est_correcte: false, feedback_specifique: '' }
                ]
            });
            this.equalizePoints();
        },

        removeQuestion(qIndex) {
            if (this.questions.length > 1) {
                this.questions.splice(qIndex, 1);
                this.equalizePoints();
            }
        },

        addOption(qIndex) {
            this.questions[qIndex].options.push({ texte: '', est_correcte: false, feedback_specifique: '' });
        },

        removeOption(qIndex, oIndex) {
            if (this.questions[qIndex].options.length > 2) {
                this.questions[qIndex].options.splice(oIndex, 1);
            }
        },

        setCorrectOption(qIndex, oIndex, isChecked) {
            const q = this.questions[qIndex];
            if (q.type === 'choix_unique') {
                // Radio behavior: only one correct answer allowed
                q.options.forEach((opt, idx) => {
                    opt.est_correcte = (idx === oIndex);
                });
            } else {
                // Checkbox behavior: multiple correct answers allowed
                q.options[oIndex].est_correcte = isChecked;
            }
        },

        toggleCorrectOption(qIndex, oIndex) {
            const q = this.questions[qIndex];
            const current = q.options[oIndex].est_correcte;
            
            if (q.type === 'choix_unique') {
                // Radio behavior: set this as the only correct answer
                q.options.forEach((opt, idx) => {
                    opt.est_correcte = (idx === oIndex);
                });
            } else {
                // Checkbox behavior: toggle this option
                q.options[oIndex].est_correcte = !current;
            }
        },

        normalizeCorrectOptions(qIndex) {
            const q = this.questions[qIndex];
            if (!q) return;
            if (q.type !== 'choix_unique') return;

            const firstCorrectIndex = q.options.findIndex(o => !!o.est_correcte);
            const keepIndex = firstCorrectIndex !== -1 ? firstCorrectIndex : 0;

            q.options.forEach((o, idx) => {
                o.est_correcte = idx === keepIndex;
            });
        },

        isSubmitting: false,

        isDirty() {
            // Considéré comme 'sale' si le titre est saisi OU si la première question a du texte 
            // OU s'il y a plus d'une question.
            return this.titre.trim().length > 0 || 
                   (this.questions.length > 0 && this.questions[0].texte.trim().length > 0) ||
                   this.questions.length > 1;
        },

        handleSubmit() {
            if (this.totalPoints !== 20) {
                this.showPointsWarning = true;
            } else {
                this.isSubmitting = true;
                window.onbeforeunload = null;
                document.getElementById('qcmForm').submit();
            }
        }
    }));

    // Interception de la fermeture de l'onglet/rechargement
    window.onbeforeunload = function(e) {
        const builder = Alpine.evaluate(document.querySelector('[x-data^=qcmBuilder]'), 'isDirty()');
        const isSubmitting = Alpine.evaluate(document.querySelector('[x-data^=qcmBuilder]'), 'isSubmitting');
        
        if (builder && !isSubmitting) {
            e.preventDefault();
            return "Voulez-vous vraiment quitter ? Vos modifications ne seront pas enregistrées.";
        }
    };

    // Interception des clics sur les liens internes pour éviter de perdre le travail
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;
        
        // On ignore les liens qui ouvrent dans un nouvel onglet ou les ancres
        if (link.target === '_blank' || link.getAttribute('href').startsWith('#')) return;
        
        const builder = Alpine.evaluate(document.querySelector('[x-data^=qcmBuilder]'), 'isDirty()');
        const isSubmitting = Alpine.evaluate(document.querySelector('[x-data^=qcmBuilder]'), 'isSubmitting');

        if (builder && !isSubmitting) {
            e.preventDefault();
            window.dispatchEvent(new CustomEvent('confirm', {
                detail: {
                    title: 'Attention : Travail en cours',
                    message: 'Vous avez des modifications non enregistrées. Voulez-vous vraiment quitter cette page ?',
                    type: 'warning',
                    confirmText: 'Quitter sans enregistrer',
                    cancelText: 'Rester ici',
                    onConfirm: () => {
                        window.onbeforeunload = null;
                        window.location.href = link.href;
                    }
                }
            }));
        }
    });
});
</script>
@endsection
