<!-- KPI Card (Atom/Molecule) -->
@props(['title', 'value', 'icon', 'color' => 'primary'])

@php
    $colorClasses = match($color) {
        'primary' => 'bg-primary-50 text-primary-500',
        'emerald' => 'bg-emerald-50 text-emerald-500',
        'indigo' => 'bg-indigo-50 text-indigo-500',
        'amber' => 'bg-amber-50 text-amber-500',
        default => 'bg-slate-50 text-slate-500'
    };
@endphp

<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:border-transparent transition-all group">
    <div class="flex items-center justify-between mb-6">
        <div class="size-12 {{ $colorClasses }} rounded-2xl flex items-center justify-center transition-all group-hover:scale-110">
            <x-lucide-icon :name="$icon" size="6" />
        </div>
        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="size-1.5 rounded-full bg-slate-200"></span>
            <span class="size-1.5 rounded-full bg-slate-200"></span>
            <span class="size-1.5 rounded-full bg-slate-200"></span>
        </div>
    </div>
    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]  mb-2">{{ $title }}</p>
    <p class="text-4xl font-black text-slate-900 tracking-tight">{{ $value }}</p>
</div>
