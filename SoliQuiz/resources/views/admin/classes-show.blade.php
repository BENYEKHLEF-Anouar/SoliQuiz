@extends('layouts.app')

@section('title', 'Détails Cohorte - ' . $classe->nom)

@section('content')
<div class="fade-in space-y-12">
    <!-- Header Strategy Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex-1">
            <nav class="flex items-center gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">
                <a href="{{ route('admin.classes') }}" class="hover:text-primary-500 transition-colors flex items-center gap-1">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M15 19l-7-7 7-7"/></svg>
                    Répertoire Cohortes
                </a>
                <span class="size-1 rounded-full bg-slate-200"></span>
                <span class="text-slate-900">Intelligence Structurelle</span>
            </nav>
            <h1 class="text-3xl lg:text-4xl font-heading font-black text-slate-900 tracking-tight leading-none italic uppercase">
                {{ $classe->nom }} <span class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">{{ $classe->promotion }}</span>
            </h1>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="bg-white border border-slate-100 px-6 py-3 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Expert Référent</p>
                    <p class="text-sm font-black text-slate-900 italic leading-none">
                        {{ $classe->formateur ? $classe->formateur->nom_complet : 'Non assigné' }}
                    </p>
                </div>
                <div class="size-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg shadow-slate-900/10">
                    @if($classe->formateur)
                        <img class="size-full rounded-xl border-2 border-white/10" src="https://ui-avatars.com/api/?name={{ urlencode(optional($classe->formateur)->nom_complet ?? 'Unassigned') }}&background=0f172a&color=fff&bold=true" alt="{{ optional($classe->formateur)->nom_complet ?? 'Unassigned' }}">
                    @else
                        <svg class="size-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Student List Section -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/20">
                    <div>
                        <h2 class="text-xl font-heading font-black text-slate-900 uppercase italic tracking-tight">Agents Apprenants</h2>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Ressources humaines rattachées</p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-900 rounded-xl shadow-md shadow-slate-900/10">
                        <span class="text-white text-[10px] font-black uppercase tracking-widest italic">{{ $classe->etudiants->count() }} Membres</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Identité</th>
                                <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Email</th>
                                <th class="px-6 py-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Contrôle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($classe->etudiants as $etudiant)
                            <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <img class="size-10 rounded-xl shadow-sm border border-white group-hover:rotate-6 transition-transform duration-500" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($etudiant->nom_complet ?? 'Student') }}&background=f1f5f9&color=64748b&bold=true" alt="">
                                            <div class="absolute -top-0.5 -right-0.5 size-3 bg-emerald-500 rounded-full border-2 border-white shadow-sm animate-pulse"></div>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-900 italic tracking-tight group-hover:text-primary-600 transition-colors">{{ $etudiant->nom_complet ?? 'Unknown' }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Actif au repertoire</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-slate-500 font-mono italic">{{ $etudiant->email }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.classes.students.remove', [$classe->id, $etudiant->id]) }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="size-8 inline-flex items-center justify-center bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all shadow-sm" title="Révoquer l'affectation">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-20 text-center bg-slate-50/10">
                                    <div class="size-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100">
                                        <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    </div>
                                    <p class="text-slate-400 font-black italic uppercase text-[10px] tracking-widest">Effectif Vierge</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions Section -->
        <div class="space-y-6">
            <!-- Add Member Card -->
            <div class="bg-slate-900 rounded-[2rem] p-8 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 size-48 bg-primary-500/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-primary-500/20 transition-colors duration-700"></div>
                
                <h3 class="text-xl font-heading font-black uppercase italic tracking-tight mb-6 relative z-10 flex items-center gap-2">
                    Inclusion <span class="text-primary-400">Agent</span>
                </h3>
                
                @if($etudiantsSansClasse->count() > 0)
                <form action="{{ route('admin.classes.students.add', $classe->id) }}" method="POST" class="space-y-6 relative z-10">
                    @csrf
                    <div>
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 italic leading-none">Candidats sans Affectation</label>
                        <div class="relative">
                            <select name="user_id" required class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary-500 transition-all font-bold appearance-none cursor-pointer uppercase">
                                <option value="" class="text-slate-900">Sélectionner un profil...</option>
                                @foreach($etudiantsSansClasse as $student)
                                    <option value="{{ $student->id }}" class="text-slate-900">{{ $student->nom_complet }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="size-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-white text-slate-900 rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-primary-500 hover:text-white transition-colors shadow-md flex items-center justify-center gap-2 italic">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
                        Intégrer à la Cohorte
                    </button>
                </form>
                @else
                <div class="bg-white/5 rounded-2xl p-6 border border-dashed border-white/10 text-center relative z-10">
                    <svg class="size-8 text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-relaxed italic">Inventaire Clos : Tous affectés.</p>
                </div>
                @endif
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 italic">Météo de Cohorte</h4>
                <div class="space-y-6">
                    <div class="flex justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Taux d'engagement</span>
                            <span class="text-xl font-black text-slate-900 italic leading-none">84.2%</span>
                        </div>
                        <div class="size-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-500">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center pt-6 border-t border-slate-50">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Activité Seances</span>
                        <span class="text-xs font-black text-slate-900 italic">Haut Niveau</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mise en Service</span>
                        <span class="text-xs font-black text-slate-900 italic">{{ $classe->created_at?->format('M Y') ?? 'Cycle ' . date('Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
