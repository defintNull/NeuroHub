<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select type of visit') }}
        </h2>
    </x-slot>

    <div class="py-12 mt-10">
        <div class="max-w-7xl mx-auto w-3/5">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('med.visits.create', $patient_id) }}">
                        <div class="flex flex-col items-center gap-4">
                            <label for="visit-type" class="flex flex-col w-full pl-10 items-start text-sm font-medium text-gray-700">
                                {{ __('Select type of visit:') }}
                            </label>
                            <div class="flex flex-col w-full items-center px-16">
                                <select id="visit-type" name="type" class="mt-1 w-full pl-5 text-sm text-gray-700 rounded-md" required>
                                    <option value="simple">{{ __('Simple Visit') }}</option>
                                    <option value="test">{{ __('Visit with tests') }}</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="mt-4 flex justify-end mr-16">
                            <x-primary-button>{{ __('Continue') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
