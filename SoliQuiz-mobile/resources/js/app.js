import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

Alpine.store('config', {
    apiBaseUrl: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' 
        ? 'http://localhost:8000/api' 
        : `http://${window.location.hostname}:8000/api`,
    studentUserId: 4,
    formateurUserId: 2
});

Alpine.start();
