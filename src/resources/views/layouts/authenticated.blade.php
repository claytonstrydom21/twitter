<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@400;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black">
<script>
    window.user = @json(auth()->user());
</script>
<div id="app">
    <side-nav></side-nav>

    <div>
        <main class="max-w-2xl mx-auto min-h-screen border-x border-gray-800">
            @yield('content')
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
</body>
</html>
