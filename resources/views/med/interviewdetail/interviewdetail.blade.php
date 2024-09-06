<x-interviewdetail-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (isset($interview))
                {{ $interview->testresult->test->name }}
            @endif
        </h2>
    </x-slot>

    <x-slot name="treenav">
        <div class="relative min-h-full bottom-0 top-0 tohidden">
            <div>
                <h2 class="font-semibold text-xl mt-6 mb-4 text-center text-gray-800 leading-tight">
                    {{ __('Test Tree') }}
                </h2>

                <ul id="tree" class="tree ml-4 overflow-auto"></ul>
                <div class="mt-8 flex flex-col items-center">
                    <form method="GET" action="{{ route('med.visits.interviews', ['visit' => $interview->visit]) }}">
                        <x-primary-button class="xl:ml-8 mr-4 mb-4">
                            {{ __("Back to visit") }}
                        </x-primary-button>
                    </form>
                </div>
                <div id="csrf" class="hidden">
                    @csrf
                </div>
            </div>
        </div>
    </x-slot>

    <div class="constructor todimension overflow-y-auto">
        <div class="flex flex-col justify-center items-center">
            <div class="p-6 mt-44 text-center font-semibold text-xl text-gray-800 leading-tight">
                @if (isset($interview))
                    {{ $interview->testresult->test->name }}
                @endif
            </div>
            <div class="mt-12 p-4 mb-24 text-center text-gray-900">
                {{ __("To continue choose a section or a question...") }}
            </div>
        </div>
    </div>

</x-interviewdetail-layout>
