<body>
    <div>
        <form method="POST" id="choosequestionform" enctype="multipart/form-data">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                @if (isset($update))
                    {{ __("Update ".$title." question") }}
                @else
                    {{ __("Create Image question") }}
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

            <div class="items-center justify-center flex flex-col mt-10 w-full">
                <p class="italic text-xs mb-1"> {{ __("Supported types:jpeg,png,jpg,gif,svg") }} </p>
                <ul id="radiolist" class="w-48 md:w-1/2 text-sm font-medium text-gray-900 border rounded-lg bg-blue-100 border-gray-400">
                    @if (isset($images))
                        @for ($i=0; $i<count($images); $i++)
                            <li class="imagelistitem w-full border-b rounded-t-lg border-gray-400">
                                <div class="flex items-center mt-2 ps-3">
                                    <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
                                    <div class="flex items-center">
                                        <label for="image-input-{{ $i }}" id="image-input-label-{{ $i }}" class="cursor-pointer ml-2 bg-blue-200 font-medium py-2 px-4 rounded hover:bg-blue-300">
                                            Choose File
                                        </label>
                                        <input type="file" id="image-input-{{ $i }}" name="imageinput{{ $i }}" accept="image/*" class="imageinput hidden" required>
                                        <span id="file-name-{{ $i }}" class="hidden ml-3 text-gray-600 text-sm">{{ $images[$i][1] }}</span>
                                    </div>
                                    <img id="image-preview-{{ $i }}" class="ml-4 w-32 h-32 object-cover rounded border-2 border-gray-300" src="{{ $images[$i][2] }}" alt="Image Preview">
                                    <div class="flex items-center cancelitem h-9 w-9 pl-2 pr-2 mr-2">
                                        <x-carbon-trash-can class="h-6 w-6 hidden" title="delete"/>
                                    </div>
                                </div>
                                <ul id="image-input-error-{{ $i }}" class="text-sm text-red-600 space-y-1 ml-10 mb-2"></ul>
                                <input id="old-image-{{ $i }}" name="imageinput{{ $i }}" value="old-{{ $i }}" class="hidden" />
                            </li>
                        @endfor
                    @endif
                    <li class="w-full rounded-t-lg border-gray-400">
                        <div class="flex py-1 items-center ps-5">
                            <label id="addchoice" class="cursor-pointer hover:rounded-lg hover:shadow-lg py-2 px-2 hover:bg-blue-400">{{ __("+ Add choice")}}</label>
                        </div>
                    </li>
                </ul>
                <ul id="image-field-error" class="text-sm mt-2 text-red-600 space-y-1 ml-10 mb-2"></ul>
            </div>

            {{-- Hidden fields --}}
            <input type="hidden" id="radiolenght" name="radiolenght" value=""/>
            <input type="hidden" id="test-id" name="testid" value=""/>
            <input type="hidden" name="questionid" value="{{ $questionid }}"/>
            <input type="hidden" id="type" value="image"/>

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
