<body>
    <div>
        @if (isset($question))
            <div class="p-6 mt-16 text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-6 p-4 text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-2 p-4 text-center text-gray-900">
                {{ __($question->text) }}
            </div>

            <div class="items-center justify-center flex mt-6 mb-8 w-full">
                <ul id="radiolist" class="w-48 grid grid-cols-2 md:w-1/2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                    @for ($i=0; $i<$question->fields->count(); $i++)
                        <li class="multiplelistitem w-full border-b rounded-t-lg border-gray-400">
                            <div class="flex items-center ps-3">
                                <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
                                <label id="radio-input-{{ $i }}" class="w-full my-3 ml-6 ms-2 text-sm font-medium text-gray-900 bg-blue-100 focus:bg-white">{{ $question->fields[$i] }}</label>
                                @if ($scores)
                                    <p class="mr-2 px-2 py-1 bg-blue-200 rounded-lg">{{ $scores[$i] }}</p>
                                @endif
                            </div>
                        </li>
                    @endfor
                </ul>
            </div>
        @endif
    </div>
</body>
