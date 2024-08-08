<delete>
    <form class="formdeletebutton">
        @csrf
        <input type="hidden" name="type" value="" />
        <input type="hidden" name="id" value="" />
        <x-carbon-trash-can />
    </form>
</delete>
<modify>
    <form class="formmodifybutton">
        @csrf
        <input type="hidden" name="type" value="" />
        <input type="hidden" name="id" value="" />
        <x-carbon-edit />
    </form>
</modify>
