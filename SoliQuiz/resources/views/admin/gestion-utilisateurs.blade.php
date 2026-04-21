@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - SoliQuiz')

@section('page-title', 'Répertoire des Utilisateurs')

@section('content')
<div class="fade-in" x-data="{
    search: '',
    users: {{ $users->toJson() }},
    loading: false,
    editUser: {},
    editUserRole: '',
    searchTimer: null,
    performSearch() {
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => {
            this.loading = true;
            fetch('{{ route('admin.utilisateurs.search') }}?search=' + encodeURIComponent(this.search), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => { this.users = data; this.loading = false; })
            .catch(() => { this.loading = false; });
        }, 300);
    }
}">
    <!-- Header Control Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-10 border-b border-slate-100 mb-10">
        <div>
            <p class="text-label mb-1">Administration</p>
            <h3 class="text-xl font-bold text-slate-900 tracking-tight italic uppercase">Directory <span class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">Utilisateurs</span></h3>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-4 px-5 py-2.5 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="text-right">
                    <p class="text-xs font-black text-slate-900 italic leading-none mb-1">{{ $users->count() }}</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Actifs</p>
                </div>
            </div>
            <button @click="$dispatch('open-modal', 'add-user-modal')"
                class="btn-premium px-8 py-4 bg-slate-900 text-white text-[11px] uppercase tracking-widest">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Nouvel Agent
            </button>
        </div>
    </div>



    <!-- Filters & Search -->
    <div class="mb-8 relative group">
        <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none ps-6">
            <svg class="size-5 text-slate-300 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path>
            </svg>
        </div>
        <input x-model="search"
               @input="performSearch()"
               class="w-full bg-white border border-slate-100 rounded-3xl py-6 ps-16 pe-8 font-bold text-slate-900 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all shadow-sm group-hover:shadow-premium"
               type="text" placeholder="Filtrer le répertoire par nom, email ou rôle...">
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-50">
                        <th class="ps-10 pe-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Profil Utilisateur</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Autorisation</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Disponibilité</th>
                        <th class="ps-6 pe-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Contrôle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="user in users" :key="user.id">
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="ps-10 pe-6 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="relative">
                                        <img class="size-14 rounded-2xl shadow-sm border-2 border-white group-hover:scale-110 transition-transform duration-500"
                                             :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.nom_complet) + '&background=' + (user.role === 'admin' ? '0f172a' : (user.role === 'formateur' ? '0ea5e9' : 'f1f5f9')) + '&color=' + (user.role === 'etudiant' ? '64748b' : 'fff') + '&bold=true'"
                                             alt="">
                                        <template x-if="user.role === 'admin'">
                                            <div class="absolute -top-1 -right-1 size-5 bg-amber-400 rounded-full border-2 border-white flex items-center justify-center text-[8px] text-white">
                                                <svg class="size-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 group-hover:text-primary-600 transition-colors" x-text="user.nom_complet"></span>
                                        <span class="text-xs font-bold text-slate-400 font-mono" x-text="user.email"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <template x-if="user.role === 'admin'">
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest">Architecte</span>
                                </template>
                                <template x-if="user.role === 'formateur'">
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-primary-50 text-primary-600 text-[9px] font-black uppercase tracking-widest border border-primary-100">Expert / Formateur</span>
                                </template>
                                <template x-if="user.role === 'etudiant'">
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest border border-slate-200">Apprenant</span>
                                </template>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="size-2 rounded-full bg-emerald-500 shadow-sm animate-pulse"></div>
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Connecté</span>
                                </div>
                            </td>
                            <td class="ps-6 pe-10 py-6 text-right">
                                <div class="flex justify-end items-center gap-3 transition-opacity">
                                    <button @click="editUser = user; editUserRole = user.role === 'admin' ? 'Administrateur' : (user.role === 'formateur' ? 'Formateur' : 'Apprenant'); $dispatch('open-modal', 'edit-user-modal')"
                                            class="size-11 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-primary-500 hover:border-primary-200 transition-all shadow-sm hover:shadow-md flex items-center justify-center">
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    
                                    <template x-if="user.id !== {{ Auth::id() }}">
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                    @click.stop="$dispatch('confirm', { 
                                                        title: 'Archiver l\'utilisateur ?', 
                                                        message: 'L\'utilisateur ne pourra plus se connecter au système.', 
                                                        onConfirm: 'delete-user-' + user.id 
                                                    })"
                                                    class="size-11 rounded-2xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm hover:shadow-rose-100 flex items-center justify-center">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                            <form :id="'delete-user-' + user.id" :action="'{{ url('/admin/utilisateurs') }}/' + user.id" method="POST" class="hidden">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                            </form>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <template x-if="users.length === 0">
            <div class="p-20 text-center">
                <div class="size-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <p class="text-slate-400 font-black text-xs uppercase tracking-widest italic">Base de données vacante</p>
            </div>
        </template>
    </div>

    <!-- Modals Layer -->

    <!-- Modal: Add User -->
    <x-ui.modal name="add-user-modal" title="Nouvel Agent">
        <form action="{{ route('admin.utilisateurs.store') }}" method="POST" class="space-y-6" x-data="{ role: 'Apprenant' }">
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

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Clef de Sécurité Initial</label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
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
                        :options="\App\Models\Classe::all()->map(fn($c) => ['value' => $c->id, 'label' => $c->nom])->toArray()"
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
        <form x-bind:action="`{{ url('/admin/utilisateurs') }}/${editUser.id}`" method="POST" class="space-y-6">
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

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Clef de Sécurité <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                <input type="password" name="password" placeholder="Régénérer la clef"
                       class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
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
                <div class="space-y-2" x-show="editUserRole === 'Apprenant' || editUserRole === 'Formateur'">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Mutation Cohorte</label>
                    <x-ui.select 
                        name="classe_id" 
                        x-model="editUser.classe_id"
                        placeholder="Aucune"
                        :options="\App\Models\Classe::all()->map(fn($c) => ['value' => $c->id, 'label' => $c->nom])->toArray()"
                    />
                </div>
            </div>

            <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm shadow-xl shadow-slate-900/10 mt-4">
                Consigner les Changements
            </button>
        </form>
    </x-ui.modal>
</div>
@endsection