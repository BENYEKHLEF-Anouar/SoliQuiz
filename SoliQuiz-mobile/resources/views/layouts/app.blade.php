<!DOCTYPE html>
<html lang="fr" class="bg-slate-900 border-x border-slate-200 shadow-2xl h-[100dvh] mx-auto w-full max-w-[430px]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>SoliQuiz Mobile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        primary: {
                            50: 'var(--color-primary-50)',
                            100: 'var(--color-primary-100)',
                            200: 'var(--color-primary-200)',
                            300: 'var(--color-primary-300)',
                            400: 'var(--color-primary-400)',
                            500: 'var(--color-primary-500)',
                            600: 'var(--color-primary-600)',
                            700: 'var(--color-primary-700)',
                            800: 'var(--color-primary-800)',
                            900: 'var(--color-primary-900)',
                        },
                        neutral: {
                            50: 'var(--color-neutral-50)',
                            100: 'var(--color-neutral-100)',
                            200: 'var(--color-neutral-200)',
                            300: 'var(--color-neutral-300)',
                            400: 'var(--color-neutral-400)',
                            500: 'var(--color-neutral-500)',
                            600: 'var(--color-neutral-600)',
                            700: 'var(--color-neutral-700)',
                            800: 'var(--color-neutral-800)',
                            900: 'var(--color-neutral-900)',
                        },
                        success: {
                            100: 'var(--color-success-100)',
                            500: 'var(--color-success-500)',
                            700: 'var(--color-success-700)',
                        },
                        warning: {
                            100: 'var(--color-warning-100)',
                            500: 'var(--color-warning-500)',
                            700: 'var(--color-warning-700)',
                        },
                        danger: {
                            100: 'var(--color-danger-100)',
                            500: 'var(--color-danger-500)',
                            700: 'var(--color-danger-700)',
                        },
                    }
                }
            }
        }
        // Alpine store for API config
        document.addEventListener('alpine:init', () => {
            Alpine.store('config', {
                apiBaseUrl: 'http://localhost:8000/api',
                studentUserId: 4,
                formateurUserId: 2
            });
        });
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .heading {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-neutral-900 text-neutral-800 antialiased">
    <div id="app" class="min-h-screen">
        @yield('content')
    </div>
    <script>
        // Initialize Preline UI components
        document.addEventListener('DOMContentLoaded', () => {
            // @ts-ignore
            HSStaticMethods.autoInit();
        });
    </script>
</body>
</html>