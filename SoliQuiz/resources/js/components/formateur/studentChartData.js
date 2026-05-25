export default function studentChartData() {
    return {
        studentNom: '',
        studentAttempts: [],
        chartInstance: null,

        openChart(detail) {
            this.studentNom = detail.studentNom;
            
            // Get attempts from details
            const rawAttempts = detail.attempts || [];

            this.studentAttempts = rawAttempts.map(a => ({
                id: a.id,
                qcm_titre: a.qcm_titre || '',
                ua_nom: a.ua_nom || 'Indépendant',
                score: parseFloat(a.score),
                date: a.date
            })).sort((a, b) => a.id - b.id);

            // Open the modal
            this.$dispatch('open-modal', 'student-progress-modal');

            // Render/Update the chart after the DOM elements are updated and visible
            this.$nextTick(() => {
                const ctx = document.getElementById('studentProgressChart');
                if (!ctx) return;

                if (this.chartInstance) {
                    this.chartInstance.destroy();
                }

                const labels = this.studentAttempts.map(a => {
                    const title = a.qcm_titre;
                    return title.length > 15 ? title.substring(0, 15) + '...' : title;
                });
                const data = this.studentAttempts.map(a => a.score);

                const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Note obtenue (/20)',
                            data: data,
                            borderColor: '#6366f1',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
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
                                backgroundColor: '#0f172a',
                                titleColor: '#fff',
                                bodyColor: '#cbd5e1',
                                borderWidth: 1,
                                borderColor: '#334155',
                                padding: 10,
                                bodyFont: {
                                    family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                    size: 11
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                        size: 9,
                                        weight: 'bold'
                                    },
                                    color: '#64748b',
                                    maxRotation: 15,
                                    autoSkip: true,
                                    maxTicksLimit: 6
                                }
                            },
                            y: {
                                min: 0,
                                max: 20,
                                ticks: {
                                    stepSize: 2,
                                    font: {
                                        family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                        size: 10
                                    },
                                    color: '#64748b'
                                },
                                grid: {
                                    color: '#f1f5f9'
                                }
                            }
                        }
                    }
                });
            });
        }
    };
}
