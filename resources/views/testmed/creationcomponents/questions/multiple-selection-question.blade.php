<body>
    <div>
        <form method="POST" id="choosequestionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                @if (isset($update))
                    {{ __("Update ".$title." question") }}
                @else
                    {{ __("Create Multiple Selection Question") }}
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
            <div class="mt-6 p-4 text-center text-gray-900">
                {{ __("Insert Text of the question") }}
            </div>
            <div class="px-10 lg:px-52 mt-2">
                @if (isset($update))
                    <x-text-input id="questiontext" value="{{ $text }}" class="text-center block mt-1 w-full" type="text" name="questiontext" required placeholder='Question Text'/>
                @else
                    <x-text-input id="questiontext" class="text-center block mt-1 w-full" type="text" name="questiontext" required placeholder='Question Text'/>
                @endif

                <ul id="questiontext-error" class="text-sm text-red-600 space-y-1 mt-2"></ul>
            </div>

            <div class="flex justify-center mt-10 relative">

                <div id="values" class="bg-white rounded-lg shadow w-60 dark:bg-gray-700">
                    <ul id="valueslist" class="max-h-52 px-3 pt-2 pb-3 overflow-y-auto text-sm" aria-labelledby="dropdownSearchButton">

                        @if (isset($update))
                            @if (isset($fields))
                                @for ($i=0; $i<count($fields); $i++)
                                    <div class="valuelistitem flex items-center p-2 rounded hover:bg-gray-100">
                                        <input disabled id="checkbox-{{ $i+1 }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <input id="checkbox-text-{{ $i+1 }}" name="checkbox{{ $i+1 }}" type="text" value="{{ $fields[$i] }}" class="w-full ms-2 mr-1 text-sm font-medium text-gray-900 rounded">
                                        <div class="flex items-center cancelitem h-7 w-9 pl-1 pr-1 mr-2">
                                            <x-carbon-trash-can class="h-5 w-5 hidden" title="delete"/>
                                        </div>
                                    </div>
                                    <ul id="checkbox-text-error-{{ $i+1 }}" class="text-sm text-red-600 space-y-1 ml-10 mb-2"></ul>
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
                @if (isset($fields))
                    <input type="hidden" id="radiolenght" name="radiolenght" value="{{ count($fields) }}"/>
                @else
                    <input type="hidden" id="radiolenght" name="radiolenght" value="0"/>
                @endif
            @else
                <input type="hidden" id="radiolenght" name="radiolenght" value="0"/>
            @endif

            <input type="hidden" id="test-id" name="testid" value=""/>
            <input type="hidden" name="questionid" value="{{ $questionid }}"/>
            <input type="hidden" id="type" value="multipleselection"/>

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
