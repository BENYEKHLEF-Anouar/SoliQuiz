@extends('layouts.app')

@section('title', 'Formateur Dashboard - SoliQuiz')

@section('content')
<div class="space-y-12">
    
    <!-- Dashboard View -->
    <div x-show="$store.router.page === 'dashboard'" x-transition x-cloak>
        <div class="mb-10">
            <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight uppercase leading-none mb-4">Tableau de Bord</h1>
            <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em]  mb-2">Statistiques de vos évaluations</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <x-kpi-card title="Mes QCM" :value="$kpis['nb_qcms']" icon="file-text" color="indigo" />
            <x-kpi-card title="QCM Actifs" :value="$kpis['nb_qcms_actifs']" icon="play-circle" color="emerald" />
            <x-kpi-card title="Tentatives" :value="$kpis['nb_tentatives']" icon="users" color="primary" />
            <x-kpi-card title="Moyenne Gnl" :value="$kpis['score_moyen'] . '/20'" icon="award" color="amber" />
        </div>

        <!-- Quick Actions -->
        <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="bg-white rounded-[3rem] border border-slate-100 p-10">
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-8">Raccourcis Studio</h3>
                <div class="space-y-4">
                    <button @click="$store.router.navigate('studio')" class="w-full flex items-center justify-between p-6 bg-indigo-50/50 rounded-3xl hover:bg-indigo-500 hover:text-white transition-all group border border-indigo-100/50">
                        <div class="flex items-center gap-4">
                            <div class="size-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-indigo-500 group-hover:bg-white/20 group-hover:text-white">
                                <x-lucide-icon name="plus" size="5" />
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-widest ">Créer un nouveau QCM</span>
                        </div>
                        <x-lucide-icon name="chevron-right" size="4" />
                    </button>
                    <button @click="$store.router.navigate('results')" class="w-full flex items-center justify-between p-6 bg-slate-50 rounded-3xl hover:bg-emerald-500 hover:text-white transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="size-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-slate-900 group-hover:bg-white/20 group-hover:text-white">
                                <x-lucide-icon name="bar-chart" size="5" />
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-widest ">Voir les derniers résultats</span>
                        </div>
                        <x-lucide-icon name="chevron-right" size="4" />
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] border border-slate-100 p-10">
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-8">Dernières Tentatives</h3>
                <div class="space-y-4">
                    <p class="text-center py-8 text-[10px] font-bold text-slate-300 uppercase tracking-widest ">Aucune activité récente</p>
                </div>
            </div>
        </div>
    </div>

    <!-- QCM Studio View (List) -->
    <div x-show="$store.router.page === 'studio'" 
         x-data="{ 
            qcms: {{ json_encode($qcms->items()) }},
            showModal: false,
            currentQcm: { titre: '', duree_minutes: 30, score_reussite: 10, unite_apprentissage_id: '', est_publie: false },
            
            // Editor State
            editorActive: false,
            editorQcm: { titre: '', questions: [] },

            async openEditor(id) {
                try {
                    const response = await fetch(`/formateur/qcms/${id}`);
                    if (response.ok) {
                        this.editorQcm = await response.json();
                        this.editorActive = true;
                    }
                } catch (e) { $store.toasts.add('Erreur lors du chargement', 'error'); }
            },

            addQuestion() {
                this.editorQcm.questions.push({
                    texte: 'Nouvelle question',
                    type: 'unique',
                    points: 1,
                    options: [
                        { texte: 'Option 1', est_correcte: true },
                        { texte: 'Option 2', est_correcte: false }
                    ]
                });
            },

            removeQuestion(index) {
                this.editorQcm.questions.splice(index, 1);
            },

            addOption(question) {
                question.options.push({ texte: 'Nouvelle option', est_correcte: false });
            },

            removeOption(question, oIndex) {
                question.options.splice(oIndex, 1);
            },

            async saveEditor() {
                try {
                    const response = await fetch(`/formateur/qcms/${this.editorQcm.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify(this.editorQcm)
                    });
                    if (response.ok) {
                        $store.toasts.add('QCM sauvegardé', 'success');
                        this.editorActive = false;
                        location.reload();
                    }
                } catch (e) { $store.toasts.add('Erreur lors de la sauvegarde', 'error'); }
            },

            async submitQcm() {
                try {
                    const response = await fetch('/formateur/qcms', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify({ ...this.currentQcm, formateur_id: {{ Auth::id() }} })
                    });
                    if (response.ok) {
                        $store.toasts.add('QCM créé avec succès', 'success');
                        location.reload();
                    }
                } catch (e) { $store.toasts.add('Erreur', 'error'); }
            },

            async toggleStatus(id) {
                try {
                    const response = await fetch(`/admin/qcms/${id}/toggle`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content') }
                    });
                    if (response.ok) { location.reload(); }
                } catch (e) { $store.toasts.add('Erreur', 'error'); }
            }
         }"
         x-transition x-cloak>
        
        <template x-if="!editorActive">
            <div>
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight uppercase leading-none mb-4">QCM Studio</h1>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em] ">Conception et gestion de vos évaluations</p>
                    </div>
                    <button @click="showModal = true" class="py-4 px-8 bg-indigo-600 text-white text-[10px] font-black rounded-2xl hover:bg-indigo-700 transition-all uppercase tracking-widest  flex items-center gap-3">
                        <x-lucide-icon name="plus" size="4" />
                        Nouveau QCM
                    </button>
                </div>

                <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] ">Titre & UA</th>
                                <th class="px-8 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] ">Questions</th>
                                <th class="px-8 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] ">Statut</th>
                                <th class="px-8 py-5 text-right text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] ">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="qcm in qcms" :key="qcm.id">
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-8 py-6">
                                        <div>
                                            <p class="text-sm font-black text-slate-900 uppercase tracking-tight" x-text="qcm.titre"></p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest " x-text="qcm.unite_apprentissage?.titre || 'Général'"></p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-600" x-text="qcm.questions_count + ' Questions'"></span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <button @click="toggleStatus(qcm.id)" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                                :class="qcm.est_publie ? 'bg-emerald-500' : 'bg-slate-200'">
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                                  :class="qcm.est_publie ? 'translate-x-6' : 'translate-x-1'"></span>
                                        </button>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openEditor(qcm.id)" class="p-2 text-slate-300 hover:text-indigo-500 transition-colors"><x-lucide-icon name="edit-3" size="4" /></button>
                                            <button class="p-2 text-slate-300 hover:text-rose-500 transition-colors"><x-lucide-icon name="trash-2" size="4" /></button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <!-- Editor View -->
        <template x-if="editorActive">
            <div class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-6">
                        <button @click="editorActive = false" class="size-12 bg-white rounded-2xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors shadow-sm">
                            <x-lucide-icon name="chevron-left" size="5" />
                        </button>
                        <div>
                            <h2 class="text-2xl font-heading font-black text-slate-900 uppercase tracking-tight leading-none" x-text="editorQcm.titre"></h2>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest  mt-2">Édition des questions</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <button @click="addQuestion()" class="py-4 px-6 bg-white border border-slate-100 text-[10px] font-black text-slate-600 rounded-2xl hover:bg-slate-50 transition-all uppercase tracking-widest  flex items-center gap-3">
                            <x-lucide-icon name="plus" size="4" />
                            Ajouter Question
                        </button>
                        <button @click="saveEditor()" class="py-4 px-10 bg-indigo-600 text-white text-[10px] font-black rounded-2xl hover:bg-indigo-700 transition-all uppercase tracking-widest  shadow-xl shadow-indigo-600/20">
                            Sauvegarder les modifications
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                    <template x-for="(q, index) in editorQcm.questions" :key="index">
                        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-10 shadow-sm relative group/q">
                            <button @click="removeQuestion(index)" class="absolute top-6 right-6 p-2 text-slate-200 hover:text-rose-500 opacity-0 group-hover/q:opacity-100 transition-all">
                                <x-lucide-icon name="trash" size="4" />
                            </button>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
                                <!-- Question Text -->
                                <div class="lg:col-span-2 space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center font-black text-xs " x-text="index + 1"></div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ">Énoncé de la question</label>
                                    </div>
                                    <textarea x-model="q.texte" rows="3" class="w-full bg-slate-50 border-none rounded-2xl p-6 text-sm font-bold focus:ring-4 focus:ring-indigo-500/5 transition-all"></textarea>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest  ml-2">Type</label>
                                            <select x-model="q.type" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase ">
                                                <option value="unique">Choix Unique</option>
                                                <option value="multiple">Choix Multiple</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest  ml-2">Points</label>
                                            <input type="number" x-model="q.points" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                                        </div>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="lg:col-span-2 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ">Réponses & Options</label>
                                        <button @click="addOption(q)" class="text-[9px] font-black text-indigo-500 uppercase tracking-widest  hover:underline">Ajouter Option</button>
                                    </div>
                                    <div class="space-y-3">
                                        <template x-for="(o, oIndex) in q.options" :key="oIndex">
                                            <div class="flex items-center gap-3 group/o">
                                                <button @click="o.est_correcte = !o.est_correcte" 
                                                        class="size-10 rounded-xl flex items-center justify-center transition-all border shrink-0"
                                                        :class="o.est_correcte ? 'bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-50 border-slate-100 text-slate-300 hover:border-emerald-200'">
                                                    <x-lucide-icon name="check" size="4" />
                                                </button>
                                                <input type="text" x-model="o.texte" class="flex-1 bg-slate-50 border-none rounded-xl py-3 px-5 text-[11px] font-bold focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                                <button @click="removeOption(q, oIndex)" class="p-2 text-slate-200 hover:text-rose-500 transition-colors opacity-0 group-hover/o:opacity-100">
                                                    <x-lucide-icon name="x" size="3" />
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <template x-if="editorQcm.questions.length === 0">
                    <div class="py-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
                        <div class="size-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <x-lucide-icon name="help-circle" size="8" />
                        </div>
                        <h3 class="text-xl font-black text-slate-400 uppercase tracking-tight ">Aucune question</h3>
                        <button @click="addQuestion()" class="mt-6 text-[10px] font-black text-indigo-500 uppercase tracking-widest  hover:underline">Commencer par ajouter une question</button>
                    </div>
                </template>
            </div>
        </template>

        <!-- QCM Creation Modal (Keep previous modal code here or before template tags) -->
        <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
            <!-- ... modal content same as before ... -->
        </div>
    </div>

    <!-- Profile View -->
    <div x-show="$store.router.page === 'profile'" 
         x-data="{ 
            profileData: { 
                nom: '{{ Auth::user()->nom }}', 
                prenom: '{{ Auth::user()->prenom }}', 
                email: '{{ Auth::user()->email }}',
                password: '',
                password_confirmation: ''
            },
            async updateProfile() {
                try {
                    const response = await fetch('/profile', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify(this.profileData)
                    });
                    const res = await response.json();
                    if (response.ok) {
                        $store.toasts.add(res.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                } catch (e) { $store.toasts.add('Erreur réseau', 'error'); }
            }
         }"
         x-transition x-cloak>
        
        <div class="mb-10">
            <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight uppercase leading-none mb-4">Mon Profil</h1>
            <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em] ">Gérer vos informations personnelles</p>
        </div>

        <div class="max-w-2xl bg-white rounded-[3rem] border border-slate-100 p-12 shadow-sm">
            <form @submit.prevent="updateProfile()" class="space-y-8">
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Nom</label>
                        <input type="text" x-model="profileData.nom" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Prénom</label>
                        <input type="text" x-model="profileData.prenom" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Email</label>
                    <input type="email" x-model="profileData.email" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                </div>
                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-primary-500 transition-all uppercase tracking-widest ">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
