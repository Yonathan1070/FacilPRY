@if (session('mensaje'))
    <div class="alert bg-green alert-dismissible" role="alert" data-auto-dismiss="6000">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div>
            <i class="material-icons">done_all</i>{{session('mensaje')}}
        </div>
    </div>
@endif