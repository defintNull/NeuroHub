<x-testadministration-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visit Administration') }}
        </h2>
    </x-slot>

    @isset($status)
        @if ($status == "exit-status")
            <x-slot name="status">
        @endif
    @endisset

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm flex flex-col p-8 sm:rounded-lg">
                <div class="px-6">
                    <p class="text-2xl font-semibold"> {{ __("Visit Control Panel") }} </p>
                    @if (isset($visit))
                        <p class="italic mt-2"> {{ __("Patient: ".$visit->patient->name." ".$visit->patient->surname) }} </p>
                    @endif
                </div>
                <div class="h-96 mt-4 overflow-y-auto bg-blue-100 rounded-lg">
                    <div class="px-10 py-4">
                        @if (isset($visit))
                            @if ($visit->interviews->count() != 0)
                            @for ($i=0; $i<$visit->interviews->count(); $i++)
                                <div class="flex flex-row items-center">
                                    <div class="flex flex-col w-1/2 mr-4 border-r-2 border-gray-300 flex-grow">
                                        <p class="text-lg">{{ __("Test:") }}</p>
                                        <p class="text-lg pl-6 truncate">{{ $visit->interviews[$i]->testresult->test->name }}</p>
                                    </div>
                                    <div class="flex flex-col w-1/2 pr-4 flex-grow">
                                        <p class="text-lg">{{ __("Diagnosis:") }}</p>
                                        @if ($visit->interviews[$i]->testresult->result == null)
                                            <p class="pl-6 truncate">{{ __("No Data") }}</p>
                                        @else
                                            <p class="pl-6 truncate">{{ $visit->interviews[$i]->testresult->result }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if ($i != $visit->interviews->count()-1)
                                    <div id="separator" class="mt-3 mb-4 bg-[repeating-linear-gradient(to_right,_transparent,_transparent_10px,_gray_10px,_gray_20px)]"></div>
                                @endif
                            @endfor
                            @else
                                <p class="italic text-lg font-thin"> {{ __("Visit Record:") }} </p>
                                <div class="px-10 pt-4">
                                    {{-- Here visit content --}}
                                    <p class="italic"> {{ __("No Records") }} </p>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('med.visitadministration.controlpanel.newinterview') }}" class="flex mt-6 justify-center items-center">
                                @csrf
                                <button class="px-4 py-3 bg-blue-200 hover:bg-blue-400 rounded-lg"> {{ __("New Interview") }} </button>
                            </form>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('med.visitadministration.visitupdate') }}">
                    @csrf
                    <div class="flex flex-col mt-8 items-center">
                        <div class="flex flex-row w-full px-16 break-all text-left italic text-lg text-gray-800 leading-tight">
                            <p class="">{{ __("Visit Diagnosis:") }}</p>
                            <p class="px-2 italic text-sm">{{ __("(Optional)") }}</p>
                        </div>
                        <div class="w-4/5 mt-2">
                            <textarea name="diagnosis" placeholder="Diagnosis..." class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div class="flex flex-row mt-6 w-full px-16 break-all text-left italic text-lg text-gray-800 leading-tight">
                            <p class="">{{ __("Visit Treatment:") }}</p>
                            <p class="px-2 italic text-sm">{{ __("(Optional)") }}</p>
                        </div>
                        <div class="w-4/5 mt-2">
                            <textarea name="treatment" placeholder="Treatment..." class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>
                    <div class="w-full flex flex-col items-end">
                        <div class="flex flex-row items-center mt-8 h-8">
                            <x-primary-button class="mr-4"> {{ __("Save Visit") }} </x-primary-button>
                            <x-danger-button id="deletevisit"> {{ __("Delete Visit") }} </x-primary-button>
                        </div>
                    </div>
                </form>
                <form method="GET" id="deletevisitform" class="hidden" action="{{ route('med.visitadministration.controlpanel') }}">

                    <input type="hidden" name="status" value="exit-status">
                </form>
            </div>
        </div>
    </div>
</x-testadministration-layout>
