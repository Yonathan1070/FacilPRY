@extends("theme.bsb.general.layout")

@section('contenido')
<div class="container-fluid">
    <div class="block-header">
        <div class="fp-page">
            <div class="fp-box">
                <div class="logo">
                    <a>Facil<b>PRY</b></a>
                </div>
                <div class="card">
                    <div class="body">
                        @include('includes.form-error')
                        @include('includes.form-exito')
                        <form action="{{route('enviar_correo')}}" id="forgot_password" method="POST">
                            @csrf
                            <div class="msg">
                                Ingrese la dirección de correo electrónico con que se encuentre registrado en FacilPRY.<br>
                                Le enviaremos un correo electrónico con su nombre de usuario y un enlace para restablecer su contraseña.
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">email</i>
                                </span>
                                <div class="form-line">
                                    <input type="email" class="form-control" id="USR_Correo" name="USR_Correo" value="{{old('USR_Correo')}}" placeholder="Correo Electrónico" required autofocus>
                                </div>
                            </div>
                            
                            <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">RECUPERAR CONTRASEÑA</button>
                            
                            <div class="row m-t-20 m-b--5 align-center">
                                <a href="{{route('login')}}">Inicia Sesión!</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection