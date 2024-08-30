<body>
    <div>
        <div class="p-6 mt-10 text-center font-semibold text-3xl text-gray-800 leading-tight">
            @if (isset($section))
                {{ __($section->name) }}
            @endif
        </div>
    </div>
</body>
