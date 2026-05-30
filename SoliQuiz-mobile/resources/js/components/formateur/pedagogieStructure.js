export default () => ({
    seances: [],
    filteredSeances: [],
    creators: [],
    loading: true,
    search: '',
    selectedCreatorId: '',
    creatorLabel: 'Tous les créateurs',

    isAdmin() {
        const profile = Alpine.store('config').profile;
        return profile && (profile.role === 'admin' || profile.role === 'Admin');
    },

    stats: {
        seances: 0,
        unites: 0,
        competences: 0
    },

    async init() {
        await this.fetchStructure();
    },

    async fetchStructure() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/pedagogie`);
            const data = await response.json();

            this.seances = data.seances || [];
            this.creators = data.creators || [];
            
            this.calculateStats();
            this.filterStructure();
        } catch (e) {
            console.error('Failed to load pedagogical structure', e);
        } finally {
            setTimeout(() => { this.loading = false; }, 400);
        }
    },

    calculateStats() {
        let uCount = 0;
        let cCount = 0;

        this.seances.forEach(s => {
            uCount += s.unites.length;
            s.unites.forEach(u => {
                cCount += u.competences.length;
            });
        });

        this.stats = {
            seances: this.seances.length,
            unites: uCount,
            competences: cCount
        };
    },

    selectCreator(id, name) {
        this.selectedCreatorId = id;
        this.creatorLabel = name;
        this.filterStructure();
    },

    clearFilters() {
        this.search = '';
        this.selectedCreatorId = '';
        this.creatorLabel = 'Tous les créateurs';
        this.filterStructure();
    },

    filterStructure() {
        let filtered = JSON.parse(JSON.stringify(this.seances)); // Deep clone to filter safely

        // Filter by creator if specified
        if (this.selectedCreatorId) {
            filtered = filtered.filter(s => s.user_id == this.selectedCreatorId);
        }

        // Filter by search query (UA name, UA code, Competence libelle, Session name)
        if (this.search.trim()) {
            const q = this.search.toLowerCase().trim();
            filtered = filtered.map(s => {
                // Keep session if name matches OR if any child matches
                const matchSession = s.nom.toLowerCase().includes(q);
                
                s.unites = s.unites.map(u => {
                    const matchUa = u.nom.toLowerCase().includes(q) || u.code.toLowerCase().includes(q);
                    
                    u.competences = u.competences.filter(c => {
                        return c.libelle.toLowerCase().includes(q) || c.code.toLowerCase().includes(q);
                    });

                    if (matchUa || u.competences.length > 0) {
                        return u;
                    }
                    return null;
                }).filter(Boolean);

                if (matchSession || s.unites.length > 0) {
                    return s;
                }
                return null;
            }).filter(Boolean);
        }

        this.filteredSeances = filtered;
    },

    formatDate(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        return `${parts[2]}/${parts[1]}`;
    }
});
