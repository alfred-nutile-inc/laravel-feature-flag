<div class="form-group">
    <div class="form-group">
        <label for="name">Key</label>
        <input type="text" name="key" class="form-control" value="@if($flag->key){{ $flag->key  }}@endif" @if($flag->key) disabled @endif/>

        @if($flag->key)
            <div class="help-block">Locked since this is linked to code.</div>
        @else
            <div class="help-block">You can enter a lower case key eg foo or see-twitter-name etc</div>
        @endif

    </div>
</div>

<div class="form-group">
    <div class="form-group">
        <label for="name">Variant</label>
        <textarea name="variants" class="form-control">@if($flag->variants){{ json_encode($flag->variants, true) }}@endif</textarea>
        <div class="help-block">This is a json formatted variant or just on | off. Example
            <br>
            <pre>{ "users": [ "foo@gmail.com" ] }</pre>
            or
            <br>
            <pre>"off"</pre>
        </div>
    </div>
</div>


<button class="btn btn-primary" type="submit">Save</button>