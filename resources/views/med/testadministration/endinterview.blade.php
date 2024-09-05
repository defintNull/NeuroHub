<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('End Interview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm flex flex-col p-8 sm:rounded-lg">
                <div class="px-6">
                    @if (isset($interview))
                        <p class="text-2xl font-semibold"> {{ __("Interview Recap") }} </p>
                        <p class="italic mt-2"> {{ "Patient: ".$interview->visit->patient->name." ".$interview->visit->patient->surname }} </p>
                        <div class="mt-8 w-full break-all text-left italic text-lg text-gray-800 leading-tight">
                            <p class="px-16">{{ __("Test Recap:") }}</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="bg-blue-100 rounded-lg mt-3 w-4/5">
                                <div class="p-4 w-full">
                                    <div class="flex flex-row items-center">
                                        <div class="flex flex-col w-1/2 mr-4 border-r-2 border-gray-300 flex-grow">
                                            <p class="text-lg pl-6 truncate">{{ $interview->testresult->test->name }}</p>
                                        </div>
                                        <div class="flex flex-col w-1/2 pr-4 flex-grow">
                                            <p class="text-lg">{{ __("Evaluation:") }}</p>
                                            @if ($interview->testresult->result == null)
                                                <p class="pl-6 truncate">{{ __("No Data") }}</p>
                                            @else
                                                <p class="pl-6 truncate">{{ $interview->testresult->result }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('med.visitadministration.endinterview') }}">
                            @csrf
                            <div class="w-full mt-8 flex flex-col items-center">
                                <div class="mt-6 flex flex-row w-full px-16 break-all text-left italic text-lg text-gray-800 leading-tight">
                                    <p class="">{{ __("Interview Diagnosis:") }}</p>
                                    <p class="px-2 italic text-sm">{{ __("(Optional)") }}</p>
                                </div>
                                <div class="w-4/5 mt-2">
                                    <textarea name="diagnosis" placeholder="Evaluation..." class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="flex flex-col relative mt-10 mb-8 items-end">
                                    <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
