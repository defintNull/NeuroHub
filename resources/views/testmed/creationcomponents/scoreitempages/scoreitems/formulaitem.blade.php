<body>
    <p class="italic text-xs mb-4">{{ __("Valid simbols: + * / - ( ) Qn(for section with question) Sn(for section with subsection) (n is a natural number > 1), no white spaces") }}</p>
    <label for="formula" class="mb-2 w-full text-start pl-2 text-base font-medium text-gray-900">Formula:</label>
    <textarea id="formula" name="formula" rows="4" class="p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Formula..."></textarea>
    <ul id="formula-error" class="text-sm break-all text-red-600 space-y-1 mt-4 mb-2"></ul>
</body>
