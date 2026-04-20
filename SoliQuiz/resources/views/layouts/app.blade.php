@extends('layouts.base')

@section('body-class', 'font-sans antialiased text-slate-800 bg-slate-50 min-h-screen')

@section('body')
    <div class="flex min-h-screen bg-mesh" x-data="{ sidebarOpen: false }">
        @auth
            <x-layout.sidebar />
        @endauth

        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                <div class="max-w-[1600px] mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
@endsection
