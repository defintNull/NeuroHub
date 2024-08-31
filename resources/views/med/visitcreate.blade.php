<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Visit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Visit Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Add patient information") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('med.visits.store') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('')

                            <!-- Diagnosis -->
                            <div>
                                <x-input-label for="diasnosis" :value="__('Diasnosis')"/>
                                <x-text-input id="diasnosis" class="block mt-1 w-full" type="text" name="diagnosis" autofocus autocomplete="given-name" value=""/>
                                <x-input-error :messages="$errors->get('diagnosis')" class="mt-2" />
                            </div>

                            <!-- Treatment -->
                            <div>
                                <x-input-label for="treatment" :value="__('Treatment')" />
                                <x-text-input id="treatment" class="block mt-1 w-full" type="text" name="treatment" autofocus autocomplete="family-name"  value=""/>
                                <x-input-error :messages="$errors->get('treatment')" class="mt-2" />
                            </div>

                            <!-- Date -->
                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" required autofocus autocomplete="day"
                                    min="{{ now()->subDays(5)->format('Y-m-d') }}"
                                    max="{{ now()->format('Y-m-d') }}"
                                />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <input type="hidden" name="patient_id" value="{{ $patient_id }}">

                            <input type="hidden" name="type" value="{{ $type }}">

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
