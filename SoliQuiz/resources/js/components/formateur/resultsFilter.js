export default function resultsFilter(config) {
    return {
        search: '',
        selectedQcmId: '',
        selectedQcmTitle: '',
        selectedClasse: '',
        loading: true,
        qcmList: config.qcmList || [],
        classeList: config.classeList || [],
        studentList: config.studentList || [],
        allTentatives: config.allGlobalTentatives || [],
        activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'qcm',
        exportUrl: config.exportUrl,
        
        gradebookChartInstance: null,
        
        init() {
            const params = new URLSearchParams(window.location.search);
            this.search = params.get('q') || '';
            this.selectedClasse = params.get('classe') || config.selectedClasse || '';
            const qid = params.get('qcm');
            if (qid) {
                const found = this.qcmList.find(q => q.id == qid);
                if (found) {
                    this.selectedQcmId = found.id;
                    this.selectedQcmTitle = found.titre;
                }
            }

            this.$watch('activeTab', value => {
                const url = new URL(window.location);
                url.searchParams.set('tab', value);
                history.replaceState({}, '', url);

                if (value === 'gradebook') {
                    this.$nextTick(() => this.renderGradebookChart());
                }
            });

            this.$watch('search', () => {
                if (this.activeTab === 'gradebook') {
                    this.renderGradebookChart();
                }
            });

            this.$watch('selectedQcmId', () => {
                if (this.activeTab === 'gradebook') {
                    this.renderGradebookChart();
                }
            });

            this.$nextTick(() => {
                setTimeout(() => {
                    this.loading = false;
                }, 200);
            });
        },
        
        switchClasse(classId) {
            this.loading = true;
            setTimeout(() => {
                window.location.href = `/admin/resultats?classe_id=${classId}&tab=${this.activeTab}`;
            }, 300);
        },
        
        get hasFilters() {
            return this.search || this.selectedQcmId;
        },

        get studentsStats() {
            const stats = {};
            this.studentList.forEach(student => {
                stats[student.name] = {
                    id: student.id,
                    name: student.name,
                    classe: 'Hors cohorte',
                    attempts: [],
                    avg: 0,
                    completed: 0,
                    passed: 0
                };
            });

            this.allTentatives.forEach(t => {
                if (stats[t.etudiant_nom]) {
                    stats[t.etudiant_nom].attempts.push(t);
                    stats[t.etudiant_nom].classe = t.etudiant_classe;
                    if (t.etudiant_id) {
                        stats[t.etudiant_nom].id = t.etudiant_id;
                    }
                    if (t.statut === 'reussi') {
                        stats[t.etudiant_nom].passed++;
                    }
                }
            });

            Object.keys(stats).forEach(name => {
                const s = stats[name];
                const scores = s.attempts.map(t => parseFloat(t.score)).filter(score => !isNaN(score));
                s.completed = scores.length;
                if (scores.length > 0) {
                    const sum = scores.reduce((a, b) => a + b, 0);
                    s.avg = (sum / scores.length).toFixed(1);
                } else {
                    s.avg = '-';
                }
            });

            return Object.values(stats).filter(s => {
                return !this.search || s.name.toLowerCase().includes(this.search.toLowerCase());
            }).sort((a, b) => {
                if (a.avg === '-') return 1;
                if (b.avg === '-') return -1;
                return b.avg - a.avg;
            });
        },
        
        applyFilters() {
            this.loading = true;
            const url = new URL(window.location);
            if (this.search) url.searchParams.set('q', this.search);
            else url.searchParams.delete('q');
            if (this.selectedQcmId) url.searchParams.set('qcm', this.selectedQcmId);
            else url.searchParams.delete('qcm');
            history.pushState({}, '', url);
            
            setTimeout(() => {
                this.loading = false;
            }, 300);
        },
        
        clearFilters() {
            this.search = '';
            this.selectedQcmId = '';
            this.selectedQcmTitle = '';
            const url = new URL(window.location);
            url.searchParams.delete('q');
            url.searchParams.delete('qcm');
            history.pushState({}, '', url);
        },

        exportData(format) {
            const baseUrl = this.exportUrl;
            const params = new URLSearchParams({
                format: format,
                q: this.search,
                qcm: this.selectedQcmId,
                classe: this.selectedClasse
            });
            window.location.href = `${baseUrl}?${params.toString()}`;
        },

        getAvatarBg(name) {
            const colors = [
                'bg-indigo-50 text-indigo-600',
                'bg-emerald-50 text-emerald-600',
                'bg-rose-50 text-rose-600',
                'bg-amber-50 text-amber-600',
                'bg-sky-50 text-sky-600',
                'bg-purple-50 text-purple-600'
            ];
            const charCode = name ? name.charCodeAt(0) : 0;
            return colors[charCode % colors.length];
        },

        get gradebookMatrix() {
            const qcms = this.qcmList;
            const rows = this.studentsStats.map(s => {
                const qcmScores = {};
                qcms.forEach(q => {
                    const t = s.attempts.find(attempt => attempt.qcm_titre === q.titre);
                    qcmScores[q.id] = t ? parseFloat(t.score) : null;
                });
                return {
                    name: s.name,
                    classe: s.classe,
                    scores: qcmScores,
                    avg: s.avg
                };
            });

            return {
                columns: qcms,
                rows: rows
            };
        },

        renderGradebookChart() {
            const ctx = document.getElementById('gradebookProgressChart');
            if (!ctx) return;

            if (this.gradebookChartInstance) {
                this.gradebookChartInstance.destroy();
            }

            const searchLower = this.search.trim().toLowerCase();
            const filteredStudents = this.studentsStats;
            const isSingleStudent = filteredStudents.length === 1 && searchLower;

            const labels = this.qcmList.map(q => q.titre);
            let datasets = [];

            if (isSingleStudent) {
                const student = filteredStudents[0];
                const scores = this.qcmList.map(q => {
                    const t = student.attempts.find(attempt => attempt.qcm_titre === q.titre);
                    return t ? parseFloat(t.score) : null;
                });

                datasets = [
                    {
                        label: `Progression de ${student.name}`,
                        data: scores,
                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y'
                    }
                ];
            } else {
                const averages = [];
                const successRates = [];

                this.qcmList.forEach(q => {
                    const attempts = this.allTentatives.filter(t => {
                        const matchesQcm = t.qcm_titre === q.titre;
                        if (!searchLower) return matchesQcm;
                        return matchesQcm && t.etudiant_nom.toLowerCase().includes(searchLower);
                    });
                    const scores = attempts.map(t => parseFloat(t.score)).filter(s => !isNaN(s));
                    
                    if (scores.length > 0) {
                        const sum = scores.reduce((a, b) => a + b, 0);
                        averages.push((sum / scores.length).toFixed(1));

                        const reussis = attempts.filter(t => t.statut === 'reussi').length;
                        const successRate = Math.round((reussis / scores.length) * 100);
                        successRates.push(successRate);
                    } else {
                        averages.push(null);
                        successRates.push(null);
                    }
                });

                const datasetLabelSuffix = searchLower ? ' (filtré)' : '';

                datasets = [
                    {
                        label: 'Moyenne générale' + datasetLabelSuffix,
                        data: averages,
                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Taux de réussite %' + datasetLabelSuffix,
                        data: successRates,
                        backgroundColor: 'rgba(52, 211, 153, 0.85)',
                        borderColor: '#34d399',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y1'
                    }
                ];
            }

            this.gradebookChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 10,
                                    weight: 'bold'
                                },
                                color: '#64748b'
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            min: 0,
                            max: 20,
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 10
                                },
                                color: '#64748b'
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            min: 0,
                            max: 100,
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 10
                                },
                                color: '#64748b'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 10,
                                    weight: 'bold'
                                },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        }
    };
}
