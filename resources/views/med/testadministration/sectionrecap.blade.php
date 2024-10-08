<body>
    <div class="grow">
        <form id="{{ isset($update) ? 'updateform' : 'nextform' }}" method="POST" class="flex flex-col items-center">
            @csrf
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
                                <div class="flex flex-col w-1/2 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/2 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <p class="pl-6 truncate">{{ $sectionresult->questionresults[$i]->questionable->value }}</p>
                                </div>
                            </div>
                        @elseif ($questiontypes[$i] == "ValueQuestion")
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
                                <div class="flex flex-col w-1/2 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/2 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <p class="pl-6 truncate">{{ implode("-", $sectionresult->questionresults[$i]->questionable->value->getArrayCopy()) }}</p>
                                </div>
                            </div>
                        @elseif ($questiontypes[$i] == "ImageQuestion")
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col w-1/2 mr-4 border-r-2 border-gray-300 flex-grow">
                                    <p class="text-lg">{{ $section->questions[$i]->questionable->title.":" }}</p>
                                    <p class="pl-6 truncate">{{ $section->questions[$i]->questionable->text }}</p>
                                </div>
                                <div class="flex flex-col w-1/2 pr-4 flex-grow">
                                    <p class="text-lg">{{ __("Answer:") }}</p>
                                    <img class="ml-4 w-40 h-40 object-contain rounded" src="{{ $images[$i] }}" alt="Image Preview">
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
                    <p class="">{{ __("Section Evaluation:") }}</p>
                    <p class="px-2 italic text-sm">{{ __("(Optional)") }}</p>
                </div>
                <div class="w-4/5">
                    @if (isset($update))
                        <textarea name="sectiontext" placeholder="Evaluation..." class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500">{{ $sectionresult->result }}</textarea>
                    @else
                        <textarea name="sectiontext" placeholder="Evaluation..." class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @endif
                </div>
            </div>
            @if (isset($update))
                <input type="hidden" name="update" value="{{ $sectionresult->id }}"/>
                <input type="hidden" name="type" value="section"/>
            @endif
            <div class="w-full">
                <div class="flex flex-col relative mt-10 mb-8 items-end">
                    @if (isset($update))
                        <div class="flex flex-row justify-center items-center mt-8 mb-12">
                            <button type="button" class="inline-flex cancel items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __("Cancel") }}
                            </button>
                            <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                        </div>
                    @else
                        <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                    @endif
                </div>
            </div>
        @endif
        </form>
    </div>
</body>
