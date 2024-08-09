<body>
    <div>
        <form method="POST" id="testform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                @if (isset($update))
                    {{ __("Update ".$name." test") }}
                @endif
            </div>
            <div class="mt-10 p-4 text-center text-gray-900">
                {{ __("Insert Name of the test") }}
            </div>
            <div class="px-10 md:px-60 mt-2">
                @if (isset($update))
                    <x-text-input id="testname" value="{{ $name }}" class="text-center block mt-1 w-full" type="text" name="testname" required placeholder='Test Name'/>
                @endif

                <ul id="testname-error" class="text-sm text-red-600 space-y-1 mt-2"></ul>
            </div>
            @if (isset($update))
                <input type="hidden" id="test-id" name="testid" value="{{ $testid }}">
            @endif

            <div class="flex items-center justify-end mb-12 mr-32 mt-32">
                @if (isset($update))
                    <x-primary-button class="ms-4" id="updatetest">
                        {{ __('Update') }}
                    </x-primary-button>
                @endif
                <x-primary-button class="ms-4 bg-gray-400 cancel">
                    {{ __('Cancel') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</body>
