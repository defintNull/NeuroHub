<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Info') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <p class="text-gray-900">{{__('Username: ')}} {{ $user->username }}</p>
                        <p class="text-gray-900">{{ __('Type: ') }} {{ $user->userable_type=='App\Models\Med'? 'Medico' : 'Testmed' }}</p>
                        <p class="text-gray-600">{{ __('Email: ') }}{{ $user->email }}</p>
                        @if ($user->userable)
                        <p class="text-gray-600">{{ __('Name: ') }}{{ $user->userable->name }}</p>
                        <p class="text-gray-600">{{ __('Surname: ') }}{{ $user->userable->surname }}</p>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
