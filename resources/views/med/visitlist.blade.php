<x-app-layout>
    <x-slot name="header">

        {{--         <form class="max-w-md mx-auto" method="GET" action="">
            <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input name="search" type="search" id="search"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search..." @if ($search) value="{{$search}}"@endif
                    required />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search
                </button>
            </div>
        </form> --}}



        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visit list') }}
        </h2>


    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div>
                    <form class="max-w-sm mx-auto flex items-center gap-4" method="GET" action="{{ route('med.visits.index') }}">
                        <label for="order" class="block text-sm font-medium text-gray-900">Order by</label>
                        <select id="order" name="order"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="" @if ($order == '') selected @endif>...</option>
                            <option value="desc" @if ($order == 'desc') selected @endif>Recent</option>
                            <option value="asc" @if ($order == 'asc') selected @endif>Older</option>
                        </select>

                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input datepicker id="default-datepicker" type="date" name="date"
                            @if ($date) value="{{ $date }}" @endif
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                            placeholder="Select date">
                        </div>
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">

                @foreach ($visits as $visit)
                    <div class="p-6 flex flex-col space-x-2">



                        <div class="flex flex-col">

                            <div class="flex justify-between items-center">

                                <div class="w-full">

                                    <span class="text-gray-600">Patient: {{ $visit->patient->name }}
                                        {{ $visit->patient->surname }} <br></span>
                                    <span class="text-gray-600 pr-6 float-right">{{ $visit->date }}</span>
                                    <span class="text-gray-600">Doctor: {{ $visit->med->name }}
                                        {{ $visit->med->surname }}</span><br>
                                    <span class="text-gray-600">Type: {{ $visit->type }}</span>


                                    <small class="ml-2 text-sm text-gray-600"></small>

                                </div>



                            </div>

                            <div class="w-full">
                                <p class="mt-4 text-lg text-gray-900 truncate">Diagnosis: {{ $visit->diagnosis }}</p>
                                <p class="mt-2 text-lg text-gray-900 truncate">Treatment: {{ $visit->treatment }}</p>
                            </div>

                            <br>
                            <div class="mt-4">
                                <button type="button"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    <a href="{{ route('med.visits.interviews', $visit->id)}}">{{__('Show Visit')}}</a>
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
            {{ $visits->appends(request()->except("page"))->links() }}
        </div>
    </div>
</x-app-layout>
