<body>
    <div>
        @if (isset($question) && isset($questionresult))
            <div class="p-6 mt-12 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-2 p-4 break-all text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-2 p-4 break-all text-center text-gray-900">
                {{ __($question->text) }}
            </div>

            <div class="items-center justify-center flex mt-6 mb-8 w-full">
                <ul id="radiolist" class="w-48 md:w-1/2 grid grid-cols-2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                    @for ($i=0; $i<$question->fields->count(); $i=$i+2)
                        <li class="multiplelistitem flex items-center border-b border-r rounded-tl-lg border-gray-400">
                            <div class="flex grow items-center break-all ps-3">
                                @if (in_array($question->fields[$i], $questionresult->value->getArrayCopy()))
                                    <input checked id="checkbox-{{ $i }}" type="checkbox" name="checkbox[]" value="{{ $i }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                @else
                                    <input disabled id="checkbox-{{ $i }}" type="checkbox" name="checkbox[]" value="{{ $i }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                @endif
                                <label for="checkbox-{{ $i }}" class="ms-2 py-3 mr-2 w-full break-all text-sm font-medium text-gray-900">{{ $question->fields[$i] }}</label>
                            </div>
                        </li>
                        @if ($i+1 < $question->fields->count())
                            <li class="multiplelistitem flex items-center border-b border-r rounded-tl-lg border-gray-400">
                                <div class="flex grow items-center break-all ps-3">
                                    @if (in_array($question->fields[$i+1], $questionresult->value->getArrayCopy()))
                                        <input checked id="checkbox-{{ $i+1 }}" type="checkbox" name="checkbox[]" value="{{ $i+1 }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    @else
                                        <input disabled id="checkbox-{{ $i+1 }}" type="checkbox" name="checkbox[]" value="{{ $i+1 }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    @endif
                                    <label for="checkbox-{{ $i+1 }}" class="ms-2 py-3 mr-2 w-full break-all text-sm font-medium text-gray-900">{{ $question->fields[$i+1] }}</label>
                                </div>
                            </li>
                        @endif
                    @endfor
                </ul>
            </div>
        @endif
    </div>
</body>
