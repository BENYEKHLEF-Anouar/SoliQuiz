<!DOCTYPE html>
<html lang="fr" class="bg-gray-50 h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoliQuiz - Tableau de bord Formateur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
</head>

<body class="text-slate-800 font-sans antialiased">

    <!-- Header / Navbar Formateur -->
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-white border-b border-slate-200 sticky top-0">
        <nav class="relative max-w-7xl w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3" aria-label="Navigation Formateur">

            <!-- Logo -->
            <div class="md:col-span-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('dashboard') }}" aria-label="SoliQuiz Accueil">
                    <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:rotate-6">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Formateur</span>
                    </div>
                </a>
            </div>

            <!-- Navbar Center -->
            <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
                <a href="{{ route('formateur.dashboard') }}" class="font-bold text-primary-600 focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Supervision</a>
                <a href="#" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Mes QCM</a>
                <a href="#" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Résultats</a>
            </div>

            <!-- Actions & User Dropdown -->
            <div class="flex items-center gap-x-3 md:gap-x-4 ms-auto md:col-span-3 justify-end relative">
                <a href="#" class="hidden sm:inline-flex items-center gap-1.5 py-2 px-3 text-xs font-bold rounded-xl border border-transparent bg-primary-500 text-white hover:bg-primary-600 transition-all shadow-md shadow-primary-500/20 active:scale-95 uppercase tracking-widest font-heading">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Créer QCM
                </a>

                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-avatar" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 focus:outline-none focus:bg-slate-50 p-1 pr-3 transition-all active:scale-95">
                        <img class="inline-block size-8 rounded-full shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom . ' ' . Auth::user()->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                        <span class="hidden md:inline-block text-slate-800 font-heading font-bold text-sm">{{ Auth::user()->prenom }}.{{ substr(Auth::user()->nom, 0, 1) }}</span>
                        <svg class="hs-dropdown-open:rotate-180 size-4 transition" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100 z-50">
                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-800 hover:bg-slate-50 font-medium" href="#">Mon Profil</a>
                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-800 hover:bg-slate-50 font-medium" href="#">Paramètres</a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-600 hover:bg-red-50 focus:outline-none font-bold">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Hero Section Formateur -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold font-heading text-slate-900">Vue d'ensemble</h1>
                <p class="text-slate-500 mt-1 font-medium text-sm">Monitorez vos cohortes en temps réel.</p>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex flex-col bg-white border border-slate-200 shadow-sm rounded-xl p-5">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 gap-1 flex items-center">Étudiants Évalués</h3>
                <div class="text-3xl font-bold font-heading text-slate-900">0</div>
            </div>
            <div class="flex flex-col bg-white border border-slate-200 shadow-sm rounded-xl p-5">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 gap-1 flex items-center">Taux de réussite</h3>
                <div class="text-3xl font-bold font-heading text-green-600">--%</div>
            </div>
            <div class="flex flex-col bg-white border border-slate-200 shadow-sm rounded-xl p-5">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 gap-1 flex items-center">Tests à Corriger</h3>
                <div class="text-3xl font-bold font-heading text-slate-900">0</div>
            </div>
            <div class="flex flex-col bg-amber-50 border border-amber-200 shadow-sm rounded-xl p-5">
                <h3 class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-2 gap-1 flex items-center">En difficulté</h3>
                <div class="text-3xl font-bold font-heading text-amber-600">0</div>
            </div>
        </div>

    </main>

</body>

</html>
