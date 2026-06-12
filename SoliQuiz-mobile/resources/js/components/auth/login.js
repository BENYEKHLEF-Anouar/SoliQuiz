export default () => ({
    email: '',
    password: '',
    showPassword: false,
    error: null,
    loading: false,
    showResetModal: false,
    resetEmail: '',
    resetSuccess: null,
    resetError: null,
    resetLoading: false,

    async handleLogin() {
        this.loading = true;
        this.error = null;
        try {
            const response = await fetch(`${Alpine.store('config').apiBaseUrl}/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: this.email,
                    password: this.password,
                    device_name: 'MobileApp'
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Échec de la connexion');
            }

            // Store authentication data
            Alpine.store('config').setAuth(data.token, data.user);

            // Redirect based on role
            if (data.user.type_profil === 'formateur') {
                window.location.href = '/formateur/qcms';
            } else if (data.user.type_profil === 'admin') {
                window.location.href = '/admin/dashboard';
            } else {
                window.location.href = '/student/dashboard';
            }
        } catch (e) {
            this.error = e.message;
        } finally {
            this.loading = false;
        }
    },

    async handleResetPassword() {
        this.resetLoading = true;
        this.resetError = null;
        this.resetSuccess = null;
        try {
            const response = await fetch(`${Alpine.store('config').apiBaseUrl}/password/reset-request`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: this.resetEmail })
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erreur lors de la demande');
            this.resetSuccess = data.message;
            this.resetEmail = '';
        } catch (e) {
            this.resetError = e.message;
        } finally {
            this.resetLoading = false;
        }
    }
});
