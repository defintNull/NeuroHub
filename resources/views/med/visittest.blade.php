<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="">
                        @csrf
                        <input type="hidden" name="type" value="test">
                        <div class="flex flex-col">
                            <label for="test_id" class="mt-2">{{ __('Test Name') }}</label>
                            <select id="test_id" name="test_id" class="mt-1 block w-full rounded-md" required>
                                <option value="" disabled selected>{{ __('Select one') }}</option>
                                @foreach ($tests as $test)
                                    <option value="{{ $test->id }}">{{ $test->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                {{ __('Select') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

