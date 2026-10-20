<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SoalKita') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('img/soalkita_logo.jpeg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased relative min-h-screen flex flex-col justify-center items-center py-6">

    <div class="fixed inset-0 -z-10 bg-cover bg-center bg-no-repeat"
        style="background-image: url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=1920&auto=format&fit=crop'); filter: brightness(35%) contrast(105%);">
    </div>

    <div class="fixed -top-20 -left-20 w-80 h-80 bg-blue-600/30 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="fixed -bottom-20 -right-20 w-80 h-80 bg-yellow-500/20 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <div class="w-full sm:max-w-md mt-4 px-6 py-8 bg-white/95 backdrop-blur-md shadow-2xl overflow-hidden sm:rounded-2xl border border-white/50 z-10">
        {{ $slot }}
    </div>

</body>

</html>