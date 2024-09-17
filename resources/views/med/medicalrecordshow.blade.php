<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medical Record of ') }}{{ $patient->name }} {{ $patient->surname }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach ($visits as $visit)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <p class="text-gray-900">{{ $visit->date }}</p>
                            <p class="text-gray-900">{{ __('Type: ') }}{{ $visit->type }}</p>
                            <p class="text-gray-600">{{ __('Diagnosis: ') }}{{ $visit->diagnosis ? $visit->diagnosis : 'No Data' }}</p>
                            <p class="text-gray-600">{{ __('Treatment: ') }}{{ $visit->treatment ? $visit->treatment : 'No Data' }}</p>
                            @if ($visit->type == 'test')
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($visit->interviews as $interview)
                                        <li class="text-sm text-gray-600">
                                            <a href="" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Show
                                                {{ $interview->testresult->test->name }} result</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                    </div>
                </div>
            @endforeach

            @if ($visits->count() == 0)
                <p class="text-lg text-gray-900">{{ __('No Visits') }}</p>
            @endif

            {{ $visits->links() }}
        </div>
    </div>
</x-app-layout>
