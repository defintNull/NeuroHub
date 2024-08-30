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
                        @csrf
                        <div class="flex items-center gap-4">
                            <label for="visit-type" class="block text-sm font-medium text-gray-700">
                                {{ __('Select type of visit') }}
                            </label>
                            <select id="visit-type" name="type" class="mt-1 block w-full pl-10 text-sm text-gray-700" required>
                                <option value="test">{{ __('With test') }}</option>
                                <option value="simple">{{ __('Without test') }}</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <x-primary-button>{{ __('Continue') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
