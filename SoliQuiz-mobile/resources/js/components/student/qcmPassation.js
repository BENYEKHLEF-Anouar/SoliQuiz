export default () => ({
    qcmId: null,
    qcm: {},
    questions: [],
    currentIndex: 0,
    loading: false,
    selectedOptions: {},
    timerMinutes: 0,
    timerSeconds: 0,
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
        if (!this.qcmId) {
            console.error('QCM ID not found');
            return;
        }
        await this.fetchQcm();
        await this.fetchQuestions();
        this.startTimer();
    },
    async fetchQcm() {
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/start`);
            const data = await response.json();
            this.qcm = data;
            if (data.tempsRestant > 0) {
                this.timerMinutes = Math.floor(data.tempsRestant / 60);
                this.timerSeconds = data.tempsRestant % 60;
            } else if (data.durationMinutes > 0) {
                this.timerMinutes = data.durationMinutes;
                this.timerSeconds = 0;
            } else {
                this.timerMinutes = 0;
                this.timerSeconds = 0;
            }
            if (data.initialAnswers) {
                this.selectedOptions = data.initialAnswers;
            }
        } catch (e) {
            console.error('Failed to start/load QCM', e);
            this.timerMinutes = 5;
            this.timerSeconds = 0;
        }
    },
    async fetchQuestions() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/questions`);
            this.questions = await response.json();
            this.questions.forEach(q => {
                if (!this.selectedOptions[q.id]) {
                    this.selectedOptions[q.id] = [];
                }
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
    clearSelection() {
        const q = this.currentQuestion;
        if (!q) return;
        this.selectedOptions[q.id] = [];
    },
    prevQuestion() {
        if (this.currentIndex > 0) this.currentIndex--;
    },
    async nextQuestion() {
        if (this.currentIndex < this.totalQuestions - 1) {
            this.currentIndex++;
        } else {
            await this.submitQcm();
        }
    },
    async submitQcm() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/submit`, {
                method: 'POST',
                body: JSON.stringify({ answers: this.selectedOptions })
            });
            if (response.ok) {
                window.location.href = `/student/qcm/${this.qcmId}/result`;
            } else {
                alert('Erreur lors de la soumission.');
            }
        } catch (e) {
            console.error('Failed to submit QCM', e);
            alert('Erreur réseau lors de la soumission.');
        } finally {
            this.loading = false;
        }
    },
    startTimer() {
        if (this.timerMinutes === 0 && this.timerSeconds === 0 && this.qcm.durationMinutes === 0) {
            return;
        }
        this.timerInterval = setInterval(async () => {
            if (this.timerSeconds === 0) {
                if (this.timerMinutes === 0) {
                    clearInterval(this.timerInterval);
                    await this.submitQcm();
                    return;
                }
                this.timerMinutes--;
                this.timerSeconds = 59;
            } else {
                this.timerSeconds--;
            }
        }, 1000);
    }
});
