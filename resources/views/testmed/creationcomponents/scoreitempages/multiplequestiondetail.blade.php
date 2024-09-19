<body>
    <div>
        @if (isset($question))
            <form @if (isset($update)) id="updateform" @else id="scoreform" @endif method="POST">
                @csrf
                <div class="p-6 mt-12 text-center break-all font-semibold text-3xl text-gray-800 leading-tight">
                    {{ __($question->title) }}
                </div>
                <div class="mt-6 p-4 text-center break-all text-gray-900">
                    {{ __($question->title) }}
                </div>
                <div class="mt-2 p-4 text-center break-all text-gray-900">
                    {{ __($question->text) }}
                </div>
                @if (isset($update))
                    <input type="hidden" id="identifier" name="identifier" value="question-{{$question->question->id}}">
                @else
                    <div id="identifier" class="hidden" value="question-{{$question->question->id}}"></div>
                @endif
                <div class="items-center justify-center flex mt-6 w-full">
                    <ul id="radiolist" class="w-48 md:w-1/2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                        @for ($i=0; $i<$question->fields->count(); $i++)
                            <li class="multiplelistitem w-full border-b rounded-t-lg border-gray-400">
                                <div class="flex items-center ps-3">
                                    <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
                                    <label id="radio-input-{{ $i }}" class="w-full mr-4 my-3 ml-6 ms-2 text-sm font-medium break-all text-gray-900 bg-blue-100 focus:bg-white">{{ $question->fields[$i] }}</label>
                                    <select id="select-value-{{ $i }}" name="selectvalue{{ $i }}" class="selectvalue hidden mr-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-28 p-2.5">
                                        @for ($n=0; $n<100; $n++)
                                            <option value="{{$n}}">{{$n}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>

                <div class="flex-col items-center">
                    <div class="flex flex-col items-center mt-6 sm:mx-4 md:mx-0">
                        <div class="flex flex-row items-center">
                            <input id="score-enabler" name="enabler" value="1" type="checkbox"/>
                            <label for="score-enabler" class="italic ml-4">Select to enable score sistem for the question</label>
                        </div>
                    </div>
                    <div>
                        <p class="text-center text-xl mt-8">{{ __("Jump section:") }}</p>
                        <div class="flex flex-col items-center mt-2 sm:mx-4 md:mx-0">
                            <div class="flex flex-row items-center">
                                <input id="jump-enabler" class="noblock" name="jump" value="1" type="checkbox"/>
                                <label for="jump-enabler" class="italic ml-4">Select to enable jump sistem for the question</label>
                            </div>
                        </div>
                        <ul id="jump-enabler-error" class="text-sm text-center mt-2 break-all hidden text-red-600 space-y-1 mb-2">
                            <li>{{ __("Anoter question or parent section already has jump operation") }}</li>
                        </ul>
                    </div>
                    <div id="jump-container" class="hidden mt-4">
                        <div class="flex flex-col items-center">
                            <div class="grid grid-cols-{{ $question->fields->count()<4 ? $question->fields->count() : 4 }} items-center w-5/6">
                                @for ($i=0; $i<$question->fields->count(); $i++)
                                    <div class="flex flex-row items-center justify-center">
                                        <p class="mr-4">R{{ $i+1 }}:</p>
                                        <select id="jumpselect{{ $i }}" name="jumpselect{{ $i }}" class="rounded-lg">
                                            @for ($n=0; $n<count($sectionlist); $n++)
                                                <option value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @if (isset($data))
                        @if ($data)
                            <div id="data" class="hidden">
                                <div id="data-type">question-multiple</div>
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
