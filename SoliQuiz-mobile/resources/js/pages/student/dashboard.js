function dashboard() {
    return {
        profile: {},
        scores: {},
        evaluations: [],
        notifications: [],
        loading: false,
        error: null,
        get urgentCount() {
            return this.evaluations.filter(e => e.urgent).length;
        },
        async init() {
            await this.fetchProfile();
            await Promise.all([
                this.fetchScores(),
                this.fetchEvaluations(),
                this.fetchNotifications()
            ]);
        },
        async fetchProfile() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/profile`);
                this.profile = await response.json();
            } catch (e) {
                console.error('Failed to load profile', e);
            }
        },
        async fetchScores() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/scores`);
                this.scores = await response.json();
            } catch (e) {
                console.error('Failed to load scores', e);
            }
        },
        async fetchEvaluations() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/evaluations`);
                this.evaluations = await response.json();
            } catch (e) {
                console.error('Failed to load evaluations', e);
            }
        },
        async fetchNotifications() {
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/notifications`);
                this.notifications = await response.json();
            } catch (e) {
                console.error('Failed to load notifications', e);
            }
        }
    }
}
