<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/testdetail.css', 'resources/js/testdetail.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex flex-col min-h-screen bg-gray-100">

            @include('testmed.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Body -->
            @isset($treenav)
                <div class="flex-grow border-t-2 grid grid-cols-4 gap-4">
                    <header class="bg-white shadow">
                        <div class="max-w-7xl min-h-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $treenav }}
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="flex-grow col-span-3">
                        <div class="flex flex-col min-h-full max-w-8xl mx-auto py-10 sm:px-6 lg:px-8">
                            <div class="flex-grow bg-white overflow-hidden shadow-sm sm:rounded-lg todimension">
                                {{ $slot }}
                            </div>
                        </div>
                    </main>
                </div>

            @endisset
        </div>
    </body>
</html>
