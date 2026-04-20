@extends('components.layout.app')

@section('content')
    <div class="bg-gray-50 h-full min-h-screen pb-16">
        <main class="max-w-7xl mx-auto px-4 py-12">

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-4 py-12">
                <!-- Dashboard Header -->
                <div
                    class="mb-12 relative overflow-hidden bg-white border border-slate-200 p-10 rounded-[2.5rem] shadow-sm">
                    <div class="absolute -top-10 -right-10 size-64 bg-slate-50 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <div>
                            <h1
                                class="text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase italic mb-2">
                                Mon Espace Formateur</h1>
                            <p class="text-slate-500 font-medium text-lg">Gérez vos QCMs et suivez vos cohortes.</p>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('formateur.qcm.create') }}"
                                class="bg-primary-500 hover:bg-primary-600 p-4 px-6 rounded-2xl text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105 active:scale-95">
                                <span class="text-2xl font-black">+</span>
                                <span class="text-[10px] font-black tracking-widest uppercase">Nouveau QCM</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KPI Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Classes
                            Gérées</h3>
                        <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_classes'] }}</div>
                    </div>
                    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Étudiants
                            Suivis</h3>
                        <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_etudiants'] }}</div>
                    </div>
                    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">QCM Créés
                        </h3>
                        <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_qcms'] }}</div>
                    </div>
                    <div class="bg-white border border-emerald-200 shadow-sm rounded-2xl p-6">
                        <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2 italic">QCM
                            Publiés</h3>
                        <div class="text-3xl font-black font-heading text-emerald-700">{{ $metrics['nb_qcms_publies'] }}
                        </div>
                    </div>
                </div>

                <!-- Classes -->
                <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight italic mb-6">Vos Cohortes
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($classes as $classe)
                        <div
                            class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="inline-block py-1 px-3 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-full">{{ $classe->promotion ?? 'Générale' }}</span>
                            </div>
                            <h3 class="text-2xl font-bold font-heading text-slate-900 tracking-tight leading-tight mb-4">
                                {{ $classe->nom }}</h3>
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-50">
                                <span class="text-sm font-medium text-slate-500">
                                    <strong class="text-slate-900">{{ $classe->etudiants_count }}</strong> Étudiants
                                </span>
                                <a href="{{ route('formateur.resultats') }}"
                                    class="text-primary-600 hover:text-primary-700 font-bold text-sm">Voir scores &rarr;</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center bg-white border border-slate-200 rounded-3xl">
                            <p class="text-slate-500 font-medium font-heading">Vous n'avez pas encore de classe assignée.</p>
                        </div>
                    @endforelse
                </div>
            </main>
    </div>
@endsection