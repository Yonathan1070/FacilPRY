@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Listar Director de Proyectos
@endsection
@section("scripts")
<script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>

<script>
    function inactivar(id){
        $.ajax({
            dataType: "json",
            method: "get",
            url: "/administrador/director-proyectos/" + id + "/inactivar"
        }).done(function (respuesta) {
            if(respuesta.mensaje == "ok") {
                location.reload();
                InkBrutalPRY.notificaciones('Director de proyectos ha quedado inactivo', 'InkBrutalPRY', 'success');
            } else {
                InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
            }
        });
    }

    function activar(id){
        $.ajax({
            dataType: "json",
            method: "get",
            url: "/administrador/director-proyectos/" + id + "/activar"
        }).done(function (respuesta) {
            if(respuesta.mensaje == "ok") {
                location.reload();
                InkBrutalPRY.notificaciones('Director de proyectos ha quedado activo', 'InkBrutalPRY', 'success');
            } else {
                InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
            }
        });
    }
</script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>
                        DIRECTOR DE PROYECTOS
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_director_administrador')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo Director de Proyectos</a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#activos" aria-controls="settings" role="tab" data-toggle="tab">Activos</a></li>
                            <li role="presentation"><a href="#inactivos" aria-controls="settings" role="tab" data-toggle="tab">Inactivos</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="activos">
                                @if (count($directoresActivos)<=0)
                                    <div class="alert alert-info">
                                        El sistema no cuenta con Directores activos
                                        <a href="{{route('crear_director_administrador')}}" class="alert-link">Clic aquí para
                                            agregar!</a>.
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th>Nombre y Apellido</th>
                                                <th>Correo Electrónico</th>
                                                <th>Telefono</th>
                                                <th>Nombre de Usuario</th>
                                                <th class="width70"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($directoresActivos as $director)
                                                <tr>
                                                    <td>{{$director->USR_Documento_Usuario}}</td>
                                                    <td>{{$director->USR_Nombres_Usuario.' '.$director->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$director->USR_Correo_Usuario}}</td>
                                                    <td>{{$director->USR_Telefono_Usuario}}</td>
                                                    <td>{{$director->USR_Nombre_Usuario}}</td>
                                                    <td>
                                                        <a href="{{route('editar_director_administrador', ['id'=>$director->USR_RLS_Usuario_Id])}}"
                                                            class="btn-accion-tabla tooltipsC" title="Editar este Registro">
                                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                        </a>
                                                        <a onclick="inactivar({{$director->USR_RLS_Usuario_Id}})" class="tooltipsC" title="Desactivar Director">
                                                            <i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="inactivos">
                                @if (count($directoresInactivos)<=0)
                                    <div class="alert alert-info">
                                        El sistema no cuenta con Directores inactivos.
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th>Nombre y Apellido</th>
                                                <th>Correo Electrónico</th>
                                                <th>Telefono</th>
                                                <th>Nombre de Usuario</th>
                                                <th class="width70"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($directoresInactivos as $director)
                                                <tr>
                                                    <td>{{$director->USR_Documento_Usuario}}</td>
                                                    <td>{{$director->USR_Nombres_Usuario.' '.$director->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$director->USR_Correo_Usuario}}</td>
                                                    <td>{{$director->USR_Telefono_Usuario}}</td>
                                                    <td>{{$director->USR_Nombre_Usuario}}</td>
                                                    <td>
                                                        <form class="form-eliminar" action="{{route('eliminar_director_administrador', ['id'=>$director->USR_RLS_Usuario_Id])}}"
                                                            class="d-inline" method="POST">
                                                            <a onclick="activar({{$director->USR_RLS_Usuario_Id}})" class="tooltipsC" title="Activar Director">
                                                                <i class="material-icons text-info" style="font-size: 18px;">arrow_upward</i>
                                                            </a>
                                                            @csrf @method("delete")
                                                            <button type="submit" class="btn-accion-tabla eliminar tooltipsC"
                                                                data-type="confirm" title="Eliminar este Registro">
                                                                <i class="material-icons text-danger"
                                                                    style="font-size: 17px;">delete_forever</i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection