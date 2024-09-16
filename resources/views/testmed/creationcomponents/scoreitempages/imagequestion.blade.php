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
                @if (isset($images))
                    <div class="items-center justify-center flex flex-col mt-10 w-full">
                        <ul id="radiolist" class="grid grid-cols-3 w-3/4 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                            @for ($i=0; $i<count($images); $i++)
                                <li class="imagelistitem w-full border-b rounded-t-lg border-gray-400">
                                    <div class="flex items-center justify-center mt-2 mb-2 px-2">
                                        <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
                                        <img id="image-preview-{{ $i }}" class="ml-4 w-32 h-32 object-cover rounded border-2 border-gray-300" src="{{ $images[$i] }}" alt="Image Preview">
                                        <select id="select-value-{{ $i }}" name="selectvalue{{ $i }}" class="selectvalue hidden ml-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-24 p-2.5">
                                            @for ($n=0; $n<100; $n++)
                                                <option value="{{$n}}">{{$n}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>
                @endif

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
                                <div id="data-type">question-image</div>
                                <div id="scores">{{ json_encode($scores) }}</div>
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
