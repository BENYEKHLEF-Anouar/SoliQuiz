<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth min-h-screen alpine-loading">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SoliQuiz'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Tailwind V4 & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">


    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
        .alpine-loading [x-show] { display: none !important; }
        #global-preloader { display: none !important; }
        .app-first-load #global-preloader { display: flex !important; }
    </style>
    <script>
        if (!sessionStorage.getItem('app-loaded')) {
            document.documentElement.classList.add('app-first-load');
            sessionStorage.setItem('app-loaded', 'true');
        }
        document.addEventListener('alpine:initialized', () => {
            document.documentElement.classList.remove('alpine-loading');
            const preloader = document.getElementById('global-preloader');
            if (preloader) {
                preloader.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => preloader.remove(), 500);
            }
        });
        setTimeout(() => {
            document.documentElement.classList.remove('alpine-loading');
            const preloader = document.getElementById('global-preloader');
            if (preloader) {
                preloader.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => preloader.remove(), 500);
            }
        }, 1500);
    </script>
</head>

<body class="@yield('body-class', 'font-sans antialiased text-slate-800 bg-slate-50 min-h-screen')">
    <!-- Global Preloader -->
    <div id="global-preloader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-50 transition-opacity duration-500">
        <div class="flex flex-col items-center gap-4">
            <div class="size-12 border-4 border-slate-200 border-t-primary-500 rounded-full animate-spin shadow-sm"></div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">SoliQuiz</p>
        </div>
    </div>

    @yield('body')

    <!-- Global UI Components -->
    <x-ui.toast />
    <x-ui.confirm-modal />

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
