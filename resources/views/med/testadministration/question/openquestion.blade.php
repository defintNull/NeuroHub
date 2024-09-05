<body>
    <div>
        <form id="{{ isset($update) ? 'updateform' : 'nextform' }}" method="POST" class="flex flex-col items-center">
            @csrf
            @if (isset($question))
            <div class="p-6 mt-12 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-6 p-4 break-all text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-5 p-4 break-all text-center text-gray-900">
                {{ __($question->text) }}
            </div>
            <div class="p-4 w-1/2 text-center text-gray-900">
                <label for="default-input" class="block mb-2 text-sm italic font-medium text-gray-900 dark:text-white">Question Answer:</label>
                @if (isset($update))
                    <input type="text" value="{{ $questionresult->value }}" id="default-input" name="openinput" placeholder="Answer..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @else
                    <input type="text" id="default-input" name="openinput" placeholder="Answer..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @endif
            </div>
            <ul id="open-question-error" class="text-sm text-center mt-2 text-red-600 space-y-1 mx-10">

            </ul>
            @if (isset($update))
                <input type="hidden" name="update" value="{{ $questionresult->id }}"/>
            @endif
            <input id="type" type="hidden" name="type" value="open"/>
            <div class="w-full">
                <div class="flex flex-col relative mt-10 mb-8 items-end">
                    <x-primary-button class="mr-28"> {{ __("Next") }} </x-primary-button>
                </div>
            </div>
        @endif
        </form>
    </div>
</body>
