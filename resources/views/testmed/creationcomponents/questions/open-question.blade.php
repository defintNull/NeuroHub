<body>
    <div>
        <form method="POST" id="choosequestionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                @if (isset($update))
                    {{ __("Update ".$title." question") }}
                @else
                    {{ __("Create Open question") }}
                @endif
            </div>
            <div class="mt-6 p-4 text-center text-gray-900">
                {{ __("Insert Title of the question") }}
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

            {{-- Hidden fields --}}
            <input type="hidden" id="test-id" name="testid" value=""/>
            <input type="hidden" name="questionid" value="{{ $questionid }}"/>
            <input type="hidden" id="type" value="open"/>

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
