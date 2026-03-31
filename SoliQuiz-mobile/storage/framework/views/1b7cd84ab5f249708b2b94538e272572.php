<?php $__env->startSection('content'); ?>
<div x-data="dashboard()" x-init="init()">
    <!-- Header App -->
    <header class="bg-white px-5 pt-safe-top pb-4 border-b border-slate-200 shadow-sm shrink-0 sticky top-0 z-40">
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
                        <span class="text-xl font-heading font-bold text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Apprenant</span>
                    </div>
                </a>
            </div>

            <!-- Profile Dropdown -->
            <div class="hs-dropdown relative inline-flex">
                <button id="hs-dropdown-profile" type="button"
                    class="hs-dropdown-toggle w-10 h-10 rounded-full border border-slate-200 bg-white shadow-sm overflow-hidden active:scale-95 transition-transform">
                    <img class="w-full h-full object-cover"
                        src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80"
                        alt="Avatar">
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100 z-50"
                    role="menu">
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-800 hover:bg-slate-50"
                        href="<?php echo e(route('student.profile')); ?>">Mon Profil</a>
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-danger-500 hover:bg-danger-50 font-bold"
                        href="#">Déconnexion</a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 text-slate-800">
        <!-- Dynamic greeting -->
        <div class="px-5 py-6 bg-gradient-to-b from-white to-slate-50 border-b border-slate-100">
            <p class="text-[11px] font-bold text-primary-500 uppercase tracking-widest mb-1" x-text="'Session Active : ' + (profile.cohort || '...')"></p>
            <h2 class="text-2xl font-heading font-bold text-slate-900 leading-tight">Bonjour, <span class="text-primary-600" x-text="profile.prenom || '...'"></span></h2>
        </div>

        <section class="px-5 mt-6 grid grid-cols-2 gap-3">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Score Global</span>
                <span class="text-2xl font-heading font-bold text-primary-600" x-text="scores.globalScore + '%'"></span>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-slate-400">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Classement</span>
                <span class="text-2xl font-heading font-bold text-slate-800" x-text="scores.ranking"></span>
                <span class="text-sm">/<span x-text="scores.totalStudents"></span></span>
            </div>
        </section>

        <section class="px-5 mt-8 space-y-4">
            <h2 class="text-lg font-heading font-bold text-slate-900 flex justify-between items-center">
                <span>Évaluations (<span x-text="evaluations.length"></span>)</span>
                <span x-show="urgentCount > 0"
                    class="text-xs font-semibold text-warning-500 bg-warning-100 px-2 py-0.5 rounded-md"
                    x-text="urgentCount + ' Urgent'"></span>
            </h2>
            <template x-for="evaluation in evaluations" :key="evaluation.id">
                <a :href="'/student/qcm/' + evaluation.id"
                    class="block bg-white border border-slate-200 shadow-sm rounded-2xl p-5 relative overflow-hidden active:scale-[0.98] transition-all">
                    <div class="absolute top-0 right-0 w-1.5 h-full" :class="evaluation.urgent ? 'bg-warning-500' : 'bg-transparent'"></div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="evaluation.subject"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest"
                            :class="evaluation.urgent ? 'text-warning-500' : 'text-slate-400'"
                            x-text="evaluation.urgent ? 'Aujourd\'hui' : 'À venir'"></span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 leading-snug font-heading pr-4" x-text="evaluation.title"></h3>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-primary-600 font-bold text-sm">
                        Démarrer le test
                        <svg class="ml-1 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </a>
            </template>
        </section>
    </main>

    <!-- Navigation Mobile Fixe (Apprenant) -->
    <nav class="fixed bottom-0 w-full max-w-[430px] bg-white border-t border-slate-200 z-50">
        <div class="flex justify-around items-center h-[72px] px-4 pb-safe">
            <a href="<?php echo e(route('student.dashboard')); ?>"
                class="flex flex-col items-center justify-center text-primary-500 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Home</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center text-slate-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Library</span>
            </a>
            <a href="<?php echo e(route('student.history')); ?>" class="flex flex-col items-center justify-center text-slate-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Stats</span>
            </a>
            <a href="<?php echo e(route('student.profile')); ?>" class="flex flex-col items-center justify-center text-slate-400 gap-1.5">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-widest">Profil</span>
            </a>
        </div>
    </nav>

    <!-- Notifications Panel (simplified) -->
    <div id="hs-offcanvas-notifications"
        class="hs-overlay hs-overlay-open:translate-y-0 translate-y-full fixed bottom-0 inset-x-0 transition-all duration-300 transform h-3/4 max-w-[430px] mx-auto w-full z-[80] bg-white border-t border-slate-200 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] hidden"
        role="dialog" tabindex="-1" aria-labelledby="hs-offcanvas-notifications-label">
        <div class="flex justify-between items-center py-3 px-5 border-b border-slate-100">
            <h3 id="hs-offcanvas-notifications-label" class="font-bold text-slate-800">
                Notifications
            </h3>
            <button type="button"
                class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-slate-100 text-slate-800 hover:bg-slate-200 focus:outline-none focus:bg-slate-200"
                aria-label="Close" data-hs-overlay="#hs-offcanvas-notifications">
                <span class="sr-only">Fermer</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
        <div class="p-5 overflow-y-auto max-h-full">
            <template x-for="notif in notifications" :key="notif.id">
                <div class="p-4 rounded-xl border mb-3"
                    :class="notif.type === 'urgent' ? 'bg-warning-100 text-warning-700 border-warning-200' : 'bg-slate-50 text-slate-800 border-slate-100'">
                    <p class="text-sm font-bold mb-1" x-text="notif.type === 'urgent' ? 'QCM Urgent !' : 'Note publiée'"></p>
                    <p class="text-xs" x-text="notif.message"></p>
                </div>
            </template>
        </div>
    </div>

    <style>
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 20px);
        }
        .pt-safe-top {
            padding-top: env(safe-area-inset-top, 0px);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Webprojects\SoliQuiz\SoliQuiz-mobile\resources\views/student/dashboard.blade.php ENDPATH**/ ?>