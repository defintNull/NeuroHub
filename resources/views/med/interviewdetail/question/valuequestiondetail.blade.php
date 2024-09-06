<body>
    <div>
        @if (isset($question) && isset($questionresult))
            <div class="p-6 mt-20 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-4 p-4 break-all text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="flex flex-col items-center text-center mt-6">
                <div class="text-gray-900 break-all w-3/4">
                    {{ __($question->text) }}
                </div>
                <div class="flex flex-row w-1/4 mt-6 items-center text-center mb-8">
                    <label for="values" class="grow justify-center mr-2 text-sm font-medium text-gray-900 dark:text-white">Value:</label>
                    <select id="values" name="valueinput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
                        <option>Choose a value</option>
                        @for ($i=0; $i<count($question->fields->singular); $i++)
                            @if ($question->fields->singular[$i] == $questionresult->value)
                                <option selected value="{{ "singular-".$i }}">{{ $question->fields->singular[$i] }}</option>
                            @else
                                <option disabled value="{{ "singular-".$i }}">{{ $question->fields->singular[$i] }}</option>
                            @endif
                        @endfor
                        @for ($i=0; $i<count($question->fields->personal); $i++)
                            @if ($question->fields->personal[$i] == $questionresult->value)
                                <option selected value="{{ "personal-".$i }}">{{ $question->fields->personal[$i] }}</option>
                            @else
                                <option disabled value="{{ "personal-".$i }}">{{ $question->fields->personal[$i] }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
            </div>
        @endif
    </div>
</body>
