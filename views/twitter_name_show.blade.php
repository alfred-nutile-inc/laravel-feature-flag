@can('feature-flag', 'see-twitter-field')
<div class="row">
    <div class="col-md-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>Twitter Name</h2>
                </div>
                <div class="ibox-content">

                    @if($user->twitter)
                        <p>Your Twitter Handle is {{ $user->twitter }}</p>
                    @else
                        <p>No Twitter Handle here</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endcan