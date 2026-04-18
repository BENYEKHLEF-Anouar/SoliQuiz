@extends('components.layout.app')

@section('content')
<div x-data="history" x-init="init()" class="h-full flex flex-col relative overflow-hidden bg-slate-50">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-slate-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('student.dashboard') }}">
                    <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 active:scale-95 transition-transform">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                </a>
                <h1 class="text-xl font-heading font-extrabold text-slate-900 tracking-tight">Historique</h1>
            </div>
            <div class="size-9 rounded-full bg-slate-100 flex items-center justify-center">
                <svg class="size-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full px-5 py-8 pb-32 hide-scrollbar relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Extraction des scores..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <div class="space-y-8">
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <div class="flex flex-col mb-6">
                        <h2 class="text-lg font-heading font-extrabold text-slate-900 tracking-tight">VOS RÉSULTATS</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Parcours individuel</p>
                    </div>
                    
                    <div class="grid gap-4">
                        <template x-for="item in history" :key="item.id">
                            <div class="p-5 flex items-center bg-white border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] rounded-[2rem] cursor-pointer active:scale-[0.98] transition-all hover:border-slate-200 group" 
                                 @click="window.location.href = '/student/qcm/' + item.id + '/result'">
                                <div class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-primary-500 shrink-0 group-hover:bg-primary-50 transition-colors">
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ms-5 flex-1">
                                    <h3 class="font-extrabold text-slate-900 text-base leading-tight" x-text="item.title"></h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight mt-1" x-text="item.date"></p>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1.5 rounded-xl text-xs font-black tracking-tight border shadow-sm transition-all"
                                        :class="item.score >= 10 ? 'bg-primary-50 text-primary-600 border-primary-100' : 'bg-semantic-error/10 text-semantic-error border-semantic-error/10'">
                                        <span x-text="item.score"></span> <span class="opacity-40 text-[9px]">/</span> 20
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div x-show="history.length === 0" class="text-center py-24 animate-in fade-in duration-700">
                <div class="size-20 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-inner">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-base font-extrabold text-slate-900 tracking-tight">Aucune activité</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Commencez un QCM pour voir vos stats</p>
            </div>
        </div>
    </main>

    <!-- Navigation -->
    @include('components.nav.student-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('history', () => ({
            history: [],
            loading: false,
            async init() {
                await this.fetchHistory();
            },
            async fetchHistory() {
                this.loading = true;
                try {
                    const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/history`);
                    this.history = await response.json();
                } catch (e) {
                    console.error('Failed to load history', e);
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endsection