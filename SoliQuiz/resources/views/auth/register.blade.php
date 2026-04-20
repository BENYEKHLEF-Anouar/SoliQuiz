<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SoliQuiz') }} - Inscription</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap"
        rel="stylesheet">
</head>

<body class="bg-slate-50 flex items-center h-full font-sans antialiased">

    <main class="w-full max-w-md mx-auto p-6">
        <div class="mt-7 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-7">
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-6">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 group outline-none">
                            <div
                                class="size-12 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                                <svg class="text-white size-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <path d="m9 15 2 2 4-4" />
                                </svg>
                            </div>
                            <span class="text-3xl font-heading font-bold text-slate-900 tracking-tight">Soli<span
                                    class="text-primary-500">Quiz</span></span>
                        </a>
                    </div>
                    <h1 class="block text-2xl font-bold font-heading text-slate-900 tracking-tight">Créer un compte</h1>
                    <p class="mt-2 text-sm text-slate-500 font-medium">Rejoignez la plateforme d'évaluation interactive.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="grid gap-y-4">
                        <!-- Nom et Prénom -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="prenom" class="block text-sm mb-2 text-slate-800 font-medium">Prénom</label>
                                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                                    class="py-3 px-4 block w-full border-slate-200 rounded-xl text-sm border shadow-sm focus:border-primary-500 focus:ring-primary-500 outline-none transition-colors @error('prenom') border-red-500 @enderror"
                                    required autocomplete="given-name" autofocus>
                                @error('prenom')
                                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nom" class="block text-sm mb-2 text-slate-800 font-medium">Nom</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                                    class="py-3 px-4 block w-full border-slate-200 rounded-xl text-sm border shadow-sm focus:border-primary-500 focus:ring-primary-500 outline-none transition-colors @error('nom') border-red-500 @enderror"
                                    required autocomplete="family-name">
                                @error('nom')
                                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Input Email -->
                        <div>
                            <label for="email" class="block text-sm mb-2 text-slate-800 font-medium">Adresse
                                Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="py-3 px-4 block w-full border-slate-200 rounded-xl text-sm border shadow-sm focus:border-primary-500 focus:ring-primary-500 outline-none transition-colors @error('email') border-red-500 @enderror"
                                required autocomplete="email">
                            @error('email')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Password -->
                        <div>
                            <label for="password" class="block text-sm mb-2 text-slate-800 font-medium">Mot de
                                passe</label>
                            <input type="password" name="password" id="password"
                                class="py-3 px-4 block w-full border-slate-200 rounded-xl text-sm border shadow-sm focus:border-primary-500 focus:ring-primary-500 outline-none transition-colors @error('password') border-red-500 @enderror"
                                required autocomplete="new-password">
                            @error('password')
                                <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password-confirm"
                                class="block text-sm mb-2 text-slate-800 font-medium">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password-confirm"
                                class="py-3 px-4 block w-full border-slate-200 rounded-xl text-sm border shadow-sm focus:border-primary-500 focus:ring-primary-500 outline-none transition-colors"
                                required autocomplete="new-password">
                        </div>

                        <!-- Action Button -->
                        <button type="submit"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-primary-500 text-white hover:bg-primary-600 focus:outline-none focus:bg-primary-600 transition-colors mt-2 shadow-sm">
                            S'enregistrer
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white border-t border-slate-100 p-4 text-center">
                <p class="text-sm text-slate-600">
                    Déjà un compte ? <a href="{{ route('login') }}"
                        class="text-primary-500 font-bold hover:underline">Se connecter ici.</a>
                </p>
            </div>
        </div>
    </main>

</body>

</html>