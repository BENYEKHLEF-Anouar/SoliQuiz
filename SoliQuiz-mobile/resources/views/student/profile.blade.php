@extends('layouts.app')

@section('content')
<div x-data="profile()" x-init="init()">
    <!-- Header -->
    <header class="bg-white px-5 pt-safe-top pb-4 border-b border-slate-200 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex items-center justify-between mt-2">
            <a href="{{ route('student.dashboard') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1 text-center">
                <span class="text-sm font-bold text-slate-800">Profil</span>
            </div>
            <div class="w-6"></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 px-5 py-6">
        <!-- Avatar -->
        <div class="flex flex-col items-center mb-8">
            <div class="relative">
                <img class="size-24 rounded-full border-4 border-white shadow-lg"
                    src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80"
                    alt="Avatar">
                <button class="absolute bottom-0 right-0 size-8 bg-primary-500 rounded-full text-white flex items-center justify-center shadow-lg">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>
            </div>
            <h2 class="mt-4 text-xl font-heading font-bold text-slate-900" x-text="profile.nom + ' ' + profile.prenom"></h2>
            <span class="text-sm font-medium text-primary-500" x-text="profile.role"></span>
            <span class="text-xs text-slate-400 mt-1" x-text="profile.cohort"></span>
        </div>

        <!-- Info list -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</span>
                <p class="text-sm font-medium text-slate-800" x-text="profile.email"></p>
            </div>
            <div class="px-5 py-4 border-b border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mot de passe</span>
                <p class="text-sm font-medium text-slate-800">••••••••</p>
            </div>
        </div>

        <!-- Logout button -->
        <button class="w-full mt-8 py-3 bg-danger-500 text-white font-bold rounded-xl shadow-lg shadow-danger-500/20">
            Déconnexion
        </button>
    </main>
</div>

<script>
function profile() {
    return {
        profile: {},
        loading: false,
        async init() {
            await this.fetchProfile();
        },
        async fetchProfile() {
            this.loading = true;
            try {
                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/profile`);
                this.profile = await response.json();
            } catch (e) {
                console.error('Failed to load profile', e);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection