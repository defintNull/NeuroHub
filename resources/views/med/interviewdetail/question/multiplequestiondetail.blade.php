<body>
    <div>
        @if (isset($questionresult) && isset($question))
            <div class="p-6 mt-12 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-2 p-4 break-all text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-2 p-4 break-all text-center text-gray-900">
                {{ __($question->text) }}
            </div>

            <div class="items-center justify-center flex mt-6 w-full mb-8">
                <ul id="radiolist" class="w-48 md:w-1/2 grid grid-cols-2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                    @for ($i=0; $i<$question->fields->count(); $i=$i+2)
                        <li class="multiplelistitem flex items-center border-b border-r rounded-tl-lg border-gray-400">
                            <div class="flex grow items-center break-all ps-3">
                                @if ($question->fields[$i] == $questionresult->value)
                                    <input checked disabled id="radio-input-{{ $i }}" type="radio" name="radioinput" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                @else
                                    <input disabled id="radio-input-{{ $i }}" type="radio" name="radioinput" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                @endif
                                <label id="radio-label-{{ $i }}" for="radio-input-{{ $i }}" class="w-full py-3 ml-1 pl-3 ps-2 pr-2 text-sm font-medium text-gray-900 bg-blue-100 focus:bg-white">{{ $question->fields[$i] }}</label>
                            </div>
                        </li>
                        @if ($i+1 < $question->fields->count())
                            <li class="multiplelistitem flex items-center border-b border-r rounded-tl-lg border-gray-400">
                                <div class="flex grow items-center break-all ps-3">
                                    @if ($question->fields[$i+1] == $questionresult->value)
                                        <input checked disabled id="radio-input-{{ $i+1 }}" type="radio" name="radioinput" value="{{ $i+1 }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                    @else
                                        <input disabled id="radio-input-{{ $i+1 }}" type="radio" name="radioinput" value="{{ $i+1 }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                    @endif
                                    <label id="radio-label-{{ $i+1 }}" for="radio-input-{{ $i+1 }}" class="w-full py-3 ml-1 ps-2 pr-2 text-sm font-medium text-gray-900 bg-blue-100 focus:bg-white">{{ $question->fields[$i+1] }}</label>
                                </div>
                            </li>
                        @endif
                    @endfor
                </ul>
            </div>
        @endif
    </div>
</body>
