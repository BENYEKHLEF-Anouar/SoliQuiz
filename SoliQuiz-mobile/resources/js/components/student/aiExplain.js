export default (questionId, tentativeId) => ({
    questionId,
    tentativeId,
    loading: false,
    explanation: null,
    error: null,

    async explain() {
        if (this.loading || this.explanation) return;

        this.loading = true;
        this.error = null;

        try {
            const res = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/etudiant/ai/explain-question`, {
                method: 'POST',
                body: JSON.stringify({
                    question_id: this.questionId,
                    tentative_id: this.tentativeId,
                }),
            });

            const data = await res.json();

            if (!res.ok || !data.explanation) {
                this.error = data.error || "Le moteur IA n'a pas pu générer une explication.";
            } else {
                this.explanation = data.explanation;
            }
        } catch (e) {
            this.error = "Impossible de joindre le serveur. Vérifiez votre connexion.";
        } finally {
            this.loading = false;
        }
    }
});
