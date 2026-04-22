<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SoliQuiz'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js & Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-full overflow-hidden" 
      x-data="{ sidebarOpen: true }">
    
    <div id="app" class="flex h-full overflow-hidden">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar -->
            <x-topbar />

            <!-- Content Area (SPA Swappable) -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-10" x-data="{}">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <x-toast-container />

    <!-- Global Confirmation Modal -->
    <div x-data x-show="$store.confirm.show" 
         class="fixed inset-0 z-[200] flex items-center justify-center p-6" x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="$store.confirm.show = false"></div>
        <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] shadow-2xl p-10 text-center"
             x-show="$store.confirm.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="size-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-8">
                <x-lucide-icon name="alert-circle" size="10" />
            </div>

            <h3 class="text-2xl font-heading font-black text-slate-900 uppercase tracking-tight leading-none mb-4" x-text="$store.confirm.title"></h3>
            <p class="text-slate-500 text-sm font-medium leading-relaxed mb-10" x-text="$store.confirm.message"></p>

            <div class="flex flex-col gap-3">
                <button @click="$store.confirm.proceed()" 
                        class="w-full py-4 bg-rose-500 text-white text-[10px] font-black rounded-xl hover:bg-rose-600 transition-all uppercase tracking-widest ">
                    Confirmer la suppression
                </button>
                <button @click="$store.confirm.show = false" 
                        class="w-full py-4 bg-slate-50 text-slate-400 text-[10px] font-black rounded-xl hover:bg-slate-100 transition-all uppercase tracking-widest ">
                    Annuler
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts & Stores -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Router Store
            Alpine.store('router', {
                page: 'dashboard', // Default page
                params: {},
                navigate(page, params = {}) {
                    this.page = page;
                    this.params = params;
                    
                    // Sync with URL for persistence
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', page);
                    // Clear search/filters when navigating to a new tab via sidebar
                    if (page !== 'users') {
                        url.searchParams.delete('search');
                        url.searchParams.delete('profil');
                    }
                    if (page !== 'qcms') {
                        url.searchParams.delete('qcm_search');
                    }
                    url.searchParams.delete('page'); // Reset pagination
                    
                    window.location.href = url.toString();
                }
            });

            // Confirm Store
            Alpine.store('confirm', {
                show: false,
                title: '',
                message: '',
                onConfirm: null,
                ask(title, message, callback) {
                    this.title = title;
                    this.message = message;
                    this.onConfirm = callback;
                    this.show = true;
                },
                proceed() {
                    if (this.onConfirm) this.onConfirm();
                    this.show = false;
                }
            });

            // Toasts Store
            Alpine.store('toasts', {
                list: [],
                add(message, type = 'success') {
                    const id = Date.now();
                    this.list.push({ id, message, type, progress: 100 });
                    
                    const timer = setInterval(() => {
                        const toast = this.list.find(t => t.id === id);
                        if (toast) {
                            toast.progress -= 2.5;
                            if (toast.progress <= 0) {
                                clearInterval(timer);
                                this.remove(id);
                            }
                        } else {
                            clearInterval(timer);
                        }
                    }, 100);
                },
                remove(id) {
                    this.list = this.list.filter(t => t.id !== id);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        window.addEventListener('alpine:navigate', () => {
            setTimeout(() => lucide.createIcons(), 50);
        });
    </script>
</body>
</html>
