@extends('layouts.default')

@section('content')

<h2>Twitter Show Example</h2>

<p>
  When on you see it when off you do not
</p>

@can('see-twitter-field')
<div class="row">
    <div class="col-md-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>Twitter Name (Feature Flag On If you See This)</h2>
                </div>
                <div class="ibox-content">

                    @if($user->twitter)
                        <p>Your Twitter Handle is {{ $user->twitter }}</p>
                    @else
                        <p>No Twitter Handle here</p>
                    @endif

                    <pre>
                      <?php print_r(\Feature\Feature::isEnabled('see-twitter-field')); ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@cannot('see-twitter-field')
 <p>
   <b>Twitter Flag NOT ON</b>
   <pre>
     <?php print_r(\Feature\Feature::isEnabled('see-twitter-field')); ?>
   </pre>
 </p>
@endcan

@endsection
