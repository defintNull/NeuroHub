<body>
    <div>
        <form method="POST" id="choosequestionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __("Create Multiple question") }}
            </div>
            <div class="mt-10 p-4 text-center text-gray-900">
                {{ __("Insert Text of the question") }}
            </div>
            <div class="px-10 md:px-60 mt-2">
                <x-text-input id="questiontitle" class="text-center block mt-1 w-full" type="text" name="questiontitle" required placeholder='Question Title'/>

                <ul id="questiontitle-error" class="text-sm text-red-600 space-y-1 mt-2">

                </ul>
            </div>

            {{-- Hidden fields --}}
            <input type="hidden" name="questionid" value="{{ $questionid }}"/>
            <div type="hidden" id="type" value="{{ $questiontype }}">

            <div class="flex items-center justify-end mb-12 mr-32 mt-32">
                <x-primary-button class="ms-4 bg-gray-400 cancel">
                    {{ __('Cancel') }}
                </x-primary-button>
                <x-primary-button class="ms-4" id="storechoosequestion">
                    {{ __('Add') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</body>
