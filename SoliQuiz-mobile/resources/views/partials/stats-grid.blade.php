<!-- Stats Grid Partial for Student Dashboard -->
<section class="px-5 mt-6 grid grid-cols-2 gap-3">
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Score Global</span>
        <span class="text-2xl font-heading font-bold text-primary-600" x-text="scores.globalScore + '%'"></span>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm text-slate-400">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Classement</span>
        <span class="text-2xl font-heading font-bold text-slate-800" x-text="scores.ranking"></span>
        <span class="text-sm">/<span x-text="scores.totalStudents"></span></span>
    </div>
</section>