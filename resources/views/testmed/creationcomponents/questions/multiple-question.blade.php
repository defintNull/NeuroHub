<body>
    <div>
        <form method="POST" id="choosequestionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                @if (isset($update))
                    {{ __("Update ".$title." question") }}
                @else
                    {{ __("Create Multiple question") }}
                @endif
            </div>
            <div class="mt-6 p-4 text-center text-gray-900">
                {{ __("Insert Text of the question") }}
            </div>
            <div class="px-10 lg:px-52 mt-2">
                @if (isset($update))
                    <x-text-input id="questiontitle" value="{{ $title }}" class="text-center block mt-1 w-full" type="text" name="questiontitle" required placeholder='Question Title'/>
                @else
                    <x-text-input id="questiontitle" class="text-center block mt-1 w-full" type="text" name="questiontitle" required placeholder='Question Title'/>
                @endif

                <ul id="questiontitle-error" class="text-sm text-red-600 space-y-1 mt-2"></ul>
            </div>

            <div class="items-center justify-center flex mt-10 w-full">
                <ul id="radiolist" class="w-48 md:w-1/2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                    @if (isset($fields))
                        @for ($i=0; $i<$fields->count(); $i++)
                            <li class="multiplelistitem w-full border-b rounded-t-lg border-gray-400">
                                <div class="flex items-center ps-3">
                                    <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
                                    <input type="text" value="{{ $fields[$i] }}" id="radio-input-{{ $i }}" name="radioinput{{ $i }}" class="w-full my-3 ml-6 ms-2 text-sm font-medium text-gray-900 bg-blue-100 focus:bg-white"/>
                                    <div class="flex items-center cancelitem h-9 w-9 pl-2 pr-2 mr-2">
                                        <x-carbon-trash-can class="h-6 w-6 hidden" title="delete"/>
                                    </div>
                                </div>
                                <ul id="radio-input-error-{{ $i }}" class="text-sm text-red-600 space-y-1 ml-10 mb-2"></ul>
                            </li>
                        @endfor
                    @endif
                    <li class="w-full rounded-t-lg border-gray-400">
                        <div class="flex py-1 items-center ps-5">
                            <label id="addchoice" class="cursor-pointer hover:rounded-lg hover:shadow-lg py-2 px-2 hover:bg-blue-400">{{ __("+ Add choice")}}</label>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Hidden fields --}}
            <input type="hidden" id="radiolenght" name="radiolenght" value=""/>
            <input type="hidden" id="test-id" name="testid" value=""/>
            <input type="hidden" name="questionid" value="{{ $questionid }}"/>
            <input type="hidden" id="type" value="multiple"/>

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
