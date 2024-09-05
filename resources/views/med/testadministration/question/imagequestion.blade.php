<body>
    <div>
        @if (isset($question))
            <form id="{{ isset($update) ? 'updateform' : 'nextform' }}" method="POST">
                @csrf
                <div class="p-6 mt-12 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                    {{ __($question->title) }}
                </div>
                <div class="mt-2 p-4 break-all text-center text-gray-900">
                    {{ __($question->title) }}
                </div>
                <div class="mt-2 p-4 break-all text-center text-gray-900">
                    {{ __($question->text) }}
                </div>

                @if (isset($images))
                    <div class="items-center justify-center flex flex-col mt-10 w-full">
                        <ul id="radiolist" class="grid grid-cols-3 w-2/3 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                            @for ($i=0; $i<count($images); $i++)
                                <li class="imagelistitem w-full border-b rounded-t-lg border-gray-400">
                                    <div class="flex items-center justify-center mt-2 mb-2">
                                        @if (isset($update))
                                            @if ($i == $position)
                                                <input checked id="radio-image-{{ $i }}" type="radio" name="imageradio" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                            @else
                                                <input id="radio-image-{{ $i }}" type="radio" name="imageradio" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                            @endif
                                        @else
                                            <input id="radio-image-{{ $i }}" type="radio" name="imageradio" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                        @endif
                                        <label for="radio-image-{{ $i }}">
                                            <img id="image-preview-{{ $i }}" class="ml-4 w-40 h-40 object-contain rounded" src="{{ $images[$i] }}" alt="Image Preview">
                                        </label>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>
                @endif
                <ul id="image-question-error" class="text-sm text-center mt-2 text-red-600 space-y-1 mx-10">

                </ul>
                @if (isset($update))
                    <input type="hidden" name="update" value="{{ $questionresult->id }}"/>
                @endif
                <input id="type" type="hidden" name="type" value="image"/>
                <div class="flex flex-col relative mt-10 mb-8 items-end">
                    <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                </div>
            </form>
        @endif
    </div>
</body>
