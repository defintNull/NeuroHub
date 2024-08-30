<body>
    <div>
        @if (isset($question))
            <div class="p-6 mt-32 text-center font-semibold text-3xl text-gray-800 leading-tight">
                {{ __($question->title) }}
            </div>
            <div class="mt-10 p-4 text-center text-gray-900">
                {{ __($question->title) }}
            </div>
            <div class="mt-4 p-4 text-center text-gray-900">
                {{ __($question->text) }}
            </div>
        @endif
    </div>
</body>
