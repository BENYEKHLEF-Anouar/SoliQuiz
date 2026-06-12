export default () => ({
    qcms: [],
    filteredQcms: [],
    classes: [],
    loading: true,

    search: '',
    qcmId: '',
    qcmLabel: 'Tous les QCMs',

    statsGlobale: {
        moyenne: 0,
        reussite: 0,
        passages: 0
    },

    selectedStudentNom: '',
    studentAttempts: [],
    chartInstance: null,
    globalChartInstance: null,
    showChartSheet: false,

    renderGlobalChart() {
        this.$nextTick(() => {
            const ctxGlobal = document.getElementById('performanceChart');
            if (!ctxGlobal || this.classes.length === 0) return;

            if (this.globalChartInstance) {
                this.globalChartInstance.destroy();
            }

            const classNames = this.classes.map(c => c.nom);
            const averages = this.classes.map(c => c.moyenne);
            const successRates = this.classes.map(c => c.taux_reussite);

            this.globalChartInstance = new Chart(ctxGlobal, {
                data: {
                    labels: classNames,
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Moyenne (/20)',
                            data: averages,
                            backgroundColor: 'rgba(79, 70, 229, 0.15)',
                            borderColor: '#4f46e5',
                            borderWidth: 2,
                            borderRadius: 8,
                            yAxisID: 'y'
                        },
                        {
                            type: 'line',
                            label: 'Taux de réussite (%)',
                            data: successRates,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            padding: 8,
                            bodyFont: { size: 9 },
                            titleFont: { size: 10, weight: 'bold' }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9, weight: 'bold' },
                                color: '#64748b'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            max: 20,
                            min: 0,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                stepSize: 5,
                                font: { size: 8 },
                                color: '#64748b'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            max: 100,
                            min: 0,
                            grid: { drawOnChartArea: false },
                            ticks: {
                                stepSize: 25,
                                font: { size: 8 },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        });
    },

    openStudentChart(studentNom) {
        this.selectedStudentNom = studentNom;
        
        const rawAttempts = [];
        this.qcms.forEach(qcm => {
            qcm.tentatives.forEach(t => {
                if (t.etudiant_nom === studentNom && t.score !== null) {
                    rawAttempts.push({
                        id: t.id,
                        qcm_titre: qcm.title,
                        score: parseFloat(t.score),
                        date: t.date,
                        status: t.statut
                    });
                }
            });
        });

        this.studentAttempts = rawAttempts.sort((a, b) => a.id - b.id);
        this.showChartSheet = true;

        this.$nextTick(() => {
            const ctx = document.getElementById('studentProgressChart');
            if (!ctx) return;

            if (this.chartInstance) {
                this.chartInstance.destroy();
            }

            const labels = this.studentAttempts.map(a => a.qcm_titre);
            const data = this.studentAttempts.map(a => a.score);

            this.chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Note obtenue (/20)',
                        data: data,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            padding: 10,
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 11 },
                            callbacks: {
                                label: function(context) {
                                    return ` Note : ${context.parsed.y}/20`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 20,
                            ticks: {
                                stepSize: 5,
                                font: { size: 9, weight: 'bold' },
                                color: '#94a3b8'
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            ticks: {
                                display: false
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    },

    async init() {
        this.$watch('loading', (isLoading) => {
            if (!isLoading) {
                this.renderGlobalChart();
            }
        });

        this.loading = true;
        try {
            const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/results`);
            const data = await response.json();

            this.qcms = data.qcms || [];
            this.classes = data.classes || [];
            
            this.calculateGlobalStats();
            this.filterResults();
        } catch (e) {
            console.error('Failed to fetch formateur results', e);
        } finally {
            setTimeout(() => { this.loading = false; }, 400);
        }
    },

    get studentsList() {
        const names = new Set();
        this.qcms.forEach(qcm => {
            qcm.tentatives.forEach(t => {
                if (t.etudiant_nom) {
                    names.add(t.etudiant_nom);
                }
            });
        });
        return [...names].sort();
    },

    calculateGlobalStats() {
        let allScores = [];
        let passages = 0;
        let reussis = 0;

        this.qcms.forEach(qcm => {
            qcm.tentatives.forEach(t => {
                if (t.score !== null) {
                    allScores.push(parseFloat(t.score));
                    passages++;
                    if (t.statut === 'reussi') {
                        reussis++;
                    }
                }
            });
        });

        const count = allScores.length;
        const sum = allScores.reduce((a, b) => a + b, 0);
        const avg = count > 0 ? (sum / count).toFixed(1) : '0';
        const rate = count > 0 ? Math.round((reussis / count) * 100) : 0;

        this.statsGlobale = {
            moyenne: avg,
            reussite: rate,
            passages: passages
        };
    },

    selectQcm(id, title) {
        this.qcmId = id;
        this.qcmLabel = title;
        this.filterResults();
    },

    clearFilters() {
        this.search = '';
        this.qcmId = '';
        this.qcmLabel = 'Tous les QCMs';
        this.filterResults();
    },

    filterResults() {
        let filtered = [...this.qcms];

        // Filter QCM list if a specific QCM is selected
        if (this.qcmId) {
            filtered = filtered.filter(q => q.id == this.qcmId);
        }

        this.filteredQcms = filtered;
    },

    getFilteredTentatives(qcm) {
        return qcm.tentatives.filter(t => {
            return !this.search.trim() || t.etudiant_nom.toLowerCase().includes(this.search.toLowerCase());
        });
    },

    getQcmStats(qcm) {
        const tentatives = this.getFilteredTentatives(qcm);
        const scores = tentatives.map(t => parseFloat(t.score)).filter(s => !isNaN(s) && s !== null);
        const count = scores.length;
        const sum = scores.reduce((a, b) => a + b, 0);
        const avg = count > 0 ? (sum / count).toFixed(1) : '0';
        const reussis = tentatives.filter(t => t.statut === 'reussi').length;
        const rate = count > 0 ? Math.round((reussis / count) * 100) : 0;
        return { avg, rate, count };
    }
});
