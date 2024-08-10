<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('testmed.createtest') }}">
                    @csrf
                    <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                        {{ __("Create Test") }}
                    </div>
                    @if (isset($status))
                        <div class="p-2 text-center text-lg text-lime-500">
                            {{ __("Test created succesfully!") }}
                        </div>
                    @endif
                    <div class="p-2 text-center text-gray-900">
                        {{ __("Insert test name to start test creation.") }} <br>
                        {{ __("Exit the creation page will discard the creation progress") }}
                    </div>
                    <div class="mt-10 p-4 text-center text-gray-900">
                        {{ __("Insert Test Name") }}
                    </div>
                    <div class="px-10 md:px-60 mt-2">
                        <x-text-input id="testname" class="text-center block mt-1 w-full" type="text" name="testname" required placeholder='Test Name'/>

                        <x-input-error :messages="$errors->get('testname')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mb-12 mr-32 mt-32">
                        <x-primary-button class="ms-4">
                            {{ __('Create') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
