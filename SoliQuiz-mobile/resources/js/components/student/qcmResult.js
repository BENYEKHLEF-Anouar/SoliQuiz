export default () => ({
    qcmId: null,
    result: {},
    loading: false,
    async init() {
        const el = document.querySelector('[x-data-qcm-id]');
        this.qcmId = el?.getAttribute('x-data-qcm-id') || null;
        if (this.qcmId) {
            await this.fetchResult();
        }
    },
    async fetchResult() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/result`);
            if (!response.ok) throw new Error('Result not found');
            this.result = await response.json();
        } catch (e) {
            console.error('Failed to load result', e);
        } finally {
            setTimeout(() => { this.loading = false; }, 800);
        }
    },
    getOptionState(option, question) {
        const isSelected = question.userAnswer?.includes(option.id);
        const isCorrect = question.correctAnswer?.includes(option.id);
        
        if (isSelected && isCorrect) {
            return {
                cardClass: 'bg-emerald-50 border border-emerald-200 p-5 rounded-2xl shadow-sm relative overflow-hidden',
                textColor: 'text-emerald-800',
                statusClass: 'text-emerald-600',
                statusText: 'Ma réponse (Correcte)',
                showFeedback: true
            };
        } else if (isSelected && !isCorrect) {
            return {
                cardClass: 'bg-rose-50 border border-rose-200 p-5 rounded-2xl shadow-sm relative overflow-hidden',
                textColor: 'text-rose-800',
                statusClass: 'text-rose-600',
                statusText: 'Ma réponse (Fausse)',
                showFeedback: true
            };
        } else if (!isSelected && isCorrect) {
            return {
                cardClass: 'bg-emerald-50/50 border-2 border-dashed border-emerald-200 p-5 rounded-2xl',
                textColor: 'text-emerald-800',
                statusClass: 'text-emerald-600',
                statusText: 'Réponse attendue',
                showFeedback: true
            };
        } else {
            return {
                cardClass: 'bg-slate-50/50 border-2 border-transparent p-5 rounded-2xl opacity-60',
                textColor: 'text-slate-700',
                statusClass: 'text-slate-400',
                statusText: 'Fausse',
                showFeedback: false
            };
        }
    }
});
