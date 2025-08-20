{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DocScanner') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
<div x-data="{ sidebarOpen: false }" class="min-h-screen">

    {{-- === Sidebar (file terpisah) === --}}
    @include('layouts.sidebar')

    {{-- === Area konten + Topbar === --}}
    <div class="lg:pl-64">
        @include('layouts.topbar')

        <main class="p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
