<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Info Medico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl flex flex-col items-center mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-3/4 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Info Medico</h1>
                </div>
                    <div class="p-6 mx-6 grid grid-cols-2 text-gray-900">
                        <h1 class="break-all">Username: {{ $user->username }}</h1>
                        <h1 class="break-all">Email: {{ $user->email }}</h1>
                        <h1 class="break-all">Tipologia: {{ $user->userable_type == 'App\Models\Med' ? 'Medico' : 'TestMed' }}</h1>
                        <h1 class="break-all">Nome: {{$info->surname}}</h1>
                        <h1 class="break-all">Cognome: {{$info->surname}}</h1>
                        <h1 class="break-all">Telefono: {{$info->telephone}}</h1>
                        <form method="post" action="{{ route('admin.users.destroy', $user->id) }}" class="p-6 mt-4 mr-4 col-span-2 flex flex-col items-end">
                            @csrf
                            @method('delete')
                            <x-danger-button class="ms-3">
                                {{ __('Delete Account') }}
                            </x-danger-button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
