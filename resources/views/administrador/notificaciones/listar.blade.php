@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Inicio
@endsection
@section('contenido')
@include('includes.form-exito')
@include('includes.form-error')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Listado de notificaciones</h2>
                </div>
                <div class="body">
                    <div class="list-group">
                        @foreach ($notificacionesTodas as $notificacion)
                            @if ($notificacion->NTF_Estado == 0)
                                <a onclick="notificacion({{$notificacion->id}})" class="list-group-item active">
                                    <h4 class="list-group-item-heading">{{$notificacion->NTF_Titulo}}</h4>
                                    <p class="list-group-item-text">
                                        @if (\Carbon\Carbon::now()->diffInSeconds($notificacion->NTF_Fecha) < 60)
                                            <p>{{\Carbon\Carbon::now()->diffInSeconds($notificacion->NTF_Fecha)}} Segundos</p>
                                        @elseif(\Carbon\Carbon::now()->diffInMinutes($notificacion->NTF_Fecha) < 60)
                                            <p>{{\Carbon\Carbon::now()->diffInMinutes($notificacion->NTF_Fecha)}} Minutos</p>
                                        @elseif(\Carbon\Carbon::now()->diffInHours($notificacion->NTF_Fecha) < 24)
                                            <p>{{\Carbon\Carbon::now()->diffInHours($notificacion->NTF_Fecha)}} Horas</p>
                                        @else
                                            <p>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $notificacion->NTF_Fecha)->format('d/m/Y')}}</p>
                                        @endif
                                    </p>
                                </a>
                            @else
                                <a onclick="notificacion({{$notificacion->id}})" class="list-group-item">
                                    <h4 class="list-group-item-heading">{{$notificacion->NTF_Titulo}}</h4>
                                    <p class="list-group-item-text">
                                        @if (\Carbon\Carbon::now()->diffInSeconds($notificacion->NTF_Fecha) < 60)
                                            {{\Carbon\Carbon::now()->diffInSeconds($notificacion->NTF_Fecha)}} Segundos
                                        @elseif(\Carbon\Carbon::now()->diffInMinutes($notificacion->NTF_Fecha) < 60)
                                            {{\Carbon\Carbon::now()->diffInMinutes($notificacion->NTF_Fecha)}} Minutos
                                        @elseif(\Carbon\Carbon::now()->diffInHours($notificacion->NTF_Fecha) < 24)
                                            {{\Carbon\Carbon::now()->diffInHours($notificacion->NTF_Fecha)}} Horas
                                        @else
                                            {{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $notificacion->NTF_Fecha)->format('d/m/Y')}}
                                        @endif
                                    </p>
                                </a>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        function notificacion(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/administrador/" + id + "/cambio-estado"
            }).done(function (notif) {
                if(notif.ruta != null)
                    document.location.replace(notif.ruta);
            });
        }
    </script>
@endsection