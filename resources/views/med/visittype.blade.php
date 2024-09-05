<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select type of visit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('med.visits.create', $patient_id) }}">
                        <div class="flex items-center gap-4">
                            <label for="visit-type" class="block text-sm font-medium text-gray-700">
                                {{ __('Select type of visit') }}
                            </label>
                            <select id="visit-type" name="type" class="mt-1 block w-full pl-5 text-sm text-gray-700 rounded-md" required>
                                <option value="simple">{{ __('Simple Visit') }}</option>
                                <option value="test">{{ __('Visit with tests') }}</option>
                            </select>
                        </div>
                        <br>
                        <div class="mt-4 flex justify-end">
                            <x-primary-button>{{ __('Continue') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
