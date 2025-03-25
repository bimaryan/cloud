<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cloud</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-lg w-full p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <header class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-lg font-semibold">Welcome to Cloud</h1>
            </div>
            <div class="flex gap-4 items-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-blue-600 dark:text-blue-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400">Log in</a>
                        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400">Register</a>
                    @endauth
                @endif
            </div>
        </header>
        <main>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Manage your cloud resources with ease.</p>
            {{-- <a href="https://cloud.laravel.com" target="_blank"
                class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Deploy Now</a> --}}
        </main>
    </div>
</body>

</html>
