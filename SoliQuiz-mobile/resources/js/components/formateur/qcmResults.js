export default () => ({
    qcmId: null,
    qcmTitle: '',
    classeName: '',
    results: [],
    filteredResults: [],
    loading: false,
    search: '',
    scoreFilter: 'all',
    sortBy: 'score',

    async init() {
        const el = document.querySelector('[x-data-qcm-id]');
        this.qcmId = el?.getAttribute('x-data-qcm-id') || null;
        if (this.qcmId) {
            await this.fetchResults();
        }
    },

    async fetchResults() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/qcms/${this.qcmId}/results`);
            const data = await response.json();
            this.qcmTitle = data.title;
            this.classeName = data.classeName || '';
            this.results = data.results.map((r, i) => ({ ...r, rank: i + 1 }));
            this.applyFilters();
        } catch (e) {
            console.error('Failed to load results', e);
        } finally {
            this.loading = false;
        }
    },

    applyFilters() {
        let filtered = [...this.results];

        // Search filter
        if (this.search) {
            const term = this.search.toLowerCase();
            filtered = filtered.filter(r => r.studentName.toLowerCase().includes(term));
        }

        // Score filter
        if (this.scoreFilter === 'pass') {
            filtered = filtered.filter(r => {
                const maxScore = r.maxScore || (r.totalQuestions * 2) || 20;
                return r.score >= (maxScore / 2);
            });
        } else if (this.scoreFilter === 'fail') {
            filtered = filtered.filter(r => {
                const maxScore = r.maxScore || (r.totalQuestions * 2) || 20;
                return r.score < (maxScore / 2);
            });
        }

        // Sort
        if (this.sortBy === 'score') {
            filtered.sort((a, b) => (b.score / b.totalQuestions) - (a.score / a.totalQuestions));
        } else if (this.sortBy === 'name') {
            filtered.sort((a, b) => a.studentName.localeCompare(b.studentName));
        } else if (this.sortBy === 'date') {
            filtered.sort((a, b) => new Date(b.date) - new Date(a.date));
        }

        // Reassign ranks after sorting
        this.filteredResults = filtered.map((r, i) => ({ ...r, rank: i + 1 }));
    },

    resetFilters() {
        this.search = '';
        this.scoreFilter = 'all';
        this.sortBy = 'score';
        this.applyFilters();
    },

    get passCount() {
        return this.results.filter(r => {
            const maxScore = r.maxScore || (r.totalQuestions * 2) || 20;
            const passingScore = maxScore / 2;
            return r.score >= passingScore;
        }).length;
    },

    get failCount() {
        return this.results.filter(r => {
            const maxScore = r.maxScore || (r.totalQuestions * 2) || 20;
            const passingScore = maxScore / 2;
            return r.score < passingScore;
        }).length;
    },

    getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    },

    getPercentage(res) {
        const maxScore = res.maxScore || res.totalQuestions * 2 || 20;
        if (!maxScore || !res.score) return 0;
        return Math.round((res.score / maxScore) * 100);
    },

    formatDate(dateStr) {
        if (!dateStr) return 'Date inconnue';
        const date = new Date(dateStr);
        return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
    },

    getScoreClass(res) {
        const pct = this.getPercentage(res);
        if (pct >= 70) return 'border-l-4 border-l-emerald-400';
        if (pct >= 50) return 'border-l-4 border-l-amber-400';
        return 'border-l-4 border-l-red-400';
    },

    getScoreColorClass(res) {
        const pct = this.getPercentage(res);
        if (pct >= 70) return 'bg-emerald-500';
        if (pct >= 50) return 'bg-amber-500';
        return 'bg-red-500';
    },

    getScoreBadgeClass(res) {
        const pct = this.getPercentage(res);
        if (pct >= 70) return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        if (pct >= 50) return 'bg-amber-50 text-amber-600 border-amber-100';
        return 'bg-red-50 text-red-600 border-red-100';
    }
});
