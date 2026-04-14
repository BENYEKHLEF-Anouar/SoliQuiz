function qcmResult() {
    return {
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
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/result`);
                if (!response.ok) throw new Error('Result not found');
                this.result = await response.json();
            } catch (e) {
                console.error('Failed to load result', e);
            } finally {
                this.loading = false;
            }
        },
        formatAnswer(optionIds, question) {
            if (!optionIds || optionIds.length === 0) return 'Aucune';
            const texts = optionIds.map(id => {
                return 'Option ' + id;
            }).filter(Boolean);
            return texts.join(', ');
        }
    }
}

window.qcmResult = qcmResult;
