<x-app-layout>
    <x-slot name="header">

        <form class="max-w-md mx-auto" method="GET" action="{{route('med.patients.index')}}">
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
        </form>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="text-gray-900">
                    <h2>Lista Pazienti</h2>
                </div>
            </div>
            <br>
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">

                @foreach ($patients as $patient)
                    <div class="p-6 flex space-x-2">



                        <div class="flex-1">

                            <div class="flex justify-between items-center">

                                <div>

                                    <span class="text-gray-600">Patient info</span>

                                    <small class="ml-2 text-sm text-gray-600"></small>

                                </div>
                                <x-dropdown>

                                    <x-slot name="trigger">

                                        <button>

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">

                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />

                                            </svg>

                                        </button>

                                    </x-slot>

                                    <x-slot name="content">

                                        <x-dropdown-link :href="route('med.patients.edit', $patient)">

                                            {{ __('Edit') }}

                                        </x-dropdown-link>

                                            <x-dropdown-link :href="route('med.patients.confirm-delete', $patient)">

                                                {{ __('Delete') }}

                                            </x-dropdown-link>

                                        </form>

                                        <x-dropdown-link :href="route('med.patients.medicalrecords.index', $patient)">

                                            {{ __('Medical Record') }}

                                        </x-dropdown-link>
                                    </x-slot>

                                </x-dropdown>


                            </div>

                            <div>
                                <p class="mt-4 text-lg text-gray-900">Name: {{ $patient->name }}</p>
                                <p class="mt-4 text-lg text-gray-900">Surname: {{ $patient->surname }}</p>
                                <p class="mt-4 text-lg text-gray-900">Telephone: {{ $patient->telephone }}</p>
                                <p class="mt-4 text-lg text-gray-900">Birthdate: {{ $patient->birthdate }}</p>
                            </div>

                            <br>
                            <div>
                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    <a href="{{ route('med.visits.create', $patient) }}">Start new visit</a>
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
            {{ $patients->links() }}
        </div>
    </div>
</x-app-layout>
