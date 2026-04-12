<!DOCTYPE html>
<html lang="fr" class="bg-slate-900 border-x border-slate-200 shadow-2xl h-[100dvh] mx-auto w-full max-w-[430px]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>SoliQuiz Mobile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('config', {
                apiBaseUrl: 'http://localhost:8000/api',
                studentUserId: 4,
                formateurUserId: 2
            });
        });
    </script>
</head>
<body class="bg-neutral-900 text-neutral-800 antialiased">
    <div id="app" class="min-h-screen">
        @yield('content')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            HSStaticMethods?.autoInit();
        });
    </script>
</body>
</html>