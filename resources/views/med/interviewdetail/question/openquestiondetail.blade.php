<body>
    <div class="flex flex-col items-center">
        @if (isset($question) && isset($questionresult))
            <div class="p-6 mt-12 break-all text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-6 p-4 break-all text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-5 p-4 break-all text-center text-gray-900">
                {{ __($question->text) }}
            </div>
            @if ($questionresult == "jump")
                <div class="p-2 italic font-semibold text-lg break-all text-center text-gray-900">
                    {{ __("Jumped") }}
                </div>
            @endif
            <div class="p-4 w-1/2 text-center mb-8 text-gray-900">
                <label for="default-input" class="block mb-2 text-sm italic font-medium text-gray-900 dark:text-white">Question Answer:</label>
                @if ($questionresult == "jump")
                    <p>{{ Jumped }}</p>
                @else
                    <p>{{ $questionresult->value }}</p>
                @endif
            </div>
        @endif
    </div>
</body>
