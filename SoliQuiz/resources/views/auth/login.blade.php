<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SoliQuiz') }} - Authentification</title>
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
                    <h1 class="block text-2xl font-bold font-heading text-slate-900 tracking-tight">Espace d'évaluation
                    </h1>
                    <p class="mt-2 text-sm text-slate-500 font-medium">Connectez-vous avec vos identifiants Solicode.
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="grid gap-y-4">
                        <!-- Input Email -->
                        <div>
                            <label for="email" class="block text-sm mb-2 text-slate-800 font-medium">Adresse
                                Email</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="py-3 px-4 block w-full border-slate-200 rounded-xl text-sm border shadow-sm focus:border-primary-500 focus:ring-primary-500 outline-none transition-colors @error('email') border-red-500 @enderror"
                                    required autocomplete="email" autofocus>
                                @error('email')
                                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-sm text-slate-800 font-medium">Mot de
                                    passe</label>
                                @if (Route::has('password.request'))
                                    <a class="text-xs text-primary-500 hover:text-primary-600 font-semibold hover:underline"
                                        href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                                @endif
                            </div>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="py-3 px-4 block w-full border-slate-200 rounded-xl text-sm border shadow-sm focus:border-primary-500 focus:ring-primary-500 outline-none transition-colors @error('password') border-red-500 @enderror"
                                    required autocomplete="current-password">
                                @error('password')
                                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember me -->
                        <div class="flex items-center mt-2">
                            <div class="flex">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="shrink-0 mt-0.5 border-slate-200 rounded text-primary-500 focus:ring-primary-500 outline-none cursor-pointer">
                            </div>
                            <div class="ms-3">
                                <label for="remember" class="text-sm font-medium text-slate-700 cursor-pointer">Se
                                    souvenir de moi</label>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button type="submit"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-primary-500 text-white hover:bg-primary-600 focus:outline-none focus:bg-primary-600 transition-colors mt-2 shadow-sm">
                            Se connecter
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

            <div class="bg-slate-50 border-t border-slate-200 p-4 text-center">
                <p class="text-xs text-slate-500">
                    Problème d'accès ? <a href="#" class="text-primary-500 font-medium hover:underline">Contactez
                        l'administration.</a>
                </p>
            </div>

            @if (Route::has('register'))
                <div class="bg-white border-t border-slate-100 p-4 text-center">
                    <p class="text-sm text-slate-600">
                        Pas encore de compte ? <a href="{{ route('register') }}"
                            class="text-primary-500 font-bold hover:underline">Inscrivez-vous ici.</a>
                    </p>
                </div>
            @endif
        </div>
    </main>

</body>

</html>