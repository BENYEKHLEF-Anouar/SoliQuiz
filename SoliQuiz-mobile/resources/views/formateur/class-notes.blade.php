@extends('layouts.app')

@section('content')
<div x-data="classNotes()" x-init="init()">
    <!-- Header -->
    <header class="bg-white px-5 pt-safe-top pb-4 border-b border-slate-200 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex items-center justify-between mt-2">
            <a href="{{ route('formateur.profile') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1 text-center">
                <span class="text-sm font-bold text-slate-800">Notes de Classe</span>
            </div>
            <div class="w-6"></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 px-5 py-6">
        <!-- Cohort selector -->
        <div class="mb-6">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Classe</label>
            <select x-model="selectedCohortId" @change="fetchStudents"
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Sélectionner une classe</option>
                <template x-for="cohort in cohorts" :key="cohort.id">
                    <option :value="cohort.id" x-text="cohort.name + ' (' + cohort.promotion + ')'"></option>
                </template>
            </select>
        </div>

        <!-- Class average -->
        <div x-show="selectedCohortId" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm mb-6">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Moyenne de la Classe</span>
            <span class="text-2xl font-heading font-bold text-primary-600" x-text="classAverage + '/20'"></span>
        </div>

        <!-- Student list -->
        <div class="space-y-4">
            <template x-for="student in students" :key="student.id">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img class="size-12 rounded-full border-2 border-white shadow"
                                src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=100&h=100&q=80"
                                alt="Avatar">
                            <div x-show="student.alert" class="absolute -top-1 -right-1 size-4 bg-danger-500 rounded-full border-2 border-white"></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-slate-900" x-text="student.name"></h3>
                            <span class="text-xs text-slate-400" x-text="'Moyenne: ' + student.averageScore + '/20'"></span>
                        </div>
                        <button @click="showStudentDetail(student)" class="text-primary-500">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="flex justify-center py-10">
            <svg class="animate-spin size-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </main>

    <!-- Student detail bottom sheet (simplified) -->
    <div id="hs-offcanvas-student"
        class="hs-overlay hs-overlay-open:translate-y-0 translate-y-full fixed bottom-0 inset-x-0 transition-all duration-300 transform h-3/4 max-w-[430px] mx-auto w-full z-[80] bg-white border-t border-slate-200 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] hidden"
        role="dialog" tabindex="-1" aria-labelledby="hs-offcanvas-student-label">
        <div class="flex justify-between items-center py-3 px-5 border-b border-slate-100">
            <h3 id="hs-offcanvas-student-label" class="font-bold text-slate-800" x-text="selectedStudent?.name"></h3>
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
        <div class="p-5 overflow-y-auto max-h-full">
            <div class="flex items-center gap-4 mb-6">
                <img class="size-16 rounded-full border-2 border-white shadow"
                    src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=100&h=100&q=80"
                    alt="Avatar">
                <div>
                    <h4 class="font-bold text-slate-900" x-text="selectedStudent?.name"></h4>
                    <span class="text-sm text-slate-500" x-text="'Dernier QCM: ' + (selectedStudent?.lastQcmScore || 'N/A')"></span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="p-4 bg-slate-50 rounded-xl">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Participation</span>
                    <span class="text-lg font-bold text-slate-800" x-text="selectedStudent?.participation"></span>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Moyenne Générale</span>
                    <span class="text-lg font-bold" :class="selectedStudent?.averageScore >= 10 ? 'text-success-500' : 'text-danger-500'"
                        x-text="selectedStudent?.averageScore + '/20'"></span>
                </div>
                <button class="w-full py-3 bg-primary-500 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20">
                    Envoyer un message de support
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function classNotes() {
    return {
        cohorts: [],
        selectedCohortId: '',
        students: [],
        selectedStudent: null,
        loading: false,
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
            // Optionally fetch performance details
            // const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/students/${student.id}/performance`);
            // this.selectedStudent = await response.json();
            // Open bottom sheet
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