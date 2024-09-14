<body>
    <div id="conversiongrid" class="grid grid-cols-2 items-start text-center rounded-lg bg-blue-100 pt-2 pb-2 w-80 max-h-96 overflow-y-auto">
        <p class="border-r border-b border-gray-400 ml-2">{{ __("Value") }}</p>
        <p class="border-l border-b border-gray-400 mr-2">{{ __("Conversion") }}</p>
        <input type="hidden" name="lenght" value="1" id="lenght" />
        <div id="conversionitemleft" class="unremovable px-2 pt-2 border-r border-gray-400">
            <input type="text" name="value-1" class="rounded-md w-full" />
            <ul id="conversion-value-error-1" class="text-sm text-red-600 space-y-1 mb-2"></ul>
        </div>
        <div id="conversionitemright" class="unremovable px-2 pt-2 border-l border-gray-400">
            <input type="text" name="converted-1" class="rounded-md w-full" />
            <ul id="conversion-converted-error-1" class="text-sm break-all text-red-600 space-y-1 mb-2"></ul>
        </div>
        <div class="pt-4">
            <button id="addconversion" class="rounded-lg bg-blue-200 px-5 py-1 hover:bg-blue-500">
                {{ __("Add") }}
            </button>
        </div>
        <div class="pt-4">
            <button id="removeconversion" class="rounded-lg bg-blue-200 px-5 py-1 hover:bg-blue-500">
                {{ __("Remove") }}
            </button>
        </div>
    </div>
</body>
