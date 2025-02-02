<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@400;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen flex items-center justify-center">
<div id="app" class="w-full grid grid-cols-2 gap-8">

    <div class="flex justify-center items-center">
        <h1 class="text-6xl">X</h1>
    </div>

    <div class="flex flex-col justify-center items-start text-center m-5">
        <h1 class="text-6xl font-extrabold">Happening now</h1>
        <h1 class="mt-8 mb-5 text-2xl font-bold">Join today.</h1>
        <a class="w-2/4 rounded-3xl border px-12 py-2 mt-1">google button</a>
        <a class="w-2/4 rounded-3xl border px-12 py-2 mt-2">apple button</a>

        <div class="flex items-center w-2/4 my-4">
            <hr class="flex-grow border-t border-gray-100">
            <p class="mx-4 text-gray-500">or</p>
            <hr class="flex-grow border-t border-gray-100">
        </div>

        <a class="w-2/4 rounded-3xl border px-12 py-2 bg-blue-400 text-white font-semibold">Create account</a>
        <p class="text-xs text-gray-400">By signing up, you agree...</p>
        <p class="text-gray-500 mt-10">Already have an account?</p>
        <h1 class="w-2/4 rounded-3xl border px-12 py-2 bg-transparent text-blue-400 font-semibold">Sign In</h1>
    </div>
</div>
</body>
</html>
