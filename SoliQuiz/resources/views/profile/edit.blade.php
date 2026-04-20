@extends('components.layout.app')

@section('content')
    <div class="bg-slate-50 min-h-screen pb-16">
        <main class="max-w-4xl mx-auto px-4 py-12">

            <main class="max-w-4xl mx-auto px-4 py-12">
                <div class="mb-8">
                    <h1
                        class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase italic mb-2">
                        Mon Profil</h1>
                    <p class="text-slate-500 mt-1 font-medium text-lg">Gérez vos informations personnelles et paramètres de
                        sécurité.</p>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 p-4 rounded-xl mb-6 font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 md:p-8 mb-6">
                    <h2 class="text-xl font-heading font-bold text-slate-900 mb-6">Informations personnelles</h2>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Prénom</label>
                                <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}"
                                    class="w-full rounded-xl border-slate-300 shadow-sm px-4 py-3 bg-slate-50 focus:border-primary-500 focus:ring-primary-500">
                                @error('prenom') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nom</label>
                                <input type="text" name="nom" value="{{ old('nom', $user->nom) }}"
                                    class="w-full rounded-xl border-slate-300 shadow-sm px-4 py-3 bg-slate-50 focus:border-primary-500 focus:ring-primary-500">
                                @error('nom') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Adresse Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full rounded-xl border-slate-200 shadow-sm px-4 py-3 bg-slate-100 text-slate-500 cursor-not-allowed">
                            <p class="text-xs text-slate-400 mt-2">L'adresse email ne peut pas être modifiée.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="py-3 px-8 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 transition-all shadow-lg shadow-primary-500/25 active:scale-95 text-sm uppercase tracking-widest">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 md:p-8">
                    <h2 class="text-xl font-heading font-bold text-slate-900 mb-6">Sécurité</h2>
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Mot de passe actuel</label>
                            <input type="password" name="current_password"
                                class="w-full rounded-xl border-slate-300 shadow-sm px-4 py-3 bg-slate-50 focus:border-primary-500 focus:ring-primary-500">
                            @error('current_password') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nouveau mot de passe</label>
                                <input type="password" name="password"
                                    class="w-full rounded-xl border-slate-300 shadow-sm px-4 py-3 bg-slate-50 focus:border-primary-500 focus:ring-primary-500">
                                @error('password') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full rounded-xl border-slate-300 shadow-sm px-4 py-3 bg-slate-50 focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="py-3 px-8 border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm uppercase tracking-widest shadow-sm">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </main>
    </div>
@endsection