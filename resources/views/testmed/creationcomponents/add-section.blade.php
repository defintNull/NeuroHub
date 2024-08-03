<body>
    <div>
        <form method="POST" id="sectionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __("Create Section") }}
            </div>
            <div class="mt-10 p-4 text-center text-gray-900">
                {{ __("Insert Name of the section") }}
            </div>
            <div class="px-10 md:px-60 mt-2">
                <x-text-input id="sectionname" class="text-center block mt-1 w-full" type="text" name="sectionname" required placeholder='Section Name'/>

                <ul id="sectionname-error" class="text-sm text-red-600 space-y-1 mt-2">

                </ul>
            </div>
            <input type="hidden" id="parent-type" name="type" value="">
            <input type="hidden" id="parent-id" name="id" value="">

            <div class="flex items-center justify-end mb-12 mr-32 mt-32">
                <x-primary-button class="ms-4" id="storesection">
                    {{ __('Add') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</body>
