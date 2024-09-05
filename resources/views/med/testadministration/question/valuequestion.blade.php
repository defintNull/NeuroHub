<body>
    <div>
        @if (isset($question))
            <div class="p-6 mt-20 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-4 p-4 break-all text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-6">
                <form id="{{ isset($update) ? 'updateform' : 'nextform' }}" method="POST" class="flex flex-col items-center text-center">
                    @csrf
                    <div class="text-gray-900 break-all w-3/4">
                        {{ __($question->text) }}
                    </div>
                    <div class="flex flex-row w-1/4 mt-6 items-center text-center">
                        <label for="values" class="grow justify-center mr-2 text-sm font-medium text-gray-900 dark:text-white">Value:</label>
                        <select id="values" name="valueinput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5">
                            <option>Choose a value</option>
                            @for ($i=0; $i<count($question->fields->singular); $i++)
                                @if (isset($update))
                                    @if ($question->fields->singular[$i] == $questionresult->value)
                                        <option selected value="{{ "singular-".$i }}">{{ $question->fields->singular[$i] }}</option>
                                    @else
                                        <option value="{{ "singular-".$i }}">{{ $question->fields->singular[$i] }}</option>
                                    @endif
                                @else
                                    <option value="{{ "singular-".$i }}">{{ $question->fields->singular[$i] }}</option>
                                @endif
                            @endfor
                            @for ($i=0; $i<count($question->fields->personal); $i++)
                                @if (isset($update))
                                    @if ($question->fields->personal[$i] == $questionresult->value)
                                        <option selected value="{{ "personal-".$i }}">{{ $question->fields->personal[$i] }}</option>
                                    @else
                                        <option value="{{ "personal-".$i }}">{{ $question->fields->personal[$i] }}</option>
                                    @endif
                                @else
                                    <option value="{{ "personal-".$i }}">{{ $question->fields->personal[$i] }}</option>
                                @endif
                            @endfor
                        </select>
                    </div>
                    <ul id="value-question-error" class="text-sm text-center mt-2 text-red-600 space-y-1 mx-10">

                    </ul>
                    @if (isset($update))
                        <input type="hidden" name="update" value="{{ $questionresult->id }}"/>
                    @endif
                    <input id="type" type="hidden" name="type" value="value"/>
                    <div class="w-full">
                        <div class="flex flex-col relative mt-10 mb-8 items-end">
                            <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</body>
