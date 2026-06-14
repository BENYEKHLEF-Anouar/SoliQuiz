import { secureFetch } from './common';

export default function qcmForm(timeRemainingSeconds, initialAnswers = {}, config) {
    return {
        answers: initialAnswers,
        isSaving: false,
        lastSaved: null,
        saveTimeout: null,
        timeRemaining: timeRemainingSeconds,
        isSubmitting: false,
        totalQuestions: config.totalQuestions,
        saveUrl: config.saveUrl,
        csrfToken: config.csrfToken,

        init() {
            this.startTimer();
            
            // Watchers for auto-save
            this.$watch('answers', () => {
                this.debouncedSave();
            });

            // Warn only if saving is in progress
            window.onbeforeunload = (e) => {
                if (this.isSaving) {
                    e.preventDefault();
                    return '';
                }
            };

            // Anti-copy measures
            document.addEventListener('contextmenu', e => e.preventDefault());
            document.addEventListener('copy', e => e.preventDefault());
            document.addEventListener('cut', e => e.preventDefault());
            document.addEventListener('keydown', (e) => {
                if (
                    (e.ctrlKey && ['c', 'x', 'u'].includes(e.key.toLowerCase())) ||
                    e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i')
                ) {
                    e.preventDefault();
                }
            });

            // Prevent PrintScreen key capture & clear clipboard
            document.addEventListener('keyup', (e) => {
                if (e.key === 'PrintScreen') {
                    navigator.clipboard.writeText('');
                }
            });

            // Blur page content when losing focus
            window.addEventListener('blur', () => {
                document.body.classList.add('blur-md', 'select-none');
            });
            window.addEventListener('focus', () => {
                document.body.classList.remove('blur-md');
            });
        },

        debouncedSave() {
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                this.persistAnswers();
            }, 2000); // Save after 2s of inactivity
        },

        async persistAnswers() {
            if (this.isSubmitting) return;
            this.isSaving = true;
            
            try {
                await secureFetch(this.saveUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: { answers: this.answers }
                });
                this.lastSaved = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } catch (e) {
                console.error('Auto-save failed', e);
            } finally {
                this.isSaving = false;
            }
        },

        selectAnswer(questionId, optionId) {
            this.answers[questionId] = String(optionId);
            this.answers = { ...this.answers };
        },

        clearQuestionAnswer(questionId) {
            this.answers[questionId] = null;
            this.answers = { ...this.answers };
        },

        toggleAnswer(questionId, optionId) {
            const current = this.answers[questionId] || [];
            const strId = String(optionId);
            const idx = current.map(String).indexOf(strId);
            if (idx === -1) {
                this.answers[questionId] = [...current, strId];
            } else {
                this.answers[questionId] = current.filter((_, i) => i !== idx);
            }
            this.answers = { ...this.answers };
        },

        isSelected(questionId, optionId) {
            const answer = this.answers[questionId];
            if (!answer) return false;
            if (Array.isArray(answer)) return answer.map(String).includes(String(optionId));
            return String(answer) === String(optionId);
        },

        isQuestionAnswered(questionId) {
            const answer = this.answers[questionId];
            if (!answer) return false;
            if (Array.isArray(answer)) return answer.length > 0;
            return true;
        },

        get answeredCount() {
            return Object.keys(this.answers).filter(id => this.isQuestionAnswered(id)).length;
        },

        get completionPercentage() {
            return this.totalQuestions > 0 ? (this.answeredCount / this.totalQuestions) * 100 : 0;
        },

        get formattedTime() {
            if (this.timeRemaining < 0) return 'Illimité';
            const m = Math.floor(this.timeRemaining / 60);
            const s = Math.floor(this.timeRemaining % 60);
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },

        startTimer() {
            if (this.timeRemaining < 0) return;
            if (this.timeRemaining <= 0) {
                this.onTimeUp();
                return;
            }
            const interval = setInterval(() => {
                if (this.isSubmitting) {
                    clearInterval(interval);
                    return;
                }
                this.timeRemaining--;
                if (this.timeRemaining <= 0) {
                    clearInterval(interval);
                    this.onTimeUp();
                }
            }, 1000);
        },

        onTimeUp() {
            this.$dispatch('open-modal', 'time-up');
            // Auto-submit after 5 seconds if no action
            setTimeout(() => {
                if (!this.isSubmitting) this.finalSubmit();
            }, 5000);
        },

        submitForm() {
            if (this.answeredCount < this.totalQuestions) {
                this.$dispatch('confirm', {
                    title: 'Évaluation incomplète',
                    message: 'Vous n\'avez pas répondu à toutes les questions. Soumettre quand même ?',
                    confirmText: 'Terminer',
                    cancelText: 'Continuer',
                    type: 'danger',
                    onConfirm: () => {
                        this.finalSubmit();
                    }
                });
            } else {
                this.$dispatch('confirm', {
                    title: 'Soumettre le QCM ?',
                    message: 'Êtes-vous sûr de vouloir soumettre vos réponses et terminer cette évaluation ?',
                    confirmText: 'Terminer',
                    cancelText: 'Annuler',
                    type: 'warning',
                    onConfirm: () => {
                        this.finalSubmit();
                    }
                });
            }
        },

        finalSubmit() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;
            window.onbeforeunload = null;
            this.persistAnswers().then(() => {
                document.getElementById('qcm-form').submit();
            });
        }
    };
}
