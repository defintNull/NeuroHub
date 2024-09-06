<body>
    <div class="flex flex-col items-center">
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
                                @if ($testresult->sectionresults[$i]->result == null)
                                    <p class="pl-6 truncate">{{ __("No Data") }}</p>
                                @else
                                    <p class="pl-6 truncate">{{ $testresult->sectionresults[$i]->result }}</p>
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
                </div>
                <div class="w-4/5 mb-12">
                    @if ($testresult->result != null)
                        <p class="break-all mt-2">{{ $testresult->result }}</p>
                    @else
                        <p class="w-full mt-6 text-center">{{ __("No Data") }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>

