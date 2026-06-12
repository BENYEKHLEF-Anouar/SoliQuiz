@extends('components.layout.app')

@section('content')
<div x-data="profile" x-init="init()" class="h-full flex flex-col relative overflow-hidden bg-slate-50">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-slate-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <button @click="isEditing ? isEditing = false : window.location.href = '{{ route('student.dashboard') }}'" 
                        class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 active:scale-95 transition-transform outline-none border-none text-white">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </button>
                <h1 class="text-xl font-heading font-extrabold text-slate-900 tracking-tight" x-text="isEditing ? 'Réglages' : 'Mon Profil'"></h1>
            </div>
            
            <button @click="isEditing = !isEditing; if (isEditing) initForm();" 
                    :class="isEditing ? 'bg-primary-50 text-primary-600 border-primary-100' : 'bg-slate-50 text-slate-500 border-slate-100'"
                    class="h-9 px-3 rounded-xl border flex items-center gap-1.5 active:scale-95 transition-transform outline-none text-[9px] font-black uppercase tracking-wider">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-text="isEditing ? 'Profil' : 'Réglages'"></span>
            </button>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto px-5 py-6 hide-scrollbar pb-24 relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Chargement du profil..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-6">

            <!-- Success/Error Feedback Banner -->
            <div x-show="toast.message" 
                 x-transition
                 :class="toast.type === 'success' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'"
                 class="p-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-lg">
                <span x-text="toast.message"></span>
                <button @click="toast.message = ''" class="text-white hover:opacity-80 ml-2">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Profile View State -->
            <div x-show="!isEditing" class="space-y-6">
                <!-- Profile Card -->
                <div class="flex flex-col items-center mb-8">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-primary-500 rounded-full blur-2xl opacity-10"></div>
                        <!-- Initials Avatar -->
                        <div class="relative inline-flex items-center justify-center size-28 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white font-bold text-3xl tracking-tight"
                             x-text="getInitials(profile.prenom, profile.nom)">
                            ST
                        </div>
                    </div>
                    <h2 class="mt-5 text-2xl font-heading font-extrabold text-slate-900 tracking-tight leading-none" x-text="(profile.prenom || '') + ' ' + (profile.nom || '')"></h2>
                    <p class="text-[10px] font-black text-primary-600 mt-2.5 uppercase tracking-[0.2em]">
                        <span x-text="profile.role || 'Apprenant'"></span> <span class="mx-1 text-slate-200">/</span> <span x-text="profile.cohort || 'Cohorte'"></span>
                    </p>
                </div>

                <!-- Info List Card -->
                <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)]">
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-4">
                            <div class="size-11 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Nom complet</p>
                                <p class="text-sm font-extrabold text-slate-900" x-text="(profile.prenom || '') + ' ' + (profile.nom || '')"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="size-11 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Adresse Email</p>
                                <p class="text-sm font-extrabold text-slate-900" x-text="profile.email || '...'"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="size-11 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Mot de passe</p>
                                <p class="text-sm font-extrabold text-slate-900 tracking-widest">••••••••</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="size-11 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-1">Code Étudiant</p>
                                <p class="text-sm font-extrabold text-slate-900" x-text="profile.code_etudiant || 'N/A'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit and Logout Buttons -->
                <div class="space-y-3">
                    <button @click="isEditing = true; initForm();"
                            class="w-full h-14 inline-flex justify-center items-center gap-x-2 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl bg-slate-900 text-white hover:bg-primary-600 transition-all active:scale-[0.98]">
                        Modifier le profil
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>

                    <button @click="$store.config.logout()"
                            class="w-full h-14 inline-flex justify-center items-center gap-x-2 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-200 transition-all active:scale-[0.98] shadow-sm shadow-rose-100/50">
                        Déconnexion
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Profile Edit / Settings State -->
            <div x-show="isEditing" class="space-y-6">
                <!-- Info Settings Section -->
                <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)] space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Informations Personnelles</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Prénom</label>
                            <input type="text" x-model="form.prenom" 
                                   class="w-full h-12 bg-slate-50 rounded-2xl px-4 text-xs font-bold text-slate-900 border border-transparent focus:border-primary-500 focus:bg-white outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Nom</label>
                            <input type="text" x-model="form.nom" 
                                   class="w-full h-12 bg-slate-50 rounded-2xl px-4 text-xs font-bold text-slate-900 border border-transparent focus:border-primary-500 focus:bg-white outline-none transition-all">
                        </div>
                    </div>

                    <button @click="saveProfile()" :disabled="savingProfile"
                            class="w-full h-12 inline-flex justify-center items-center gap-x-2 text-[10px] font-black uppercase tracking-[0.2em] rounded-xl bg-slate-900 text-white hover:bg-primary-600 transition-all active:scale-[0.98] disabled:opacity-50">
                        <template x-if="savingProfile">
                            <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="savingProfile ? 'Enregistrement...' : 'Sauvegarder les infos'"></span>
                    </button>
                </div>

                <!-- Password Settings Section -->
                <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 shadow-[0_8px_30px_rgba(0,0,0,0.02)] space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Modifier le mot de passe</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Mot de passe actuel</label>
                            <div class="relative">
                                <input :type="showCurrentPassword ? 'text' : 'password'" x-model="form.current_password" 
                                       class="w-full h-12 bg-slate-50 rounded-2xl pl-4 pr-12 text-xs font-bold text-slate-900 border border-transparent focus:border-primary-500 focus:bg-white outline-none transition-all">
                                <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 outline-none">
                                    <template x-if="showCurrentPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </template>
                                    <template x-if="!showCurrentPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                            <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                            <line x1="2" y1="2" x2="22" y2="22"/>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Nouveau mot de passe</label>
                            <div class="relative">
                                <input :type="showNewPassword ? 'text' : 'password'" x-model="form.password" 
                                       class="w-full h-12 bg-slate-50 rounded-2xl pl-4 pr-12 text-xs font-bold text-slate-900 border border-transparent focus:border-primary-500 focus:bg-white outline-none transition-all">
                                <button type="button" @click="showNewPassword = !showNewPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 outline-none">
                                    <template x-if="showNewPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </template>
                                    <template x-if="!showNewPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                            <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                            <line x1="2" y1="2" x2="22" y2="22"/>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Confirmer le nouveau mot de passe</label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'" x-model="form.password_confirmation" 
                                       class="w-full h-12 bg-slate-50 rounded-2xl pl-4 pr-12 text-xs font-bold text-slate-900 border border-transparent focus:border-primary-500 focus:bg-white outline-none transition-all">
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 outline-none">
                                    <template x-if="showConfirmPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </template>
                                    <template x-if="!showConfirmPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                            <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                            <line x1="2" y1="2" x2="22" y2="22"/>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button @click="savePassword()" :disabled="savingPassword"
                            class="w-full h-12 inline-flex justify-center items-center gap-x-2 text-[10px] font-black uppercase tracking-[0.2em] rounded-xl bg-slate-900 text-white hover:bg-primary-600 transition-all active:scale-[0.98] disabled:opacity-50">
                        <template x-if="savingPassword">
                            <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="savingPassword ? 'Modification...' : 'Modifier le mot de passe'"></span>
                    </button>
                </div>
            </div>

            <p class="text-center text-[9px] font-black text-slate-200 uppercase tracking-[0.4em] pt-6 leading-loose">
                SoliQuiz Mobile v1.0<br/> <span class="text-slate-100">© 2026 Solicode</span>
            </p>

        </div>
    </main>

    <!-- Navigation -->
    @include('components.nav.student-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>


@endsection