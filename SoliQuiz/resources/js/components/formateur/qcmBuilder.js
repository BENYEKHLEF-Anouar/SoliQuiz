import { secureFetch, dispatchToast, dispatchConfirm } from './common';

export default function qcmBuilder(initialUnites, initialClasses, initialQcm = null, oldData = {}) {
    if (initialQcm && !oldData && typeof initialQcm === 'object' && !initialQcm.hasOwnProperty('titre') && !initialQcm.hasOwnProperty('questions')) {
        oldData = initialQcm;
        initialQcm = null;
    }

    const hasOld = oldData && Object.keys(oldData).length > 0;
    
    let oldQuestions = null;
    if (hasOld && oldData.questions) {
        oldQuestions = (Array.isArray(oldData.questions) ? oldData.questions : Object.values(oldData.questions)).map(q => ({
            texte: q.texte || '',
            type: (q.type === 'unique' || q.type === 'choix_unique') ? 'choix_unique' : 'choix_multiple',
            points: q.points ? parseFloat(q.points) : 1,
            explication_feedback: q.explication_feedback || '',
            options: (q.options ? (Array.isArray(q.options) ? q.options : Object.values(q.options)) : []).map(o => ({
                texte: o.texte || '',
                est_correcte: o.est_correcte == '1' || o.est_correcte === true || o.est_correcte === 'true' || o.est_correcte === 'on',
                feedback_specifique: o.feedback_specifique || ''
            }))
        }));
    }

    let oldCompetences = [];
    if (hasOld) {
        let rawComps = oldData.competence_ids || [];
        oldCompetences = Array.isArray(rawComps) ? rawComps.map(id => parseInt(id)) : [];
    } else {
        oldCompetences = initialQcm?.competences?.map(c => c.id) || [];
    }

    return {
        titre: hasOld ? (oldData.titre || '') : (initialQcm?.titre || ''),
        statut: hasOld ? (oldData.statut || 'brouillon') : (initialQcm?.statut || 'brouillon'),
        showPointsWarning: false,
        hasTimer: hasOld ? (oldData.duree_minutes !== undefined ? (parseInt(oldData.duree_minutes) > 0) : true) : (initialQcm ? (initialQcm.duree_minutes > 0) : true),
        dureeMinutes: hasOld ? (oldData.duree_minutes !== undefined ? parseInt(oldData.duree_minutes) : 30) : ((initialQcm?.duree_minutes > 0) ? initialQcm.duree_minutes : 30),
        duree_minutes: hasOld ? (oldData.duree_minutes !== undefined ? parseInt(oldData.duree_minutes) : 30) : ((initialQcm?.duree_minutes > 0) ? initialQcm.duree_minutes : 30),
        scoreReussite: hasOld ? (oldData.score_reussite !== undefined ? parseFloat(oldData.score_reussite) : 10) : (initialQcm?.score_reussite || 10),
        allUnites: initialUnites,
        allClasses: initialClasses,
        selectedUniteId: hasOld ? (oldData.unite_apprentissage_id || '') : (initialQcm?.unite_apprentissage_id || ''),
        selectedClasseId: hasOld ? (oldData.classe_id || '') : (initialQcm?.classe_id || ''),
        selectedCompetences: oldCompetences,
        openAiModal: false,
        aiLoading: false,
        aiTopic: '',
        aiQuestionCount: 5,
        aiQuestionType: 'both',
        aiSuccessCount: 0,
        aiTopicError: '',
        
        init() {
            this.$watch('questions', (questions) => {
                questions.forEach((q, idx) => {
                    if (q.type === 'choix_unique') {
                        const hasCorrect = q.options.some(o => o.est_correcte);
                        if (!hasCorrect && q.options.length > 0) {
                            q.options[0].est_correcte = true;
                        }
                    }
                });
            });

            this.$watch('selectedUniteId', (value, oldValue) => {
                if (oldValue && value !== oldValue) {
                    this.selectedCompetences = [];
                }
            });

            window.onbeforeunload = (e) => {
                if (this.isDirty() && !this.isSubmitting) {
                    e.preventDefault();
                    return "Voulez-vous vraiment quitter ? Vos modifications ne seront pas enregistrées.";
                }
            };

            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;
                if (link.target === '_blank' || link.getAttribute('href').startsWith('#')) return;

                if (this.isDirty() && !this.isSubmitting) {
                    e.preventDefault();
                    dispatchConfirm({
                        title: initialQcm ? 'Quitter l\'édition ?' : 'Attention : Travail en cours',
                        message: initialQcm ? 'Vous avez des modifications non enregistrées. Voulez-vous vraiment abandonner vos changements ?' : 'Vous avez des modifications non enregistrées. Voulez-vous vraiment quitter cette page ?',
                        type: 'warning',
                        confirmText: initialQcm ? 'Abandonner les changements' : 'Quitter sans enregistrer',
                        cancelText: initialQcm ? 'Continuer l\'édition' : 'Rester ici',
                        onConfirm: () => {
                            window.onbeforeunload = null;
                            window.location.href = link.href;
                        }
                    });
                }
            });
        },
        
        get filteredCompetences() {
            if (!this.selectedUniteId) return [];
            const unite = this.allUnites.find(u => u.id == this.selectedUniteId);
            return unite ? unite.competences : [];
        },

        get totalPoints() {
            return this.questions.reduce((sum, q) => sum + (parseFloat(q.points) || 0), 0);
        },

        equalizePoints() {
            const n = this.questions.length;
            if (n === 0) return;
            const base = Math.floor(20 / n);
            const remainder = 20 - base * n;
            this.questions.forEach((q, i) => {
                q.points = base + (i === n - 1 ? remainder : 0);
            });
        },

        questions: oldQuestions && oldQuestions.length > 0 ? oldQuestions : (initialQcm?.questions?.map(q => ({
            texte: q.texte,
            type: (q.type === 'unique' || q.type === 'choix_unique') ? 'choix_unique' : 'choix_multiple',
            points: q.points,
            explication_feedback: q.explication_feedback || '',
            options: q.options?.map(o => ({
                texte: o.texte,
                est_correcte: o.est_correcte == '1' || o.est_correcte === true || o.est_correcte === 'true',
                feedback_specifique: o.feedback_specifique || ''
            })) || [
                { texte: '', est_correcte: false, feedback_specifique: '' },
                { texte: '', est_correcte: false, feedback_specifique: '' }
            ]
        })) || [
            {
                texte: '',
                type: 'choix_unique',
                points: 20,
                explication_feedback: '',
                options: [
                    { texte: '', est_correcte: true, feedback_specifique: '' },
                    { texte: '', est_correcte: false, feedback_specifique: '' }
                ]
            }
        ]),

        addQuestion() {
            this.questions.push({
                texte: '',
                type: 'choix_unique',
                points: 0,
                explication_feedback: '',
                options: [
                    { texte: '', est_correcte: false, feedback_specifique: '' },
                    { texte: '', est_correcte: false, feedback_specifique: '' }
                ]
            });
            this.equalizePoints();
        },

        removeQuestion(qIndex) {
            if (this.questions.length > 1) {
                this.questions.splice(qIndex, 1);
                this.equalizePoints();
            }
        },

        addOption(qIndex) {
            this.questions[qIndex].options.push({ texte: '', est_correcte: false, feedback_specifique: '' });
        },

        removeOption(qIndex, oIndex) {
            if (this.questions[qIndex].options.length > 2) {
                this.questions[qIndex].options.splice(oIndex, 1);
            }
        },

        setCorrectOption(qIndex, oIndex, isChecked) {
            const q = this.questions[qIndex];
            if (q.type === 'choix_unique') {
                q.options.forEach((opt, idx) => {
                    opt.est_correcte = (idx === oIndex);
                });
            } else {
                q.options[oIndex].est_correcte = isChecked;
            }
        },

        toggleCorrectOption(qIndex, oIndex) {
            const q = this.questions[qIndex];
            const current = q.options[oIndex].est_correcte;
            
            if (q.type === 'choix_unique') {
                q.options.forEach((opt, idx) => {
                    opt.est_correcte = (idx === oIndex);
                });
            } else {
                q.options[oIndex].est_correcte = !current;
            }
        },

        normalizeCorrectOptions(qIndex) {
            const q = this.questions[qIndex];
            if (!q) return;
            if (q.type !== 'choix_unique') return;

            const firstCorrectIndex = q.options.findIndex(o => !!o.est_correcte);
            const keepIndex = firstCorrectIndex !== -1 ? firstCorrectIndex : 0;

            q.options.forEach((o, idx) => {
                o.est_correcte = idx === keepIndex;
            });
        },

        isSubmitting: false,

        isDirty() {
            if (!initialQcm) {
                return this.titre.trim().length > 0 ||
                    (this.questions.length > 0 && this.questions[0].texte.trim().length > 0) ||
                    this.questions.length > 1;
            }

            const currentSnapshot = JSON.stringify({
                t: this.titre.trim(),
                c: this.questions.length,
                tx: this.questions.map(q => q.texte.trim()),
                p: this.questions.map(q => q.points)
            });

            const initialSnapshot = JSON.stringify({
                t: (initialQcm?.titre || '').trim(),
                c: (initialQcm?.questions?.length || 0),
                tx: (initialQcm?.questions?.map(q => q.texte.trim()) || []),
                p: (initialQcm?.questions?.map(q => q.points) || [])
            });

            return currentSnapshot !== initialSnapshot;
        },

        handleSubmit() {
            if (this.totalPoints !== 20) {
                this.showPointsWarning = true;
            } else {
                this.isSubmitting = true;
                window.onbeforeunload = null;
                this.$nextTick(() => {
                    document.getElementById('qcmForm').submit();
                });
            }
        },

        async generateQuestionsWithAI() {
            if (!this.aiTopic.trim()) {
                this.aiTopicError = 'Veuillez spécifier un thème ou une compétence.';
                return;
            }
            this.aiTopicError = '';
            this.aiLoading = true;
            
            try {
                const response = await secureFetch('/formateur/qcm/generate-ai', {
                    method: 'POST',
                    body: {
                        topic: this.aiTopic,
                        question_count: this.aiQuestionCount,
                        question_type: this.aiQuestionType
                    }
                });

                if (!response.ok) throw new Error();

                const data = await response.json();
                let rawQuestions = [];

                if (Array.isArray(data)) {
                    rawQuestions = data;
                    if (!this.titre || this.titre.trim() === '') {
                        this.titre = "ÉVALUATION : " + this.aiTopic.toUpperCase();
                    }
                } else if (data && typeof data === 'object') {
                    rawQuestions = data.questions || [];
                    if (data.title) {
                        this.titre = data.title.toUpperCase();
                    } else if (!this.titre || this.titre.trim() === '') {
                        this.titre = "ÉVALUATION : " + this.aiTopic.toUpperCase();
                    }
                }

                const mappedQuestions = rawQuestions.map(q => ({
                    texte: q.text || q.texte || '',
                    type: (q.type === 'single' || q.type === 'choix_unique') ? 'choix_unique' : 'choix_multiple',
                    points: q.points ? parseFloat(q.points) : 0,
                    explication_feedback: q.explanation || q.explication_feedback || '',
                    options: (q.options || []).map(o => ({
                        texte: o.text || o.texte || '',
                        est_correcte: !!(o.isCorrect || o.est_correcte),
                        feedback_specifique: o.feedback || o.feedback_specifique || ''
                    }))
                }));

                if (this.questions.length === 1 && this.questions[0].texte === '') {
                    this.questions = mappedQuestions;
                } else {
                    this.questions = [...this.questions, ...mappedQuestions];
                }
                this.equalizePoints();

                this.aiSuccessCount = mappedQuestions.length;
                this.$dispatch('close-modal', 'ai-generation');
                this.aiTopic = '';
                this.$dispatch('open-modal', 'ai-success');
            } catch (error) {
                this.$dispatch('close-modal', 'ai-generation');
                this.$dispatch('open-modal', 'ai-error');
            } finally {
                this.aiLoading = false;
            }
        }
    };
}
