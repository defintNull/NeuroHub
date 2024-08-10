<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registry patient') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Registry Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Add patient information") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('med.patients.update', $patient) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Name -->
                            <div>
                                <x-input-label for="name"/>
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $patient->name)" required autofocus autocomplete="given-name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Surname -->
                            <div>
                                <x-input-label for="surname" />
                                <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('name', $patient->surname)" required autofocus autocomplete="family-name" />
                                <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                            </div>

                            <!-- Telephone -->
                            <div>
                                <x-input-label for="telephone" :value="__('Telephone')" />
                                <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('name', $patient->telephone)" required autofocus autocomplete="tel" />
                                <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                            </div>

                            <!-- Birthdate -->
                            <div>
                                <x-input-label for="birthdate"/>
                                <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('name', $patient->birthdate)" required autofocus autocomplete="bday" />
                                <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'registry-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
