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

                <div class="items-center justify-center flex mt-6 w-full">
                    <ul id="radiolist" class="w-48 md:w-1/2 grid grid-cols-2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                        @for ($i=0; $i<$question->fields->count(); $i=$i+2)
                            <li class="multiplelistitem flex items-center border-b border-r rounded-tl-lg border-gray-400">
                                <div class="flex grow items-center break-all ps-3">
                                    @if (isset($update))
                                        @if ($question->fields[$i] == $questionresult->value)
                                            <input checked id="radio-input-{{ $i }}" type="radio" name="radioinput" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                        @else
                                            <input id="radio-input-{{ $i }}" type="radio" name="radioinput" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                        @endif
                                    @else
                                        <input id="radio-input-{{ $i }}" type="radio" name="radioinput" value="{{ $i }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                    @endif
                                    <label id="radio-label-{{ $i }}" for="radio-input-{{ $i }}" class="w-full py-3 ml-1 pl-3 ps-2 pr-2 text-sm font-medium text-gray-900 bg-blue-100 focus:bg-white">{{ $question->fields[$i] }}</label>
                                </div>
                            </li>
                            @if ($i+1 < $question->fields->count())
                                <li class="multiplelistitem flex items-center border-b border-r rounded-tl-lg border-gray-400">
                                    <div class="flex grow items-center break-all ps-3">
                                        @if (isset($update))
                                            @if ($question->fields[$i+1] == $questionresult->result)
                                                <input checked id="radio-input-{{ $i+1 }}" type="radio" name="radioinput" value="{{ $i+1 }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                            @else
                                                <input id="radio-input-{{ $i+1 }}" type="radio" name="radioinput" value="{{ $i+1 }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                            @endif
                                        @else
                                            <input id="radio-input-{{ $i+1 }}" type="radio" name="radioinput" value="{{ $i+1 }}" class="w-4 h-4 text-blue-600 focus:ring-blue-600 focus:ring-2 bg-blue-300 border-gray-500">
                                        @endif
                                        <label id="radio-label-{{ $i+1 }}" for="radio-input-{{ $i+1 }}" class="w-full py-3 ml-1 ps-2 pr-2 text-sm font-medium text-gray-900 bg-blue-100 focus:bg-white">{{ $question->fields[$i+1] }}</label>
                                    </div>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </div>
                <ul id="multiple-question-error" class="text-sm text-center mt-2 text-red-600 space-y-1 mx-10">

                </ul>
                @if (isset($update))
                    <input type="hidden" name="update" value="{{ $questionresult->id }}"/>
                @endif
                <input id="type" type="hidden" name="type" value="multiple"/>
                <div class="flex flex-col relative mt-10 mb-8 items-end">
                    @if (isset($update))
                        <div class="flex flex-row justify-center items-center mt-8 mb-12">
                            <button type="button" class="inline-flex cancel items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __("Cancel") }}
                            </button>
                            <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                        </div>
                    @else
                        <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                    @endif
                </div>
            </form>
        @endif
    </div>
</body>
