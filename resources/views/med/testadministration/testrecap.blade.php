<body>
    <div class="grow">
        <form id="testform" method="POST" class="flex flex-col items-center">
            @csrf
            @if (isset($test) && isset($testresult))
            <div class="p-6 mt-12 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($test->name) }}
            </div>
            <div class="mt-2 w-full break-all text-left italic text-lg text-gray-800 leading-tight">
                <p class="px-16">{{ __("Section Recap:") }}</p>
            </div>
            <div class="bg-blue-100 rounded-lg min-h-60 mt-3 w-4/5">
                <div class="p-4 w-full">
                    @for ($i=0; $i<$test->sections->count(); $i++)
                        <div class="flex flex-row items-center">
                            <div class="flex flex-col w-1/2 mr-4 border-r-2 border-gray-300 flex-grow">
                                <p class="text-lg pl-6 truncate">{{ $test->sections[$i]->name }}</p>
                            </div>
                            <div class="flex flex-col w-1/2 pr-4 flex-grow">
                                <p class="text-lg">{{ __("Evaluation:") }}</p>
                                @if ($testresult->sectionresults[$i]->jump == 1)
                                    <p class="pl-6 truncate">{{ __("Jumped") }}</p>
                                @else
                                @if ($testresult->sectionresults[$i]->result == null)
                                    <p class="pl-6 truncate">{{ __("No Data") }}</p>
                                @else
                                    <p class="pl-6 truncate">{{ $testresult->sectionresults[$i]->result }}</p>
                                @endif
                                @endif
                            </div>
                        </div>
                        @if ($i != $test->sections->count()-1)
                            <div id="separator" class="mt-3 mb-4 bg-[repeating-linear-gradient(to_right,_transparent,_transparent_10px,_gray_10px,_gray_20px)]"></div>
                        @endif
                    @endfor
                </div>
            </div>
            <div class="w-full flex flex-col items-center">
                <div class="mt-6 flex flex-row w-full px-16 break-all text-left italic text-lg text-gray-800 leading-tight">
                    <p class="">{{ __("Test Evaluation:") }}</p>
                    <p class="px-2 italic text-sm">{{ __("(Optional)") }}</p>
                </div>
                <div class="w-4/5">
                    <textarea name="testtext" placeholder="Evaluation..." class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            <div class="w-full">
                <div class="flex flex-col relative mt-10 mb-8 items-end">
                    <div class="flex flex-row items-center">
                        <p class="italic mr-6 text-sm">{{ __("This will end the test compilation") }}</p>
                        <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                    </div>
                </div>
            </div>
        @endif
        </form>
    </div>
</body>

