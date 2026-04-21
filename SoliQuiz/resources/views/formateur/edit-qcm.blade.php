@extends('layouts.app')

@section('title', 'Modifier QCM - SoliQuiz')

@section('page-title', 'Studio de Modification')

@section('content')
<div class="fade-in pb-32" x-data="qcmBuilder({{ Js::from($unites) }}, {{ Js::from($classes) }}, {{ Js::from($qcm) }})">
    
    <!-- Action Form -->
    <form id="qcmForm" action="{{ route('formateur.qcm.update', $qcm->id) }}" method="POST" @submit.prevent="handleSubmit">
        @csrf
        @method('PUT')
        
        <!-- Header Configuration Section -->
        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8 md:p-12 mb-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-5">
                <svg class="size-48" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="m9 15 2 2 4-4"/></svg>
            </div>

            <div class="relative z-10 space-y-12">
                <!-- Row 1: Main Title & Status -->
                <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-10">
                    <div class="flex-1 space-y-3">
                        <label class="text-label ml-1">Identité de l'évaluation</label>
                        <input type="text" name="titre" required x-model="titre" 
                               class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-5 px-8 text-2xl font-black text-slate-900 placeholder:text-slate-200 focus:bg-white focus:border-primary-500 transition-all outline-none uppercase italic" 
                               placeholder="Saisir le titre du QCM...">
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="space-y-3">
                            <label class="text-label ml-1">Disponibilité</label>
                            <x-ui.select 
                                name="statut" 
                                x-model="statut"
                                class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-4 !px-6 !w-44"
                                :options="[
                                    ['value' => 'brouillon', 'label' => 'Brouillon'],
                                    ['value' => 'public', 'label' => 'Public'],
                                    ['value' => 'termine', 'label' => 'Terminé'],
                                ]"
                            />
                        </div>
                        <div class="space-y-3">
                            <label class="text-label ml-1">Total Points</label>
                            <div class="h-[60px] px-6 bg-slate-900 rounded-2xl flex items-center gap-3 shadow-lg shadow-slate-900/10">
                                <span class="text-xl font-black text-white italic" x-text="`${totalPoints}/20`"></span>
                                <div class="w-px h-6 bg-white/20"></div>
                                <button type="button" @click="equalizePoints()" class="text-[9px] font-black text-primary-400 uppercase tracking-widest hover:text-white transition-colors">Équilibrer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Pedagogical Alignment -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-8 border-t border-slate-50">
                    <div class="space-y-3">
                        <label class="text-label ml-1">Module UA</label>
                        <x-ui.select 
                            name="unite_apprentissage_id" 
                            x-model="selectedUniteId" 
                            required 
                            placeholder="Choisir l'UA"
                            jsOptions="allUnites.map(u => ({value: u.id, label: u.nom}))"
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-4 !px-6"
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
                            class="!bg-slate-50/50 !border-transparent !rounded-2xl !py-4 !px-6"
                        />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <label class="text-label ml-1">Durée (min)</label>
                            <input type="number" name="duree_minutes" x-model="dureeMinutes" required 
                                   class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-4 px-4 font-black text-slate-900 text-center text-lg focus:bg-white focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="space-y-3">
                            <label class="text-label ml-1">Seuil Réussite</label>
                            <input type="number" name="score_reussite" x-model="scoreReussite" required step="0.5"
                                   class="w-full bg-slate-50/50 border-2 border-transparent rounded-2xl py-4 px-4 font-black text-slate-900 text-center text-lg focus:bg-white focus:border-primary-500 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <!-- Row 3: Competences Selection -->
                <div x-show="selectedUniteId" x-transition class="pt-8 border-t border-slate-50 space-y-6">
                    <div class="flex items-center justify-between px-1">
                        <label class="text-label">Piliers de compétences ciblés</label>
                        <span class="text-[9px] font-black text-slate-300" x-text="`${filteredCompetences.length} disponibles`"></span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="comp in filteredCompetences" :key="comp.id">
                            <label class="relative group cursor-pointer">
                                <input type="checkbox" name="competence_ids[]" :value="comp.id" 
                                       :checked="selectedCompetences.includes(comp.id)"
                                       class="peer hidden">
                                <div class="px-6 py-4 bg-slate-50 rounded-2xl border-2 border-transparent peer-checked:border-primary-500 peer-checked:bg-white peer-checked:shadow-md transition-all flex items-center gap-3">
                                    <div class="size-6 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-[9px] font-black text-slate-400 peer-checked:bg-primary-500 peer-checked:text-white transition-colors" x-text="comp.code"></div>
                                    <span class="text-xs font-bold text-slate-700 uppercase italic tracking-tight" x-text="comp.libelle"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Dynamic Section -->
        <div class="space-y-12">
            <template x-for="(question, qIndex) in questions" :key="qIndex">
                <article class="bg-white rounded-[40px] border border-slate-100 shadow-sm hover:shadow-premium transition-all duration-500 overflow-hidden" 
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8">
                    
                    <!-- Question Sub-Header -->
                    <div class="px-10 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                        <div class="flex items-center gap-6">
                            <div class="size-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-lg font-black italic shadow-xl shadow-slate-900/10" x-text="qIndex + 1"></div>
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

                    <div class="p-10 md:p-12 space-y-12">
                        <!-- Points & Statement -->
                        <div class="flex flex-col md:flex-row gap-10">
                            <div class="w-28 shrink-0 space-y-3">
                                <label class="text-label ml-1">Points</label>
                                <input type="number" x-model.number="question.points" :name="`questions[${qIndex}][points]`" 
                                       class="w-full py-5 rounded-2xl border-none bg-slate-50 font-black text-2xl text-slate-900 text-center focus:bg-white transition-all outline-none">
                            </div>
                            <div class="flex-1 space-y-3">
                                <label class="text-label ml-1">Énoncé Pédagogique</label>
                                <textarea x-model="question.texte" :name="`questions[${qIndex}][texte]`" required 
                                          class="w-full py-5 px-8 rounded-[2rem] bg-slate-50 border-none font-bold text-xl text-slate-900 placeholder:text-slate-200 focus:bg-white focus:ring-8 focus:ring-primary-500/5 transition-all outline-none resize-none" 
                                          rows="2" placeholder="Formuler la question..."></textarea>
                            </div>
                        </div>

                        <!-- Options Management -->
                        <div class="space-y-8">
                            <div class="flex items-center justify-between border-b border-slate-50 pb-5">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Options Stratégiques</h4>
                                <x-ui.select 
                                    x-model="question.type" 
                                    name="questions[${qIndex}][type]" 
                                    class="!bg-primary-50 !border-transparent !rounded-xl !py-2 !px-4 !w-52 !text-primary-600"
                                    :options="[
                                        ['value' => 'choix_unique', 'label' => 'Réponse Unique'],
                                        ['value' => 'choix_multiple', 'label' => 'Multi-Réponses'],
                                    ]"
                                />
                            </div>

                            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.25em]" x-text="question.type === 'choix_unique' ? 'Sélectionner la réponse correcte' : 'Sélectionner les réponses correctes'"></span>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="(opt, oIndex) in question.options" :key="oIndex">
                                    <div class="group/opt p-6 bg-slate-50/50 rounded-3xl border border-transparent hover:border-primary-100 hover:bg-white transition-all duration-300 space-y-4 shadow-xs">
                                        <div class="flex items-center gap-4">
                                            <span x-show="false" x-effect="normalizeCorrectOptions(qIndex)"></span>
                                            <input type="hidden" :name="`questions[${qIndex}][options][${oIndex}][est_correcte]`" :value="opt.est_correcte ? '1' : '0'">
                                            <button @click="setCorrectOption(qIndex, oIndex, !opt.est_correcte)" type="button"
                                                    class="size-7 rounded-lg border-2 flex items-center justify-center shrink-0 transition-all"
                                                    :class="opt.est_correcte ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-200 text-transparent'">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                            <input type="text" required x-model="opt.texte" :name="`questions[${qIndex}][options][${oIndex}][texte]`" 
                                                   placeholder="Option de réponse..." class="w-full bg-transparent border-none p-0 font-bold text-slate-800 text-base placeholder:text-slate-200 focus:ring-0 outline-none">
                                            
                                            <button @click="removeOption(qIndex, oIndex)" type="button" class="text-slate-200 hover:text-rose-500 transition-colors opacity-0 group-hover/opt:opacity-100">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                        <input type="text" x-model="opt.feedback_specifique" :name="`questions[${qIndex}][options][${oIndex}][feedback_specifique]`" 
                                               placeholder="Feedback correctif (optionnel)..." 
                                               class="w-full bg-white/50 border border-slate-100 rounded-xl py-2 px-4 text-[10px] font-bold text-slate-500 italic placeholder:text-slate-200 focus:bg-white transition-all outline-none">
                                    </div>
                                </template>

                                <button @click="addOption(qIndex)" type="button" 
                                        class="h-full min-h-[110px] border-2 border-dashed border-slate-100 rounded-3xl flex flex-col items-center justify-center gap-2 text-slate-300 hover:text-primary-500 hover:border-primary-200 hover:bg-primary-50/30 transition-all group">
                                    <svg class="size-6 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                    <span class="text-[9px] font-black uppercase tracking-[0.2em]">Insérer Option</span>
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
                                <textarea x-model="question.explication_feedback" :name="`questions[${qIndex}][explication_feedback]`" 
                                          class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-slate-600 focus:bg-white transition-all outline-none resize-none" 
                                          rows="2" placeholder="Expliquer le raisonnement correct..."></textarea>
                            </div>
                        </div>
                    </div>
                </article>
            </template>

            <!-- Final Actions -->
            <div class="flex flex-col items-center gap-10 py-16">
                <button @click="addQuestion()" type="button" 
                        class="h-20 px-12 bg-white border border-slate-100 rounded-[2.5rem] font-black text-slate-900 uppercase tracking-[0.3em] italic shadow-lg hover:shadow-xl hover:-translate-y-2 active:scale-95 transition-all flex items-center gap-4">
                    <svg class="size-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                    Nouvelle Question
                </button>
            </div>
        </div>

        <!-- Floating Submit Button -->
        <div class="fixed bottom-10 right-10 z-[100] flex items-center gap-4 animate-in slide-in-from-bottom duration-500">
            <div class="bg-slate-900 text-white rounded-3xl p-5 shadow-2xl flex items-center gap-8 border border-white/10 backdrop-blur-xl">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">État du Barème</span>
                    <span class="text-xl font-black italic leading-none" :class="totalPoints === 20 ? 'text-emerald-400' : 'text-amber-400'" x-text="`${totalPoints} / 20 PTS`"></span>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <button type="submit" class="h-14 px-10 bg-primary-500 text-white rounded-2xl font-black uppercase tracking-widest italic hover:bg-primary-400 shadow-lg shadow-primary-500/20 active:scale-95 transition-all flex items-center gap-3">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>

    <!-- Points Warning Modal -->
    <x-ui.modal name="points-warning" x-model:show="showPointsWarning" maxWidth="md">
        <div class="text-center p-8">
            <div class="size-20 bg-rose-50 rounded-3xl flex items-center justify-center mx-auto mb-8">
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
                <button @click="showPointsWarning = false; $nextTick(() => document.getElementById('qcmForm').submit())" type="button"
                        class="h-14 rounded-2xl bg-white border border-slate-100 font-black text-rose-500 uppercase tracking-widest text-[10px] hover:bg-rose-50 transition-all">
                    Ignorer et Enregistrer
                </button>
            </div>
        </div>
    </x-ui.modal>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qcmBuilder', (initialUnites, initialClasses, initialQcm) => ({
        titre: initialQcm?.titre || '',
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

        questions: initialQcm?.questions?.map(q => ({
            texte: q.texte,
            type: q.type === 'unique' ? 'choix_unique' : 'choix_multiple',
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
