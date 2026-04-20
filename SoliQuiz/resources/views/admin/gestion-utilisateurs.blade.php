@extends('components.layout.app')

@section('content')
    <div class="bg-slate-50 min-h-screen">
        <main class="max-w-7xl mx-auto px-4 py-12">
            @if (session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-600 font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 font-bold text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 font-bold text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Welcome Header Hero -->
            <div class="mb-12 relative overflow-hidden bg-white border border-slate-200 p-10 rounded-[2.5rem] shadow-sm">
                <div class="absolute -top-10 -right-10 size-64 bg-slate-50 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1
                            class="text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase italic mb-2">
                            Utilisateurs & Permissions</h1>
                        <p class="text-slate-500 font-medium text-lg">Centralisez la gestion des accès formateurs et
                            étudiants du centre Solicode.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-slate-50 p-4 px-6 rounded-2xl border border-slate-100 flex flex-col">
                            <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Utilisateurs
                                actifs</span>
                            <span class="text-2xl font-black text-slate-900">{{ $users->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <div id="tabs-users" x-data="{ 
                    search: '', 
                    addModalOpen: false, 
                    editModalOpen: false, 
                    editUser: {}, 
                    editUserRole: '' 
                }">
                    <!-- Data Filter & Search Bar -->
                    <div class="flex flex-col md:flex-row gap-4 mb-8">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                <svg class="shrink-0 size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </svg>
                            </div>
                            <input x-model="search"
                                class="py-3.5 ps-11 pe-4 block w-full border-slate-200 rounded-xl text-sm font-medium text-slate-600 outline-none focus:border-primary-500 focus:ring-1 transition-all shadow-sm"
                                type="text" placeholder="Rechercher un utilisateur (ex: Anouar)...">
                        </div>
                        <button type="button" @click="addModalOpen = true"
                            class="py-3.5 px-8 inline-flex items-center gap-x-2 text-sm font-black rounded-xl border border-transparent bg-primary-600 text-white shadow-lg shadow-primary-500/20 hover:bg-primary-700 transition-all uppercase tracking-widest whitespace-nowrap">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Nouveau
                        </button>
                    </div>

                    <!-- Modal: Add User -->
                    <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-black/50"
                        aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="addModalOpen = false">
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200"
                                @click.stop>
                                <form action="{{ route('admin.utilisateurs.store') }}" method="POST">
                                    @csrf
                                    <div class="flex justify-between items-center py-5 px-8 border-b border-slate-100">
                                        <h3 class="font-heading font-black text-slate-900 uppercase italic tracking-tight">
                                            Nouvel Utilisateur</h3>
                                        <button type="button"
                                            class="size-8 inline-flex justify-center items-center rounded-full bg-slate-100 text-slate-500 hover:text-slate-800 transition-all"
                                            @click="addModalOpen = false">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M18 6 6 18M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-8 space-y-5" x-data="{ role: 'Apprenant' }">
                                        <div class="flex gap-4">
                                            <div class="w-1/2">
                                                <label
                                                    class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Prénom</label>
                                                <input type="text" name="prenom" required
                                                    class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500"
                                                    placeholder="Ex: Jean">
                                            </div>
                                            <div class="w-1/2">
                                                <label
                                                    class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Nom</label>
                                                <input type="text" name="nom" required
                                                    class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500"
                                                    placeholder="Ex: Dupont">
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Email</label>
                                            <input type="email" name="email" required
                                                class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500"
                                                placeholder="jean@solicode.co">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Mot
                                                de passe</label>
                                            <input type="password" name="password" required
                                                class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500"
                                                placeholder="••••••••">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Rôle</label>
                                            <select name="role" required x-model="role"
                                                class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500 bg-white">
                                                <option value="Apprenant">Apprenant</option>
                                                <option value="Formateur">Formateur</option>
                                                <option value="Administrateur">Administrateur</option>
                                            </select>
                                        </div>
                                        <div x-show="role === 'Apprenant' || role === 'Formateur'">
                                            <label
                                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Assignation
                                                Classe</label>
                                            <select name="classe_id"
                                                class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500 bg-white">
                                                <option value="">Aucune classe</option>
                                                @foreach(\App\Models\Classe::all() as $classe)
                                                    <option value="{{ $classe->id }}">{{ $classe->nom }}
                                                        ({{ $classe->promotion }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-end items-center gap-x-2 py-5 px-8 border-t border-slate-100 bg-slate-50 rounded-b-3xl">
                                        <button type="button"
                                            class="py-3 px-6 inline-flex items-center gap-x-2 font-bold text-xs text-slate-500 rounded-xl hover:bg-slate-100 transition-all"
                                            @click="addModalOpen = false">Annuler</button>
                                        <button type="submit"
                                            class="py-3 px-8 inline-flex items-center gap-x-2 font-black text-xs text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/20 transition-all">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Data Table -->
                    <div
                        class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xl shadow-slate-200/50">
                        <table class="w-full text-left" id="users-table">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th
                                        class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                        Identité</th>
                                    <th
                                        class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                        Rôle</th>
                                    <th
                                        class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                        Status Accès</th>
                                    <th
                                        class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50 transition-colors"
                                        x-show="search === '' || '{{ addslashes(strtolower($user->prenom . ' ' . $user->nom)) }}'.includes(search.toLowerCase()) || '{{ addslashes(strtolower($user->email)) }}'.includes(search.toLowerCase())">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="size-10 rounded-full {{ $user->role === 'admin' ? 'bg-slate-900 text-white' : ($user->role === 'formateur' ? 'bg-primary-100 text-primary-700' : 'bg-slate-100 text-slate-500') }} flex items-center justify-center font-bold text-xs">
                                                    {{ substr($user->prenom, 0, 1) . substr($user->nom, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-900 uppercase italic">{{ $user->prenom }}
                                                        {{ $user->nom }}</span>
                                                    <span
                                                        class="text-xs text-slate-400 font-medium italic">{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            @if($user->role === 'admin')
                                                <span
                                                    class="px-3 py-1 bg-slate-900 text-white rounded-lg text-[10px] font-black uppercase tracking-wider italic">Administrateur</span>
                                            @elseif($user->role === 'formateur')
                                                <span
                                                    class="px-3 py-1 bg-primary-100 text-primary-700 rounded-lg text-[10px] font-black uppercase tracking-wider italic border border-primary-200">Formateur</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black uppercase tracking-wider italic border border-slate-200">Apprenant</span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-2">
                                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                                <span
                                                    class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Actif</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex justify-end items-center gap-1.5">
                                                <button
                                                    class="size-9 inline-flex items-center justify-center text-slate-300 hover:text-primary-500 hover:bg-primary-50 rounded-xl transition-all active:scale-90 shadow-sm"
                                                    @click="editModalOpen = true; editUser = {{ json_encode($user) }}; editUserRole = '{{ $user->role === 'admin' ? 'Administrateur' : ($user->role === 'formateur' ? 'Formateur' : 'Apprenant') }}';"
                                                    title="Modifier">
                                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2.2">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                @if($user->id !== Auth::id())
                                                    <button
                                                        class="size-9 inline-flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all active:scale-90 shadow-sm"
                                                        onclick="if(confirm('Supprimer cet utilisateur ?')) { document.getElementById('delete-user-{{ $user->id }}').submit(); }"
                                                        title="Supprimer">
                                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2.2">
                                                            <path
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                    <form id="delete-user-{{ $user->id }}"
                                                        action="{{ route('admin.utilisateurs.destroy', $user->id) }}" method="POST"
                                                        class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modal: Edit User -->
        <template x-if="editModalOpen">
            <div class="fixed inset-0 z-50 overflow-y-auto bg-black/50" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="editModalOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200"
                        @click.stop>
                        <form :action="'/admin/utilisateurs/' + editUser.id" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="flex justify-between items-center py-5 px-8 border-b border-slate-100">
                                <h3 class="font-heading font-black text-slate-900 uppercase italic tracking-tight">Modifier
                                    Utilisateur</h3>
                                <button type="button"
                                    class="size-8 inline-flex justify-center items-center rounded-full bg-slate-100 text-slate-500 hover:text-slate-800 transition-all"
                                    @click="editModalOpen = false">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M18 6 6 18M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-8 space-y-5">
                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <label
                                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Prénom</label>
                                        <input type="text" name="prenom" x-model="editUser.prenom" required
                                            class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500">
                                    </div>
                                    <div class="w-1/2">
                                        <label
                                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Nom</label>
                                        <input type="text" name="nom" x-model="editUser.nom" required
                                            class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Email</label>
                                    <input type="email" name="email" x-model="editUser.email" required
                                        class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Mot
                                        de passe <span class="text-slate-300 font-normal normal-case">(Laisser vide pour ne
                                            pas changer)</span></label>
                                    <input type="password" name="password"
                                        class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500"
                                        placeholder="••••••••">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Rôle</label>
                                    <select name="role" required x-model="editUserRole"
                                        class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500 bg-white">
                                        <option value="Apprenant">Apprenant</option>
                                        <option value="Formateur">Formateur</option>
                                        <option value="Administrateur">Administrateur</option>
                                    </select>
                                </div>
                                <div x-show="editUserRole === 'Apprenant' || editUserRole === 'Formateur'">
                                    <label
                                        class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Assignation
                                        Classe</label>
                                    <select name="classe_id" x-model="editUser.classe_id"
                                        class="py-3 px-4 block w-full border border-slate-200 rounded-xl text-sm outline-none focus:border-primary-500 bg-white">
                                        <option value="">Aucune classe</option>
                                        @foreach(\App\Models\Classe::all() as $classe)
                                            <option value="{{ $classe->id }}">{{ $classe->nom }} ({{ $classe->promotion }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div
                                class="flex justify-end items-center gap-x-2 py-5 px-8 border-t border-slate-100 bg-slate-50 rounded-b-3xl">
                                <button type="button"
                                    class="py-3 px-6 inline-flex items-center gap-x-2 font-bold text-xs text-slate-500 rounded-xl hover:bg-slate-100 transition-all"
                                    @click="editModalOpen = false">Annuler</button>
                                <button type="submit"
                                    class="py-3 px-8 inline-flex items-center gap-x-2 font-black text-xs text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/20 transition-all">Mettre
                                    à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection