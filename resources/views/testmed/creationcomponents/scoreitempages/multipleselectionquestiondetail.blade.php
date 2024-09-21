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
            <form @if (isset($update)) id="updateform" @else id="scoreform" @endif method="POST">
                @csrf
                @if (isset($update))
                    <input type="hidden" id="identifier" name="identifier" value="question-{{$question->question->id}}">
                @else
                    <div id="identifier" class="hidden" value="question-{{$question->question->id}}"></div>
                @endif
                <div class="flex justify-center mt-10 relative w-full">
                    <ul id="valueslist" class="w-48 md:w-1/2 grid grid-cols-2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                        @for ($i=0; $i<count($question->fields); $i++)
                            <div class="valuelistitem flex items-center p-2 rounded hover:bg-gray-100">
                                <input disabled id="checkbox-{{ $i+1 }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label id="checkbox-text-{{ $i+1 }}" class="w-full ms-2 mr-1 text-sm font-medium text-gray-900 rounded">{{ $question->fields[$i] }}</label>
                                <select id="select-value-{{ $i }}" name="selectvalue{{ $i }}" class="selectvalue hidden mr-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-28 p-2.5">
                                    @for ($n=0; $n<100; $n++)
                                        <option value="{{$n}}">{{$n}}</option>
                                    @endfor
                                </select>
                            </div>
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
                    <div class="opacity-50">
                        <p class="text-center text-xl mt-8">{{ __("Jump section:") }}</p>
                        <div class="flex flex-col items-center mt-2 sm:mx-4 md:mx-0">
                            <div class="flex flex-row items-center">
                                @if (isset($jump))
                                    @if ($jump)
                                    <input disabled checked id="jump-enabler" name="jump" value="1" type="checkbox"/>
                                    @else
                                    <input disabled id="jump-enabler" name="jump" value="1" type="checkbox"/>
                                    @endif
                                @else
                                <input disabled id="jump-enabler" name="jump" value="1" type="checkbox"/>
                                @endif
                                <label for="jump-enabler" class="italic ml-4">Select to enable jump sistem for the question</label>
                            </div>
                        </div>
                        <ul id="jump-enabler-error" class="text-sm text-center mt-2 break-all hidden text-red-600 space-y-1 mb-2">
                            <li>{{ __("Anoter question or parent section already has jump operation") }}</li>
                        </ul>
                    </div>
                    <div id="jump-container" class="hidden mt-4">
                        <div class="flex flex-col items-center">
                            <div class="flex flex-col items-center mt-4 justify-center w-2/3">
                                <div class="grid grid-cols-4 items-center w-full">
                                    <p class="text-center col-span-2 text-lg">{{ __("Range of values") }}</p>
                                    <p class="text-center col-span-2 text-lg">{{ __("Section Jump") }}</p>
                                </div>
                                @if (isset($jump))
                                    @if ($jump)
                                    <input id="jumplenght" name="jumplenght" value="{{ $question->jump->count() }}" class="hidden"/>
                                    @else
                                    <input id="jumplenght" name="jumplenght" value="1" class="hidden"/>
                                    @endif
                                @else
                                <input id="jumplenght" name="jumplenght" value="1" class="hidden"/>
                                @endif
                                @if (isset($jump))
                                    @if ($jump)
                                        @for ($i=0; $i<$question->jump->count(); $i++)
                                            <div class="rangecontainer grid grid-cols-4 items-start w-full">
                                                <div class="flex flex-col items-center mt-4 justify-center">
                                                    <div class="flex flex-row items-center justify-center">
                                                        <p class="mr-4">From:</p>
                                                        <input type="text" name="from-{{ $i+1 }}" value="{{ $question->jump[$i][0] }}" class="rangeinput rounded-lg w-20"/>
                                                    </div>
                                                    <ul id="from-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                                        <li>{{ __("Required") }}</li>
                                                    </ul>
                                                </div>
                                                <div class="flex flex-col items-center mt-4 justify-center">
                                                    <div class="flex flex-row items-center justify-center">
                                                        <p class="mr-4">To:</p>
                                                        <input type="text" name="to-{{ $i+1 }}" value="{{ $question->jump[$i][1] }}" class="rangeinput rounded-lg w-20"/>
                                                    </div>
                                                    <ul id="to-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                                        <li>{{ __("Required") }}</li>
                                                    </ul>
                                                </div>
                                                <div class="flex flex-row col-span-2 mt-4 border-l border-gray-400 items-center justify-center">
                                                    <p class="mr-4">Section:</p>
                                                    <select id="jumpinterval{{ $i+1 }}" name="jumpinterval{{ $i+1 }}" class="rounded-lg">
                                                        @for ($n=0; $n<count($sectionlist); $n++)
                                                            @if (isset($jump))
                                                                @if ($sectionlist[$n][0] == $question->jump[$i][2])
                                                                <option selected value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                                                @else
                                                                <option value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                                                @endif
                                                            @else
                                                            <option value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        @endfor
                                    @else
                                        <div class="rangecontainer grid grid-cols-4 items-start w-full">
                                            <div class="flex flex-col items-center mt-4 justify-center">
                                                <div class="flex flex-row items-center justify-center">
                                                    <p class="mr-4">From:</p>
                                                    <input type="text" name="from-1" value="" class="rangeinput rounded-lg w-20"/>
                                                </div>
                                                <ul id="from-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                                    <li>{{ __("Required") }}</li>
                                                </ul>
                                            </div>
                                            <div class="flex flex-col items-center mt-4 justify-center">
                                                <div class="flex flex-row items-center justify-center">
                                                    <p class="mr-4">To:</p>
                                                    <input type="text" name="to-1" value="" class="rangeinput rounded-lg w-20"/>
                                                </div>
                                                <ul id="to-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                                    <li>{{ __("Required") }}</li>
                                                </ul>
                                            </div>
                                            <div class="flex flex-row col-span-2 mt-4 border-l border-gray-400 items-center justify-center">
                                                <p class="mr-4">Section:</p>
                                                <select id="jumpinterval1" name="jumpinterval1" class="rounded-lg">
                                                    @for ($n=0; $n<count($sectionlist); $n++)
                                                        <option value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                <div class="rangecontainer grid grid-cols-4 items-start w-full">
                                    <div class="flex flex-col items-center mt-4 justify-center">
                                        <div class="flex flex-row items-center justify-center">
                                            <p class="mr-4">From:</p>
                                            <input type="text" name="from-1" value="" class="rangeinput rounded-lg w-20"/>
                                        </div>
                                        <ul id="from-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                            <li>{{ __("Required") }}</li>
                                        </ul>
                                    </div>
                                    <div class="flex flex-col items-center mt-4 justify-center">
                                        <div class="flex flex-row items-center justify-center">
                                            <p class="mr-4">To:</p>
                                            <input type="text" name="to-1" value="" class="rangeinput rounded-lg w-20"/>
                                        </div>
                                        <ul id="to-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                            <li>{{ __("Required") }}</li>
                                        </ul>
                                    </div>
                                    <div class="flex flex-row col-span-2 mt-4 border-l border-gray-400 items-center justify-center">
                                        <p class="mr-4">Section:</p>
                                        <select id="jumpinterval1" name="jumpinterval1" class="rounded-lg">
                                            @for ($n=0; $n<count($sectionlist); $n++)
                                                <option value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 items-center mt-8">
                                <button id="addrange" class="rounded-lg bg-blue-200 px-5 py-2 mr-10 hover:bg-blue-500">
                                    {{ __("Add") }}
                                </button>
                                <button id="removerange" class="rounded-lg bg-blue-200 px-5 py-2 hover:bg-blue-500">
                                    {{ __("Remove") }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @if (isset($data))
                        @if ($data)
                            <div id="data" class="hidden">
                                <div id="data-type">question-multiple-selection</div>
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
