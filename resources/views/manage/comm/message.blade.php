@if(Session::has('success') || Session::has('error'))
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    {{ Session::get('success') }}
                </div>
            @endif

            @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                {{ Session::get('error') }}
            </div>
            @endif
        </div>
    </div>
@endif