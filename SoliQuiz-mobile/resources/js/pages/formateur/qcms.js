function formateurQcms() {
    return {
        qcms: [],
        loading: false,
        search: '',
        get filteredQcms() {
            if (!this.search) return this.qcms;
            const term = this.search.toLowerCase();
            return this.qcms.filter(q => q.title.toLowerCase().includes(term));
        },
        async init() {
            await this.fetchQcms();
        },
        async fetchQcms() {
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/qcms`);
                this.qcms = await response.json();
            } catch (e) {
                console.error('Failed to load QCMs', e);
            } finally {
                this.loading = false;
            }
        }
    }
}
