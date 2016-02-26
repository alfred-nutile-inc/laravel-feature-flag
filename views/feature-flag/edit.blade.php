@extends('layouts.default')

@section('content')
    <div class="page-header">
        <h1>FeatureFlag / Edit </h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('feature_flags.update', $flag->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @include('feature_flags::feature-flag.form')
            </form>
        </div>
    </div>


@endsection
