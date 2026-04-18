<!-- Evaluation Card Component -->
<a :href="'/student/qcm/' + $evaluation.id" class="block bg-white border border-slate-200 shadow-sm rounded-2xl p-5 relative overflow-hidden active:scale-[0.98] transition-all">
    <div class="absolute top-0 right-0 w-1.5 h-full" :class="$evaluation.urgent ? 'bg-semantic-warning' : 'bg-transparent'"></div>
    <div class="flex justify-between items-start mb-2">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="$evaluation.subject"></span>
        <span class="text-[10px] font-bold uppercase tracking-widest" :class="$evaluation.urgent ? 'text-semantic-warning' : 'text-slate-400'" x-text="$evaluation.urgent ? 'Aujourd\\'hui' : 'À venir'"></span>
    </div>
    <h3 class="text-lg font-bold text-slate-900 leading-snug font-heading pr-4" x-text="$evaluation.title"></h3>
    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-primary-600 font-bold text-sm">
        Démarrer le test
        <svg class="ml-1 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m9 18 6-6-6-6" />
        </svg>
    </div>
</a>