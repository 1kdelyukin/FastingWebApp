<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Explore')</title>
    
    <!-- CSS inline to prevent FOUC -->
    <style>
        body {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .bg-white { background-color: white; }
        .rounded-lg { border-radius: 0.5rem; }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .p-4 { padding: 1rem; }
        .w-full { width: 100%; }
        .h-64 { height: 16rem; }
        .object-cover { object-fit: cover; }
    </style>
    
    <!-- Regular styles -->
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('meta')
    
    <!-- JavaScript to show body when loaded -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '1';
        });
    </script>
</head>

<body class="font-sans antialiased">
    <!-- Include the header component -->

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