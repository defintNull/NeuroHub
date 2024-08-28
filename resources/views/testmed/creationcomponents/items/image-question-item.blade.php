<body>
    <li class="imagelistitem w-full border-b rounded-t-lg border-gray-400">
        <div class="flex items-center mt-2 ps-3">
            <input disabled type="radio" class="w-4 h-4 text-blue-600 focus:ring-blue-600 ring-offset-gray-700 focus:ring-offset-gray-700 focus:ring-2 bg-gray-600 border-gray-500">
            <div class="flex items-center">
                <label for="image-input-" id="image-input-label-" class="cursor-pointer ml-2 bg-blue-200 font-medium py-2 px-4 rounded hover:bg-blue-300">
                    Choose File
                </label>
                <input type="file" id="image-input-" name="imageinput" accept="image/*" class="imageinput hidden" required>
                <span id="file-name-" class="ml-3 text-gray-600 text-sm">No file chosen</span>
            </div>
            <img id="image-preview-" class="ml-4 hidden w-32 h-32 object-cover rounded border-2 border-gray-300" src="#" alt="Image Preview">
            <div class="flex items-center cancelitem h-9 w-9 pl-2 pr-2 mr-2">
                <x-carbon-trash-can class="h-6 w-6 hidden" title="delete"/>
            </div>
        </div>
        <ul id="image-input-error-" class="text-sm text-red-600 space-y-1 ml-10 mb-2"></ul>
    </li>
</body>
