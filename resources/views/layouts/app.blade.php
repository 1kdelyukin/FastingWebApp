<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('meta')
</head>

<body class="font-sans antialiased">
    <!-- Include the header component -->
    <x-header />

    <!-- Page Content -->
    <main class="flex flex-col items-center justify-center pb-20">
        @yield('content')
    </main>

    <!-- Fixed footer with white background -->
    <div class="fixed bottom-0 left-0 right-0 flex justify-center w-full bg-white border-t border-gray-200 pt-2 pb-1">
        <div class="w-10/12">
            <x-footer/>
        </div>
    </div>

    @yield('scripts')
</body>
</html>