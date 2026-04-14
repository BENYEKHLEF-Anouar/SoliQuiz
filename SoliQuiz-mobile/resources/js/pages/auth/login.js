function loginForm() {
    return {
        email: '',
        password: '',
        handleLogin() {
            const email = this.email.toLowerCase();
            if (email.includes('formateur')) {
                window.location.href = '/formateur/qcms';
            } else {
                window.location.href = '/student/dashboard';
            }
        }
    }
}

window.loginForm = loginForm;
