function formateurProfile() {
    return {
        profile: {},
        loading: false,
        async init() {
            await this.fetchProfile();
        },
        async fetchProfile() {
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/profile`);
                this.profile = await response.json();
            } catch (e) {
                console.error('Failed to load profile', e);
            } finally {
                this.loading = false;
            }
        }
    }
}

window.formateurProfile = formateurProfile;
