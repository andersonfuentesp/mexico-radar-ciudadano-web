<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pollería La Familia') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('frontend/images/logo.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div id="background-image"
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 responsive-padding">
        <div class="responsive-image">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg responsive-content">
            {{ $slot }}
        </div>

    </div>
</body>

<style>
    #background-image {
        position: relative;
        background-image: url({{ asset('frontend/background/image.jpg') }});
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    #background-image::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    /* Asegúrate de que los elementos hijos tengan un z-index mayor que el pseudo-elemento */
    #background-image>div {
        position: relative;
        z-index: 2;
    }
</style>

<style>
    @media (max-width: 639px) {
        .responsive-padding {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .responsive-content {
            border-radius: 10px;
        }

        .responsive-image {
            margin-top: 30%;
        }
    }
</style>

</html>
