<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test List') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center mt-6 mb-8">
                <form class="mx-auto w-2/4" method="GET" action="{{ route("testmed.testlist") }}">
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        @if (isset($search))
                        <input type="search" id="default-search" name="search" value="{{ $search }}" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Test..." required />
                        @else
                        <input type="search" id="default-search" name="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Test..." required />
                        @endif
                        <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                    </div>
                </form>
            </div>
            <div class="mt-2 flex flex-col w-full items-center ">
                <div class="flex flex-col w-2/3 items-center rounded-lg divide-y">

                    @if (isset($tests))
                        @foreach ($tests as $test)
                            <div class="flex w-full justify-center bg-white space-x-2">
                                @if ($test->status == 1)
                                    <form id="select-test-form" method="POST" action="{{ route('testmed.testlist') }}" class="w-full px-6 py-2 flex justify-center items-center">
                                        @csrf
                                        <input type="hidden" for="select-test-form" name="testname" value="{{ $test->name }}"/>
                                        <button type="submit" style="all: unset; cursor: pointer; width: inherit">
                                            <div class="grid grid-cols-2 w-full hover:bg-blue-100 rounded-lg py-6 justify-center">
                                                <p class="inline-block text-right text-lg pr-10 text-gray-900">Name: {{ $test->name }}</p>
                                                <p class="inline-block text-left text-lg pl-10 text-gray-900">Status: Closed</p>
                                            </div>
                                        </button>
                                    </form>
                                @else
                                    <div class="w-full px-6 py-2 flex justify-center items-center">
                                        <div class="grid grid-cols-2 w-full py-6 justify-center">
                                            <p class="inline-block text-right text-lg pr-10 text-gray-900">Name: {{ $test->name }}</p>
                                            <p class="inline-block text-left text-lg pl-10 text-gray-900">Status: Open</p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="w-full">
                        {{ $tests->appends(request()->except('page'))->onEachSide(2)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
