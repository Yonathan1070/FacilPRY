@extends("theme.bsb.general.layout")

@section('contenido')
<div class="container-fluid">
    <div class="block-header">
        <div class="fp-page">
            <div class="fp-box">
                <div class="logo">
                    <div class="card profile-card">
                        <div class="profile-header" style="background-color: #131426">&nbsp;</div>
                        <div class="profile-body">
                            <div class="image-area content_img">
                                <a style="background-color: #131426">
                                    <img src="{{route('foto_perfil', ['id' => session()->get('Usuario_Id')])}}" width="128" height="128" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="body">
                        @include('includes.form-error')
                        @include('includes.form-exito')
                        <form action="{{route('activar_sesion')}}" method="POST">
                            @csrf
                            <div class="msg">
                                Su cuenta se encuentra en un estado de inactividad, ingrese la contraseña para reactivar la sesión.
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>
                                <div class="form-line">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required autofocus>
                                </div>
                            </div>
                            
                            <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">ENTRAR</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection