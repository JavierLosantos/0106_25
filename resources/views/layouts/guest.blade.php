<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: url("{!! asset('assets/dist/img/fondo.webp') !!}") no-repeat center center fixed;
            background-size: cover;
        }
    </style>
    
    
</head>
<body class="font-sans text-gray-900 antialiased">
    
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="flex items-center space-x-3 bg-white p-3 rounded shadow">
            <a href="/">
                <img class="animation__shake" src="{{ url('assets/dist/img/AdminLTELogo.webp') }}" alt="AdminLTELogo" height="40" width="40">
            </a>
            <span class="fs-2 fw-bold text-success">
                <span class="fw-bolder">Nutri</span>Naza
            </span>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
