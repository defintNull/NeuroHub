<body>
    <form method="get">
        @csrf
        <button type="submit" class="addsectionbutton flex items-center justify-center text-center rounded py-2 px-4 bg-blue-100 hover:bg-blue-300 hover:shadow-xl">
            {{ __("+ Add Section") }}
        </button>
    </form>
</body>
