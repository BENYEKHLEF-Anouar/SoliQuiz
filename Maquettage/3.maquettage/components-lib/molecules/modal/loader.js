document.addEventListener('DOMContentLoaded', function () {
    const modalsContainer = document.createElement('div');
    modalsContainer.id = 'global-modals-container';
    document.body.appendChild(modalsContainer);

    // Chemin relatif depuis le dossier /mockups/
    const path = '../components-lib/molecules/modal/all-modals.html';

    fetch(path)
        .then(response => {
            if (!response.ok) throw new Error('Fetch failed');
            return response.text();
        })
        .then(html => {
            modalsContainer.innerHTML = html;

            // Laisser le temps au DOM de se stabiliser avant l'auto-init de Preline
            setTimeout(() => {
                if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
                    window.HSStaticMethods.autoInit();
                }
            }, 100);
        })
        .catch(err => {
            console.warn('Mode fallback: Impossible de charger les modals via fetch (possiblement dû au protocole file://)');
            console.error(err);
        });
});
