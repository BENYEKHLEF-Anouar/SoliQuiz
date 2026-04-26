@extends('components.layout.app')

@section('content')
<div x-data="classNotes" x-init="init()" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header App Formateur -->
    @include('components.header.formateur-header')

    <main class="flex-1 overflow-y-auto w-full px-5 py-6 pb-24 hide-scrollbar">
        <div class="flex flex-col gap-4 mb-6">
            <h1 class="text-2xl font-heading font-bold tracking-tight text-slate-900">Notes & Suivi</h1>
            
            <!-- Custom Styled Cohort Filter -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button"
                    class="w-full flex justify-between items-center bg-white border border-slate-200 text-slate-700 rounded-xl py-3.5 px-4 text-sm font-bold shadow-sm outline-none transition-all hover:border-primary-300">
                    <span class="flex items-center gap-2">
                        <svg class="size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <span x-text="selectedCohortId ? cohorts.find(c => c.id == selectedCohortId)?.name : 'Sélectionner une classe'"></span>
                    </span>
                    <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <!-- Custom Dropdown Menu -->
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl p-2 z-50 max-h-60 overflow-y-auto">
                    <template x-for="cohort in cohorts" :key="cohort.id">
                        <button @click="selectedCohortId = cohort.id; open = false; fetchStudents()"
                            class="w-full flex items-center gap-3 py-3 px-4 rounded-xl text-sm font-bold transition-colors text-left"
                            :class="selectedCohortId == cohort.id ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50'">
                            <span class="size-8 rounded-lg flex items-center justify-center text-xs font-black"
                                :class="selectedCohortId == cohort.id ? 'bg-primary-100 text-primary-600' : 'bg-slate-100 text-slate-500'"
                                x-text="cohort.name.substring(0, 2).toUpperCase()">
                            </span>
                            <span x-text="cohort.name"></span>
                            <svg x-show="selectedCohortId == cohort.id" class="size-4 ml-auto text-primary-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-gradient-to-br from-primary-50 to-white rounded-[2rem] p-6 border border-primary-100/50 shadow-sm relative overflow-hidden group">
                <div class="absolute -top-6 -right-6 size-16 bg-white/50 rounded-full blur-xl group-hover:scale-150 transition-transform duration-1000"></div>
                <span class="text-[9px] font-black text-primary-600 uppercase tracking-[0.2em] block mb-2 relative z-10">Moy. Classe</span>
                <div class="flex items-baseline gap-1 relative z-10">
                    <span class="text-3xl font-heading font-extrabold text-primary-700 leading-none" x-text="classAverage"></span>
                    <span class="text-[10px] font-bold text-primary-300 uppercase italic">/20</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 mb-6">
            <!-- PRELINE SEARCH INPUT -->
            <div class="relative group">
                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none group-focus-within:text-primary-500 transition-colors">
                    <svg class="shrink-0 size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <input type="text" x-model="search"
                    class="py-4 px-4 ps-11 block w-full border-slate-100 bg-white shadow-sm rounded-2xl text-sm font-medium focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                    placeholder="Rechercher un étudiant...">
            </div>
            <div class="flex justify-between items-center">
                <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">LISTE DES APPRENANTS</h2>
            </div>
        </div>

        <div class="grid gap-4">
            <template x-for="student in filteredStudents" :key="student.id">
                <div class="p-5 flex items-center bg-white border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.03)] rounded-[2rem] cursor-pointer active:scale-[0.98] transition-all hover:border-slate-200 group"
                    :class="student.averageScore < 10 ? 'border-l-4 border-l-semantic-error' : 'border-l-4 border-l-transparent'"
                    @click="showStudentDetail(student)">
                    
                    <div class="size-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center border border-slate-100 shrink-0 transition-colors group-hover:from-primary-50 group-hover:to-primary-100 group-hover:border-primary-200">
                        <span class="text-sm font-bold text-slate-500 group-hover:text-primary-600 transition-colors" x-text="getInitials(student.name)">ST</span>
                    </div>
                    
                    <div class="ms-4 flex-1">
                        <h3 class="font-extrabold text-slate-900 text-base leading-tight" x-text="student.name"></h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1" x-text="student.averageScore < 10 ? 'Suivi prioritaire' : 'Progression normale'"></p>
                    </div>

                    <div class="text-right">
                        <span class="font-heading font-extrabold text-lg px-3 py-1.5 rounded-xl border shadow-sm transition-all"
                            :class="student.averageScore >= 10 ? 'bg-primary-50 text-primary-600 border-primary-100' : 'bg-semantic-error/10 text-semantic-error border-semantic-error/10'"
                            x-text="student.averageScore.toFixed(1)"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty state -->
        <div x-show="!loading && filteredStudents.length === 0" class="text-center py-20">
            <div class="size-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Aucun étudiant ne correspond</p>
        </div>
    </main>

    <!-- PRELINE OFFCANVAS: Détail Étudiant (Bottom Sheet actionnable) -->
    <div id="hs-offcanvas-student"
        class="hs-overlay hs-overlay-open:translate-y-0 translate-y-full fixed bottom-0 inset-x-0 transition-all duration-300 transform h-2/3 max-w-[430px] mx-auto w-full z-[80] bg-white border-t border-slate-200 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] hidden"
        role="dialog" tabindex="-1" aria-labelledby="hs-offcanvas-student-label">
        <div class="flex justify-between items-center py-4 px-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-sm"
                         x-text="selectedStudent ? getInitials(selectedStudent.name) : 'ST'">
                    </div>
                    <h3 id="hs-offcanvas-student-label" class="font-bold text-slate-900 text-lg" x-text="selectedStudent?.name"></h3>
                </div>
            <button type="button"
                class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none focus:bg-slate-200"
                aria-label="Close" data-hs-overlay="#hs-offcanvas-student">
                <span class="sr-only">Fermer</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div class="p-5 overflow-y-auto max-h-full" x-show="selectedStudent">
            <h4 class="text-xs font-bold uppercase tracking-widest mb-3"
                :class="selectedStudent?.averageScore < 10 ? 'text-semantic-error' : 'text-primary-600'"
                x-text="selectedStudent?.averageScore < 10 ? 'Risque de décrochage détecté' : 'Progression satisfaisante'"></h4>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Dernier QCM</span>
                    <span class="text-lg font-mono font-bold" :class="selectedStudent?.lastQcmScore < 10 ? 'text-semantic-error' : 'text-semantic-success'" x-text="selectedStudent?.lastQcmScore + '/20'"></span>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Participation</span>
                    <span class="text-lg font-mono font-bold text-slate-700" x-text="selectedStudent?.participation || 'Moyenne'"></span>
                </div>
            </div>

            <!-- Preline Accordion pour Voir l'historique -->
            <div class="hs-accordion-group">
                <div class="hs-accordion bg-white border border-slate-200 rounded-xl" id="hs-basic-heading-one">
                    <button
                        class="hs-accordion-toggle hs-accordion-active:text-primary-600 py-3 px-4 inline-flex items-center gap-x-3 w-full font-semibold text-start text-slate-800 hover:text-slate-500 rounded-xl focus:outline-none"
                        aria-expanded="false" aria-controls="hs-basic-collapse-one">
                        <svg class="hs-accordion-active:hidden block size-4" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                        <svg class="hs-accordion-active:block hidden size-4" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                        Détail des notes précédentes
                    </button>
                    <div id="hs-basic-collapse-one"
                        class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                        role="region" aria-labelledby="hs-basic-heading-one">
                        <div class="p-4 pt-0 text-sm text-slate-600">
                            <ul class="space-y-2">
                                <li class="flex justify-between"><span>Intro Algorithmique</span> <span
                                        class="font-mono text-semantic-warning">11/20</span></li>
                                <li class="flex justify-between"><span>Variables PHP</span> <span
                                        class="font-mono text-semantic-error">09/20</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button"
                class="mt-6 w-full py-3.5 px-4 flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-slate-900 text-white hover:bg-slate-800 shadow-md">
                Envoyer un message de soutien
            </button>
        </div>
    </div>

    <!-- Navigation Mobile Formateur -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('classNotes', () => ({
            cohorts: [],
            selectedCohortId: '',
            students: [],
            selectedStudent: null,
            loading: false,
            search: '',
            get filteredStudents() {
                if (!this.search) return this.students;
                const term = this.search.toLowerCase();
                return this.students.filter(s => s.name.toLowerCase().includes(term));
            },
            get classAverage() {
                if (this.students.length === 0) return 0;
                const sum = this.students.reduce((acc, s) => acc + s.averageScore, 0);
                return (sum / this.students.length).toFixed(1);
            },
            async init() {
                await this.fetchCohorts();
            },
            async fetchCohorts() {
                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/cohorts`);
                    this.cohorts = await response.json();
                    if (this.cohorts.length > 0) {
                        this.selectedCohortId = this.cohorts[0].id;
                        await this.fetchStudents();
                    }
                } catch (e) {
                    console.error('Failed to load cohorts', e);
                } finally {
                    this.loading = false;
                }
            },
            async fetchStudents() {
                if (!this.selectedCohortId) return;
                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/cohorts/${this.selectedCohortId}/students`);
                    this.students = await response.json();
                } catch (e) {
                    console.error('Failed to load students', e);
                } finally {
                    this.loading = false;
                }
            },
            async showStudentDetail(student) {
                this.selectedStudent = student;
                const el = document.getElementById('hs-offcanvas-student');
                if (el) {
                    el.classList.remove('hidden');
                    el.classList.add('translate-y-0');
                }
            },
            getInitials(name) {
                if (!name) return 'ST';
                const parts = name.split(' ');
                const first = parts[0]?.charAt(0).toUpperCase() || '';
                const last = parts[parts.length - 1]?.charAt(0).toUpperCase() || '';
                return first + last;
            }
        }));
    });
</script>
@endsection