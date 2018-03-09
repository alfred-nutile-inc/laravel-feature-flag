@extends(config("laravel-feature-flag.default_view")) @section('content')
<div class="page-header">
    <h1>FeatureFlag / Edit </h1>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('laravel-feature-flag.update', $flag->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"> @include('laravel-feature-flag::form')
        </form>
    </div>
</div>


@endsection