export default function leaderboardFilter() {
    return {
        loading: false,
        openClasse: false,
        openQcm: false,
        searchClasse: '',
        searchQcm: '',
        
        updateFilters(params, targetUrl = null) {
            this.loading = true;
            let url;
            if (targetUrl) {
                url = new URL(targetUrl);
            } else {
                url = new URL(window.location.href);
            }
            if (params) {
                Object.keys(params).forEach(key => {
                    if (params[key] !== null && params[key] !== undefined) {
                        url.searchParams.set(key, params[key]);
                    } else {
                        url.searchParams.delete(key);
                    }
                });
            }
            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('leaderboard-content');
                if (newContent) {
                    document.getElementById('leaderboard-content').innerHTML = newContent.innerHTML;
                    history.pushState({}, '', url.toString());
                }
                this.loading = false;
            })
            .catch(() => { this.loading = false; });
        }
    };
}
