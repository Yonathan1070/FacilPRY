@extends("theme.bsb.general.layout")
@section('titulo')
    Inicio Sesión
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="block-header">
        <div class="login-page">
            <div class="login-box">
                <div class="logo">
                    <a><img src="{{asset("assets/images/ink_logo.png")}}" height="70px" width="200px" /></a>
                </div>
                <div class="card">
                    <div class="body">
                        @include('includes.form-error')
                        @include('includes.form-exito')
                        <form action="{{route('login_post')}}" method="POST">
                            @csrf
                            <div class="msg">Inicio de Sesión</div>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">person</i>
                                </span>
                                <div class="form-line">
                                    <input type="text" class="form-control" id="USR_Nombre_Usuario" name="USR_Nombre_Usuario" value="{{old('USR_Nombre_Usuario')}}" placeholder="Nombre de Usuario" required autofocus>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>
                                <div class="form-line">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 p-t-5"></div>
                                <div class="col-xs-4">
                                    <button class="btn btn-block bg-pink waves-effect" type="submit">ENTRAR</button>
                                </div>
                            </div>
                            <div class="row m-t-15 m-b--20">
                                <div class="col-xs-5">
                                </div>
                                <div class="col-xs-7 align-right">
                                    <a href="{{route('recuperar_clave')}}">¿Olvidaste tu contraseña?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/examples/sign-in.js")}}"></script>
@endsection