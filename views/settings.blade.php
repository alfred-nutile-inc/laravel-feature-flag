@extends('layouts.default')

@section('content')

    <h2>Set your feature flags</h2>

    <p>
        You can disable it completely or using the patterns seen <a href="https://github.com/Atriedes/feature#a-totally-enabled-feature" target="_blank">here</a>
        you can begin to modify the variants as needed.
    </p>

        <a class="btn btn-default" href="{{ route('laravel-feature-flag.create_form') }}">Create Feature Flag</a>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Key</td>
                    <td>Variant</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
            </thead>
            @foreach($settings as $setting)
                <tr>
                    <td>#{{ $setting->id }}</td>
                    <td>{{ $setting->key }}</td>
                    <td>{{ json_encode($setting->variants, JSON_PRETTY_PRINT) }}</td>
                    <td><a class="btn btn-success" href="/admin/feature_flags/{{ $setting->id }}/edit">Edit</a></td>
                    <td><form action="{{ route('laravel-feature-flag.delete', $setting->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="{{ csrf_token() }}"> <button class="btn btn-danger" type="submit">Delete</button></form></td>
                </tr>
            @endforeach
        </table>
@endsection