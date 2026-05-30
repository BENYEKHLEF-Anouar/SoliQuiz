@extends('layouts.base')

@section('body-class', 'font-sans antialiased text-slate-800 bg-slate-50 h-screen overflow-hidden')

@section('body')
    <div class="flex h-screen overflow-hidden p-0 m-0" x-data="{ sidebarOpen: false }">
        <x-layout.sidebar />

        <div class="flex-1 flex flex-col min-w-0 p-0 m-0 lg:ml-72">
            <main class="flex-1 overflow-y-auto custom-scrollbar min-h-0">
                <div class="p-4 lg:p-10 pt-0 lg:pt-0">
                    <div class="max-w-[1600px] mx-auto pt-10">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
