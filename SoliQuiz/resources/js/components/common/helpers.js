export function dispatchToast(message, type = 'success') {
    window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
}

export function dispatchConfirm({ title, message, type = 'warning', confirmText, cancelText, onConfirm }) {
    window.dispatchEvent(new CustomEvent('confirm', {
        detail: { title, message, type, confirmText, cancelText, onConfirm }
    }));
}

export function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

export async function secureFetch(url, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        ...(options.headers || {})
    };
    if (options.body && typeof options.body === 'object' && !(options.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(options.body);
    }
    return fetch(url, { ...options, headers });
}
