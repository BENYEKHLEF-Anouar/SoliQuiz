export default () => ({
    loading: false,
    isEditing: false,
    savingProfile: false,
    savingPassword: false,
    showCurrentPassword: false,
    showNewPassword: false,
    showConfirmPassword: false,
    toast: { message: '', type: 'success' },
    form: {
        nom: '',
        prenom: '',
        current_password: '',
        password: '',
        password_confirmation: ''
    },
    get profile() {
        return Alpine.store('config').profile || {};
    },
    async init() {
        // Use cached profile if available, otherwise fetch
        if (!Alpine.store('config').profile) {
            await this.fetchProfile();
        }
    },
    async fetchProfile() {
        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/profile`);
            const data = await response.json();
            Alpine.store('config').setProfile(data);
        } catch (e) {
            console.error('Failed to load profile', e);
        } finally {
            this.loading = false;
        }
    },
    initForm() {
        this.form.nom = this.profile.nom || '';
        this.form.prenom = this.profile.prenom || '';
        this.form.current_password = '';
        this.form.password = '';
        this.form.password_confirmation = '';
        this.showCurrentPassword = false;
        this.showNewPassword = false;
        this.showConfirmPassword = false;
    },
    showToast(message, type = 'success') {
        this.toast.message = message;
        this.toast.type = type;
        setTimeout(() => {
            if (this.toast.message === message) this.toast.message = '';
        }, 4000);
    },
    async saveProfile() {
        if (!this.form.prenom || !this.form.nom) {
            this.showToast('Veuillez remplir tous les champs obligatoires.', 'error');
            return;
        }
        this.savingProfile = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/profile`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    prenom: this.form.prenom,
                    nom: this.form.nom
                })
            });
            const res = await response.json();
            if (response.ok) {
                Alpine.store('config').setProfile(res.profile);
                this.showToast('Profil mis à jour avec succès.', 'success');
                this.isEditing = false;
            } else {
                const errorMsg = res.errors ? Object.values(res.errors)[0][0] : (res.message || 'Erreur lors de la mise à jour.');
                this.showToast(errorMsg, 'error');
            }
        } catch (e) {
            console.error('Failed to update profile', e);
            this.showToast('Une erreur est survenue.', 'error');
        } finally {
            this.savingProfile = false;
        }
    },
    async savePassword() {
        if (!this.form.current_password || !this.form.password || !this.form.password_confirmation) {
            this.showToast('Veuillez remplir tous les champs de mot de passe.', 'error');
            return;
        }
        if (this.form.password !== this.form.password_confirmation) {
            this.showToast('Le nouveau mot de passe et sa confirmation ne correspondent pas.', 'error');
            return;
        }
        this.savingPassword = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/profile/password`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_password: this.form.current_password,
                    password: this.form.password,
                    password_confirmation: this.form.password_confirmation
                })
            });
            const res = await response.json();
            if (response.ok) {
                this.showToast('Mot de passe mis à jour avec succès.', 'success');
                this.form.current_password = '';
                this.form.password = '';
                this.form.password_confirmation = '';
                this.isEditing = false;
            } else {
                const errorMsg = res.errors ? Object.values(res.errors)[0][0] : (res.message || 'Le mot de passe actuel est incorrect.');
                this.showToast(errorMsg, 'error');
            }
        } catch (e) {
            console.error('Failed to update password', e);
            this.showToast('Une erreur est survenue.', 'error');
        } finally {
            this.savingPassword = false;
        }
    },
    getInitials(prenom, nom) {
        const p = (prenom || '').charAt(0).toUpperCase();
        const n = (nom || '').charAt(0).toUpperCase();
        return p + n || 'FM';
    }
});
