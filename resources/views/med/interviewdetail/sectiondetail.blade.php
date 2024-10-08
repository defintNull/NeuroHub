<body>
    <div class="flex flex-col items-center">
        @if (isset($section) && isset($sectionresult) && isset($questiontypes))
            <div class="p-6 mt-12 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($section->name) }}
            </div>
            <div class="mt-2 w-full break-all text-left italic text-lg text-gray-800 leading-tight">
                <p class="px-16">{{ __("Section Recap:") }}</p>
            </div>
            <div class="bg-blue-100 rounded-lg min-h-60 mt-3 w-4/5">
                <div class="p-4 w-full">
                    @for ($i=0; $i<count($questiontypes); $i++)
                        @if ($questiontypes[$i] == "MultipleQuestion")
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->value }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Score:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->score }}</p>
                                </div>
                            </div>
                        @elseif ($questiontypes[$i] == "ValueQuestion")
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->value }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Score:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->score }}</p>
                                </div>
                            </div>
                        @elseif ($questiontypes[$i] == "OpenQuestion")
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col w-1/2 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/2 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->value }}</p>
                                </div>
                            </div>
                        @elseif ($questiontypes[$i] == "MultipleSelectionQuestion")
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <p class="pl-6 truncate">{{ implode("-", $sectionresult->questionresults[$i]->questionable->value->getArrayCopy()) }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Score:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->score }}</p>
                                </div>
                            </div>
                        @elseif ($questiontypes[$i] == "ImageQuestion")
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 mr-4 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <img class="ml-4 w-40 h-40 object-contain rounded" src="{{ $images[$i] }}" alt="Image Preview">
                                </div>
                                <div class="flex flex-col w-1/3 pl-4 pr-4 border-l-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ __("Score:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->score }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <p class="pl-6 truncate">{{ 'Jumped' }}</p>
                                </div>
                                <div class="flex flex-col w-1/3 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Score:") }}</p>
                                    <p class="pl-6 truncate">{{ 'Jumped' }}</p>
                                </div>
                            </div>
                        @endif
                        @if ($i != count($questiontypes)-1)
                            <div id="separator" class="mt-3 mb-4 bg-[repeating-linear-gradient(to_right,_transparent,_transparent_10px,_gray_10px,_gray_20px)]"></div>
                        @endif
                    @endfor
                </div>
            </div>
            <div class="w-full flex flex-col items-center">
                <div class="mt-6 flex flex-row w-full px-16 break-all text-left italic text-lg text-gray-800 leading-tight">
                    <p class="">{{ __("Section Score:") }}</p>
                    @if ($sectionresult->jump == 1)
                        <p class="px-2 justify-center">{{ __("Jumped") }}</p>
                    @else
                        <p class="px-2 justify-center">{{ $sectionresult->score }}</p>
                    @endif
                </div>
            </div>
            <div class="w-full flex flex-col items-center">
                <div class="mt-6 flex flex-row w-full px-16 break-all text-left italic text-lg text-gray-800 leading-tight">
                    <p class="">{{ __("Section Evaluation:") }}</p>
                    <p class="px-2 italic text-sm">{{ __("(Optional)") }}</p>
                </div>
                <div class="w-4/5 mb-12">
                    @if ($sectionresult->result != null)
                        <p class="break-all mt-2">{{ $sectionresult->result }}</p>
                    @else
                        <p class="w-full mt-6">{{ __("No Data") }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
