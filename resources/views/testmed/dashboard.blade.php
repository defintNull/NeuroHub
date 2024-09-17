<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mt-10 p-10 text-gray-900 text-center text-4xl">
                    {{ __("Welcome back ". Auth::user()->userable->name."!") }}
                </div>

                <div class="m-4 items-center">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <img src="/logo_neurohub.svg" height="300" width="300" alt="Logo NeuroHub"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
