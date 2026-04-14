function classNotes() {
    return {
        cohorts: [],
        selectedCohortId: '',
        students: [],
        selectedStudent: null,
        loading: false,
        search: '',
        get filteredStudents() {
            if (!this.search) return this.students;
            const term = this.search.toLowerCase();
            return this.students.filter(s => s.name.toLowerCase().includes(term));
        },
        get classAverage() {
            if (this.students.length === 0) return 0;
            const sum = this.students.reduce((acc, s) => acc + s.averageScore, 0);
            return (sum / this.students.length).toFixed(1);
        },
        async init() {
            await this.fetchCohorts();
        },
        async fetchCohorts() {
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/cohorts`);
                this.cohorts = await response.json();
                if (this.cohorts.length > 0) {
                    this.selectedCohortId = this.cohorts[0].id;
                    await this.fetchStudents();
                }
            } catch (e) {
                console.error('Failed to load cohorts', e);
            } finally {
                this.loading = false;
            }
        },
        async fetchStudents() {
            if (!this.selectedCohortId) return;
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/formateur/cohorts/${this.selectedCohortId}/students`);
                this.students = await response.json();
            } catch (e) {
                console.error('Failed to load students', e);
            } finally {
                this.loading = false;
            }
        },
        async showStudentDetail(student) {
            this.selectedStudent = student;
            const el = document.getElementById('hs-offcanvas-student');
            if (el) {
                el.classList.remove('hidden');
                el.classList.add('translate-y-0');
            }
        }
    }
}

window.classNotes = classNotes;
