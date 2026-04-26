import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

// Authentication & Config Store
Alpine.store('config', {
    apiBaseUrl: (() => {
        const hostname = window.location.hostname;
        // Android emulator special IP to access host localhost
        if (hostname === '10.0.2.2') return 'http://10.0.2.2:8000/api';
        // iOS simulator / local dev
        if (hostname === 'localhost' || hostname === '127.0.0.1') return 'http://localhost:8000/api';
        // Production / LAN testing
        return `http://${hostname}:8000/api`;
    })(),
    
    token: localStorage.getItem('soliquiz_token') || null,
    user: JSON.parse(localStorage.getItem('soliquiz_user')) || null,
    profile: JSON.parse(localStorage.getItem('soliquiz_profile')) || null,
    studentProfile: JSON.parse(localStorage.getItem('soliquiz_student_profile')) || null,

    setAuth(token, user) {
        this.token = token;
        this.user = user;
        localStorage.setItem('soliquiz_token', token);
        localStorage.setItem('soliquiz_user', JSON.stringify(user));
    },

    setProfile(profile) {
        this.profile = profile;
        localStorage.setItem('soliquiz_profile', JSON.stringify(profile));
    },

    setStudentProfile(profile) {
        this.studentProfile = profile;
        localStorage.setItem('soliquiz_student_profile', JSON.stringify(profile));
    },

    getInitials() {
        if (!this.profile) return 'FM';
        const p = (this.profile.prenom || '').charAt(0).toUpperCase();
        const n = (this.profile.nom || '').charAt(0).toUpperCase();
        return p + n || 'FM';
    },

    getFullName() {
        if (!this.profile) return 'Formateur';
        return `${this.profile.prenom || ''} ${this.profile.nom || ''}`.trim() || 'Formateur';
    },

    getEmail() {
        return this.profile?.email || this.user?.email || '';
    },

    getStudentInitials() {
        if (!this.studentProfile) return 'ST';
        const p = (this.studentProfile.prenom || '').charAt(0).toUpperCase();
        const n = (this.studentProfile.nom || '').charAt(0).toUpperCase();
        return p + n || 'ST';
    },

    getStudentFullName() {
        if (!this.studentProfile) return 'Apprenant';
        return `${this.studentProfile.prenom || ''} ${this.studentProfile.nom || ''}`.trim() || 'Apprenant';
    },

    getStudentEmail() {
        return this.studentProfile?.email || this.user?.email || '';
    },

    logout() {
        this.token = null;
        this.user = null;
        this.profile = null;
        this.studentProfile = null;
        localStorage.removeItem('soliquiz_token');
        localStorage.removeItem('soliquiz_user');
        localStorage.removeItem('soliquiz_profile');
        localStorage.removeItem('soliquiz_student_profile');
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
