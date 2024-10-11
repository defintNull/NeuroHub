<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visit') }}
        </h2>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Patient: ') }}{{ $visit->patient->name }} {{ $visit->patient->surname }}
            </h2>
        </header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($visit->type == 'test')
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">
                            {{ __('Tests list') }}
                        </h3>
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Test') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Diagnosis') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Score') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($visit->interviews as $interview)
                                    <tr>
                                        <td class="px-6 py-4 min-w-14 whitespace-no-wrap border-b border-gray-200">
                                            {{ $interview->testresult->test->name }}
                                        </td>
                                        <td class="px-6 py-4 min-w-14 whitespace-no-wrap border-b border-gray-200 break-all">
                                            {{ __('Diagnosis: ') }}{{ $interview->diagnosis ? $interview->diagnosis : 'No Data' }}
                                        </td>
                                        <td class="px-6 py-4 min-w-14 whitespace-no-wrap border-b border-gray-200 break-all">
                                            {{ __('Score: ') }}{{ $interview->testresult->score }}
                                        </td>
                                        <td class="px-6 py-4 min-w-14 whitespace-no-wrap border-b border-gray-200">
                                            <form method="POST" action="{{ route('med.visits.interviewdetail.storeinterview', $visit->id)}}" class="text-indigo-600 hover:text-indigo-900">
                                                <input type="submit" value="{{ __('Show Answers') }}" class="cursor-pointer">
                                                @csrf
                                                <input type="hidden" name="interview" value="{{ $interview->id }}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <div @if ($visit->type == 'test') class="mt-8" @endif>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ __('Visit diagnosis and treatment') }}
                        </h3>
                        <div class="mt-4 px-6 mb-6">
                            <div class="flex flex-row items-start mt-1">
                                <p class="text-lg text-gray-800">{{ __('Diagnosis: ') }}</p>
                                <p class="text-sm ml-4 mt-1 text-gray-600">
                                    {{ $visit->diagnosis ? $visit->diagnosis : 'No Data' }}
                                </p>
                            </div>
                            <div class="flex flex-row items-start mt-2">
                                <p class="text-lg text-gray-800">{{ __('Treatment: ') }}</p>
                                <p class="text-sm ml-4 mt-1 text-gray-600">
                                    {{ $visit->treatment ? $visit->treatment : 'No Data' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end mr-8">
                        <form method="GET" action="{{ route('med.visits.show', ['patient' => $visit->patient]) }}">
                            <x-primary-button>
                                {{ __("Back to patient list") }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
