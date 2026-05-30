export default () => ({
    allQcms: [],
    filteredQcms: [],
    unites: [],
    moyenne: 0,
    loading: true,
    
    search: '',
    uaId: '',
    uaLabel: 'Toutes les UAs',
    statusFilter: '',
    statusLabel: 'Statut : Tous',

    get terminesCount() {
        return this.allQcms.filter(q => q.etat === 'reussi' || q.etat === 'echoue').length;
    },
    get aFaireCount() {
        return this.allQcms.filter(q => q.etat === 'a_faire').length;
    },

    async init() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/etudiant/bibliotheque`);
            const data = await response.json();
            
            this.allQcms = data.qcms || [];
            this.unites = data.unites || [];
            this.moyenne = data.moyenne || 0;
            
            this.filterQcms();
        } catch (e) {
            console.error('Failed to load library data', e);
        } finally {
            setTimeout(() => { this.loading = false; }, 400);
        }
    },

    selectUa(id, name) {
        this.uaId = id;
        this.uaLabel = name;
        this.filterQcms();
    },

    selectStatus(value, label) {
        this.statusFilter = value;
        this.statusLabel = label;
        this.filterQcms();
    },

    filterQcms() {
        let qcms = this.allQcms;

        if (this.search.trim()) {
            const term = this.search.toLowerCase();
            qcms = qcms.filter(q => q.titre.toLowerCase().includes(term));
        }

        if (this.uaId) {
            qcms = qcms.filter(q => q.unite_id == this.uaId);
        }

        if (this.statusFilter) {
            qcms = qcms.filter(q => q.etat === this.statusFilter);
        }

        this.filteredQcms = qcms;
    }
});
