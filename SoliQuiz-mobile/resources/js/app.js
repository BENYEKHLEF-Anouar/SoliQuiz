import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

Alpine.store('config', {
    apiBaseUrl: (import.meta.env.VITE_API_URL || 'http://localhost:8000') + '/api',
    studentUserId: 4,
    formateurUserId: 2
});

Alpine.start();
