@extends('layouts.app')

@section('title', 'Modifier QCM - SoliQuiz')

@section('content')
<div x-data="qcmBuilder({{ Js::from($unites) }}, {{ Js::from($classes) }}, {{ Js::from($qcm) }})" class="bg-slate-50 flex flex-col h-screen overflow-hidden">
    
    <!-- Action Form -->
    <form id="qcmForm" action="{{ route('formateur.qcm.update', $qcm->id) }}" method="POST" class="contents" @submit.prevent="handleSubmit">
    @csrf
    @method('PUT')

    <!-- Toolbar Editor -->
    <div class="w-full bg-white border-b border-slate-200 shrink-0 z-50 flex items-center h-[70px]">
        <div class="max-w-[1600px] w-full mx-auto px-6 flex justify-between items-center h-full">
            <div class="flex items-center gap-6">
                <a class="p-2.5 hover:bg-slate-100 rounded-xl transition-colors text-slate-500 group" href="{{ route('formateur.bibliotheque') }}">
                    <svg class="size-5 group-hover:-translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <div class="h-8 w-px bg-slate-200"></div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-primary-500 uppercase tracking-widest leading-none mb-1">Modification</span>
                    <input type="text" name="titre" required x-model="titre" class="text-xl font-heading font-black text-slate-900 border-transparent hover:border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded-lg bg-transparent px-2 py-0.5 outline-none transition-all w-[300px] md:w-[500px]" placeholder="Nom de l'évaluation...">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Live Points Tracker -->
                <div class="hidden sm:flex items-center gap-2 px-4 py-2.5 rounded-2xl border-2 transition-all"
                     :class="totalPoints === 20 
                         ? 'bg-emerald-50 border-emerald-200 text-emerald-700' 
                         : 'bg-rose-50 border-rose-200 text-rose-600'">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path x-show="totalPoints === 20" d="M5 13l4 4L19 7"/>
                        <path x-show="totalPoints !== 20" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Total :</span>
                    <span class="text-sm font-black font-heading" x-text="`${totalPoints}/20`"></span>
                    <button type="button" @click="equalizePoints()"
                            class="ml-1 text-[9px] font-black uppercase tracking-widest underline opacity-70 hover:opacity-100 transition-opacity">
                        Égaliser
                    </button>
                </div>

                <!-- Status -->
                <div class="hidden sm:flex items-center gap-3 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl group focus-within:border-primary-500 focus-within:ring-4 focus-within:ring-primary-500/10 transition-all">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">État :</span>
                    <select name="statut" x-model="statut" class="text-[10px] font-black uppercase text-slate-700 bg-transparent border-none focus:ring-0 outline-none cursor-pointer p-0 custom-select !bg-none !pr-6">
                        <option value="brouillon">Brouillon</option>
                        <option value="public">Public</option>
                        <option value="termine">Terminé</option>
                    </select>
                </div>
                <button type="submit" class="group btn-premium py-2.5 px-6 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all text-[11px] uppercase tracking-widest flex items-center gap-3">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="m22 2-7 20-4-9-9-4Z" /><path d="M22 2 11 13" />
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 text-rose-600 px-8 py-3 border-b border-rose-200 text-xs font-bold animate-in slide-in-from-top duration-500">
            <ul class="flex flex-wrap gap-x-8 gap-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-center gap-2">
                        <span class="size-1 rounded-full bg-rose-400"></span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-50 text-rose-600 px-8 py-3 border-b border-rose-200 text-xs font-bold animate-in slide-in-from-top duration-500 flex items-center gap-2">
            <span class="size-1 rounded-full bg-rose-400"></span>
            {{ session('error') }}
        </div>
    @endif

    <!-- App Layout Split -->
    <div class="flex flex-1 overflow-hidden max-w-[1600px] w-full mx-auto bg-white border-x border-slate-200 shadow-sm relative">
        
        <!-- Sidebar Paramètres Globaux -->
        <aside class="hidden lg:block w-[360px] bg-slate-50 border-r border-slate-200 overflow-y-auto shrink-0 z-10 p-8 space-y-10">
            <section>
                <div class="flex items-center gap-3 mb-6">
                    <div class="size-8 bg-slate-900 text-white rounded-xl flex items-center justify-center">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em]">Context Péd.</h2>
                </div>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Cible Cohorte</label>
                        <select name="classe_id" x-model="selectedClasseId" class="w-full bg-white border-2 border-slate-100 rounded-2xl py-3.5 px-5 font-bold text-slate-900 focus:border-primary-500 outline-none transition-all text-sm custom-select">
                            <option value="">Toutes mes classes</option>
                            <template x-for="classe in allClasses" :key="classe.id">
                                <option :value="classe.id" x-text="classe.nom"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Module (UA)</label>
                        <select name="unite_apprentissage_id" x-model="selectedUniteId" required class="w-full bg-white border-2 border-slate-100 rounded-2xl py-3.5 px-5 font-bold text-slate-900 focus:border-primary-500 outline-none transition-all text-sm custom-select">
                            <option value="">Choisir l'UA</option>
                            <template x-for="unite in allUnites" :key="unite.id">
                                <option :value="unite.id" x-text="unite.nom"></option>
                            </template>
                        </select>
                    </div>

                    <div x-show="selectedUniteId" x-transition class="space-y-4">
                        <label class="text-[10px] font-black text-primary-500 uppercase tracking-[0.2em] ml-1">Compétences visées</label>
                        <div class="bg-white border-2 border-slate-100 rounded-[32px] p-5 space-y-2.5 max-h-[300px] overflow-y-auto custom-scrollbar shadow-inner">
                            <template x-for="comp in filteredCompetences" :key="comp.id">
                                <label class="flex items-start gap-4 p-4 hover:bg-primary-50/50 rounded-2xl cursor-pointer transition-all group border-2 border-transparent hover:border-primary-100">
                                    <div class="mt-1">
                                        <input type="checkbox" name="competence_ids[]" :value="comp.id" :checked="selectedCompetences.includes(comp.id)" class="size-4 rounded-md border-2 border-slate-200 text-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all checked:border-primary-500">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black text-primary-500/60 group-hover:text-primary-600 transition-colors" x-text="comp.code"></span>
                                        <span class="text-[11px] font-bold text-slate-800 leading-snug group-hover:text-slate-900 transition-colors" x-text="comp.libelle"></span>
                                    </div>
                                </label>
                            </template>
                            <template x-if="filteredCompetences.length === 0">
                                <div class="py-12 text-center">
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-relaxed">Aucun pilier trouvé<br>pour ce module</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="flex items-center gap-4 mb-8">
                    <div class="size-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-slate-900/10">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em]">Contraintes</h2>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Durée (min)</label>
                        <input type="number" name="duree_minutes" required x-model="dureeMinutes" class="w-full bg-slate-50 border-2 border-slate-50 rounded-[20px] py-4 px-4 font-black text-slate-900 focus:bg-white transition-all text-center text-lg">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Note pour réussir (/20)</label>
                        <input type="number" name="score_reussite" required step="0.5" min="0" max="20" x-model="scoreReussite" class="w-full bg-slate-50 border-2 border-slate-50 rounded-[20px] py-4 px-4 font-black text-slate-900 focus:bg-white transition-all text-center text-lg">
                    </div>
                </div>
            </section>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 overflow-y-auto bg-slate-50/50 p-6 md:p-12 relative overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-10 pb-[150px]">

                <template x-for="(question, qIndex) in questions" :key="qIndex">
                    <article class="bg-white border-2 border-slate-100 rounded-[40px] shadow-sm hover:shadow-premium hover:border-primary-100 transition-all duration-500 relative">
                        <div class="absolute -left-4 top-10 size-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black shadow-xl shadow-slate-900/10 z-20" x-text="qIndex + 1"></div>
                        
                        <div class="p-8 md:p-10 space-y-10">
                            <!-- Points & Text -->
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <div class="w-28 shrink-0 space-y-2">
                                    <div class="flex items-center justify-between ml-1">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Points</label>
                                        <span class="text-[9px] font-black text-slate-300">/20</span>
                                    </div>
                                    <div class="relative">
                                        <input type="number" 
                                               x-model.number="question.points" 
                                               :name="`questions[${qIndex}][points]`" 
                                               min="0" max="20" step="1"
                                               class="w-full py-4 border-2 rounded-2xl text-center font-black text-slate-900 transition-all outline-none"
                                               :class="totalPoints === 20 ? 'border-slate-50 bg-slate-50 focus:bg-white focus:border-primary-500' : 'border-rose-100 bg-rose-50/50 focus:bg-white focus:border-rose-400'">
                                    </div>
                                </div>
                                <div class="flex-1 w-full space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Énoncé de la question</label>
                                    <textarea x-model="question.texte" :name="`questions[${qIndex}][texte]`" required class="w-full py-5 px-6 border-2 border-slate-50 rounded-[32px] text-lg font-bold text-slate-900 bg-slate-50 focus:bg-white focus:border-primary-500 transition-all outline-none resize-none" rows="2" placeholder="Posez votre question ici..."></textarea>
                                </div>
                                <button type="button" @click="removeQuestion(qIndex)" class="p-3 text-slate-300 hover:text-rose-500 transition-colors mt-8">
                                    <svg class="size-6 shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>

                            <!-- Options Grid -->
                            <div class="space-y-6">
                                <div class="flex justify-between items-center px-2">
                                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Options de réponse</h3>
                                    <select x-model="question.type" :name="`questions[${qIndex}][type]`" class="text-[10px] font-black text-primary-500 uppercase tracking-widest bg-emerald-50 px-4 py-2 rounded-xl border-2 border-emerald-100 outline-none custom-select shadow-sm">
                                        <option value="choix_unique">Réponse Unique</option>
                                        <option value="choix_multiple">Plusieurs Réponses</option>
                                    </select>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <template x-for="(opt, oIndex) in question.options" :key="oIndex">
                                        <div class="group/opt bg-slate-50/50 p-4 rounded-3xl border-2 border-transparent hover:border-primary-100 hover:bg-white transition-all space-y-3">
                                            <div class="flex items-center gap-3">
                                                <input type="hidden" :name="`questions[${qIndex}][options][${oIndex}][est_correcte]`" :value="opt.est_correcte ? '1' : '0'">
                                                <div @click="setCorrectOption(qIndex, oIndex, !opt.est_correcte)" 
                                                     class="size-6 rounded-lg border-2 cursor-pointer transition-all flex items-center justify-center shrink-0"
                                                     :class="opt.est_correcte ? 'bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white border-slate-200 text-transparent'">
                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                                <input type="text" required x-model="opt.texte" :name="`questions[${qIndex}][options][${oIndex}][texte]`" placeholder="Texte de l'option..." class="w-full bg-transparent border-none outline-none font-bold text-slate-800 text-sm placeholder-slate-300">
                                                <button type="button" @click="removeOption(qIndex, oIndex)" class="text-slate-200 hover:text-rose-500 transition-colors">
                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                </button>
                                            </div>
                                            <!-- Option Feedback -->
                                            <input type="text" x-model="opt.feedback_specifique" :name="`questions[${qIndex}][options][${oIndex}][feedback_specifique]`" placeholder="Feedback si choisie (optionnel)..." class="w-full bg-white/50 border border-slate-100 rounded-xl py-2 px-3 text-[10px] font-bold text-slate-500 italic focus:bg-white transition-all outline-none">
                                        </div>
                                    </template>
                                    
                                    <button type="button" @click="addOption(qIndex)" class="h-full border-2 border-dashed border-slate-200 rounded-3xl p-4 flex items-center justify-center gap-3 text-slate-400 hover:text-primary-500 hover:border-primary-500 hover:bg-primary-50/30 transition-all group">
                                        <svg class="size-4 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Ajouter une option</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Global Question Feedback -->
                            <div class="pt-6 border-t border-slate-50">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="size-6 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center">
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Explication Pédagogique Globale</label>
                                </div>
                                <textarea x-model="question.explication_feedback" :name="`questions[${qIndex}][explication_feedback]`" class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 text-xs font-bold text-slate-600 focus:bg-white focus:border-primary-200 transition-all outline-none resize-none" rows="2" placeholder="Cette explication apparaîtra au survol de l'icône 'i' pour l'étudiant après le test..."></textarea>
                            </div>
                        </div>
                    </article>
                </template>

                <div class="flex justify-center flex-col items-center gap-6 pt-10">
                    <button type="button" @click="addQuestion()" class="btn-premium py-5 px-12 bg-white text-slate-900 border-2 border-slate-100 rounded-[32px] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl hover:-translate-y-2 active:scale-95 transition-all">
                        🚀 Insérer Nouvelle Question
                    </button>
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">
                        <span x-text="questions.length"></span> question<span x-show="questions.length > 1">s</span> — 
                        <span :class="totalPoints === 20 ? 'text-emerald-500' : 'text-rose-400'" x-text="`${totalPoints}/20 pts`"></span>
                    </p>
                </div>
            </div>
        </main>
    </div>
    </form>

    <!-- Points Warning Modal -->
    <div x-show="showPointsWarning" x-cloak 
         class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl">
            <div class="size-16 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="size-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-heading font-black text-slate-900 text-center mb-2">Total de points incorrect</h3>
            <p class="text-slate-500 text-center text-sm mb-2">
                La somme des points est de 
                <strong :class="totalPoints > 20 ? 'text-rose-600' : 'text-amber-600'" x-text="totalPoints"></strong> /20.
            </p>
            <p class="text-slate-400 text-center text-xs mb-6">Souhaitez-vous égaliser automatiquement ou sauvegarder quand même ?</p>
            <div class="flex gap-3">
                <button @click="showPointsWarning = false; equalizePoints()" 
                        class="flex-1 h-12 rounded-xl border-2 border-slate-200 font-bold text-slate-700 hover:bg-slate-50 transition-colors text-sm">
                    Égaliser
                </button>
                <button @click="showPointsWarning = false; $nextTick(() => document.getElementById('qcmForm').submit())"
                        class="flex-1 h-12 rounded-xl bg-rose-500 text-white font-bold hover:bg-rose-600 transition-colors text-sm">
                    Sauvegarder quand même
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qcmBuilder', (initialUnites, initialClasses, initialQcm) => ({
        titre: initialQcm?.titre || 'Évaluation sans titre',
        statut: initialQcm?.statut || 'brouillon',
        showPointsWarning: false,
        dureeMinutes: initialQcm?.duree_minutes || 30,
        scoreReussite: initialQcm?.score_reussite || 10,
        allUnites: initialUnites,
        allClasses: initialClasses,
        selectedUniteId: initialQcm?.unite_apprentissage_id || '',
        selectedClasseId: initialQcm?.classe_id || '',
        selectedCompetences: initialQcm?.competences?.map(c => c.id) || [],
        
        get filteredCompetences() {
            if (!this.selectedUniteId) return [];
            const unite = this.allUnites.find(u => u.id == this.selectedUniteId);
            return unite ? unite.competences : [];
        },

        get totalPoints() {
            return this.questions.reduce((sum, q) => sum + (parseInt(q.points) || 0), 0);
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

        questions: initialQcm?.questions?.map(q => ({
            texte: q.texte,
            type: q.type === 'unique' ? 'choix_unique' : 'choix_multiple', // Map DB→UI
            points: q.points,
            explication_feedback: q.explication_feedback || '',
            options: q.options?.map(o => ({
                texte: o.texte,
                est_correcte: o.est_correcte,
                feedback_specifique: o.feedback_specifique || ''
            })) || [
                { texte: '', est_correcte: false, feedback_specifique: '' },
                { texte: '', est_correcte: false, feedback_specifique: '' }
            ]
        })) || [
            {
                texte: '',
                type: 'choix_unique',
                points: 20,
                explication_feedback: '',
                options: [
                    { texte: '', est_correcte: false, feedback_specifique: '' },
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
                    { texte: '', est_correcte: false, feedback_specifique: '' },
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
            if (this.questions[qIndex].type === 'choix_unique') {
                this.questions[qIndex].options.forEach((opt, idx) => {
                    opt.est_correcte = (idx === oIndex);
                });
            } else {
                this.questions[qIndex].options[oIndex].est_correcte = isChecked;
            }
        },

        handleSubmit() {
            if (this.totalPoints !== 20) {
                this.showPointsWarning = true;
            } else {
                document.getElementById('qcmForm').submit();
            }
        }
    }));
});
</script>
@endsection
