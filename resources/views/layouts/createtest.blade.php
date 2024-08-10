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
        @vite(['resources/css/createtest.css', 'resources/js/testcreation.js'])
    </head>
    <body class="font-sans antialiased">
        @if (isset($status))
            <div class="backdrop-blur-sm absolute size-full z-10 inset-0">
                <div class="bg-white absolute shadow-xl w-1/2 h-fit top-1/4 right-1/4">
                    <form method="post" action="{{ route('testmed.createteststructure.destroy') }}">
                        @csrf
                        @method('delete')

                        <div class="p-6 mt-10 text-center font-semibold text-xl text-gray-800 leading-tight">
                            {{ __("Cancel test creation") }}
                        </div>
                        <div class="p-6 mt-4 text-center font-semibold text-base text-gray-800 leading-tight">
                            {{ __("Are you sure you want to erase all the progress made? ") }}
                        </div>
                        <div class="flex justify-center items-center mt-8 mb-12">
                            <x-primary-button type="button" id="cancel" class="mr-4">
                                {{ __("Cancel") }}
                            </x-primary-button>
                            <x-danger-button class="inline-block">
                                {{ __("Delete") }}
                            </x-danger-button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
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
