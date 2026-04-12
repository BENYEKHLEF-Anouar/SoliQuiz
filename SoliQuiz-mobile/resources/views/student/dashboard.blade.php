@extends('components.layout.app')

@section('content')
<div x-data="dashboard()" x-init="init()" class="h-[100dvh] flex flex-col relative overflow-hidden font-sans">
    <!-- Header -->
    @include('components.header.student-header')

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 text-slate-800">
        <!-- Dynamic greeting -->
        <div class="px-5 py-6 bg-gradient-to-b from-white to-slate-50 border-b border-slate-100">
            <p class="text-[11px] font-bold text-primary-500 uppercase tracking-widest mb-1">Session Active : <span x-text="profile.cohort || '...'"></span></p>
            <h2 class="text-2xl font-heading font-bold text-slate-900 leading-tight">Bonjour, <span class="text-primary-600" x-text="profile.prenom || '...'"></span></h2>
        </div>

        <!-- Stats Grid -->
        @include('partials.stats-grid')

        <!-- Evaluations Section -->
        <section class="px-5 mt-8 space-y-4">
            <h2 class="text-lg font-heading font-bold text-slate-900 flex justify-between items-center">
                <span>Évaluations (<span x-text="evaluations.length"></span>)</span>
                <span x-show="urgentCount > 0" class="text-xs font-semibold text-semantic-warning bg-semantic-warning/20 px-2 py-0.5 rounded-md" x-text="urgentCount + ' Urgent'"></span>
            </h2>
            <template x-for="evaluation in evaluations" :key="evaluation.id">
                <a :href="'/student/qcm/' + evaluation.id" class="block bg-white border border-slate-200 shadow-sm rounded-2xl p-5 relative overflow-hidden active:scale-[0.98] transition-all">
                    <div class="absolute top-0 right-0 w-1.5 h-full" :class="evaluation.urgent ? 'bg-semantic-warning' : 'bg-transparent'"></div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="evaluation.subject"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="evaluation.urgent ? 'text-semantic-warning' : 'text-slate-400'" x-text="evaluation.urgent ? 'Aujourd\'hui' : 'À venir'"></span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 leading-snug font-heading pr-4" x-text="evaluation.title"></h3>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-primary-600 font-bold text-sm">
                        Démarrer le test
                        <svg class="ml-1 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </a>
            </template>
        </section>
    </main>

    <!-- Navigation -->
    @include('components.nav.student-bottom-nav')

    <!-- Notifications Panel -->
    <div id="hs-offcanvas-notifications" class="hs-overlay hs-overlay-open:translate-y-0 translate-y-full fixed bottom-0 inset-x-0 transition-all duration-300 transform h-3/4 max-w-[430px] mx-auto w-full z-[80] bg-white border-t border-slate-200 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] hidden" role="dialog" tabindex="-1" aria-labelledby="hs-offcanvas-notifications-label">
        <div class="flex justify-between items-center py-3 px-5 border-b border-slate-100">
            <h3 id="hs-offcanvas-notifications-label" class="font-bold text-slate-800">Notifications</h3>
            <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none focus:bg-slate-200" aria-label="Close" data-hs-overlay="#hs-offcanvas-notifications">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
        <div class="p-5 overflow-y-auto max-h-full">
            <template x-for="notif in notifications" :key="notif.id">
                <div class="p-4 rounded-xl border mb-3" :class="notif.type === 'urgent' ? 'bg-semantic-warning/20 text-semantic-warning border-semantic-warning/30' : 'bg-slate-50 text-slate-800 border-slate-100'">
                    <p class="text-sm font-bold mb-1" x-text="notif.type === 'urgent' ? 'QCM Urgent !' : 'Note publiée'"></p>
                    <p class="text-xs" x-text="notif.message"></p>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function dashboard() {
    return {
        profile: {},
        scores: {},
        evaluations: [],
        notifications: [],
        loading: false,
        error: null,
        get urgentCount() {
            return this.evaluations.filter(e => e.urgent).length;
        },
        async init() {
            await this.fetchProfile();
            await Promise.all([
                this.fetchScores(),
                this.fetchEvaluations(),
                this.fetchNotifications()
            ]);
        },
        async fetchProfile() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/profile`);
                this.profile = await response.json();
            } catch (e) {
                console.error('Failed to load profile', e);
            }
        },
        async fetchScores() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/scores`);
                this.scores = await response.json();
            } catch (e) {
                console.error('Failed to load scores', e);
            }
        },
        async fetchEvaluations() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/evaluations`);
                this.evaluations = await response.json();
            } catch (e) {
                console.error('Failed to load evaluations', e);
            }
        },
        async fetchNotifications() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/notifications`);
                this.notifications = await response.json();
            } catch (e) {
                console.error('Failed to load notifications', e);
            }
        }
    }
}
</script>
@endsection