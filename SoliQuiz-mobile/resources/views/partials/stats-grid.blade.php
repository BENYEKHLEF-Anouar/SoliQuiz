<!-- Stats Grid Partial for Student Dashboard -->
<section class="px-5 mt-6 grid grid-cols-2 gap-4">
    <div class="bg-white border border-slate-100 rounded-[2rem] p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] group transition-all hover:border-primary-100">
        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">Score Global</span>
        <div class="flex items-baseline gap-1">
            <span class="text-3xl font-heading font-extrabold text-primary-500 leading-none" x-text="scores.globalScore || 0"></span>
            <span class="text-[10px] font-bold text-primary-200 uppercase italic">%</span>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-[2rem] p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] group transition-all hover:border-primary-100">
        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">Classement</span>
        <div class="flex items-baseline gap-0.5">
            <span class="text-3xl font-heading font-extrabold text-slate-900 leading-none" x-text="scores.ranking || 0"></span>
            <span class="text-[10px] font-bold text-slate-300 uppercase italic">/<span x-text="scores.totalStudents || 0"></span></span>
        </div>
    </div>
</section>