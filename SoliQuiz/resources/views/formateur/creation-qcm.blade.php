@extends('components.layout.app')

@section('content')
<div x-data="qcmBuilder({{ Js::from($unites) }})" class="bg-slate-50 flex flex-col h-screen overflow-hidden">
    
    <!-- Action Form -->
    <form id="qcmForm" action="{{ route('formateur.qcm.store') }}" method="POST" class="contents">
    @csrf

    <!-- Toolbar Editor -->
    <div class="w-full bg-white border-b border-slate-200 shrink-0 z-50 flex items-center h-[60px]">
        <div class="max-w-[1600px] w-full mx-auto px-4 flex justify-between items-center h-full">
            <div class="flex items-center gap-4">
                <a class="p-2 hover:bg-slate-100 rounded-lg transition-colors text-slate-500" href="{{ route('formateur.dashboard') }}">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <div class="h-6 w-px bg-slate-200"></div>
                <!-- Editable Title -->
                <input type="text" name="titre" required x-model="titre" class="text-lg font-bold font-heading text-slate-900 border-transparent hover:border-slate-300 focus:border-primary-500 focus:ring-primary-500 rounded bg-transparent px-2 py-1 outline-none transition-colors w-[250px] md:w-[400px]" placeholder="Titre du QCM">
            </div>

            <div class="flex items-center gap-3">
                <input type="hidden" name="est_publie" :value="isPublished ? '1' : '0'">
                <button type="button" @click="isPublished = false; document.getElementById('qcmForm').submit()" class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 transition-colors">
                    Sauvegarder Brouillon
                </button>
                <button type="button" @click="isPublished = true; document.getElementById('qcmForm').submit()" class="py-1.5 px-4 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-transparent bg-primary-500 text-white shadow-sm hover:bg-primary-600 transition-colors">
                    <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m22 2-7 20-4-9-9-4Z" />
                        <path d="M22 2 11 13" />
                    </svg>
                    Publier le Test
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 border-b border-red-200 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- App Layout Split -->
    <div class="flex flex-1 overflow-hidden max-w-[1600px] w-full mx-auto bg-white border-x border-slate-200 shadow-sm">
        
        <!-- Sidebar Paramètres Globaux -->
        <aside class="hidden lg:block w-[320px] bg-slate-50 border-r border-slate-200 overflow-y-auto shrink-0 z-10 p-6 space-y-8">
            <div>
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 border-b pb-2 border-slate-200">Paramètres du Test</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1.5 text-slate-800">Unité d'apprentissage</label>
                        <select name="unite_apprentissage_id" x-model="selectedUniteId" required class="py-2.5 px-3 w-full bg-white border border-slate-200 text-slate-800 rounded-lg text-sm shadow-sm outline-none">
                            <option value="">Sélectionnez une UA</option>
                            <template x-for="unite in allUnites" :key="unite.id">
                                <option :value="unite.id" x-text="unite.nom"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Liste des compétences filtrées -->
                    <div x-show="selectedUniteId" x-transition>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Compétences cibles</h3>
                        <div class="space-y-2 bg-white border border-slate-200 rounded-xl p-3 max-h-[200px] overflow-y-auto shadow-inner">
                            <template x-for="comp in filteredCompetences" :key="comp.id">
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <input type="checkbox" name="competence_ids[]" :value="comp.id" class="mt-1 size-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500 cursor-pointer">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700 group-hover:text-primary-600 transition-colors" x-text="comp.code"></span>
                                        <span class="text-[10px] text-slate-500 leading-tight" x-text="comp.libelle"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 border-b pb-2 border-slate-200">Conditions</h2>
                <div class="space-y-5">
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-slate-800 font-semibold cursor-pointer">Temps limite (min)</label>
                        <input type="number" name="duree_minutes" required class="py-1 px-2 w-[80px] text-center border border-slate-200 rounded-md text-sm bg-white shadow-sm outline-none" value="20">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-slate-800 font-semibold cursor-pointer">Score réussite (%)</label>
                        <input type="number" name="score_reussite" required class="py-1 px-2 w-[80px] text-center border border-slate-200 rounded-md text-sm bg-white shadow-sm outline-none" value="50">
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace (Editor Flow) -->
        <main class="flex-1 overflow-y-auto bg-slate-100 p-4 md:p-8 relative">
            <div class="max-w-3xl mx-auto space-y-6 pb-[100px]">

                <template x-for="(question, qIndex) in questions" :key="qIndex">
                    <article class="bg-white border shadow-md border-primary-100 rounded-2xl w-full flex flex-col focus-within:ring-2 ring-primary-500/20 ring-offset-4 ring-offset-slate-100 mb-6">
                        <header class="border-b border-primary-100 py-3 px-6 flex justify-between items-center bg-primary-50/30 rounded-t-2xl">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex size-[28px] bg-primary-500 text-white shadow-sm rounded-full items-center justify-center text-xs font-bold" x-text="qIndex + 1"></span>
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-wider">Édition</span>
                            </div>
                            <button type="button" @click="removeQuestion(qIndex)" class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /></svg>
                            </button>
                        </header>

                        <div class="p-6 space-y-6">
                            <!-- Points & Text -->
                            <div class="flex gap-4">
                                <div class="w-16 shrink-0">
                                    <input type="number" x-model="question.points" :name="`questions[${qIndex}][points]`" class="w-full py-3 px-2 border border-slate-200 rounded-xl text-center shadow-sm text-sm" placeholder="Pts">
                                </div>
                                <div class="w-full">
                                    <textarea x-model="question.texte" :name="`questions[${qIndex}][texte]`" required class="w-full py-3 px-4 block border border-slate-200 rounded-xl text-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors outline-none font-medium placeholder-slate-400" rows="2" placeholder="Saisissez l'énoncé de la question..."></textarea>
                                </div>
                            </div>

                            <!-- Builder Réponses -->
                            <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Réponses (Cocher la bonne)</span>
                                    <select x-model="question.type" :name="`questions[${qIndex}][type]`" class="py-1.5 px-3 bg-white border border-slate-200 rounded text-xs font-semibold outline-none">
                                        <option value="choix_unique">Choix Unique</option>
                                        <option value="choix_multiple">Choix Multiple</option>
                                    </select>
                                </div>

                                <template x-for="(opt, oIndex) in question.options" :key="oIndex">
                                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-lg border border-slate-200 shadow-sm focus-within:ring-2 focus-within:border-primary-500 transition-all">
                                        
                                        <!-- Real Checkbox/Radio for DB (hidden via Alpine, we handle value via JS/binding) -->
                                        <input type="hidden" :name="`questions[${qIndex}][options][${oIndex}][est_correcte]`" :value="opt.est_correcte ? '1' : '0'">
                                        
                                        <!-- Visual Checkbox to toggle state -->
                                        <input :type="question.type === 'choix_unique' ? 'radio' : 'checkbox'" 
                                               :name="`visual_radio_${qIndex}`"
                                               :checked="opt.est_correcte"
                                               @change="setCorrectOption(qIndex, oIndex, $event.target.checked)"
                                               class="shrink-0 size-4 border-slate-300 text-primary-500 ml-1 cursor-pointer">
                                        
                                        <input type="text" required x-model="opt.texte" :name="`questions[${qIndex}][options][${oIndex}][texte]`" placeholder="Option de réponse" class="block w-full text-sm font-medium text-slate-800 outline-none pb-0.5 border-b border-transparent focus:border-slate-300 bg-transparent">
                                        
                                        <button type="button" @click="removeOption(qIndex, oIndex)" class="text-slate-300 hover:text-red-500 p-1">
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18" /><path d="m6 6 12 12" /></svg>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="addOption(qIndex)" class="mt-2 py-2 px-3 inline-flex items-center gap-x-2 text-xs font-bold rounded-lg text-primary-600 hover:bg-primary-50 transition-colors w-full justify-center border border-dashed border-primary-200">
                                    <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                                    Ajouter une option
                                </button>
                            </div>

                            <!-- Feedback -->
                            <div>
                                <textarea x-model="question.explication" :name="`questions[${qIndex}][explication]`" class="w-full py-2.5 px-3 block border border-slate-200 rounded-xl text-sm shadow-sm focus:border-slate-300 outline-none transition-colors placeholder-slate-400 font-medium" rows="2" placeholder="Feedback Pédagogique (Optionnel)..."></textarea>
                            </div>
                        </div>
                    </article>
                </template>

                <div class="flex justify-center pt-8">
                    <button type="button" @click="addQuestion" class="py-3 px-6 shadow-md inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-full border border-slate-200 bg-white text-slate-700 hover:bg-primary-50 transition-all hover:-translate-y-1">
                        Nouvelle Question
                    </button>
                </div>
            </div>
        </main>
    </div>
    </form>
</div>

<!-- Alpine Logic -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qcmBuilder', (initialUnites) => ({
        titre: 'Nouveau QCM',
        isPublished: false,
        allUnites: initialUnites,
        selectedUniteId: '',
        
        get filteredCompetences() {
            if (!this.selectedUniteId) return [];
            const unite = this.allUnites.find(u => u.id == this.selectedUniteId);
            return unite ? unite.competences : [];
        },
        questions: [
            {
                texte: '',
                type: 'choix_unique',
                points: 1,
                explication: '',
                options: [
                    { texte: '', est_correcte: true },
                    { texte: '', est_correcte: false }
                ]
            }
        ],

        addQuestion() {
            this.questions.push({
                texte: '',
                type: 'choix_unique',
                points: 1,
                explication: '',
                options: [
                    { texte: '', est_correcte: false },
                    { texte: '', est_correcte: false }
                ]
            });
        },

        removeQuestion(qIndex) {
            if (this.questions.length > 1) {
                this.questions.splice(qIndex, 1);
            }
        },

        addOption(qIndex) {
            this.questions[qIndex].options.push({ texte: '', est_correcte: false });
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
        }
    }));
});
</script>
@endsection
