<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cloud') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                includedLanguages: 'id,en',
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
    <script src="https://kit.fontawesome.com/f74deb4653.js" crossorigin="anonymous"></script>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex items-center p-6 justify-center bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
            <div class="text-center mb-4">
                <a href="/">
                    <p class="text-4xl font-semibold">Cloud</p>
                </a>
            </div>
            {{ $slot }}
        </div>
    </div>
</body>

</html>
