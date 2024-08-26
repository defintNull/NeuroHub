<body>
    <li class="multiplelistitem w-full border-b rounded-t-lg border-gray-400">
        <div class="flex items-center ps-3">
            <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
            <input type="text" id="radio-input-" name="radioinput" class="w-full my-3 ml-6 ms-2 text-sm font-medium text-gray-900 bg-blue-100 focus:bg-white"/>
            <div class="flex items-center cancelitem h-9 w-9 pl-2 pr-2 mr-2">
                <x-carbon-trash-can class="h-6 w-6 hidden" title="delete"/>
            </div>
        </div>
        <ul id="radio-input-error-" class="text-sm text-red-600 space-y-1 ml-10 mb-2"></ul>
    </li>
</body>
