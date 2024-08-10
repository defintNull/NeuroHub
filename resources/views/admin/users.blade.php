<x-app-layout>
    <x-slot name="header">

        <form class="max-w-md mx-auto" method="GET" action="">
            <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input name="search" type="search" id="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." required />
                <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
            </div>
        </form>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
{{--             <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
                <form method="GET" action="">
                    @csrf
                    <input type="text" name="search" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <x-primary-button class="mt-4">{{ __('Cerca') }}</x-primary-button>
                </form>
            </div> --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Lista Medici</h1>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @foreach ($users as $user)
                    <div class="p-6 text-gray-900">
                        <h1>Username: {{ $user->username }}</h1>
                        <h1>Email: {{ $user->email }}</h1>
                        <h1>Tipologia: {{ $user->userable_type == 'App\Models\Med' ? 'Medico' : 'TestMed' }}</h1>
                        @if ($user->userable_id)
                        <button class="h-10 px-5 m-2 text-blue-100 transition-colors duration-150 bg-blue-600 rounded-lg focus:shadow-outline hover:bg-blue-700">
                            <a href="{{ route('admin.info', ['id'=>$user->id]) }}">Info</a>
                        </button>
                        @endif
                        <form method="post" action="{{ route('admin.del', $user->id) }}">
                            @csrf
                            @method('delete')
{{--                             <x-danger-button class="ms-3">
                                {{ __('Delete Account') }}
                            </x-danger-button> --}}
                            <button class="h-10 px-5 m-2 text-red-100 transition-colors duration-150 bg-red-700 rounded-lg focus:shadow-outline hover:bg-red-800" type="submit">Delete</button>
                        </form>
                    </div>
                @endforeach
            </div>
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
