<section>
    <form method="get">
        @csrf
        <button type="submit" class="addsectionbutton flex min-w-40 items-center justify-center text-center rounded py-2 bg-blue-100 hover:bg-blue-300 hover:shadow-xl">
            {{ __("+ Add Section") }}
        </button>
    </form>
</section>
<question>
    <form method="get">
        @csrf
        <button type="submit" class="addquestionbutton flex min-w-40 items-center justify-center text-center rounded py-2 bg-blue-100 hover:bg-blue-300 hover:shadow-xl">
            {{ __("+ Add Question") }}
        </button>
    </form>
</question>
