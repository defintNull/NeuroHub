<body>
    <div>
        <form id="scoreform" method="POST">
            @csrf
            @if ($enabler)
                <div class="p-6 mt-12 text-center font-semibold text-3xl text-gray-800 leading-tight">
                    @if (isset($test))
                        {{ __($test->name) }}
                    @endif
                </div>
                <div class="flex-col items-center">
                    <div class="flex flex-col items-center mt-6 sm:mx-4 md:mx-0">
                        <div class="flex flex-row items-center">
                            <input id="score-enabler" name="enabler" value="1" type="checkbox"/>
                            <label for="score-enabler" class="italic ml-4">Select to enable score sistem for the section</label>
                        </div>
                    </div>
                    <div id="scorecontainer" class="opacity-50 flex-col items-center sm:mx-4 md:mx-0">
                        <div class="max-w-sm mx-auto mt-12">
                            <label for="scoreoperations" class="block mb-2 m-4 text-sm font-medium text-gray-900 dark:text-white">Select an option</label>
                            <select disabled id="scoreoperations" name="scoreoperation" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option selected disabled>Choose a score method</option>
                                <option value="1">Formula</option>
                                <option value="2">Conversion Table</option>
                                <option value="3">Formula + Convertion Table</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col items-center mt-6 mb-4">
                        <div id="score" class="flex flex-col w-3/4 items-center">

                        </div>
                    </div>
                    <div class="flex flex-col w-full items-end mt-8 mb-12 pr-24">
                        <x-primary-button>{{ __("Next") }}</x-primary-button>
                    </div>
                </div>
            @else
                <div class="p-6 mt-48 text-center font-semibold text-3xl text-gray-800 leading-tight">
                    @if (isset($test))
                        {{ __($test->name) }}
                    @endif
                </div>
                <div class="flex-col items-center">
                    <div class="flex flex-col w-full items-end mt-32 mb-12 pr-24">
                        <x-primary-button>{{ __("Next") }}</x-primary-button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</body>
