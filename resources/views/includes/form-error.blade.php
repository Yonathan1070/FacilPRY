@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <ul class="list">
            @foreach ($errors->all() as $error)
                <div><i class="material-icons">warning</i> {{$error}}</div>
            @endforeach
        </ul>
    </div>
@endif