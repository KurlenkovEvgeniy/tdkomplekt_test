<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $seoTitle ?? 'Evgeniy Kurlenkov\'s test' }}</title>
        @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        <x-base-header/>
        <main class="d-flex flex-column flex-grow-1">
            {{ $slot }}
        </main>
    </body>
</html>
