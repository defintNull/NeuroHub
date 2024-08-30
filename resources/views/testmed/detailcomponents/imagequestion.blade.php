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

            @if (isset($images))
                <div class="items-center justify-center flex flex-col mt-10 w-full">
                    <ul id="radiolist" class="w-48 md:w-1/2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                        @for ($i=0; $i<count($images); $i++)
                            <li class="imagelistitem w-full border-b rounded-t-lg border-gray-400">
                                <div class="flex items-center mt-2 ps-3">
                                    <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
                                    <img id="image-preview-{{ $i }}" class="ml-4 w-32 h-32 object-cover rounded border-2 border-gray-300" src="{{ $images[$i] }}" alt="Image Preview">
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>
            @endif
        @endif
    </div>
</body>
