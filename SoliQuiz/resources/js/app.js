import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => Alpine.start());
} else {
    Alpine.start();
}
