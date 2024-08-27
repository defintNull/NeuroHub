<body>
    <div>
        <form method="POST" id="questionform">
            @csrf
            <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __("Create Question") }}
            </div>
            <div class="mt-10 p-4 text-center text-gray-900">
                {{ __("Choose the question type...") }}
            </div>
            <div class="flex w-full justify-center mt-10">
                <div class="flex justify-center w-3/4">
                    <div class="flex w-1/3 mr-4 items-center ps-4 border border-gray-200 rounded">
                        <input checked id="multiple-radio" type="radio" value="1" name="radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="multiple-radio" class="w-full py-4 ms-2 text-sm font-medium text-gray-900"> {{ __("Multiple Choice") }} </label>
                    </div>
                    <div class="flex w-1/3 mr-4 items-center ps-4 border border-gray-200 rounded">
                        <input id="value-radio" type="radio" value="2" name="radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="value-radio" class="w-full py-4 ms-2 text-sm font-medium text-gray-900">{{ __("Value Chocice") }}</label>
                    </div>
                    <div class="flex w-1/3 items-center ps-4 border border-gray-200 rounded">
                        <input id="open-radio" type="radio" value="3" name="radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="open-radio" class="w-full py-4 ms-2 text-sm font-medium text-gray-900">{{ __("Open Question") }}</label>
                    </div>
                </div>
            </div>
            <div class="flex w-full justify-center mt-5">
                <div class="flex justify-center w-2/4">
                    <div class="flex w-1/2 mr-4 items-center ps-4 border border-gray-200 rounded">
                        <input id="selection-radio" type="radio" value="4" name="radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="selection-radio" class="w-full py-4 ms-2 text-sm font-medium text-gray-900"> {{ __("Multiple Selection") }} </label>
                    </div>
                    <div class="flex w-1/2 items-center ps-4 border border-gray-200 rounded">
                        <input id="image-radio" type="radio" value="5" name="radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="image-radio" class="w-full py-4 ms-2 text-sm font-medium text-gray-900">{{ __("Image Choice") }}</label>
                    </div>
                </div>
            </div>
            <input type="hidden" id="test-id" name="testid" value="">
            <input type="hidden" id="parent-id" name="id" value="">

            <div class="flex items-center justify-end mb-12 mr-32 mt-12">
                <x-primary-button class="ms-4 bg-gray-400 cancel">
                    {{ __('Cancel') }}
                </x-primary-button>
                <x-primary-button class="ms-4" id="storequestion">
                    {{ __('Add') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</body>
