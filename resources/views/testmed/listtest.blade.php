<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Lista Test</h1>
                </div>
            </div>
            <br>
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">

                @foreach ($tests as $test)
                    <div class="p-6 flex space-x-2">



                        <div class="flex-1">

                            <div>
                                <p class="mt-4 text-lg text-gray-900">Name: {{ $test->name }}</p>
                                <p class="mt-4 text-lg text-gray-900">Status: {{ $test->status }}</p>
                            </div>

                        </div>

                    </div>
                @endforeach

            </div>
            {{ $tests->links() }}
        </div>
    </div>
</x-app-layout>
