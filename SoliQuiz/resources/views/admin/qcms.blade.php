@extends('layouts.app')

@section('title', 'Banque de QCM - SoliQuiz')

@section('content')
<div class="reveal active space-y-12 pb-20" x-data="{ search: '{{ $search }}' }">
    <!-- Corporate Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Ressources Pédagogiques</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Banque de QCM</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Global <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">Library</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Supervisez l'intégralité du catalogue d'évaluations. Gérez les statuts de publication et assurez la qualité du contenu pédagogique.
            </p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="glass bg-white/50 px-8 py-5 rounded-[24px] border border-slate-100 flex flex-col items-end">
                <span class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase mb-1">Total Entités</span>
                <span class="text-3xl font-black text-slate-900 font-heading leading-none">{{ $qcms->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="space-y-4">
        @if(session('success'))
            <div class="glass border-emerald-100 bg-emerald-50/50 p-6 rounded-[24px] flex items-center gap-4 animate-in slide-in-from-top duration-500">
                <div class="size-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-0.5">Notification Système</p>
                    <p class="text-sm font-bold text-slate-900">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-4 items-center">
        <form action="{{ route('admin.qcms') }}" method="GET" class="flex-1 w-full relative group">
            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary-500 transition-colors">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Rechercher une évaluation, un module ou un auteur..."
                   class="w-full h-16 pl-16 pr-8 bg-white border border-slate-100 rounded-[28px] font-bold text-slate-900 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all shadow-sm group-hover:shadow-premium outline-none">
        </form>
    </div>

    <!-- QCM List -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($qcms as $qcm)
            <div class="group bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm hover:shadow-premium hover:-translate-y-1.5 transition-all duration-500 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 size-48 bg-slate-50/50 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10 relative z-10">
                    <!-- QCM Info -->
                    <div class="flex items-start gap-8">
                        <div class="shrink-0 size-20 rounded-[28px] flex items-center justify-center transition-all duration-500 {{ $qcm->statut === 'public' ? 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/20' : ($qcm->statut === 'termine' ? 'bg-slate-100 text-slate-400' : 'bg-amber-50 text-amber-500 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-amber-500/20') }}">
                            <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-2xl font-heading font-black text-slate-900 group-hover:text-primary-600 transition-colors">{{ $qcm->titre }}</h3>
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $qcm->statut === 'public' ? 'bg-emerald-100 text-emerald-600' : ($qcm->statut === 'termine' ? 'bg-slate-200 text-slate-600' : 'bg-amber-100 text-amber-600') }}">
                                    {{ $qcm->statut }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="size-2 rounded-full bg-primary-500"></div>
                                    <span class="text-xs font-black text-slate-700">{{ $qcm->uniteApprentissage->nom ?? 'Module Libre' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-xs font-bold">{{ $qcm->duree_minutes }} min</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-xs font-bold">{{ $qcm->questions_count }} Points de contrôle</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Author & Metrics -->
                    <div class="flex items-center gap-10">
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Expert Responsable</span>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm font-black text-slate-900 leading-none">{{ $qcm->formateur->nom_complet }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Formateur @solicode</p>
                                </div>
                                <img class="size-10 rounded-xl bg-slate-50 border border-slate-100" src="https://ui-avatars.com/api/?name={{ urlencode($qcm->formateur->nom_complet) }}&background=f8fafc&color=64748b&bold=true" alt="">
                            </div>
                        </div>

                        <div class="h-12 w-px bg-slate-100 hidden lg:block"></div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('formateur.qcm.edit', $qcm->id) }}"
                               class="flex items-center gap-3 px-8 h-14 bg-slate-900 text-white rounded-[20px] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-primary-500 hover:shadow-xl hover:shadow-primary-500/20 transition-all active:scale-95">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                Editer
                            </a>

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="size-14 rounded-[20px] border-2 border-slate-50 flex items-center justify-center text-slate-300 hover:text-slate-900 hover:border-slate-900 transition-all">
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute right-0 top-full mt-3 w-64 bg-white rounded-[32px] border border-slate-50 shadow-premium p-3 z-[100]"
                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                                    
                                    <form action="{{ route('formateur.qcm.toggle', $qcm->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full flex items-center justify-between px-5 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest {{ $qcm->statut === 'public' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors">
                                            <span>{{ $qcm->statut === 'public' ? 'Dépublier' : 'Publier' }}</span>
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                    </form>

                                    @if($qcm->statut === 'public')
                                    <form action="{{ route('formateur.qcm.close', $qcm->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full flex items-center justify-between px-5 py-4 rounded-2xl text-slate-500 hover:bg-slate-50 transition-colors text-[10px] font-black uppercase tracking-widest">
                                            <span>Fermer</span>
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    </form>
                                    @endif

                                    <div class="h-px bg-slate-50 my-2"></div>

                                    <form action="{{ route('formateur.qcm.destroy', $qcm->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce QCM ? Cette action est irréversible.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full flex items-center justify-between px-5 py-4 rounded-2xl text-rose-500 hover:bg-rose-50 transition-colors text-[10px] font-black uppercase tracking-widest">
                                            <span>Supprimer</span>
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass p-20 text-center rounded-[50px] border-2 border-dashed border-slate-100">
                <div class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 border border-slate-100 shadow-inner">
                    <svg class="size-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-heading font-black text-slate-900 mb-2">Structure Vide</h3>
                <p class="text-slate-400 font-medium italic">Aucun QCM détecté dans l'inventaire système.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-12">
            {{ $qcms->links() }}
        </div>
    </div>
</div>
@endsection
