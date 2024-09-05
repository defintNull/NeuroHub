<x-testcompilation-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Compilation') }}
        </h2>
    </x-slot>

    @isset($status)
        @if ($status == "exit-status")
            <x-slot name="status">
        @endif
    @endisset

    <x-slot name="treenav">
        <div class="relative min-h-full bottom-0 top-0">
            <div>
                <h2 class="font-semibold text-xl mt-6 mb-4 text-center text-gray-800 leading-tight">
                    {{ __('Test Tree') }}
                </h2>

                <ul id="tree" class="tree ml-4 overflow-auto"></ul>
            </div>

            <div class="mt-8 flex flex-col items-center">
                <form method="GET" action="{{ route('med.visitadministration.testcompilation') }}">
                    <x-danger-button class="xl:ml-8 mr-4 mb-4">
                        {{ __("Delete") }}
                    </x-danger-button>
                    <input type="hidden" name="status" value="exit-status">
                </form>
            </div>
        </div>
        <x-carbon-checkmark id="checkmark" class="hidden"></x-carbon-checkmark>
    </x-slot>

    <div class="constructor grow todimension overflow-y-auto">

    </div>

</x-testcompilation-layout>
