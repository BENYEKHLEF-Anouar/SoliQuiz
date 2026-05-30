import { secureFetch } from './common';

export default function etudiantLibrary(config) {
    return {
        enCours: config.enCours,
        aFaire: config.aFaire,
        termines: config.termines,
        search: config.search,
        uaId: config.uaId,
        uaLabel: config.uaLabel,
        statut: config.statut,
        statutLabel: config.statutLabel,
        searchUrl: config.searchUrl,
        loading: false,

        async applyFilters() {
            this.loading = true;
            const url = new URL(this.searchUrl);
            if (this.search) url.searchParams.set('search', this.search);
            if (this.uaId) url.searchParams.set('ua_id', this.uaId);
            if (this.statut) url.searchParams.set('statut', this.statut);

            try {
                const response = await secureFetch(url.toString());
                const data = await response.json();
                this.enCours = data.enCours;
                this.aFaire = data.aFaire;
                this.termines = data.termines;
            } catch (error) {
                console.error('Erreur recherche etudiant:', error);
            } finally {
                this.loading = false;
            }
        }
    };
}
