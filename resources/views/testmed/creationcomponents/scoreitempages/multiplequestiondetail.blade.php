<body>
    <div>
        @if (isset($question))
            <form id="scoreform" method="POST">
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
                <div id="identifier" class="hidden" value="question-{{$question->question->id}}"></div>
                <div class="items-center justify-center flex mt-6 w-full">
                    <ul id="radiolist" class="w-48 md:w-1/2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                        @for ($i=0; $i<$question->fields->count(); $i++)
                            <li class="multiplelistitem w-full border-b rounded-t-lg border-gray-400">
                                <div class="flex items-center ps-3">
                                    <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
                                    <label id="radio-input-{{ $i }}" class="w-full mr-4 my-3 ml-6 ms-2 text-sm font-medium break-all text-gray-900 bg-blue-100 focus:bg-white">{{ $question->fields[$i] }}</label>
                                    <select id="select-value-{{ $i }}" name="selectvalue{{ $i }}" class="selectvalue hidden mr-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-28 p-2.5">
                                        @for ($n=0; $n<100; $n++)
                                            <option value="{{$n}}"">{{$n}}</option>
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
                    <div class="flex flex-col w-full items-end mt-8 mb-12 pr-24">
                        <x-primary-button>{{ __("Next") }}</x-primary-button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</body>
