<body>
    <div>
        <div class="p-6 {{ $formula || $conversion ? 'mt-10' : 'mt-32' }} text-center font-semibold text-3xl text-gray-800 leading-tight">
            @if (isset($section))
                {{ __($section->name) }}
            @endif
        </div>
        @if ($formula || $conversion)
            <div class="flex flex-col items-center mt-6 mb-8">
                <div id="score" class="flex flex-col w-3/4 items-center">
                    @if ($conversion)
                        <div id="conversiongrid" class="grid grid-cols-2 items-start text-center rounded-lg bg-blue-100 pt-2 pb-2 w-80 max-h-96 overflow-y-auto">
                            <p class="border-r border-b border-gray-400 ml-2">{{ __("Value") }}</p>
                            <p class="border-l border-b border-gray-400 mr-2">{{ __("Conversion") }}</p>
                            @foreach ($conversion as $key => $value)
                                <div class="px-2 pt-2 border-r border-gray-400">
                                    <input disabled type="text" value="{{ $key }}" class="rounded-md w-full" />
                                </div>
                                <div class="px-2 pt-2 border-l border-gray-400">
                                    <input disabled type="text" value="{{ $value }}" class="rounded-md w-full" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if ($formula)
                        <p class="italic text-xs mt-6 mb-4">{{ __("Valid simbols: + * / - ( ) Qn(for section with question) Sn(for section with subsection) (n is a natural number > 1), no white spaces") }}</p>
                        <label for="formula" class="mb-2 w-full text-start pl-2 text-base font-medium text-gray-900">Formula:</label>
                        <textarea disabled id="formula" rows="4" class="p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Formula...">{{ $formula }}</textarea>
                    @endif
                </div>
            </div>
        @else
            <div class="flex flex-col items-center text-center mt-20">
                <p>{{ __("No score operation associated with the section") }}</p>
            </div>
        @endif
    </div>
</body>


