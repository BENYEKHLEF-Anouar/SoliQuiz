@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - SoliQuiz')

@section('page-title', 'Repertoire des Utilisateurs')

@section('content')
<div class="fade-in" x-data="{
    users: {{ Js::from($users->items()) }},
    search: '{{ $search }}',
    role: '{{ $role }}',
    loading: false,
    editUser: {},
    editUserRole: '',
    editUserClasse: '',
    searchTimer: null,
    totalCount: {{ $users->total() }},

    performSearch() {
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => {
            this.loading = true;
            let url = '{{ route('admin.utilisateurs.search') }}?search=' + encodeURIComponent(this.search);
            if (this.role) url += '&role=' + encodeURIComponent(this.role);

            fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => { 
                this.users = data.data; 
                this.totalCount = data.total;
                this.loading = false; 

                // Update URL
                const browserUrl = new URL(window.location);
                if (this.search) browserUrl.searchParams.set('search', this.search); else browserUrl.searchParams.delete('search');
                if (this.role) browserUrl.searchParams.set('role', this.role); else browserUrl.searchParams.delete('role');
                history.pushState({}, '', browserUrl);
            })
            .catch(() => { this.loading = false; });
        }, 300);
    }
}">
    <!-- Header -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Admin</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Utilisateurs</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Gestion des Agents
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Administrez les comptes, rôles et affectations de cohortes de tous les utilisateurs.
                </p>
            </div>
            <button @click="$dispatch('open-modal', 'add-user-modal')"
                    class="px-8 py-4 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest italic rounded-xl flex items-center gap-2 shrink-0 hover:bg-primary-600 active:scale-95 transition-all">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Nouvel Agent
            </button>
        </div>
    </div>


    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-4 mb-8">
        <div class="flex-1 relative group">
            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none ps-6">
                <svg class="size-4 text-slate-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
            <input x-model="search"
                   @input="performSearch()"
                   class="w-full bg-white border border-slate-100 rounded-2xl py-3 ps-14 pe-14 font-bold text-sm text-slate-900 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all shadow-sm group-hover:shadow-md h-[52px]"
                   type="text" placeholder="Rechercher par nom, email...">

            <!-- Live Search Loader -->
            <div x-show="loading" 
                 class="absolute right-5 top-1/2 -translate-y-1/2"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-50"
                 x-transition:enter-end="opacity-100 scale-100"
                 style="display: none;">
                <div class="size-4 border-2 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
            </div>
        </div>

        <div class="relative flex items-center self-stretch" x-data="{ open: false }">
            <button type="button" @click="open = !open" 
                    class="h-[52px] px-6 bg-white border border-slate-100 rounded-2xl transition-all shadow-sm group flex items-center gap-3 min-w-[200px]"
                    :class="role ? 'text-primary-600 bg-primary-50/50 border-primary-200' : 'text-slate-400'">
                <svg class="size-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span class="text-[10px] font-black uppercase tracking-wider" x-text="role ? (role === 'admin' ? 'Administrateurs' : (role === 'formateur' ? 'Formateurs' : 'Apprenants')) : 'Tous les rôles'">Tous les rôles</span>
            </button>

            <div x-show="open" @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute top-full right-0 mt-4 w-64 bg-white rounded-[24px] shadow-premium border border-slate-100 p-2 z-50 overflow-hidden"
                 style="display: none;">
                <button @click="role = ''; open = false; performSearch()" 
                        class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                        :class="!role ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50'">
                    Tous les rôles
                </button>
                <button @click="role = 'admin'; open = false; performSearch()" 
                        class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                        :class="role === 'admin' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50'">
                    Administrateurs
                </button>
                <button @click="role = 'formateur'; open = false; performSearch()" 
                        class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                        :class="role === 'formateur' ? 'bg-primary-500 text-white' : 'text-slate-600 hover:bg-primary-50'">
                    Formateurs
                </button>
                <button @click="role = 'etudiant'; open = false; performSearch()" 
                        class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                        :class="role === 'etudiant' ? 'bg-slate-200 text-slate-800' : 'text-slate-600 hover:bg-slate-50'">
                    Apprenants
                </button>
            </div>
        </div>
    </div>

    <!-- Users List Wrapper -->
    <div class="relative mt-6" :class="loading && users.length === 0 ? 'min-h-[200px]' : ''">
        <!-- Loading Overlay -->
        <div x-show="loading"
            class="absolute inset-0 bg-white/40 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center rounded-2xl min-h-[200px]"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;">
            <div class="flex flex-col items-center gap-3 scale-90">
                <div class="size-10 border-4 border-slate-100 border-t-primary-500 rounded-full animate-spin shadow-sm"></div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">Filtrage...
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm"
             :class="loading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
            <div class="overflow-visible">
                <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="ps-6 pe-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Profil Utilisateur</th>
                        <th class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Autorisation</th>
                        <th class="px-4 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Cohorte / Classe</th>
                        <th class="ps-4 pe-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Controle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="user in users" :key="user.id">
                        <tr class="group hover:bg-slate-50/60 transition-colors">
                            <td class="ps-6 pe-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="relative shrink-0">
                                        <img class="size-9 rounded-xl shadow-sm border border-slate-100"
                                             :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.nom + ' ' + user.prenom) + '&background=' + (user.type_profil === 'admin' ? '0f172a' : (user.type_profil === 'formateur' ? '0ea5e9' : 'f1f5f9')) + '&color=' + (user.type_profil === 'etudiant' ? '64748b' : 'fff') + '&bold=true'"
                                             alt="">
                                        <template x-if="user.type_profil === 'admin'">
                                            <div class="absolute -top-1 -right-1 size-3.5 bg-amber-400 rounded-full border border-white flex items-center justify-center">
                                                <svg class="size-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs font-black text-slate-900 group-hover:text-primary-600 transition-colors truncate" x-text="user.prenom + ' ' + user.nom"></span>
                                        <span class="text-[10px] font-bold text-slate-400 font-mono truncate" x-text="user.email"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <template x-if="user.type_profil === 'admin'">
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest">Administrateur</span>
                                </template>
                                <template x-if="user.type_profil === 'formateur'">
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-primary-50 text-primary-600 text-[9px] font-black uppercase tracking-widest border border-primary-100">Expert / Formateur</span>
                                </template>
                                <template x-if="user.type_profil === 'etudiant'">
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest border border-slate-200">Apprenant</span>
                                </template>
                            </td>
                            <td class="px-4 py-3">
                                <!-- Logique Affichage Formateur -->
                                <template x-if="user.type_profil === 'formateur' && user.classe_geree && user.classe_geree.length > 0">
                                    <div class="flex items-center gap-3">
                                        <div class="size-2 rounded-full bg-primary-500"></div>
                                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest" x-text="user.classe_geree[0].nom"></span>
                                        <template x-if="user.classe_geree.length > 1">
                                            <span class="text-[9px] font-black text-primary-500 bg-primary-50 px-1.5 py-0.5 rounded-md" x-text="'+' + (user.classe_geree.length - 1)"></span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="user.type_profil === 'formateur' && (!user.classe_geree || user.classe_geree.length === 0)">
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Sans Assignation</span>
                                </template>

                                <!-- Logique Affichage Etudiant -->
                                <template x-if="user.type_profil === 'etudiant' && user.classe">
                                    <div class="flex items-center gap-3">
                                        <div class="size-2 rounded-full bg-slate-400"></div>
                                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest" x-text="user.classe.nom"></span>
                                    </div>
                                </template>
                                <template x-if="user.type_profil === 'etudiant' && !user.classe">
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Indépendant</span>
                                </template>

                                <!-- Logique Affichage Admin -->
                                <template x-if="user.type_profil === 'admin'">
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Superviseur Global</span>
                                </template>
                            </td>
                            <td class="ps-4 pe-6 py-3 text-right">
                                <div class="flex justify-end items-center gap-2" x-data="{ options: false }">
                                    <!-- Primary Action: Edit -->
                                    <button @click="editUser = user; editUserRole = user.type_profil === 'admin' ? 'Administrateur' : (user.type_profil === 'formateur' ? 'Formateur' : 'Apprenant'); editUserClasse = (user.type_profil === 'formateur' && user.classe_geree && user.classe_geree.length > 0) ? user.classe_geree[0].id : (user.classe_id || ''); $dispatch('open-modal', 'edit-user-modal')"
                                            class="size-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-primary-500 hover:text-white transition-all flex items-center justify-center active:scale-95 group/edit">
                                        <svg class="size-4 group-hover/edit:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    
                                    <template x-if="user.id !== {{ Auth::id() }}">
                                        <div class="relative">
                                            <button @click="options = !options" @click.away="options = false"
                                                    class="size-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-200 transition-all flex items-center justify-center">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                            </button>

                                            <div x-show="options" 
                                                 class="absolute top-full right-0 mt-2 w-40 bg-white rounded-xl border border-slate-100 shadow-premium z-50 py-1.5 overflow-hidden"
                                                 x-transition:enter="transition ease-out duration-200" 
                                                 x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                 style="display: none;">
                                                
                                                <button type="button" 
                                                        @click.stop="$dispatch('confirm', { 
                                                            title: 'Archiver l\'utilisateur ?', 
                                                            message: 'L\'utilisateur ne pourra plus se connecter.', 
                                                            onConfirm: 'delete-user-' + user.id 
                                                        })"
                                                        class="w-full flex items-center gap-2.5 px-3 py-2 text-rose-500 hover:bg-rose-50 transition-colors text-[9px] font-black uppercase tracking-widest">
                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    <span>Désactiver</span>
                                                </button>
                                                <form :id="'delete-user-' + user.id" :action="'{{ url('/admin/utilisateurs') }}/' + user.id" method="POST" class="hidden">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <template x-if="!loading && users.length === 0">
            <div class="p-20 text-center">
                <div class="size-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <p class="text-slate-400 font-black text-xs uppercase tracking-widest italic">Aucun utilisateur trouvé</p>
            </div>
        </template>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center" 
         x-show="users.length > 0"
         :class="loading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
        <nav class="flex items-center gap-2">
            @if($users->previousPageUrl())
                <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-primary-50 hover:text-primary-600 transition-all">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif
            
            <span class="px-4 py-2 text-sm font-bold text-slate-500">
                Page {{ $users->currentPage() }} sur {{ $users->lastPage() }}
            </span>
            
            @if($users->nextPageUrl())
                <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-primary-50 hover:text-primary-600 transition-all">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </nav>
    </div>

    <!-- Modals Layer -->
    <template x-teleport="body">
        <div>
            <!-- Modal: Add User -->
            <x-ui.modal name="add-user-modal" title="Nouvel Agent">
                <form action="{{ route('admin.utilisateurs.store') }}" method="POST" class="space-y-6 pb-32" x-data="{ role: 'Apprenant' }">
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Prénom</label>
                            <input type="text" name="prenom" required placeholder="Prénom"
                                   class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nom de famille</label>
                            <input type="text" name="nom" required placeholder="Nom"
                                   class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Courriel Institutionnel</label>
                        <input type="email" name="email" required placeholder="adresse@soliquiz.fr"
                               class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2" x-data="{ showPw: false }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Clef de Sécurité <span class="text-slate-300 normal-case">(Par défaut : "password")</span></label>
                        <div class="relative">
                            <input :type="showPw ? 'text' : 'password'" name="password" placeholder="••••••••"
                                   class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 pe-14 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                            <button type="button" @click="showPw = !showPw" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                                <svg x-show="!showPw" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="showPw" x-cloak class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Privilège</label>
                            <x-ui.select 
                                name="role" 
                                required 
                                x-model="role"
                                placeholder="Niveau d'Accès"
                                :options="[
                                    ['value' => 'Apprenant', 'label' => 'Apprenant'],
                                    ['value' => 'Formateur', 'label' => 'Formateur'],
                                    ['value' => 'Administrateur', 'label' => 'Administrateur'],
                                ]"
                            />
                        </div>
                        
                        <div class="space-y-2" x-show="role === 'Apprenant' || role === 'Formateur'" x-transition>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Cohorte d'ancrage</label>
                            <x-ui.select 
                                name="classe_id" 
                                placeholder="Indépendant"
                                :options="array_merge([['value' => '', 'label' => 'Indépendant']], \App\Models\Classe::all()->map(fn($c) => ['value' => $c->id, 'label' => $c->nom])->toArray())"
                            />
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm mt-4 shadow-xl shadow-slate-900/10">
                        Inscrire dans le Directory
                    </button>
                </form>
            </x-ui.modal>

            <!-- Modal: Edit User -->
            <x-ui.modal name="edit-user-modal" title="Mutation Profil">
                <form x-bind:action="`{{ url('/admin/utilisateurs') }}/${editUser.id}`" method="POST" class="space-y-6 pb-32">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Prénom</label>
                            <input type="text" name="prenom" x-model="editUser.prenom" required
                                   class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nom</label>
                            <input type="text" name="nom" x-model="editUser.nom" required
                                   class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Identifiant Mail</label>
                        <input type="email" name="email" x-model="editUser.email" required
                               class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2" x-data="{ showPw: false }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Clef de Sécurité <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                        <div class="relative">
                            <input :type="showPw ? 'text' : 'password'" name="password" placeholder="Régénérer la clef"
                                   class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 pe-14 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                            <button type="button" @click="showPw = !showPw" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors">
                                <svg x-show="!showPw" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="showPw" x-cloak class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display: none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Niveau d'Accès</label>
                            <x-ui.select 
                                name="role" 
                                required 
                                x-model="editUserRole"
                                :options="[
                                    ['value' => 'Apprenant', 'label' => 'Apprenant'],
                                    ['value' => 'Formateur', 'label' => 'Formateur'],
                                    ['value' => 'Administrateur', 'label' => 'Administrateur'],
                                ]"
                            />
                        </div>
                        <div class="space-y-2" x-show="editUserRole === 'Apprenant' || editUserRole === 'Formateur'" x-transition>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Mutation Cohorte</label>
                            <x-ui.select 
                                name="classe_id" 
                                x-model="editUserClasse"
                                placeholder="Indépendant"
                                :options="array_merge([['value' => '', 'label' => 'Indépendant']], \App\Models\Classe::all()->map(fn($c) => ['value' => $c->id, 'label' => $c->nom])->toArray())"
                            />
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm shadow-xl shadow-slate-900/10 mt-4">
                        Consigner les Changements
                    </button>
                </form>
            </x-ui.modal>
        </div>
    </template>
</div>
@endsection
