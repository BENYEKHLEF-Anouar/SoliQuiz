@extends('components.layout.app')

@section('content')
<div x-data="classNotes()" x-init="init()" class="h-[100dvh] flex flex-col relative overflow-hidden font-sans">
    <!-- Header App Formateur -->
    <header class="bg-slate-900 px-5 pt-safe-top pb-4 border-b border-white/10 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 group outline-none" href="#" aria-label="SoliQuiz Accueil">
                    <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-white tracking-tight">Soli<span class="text-primary-400">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Formateur</span>
                    </div>
                </a>
            </div>

            <div class="hs-dropdown relative inline-flex">
                <button id="hs-dropdown-avatar" class="hs-dropdown-toggle size-[38px] rounded-full ring-2 ring-primary-500/20 border-2 border-slate-900 overflow-hidden active:scale-95 transition-transform">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Avatar">
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100" role="menu">
                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-slate-700 hover:bg-slate-50 font-medium" href="{{ route('formateur.profile') }}">Mon Profil</a>
                    <div class="my-1 border-t border-slate-100"></div>
                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-semantic-error hover:bg-semantic-error/10 font-bold" href="{{ route('landing') }}">Déconnexion</a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full px-5 py-6 pb-24 hide-scrollbar">
        <div class="flex flex-col gap-4 mb-6">
            <h1 class="text-2xl font-heading font-bold tracking-tight text-slate-900">Notes & Suivi</h1>
            
            <!-- Cohort Dropdown -->
            <div class="hs-dropdown relative inline-flex w-full">
                <button id="hs-dropdown-cohort" type="button" class="hs-dropdown-toggle w-full flex justify-between items-center bg-white border border-slate-200 text-slate-800 rounded-xl py-3 px-4 text-sm font-bold shadow-sm outline-none">
                    <span x-text="selectedCohortId ? cohorts.find(c => c.id == selectedCohortId)?.name + ' (' + cohorts.find(c => c.id == selectedCohordId)?.promotion + ')' : 'Sélectionner une classe'"></span>
                    <svg class="hs-dropdown-open:rotate-180 size-4 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-white shadow-lg rounded-xl p-1 mt-2 border border-slate-200 z-[60]" role="menu">
                    <template x-for="cohort in cohorts" :key="cohort.id">
                        <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm hover:bg-slate-100 focus:outline-none" 
                           :class="selectedCohortId == cohort.id ? 'text-primary-600 font-bold bg-primary-50' : 'text-slate-800'"
                           @click="selectedCohortId = cohort.id; fetchStudents()" 
                           x-text="cohort.name + ' (' + cohort.promotion + ')'"></a>
                    </template>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-primary-50 rounded-2xl p-4 border border-primary-100 flex flex-col">
                <span class="text-[10px] font-bold text-primary-600 uppercase tracking-widest mb-1">Moy. Classe</span>
                <span class="text-2xl font-heading font-bold text-primary-700"><span x-text="classAverage"></span><span class="text-sm">/20</span></span>
            </div>
        </div>

        <!-- Search -->
        <div class="relative mb-6">
            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                <svg class="shrink-0 size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </div>
            <input type="text" x-model="search" class="py-3 px-4 ps-11 block w-full border-slate-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Rechercher un étudiant...">
        </div>

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Liste des Étudiants</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <ul class="divide-y divide-slate-100">
                <template x-for="student in filteredStudents" :key="student.id">
                    <li class="p-4 flex flex-col gap-2 cursor-pointer active:bg-slate-50 transition-colors" @click="showStudentDetail(student)">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <img class="w-8 h-8 rounded-full border border-slate-200" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=facearea&facepad=2&w=150&h=150&q=80" alt="Avatar">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm" x-text="student.name"></h3>
                                </div>
                            </div>
                            <span class="font-bold font-mono px-2.5 py-1 rounded-lg text-sm" 
                                  :class="student.averageScore >= 10 ? 'bg-semantic-success/20 text-semantic-success' : 'bg-semantic-error/20 text-semantic-error'"
                                  x-text="student.averageScore + '/20'"></span>
                        </div>
                    </li>
                </template>
            </ul>
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="flex justify-center py-10">
            <svg class="animate-spin size-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </main>

    <!-- Student Detail Offcanvas -->
    <div id="hs-offcanvas-student" class="hs-overlay hs-overlay-open:translate-y-0 translate-y-full fixed bottom-0 inset-x-0 transition-all duration-300 transform h-2/3 max-w-[430px] mx-auto w-full z-[80] bg-white border-t border-slate-200 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] hidden" role="dialog" tabindex="-1">
        <div class="flex justify-between items-center py-4 px-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <img class="w-10 h-10 rounded-full border border-slate-200" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=facearea&facepad=2&w=150&h=150&q=80" alt="Avatar">
                <h3 class="font-bold text-slate-900 text-lg" x-text="selectedStudent?.name"></h3>
            </div>
            <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-slate-100 text-slate-800 hover:bg-slate-200" aria-label="Close" data-hs-overlay="#hs-offcanvas-student">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
        <div class="p-5 overflow-y-auto max-h-full" x-show="selectedStudent">
            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Dernier QCM</span>
                    <span class="text-lg font-mono font-bold" :class="selectedStudent?.lastQcmScore >= 10 ? 'text-semantic-success' : 'text-semantic-error'" x-text="selectedStudent?.lastQcmScore + '/20'"></span>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Participation</span>
                    <span class="text-lg font-mono font-bold text-slate-700" x-text="selectedStudent?.participation || 'Active'"></span>
                </div>
            </div>

            <button type="button" class="mt-6 w-full py-3.5 px-4 flex justify-center items-center gap-x-2 text-sm font-bold rounded-xl border border-transparent bg-slate-900 text-white hover:bg-slate-800 shadow-md">
                Envoyer un message de soutien
            </button>
        </div>
    </div>

    <!-- Navigation Mobile Formateur -->
    <nav class="fixed bottom-0 w-full max-w-[430px] bg-slate-900 border-t border-white/10 z-50 rounded-b-[2.5rem]">
        <div class="flex justify-around items-center h-[72px] px-4 pb-safe text-white">
            <a href="{{ route('formateur.qcms') }}" class="flex flex-col items-center justify-center text-slate-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="9" />
                    <rect x="14" y="3" width="7" height="5" />
                    <rect x="14" y="12" width="7" height="9" />
                    <rect x="3" y="16" width="7" height="5" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">QCMs</span>
            </a>
            <a href="{{ route('formateur.class-notes') }}" class="flex flex-col items-center justify-center text-primary-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Classe</span>
            </a>
            <a href="{{ route('formateur.profile') }}" class="flex flex-col items-center justify-center text-slate-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Profil</span>
            </a>
        </div>
    </nav>

    <style>
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); }
        .pt-safe-top { padding-top: env(safe-area-inset-top, 0px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
function classNotes() {
    return {
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
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/cohorts`);
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
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/cohorts/${this.selectedCohortId}/students`);
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
        }
    }
}
</script>
@endsection