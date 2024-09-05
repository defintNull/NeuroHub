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
        @vite(['resources/css/testadministration.css', 'resources/js/testadministration.js'])
    </head>
    <body class="font-sans antialiased">
        @if (isset($status))
            <div class="backdrop-blur-sm absolute size-full z-10 inset-0">
                <div class="bg-white absolute shadow-xl w-1/2 h-fit top-1/4 right-1/4">
                    <form method="post" action="{{ route('med.visitadministration.visitdestroy') }}">
                        @csrf
                        @method('delete')

                        <div class="p-6 mt-10 text-center font-semibold text-xl text-gray-800 leading-tight">
                            {{ __("Cancel test compilation") }}
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
        <div class="min-h-screen bg-gray-100">

            @include('med.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
