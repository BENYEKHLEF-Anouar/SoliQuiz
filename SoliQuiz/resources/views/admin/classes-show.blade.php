@extends('layouts.app')

@section('content')
<div class="shell-outer min-h-screen">
    <div class="shell-inner p-8 md:p-12">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
            <div class="flex-1">
                <nav class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 italic">
                    <a href="{{ route('admin.classes') }}" class="hover:text-primary-500 transition-colors">Cohortes</a>
                    <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-900">Détails Classe</span>
                </nav>
                <h1 class="text-5xl font-heading font-black text-slate-900 tracking-tight italic uppercase leading-none">
                    {{ $classe->nom }} <span class="text-primary-500 text-3xl not-italic">— {{ $classe->promotion }}</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="bg-white border border-slate-200/60 px-8 py-5 rounded-[2rem] shadow-xl shadow-slate-200/40">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] block mb-2">Responsable Pédagogique</span>
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-xs uppercase">
                            {{ $classe->formateur ? substr($classe->formateur->prenom, 0, 1) . substr($classe->formateur->nom, 0, 1) : '??' }}
                        </div>
                        <span class="text-sm font-black text-slate-900 italic">
                            @if($classe->formateur)
                                {{ $classe->formateur->prenom }} {{ $classe->formateur->nom }}
                            @else
                                <span class="text-slate-300">Non assigné</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-10 p-6 bg-accent-teal/10 border border-accent-teal/20 rounded-[2rem] text-accent-teal font-black text-sm uppercase tracking-widest flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="size-10 rounded-2xl bg-accent-teal text-white flex items-center justify-center shadow-lg shadow-accent-teal/30">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="m5 13 4 4L19 7"/></svg>
                </div>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Student List -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[3rem] border border-slate-200/60 overflow-hidden shadow-2xl shadow-slate-200/50">
                    <div class="p-10 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                        <div>
                            <h2 class="text-2xl font-heading font-black text-slate-900 uppercase italic tracking-tight">Liste des Apprenants</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Membres rattachés à cette cohorte</p>
                        </div>
                        <span class="bg-primary-500 text-white text-[11px] font-black px-4 py-2 rounded-2xl uppercase tracking-widest shadow-lg shadow-primary-500/20">
                            {{ $classe->etudiants->count() }} Élèves
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic border-b border-slate-100">Profil</th>
                                    <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic border-b border-slate-100">Contact</th>
                                    <th class="px-10 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic border-b border-slate-100 italic">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($classe->etudiants as $etudiant)
                                <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-5">
                                            <div class="size-14 rounded-2xl bg-neutral-900 text-white flex items-center justify-center font-black text-sm group-hover:scale-105 transition-transform shadow-xl shadow-neutral-900/10">
                                                {{ substr($etudiant->prenom, 0, 1) }}{{ substr($etudiant->nom, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-base font-black text-slate-900 italic tracking-tight">{{ $etudiant->prenom }} {{ $etudiant->nom }}</span>
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mt-0.5">Apprenant Actif</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <span class="text-sm font-bold text-slate-500">{{ $etudiant->email }}</span>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <form action="{{ route('admin.classes.students.remove', [$classe->id, $etudiant->id]) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="group/del size-10 inline-flex items-center justify-center bg-slate-50 text-slate-400 hover:bg-accent-pink/10 hover:text-accent-pink rounded-xl transition-all active:scale-90" title="Retirer de la classe">
                                                <svg class="size-5 group-hover/del:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-10 py-32 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="size-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6">
                                                <svg class="size-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                            </div>
                                            <p class="text-slate-400 font-black italic uppercase text-[11px] tracking-[0.2em]">Aucun apprenant assigné</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Member Column -->
            <div class="space-y-8">
                <div class="bg-neutral-900 rounded-[3rem] p-10 text-white shadow-2xl shadow-neutral-900/40 relative overflow-hidden">
                    <div class="absolute top-0 right-0 size-48 bg-primary-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    
                    <h3 class="text-2xl font-heading font-black uppercase italic tracking-tight mb-8 relative z-10">Ajouter un Membre</h3>
                    
                    @if($etudiantsSansClasse->count() > 0)
                    <form action="{{ route('admin.classes.students.add', $classe->id) }}" method="POST" class="space-y-6 relative z-10">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 italic">Membres disponibles</label>
                            <select name="user_id" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-sm outline-none focus:border-primary-500 transition-all font-bold appearance-none">
                                <option value="" class="text-slate-900">-- Sélectionner un élève --</option>
                                @foreach($etudiantsSansClasse as $student)
                                    <option value="{{ $student->id }}" class="text-slate-900">{{ $student->prenom }} {{ $student->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full py-5 bg-primary-500 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[11px] hover:bg-primary-600 transition-all shadow-xl shadow-primary-500/30 active:scale-95 flex items-center justify-center gap-3">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
                            Ajouter à la Classe
                        </button>
                    </form>
                    @else
                    <div class="bg-white/5 rounded-[2rem] p-8 border border-dashed border-white/10 text-center relative z-10">
                        <svg class="size-10 text-slate-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest leading-relaxed">Tous les apprenants actifs sont déjà affectés.</p>
                    </div>
                    @endif

                    <div class="mt-12 pt-10 border-t border-white/5 relative z-10">
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] block mb-4 italic">Note informative</span>
                        <p class="text-[10px] leading-relaxed text-slate-400 font-bold italic tracking-tight">
                            Cette liste n'affiche que les utilisateurs avec le profil <span class="text-primary-400">Apprenant</span> qui n'ont actuellement aucune affectation pédagogique.
                        </p>
                    </div>
                </div>

                <!-- Stats Sidebar Mock -->
                <div class="bg-accent-yellow/5 rounded-[3rem] p-10 border border-accent-yellow/20">
                    <h4 class="text-lg font-heading font-black text-slate-900 uppercase italic tracking-tight mb-6">Résumé Cohorte</h4>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Capacité</span>
                            <span class="text-sm font-black text-slate-900 italic">Illimitée</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Taux d'activité</span>
                            <span class="text-sm font-black text-accent-teal italic">84%</span>
                        </div>
                        <div class="flex justify-between items-center pt-6 border-t border-accent-yellow/10">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rentrée</span>
                            <span class="text-sm font-black text-slate-900 italic">{{ $classe->created_at?->format('M Y') ?? 'Lancement ' . date('Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
