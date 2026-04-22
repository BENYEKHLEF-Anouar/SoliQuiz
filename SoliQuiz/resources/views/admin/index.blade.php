@extends('layouts.app')

@section('title', 'Admin Dashboard - SoliQuiz')

@section('content')
<div class="space-y-12" 
     x-data="{ 
        // Filter States
        search: '{{ $search }}',
        profil: '{{ $profil }}',
        qcmSearch: '{{ $qcmSearch }}',

        init() {
            // Set initial tab from server
            $store.router.page = '{{ $tab }}';
        },

        applyUserFilters() {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', 'users');
            url.searchParams.set('search', this.search);
            url.searchParams.set('profil', this.profil);
            url.searchParams.delete('page'); // Reset pagination on new search
            window.location.href = url.toString();
        },

        applyQcmFilters() {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', 'qcms');
            url.searchParams.set('qcm_search', this.qcmSearch);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        },

        resetFilters() {
            const url = new URL(window.location.href);
            url.search = '';
            url.searchParams.set('tab', $store.router.page);
            window.location.href = url.toString();
        }
     }">
    
    <!-- Dashboard View -->
    <div x-show="$store.router.page === 'dashboard'" 
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="opacity-0 translate-y-8" 
         x-transition:enter-end="opacity-100 translate-y-0">
        
        <!-- Welcome Banner -->
        <div class="relative overflow-hidden bg-slate-900 rounded-[3rem] p-12 mb-12 group">
            <div class="absolute top-0 right-0 size-96 bg-primary-500/20 rounded-full blur-[100px] -mr-32 -mt-32 group-hover:bg-primary-500/30 transition-all duration-700"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-heading font-black text-white tracking-tight uppercase leading-none mb-4">
                        Bonjour, <span class="text-primary-500">{{ Auth::user()->prenom }}</span>
                    </h1>
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em] ">Bienvenue sur votre centre de commandement SoliQuiz.</p>
                </div>
                <div class="flex gap-4">
                    <div class="px-6 py-4 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10">
                        <p class="text-[8px] font-black text-primary-400 uppercase tracking-widest  mb-1">Statut Système</p>
                        <div class="flex items-center gap-2">
                            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-black text-white uppercase ">Opérationnel</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <x-kpi-card title="Formateurs" :value="$kpis['nb_formateurs']" icon="users" color="primary" />
            <x-kpi-card title="Étudiants" :value="$kpis['nb_etudiants']" icon="graduation-cap" color="indigo" />
            <x-kpi-card title="QCM Actifs" :value="$kpis['nb_qcms_publie']" icon="file-check" color="emerald" />
            <x-kpi-card title="Score Moyen" :value="$kpis['score_moyen'] . '/20'" icon="award" color="amber" />
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mt-16">
            <!-- Quick Actions -->
            <div class="lg:col-span-1 space-y-4">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]  ml-6 mb-6">Actions de gestion</h3>
                <button @click="$store.router.navigate('users')" class="w-full flex items-center justify-between p-8 bg-white border border-slate-100 rounded-[2.5rem] hover:border-primary-500/20 hover:shadow-xl hover:shadow-primary-500/5 transition-all group">
                    <div class="flex items-center gap-5">
                        <div class="size-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-900 group-hover:bg-primary-500 group-hover:text-white transition-all">
                            <x-lucide-icon name="user-plus" size="5" />
                        </div>
                        <span class="text-xs font-black uppercase tracking-widest ">Utilisateurs</span>
                    </div>
                    <x-lucide-icon name="arrow-right" size="4" class="text-slate-200 group-hover:text-primary-500 group-hover:translate-x-1 transition-all" />
                </button>
                <button @click="$store.router.navigate('pedagogie')" class="w-full flex items-center justify-between p-8 bg-white border border-slate-100 rounded-[2.5rem] hover:border-indigo-500/20 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="flex items-center gap-5">
                        <div class="size-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-900 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                            <x-lucide-icon name="book-open" size="5" />
                        </div>
                        <span class="text-xs font-black uppercase tracking-widest ">Pédagogie</span>
                    </div>
                    <x-lucide-icon name="arrow-right" size="4" class="text-slate-200 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" />
                </button>
            </div>

            <!-- Top QCMs -->
            <div class="lg:col-span-2 bg-white rounded-[3rem] border border-slate-100 p-10">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">QCM les plus actifs</h3>
                    <button @click="$store.router.navigate('qcms')" class="text-[9px] font-black text-primary-500 uppercase tracking-widest  hover:underline">Voir tout</button>
                </div>
                <div class="space-y-4">
                    @foreach($qcms->take(4) as $qcm)
                    <div @click="$store.router.navigate('qcms')" 
                         class="flex items-center justify-between p-6 bg-slate-50/50 rounded-3xl border border-slate-50 hover:border-primary-100 transition-colors group cursor-pointer">
                        <div class="flex items-center gap-5">
                            <div class="size-12 bg-white rounded-2xl flex items-center justify-center text-primary-500 shadow-sm border border-slate-100">
                                <x-lucide-icon name="file-text" size="5" />
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $qcm->titre }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ">Par {{ $qcm->formateur->nom }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-900">{{ $qcm->tentatives_count }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ">Prises</p>
                            </div>
                            <div class="size-8 bg-white rounded-xl flex items-center justify-center text-slate-200 group-hover:text-primary-500 transition-colors">
                                <x-lucide-icon name="chevron-right" size="4" />
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Users View -->
    <div x-show="$store.router.page === 'users'" 
         x-data="{ 
            users: {{ json_encode($users->items()) }},
            classes: {{ json_encode($classes) }},
            showUserModal: false,
            userMode: 'create',
            currentUser: { nom: '', prenom: '', email: '', password: '', type_profil: 'etudiant', classe_id: null, matricule: '' },
            selectedUsers: [],

            openUserModal(mode, user = {}) {
                this.userMode = mode;
                this.currentUser = mode === 'create' 
                    ? { nom: '', prenom: '', email: '', password: '', type_profil: 'etudiant', classe_id: null, matricule: '' } 
                    : { ...user, password: '' };
                this.showUserModal = true;
            },

            async submitUser() {
                let url = this.userMode === 'create' ? '/admin/users' : `/admin/users/${this.currentUser.id}`;
                let method = this.userMode === 'create' ? 'POST' : 'PUT';
                
                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify(this.currentUser)
                    });
                    const res = await response.json();
                    if (response.ok) {
                        $store.toasts.add(res.message, 'success');
                        this.showUserModal = false;
                        location.reload();
                    } else {
                        $store.toasts.add(res.message || 'Erreur lors de l\'enregistrement', 'error');
                    }
                } catch (e) { $store.toasts.add('Erreur réseau', 'error'); }
            },

            deleteUser(id) {
                $store.confirm.ask(
                    'Supprimer l\'utilisateur',
                    'Cette action est irréversible. Toutes les données associées seront perdues.',
                    async () => {
                        try {
                            const response = await fetch(`/admin/users/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content') }
                            });
                            if (response.ok) {
                                $store.toasts.add('Utilisateur supprimé', 'success');
                                location.reload();
                            }
                        } catch (e) { $store.toasts.add('Erreur', 'error'); }
                    }
                );
            },

            async massAssign(classeId) {
                if (this.selectedUsers.length === 0) return;
                try {
                    const response = await fetch('/admin/users/mass-assign', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify({ user_ids: this.selectedUsers, classe_id: classeId })
                    });
                    if (response.ok) {
                        $store.toasts.add('Affectation en masse réussie', 'success');
                        location.reload();
                    }
                } catch (e) { $store.toasts.add('Erreur', 'error'); }
            }
         }"
         x-transition x-cloak>
        
        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight uppercase leading-none mb-4">Utilisateurs</h1>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em] ">Gérer les accès et les profils</p>
            </div>
            <div class="flex gap-4">
                <template x-if="selectedUsers.length > 0">
                    <div class="flex items-center gap-3 bg-indigo-50 px-4 py-2 rounded-2xl border border-indigo-100">
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest " x-text="selectedUsers.length + ' sélectionnés'"></span>
                        <select @change="massAssign($event.target.value)" class="bg-white border-none rounded-xl py-2 px-4 text-[9px] font-black uppercase tracking-widest  focus:ring-0">
                            <option value="">Affecter classe...</option>
                            <template x-for="c in classes" :key="c.id">
                                <option :value="c.id" x-text="c.nom"></option>
                            </template>
                        </select>
                    </div>
                </template>
                <button @click="openUserModal('create')" class="py-4 px-8 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-primary-500 transition-all uppercase tracking-widest  flex items-center gap-3">
                    <x-lucide-icon name="plus" size="4" />
                    Créer un Utilisateur
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/20 overflow-hidden">
            <!-- Table Filter Bar -->
            <div class="p-8 bg-slate-50/50 border-b border-slate-100 flex flex-wrap gap-6 items-center justify-between">
                <div class="flex items-center gap-6 flex-1 min-w-[300px]">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-300">
                            <x-lucide-icon name="search" size="4" />
                        </div>
                        <input type="text" placeholder="Rechercher un collaborateur..." 
                               x-model="search"
                               @keydown.enter="applyUserFilters()"
                               class="w-full bg-white border border-slate-100 rounded-2xl py-4 pl-12 pr-6 text-xs font-bold focus:ring-4 focus:ring-primary-500/5 transition-all  placeholder:text-slate-300">
                    </div>
                    <select x-model="profil" @change="applyUserFilters()"
                            class="bg-white border border-slate-100 rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest  focus:ring-4 focus:ring-primary-500/5 cursor-pointer">
                        <option value="">Tous les Profils</option>
                        <option value="admin">Administrateur</option>
                        <option value="formateur">Formateur</option>
                        <option value="etudiant">Étudiant</option>
                    </select>
                    <button @click="resetFilters()" class="text-[9px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest  transition-colors">Réinitialiser</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white">
                            <th class="w-12 px-8 py-6">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" @change="selectedUsers = $event.target.checked ? users.map(u => u.id) : []" class="size-5 rounded-lg border-slate-200 text-primary-500 focus:ring-primary-500/10 transition-all">
                                </div>
                            </th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ">Utilisateur</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ">Rôle / Profil</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ">Affectation</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="user in users" :key="user.id">
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center">
                                        <input type="checkbox" :value="user.id" x-model="selectedUsers" class="size-5 rounded-lg border-slate-200 text-primary-500 focus:ring-primary-500/10 transition-all">
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="size-12 rounded-2xl bg-slate-900 flex items-center justify-center text-primary-500 font-black text-xs  shadow-lg shadow-slate-900/10" x-text="user.nom.charAt(0) + user.prenom.charAt(0)"></div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 uppercase tracking-tight" x-text="user.prenom + ' ' + user.nom"></p>
                                            <p class="text-[10px] font-bold text-slate-400 lowercase " x-text="user.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border"
                                        :class="{
                                            'bg-rose-50 border-rose-100 text-rose-600': user.type_profil === 'admin',
                                            'bg-indigo-50 border-indigo-100 text-indigo-600': user.type_profil === 'formateur',
                                            'bg-emerald-50 border-emerald-100 text-emerald-600': user.type_profil === 'etudiant'
                                        }">
                                        <div class="size-1.5 rounded-full" :class="{ 'bg-rose-500': user.type_profil === 'admin', 'bg-indigo-500': user.type_profil === 'formateur', 'bg-emerald-500': user.type_profil === 'etudiant' }"></div>
                                        <span class="text-[9px] font-black uppercase tracking-widest " x-text="user.type_profil"></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-icon name="hash" size="3" class="text-slate-300" />
                                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-tight " x-text="user.matricule || user.classe?.nom || 'Non assigné'"></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openUserModal('edit', user)" class="size-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary-500 hover:border-primary-100 hover:shadow-lg hover:shadow-primary-500/10 transition-all"><x-lucide-icon name="edit-3" size="4" /></button>
                                        <button @click="deleteUser(user.id)" class="size-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-100 hover:shadow-lg hover:shadow-rose-500/10 transition-all"><x-lucide-icon name="trash-2" size="4" /></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ">Page {{ $users->currentPage() }} sur {{ $users->lastPage() }} — Total {{ $users->total() }}</p>
                <div class="flex gap-2 pagination-luminous">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        <!-- User Create/Edit Modal -->
        <div x-show="showUserModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showUserModal = false"></div>
            <div class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl overflow-hidden p-10 reveal" x-transition>
                <div class="mb-8">
                    <h3 class="text-2xl font-heading font-black text-slate-900 uppercase tracking-tight leading-none mb-2" x-text="userMode === 'create' ? 'Nouvel Utilisateur' : 'Modifier Profil'"></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ">Informations d'identification</p>
                </div>

                <form @submit.prevent="submitUser()" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Nom</label>
                            <input type="text" x-model="currentUser.nom" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Prénom</label>
                            <input type="text" x-model="currentUser.prenom" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Email Professionnel</label>
                        <input type="email" x-model="currentUser.email" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Mot de passe <template x-if="userMode === 'edit'"><span class="normal-case opacity-50">(Laisser vide pour ne pas changer)</span></template></label>
                        <input type="password" x-model="currentUser.password" :required="userMode === 'create'" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Profil</label>
                            <select x-model="currentUser.type_profil" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase ">
                                <option value="admin">Administrateur</option>
                                <option value="formateur">Formateur</option>
                                <option value="etudiant">Étudiant</option>
                            </select>
                        </div>
                        
                        <template x-if="currentUser.type_profil === 'etudiant'">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Classe</label>
                                <select x-model="currentUser.classe_id" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase ">
                                    <option value="">Sélectionner...</option>
                                    <template x-for="c in classes" :key="c.id">
                                        <option :value="c.id" x-text="c.nom" :selected="c.id == currentUser.classe_id"></option>
                                    </template>
                                </select>
                            </div>
                        </template>

                        <template x-if="currentUser.type_profil === 'formateur'">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Matricule</label>
                                <input type="text" x-model="currentUser.matricule" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold ">
                            </div>
                        </template>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 py-4 bg-slate-900 text-white text-[10px] font-black rounded-xl hover:bg-primary-500 transition-all uppercase tracking-widest ">Enregistrer</button>
                        <button type="button" @click="showUserModal = false" class="py-4 px-8 bg-slate-50 text-slate-400 text-[10px] font-black rounded-xl hover:bg-slate-100 transition-all uppercase tracking-widest ">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pedagogie View -->
    <div x-show="$store.router.page === 'pedagogie'" 
         x-data="{ 
            structure: {{ json_encode($structure) }},
            showModal: false,
            modalType: '', // seance, ua, competence
            modalMode: 'create',
            currentItem: {},
            parentId: null,

            openModal(type, mode, item = {}, pId = null) {
                this.modalType = type;
                this.modalMode = mode;
                this.currentItem = mode === 'create' ? { titre: '', description: '', code: '' } : { ...item };
                this.parentId = pId;
                this.showModal = true;
            },

            async submitForm() {
                let url = '';
                let method = this.modalMode === 'create' ? 'POST' : 'PUT';
                let body = { ...this.currentItem };

                if (this.modalType === 'seance') {
                    url = this.modalMode === 'create' ? '/admin/seances' : `/admin/seances/${this.currentItem.id}`;
                } else if (this.modalType === 'ua') {
                    url = this.modalMode === 'create' ? '/admin/uas' : `/admin/uas/${this.currentItem.id}`;
                    if (this.modalMode === 'create') body.seance_id = this.parentId;
                } else if (this.modalType === 'competence') {
                    url = this.modalMode === 'create' ? '/admin/competences' : `/admin/competences/${this.currentItem.id}`;
                    if (this.modalMode === 'create') body.unite_apprentissage_id = this.parentId;
                }

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                        },
                        body: JSON.stringify(body)
                    });
                    
                    if (response.ok) {
                        const res = await response.json();
                        $store.toasts.add(res.message, 'success');
                        this.showModal = false;
                        location.reload(); // Refresh to get updated structure (In a true SPA we'd update state)
                    } else {
                        $store.toasts.add('Erreur lors de l\'enregistrement', 'error');
                    }
                } catch (e) {
                    $store.toasts.add('Erreur réseau', 'error');
                }
            },

            deleteItem(type, id) {
                $store.confirm.ask(
                    'Confirmer la suppression',
                    `Voulez-vous vraiment supprimer cet(te) ${type} ? Cela pourrait impacter les éléments enfants.`,
                    async () => {
                        let url = `/admin/${type}s/${id}`;
                        try {
                            const response = await fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                                }
                            });
                            if (response.ok) {
                                $store.toasts.add('Supprimé avec succès', 'success');
                                location.reload();
                            }
                        } catch (e) {
                            $store.toasts.add('Erreur lors de la suppression', 'error');
                        }
                    }
                );
            }
         }"
         x-transition x-cloak>
        
        <div class="flex justify-between items-end mb-12">
            <div>
                <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight uppercase leading-none mb-4">Structure Pédagogique</h1>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em] ">Architecture des modules et compétences</p>
            </div>
            <button @click="openModal('seance', 'create')" class="py-4 px-8 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-primary-500 transition-all uppercase tracking-widest  flex items-center gap-3">
                <x-lucide-icon name="plus" size="4" />
                Nouvelle Séance
            </button>
        </div>

        <div class="space-y-8">
            <template x-for="seance in structure" :key="seance.id">
                <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <!-- Séance Header -->
                    <div class="p-8 bg-slate-50/50 flex justify-between items-center border-b border-slate-100">
                        <div class="flex items-center gap-6">
                            <div class="size-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 border border-slate-100 font-black text-xs ">
                                S<span x-text="seance.id"></span>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight" x-text="seance.titre"></h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest " x-text="seance.unites_apprentissage.length + ' Unités d\'Apprentissage'"></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="openModal('ua', 'create', {}, seance.id)" class="p-3 bg-white text-emerald-500 rounded-xl hover:bg-emerald-50 transition-colors shadow-sm border border-slate-100"><x-lucide-icon name="plus" size="4" /></button>
                            <button @click="openModal('seance', 'edit', seance)" class="p-3 bg-white text-slate-400 hover:text-primary-500 rounded-xl transition-colors shadow-sm border border-slate-100"><x-lucide-icon name="edit-2" size="4" /></button>
                            <button @click="deleteItem('seance', seance.id)" class="p-3 bg-white text-slate-400 hover:text-rose-500 rounded-xl transition-colors shadow-sm border border-slate-100"><x-lucide-icon name="trash-2" size="4" /></button>
                        </div>
                    </div>

                    <!-- UAs Content -->
                    <div class="p-8 space-y-6">
                        <template x-for="ua in seance.unites_apprentissage" :key="ua.id">
                            <div class="border-l-2 border-slate-100 pl-8 relative">
                                <div class="absolute -left-[5px] top-0 size-2 rounded-full bg-slate-200"></div>
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight" x-text="ua.titre"></h4>
                                        <p class="text-[10px] text-slate-400  font-medium mt-1" x-text="ua.description || 'Aucune description'"></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button @click="openModal('competence', 'create', {}, ua.id)" class="text-[9px] font-black uppercase tracking-widest text-primary-500 hover:text-primary-600 px-3 py-1 bg-primary-50 rounded-lg ">Add Compétence</button>
                                        <button @click="openModal('ua', 'edit', ua)" class="p-1.5 text-slate-300 hover:text-slate-600 transition-colors"><x-lucide-icon name="edit-3" size="3.5" /></button>
                                        <button @click="deleteItem('ua', ua.id)" class="p-1.5 text-slate-300 hover:text-rose-500 transition-colors"><x-lucide-icon name="trash" size="3.5" /></button>
                                    </div>
                                </div>

                                <!-- Compétences -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <template x-for="comp in ua.competences" :key="comp.id">
                                        <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 flex justify-between items-center group/comp">
                                            <div class="flex items-center gap-3">
                                                <div class="size-7 bg-white rounded-lg flex items-center justify-center text-[9px] font-black text-slate-400 border border-slate-100 " x-text="comp.code || 'C'"></div>
                                                <span class="text-[11px] font-bold text-slate-600 truncate max-w-[120px]" x-text="comp.titre"></span>
                                            </div>
                                            <div class="flex gap-1 opacity-0 group-hover/comp:opacity-100 transition-opacity">
                                                <button @click="openModal('competence', 'edit', comp)" class="p-1 text-slate-300 hover:text-primary-500"><x-lucide-icon name="edit-3" size="3" /></button>
                                                <button @click="deleteItem('competence', comp.id)" class="p-1 text-slate-300 hover:text-rose-500"><x-lucide-icon name="x" size="3" /></button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="seance.unites_apprentissage.length === 0">
                            <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest  text-center py-4">Aucune unité d'apprentissage</p>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Global Pedagogie Modal -->
        <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl overflow-hidden p-10 reveal" x-transition>
                <div class="mb-8">
                    <h3 class="text-2xl font-heading font-black text-slate-900 uppercase tracking-tight leading-none mb-2" x-text="modalMode === 'create' ? 'Ajouter' : 'Modifier'"></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] " x-text="modalType"></p>
                </div>

                <form @submit.prevent="submitForm()" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Titre / Libellé</label>
                        <input type="text" x-model="currentItem.titre" required
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-4 focus:ring-primary-500/5 transition-all ">
                    </div>

                    <template x-if="modalType === 'ua'">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Description</label>
                            <textarea x-model="currentItem.description" rows="3"
                                      class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-4 focus:ring-primary-500/5 transition-all "></textarea>
                        </div>
                    </template>

                    <template x-if="modalType === 'competence'">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Code (ex: CP1)</label>
                            <input type="text" x-model="currentItem.code"
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold focus:ring-4 focus:ring-primary-500/5 transition-all ">
                        </div>
                    </template>

                    <div class="pt-4 flex gap-4">
                        <button type="submit" class="flex-1 py-4 bg-slate-900 text-white text-[10px] font-black rounded-xl hover:bg-primary-500 transition-all uppercase tracking-widest ">
                            Enregistrer
                        </button>
                        <button type="button" @click="showModal = false" class="py-4 px-8 bg-slate-50 text-slate-400 text-[10px] font-black rounded-xl hover:bg-slate-100 transition-all uppercase tracking-widest ">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- QCM Supervision View -->
    <div x-show="$store.router.page === 'qcms'" 
         x-data="{ 
            qcms: {{ json_encode($qcms->items()) }},
            async toggleStatus(id) {
                try {
                    const response = await fetch(`/admin/qcms/${id}/toggle`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content') }
                    });
                    if (response.ok) {
                        const res = await response.json();
                        $store.toasts.add(res.message, 'success');
                        location.reload();
                    }
                } catch (e) { $store.toasts.add('Erreur', 'error'); }
            },
            deleteQcm(id) {
                $store.confirm.ask(
                    'Supprimer l\'évaluation',
                    'Voulez-vous vraiment supprimer ce QCM ? Les résultats des étudiants seront également supprimés.',
                    async () => {
                        try {
                            const response = await fetch(`/admin/qcms/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content') }
                            });
                            if (response.ok) {
                                $store.toasts.add('QCM supprimé', 'success');
                                location.reload();
                            }
                        } catch (e) { $store.toasts.add('Erreur', 'error'); }
                    }
                );
            }
         }"
         x-transition x-cloak>
        
        <div class="mb-10">
            <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight uppercase leading-none mb-4">Supervision QCM</h1>
            <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em] ">Surveillance et contrôle des évaluations</p>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/20 overflow-hidden">
            <!-- Filter Bar for QCMs -->
            <div class="p-8 bg-slate-50/50 border-b border-slate-100 flex flex-wrap gap-6 items-center justify-between">
                <div class="flex items-center gap-6 flex-1 min-w-[300px]">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-300">
                            <x-lucide-icon name="search" size="4" />
                        </div>
                        <input type="text" placeholder="Rechercher une évaluation..." 
                               x-model="qcmSearch"
                               @keydown.enter="applyQcmFilters()"
                               class="w-full bg-white border border-slate-100 rounded-2xl py-4 pl-12 pr-6 text-xs font-bold focus:ring-4 focus:ring-primary-500/5 transition-all  placeholder:text-slate-300">
                    </div>
                    <button @click="resetFilters()" class="text-[9px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest  transition-colors">Réinitialiser</button>
                </div>
            </div>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-white">
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">Titre & Unité</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">Formateur</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">Performance</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">Statut</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="qcm in qcms" :key="qcm.id">
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="size-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-500">
                                        <x-lucide-icon name="file-text" size="5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 uppercase tracking-tight" x-text="qcm.titre"></p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest " x-text="qcm.unite_apprentissage?.titre || 'Général'"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-black text-[10px] " x-text="qcm.formateur.nom.charAt(0)"></div>
                                    <span class="text-[11px] font-bold text-slate-600" x-text="qcm.formateur.prenom + ' ' + qcm.formateur.nom"></span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-6">
                                    <div class="text-center">
                                        <p class="text-[10px] font-black text-slate-900" x-text="qcm.tentatives_count"></p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ">Passages</p>
                                    </div>
                                    <div class="w-px h-6 bg-slate-100"></div>
                                    <div class="text-center">
                                        <p class="text-[10px] font-black text-emerald-500" x-text="(parseFloat(qcm.tentatives_avg_score_obtenu) || 0).toFixed(1) + '/20'"></p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest ">Moyenne</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <button @click="toggleStatus(qcm.id)" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                        :class="qcm.est_publie ? 'bg-emerald-500 shadow-lg shadow-emerald-500/20' : 'bg-slate-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm"
                                          :class="qcm.est_publie ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="size-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-300 hover:text-slate-900 hover:border-slate-200 transition-all"><x-lucide-icon name="eye" size="4" /></button>
                                    <button @click="deleteQcm(qcm.id)" class="size-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 hover:border-rose-100 transition-all"><x-lucide-icon name="trash-2" size="4" /></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
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
                        this.profileData.password = '';
                        this.profileData.password_confirmation = '';
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        $store.toasts.add(res.message || 'Erreur lors de la mise à jour', 'error');
                    }
                } catch (e) { $store.toasts.add('Erreur réseau', 'error'); }
            }
         }"
         x-transition x-cloak>
        
        <div class="mb-10">
            <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight uppercase leading-none mb-4">Mon Profil</h1>
            <p class="text-slate-400 text-sm font-bold uppercase tracking-[0.2em] ">Gérer vos informations personnelles et sécurité</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[3rem] border border-slate-100 p-12 shadow-sm">
                    <form @submit.prevent="updateProfile()" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Nom</label>
                                <input type="text" x-model="profileData.nom" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold  focus:ring-4 focus:ring-primary-500/5">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Prénom</label>
                                <input type="text" x-model="profileData.prenom" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold  focus:ring-4 focus:ring-primary-500/5">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Email</label>
                            <input type="email" x-model="profileData.email" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold  focus:ring-4 focus:ring-primary-500/5">
                        </div>

                        <div class="h-px bg-slate-50 my-4"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Nouveau Mot de passe</label>
                                <input type="password" x-model="profileData.password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold  focus:ring-4 focus:ring-primary-500/5" placeholder="Laisser vide pour garder l'actuel">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest  ml-2">Confirmer le Mot de passe</label>
                                <input type="password" x-model="profileData.password_confirmation" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-sm font-bold  focus:ring-4 focus:ring-primary-500/5">
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full py-4 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-primary-500 transition-all uppercase tracking-widest ">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 size-32 bg-primary-500/20 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="size-16 rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center mb-6">
                            <x-lucide-icon name="shield-check" size="8" class="text-primary-400" />
                        </div>
                        <h4 class="text-xl font-black uppercase tracking-tight mb-2">Compte Sécurisé</h4>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest  leading-relaxed">
                            Votre profil administrateur est protégé.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
