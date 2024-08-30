<body>
    <div>
        @if (isset($question))
            <div class="p-6 mt-20 text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-6 p-4 text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-2 p-4 text-center text-gray-900">
                {{ __($question->text) }}
            </div>

            <div class="flex justify-center mt-10 relative">
                <div id="values" class="rounded-lg shadow-lg bg-blue-100 w-60 dark:bg-gray-700">
                    <ul id="valueslist" class="max-h-52 px-3 pt-2 pb-3 overflow-y-auto text-sm" aria-labelledby="dropdownSearchButton">
                        @for ($i=0; $i<count($question->fields); $i++)
                        <div class="valuelistitem flex items-center p-2 rounded hover:bg-gray-100">
                            <input disabled id="checkbox-{{ $i+1 }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label id="checkbox-text-{{ $i+1 }}" class="w-full ms-2 mr-1 text-sm font-medium text-gray-900 rounded">{{ $question->fields[$i] }}</label>
                        </div>
                    @endfor
                    </ul>
                </div>
            </div>
        @endif
    </div>
</body>
