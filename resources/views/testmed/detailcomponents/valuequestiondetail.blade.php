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
                    @if ($scores)
                        <p class="mt-8 italic">{{ __("Score values set for this question") }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
