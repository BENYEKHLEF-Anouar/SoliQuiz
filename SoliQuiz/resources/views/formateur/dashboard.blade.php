@extends('layouts.app')

@section('title', 'Espace Formateur - Supervision')

@section('content')
<div class="space-y-12 reveal active">
    <!-- Premium Header -->
    <div class="relative overflow-hidden bg-slate-900 rounded-[40px] p-10 lg:p-16 shadow-2xl">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-primary-500/20 to-transparent"></div>
        <div class="absolute -bottom-24 -right-24 size-96 bg-primary-500/10 rounded-full blur-3xl animate-pulse"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
            <div class="max-w-2xl">
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-primary-400/80 mb-6">
                    <span>Espace Formateur</span>
                    <span class="size-1 rounded-full bg-slate-700"></span>
                    <span class="text-white">Supervision Active</span>
                </nav>
                <h1 class="text-4xl lg:text-6xl font-heading font-black text-white tracking-tight leading-none mb-6">
                    Pilotez vos <span class="text-primary-400">Évaluations</span>
                </h1>
                <p class="text-slate-400 text-lg font-medium leading-relaxed">
                    Accédez à vos cohortes en un clic, créez de nouveaux tests interactifs et suivez la montée en compétences de vos apprenants en temps réel.
                </p>
            </div>
            
            <a href="{{ route('formateur.qcm.create') }}"
               class="group relative flex items-center gap-4 bg-primary-500 hover:bg-primary-400 p-6 px-8 rounded-3xl text-white shadow-2xl shadow-primary-500/20 transition-all hover:-translate-y-1 active:scale-95 overflow-hidden">
                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                <div class="size-12 bg-white/20 rounded-2xl flex items-center justify-center font-black text-2xl group-hover:rotate-90 transition-transform duration-500">+</div>
                <div class="relative">
                    <span class="text-[10px] font-black tracking-[0.2em] uppercase block opacity-70">Action Rapide</span>
                    <span class="text-xl font-black tracking-tight">Créer un QCM</span>
                </div>
            </a>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-premium transition-all">
            <div class="size-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors duration-500">
                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Classes</span>
                <span class="text-3xl font-black text-slate-900 leading-none font-heading">{{ $metrics['nb_classes'] }}</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-premium transition-all">
            <div class="size-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors duration-500">
                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Étudiants</span>
                <span class="text-3xl font-black text-slate-900 leading-none font-heading">{{ $metrics['nb_etudiants'] }}</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-premium transition-all">
            <div class="size-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors duration-500">
                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Total QCM</span>
                <span class="text-3xl font-black text-slate-900 leading-none font-heading">{{ $metrics['nb_qcms'] }}</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-premium transition-all">
            <div class="size-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-500">
                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest block mb-0.5">En Ligne</span>
                <span class="text-3xl font-black text-emerald-700 leading-none font-heading">{{ $metrics['nb_qcms_publies'] }}</span>
            </div>
        </div>
    </div>

    <!-- Cohorts Section -->
    <section>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-3">
                Vos Cohortes
                <span class="bg-primary-50 text-primary-600 text-xs px-3 py-1 rounded-full">{{ count($classes) }} active(s)</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($classes as $classe)
                <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm hover:shadow-premium transition-all group flex flex-col relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 size-40 bg-slate-50 rounded-full group-hover:bg-primary-50 transition-colors duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-10">
                            <span class="px-4 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-full group-hover:bg-white transition-colors">
                                {{ $classe->promotion ?? 'Promotion 2026' }}
                            </span>
                            <div class="size-12 bg-white rounded-xl shadow-sm border border-slate-50 flex items-center justify-center text-primary-500 group-hover:scale-110 transition-transform">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        
                        <h3 class="text-3xl font-black font-heading text-slate-900 tracking-tight leading-tight mb-4 group-hover:text-primary-600 transition-colors">{{ $classe->nom }}</h3>
                        
                        <div class="flex items-center gap-2 mb-10">
                            <div class="flex -space-x-2">
                                <div class="size-7 rounded-full border-2 border-white bg-slate-100"></div>
                                <div class="size-7 rounded-full border-2 border-white bg-slate-200"></div>
                                <div class="size-7 rounded-full border-2 border-white bg-slate-300"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-400"><strong class="text-slate-900 font-black">{{ $classe->etudiants_count }}</strong> Apprenants</span>
                        </div>

                        <a href="{{ route('formateur.resultats') }}" 
                           class="inline-flex items-center gap-3 text-sm font-black uppercase tracking-widest text-primary-500 hover:text-primary-600 transition-colors group/link">
                            Consulter les scores
                            <svg class="size-4 transition-transform group-hover/link:translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white border border-dashed border-slate-200 rounded-[40px]">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Aucune classe assignée</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection