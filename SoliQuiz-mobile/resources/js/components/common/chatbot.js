export default function chatbot() {
    return {
        open: false,
        message: '',
        history: [],
        loading: false,
        sessionId: null,
        suggestions: [
            "Comment fonctionne SoliQuiz ?",
            "Quels sont les rôles disponibles ?",
            "Est-ce synchronisé avec SoliLMS ?"
        ],

        init() {
            this.sessionId = sessionStorage.getItem('solibot_session') || crypto.randomUUID();
            sessionStorage.setItem('solibot_session', this.sessionId);

            this.history.push({
                role: 'model',
                content: "Bonjour, je suis **SoliBot**, l'assistant IA de SoliQuiz. Comment puis-je vous aider ?"
            });
        },

        async sendMessage(text = null) {
            const inputMsg = text ? text.trim() : this.message.trim();
            if (!inputMsg || this.loading) return;

            if (!text) {
                this.message = '';
            }

            this.history.push({ role: 'user', content: inputMsg });
            this.loading = true;
            this.scrollToBottom();

            try {
                const apiBaseUrl = Alpine.store('config').apiBaseUrl;
                const response = await fetch(`${apiBaseUrl}/chatbot/chat`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: inputMsg,
                        history: this.history.slice(0, -1),
                        session_id: this.sessionId
                    })
                });

                const data = await response.json();

                if (response.status === 429) {
                    this.history.push({
                        role: 'model',
                        content: data.error || "Le quota de l'assistant IA est atteint. Veuillez reessayer demain."
                    });
                } else if (response.ok && data.reply) {
                    this.history.push({ role: 'model', content: data.reply });
                } else {
                    this.history.push({
                        role: 'model',
                        content: data.error || "Une erreur est survenue. Veuillez reessayer."
                    });
                }
            } catch (e) {
                this.history.push({
                    role: 'model',
                    content: "Impossible de joindre le serveur. Verifiez votre connexion et reessayez."
                });
            } finally {
                this.loading = false;
                this.scrollToBottom();
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer || document.getElementById('chat-messages-container');
                if (container) {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            });
        }
    };
}
