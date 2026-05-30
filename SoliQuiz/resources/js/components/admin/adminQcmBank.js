import { toggleQcmStatusApi, deleteQcmWithConfirm } from './common';

export default function adminQcmBank(config) {
    return {
        qcms: config.initialQcms,
        search: config.initialSearch,
        statut: config.initialStatut,
        formateurId: config.initialFormateurId,
        formateurs: config.formateurs,
        loading: false,
        totalCount: config.totalCount,
        searchUrl: config.searchUrl,
        viewMode: localStorage.getItem('qcms_view_mode') || 'cards',

        setViewMode(mode) {
            this.viewMode = mode;
            localStorage.setItem('qcms_view_mode', mode);
        },

        get statutLabel() {
            if (!this.statut) return 'Tous les statuts';
            return this.statut.charAt(0).toUpperCase() + this.statut.slice(1);
        },

        get formateurLabel() {
            if (!this.formateurId) return 'Tous les formateurs';
            const f = this.formateurs.find(x => x.id == this.formateurId);
            return f ? f.nom_complet : 'Tous les formateurs';
        },

        async applyFilters() {
            this.loading = true;
            const url = new URL(this.searchUrl);
            if (this.search) url.searchParams.set('search', this.search);
            if (this.statut) url.searchParams.set('statut', this.statut);
            if (this.formateurId) url.searchParams.set('formateur_id', this.formateurId);

            try {
                const response = await fetch(url);
                const data = await response.json();
                this.qcms = data.data;
                this.totalCount = data.total;

                const browserUrl = new URL(window.location);
                if (this.search) browserUrl.searchParams.set('search', this.search); else browserUrl.searchParams.delete('search');
                if (this.statut) browserUrl.searchParams.set('statut', this.statut); else browserUrl.searchParams.delete('statut');
                if (this.formateurId) browserUrl.searchParams.set('formateur_id', this.formateurId); else browserUrl.searchParams.delete('formateur_id');
                history.pushState({}, '', browserUrl);
            } catch (error) {
                console.error('Erreur de recherche:', error);
            } finally {
                this.loading = false;
            }
        },

        async toggleStatus(qcm) {
            await toggleQcmStatusApi(qcm);
        },

        deleteQcm(id) {
            deleteQcmWithConfirm(id, () => {
                this.qcms = this.qcms.filter(q => q.id !== id);
                this.totalCount--;
            });
        }
    };
}
