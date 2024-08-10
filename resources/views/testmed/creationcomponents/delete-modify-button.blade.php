<delete>
    <form class="formdeletebutton">
        @csrf
        <input type="hidden" name="type" value="" />
        <input type="hidden" name="id" value="" />
        <x-carbon-trash-can title="delete"/>
    </form>
</delete>
<modify>
    <form class="formmodifybutton">
        @csrf
        <input type="hidden" name="type" value="" />
        <input type="hidden" name="id" value="" />
        <x-carbon-edit title="edit"/>
    </form>
</modify>
