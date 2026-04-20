@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - SoliQuiz')

@section('content')
<div class="reveal active" x-data="{ 
    search: '', 
    addModalOpen: false, 
    editModalOpen: false, 
    editUser: {}, 
    editUserRole: '' 
}">
    <!-- Header Hero Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Administration</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Permissions & Accès</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Directory <span class="text-primary-500">Global</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Supervisez le vivier de talents du centre. Gérez les privilèges des formateurs et suivez le déploiement des cohortes d'apprenants.
            </p>
        </div>
        
        <div class="flex gap-4">
            <div class="glass bg-white/50 px-8 py-5 rounded-[24px] border border-slate-100 flex flex-col items-end">
                <span class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase mb-1">Entités Actives</span>
                <span class="text-3xl font-black text-slate-900 font-heading leading-none">{{ $users->count() }}</span>
            </div>
            
            <button @click="addModalOpen = true"
                class="group btn-premium px-8 py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all flex items-center justify-center gap-3">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Nouvel Agent
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="space-y-4 mb-10">
        @if (session('success'))
            <div class="glass border-emerald-100 bg-emerald-50/50 p-6 rounded-[24px] flex items-center gap-4 animate-in slide-in-from-top duration-500">
                <div class="size-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-0.5">Opération Réussie</p>
                    <p class="text-sm font-bold text-slate-900">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any() || session('error'))
            <div class="glass border-rose-100 bg-rose-50/50 p-6 rounded-[24px] flex items-center gap-4 animate-in slide-in-from-top duration-500">
                <div class="size-10 bg-rose-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-600 mb-0.5">Notification Système</p>
                    <p class="text-sm font-bold text-slate-900">{{ session('error') }}</p>
                    @if($errors->any())
                        <ul class="text-xs font-bold text-slate-700 list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Filters & Search -->
    <div class="mb-8 relative group">
        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-6">
            <svg class="size-5 text-slate-300 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path>
            </svg>
        </div>
        <input x-model="search"
               class="w-full bg-white border border-slate-100 rounded-[28px] py-6 ps-16 pe-8 font-bold text-slate-900 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all shadow-sm group-hover:shadow-premium"
               type="text" placeholder="Filtrer le répertoire par nom, email ou rôle...">
    </div>

    <!-- Data Table Container -->
    <div class="glass bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden">
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
                    @foreach($users as $user)
                        <tr class="group hover:bg-slate-50/80 transition-colors"
                            x-show="search === '' || '{{ addslashes(strtolower($user->nom_complet)) }}'.includes(search.toLowerCase()) || '{{ addslashes(strtolower($user->email)) }}'.includes(search.toLowerCase())">
                            <td class="ps-10 pe-6 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="relative">
                                        <img class="size-14 rounded-2xl shadow-sm border-2 border-white group-hover:scale-110 transition-transform duration-500"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($user->nom_complet) }}&background={{ $user->role === 'admin' ? '0f172a' : ($user->role === 'formateur' ? '0ea5e9' : 'f1f5f9') }}&color={{ $user->role === 'etudiant' ? '64748b' : 'fff' }}&bold=true"
                                             alt="">
                                        @if($user->role === 'admin')
                                            <div class="absolute -top-1 -right-1 size-5 bg-amber-400 rounded-full border-2 border-white flex items-center justify-center text-[8px] text-white">
                                                <svg class="size-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 group-hover:text-primary-600 transition-colors">{{ $user->nom_complet }}</span>
                                        <span class="text-xs font-bold text-slate-400 font-mono">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                @if($user->role === 'admin')
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest">Architecte</span>
                                @elseif($user->role === 'formateur')
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-primary-50 text-primary-600 text-[9px] font-black uppercase tracking-widest border border-primary-100">Expert / Formateur</span>
                                @else
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest border border-slate-200">Apprenant</span>
                                @endif
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="size-2 rounded-full bg-emerald-500 shadow-sm animate-pulse"></div>
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Connecté</span>
                                </div>
                            </td>
                            <td class="ps-6 pe-10 py-6 text-right">
                                <div class="flex justify-end items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="editModalOpen = true; editUser = {{ json_encode($user) }}; editUserRole = '{{ $user->role === 'admin' ? 'Administrateur' : ($user->role === 'formateur' ? 'Formateur' : 'Apprenant') }}';"
                                            class="size-11 rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-primary-500 hover:border-primary-200 transition-all shadow-sm hover:shadow-md flex items-center justify-center">
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    
                                    @if($user->id !== Auth::id())
                                        <button onclick="if(confirm('Archiver définitivement ce profil ?')) { document.getElementById('delete-user-{{ $user->id }}').submit(); }"
                                                class="size-11 rounded-2xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm hover:shadow-rose-100 flex items-center justify-center">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        <form id="delete-user-{{ $user->id }}" action="{{ route('admin.utilisateurs.destroy', $user->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($users->isEmpty())
            <div class="p-20 text-center">
                <div class="size-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <p class="text-slate-400 font-black text-xs uppercase tracking-widest italic">Base de données vacante</p>
            </div>
        @endif
    </div>

    <!-- Modals Layer -->

    <!-- Modal: Add User -->
    <div x-show="addModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="addModalOpen = false"></div>
        <div class="relative w-full max-w-[540px] bg-white rounded-[40px] shadow-2xl p-10 animate-in zoom-in-95 duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 size-40 bg-primary-50 rounded-full -mr-20 -mt-20"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-3xl font-heading font-black text-slate-900 uppercase italic">Nouvel Agent</h3>
                    <button @click="addModalOpen = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('admin.utilisateurs.store') }}" method="POST" class="space-y-6" x-data="{ role: 'Apprenant' }">
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Prénom</label>
                            <input type="text" name="prenom" required placeholder="Ex: Jean"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nom de famille</label>
                            <input type="text" name="nom" required placeholder="Ex: Dupont"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Courriel Institutionnel</label>
                        <input type="email" name="email" required placeholder="jean.dupont@solicode.co"
                               class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Clef de Sécurité Initial</label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Privilège</label>
                            <select name="role" required x-model="role"
                                    class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all appearance-none cursor-pointer">
                                <option value="Apprenant">Apprenant</option>
                                <option value="Formateur">Formateur</option>
                                <option value="Administrateur">Administrateur</option>
                            </select>
                        </div>
                        <div class="space-y-2" x-show="role === 'Apprenant' || role === 'Formateur'" x-transition>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Cohorte d'ancrage</label>
                            <select name="classe_id"
                                    class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all appearance-none cursor-pointer">
                                <option value="">Indépendant</option>
                                @foreach(\App\Models\Classe::all() as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm mt-4 shadow-xl shadow-slate-900/10">
                        Inscrire dans le Directory
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit User (Refactored) -->
    <template x-if="editModalOpen">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-6" x-cloak>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="editModalOpen = false"></div>
            <div class="relative w-full max-w-[540px] bg-white rounded-[40px] shadow-2xl p-10 animate-in zoom-in-95 duration-300">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-3xl font-heading font-black text-slate-900 uppercase italic">Mutation Profil</h3>
                    <button @click="editModalOpen = false" class="text-slate-300 hover:text-slate-900 transition-colors">
                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form :action="'/admin/utilisateurs/' + editUser.id" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Prénom</label>
                            <input type="text" name="prenom" x-model="editUser.prenom" required
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nom</label>
                            <input type="text" name="nom" x-model="editUser.nom" required
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Identifiant Mail</label>
                        <input type="email" name="email" x-model="editUser.email" required
                               class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Clef de Sécurité <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                        <input type="password" name="password" placeholder="Régénérer la clef"
                               class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Niveau d'Accès</label>
                            <select name="role" required x-model="editUserRole"
                                    class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all appearance-none cursor-pointer">
                                <option value="Apprenant">Apprenant</option>
                                <option value="Formateur">Formateur</option>
                                <option value="Administrateur">Administrateur</option>
                            </select>
                        </div>
                        <div class="space-y-2" x-show="editUserRole === 'Apprenant' || editUserRole === 'Formateur'">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Mutation Cohorte</label>
                            <select name="classe_id" x-model="editUser.classe_id"
                                    class="w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 focus:bg-white focus:border-primary-500 outline-none transition-all appearance-none cursor-pointer">
                                <option value="">Aucune</option>
                                @foreach(\App\Models\Classe::all() as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm shadow-xl shadow-slate-900/10 mt-4">
                        Consigner les Changements
                    </button>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection