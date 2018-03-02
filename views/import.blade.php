<form action="{{ route('laravel-feature-flag.imports') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <div class="form-group">
            <label for="name">Array of Exported Features</label>
            <textarea class="form-control" name="features" id="features" cols="30" rows="80"></textarea>
        </div>
    </div>


    <button class="btn btn-primary" type="submit">Save</button>

</form>