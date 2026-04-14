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

window.history = history;
