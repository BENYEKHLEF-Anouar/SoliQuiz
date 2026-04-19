<!DOCTYPE html>
<html lang="fr" class="bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoliQuiz - Supervision Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
</head>

<body class="text-slate-800 font-sans antialiased">

    <!-- Header / Navbar Admin -->
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-slate-900 border-b border-slate-800 sticky top-0">
        <nav class="relative max-w-[1600px] w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3" aria-label="Navigation Admin">

            <!-- Logo -->
            <div class="md:col-span-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('dashboard') }}" aria-label="SoliQuiz Accueil">
                    <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-white tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-0.5">Admin</span>
                    </div>
                </a>
            </div>

            <!-- Navbar Center -->
            <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-white focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Opérations</a>
                <a href="#" class="font-bold text-slate-400 hover:text-white transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Utilisateurs</a>
            </div>

            <!-- User Dropdown -->
            <div class="flex items-center gap-x-3 md:gap-x-4 ms-auto md:col-span-3 justify-end relative text-white">
                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-avatar" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-slate-700 bg-slate-800 text-white shadow-sm hover:bg-slate-700 focus:outline-none focus:bg-slate-700 p-1 pr-3 transition-all active:scale-95">
                        <div class="size-8 rounded-full bg-primary-500 flex items-center justify-center font-bold text-xs uppercase">{{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}</div>
                        <span class="hidden md:inline-block font-heading font-bold text-sm">{{ Auth::user()->prenom }}.{{ substr(Auth::user()->nom, 0, 1) }}</span>
                        <svg class="hs-dropdown-open:rotate-180 size-4 transition" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>

                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-slate-900 shadow-xl rounded-2xl p-2 mt-2 border border-slate-800 z-50">
                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-300 hover:bg-slate-800 font-medium" href="#">Système</a>
                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-300 hover:bg-slate-800 font-medium" href="#">Logs</a>
                        <div class="my-1 border-t border-slate-800"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-400 hover:bg-red-400/10 focus:outline-none font-bold">
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

    <main class="max-w-[1600px] mx-auto px-4 md:px-6 py-8 space-y-8">

        <!-- Hero Admin -->
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-3xl font-bold font-heading text-slate-900">Health Monitoring</h1>
            <button type="button" class="py-2.5 px-6 items-center gap-x-2 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-800 shadow-sm hover:bg-slate-50 transition-colors inline-flex">
                <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" />
                    <path d="M21 3v5h-5" />
                </svg>
                Forcer Sync SoliLMS
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex flex-col bg-white border border-slate-200 shadow-sm rounded-xl p-5">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Statut API Native</h3>
                <div class="flex items-center justify-between mt-auto">
                    <div class="text-2xl font-bold font-heading text-slate-900">Uptime: 99.9%</div>
                    <span class="flex size-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(22,163,74,0.5)]"></span>
                </div>
            </div>
            <div class="flex flex-col bg-white border border-slate-200 shadow-sm rounded-xl p-5">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Dernière Sync SoliLMS</h3>
                <div class="flex gap-2 items-end mt-auto">
                    <div class="text-2xl font-bold font-heading text-slate-900">--:--</div>
                    <div class="text-xs text-slate-500 font-medium mb-1">Aujourd'hui</div>
                </div>
            </div>
        </div>

    </main>

</body>

</html>
