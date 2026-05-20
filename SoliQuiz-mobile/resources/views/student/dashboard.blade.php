@extends('components.layout.app')

@section('content')
<div x-data="dashboard" x-init="init()" class="h-full flex flex-col relative overflow-hidden font-sans">
    <!-- Header -->
    @include('components.header.student-header')

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Synchronisation en cours..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Premium Greeting Section -->
            <div class="px-5 pt-8 pb-10 relative overflow-hidden group">
                <!-- Subtle Background Decorative Element -->
                <div class="absolute -top-10 -right-10 size-40 bg-primary-100/50 rounded-full blur-3xl transition-transform group-hover:scale-110 duration-1000"></div>
                
                <div class="relative">
                    <div class="inline-flex items-center gap-x-1.5 py-1.5 px-3.5 rounded-full bg-primary-50 text-primary-600 border border-primary-100/50 mb-5 relative z-10 transition-all hover:bg-primary-100">
                        <span class="size-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em]" x-text="'Promotion ' + (profile.cohort || 'DW_102')"></span>
                    </div>
                    <h2 class="text-4xl font-heading font-extrabold text-slate-900 tracking-tighter leading-[0.95] mb-3">
                        Bonjour, <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400" x-text="profile.prenom || 'Apprenant'"></span>
                    </h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">C'est le moment de briller.</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="-mt-8">
                @include('partials.stats-grid')
            </div>

            <!-- Evaluations Section -->
            <section class="px-5 mt-12 space-y-6">
                <div class="flex justify-between items-end mb-4">
                    <div class="flex flex-col">
                        <h2 class="text-xl font-heading font-extrabold text-slate-900 tracking-tight">VOS ÉVALUATIONS</h2>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Aujourd'hui & À venir</p>
                    </div>
                    <div x-show="urgentCount > 0" class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-xl bg-semantic-warning/10 text-semantic-warning border border-semantic-warning/10">
                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        <span class="text-[10px] font-black uppercase tracking-tight" x-text="urgentCount + ' Urgent'"></span>
                    </div>
                </div>
                
                <div class="grid gap-5 text-left">
                    <template x-for="evaluation in evaluations" :key="evaluation.id">
                        <div class="group bg-white border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] rounded-[2.5rem] p-7 relative overflow-hidden transition-all duration-300 hover:border-slate-200">
                            
                            <!-- Status Indicator Accent -->
                            <div class="absolute top-0 right-12 w-16 h-1 rounded-b-full transition-all duration-500" 
                                :class="evaluation.urgent ? 'bg-semantic-warning shadow-[0_0_15px_rgba(245,158,11,0.4)]' : 'bg-slate-100'"></div>
                            
                            <div class="flex justify-between items-start mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="size-11 rounded-[1.25rem] bg-slate-50 flex items-center justify-center text-slate-300 transition-all">
                                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="m9 15 2 2 4-4"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]" x-text="evaluation.subject"></span>
                                </div>
                            </div>

                            <h3 class="text-2xl font-heading font-extrabold text-slate-900 leading-[1.1] tracking-tight mb-8" x-text="evaluation.title"></h3>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-x-2 text-[10px] font-black uppercase" :class="evaluation.urgent ? 'text-semantic-warning' : 'text-slate-300'">
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span class="tracking-widest" x-text="evaluation.urgent ? 'Expire aujourd\'hui' : 'À venir'"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>
        </div>
    </main>

    <!-- Navigation -->
    @include('components.nav.student-bottom-nav')

    <!-- Notifications Panel -->
    <div id="hs-offcanvas-notifications" class="hs-overlay hs-overlay-open:translate-y-0 translate-y-full fixed bottom-0 inset-x-0 transition-all duration-300 transform h-3/4 max-w-[430px] mx-auto w-full z-[80] bg-white border-t border-slate-200 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] hidden" role="dialog" tabindex="-1" aria-labelledby="hs-offcanvas-notifications-label">
        <div class="flex justify-between items-center py-3 px-5 border-b border-slate-100">
            <h3 id="hs-offcanvas-notifications-label" class="font-bold text-slate-800 uppercase tracking-widest text-xs">Notifications</h3>
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
                    <p class="text-sm font-bold mb-1 uppercase tracking-tight" x-text="notif.type === 'urgent' ? 'QCM Urgent !' : 'Note publiée'"></p>
                    <p class="text-xs" x-text="notif.message"></p>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard', () => ({
            profile: {},
            scores: {},
            evaluations: [],
            notifications: [],
            loading: true,
            error: null,
            get urgentCount() {
                return this.evaluations.filter(e => e.urgent).length;
            },
            async init() {
                this.loading = true;
                try {
                    await Promise.all([
                        this.fetchProfile(),
                        this.fetchScores(),
                        this.fetchEvaluations(),
                        this.fetchNotifications()
                    ]);
                } catch (e) {
                    console.error('Initialisation failed', e);
                } finally {
                    // Small delay for smooth transition
                    setTimeout(() => { this.loading = false; }, 800);
                }
            },
            async fetchProfile() {
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/student/profile`);
                    this.profile = await response.json();
                } catch (e) {
                    console.error('Failed to load profile', e);
                }
            },
            async fetchScores() {
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/student/scores`);
                    this.scores = await response.json();
                } catch (e) {
                    console.error('Failed to load scores', e);
                }
            },
            async fetchEvaluations() {
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/student/evaluations`);
                    this.evaluations = await response.json();
                } catch (e) {
                    console.error('Failed to load evaluations', e);
                }
            },
            async fetchNotifications() {
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/student/notifications`);
                    this.notifications = await response.json();
                } catch (e) {
                    console.error('Failed to load notifications', e);
                }
            }
        }));
    });
</script>
@endsection

