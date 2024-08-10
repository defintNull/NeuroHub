<body>
    <div>
        <form method="POST" id="sectionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                @if (isset($update))
                    {{ __("Update ".$name." section") }}
                @else
                    {{ __("Create Section") }}
                @endif
            </div>
            <div class="mt-10 p-4 text-center text-gray-900">
                {{ __("Insert Name of the section") }}
            </div>
            <div class="px-10 md:px-60 mt-2">
                @if (isset($update))
                    <x-text-input id="sectionname" value="{{ $name }}" class="text-center block mt-1 w-full" type="text" name="sectionname" required placeholder='Section Name'/>
                @else
                    <x-text-input id="sectionname" class="text-center block mt-1 w-full" type="text" name="sectionname" required placeholder='Section Name'/>
                @endif

                <ul id="sectionname-error" class="text-sm text-red-600 space-y-1 mt-2">

                </ul>
            </div>
            @if (isset($update))
                <input type="hidden" id="section-id" name="sectionid" value="{{ $sectionid }}">
            @else
                <input type="hidden" id="test-id" name="testid" value="">
                <input type="hidden" id="parent-type" name="type" value="">
                <input type="hidden" id="parent-id" name="id" value="">
            @endif

            <div class="flex items-center justify-end mb-12 mr-32 mt-32">
                @if (isset($update))
                    <x-primary-button class="ms-4" id="updatesection">
                        {{ __('Update') }}
                    </x-primary-button>
                @else
                    <x-primary-button class="ms-4" id="storesection">
                        {{ __('Add') }}
                    </x-primary-button>
                @endif
                <x-primary-button class="ms-4 bg-gray-400 cancel">
                    {{ __('Cancel') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</body>
