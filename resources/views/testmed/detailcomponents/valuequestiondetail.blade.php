<body>
    <div>
        @if (isset($question))
            <div class="p-6 mt-12 text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-6 p-4 text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-2 p-4 text-center text-gray-900">
                {{ __($question->text) }}
            </div>
            <div class="flex justify-center mt-4 mb-8 relative">
                <div id="values" class="bg-white rounded-lg shadow w-60 dark:bg-gray-700">
                    <ul id="valueslist" class="h-52 px-3 pb-3 overflow-y-auto text-sm" aria-labelledby="dropdownSearchButton">
                        <div class="items-center justify-center flex w-full">
                            <ul id="radiolist" class="w-52 grid grid-cols-2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                                @for ($i=0; $i<count($question->fields->singular); $i++)
                                    <div class="valuelistitem singlecheck flex items-center p-2 rounded hover:bg-gray-100">
                                        <input disabled id="checkbox-singular-{{ $i+1 }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label id="checkbox-singular-text-{{ $i+1 }}" class="w-full ms-2 mr-1 text-sm font-medium text-gray-900 rounded">{{ $question->fields->singular[$i] }}</label>
                                    </div>
                                @endfor
                                @for ($i=0; $i<count($question->fields->personal); $i++)
                                    <div class="valuelistitem singlecheck flex items-center p-2 rounded hover:bg-gray-100">
                                        <input disabled id="checkbox-personal-{{ $i+1 }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label id="checkbox-personal-text-{{ $i+1 }}" class="w-full ms-2 mr-1 text-sm font-medium text-gray-900 rounded">{{ $question->fields->personal[$i] }}</label>
                                    </div>
                                @endfor
                            </ul>
                        </div>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col items-center mb-8">
                @if ($scores)
                    <p class="italic">{{ __("Score values set for this question") }}</p>
                @endif
            </div>
            @if ($jump)
            <div id="jump-container" class="mt-8 mb-10">
                <div class="text-center text-2xl mb-6 mt-6 font-semibold">{{ __("Jump System") }}</div>
                <div class="flex flex-col items-center">
                    <div class="flex flex-col items-center mt-4 justify-center w-2/3">
                        <div class="grid grid-cols-4 items-center w-full">
                            <p class="text-center col-span-2 text-lg">{{ __("Range of values") }}</p>
                            <p class="text-center col-span-2 text-lg">{{ __("Section Jump") }}</p>
                        </div>
                        @for ($i=0; $i<$question->jump->count(); $i++)
                            <div class="rangecontainer grid grid-cols-4 items-start w-full">
                                <div class="flex flex-col items-center mt-4 justify-center">
                                    <div class="flex flex-row items-center justify-center">
                                        <p class="mr-4">From:</p>
                                        <input type="text" value="{{ $question->jump[$i][0] }}" class="rangeinput rounded-lg w-20"/>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center mt-4 justify-center">
                                    <div class="flex flex-row items-center justify-center">
                                        <p class="mr-4">To:</p>
                                        <input type="text" value="{{ $question->jump[$i][1] }}" class="rangeinput rounded-lg w-20"/>
                                    </div>
                                </div>
                                <div class="flex flex-row col-span-2 mt-4 border-l border-gray-400 items-center justify-center">
                                    <p class="mr-4">Section:</p>
                                    <select id="jumpinterval{{ $i+1 }}" name="jumpinterval{{ $i+1 }}" class="rounded-lg">
                                        @for ($n=0; $n<count($sectionlist); $n++)
                                            @if ($sectionlist[$n][0] == $question->jump[$i][2])
                                                <option selected value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                            @else
                                                <option value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            @endif
        @endif
    </div>
</body>
