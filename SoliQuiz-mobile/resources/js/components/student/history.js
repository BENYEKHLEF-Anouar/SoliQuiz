export default () => ({
    history: [],
    loading: false,
    filterStatus: 'all',
    get lastScores() {
        return this.history.slice(0, 7).map(item => Number(item.score)).reverse();
    },
    get lastAttempts() {
        return this.history.slice(0, 7).map(item => ({
            score: Number(item.score),
            title: item.title
        })).reverse();
    },
    get maxScore() {
        const scores = this.lastScores;
        return scores.length > 0 ? Math.max(...scores) : 20;
    },
    get filteredHistory() {
        if (this.filterStatus === 'success') {
            return this.history.filter(item => item.score >= 10);
        }
        if (this.filterStatus === 'failure') {
            return this.history.filter(item => item.score < 10);
        }
        return this.history;
    },
    async init() {
        await this.fetchHistory();
    },
    async fetchHistory() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/etudiant/history`);
            this.history = await response.json();
        } catch (e) {
            console.error('Failed to load history', e);
        } finally {
            this.loading = false;
        }
    }
});
