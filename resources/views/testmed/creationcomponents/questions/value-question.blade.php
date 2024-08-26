<body>
    <div>
        <form method="POST" id="choosequestionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                @if (isset($update))
                    {{ __("Update ".$title." question") }}
                @else
                    {{ __("Create Value question") }}
                @endif
            </div>
            <div class="mt-4 p-4 text-center text-gray-900">
                {{ __("Insert Text of the question") }}
            </div>
            <div class="px-10 lg:px-60 mt-2">
                @if (isset($update))
                    <x-text-input id="questiontitle" value="{{ $title }}" class="text-center block mt-1 w-full" type="text" name="questiontitle" required placeholder='Question Title'/>
                @else
                    <x-text-input id="questiontitle" class="text-center block mt-1 w-full" type="text" name="questiontitle" required placeholder='Question Title'/>
                @endif

                <ul id="questiontitle-error" class="text-sm text-red-600 space-y-1 mt-2">

                </ul>
            </div>

            <div class="flex justify-center mt-10 relative">

                <div id="values" class="bg-white rounded-lg shadow w-60 dark:bg-gray-700">
                    <ul id="valueslist" class="h-52 px-3 pb-3 overflow-y-auto text-sm" aria-labelledby="dropdownSearchButton">
                        <li>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100">
                            <label class="w-full ms-2 text-sm font-medium text-gray-900 rounded">Rapid selectors</label>
                            </div>

                            <div class="flex items-center p-2 rounded hover:bg-gray-100">
                            <input id="checkbox-rapid-1" name="checkboxrapid" type="radio" value="10" class="rapidcheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="checkbox-rapid-1" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">0-10</label>
                            </div>

                            <div class="flex items-center p-2 rounded hover:bg-gray-100">
                            <input id="checkbox-rapid-2" name="checkboxrapid" type="radio" value="20" class="rapidcheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="checkbox-rapid-2" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">0-20</label>
                            </div>

                            <div class="flex items-center p-2 rounded hover:bg-gray-100">
                            <input id="checkbox-rapid-3" name="checkboxrapid" type="radio" value="50" class="rapidcheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="checkbox-rapid-3" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">0-50</label>
                            </div>

                            <div class="flex items-center p-2 rounded hover:bg-gray-100">
                            <input id="checkbox-rapid-4" name="checkboxrapid" type="radio" value="100" class="rapidcheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="checkbox-rapid-4" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">0-100</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100">
                            <label class="w-full ms-2 text-sm font-medium text-gray-900 rounded">Single selectors</label>
                            </div>
                        </li>

                        @for ($i=0; $i<101; $i++)
                        <li>
                            <div class="singlecheck flex items-center p-2 rounded hover:bg-gray-100">
                            @if (isset($update))
                                @if (isset($fields["singular"]))
                                    @php
                                        $pass = false;
                                    @endphp
                                    @foreach ($fields["singular"] as $field)
                                        @if ($field == $i)
                                            <input checked id="checkbox-single-{{ $i }}" name="checkboxsingle{{ $i }}" type="checkbox" value="{{ $i }}" class="singlecheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            @php
                                                $pass = true;
                                            @endphp
                                            @break
                                        @endif
                                    @endforeach
                                    @if (!$pass)
                                        <input id="checkbox-single-{{ $i }}" name="checkboxsingle{{ $i }}" type="checkbox" value="{{ $i }}" class="singlecheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    @endif
                                @else
                                    <input id="checkbox-single-{{ $i }}" name="checkboxsingle{{ $i }}" type="checkbox" value="{{ $i }}" class="singlecheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                @endif
                            @else
                                <input id="checkbox-single-{{ $i }}" name="checkboxsingle{{ $i }}" type="checkbox" value="{{ $i }}" class="singlecheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            @endif
                            <label for="checkbox-single-{{ $i }}" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">{{ $i }}</label>
                            </div>
                        </li>
                        @endfor

                        @if (isset($update))
                            @if (isset($fields["personal"]))
                                @for ($i=0; $i<count($fields["personal"]); $i++)
                                    <div class="singlecheck flex items-center p-2 rounded hover:bg-gray-100">
                                        <input checked id="checkbox-personal-{{ $i+1 }}" type="checkbox" class="singlecheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <input id="checkbox-personal-text-{{ $i+1 }}" name="checkboxpersonal{{ $i+1 }}" type="text" value="{{ $fields["personal"][$i] }}" class="singlecheck w-full ms-2 mr-1 text-sm font-medium text-gray-900 rounded">
                                        <div class="flex items-center cancelitem h-7 w-9 pl-1 pr-1 mr-2">
                                            <x-carbon-trash-can class="h-5 w-5" title="delete"/>
                                        </div>
                                    </div>
                                    <ul class="text-sm text-red-600 space-y-1 ml-10 mb-2">
                                        <li id="checkbox-personal-text-error-{{ $i+1 }}" class="hidden">This field must be greater that 100</li>
                                    </ul>
                                @endfor
                            @endif
                        @endif
                        <li>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100">
                                <div class="flex py-1 items-center ps-5">
                                    <label id="addchoice" class="cursor-pointer hover:rounded-lg hover:shadow-lg py-2 px-2 hover:bg-blue-400">{{ __("+ Add choice")}}</label>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul id="values-input-error" class="text-sm mt-6 text-red-600 space-y-1 ml-10 mb-2">

                    </ul>
                </div>
            </div>

            {{-- Hidden fields --}}
            @if (isset($update))
                @if (isset($fields["personal"]))
                    <input type="hidden" id="radiolenght" name="radiolenght" value="{{ count($fields["personal"]) }}"/>
                @else
                    <input type="hidden" id="radiolenght" name="radiolenght" value="0"/>
                @endif
            @else
                <input type="hidden" id="radiolenght" name="radiolenght" value="0"/>
            @endif

            <input type="hidden" id="test-id" name="testid" value=""/>
            <input type="hidden" name="questionid" value="{{ $questionid }}"/>
            <input type="hidden" id="type" value="value"/>

            <div class="flex items-center justify-end mb-12 mr-32 mt-20">
                <x-primary-button class="ms-4 bg-gray-400 cancel">
                    {{ __('Cancel') }}
                </x-primary-button>
                @if (isset($update))
                    <x-primary-button class="ms-4" id="updatechoosequestion">
                        {{ __('Update') }}
                    </x-primary-button>
                @else
                    <x-primary-button class="ms-4" id="storechoosequestion">
                        {{ __('Add') }}
                    </x-primary-button>
                @endif
            </div>
        </form>
    </div>
</body>
