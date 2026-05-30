<!DOCTYPE html>
<html lang="fr" class="bg-slate-50 border-x border-white/5 shadow-2xl h-full mx-auto w-full max-w-[430px] scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#f8fafc">
    <title>SoliQuiz Mobile</title>
    
    <!-- Fonts -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 h-full flex flex-col relative overflow-hidden font-sans antialiased text-slate-900 selection:bg-primary-500/30">
    <div id="app" class="h-full flex flex-col overflow-hidden">
        @yield('content')
    </div>

    <!-- Preline UI -->
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
    <script>
        window.addEventListener('load', () => {
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        });
    </script>
</body>
</html>