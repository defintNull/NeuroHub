<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm User Deletion') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')

                        <div class="mt-6">
                            <x-input-label for="username" :value="__('Username')" />

                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                                value="{{ $user->username }}" readonly />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="email" :value="__('Type')" />

                            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email"
                                value="{{ $user->userable_type == 'App\Models\Med' ? 'Medico' : 'Testmed' }}" readonly />
                        </div>


                        @if ($user->userable)


                        <div class="mt-6">
                            <x-input-label for="name" :value="__('Name')" />

                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                value="{{ $user->userable->name }}" readonly />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="surname" :value="__('Surname')" />

                            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                                value="{{ $user->userable->surname }}" readonly />
                        </div>

                        @endif

                        <div class="flex items-center justify-end mt-4">
                            <x-danger-button class="ml-4">
                                {{ __('Confirm Deletion') }}
                            </x-danger-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
