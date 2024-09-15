<x-testscore-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Score') }}
        </h2>
    </x-slot>

    @isset($status)
        @if ($status == "exit-status")
            <x-slot name="status">
        @endif
    @endisset

    <x-slot name="treenav">
        <div class="relative min-h-full bottom-0 top-0 tohidden">
            <div>
                <h2 class="font-semibold text-xl mt-6 mb-4 text-center text-gray-800 leading-tight">
                    {{ __('Test Tree') }}
                </h2>

                <ul id="tree" class="tree ml-4 overflow-auto"></ul>
                <div id="csrf" class="hidden">
                    @csrf
                </div>
            </div>

            <div class="mt-8">
                @if (isset($error))
                    <div class="text-red-500 mb-1 text-center">{{ $error }}</div>
                @endif
                <form class="flex flex-col items-center" method="get" action="{{ route('testmed.createteststructure.testscore') }}">
                    <x-danger-button class="mb-4">
                        {{ __("Delete") }}
                    </x-danger-button>
                    <input type="hidden" name="status" value="exit-status">
                </form>
            </div>
        </div>
        <x-carbon-checkmark id="checkmark" class="hidden"></x-carbon-checkmark>
        <x-carbon-subtract-alt id="alt" class="hidden"></x-carbon-subtract-alt>
        <x-carbon-hourglass id="hourglass" class="hidden"></x-carbon-hourglass>
    </x-slot>

    <div class="constructor todimension overflow-y-auto">
        <div class="flex flex-col justify-center items-center">
            <div class="p-6 mt-44 text-center font-semibold text-xl text-gray-800 leading-tight">
                {{ __("Test Creation") }}
            </div>
            <div class="mt-12 p-4 mb-24 text-center text-gray-900">
                {{ __("To continue creation add a section or a question...") }}
            </div>
        </div>
    </div>

</x-testscore-layout>
