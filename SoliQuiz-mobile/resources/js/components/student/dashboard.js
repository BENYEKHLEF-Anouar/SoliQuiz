export default () => ({
    profile: {},
    scores: {},
    evaluations: [],
    notifications: [],
    loading: true,
    error: null,
    get urgentCount() {
        return this.evaluations.filter(e => e.urgent).length;
    },
    async init() {
        this.loading = true;
        try {
            await Promise.all([
                this.fetchProfile(),
                this.fetchScores(),
                this.fetchEvaluations(),
                this.fetchNotifications()
            ]);
        } catch (e) {
            console.error('Initialisation failed', e);
        } finally {
            setTimeout(() => { this.loading = false; }, 800);
        }
    },
    async fetchProfile() {
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/etudiant/profile`);
            this.profile = await response.json();
        } catch (e) {
            console.error('Failed to load profile', e);
        }
    },
    async fetchScores() {
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/etudiant/scores`);
            this.scores = await response.json();
        } catch (e) {
            console.error('Failed to load scores', e);
        }
    },
    async fetchEvaluations() {
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/etudiant/evaluations`);
            this.evaluations = await response.json();
        } catch (e) {
            console.error('Failed to load evaluations', e);
        }
    },
    async fetchNotifications() {
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/etudiant/notifications`);
            this.notifications = await response.json();
        } catch (e) {
            console.error('Failed to load notifications', e);
        }
    }
});
