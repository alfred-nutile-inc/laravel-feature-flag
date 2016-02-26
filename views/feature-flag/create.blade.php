@extends('layouts.default')

@section('content')
    <div class="page-header">
        <h1>FeatureFlag / Create </h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('feature_flags.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @include('feature_flags::feature-flag.form')
            </form>
        </div>
    </div>


@endsection