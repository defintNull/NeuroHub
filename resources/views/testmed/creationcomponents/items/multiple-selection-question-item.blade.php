<body>
    <div class="valuelistitem flex items-center p-2 rounded hover:bg-gray-100">
        <input disabled id="checkbox-" type="checkbox" class="checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
        <input id="checkbox-text-" name="checkbox" type="text" value="" class="w-full ms-2 text-sm font-medium text-gray-900 rounded">
        <div class="flex items-center cancelitem h-7 w-9 pl-1 pr-1 mr-2">
            <x-carbon-trash-can class="h-5 w-5 hidden" title="delete"/>
        </div>
    </div>
    <ul id="checkbox-text-error-" class="text-sm text-red-600 space-y-1 ml-10 mb-2"></ul>
</body>
