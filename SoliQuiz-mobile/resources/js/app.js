import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

// Authentication & Config Store
Alpine.store('config', {
    apiBaseUrl: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' 
        ? 'http://localhost:8000/api' 
        : `http://${window.location.hostname}:8000/api`,
    
    token: localStorage.getItem('soliquiz_token') || null,
    user: JSON.parse(localStorage.getItem('soliquiz_user')) || null,

    setAuth(token, user) {
        this.token = token;
        this.user = user;
        localStorage.setItem('soliquiz_token', token);
        localStorage.setItem('soliquiz_user', JSON.stringify(user));
    },

    logout() {
        this.token = null;
        this.user = null;
        localStorage.removeItem('soliquiz_token');
        localStorage.removeItem('soliquiz_user');
        window.location.href = '/login';
    },

    get isAuthenticated() {
        return !!this.token;
    },

    // Global fetch helper with auth header
    async authFetch(url, options = {}) {
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...options.headers,
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        const response = await fetch(url, { ...options, headers });
        
        if (response.status === 401) {
            this.logout();
            throw new Error('Unauthorized');
        }

        return response;
    }
});

Alpine.start();
