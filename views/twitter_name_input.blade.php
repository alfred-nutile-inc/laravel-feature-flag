@can('feature-flag', 'add-twitter-field')
<div class="form-group">
    <div class="form-group">
        <label for="twitter">Twitter Name</label>
        <input type="text" name="twitter" class="form-control" value="@if($user->twitter){{ $user->twitter }}@endif"/>
    </div>
</div>
@endcan