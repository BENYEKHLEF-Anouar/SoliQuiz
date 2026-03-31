<?php $__env->startSection('content'); ?>
<div x-data="qcmPassation()" x-init="init()">
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
                <span class="text-sm font-bold text-slate-800" x-text="qcm.title"></span>
            </div>
            <div class="w-6"></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 px-5 py-6">
        <!-- Progress -->
        <div class="flex justify-between items-center mb-6">
            <span class="text-sm font-bold text-slate-500">
                Question <span x-text="currentIndex + 1"></span>/<span x-text="totalQuestions"></span>
            </span>
            <div class="w-32 h-2 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 transition-all duration-300"
                    :style="`width: ${((currentIndex + 1) / totalQuestions) * 100}%`"></div>
            </div>
        </div>

        <!-- Timer (static for now) -->
        <div class="flex justify-end mb-4">
            <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">
                <svg class="inline size-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>03:45</span>
            </span>
        </div>

        <!-- Question -->
        <template x-if="currentQuestion">
            <div>
                <h2 class="text-lg font-heading font-bold text-slate-900 mb-6" x-text="currentQuestion.text"></h2>
                <div class="space-y-3">
                    <template x-for="option in currentQuestion.options" :key="option.id">
                        <label class="block p-4 border rounded-xl cursor-pointer transition-all"
                            :class="selectedOptionIds.includes(option.id) ? 'border-primary-500 bg-primary-50' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-0.5">
                                    <input :type="currentQuestion.type === 'unique' ? 'radio' : 'checkbox'"
                                        :name="'question-' + currentQuestion.id"
                                        :value="option.id"
                                        class="size-4 text-primary-600 border-slate-300 focus:ring-primary-500"
                                        @change="selectOption(option.id)"
                                        :checked="selectedOptionIds.includes(option.id)">
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-slate-800" x-text="option.text"></span>
                                </div>
                            </div>
                        </label>
                    </template>
                </div>
            </div>
        </template>

        <!-- Loading state -->
        <div x-show="loading" class="flex justify-center py-10">
            <svg class="animate-spin size-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </main>

    <!-- Navigation buttons -->
    <nav class="fixed bottom-0 w-full max-w-[430px] bg-white border-t border-slate-200 z-50">
        <div class="flex justify-between items-center h-[72px] px-4 pb-safe">
            <button @click="prevQuestion" :disabled="currentIndex === 0"
                class="px-4 py-2 rounded-xl font-bold text-slate-500 disabled:opacity-50">
                Précédent
            </button>
            <button @click="nextQuestion" :disabled="currentIndex === totalQuestions - 1"
                class="px-4 py-2 rounded-xl font-bold bg-primary-500 text-white shadow-lg shadow-primary-500/20 disabled:opacity-50">
                Suivant
            </button>
        </div>
    </nav>
</div>

<script>
function qcmPassation() {
    return {
        qcmId: <?php echo json_encode($qcmId, 15, 512) ?>,
        qcm: {},
        questions: [],
        currentIndex: 0,
        loading: false,
        selectedOptions: {}, // questionId => array of optionIds
        get totalQuestions() {
            return this.questions.length;
        },
        get currentQuestion() {
            return this.questions[this.currentIndex];
        },
        get selectedOptionIds() {
            if (!this.currentQuestion) return [];
            return this.selectedOptions[this.currentQuestion.id] || [];
        },
        async init() {
            await this.fetchQcm();
            await this.fetchQuestions();
        },
        async fetchQcm() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}`);
                this.qcm = await response.json();
            } catch (e) {
                console.error('Failed to load QCM', e);
            }
        },
        async fetchQuestions() {
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/questions`);
                this.questions = await response.json();
                // Initialize selected options
                this.questions.forEach(q => {
                    this.selectedOptions[q.id] = [];
                });
            } catch (e) {
                console.error('Failed to load questions', e);
            } finally {
                this.loading = false;
            }
        },
        selectOption(optionId) {
            const q = this.currentQuestion;
            if (!q) return;
            if (q.type === 'unique') {
                this.selectedOptions[q.id] = [optionId];
            } else {
                // multiple
                const idx = this.selectedOptions[q.id].indexOf(optionId);
                if (idx > -1) {
                    this.selectedOptions[q.id].splice(idx, 1);
                } else {
                    this.selectedOptions[q.id].push(optionId);
                }
            }
        },
        prevQuestion() {
            if (this.currentIndex > 0) this.currentIndex--;
        },
        nextQuestion() {
            if (this.currentIndex < this.totalQuestions - 1) this.currentIndex++;
        }
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Webprojects\SoliQuiz\SoliQuiz-mobile\resources\views/student/qcm-passation.blade.php ENDPATH**/ ?>