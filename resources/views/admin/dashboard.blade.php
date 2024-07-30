<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
                <form method="GET" action="">
                    @csrf
                    <input type="text" name="search" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <x-primary-button class="mt-4">{{ __('Cerca') }}</x-primary-button>
                </form>
            </div>
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
                        <a class="button" href="{{ route('admin.info', ['id'=>$user->id]) }}">Info</a>
                        <a href="del/{{$user->id}}" class="mt-4">Cancella</a>
                    </div>
                @endforeach
            </div>
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
