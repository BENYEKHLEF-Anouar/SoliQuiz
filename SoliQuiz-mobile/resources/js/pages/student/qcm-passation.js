function qcmPassation() {
    return {
        qcmId: null,
        qcm: {},
        questions: [],
        currentIndex: 0,
        loading: false,
        selectedOptions: {},
        timerMinutes: 3,
        timerSeconds: 45,
        timerInterval: null,
        get totalQuestions() { return this.questions.length; },
        get currentQuestion() { return this.questions[this.currentIndex]; },
        get selectedOptionIds() {
            if (!this.currentQuestion) return [];
            return this.selectedOptions[this.currentQuestion.id] || [];
        },
        get timerDisplay() {
            return `${this.timerMinutes.toString().padStart(2, '0')}:${this.timerSeconds.toString().padStart(2, '0')}`;
        },
        async init() {
            const el = document.querySelector('[x-data-qcm-id]');
            this.qcmId = el?.getAttribute('x-data-qcm-id') || null;
            await this.fetchQcm();
            await this.fetchQuestions();
            this.startTimer();
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
        },
        startTimer() {
            this.timerInterval = setInterval(() => {
                if (this.timerSeconds === 0) {
                    if (this.timerMinutes === 0) {
                        clearInterval(this.timerInterval);
                        return;
                    }
                    this.timerMinutes--;
                    this.timerSeconds = 59;
                } else {
                    this.timerSeconds--;
                }
            }, 1000);
        }
    }
}
