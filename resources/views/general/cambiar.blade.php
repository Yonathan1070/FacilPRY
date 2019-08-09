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
                        <form action="{{route('actualizar_clave')}}" id="forgot_password" method="POST">
                            @csrf
                            <div class="msg">
                                Digite su nueva contraseña
                            </div>
                            <input type="hidden" name="USR_Correo" id="USR_Correo" value="{{$consulta->USR_Correo}}">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>
                                <div class="form-line">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">lock</i>
                                </span>
                                <div class="form-line">
                                        <input type="password" class="form-control" id="confirmar" name="confirmar" placeholder="Confirmar Contraseña" required>
                                </div>
                            </div>

                            <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">ACTUALIZAR CONTRASEÑA</button>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection