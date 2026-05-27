export default () => ({
    qcms: [],
    filteredQcms: [],
    loading: true,
    search: '',
    statusFilter: 'all',
    uniteFilter: 'all',
    uniteLabel: 'Toutes les UAs',

    async init() {
        this.loading = true;
        await this.fetchQcms();
    },

    async fetchQcms() {
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/qcms`);
            this.qcms = await response.json();
            this.applyFilters();
        } catch (e) {
            console.error('Failed to load QCMs', e);
        } finally {
            setTimeout(() => { this.loading = false; }, 800);
        }
    },

    get unitesList() {
        const unique = new Map();
        this.qcms.forEach(q => {
            if (q.unite_id) {
                unique.set(q.unite_id, q.unite_nom);
            }
        });
        return Array.from(unique.entries()).map(([id, nom]) => ({ id, nom }));
    },

    applyFilters() {
        let filtered = [...this.qcms];

        // Search filter
        if (this.search) {
            const term = this.search.toLowerCase();
            filtered = filtered.filter(q => q.title.toLowerCase().includes(term));
        }

        // Status filter
        if (this.statusFilter !== 'all') {
            filtered = filtered.filter(q => q.status === this.statusFilter);
        }

        // Unite filter
        if (this.uniteFilter !== 'all') {
            filtered = filtered.filter(q => q.unite_id == this.uniteFilter);
        }

        this.filteredQcms = filtered;
    },

    resetFilters() {
        this.search = '';
        this.statusFilter = 'all';
        this.uniteFilter = 'all';
        this.uniteLabel = 'Toutes les UAs';
        this.applyFilters();
    }
});
