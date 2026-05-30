import { secureFetch } from './common';

export default function qcmLibrary(config) {
    return {
        qcms: config.qcms,
        search: config.search,
        status: config.status,
        uaId: config.uaId,
        uaLabel: 'Toutes les unités',
        loading: false,
        searchUrl: config.searchUrl,
        
        get statusLabel() {
            if (!this.status) return 'Tous';
            const labels = { 'public': 'Publiés', 'brouillon': 'Brouillons', 'termine': 'Terminés' };
            return labels[this.status] || 'Tous';
        },
        
        get hasFilters() {
            return this.search || this.status;
        },
        
        get filteredQcms() {
            return this.qcms;
        },
        
        async applyFilters() {
            this.loading = true;
            const url = new URL(window.location);
            if (this.search) {
                url.searchParams.set('search', this.search);
            } else {
                url.searchParams.delete('search');
            }
            if (this.status) {
                url.searchParams.set('status', this.status);
            } else {
                url.searchParams.delete('status');
            }
            if (this.uaId) {
                url.searchParams.set('ua_id', this.uaId);
            } else {
                url.searchParams.delete('ua_id');
            }
            history.pushState({}, '', url);
            
            try {
                const response = await secureFetch(this.searchUrl + '?' + url.searchParams.toString());
                const data = await response.json();
                this.qcms = data.data;
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
            } finally {
                this.loading = false;
            }
        },
        
        clearFilters() {
            this.search = '';
            this.status = '';
            this.uaId = '';
            this.uaLabel = 'Toutes les unités';
            this.applyFilters();
        }
    };
}
