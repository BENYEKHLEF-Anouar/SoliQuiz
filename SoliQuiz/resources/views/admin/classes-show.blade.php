@extends('layouts.app')

@section('title', 'Détails Cohorte - ' . $classe->nom)

@section('content')
    <div class="fade-in space-y-12"
        x-data="{ activeClasseId: {{ $classe->id }}, activeClasseName: '{{ addslashes($classe->nom) }}' }">
        <!-- Header Strategy Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
            <div class="flex-1">
                <nav
                    class="flex items-center gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">
                    <a href="{{ route('admin.classes') }}"
                        class="hover:text-primary-500 transition-colors flex items-center gap-1">
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                            <path d="M15 19l-7-7 7-7" />
                        </svg>
                        Répertoire Cohortes
                    </a>
                    <span class="size-1 rounded-full bg-slate-200"></span>
                    <span class="text-slate-900">Intelligence Structurelle</span>
                </nav>
                <h1
                    class="text-3xl lg:text-4xl font-heading font-black text-slate-900 tracking-tight leading-none uppercase">
                    {{ $classe->nom }} <span
                        class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">{{ $classe->promotion }}</span>
                </h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="bg-white border border-slate-100 p-2 pr-6 rounded-2xl shadow-sm flex items-center gap-4 group hover:border-primary-100 transition-all cursor-pointer"
                    @click="$dispatch('open-modal', 'assign-formateur-modal')">
                    <div
                        class="size-12 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg shadow-slate-900/10 group-hover:scale-105 transition-transform">
                        @if($classe->formateur)
                            <img class="size-full rounded-xl border-2 border-white/10"
                                src="https://ui-avatars.com/api/?name={{ urlencode(optional($classe->formateur)->nom_complet ?? 'Unassigned') }}&background=0f172a&color=fff&bold=true"
                                alt="{{ optional($classe->formateur)->nom_complet ?? 'Unassigned' }}">
                        @else
                            <svg class="size-6 text-slate-500 group-hover:text-primary-400 transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Expert
                            Référent</p>
                        <p
                            class="text-sm font-black text-slate-900 leading-none group-hover:text-primary-600 transition-colors">
                            {{ $classe->formateur ? $classe->formateur->nom_complet : 'Assigner un Expert' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Student List Section -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/20">
                        <div>
                            <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight">
                                Agents Apprenants</h2>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ressources
                                humaines rattachées</p>
                        </div>
                        <div
                            class="flex items-center gap-2 px-4 py-2 bg-slate-900 rounded-xl shadow-md shadow-slate-900/10">
                            <span
                                class="text-white text-[10px] font-black uppercase tracking-widest">{{ $classe->etudiants->count() }}
                                Membres</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto pb-4">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/30">
                                    <th
                                        class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                        Identité</th>
                                    <th
                                        class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                        Email</th>
                                    <th
                                        class="px-6 py-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                        Contrôle</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($classe->etudiants as $etudiant)
                                    <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="relative">
                                                    <img class="size-10 rounded-xl shadow-sm border border-white group-hover: transition-transform duration-500"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($etudiant->nom_complet ?? 'Student') }}&background=f1f5f9&color=64748b&bold=true"
                                                        alt="">
                                                    <div
                                                        class="absolute -top-0.5 -right-0.5 size-3 bg-emerald-500 rounded-full border-2 border-white shadow-sm animate-pulse">
                                                    </div>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-sm font-black text-slate-900 tracking-tight group-hover:text-primary-600 transition-colors">{{ $etudiant->nom_complet ?? 'Unknown' }}</span>
                                                    <span
                                                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Actif
                                                        au repertoire</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-xs font-bold text-slate-500 font-mono">{{ $etudiant->email }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" @click.prevent="$dispatch('confirm', { 
                                                        title: 'Révoquer l\'affectation ?', 
                                                        message: 'L\'apprenant ne fera plus partie de cette cohorte.', 
                                                        onConfirm: 'remove-student-' + {{ $etudiant->id }} 
                                                    })"
                                                class="size-8 inline-flex items-center justify-center bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all shadow-sm"
                                                title="Révoquer l'affectation">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            <form id="remove-student-{{ $etudiant->id }}"
                                                action="{{ route('admin.classes.students.remove', [$classe->id, $etudiant->id]) }}"
                                                method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-20 text-center bg-slate-50/10">
                                            <div
                                                class="size-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100">
                                                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                                    <circle cx="9" cy="7" r="4" />
                                                </svg>
                                            </div>
                                            <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">
                                                Effectif Vierge</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions Section -->
            <div class="space-y-6">
                <!-- Add Member Card -->
                <div class="bg-slate-900 rounded-[2rem] p-8 text-white shadow-xl shadow-slate-900/20 relative group overflow-hidden"
                     x-data="{ 
                         tab: 'affectation',
                         searchQuery: '',
                         selectedStudents: [],
                         unassignedStudents: {{ Js::from($etudiantsSansClasse->map(fn($s) => ['id' => $s->id, 'nom_complet' => $s->nom_complet, 'email' => $s->email])->toArray()) }},
                         
                         get filteredStudents() {
                             if (!this.searchQuery) return this.unassignedStudents;
                             const query = this.searchQuery.toLowerCase();
                             return this.unassignedStudents.filter(s => 
                                 s.nom_complet.toLowerCase().includes(query) || 
                                 s.email.toLowerCase().includes(query)
                             );
                         },
                         
                         toggleStudent(id) {
                             if (this.selectedStudents.includes(id)) {
                                 this.selectedStudents = this.selectedStudents.filter(x => x !== id);
                             } else {
                                 this.selectedStudents.push(id);
                             }
                         },
                         
                         toggleAll() {
                             const visibleIds = this.filteredStudents.map(s => s.id);
                             const allSelected = visibleIds.every(id => this.selectedStudents.includes(id));
                             if (allSelected) {
                                 this.selectedStudents = this.selectedStudents.filter(id => !visibleIds.includes(id));
                             } else {
                                 visibleIds.forEach(id => {
                                     if (!this.selectedStudents.includes(id)) {
                                         this.selectedStudents.push(id);
                                     }
                                 });
                             }
                         }
                     }">
                    <div class="absolute inset-0 overflow-hidden rounded-[2rem] pointer-events-none">
                        <div
                            class="absolute top-0 right-0 size-48 bg-primary-500/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-primary-500/20 transition-colors duration-700">
                        </div>
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <!-- Card Header & Tabs -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/5">
                            <h3 class="text-xl font-heading font-black uppercase tracking-tight">
                                Inclusion <span class="text-primary-400">Agent</span>
                            </h3>
                            <div class="flex bg-white/5 p-1 rounded-xl border border-white/10 shrink-0">
                                <button type="button" @click="tab = 'affectation'" 
                                        :class="tab === 'affectation' ? 'bg-primary-500 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                                        class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all duration-200">
                                    Affecter
                                </button>
                                <button type="button" @click="tab = 'import'" 
                                        :class="tab === 'import' ? 'bg-primary-500 text-white shadow-md' : 'text-slate-400 hover:text-white'"
                                        class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all duration-200">
                                    Import
                                </button>
                            </div>
                        </div>

                        <!-- Error Messages directly in Card -->
                        @if($errors->any())
                            <div class="mb-4 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 text-[10px] text-rose-400 font-bold space-y-1">
                                @foreach($errors->all() as $error)
                                    <p>• {!! $error !!}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Tab 1: Affectation -->
                        <div x-show="tab === 'affectation'" class="space-y-4 flex-1 flex flex-col">
                            <template x-if="unassignedStudents.length > 0">
                                <div class="space-y-4">
                                    <!-- Search input -->
                                    <div class="relative">
                                        <input type="text" x-model="searchQuery" placeholder="Rechercher un apprenant..." 
                                               class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 pl-10 pr-4 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-bold">
                                        <svg class="absolute left-3.5 top-3 size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>

                                    <!-- Actions (Select All) -->
                                    <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-slate-400">
                                        <button type="button" @click="toggleAll" class="hover:text-primary-400 transition-colors">
                                            <span x-text="filteredStudents.every(s => selectedStudents.includes(s.id)) ? 'Tout désélectionner' : 'Tout sélectionner'"></span>
                                        </button>
                                        <span x-text="`${selectedStudents.length} sélectionné(s)`"></span>
                                    </div>

                                    <!-- Student Checklist Scrollable Area -->
                                    <div class="max-h-56 overflow-y-auto pr-1 space-y-2 custom-scrollbar">
                                        <template x-for="student in filteredStudents" :key="student.id">
                                            <div @click="toggleStudent(student.id)" 
                                                 :class="selectedStudents.includes(student.id) ? 'border-primary-500 bg-primary-500/10' : 'border-white/5 bg-white/5 hover:bg-white/10'"
                                                 class="border rounded-xl p-3 flex items-center gap-3 cursor-pointer transition-all">
                                                <div class="relative shrink-0">
                                                    <img class="size-8 rounded-lg shadow-sm border border-white/10"
                                                         :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(student.nom_complet)}&background=0f172a&color=fff&bold=true`"
                                                         alt="">
                                                    <div :class="selectedStudents.includes(student.id) ? 'bg-primary-500' : 'bg-transparent border border-white/30'"
                                                         class="absolute -top-1 -right-1 size-4 rounded-full flex items-center justify-center transition-colors">
                                                         <svg x-show="selectedStudents.includes(student.id)" class="size-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                             <path d="M5 13l4 4L19 7" />
                                                         </svg>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-xs font-black truncate text-white" x-text="student.nom_complet"></span>
                                                    <span class="text-[9px] font-bold text-slate-400 font-mono truncate" x-text="student.email"></span>
                                                </div>
                                            </div>
                                        </template>
                                        <div x-show="filteredStudents.length === 0" class="text-center py-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest bg-white/5 rounded-xl border border-white/5">
                                            Aucun apprenant trouvé
                                        </div>
                                    </div>

                                    <!-- Form for bulk submission -->
                                    <form action="{{ route('admin.classes.students.bulk-add', $classe->id) }}" method="POST">
                                        @csrf
                                        <template x-for="id in selectedStudents" :key="id">
                                            <input type="hidden" name="user_ids[]" :value="id">
                                        </template>
                                        <button type="submit" :disabled="selectedStudents.length === 0"
                                                class="w-full py-3 bg-white text-slate-900 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-primary-500 hover:text-white transition-all shadow-md flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path d="M12 5v14M5 12h14" />
                                            </svg>
                                            Intégrer à la Cohorte
                                        </button>
                                    </form>
                                </div>
                            </template>
                            
                            <div x-show="unassignedStudents.length === 0" class="bg-white/5 rounded-2xl p-6 border border-dashed border-white/10 text-center flex-1 flex flex-col justify-center items-center">
                                <svg class="size-8 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                                    Tous les apprenants du repertoire sont affectés.
                                </p>
                            </div>
                        </div>

                        <!-- Tab 2: Import -->
                        <div x-show="tab === 'import'" class="space-y-4" x-cloak>
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10 text-[10px] leading-relaxed text-slate-350">
                                <p class="font-black uppercase tracking-wider text-primary-400 mb-2">Instructions d'importation</p>
                                <p>Saisissez un e-mail ou un profil par ligne. Si l'étudiant n'existe pas, un compte sera créé.</p>
                                <div class="mt-3 font-mono bg-black/30 p-2 rounded-lg border border-white/5 text-[9px] text-slate-400 space-y-1">
                                    <p class="text-white">// Formats acceptés :</p>
                                    <p>prenom;nom;email@domain.com</p>
                                    <p>email@domain.com</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.classes.students.import', $classe->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="space-y-2">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none ml-1">Données des apprenants</label>
                                    <textarea name="import_data" required rows="4" placeholder="Jean;Dupont;jean.dupont@domain.com&#10;john.doe@example.com" 
                                              class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/50 transition-all font-mono resize-none"></textarea>
                                </div>

                                <button type="submit"
                                        class="w-full py-3 bg-white text-slate-900 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-primary-500 hover:text-white transition-all shadow-md flex items-center justify-center gap-2 active:scale-95">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path d="M12 5v14M5 12h14" />
                                    </svg>
                                    Lancer l'importation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Météo de Cohorte
                    </h4>
                    <div class="space-y-6">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col">
                                <span
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Taux
                                    d'engagement</span>
                                <span
                                    class="text-xl font-black text-slate-900 leading-none">{{ $tauxEngagement }}%</span>
                            </div>
                            <div class="size-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-500">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-6 border-t border-slate-50">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Score Moyen</span>
                            @if($moyenneGlobale !== null)
                                <span
                                    class="text-xs font-black {{ $moyenneGlobale >= 14 ? 'text-emerald-500' : ($moyenneGlobale >= 10 ? 'text-amber-500' : 'text-rose-500') }}">
                                    {{ number_format($moyenneGlobale, 1, ',', ' ') }} / 20
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-slate-300">Aucun test</span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mise en
                                Service</span>
                            <span
                                class="text-xs font-black text-slate-900">{{ $classe->created_at?->translatedFormat('d M Y') ?? 'Non définie' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals Layer -->
        <template x-teleport="body">
            <div>
                <!-- Modal: Assigner Formateur -->
                <x-ui.modal name="assign-formateur-modal" title="Désignation <br> Expert" maxWidth="md">
                    <form action="{{ route('admin.classes.assign', $classe->id) }}" method="POST" class="space-y-8 pb-32">
                        @csrf

                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                            <div
                                class="size-12 bg-primary-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 leading-none">
                                    Cohorte Ciblée</p>
                                <p class="text-lg font-black text-slate-900" x-text="activeClasseName"></p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Répertoire
                                des Experts</label>
                            @php
                                $formateurs = \App\Models\User::where('type_profil', 'formateur')->orderBy('nom')->get();
                            @endphp
                            <x-ui.select name="formateur_id" required placeholder="Sélectionner un formateur..."
                                class="!rounded-2xl !py-4 !px-5 border border-slate-200" :options="$formateurs->map(fn($f) => ['value' => $f->id, 'label' => $f->nom_complet])->toArray()"
                                :selected="$classe->formateur_id" />
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-500 transition-all uppercase tracking-widest text-xs shadow-xl shadow-slate-900/20 active:scale-95">
                            Confirmer l'Assignation
                        </button>
                    </form>
                </x-ui.modal>
            </div>
        </template>
    </div>
@endsection