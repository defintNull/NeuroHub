<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
{{--                 <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div> --}}

                <div class="m-4">
                    <form method="GET" action="">
                        <label for="testname"> {{ __('Select a test:') }} </label>
                        <select name="test" id="testname" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="all">All...</option>
                            @foreach ($tests as $test)
                                <option value="{{ $test->id }}" @if (isset($sel) && $sel == $test->id) selected @endif>
                                    {{ $test->name }}</option>
                            @endforeach
                        </select>
                        <label for="date1"> {{ __('From:') }} </label>
                        <input id="date1" type="date" name="datemin" autocomplete="day"
                            max="{{ now()->format('Y-m-d') }}"
                            @if (isset($datemin)) value="{{ $datemin }}" @endif
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"/>

                        <label for="date2"> {{ __('To:') }} </label>
                        <input id="date2" type="date" name="datemax" autocomplete="day"
                            max="{{ now()->format('Y-m-d') }}"
                            @if (isset($datemax)) value="{{ $datemax }}" @endif
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"/>

                            <p class="p-4 text-center text-sm text-red-600"></p>

                    </form>
                </div>

                <div class="p-6">
                    <canvas id="myChart"></canvas>
                </div>

{{--                 <script>
                    var d = {{ Js::from($data) }};
                </script> --}}
            </div>
        </div>
    </div>
</x-dashboard-layout>
