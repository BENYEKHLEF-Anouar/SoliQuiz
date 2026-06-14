export default (initialSearch = '', initialCreatorFilter = '', initialCreatorName = '') => ({
    activeSeanceId: null,
    activeUaId: null,
    seanceMode: 'create',
    seanceId: null,
    seanceNom: '',
    seanceDateDebut: '',
    seanceDateFin: '',
    seanceFormateurId: '',
    uaMode: 'create',
    uaId: null,
    uaNom: '',
    uaCode: '',
    uaDateDebut: '',
    uaDateFin: '',
    uaFormateurId: '',
    compMode: 'create',
    compId: null,
    compNom: '',
    compCode: '',
    compDesc: '',
    search: initialSearch,
    creatorId: initialCreatorFilter,
    creatorName: initialCreatorName,
    loading: false,
    fetchContent() {
        this.loading = true;
        const url = new URL(window.location.href);
        if (this.search) url.searchParams.set('search', this.search); else url.searchParams.delete('search');
        if (this.creatorId) url.searchParams.set('creator', this.creatorId); else url.searchParams.delete('creator');

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('pedagogie-content');
            if (newContent) {
                document.getElementById('pedagogie-content').innerHTML = newContent.innerHTML;
            }
            history.pushState({}, '', url.toString());
            this.loading = false;
        })
        .catch(err => {
            console.error(err);
            this.loading = false;
        });
    },
    openSeanceCreate() {
        this.seanceMode = 'create'; this.seanceId = null; this.seanceNom = '';
        this.seanceDateDebut = ''; this.seanceDateFin = ''; this.seanceFormateurId = '';
        this.$dispatch('open-modal', 'seance-modal');
    },
    openSeanceEdit(id, nom, debut, fin, formateurId) {
        this.seanceMode = 'edit'; this.seanceId = id; this.seanceNom = nom;
        this.seanceDateDebut = debut; this.seanceDateFin = fin; this.seanceFormateurId = formateurId || '';
        this.$dispatch('open-modal', 'seance-modal');
    },
    openUaCreate(seanceId) {
        this.uaMode = 'create'; this.uaId = null; this.uaNom = ''; this.uaCode = ''; 
        this.uaDateDebut = ''; this.uaDateFin = ''; this.uaFormateurId = '';
        this.activeSeanceId = seanceId;
        this.$dispatch('open-modal', 'ua-modal');
    },
    openUaEdit(id, nom, code, debut, fin, formateurId) {
        this.uaMode = 'edit'; this.uaId = id; this.uaNom = nom; this.uaCode = code;
        this.uaDateDebut = debut; this.uaDateFin = fin; this.uaFormateurId = formateurId || '';
        this.$dispatch('open-modal', 'ua-modal');
    },
    openCompCreate(uaId) {
        this.compMode = 'create'; this.compId = null; this.compNom = ''; this.compCode = ''; this.compDesc = ''; this.activeUaId = uaId;
        this.$dispatch('open-modal', 'comp-modal');
    },
    openCompEdit(id, nom, code, desc) {
        this.compMode = 'edit'; this.compId = id; this.compNom = nom; this.compCode = code; this.compDesc = desc || '';
        this.$dispatch('open-modal', 'comp-modal');
    }
});
