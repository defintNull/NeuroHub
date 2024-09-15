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
            @if (isset($update))
                <input type="hidden" id="identifier" name="identifier" value="question-{{$question->question->id}}">
            @else
                <div id="identifier" class="hidden" value="question-{{$question->question->id}}"></div>
            @endif
            <form @if (isset($update)) id="updateform" @else id="scoreform" @endif method="POST">
                @csrf
                <div class="flex justify-center mt-4 relative">
                    <div id="values" class="bg-white rounded-lg shadow w-60 dark:bg-gray-700">
                        <ul id="valueslist" class="h-52 px-3 pb-3 overflow-y-auto text-sm" aria-labelledby="dropdownSearchButton">
                            <div class="items-center justify-center flex w-full">
                                <ul id="radiolist" class="w-56 grid grid-cols-2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
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

                <div class="flex-col items-center">
                    <div class="flex flex-col items-center mt-6 sm:mx-4 md:mx-0">
                        <div class="flex flex-row items-center">
                            <input id="score-enabler" name="enabler" value="1" type="checkbox"/>
                            <label for="score-enabler" class="italic ml-4">Select to enable score sistem for the question</label>
                        </div>
                    </div>
                    @if (isset($data))
                        @if ($data)
                            <div id="data" class="hidden">
                                <div id="data-type">question-value</div>
                            </div>
                        @endif
                    @endif
                    <div class="flex flex-col w-full items-end mt-8 mb-12 pr-24">
                        @if (isset($update))
                            <div class="flex flex-row items-center">
                                <button type="submit" class="back mr-4 inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Back') }}
                                </button>
                                <x-primary-button>{{ __("Save") }}</x-primary-button>
                            </div>
                        @else
                            <x-primary-button>{{ __("Next") }}</x-primary-button>
                        @endif
                    </div>
                </div>
            </form>
        @endif
    </div>
</body>
