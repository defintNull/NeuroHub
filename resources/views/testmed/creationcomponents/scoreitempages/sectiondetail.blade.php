<body>
    <div>
        <form @if (isset($update)) id="updateform" @else id="scoreform" @endif method="POST">
            @csrf
            @if ($enabler)
                <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
                    @if (isset($section))
                        {{ __($section->name) }}
                    @endif
                </div>
                @if (isset($update))
                    <input type="hidden" id="identifier" name="identifier" value="section-{{$section->id}}">
                @else
                    <div id="identifier" class="hidden" value="section-{{$section->id}}"></div>
                @endif
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
                            <ul id="scoreoperation-error" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">

                            </ul>
                        </div>
                    </div>
                    <div class="flex flex-col items-center mt-6 mb-4">
                        <div id="score" class="flex flex-col w-3/4 items-center">

                        </div>
                    </div>
                    <div class="opacity-50">
                        <p class="text-center text-xl mt-8">{{ __("Jump section:") }}</p>
                        <div class="flex flex-col items-center mt-2 sm:mx-4 md:mx-0">
                            <div class="flex flex-row items-center">
                                <input disabled id="jump-enabler" name="jump" value="1" type="checkbox"/>
                                <label for="jump-enabler" class="italic ml-4">Select to enable jump sistem for the section</label>
                            </div>
                        </div>
                        <ul id="jump-enabler-error" class="text-sm text-center mt-2 break-all hidden text-red-600 space-y-1 mb-2">
                            <li>{{ __("Anoter question or parent section already has jump operation") }}</li>
                        </ul>
                    </div>
                    <div id="jump-container" class="hidden mt-4">
                        <div class="flex flex-col items-center">
                            <div class="flex flex-col items-center mt-4 justify-center w-2/3">
                                <div class="grid grid-cols-4 items-center w-full">
                                    <p class="text-center col-span-2 text-lg">{{ __("Range of values") }}</p>
                                    <p class="text-center col-span-2 text-lg">{{ __("Section Jump") }}</p>
                                </div>
                                <input id="jumplenght" name="jumplenght" value="1" class="hidden"/>
                                <div class="rangecontainer grid grid-cols-4 items-start w-full">
                                    <div class="flex flex-col items-center mt-4 justify-center">
                                        <div class="flex flex-row items-center justify-center">
                                            <p class="mr-4">From:</p>
                                            <input type="text" name="from-1" value="" class="rangeinput rounded-lg w-20"/>
                                        </div>
                                        <ul id="from-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                            <li>{{ __("Required") }}</li>
                                        </ul>
                                    </div>
                                    <div class="flex flex-col items-center mt-4 justify-center">
                                        <div class="flex flex-row items-center justify-center">
                                            <p class="mr-4">To:</p>
                                            <input type="text" name="to-1" value="" class="rangeinput rounded-lg w-20"/>
                                        </div>
                                        <ul id="to-error-1" class="text-sm text-center mt-2 break-all text-red-600 space-y-1 mb-2">
                                            <li>{{ __("Required") }}</li>
                                        </ul>
                                    </div>
                                    <div class="flex flex-row col-span-2 mt-4 border-l border-gray-400 items-center justify-center">
                                        <p class="mr-4">Section:</p>
                                        <select id="jumpinterval1" name="jumpinterval1" class="rounded-lg">
                                            @for ($n=0; $n<count($sectionlist); $n++)
                                                <option value="{{ $sectionlist[$n][0] }}">{{ Str::limit($sectionlist[$n][1], 16) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 items-center mt-8">
                                <button id="addrange" class="rounded-lg bg-blue-200 px-5 py-2 mr-10 hover:bg-blue-500">
                                    {{ __("Add") }}
                                </button>
                                <button id="removerange" class="rounded-lg bg-blue-200 px-5 py-2 hover:bg-blue-500">
                                    {{ __("Remove") }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @if (isset($data))
                        <div id="data" class="hidden">
                            <div id="data-type">section</div>
                            <div id="scoretype">{{ $scoretype }}</div>
                            @if (isset($formula))
                                <div id="given-formula">{{ $formula }}</div>
                            @endif
                            @if (isset($conversion))
                                <div id="given-conversion">{{ json_encode($conversion) }}</div>
                            @endif
                        </div>
                    @endif
                    <div class="flex flex-col w-full items-end mt-8 mb-12 pr-24">
                        @if (isset($update))
                            <div class="flex flex-row items-center">
                                <button type="submit" class="back mr-4 inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Back') }}
                                </button>
                                <x-primary-button>{{ __("Save") }}</x-primary-button>
                            </div>
                        @else
                            <x-primary-button>{{ __("Next") }}</x-primary-button>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-6 mt-48 text-center font-semibold text-3xl text-gray-800 leading-tight">
                    @if (isset($section))
                        {{ __($section->name) }}
                    @endif
                </div>
                <div id="identifier" class="hidden" value="section-{{$section->id}}"></div>
                <div class="flex-col items-center">
                    <div class="flex flex-col w-full items-end mt-32 mb-12 pr-24">
                        @if (isset($update))
                            <div class="flex flex-row items-center">
                                <button type="submit" class="back mr-4 inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Back') }}
                                </button>
                                <x-primary-button>{{ __("Save") }}</x-primary-button>
                            </div>
                        @else
                            <x-primary-button>{{ __("Next") }}</x-primary-button>
                        @endif
                    </div>
                </div>
            @endif
        </form>
    </div>
</body>
