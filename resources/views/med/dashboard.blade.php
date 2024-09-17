<x-meddashboard-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <div class="p-6 text-gray-900 text-center text-2xl">
                    {{ __("Welcome back ". auth()->user()->userable->name."!") }}
                </div>

                <div class="m-4">
                        <label for="date1"> {{ __('From:') }} </label>
                        <input id="date1" type="date" name="date1" autocomplete="day"
                            max="{{ now()->format('Y-m-d') }}"
                            @if (isset($datemin)) value="{{ $datemin }}" @endif
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />

                        <label for="date2"> {{ __('To:') }} </label>
                        <input id="date2" type="date" name="date2" autocomplete="day"
                            max="{{ now()->format('Y-m-d') }}"
                            @if (isset($datemax)) value="{{ $datemax }}" @endif
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />

                        <button type="submit" id="submitbtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>

                        <p class="p-4 text-center text-sm text-red-600"></p>
                </div>

                <div class="{{-- flex flex-col items-center --}} w-full h-full">
                    <div class="p-6" id="canvascontainer">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-meddeashboard-layout>
