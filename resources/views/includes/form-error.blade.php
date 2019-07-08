@if ($errors->any())
    <div class="alert bg-red alert-dismissible" role="alert" data-auto-dismiss="3000">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="alert-text">
            @foreach ($errors->all() as $error)
            <div><i class="material-icons">warning</i> {{$error}}</div>
            @endforeach
        </div>
    </div>
@endif