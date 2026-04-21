<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Introuvable - SoliQuiz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 h-full flex items-center justify-center p-6 antialiased font-sans">
    <div class="max-w-xl w-full text-center">
        <div class="relative inline-block mb-12">
            <h1 class="text-[180px] font-heading font-black text-slate-200 leading-none select-none">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="size-24 bg-primary-500 rounded-[2rem] shadow-2xl flex items-center justify-center rotate-12 animate-bounce-slow">
                    <svg class="text-white size-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <h2 class="text-4xl font-heading font-black text-slate-900 mb-4 uppercase italic tracking-tight">Oups ! Chemin perdu.</h2>
        <p class="text-slate-500 font-medium text-lg mb-10 leading-relaxed">
            La page que vous recherchez semble avoir été déplacée ou n'existe plus dans notre référentiel pédagogique.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/10 active:scale-95">
                Retour à l'accueil
            </a>
            <button onclick="history.back()" class="w-full sm:w-auto px-8 py-5 bg-white text-slate-700 border border-slate-200 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-50 transition-all shadow-sm">
                Page précédente
            </button>
        </div>
        
        <p class="mt-16 text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">SoliQuiz Ecosystem — Excellence Inside</p>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0) rotate(12deg); }
            50% { transform: translateY(-20px) rotate(12deg); }
        }
        .animate-bounce-slow { animation: bounce-slow 4s ease-in-out infinite; }
    </style>
</body>
</html>
