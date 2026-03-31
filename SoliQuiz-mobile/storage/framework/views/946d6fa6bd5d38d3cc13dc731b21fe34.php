<?php $__env->startSection('content'); ?>
<div x-data="history()" x-init="init()">
    <!-- Header -->
    <header class="bg-white px-5 pt-safe-top pb-4 border-b border-slate-200 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex items-center justify-between mt-2">
            <a href="<?php echo e(route('student.dashboard')); ?>" class="text-slate-400 hover:text-slate-600">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1 text-center">
                <span class="text-sm font-bold text-slate-800">Historique</span>
            </div>
            <div class="w-6"></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 px-5 py-6">
        <!-- History list -->
        <div class="space-y-4">
            <template x-for="item in history" :key="item.id">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="item.date"></span>
                        <span class="text-sm font-bold" :class="item.score >= 10 ? 'text-success-500' : 'text-danger-500'"
                            x-text="item.score + '/' + item.totalQuestions"></span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 leading-snug font-heading" x-text="item.title"></h3>
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

        <!-- Empty state -->
        <div x-show="!loading && history.length === 0" class="text-center py-10 text-slate-400">
            Aucun historique trouvé.
        </div>
    </main>
</div>

<script>
function history() {
    return {
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
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Webprojects\SoliQuiz\SoliQuiz-mobile\resources\views/student/history.blade.php ENDPATH**/ ?>