<body>
    <div class="valuelistitem singlecheck flex items-center p-2 rounded hover:bg-gray-100">
        <input id="checkbox-personal-" type="checkbox" class="checkboxpersonal singlecheck w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
        <input id="checkbox-personal-text-" name="checkboxpersonal" type="text" value="" class="checkboxpersonaltext singlecheck w-full ms-2 text-sm font-medium text-gray-900 rounded">
        <div class="flex items-center cancelitem h-7 w-9 pl-1 pr-1 mr-2">
            <x-carbon-trash-can class="h-5 w-5 hidden" title="delete"/>
        </div>
    </div>
    <ul class="text-sm text-red-600 space-y-1 ml-10 mb-2">
        <li id="checkbox-personal-text-error-" class="hidden">This field must be greater that 100</li>
    </ul>
</body>
