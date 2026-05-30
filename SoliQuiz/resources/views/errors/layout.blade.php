<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SoliQuiz</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .blob {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, var(--color-primary-200), transparent 70%);
            filter: blur(140px);
            border-radius: 50%;
            z-index: -10;
            pointer-events: none;
        }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, 30px); } }
        @keyframes pulse-soft { 0%, 100% { opacity: 0.15; transform: scale(1); } 50% { opacity: 0.2; transform: scale(1.05); } }
        .animate-float { animation: float 12s ease-in-out infinite; }
        .animate-pulse-soft { animation: pulse-soft 10s ease-in-out infinite; }
        .reveal-up { animation: revealUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes revealUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 h-full flex flex-col items-center justify-center p-6 antialiased overflow-hidden relative">
    
    <!-- Background Elements Identical to Landing -->
    <div class="blob opacity-20 -top-24 -left-24 animate-pulse-soft"></div>
    <div class="blob opacity-10 top-1/2 right-0 animate-float"></div>
    <div class="blob opacity-15 top-[70%] -left-48 animate-pulse-soft"
        style="background: linear-gradient(to left, var(--color-primary-400), transparent)"></div>

    <div class="max-w-xl w-full text-center relative z-10 reveal-up mt-14">
        
        <!-- Icon -->
        <!-- <div class="flex justify-center mb-8">
            <div class="size-16 bg-primary-500 rounded-2xl shadow-lg shadow-primary-500/20 flex items-center justify-center rotate-3 transform hover:rotate-6 transition-transform duration-500">
                <div class="text-white scale-75">
                    @yield('icon')
                </div>
            </div>
        </div> -->

        <!-- Identity pill -->
        <!-- <div class="inline-flex items-center gap-2.5 bg-white/60 backdrop-blur-md border border-slate-200 text-primary-600 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] mb-8">
            <span class="relative flex h-1.5 w-1.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-primary-500"></span>
            </span>
            System @yield('code')
        </div> -->

        <h1 class="text-7xl lg:text-8xl font-heading font-black text-slate-900 tracking-tighter mb-4 leading-none">
            @yield('code')
        </h1>
        
        <h2 class="text-2xl lg:text-3xl font-heading font-black text-slate-900 mb-6 tracking-tight leading-tight uppercase">
            @yield('message')
        </h2>
        
        <p class="text-slate-500 font-medium text-base mb-12 max-w-sm mx-auto leading-relaxed">
            @yield('description')
        </p>

        <!-- Premium Buttons: Compact & Direct -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="w-full sm:w-auto py-3.5 px-10 bg-slate-900 text-white font-bold rounded-xl hover:bg-primary-500 transition-all shadow-lg active:scale-95 text-[10px] uppercase tracking-widest group">
                Retour à l'accueil
            </a>
            <button onclick="history.back()" class="w-full sm:w-auto py-3.5 px-10 bg-white/80 backdrop-blur-sm text-slate-700 border border-slate-200 font-bold rounded-xl hover:bg-slate-50 transition-all text-[10px] uppercase tracking-widest shadow-sm active:scale-95">
                Page précédente
            </button>
        </div>

        <!-- Minimal Footer -->
        <div class="mt-20 flex items-center justify-center gap-3 opacity-30">
            <div class="size-6 bg-slate-900 rounded-lg flex items-center justify-center">
                <svg class="text-white size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /><path d="m9 15 2 2 4-4" />
                </svg>
            </div>
            <span class="text-xl font-heading font-black text-slate-900 tracking-tighter">SoliQuiz</span>
        </div>
    </div>
</body>
</html>




