<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visit Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm flex flex-col p-8 sm:rounded-lg">
                <div class="px-6">
                    <p class="text-2xl font-semibold"> {{ __("Visit Control Panel") }} </p>
                    <p class="italic mt-2"> {{ __("Patient: Surname Name") }} </p>
                </div>
                <div class="h-96 mt-4 overflow-y-auto bg-blue-100 rounded-lg">
                    <div class="px-10 py-4">
                        <p class="italic text-lg font-thin"> {{ __("Visit Record:") }} </p>
                        <div class="px-10 pt-4">
                            {{-- Here visit content --}}
                            <p class="italic"> {{ __("No Records") }} </p>
                        </div>
                        <form method="POST" action="{{ route('med.visitadministration.controlpanel.newinterview') }}" class="flex mt-6 justify-center items-center">
                            @csrf
                            <button class="px-4 py-3 bg-blue-200 hover:bg-blue-400 rounded-lg"> {{ __("New Interview") }} </button>
                        </form>
                    </div>
                </div>
                <div class="mt-6 w-full h-8 relative">
                    <x-primary-button class="absolute right-0 mr-10"> {{ __("End Visit") }} </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
