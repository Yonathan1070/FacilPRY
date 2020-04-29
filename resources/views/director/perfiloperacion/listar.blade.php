@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Listar Perfil de Operación
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
    <script>
        function inactivar(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/perfil-operacion/" + id + "/inactivar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "ok") {
                    location.reload();
                    InkBrutalPRY.notificaciones('Perfil de operación ha quedado inactivo', 'InkBrutalPRY', 'success');
                } else {
                    InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
                }
            });
        }

        function activar(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/perfil-operacion/" + id + "/activar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "ok") {
                    location.reload();
                    InkBrutalPRY.notificaciones('Perfil de operación ha quedado activo', 'InkBrutalPRY', 'success');
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
                        <h2>PERFIL DE OPERACIÓN</h2>
                        <ul class="header-dropdown" style="top:10px;">
                            <li class="dropdown">
                                <a class="btn btn-success waves-effect" href="{{route('crear_perfil_operacion')}}">
                                    <i class="material-icons" style="color:white;">add</i> Nuevo Perfil de Operación
                                </a>
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
                                    @if (count($perfilesOperacionActivos)<=0)
                                        <div class="alert alert-info">
                                            El sistema no cuenta con Perfil de Operación registrado
                                            <a href="{{route('crear_perfil_operacion')}}" class="alert-link">Clic aquí para agregar!</a>.
                                        </div>
                                    @else
                                        <table class="table table-striped table-bordered table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Documento</th>
                                                    <th>Nombre y Apellido</th>
                                                    <th>Telefono</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Cargo</th>
                                                    <th>Estado</th>
                                                    <th class="width70"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($perfilesOperacionActivos as $perfilOperacion)
                                                    <tr>
                                                        <td>{{$perfilOperacion->USR_Documento_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Telefono_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Correo_Usuario}}</td>
                                                        <td>{{$perfilOperacion->RLS_Nombre_Rol}}</td>
                                                        <td>
                                                            @if ($perfilOperacion->USR_RLS_Estado == 1)
                                                                Activo
                                                            @else
                                                                Inactivo
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{route('editar_perfil_operacion', ['id'=>$perfilOperacion->Id_Perfil])}}" class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                                <i class="material-icons text-info" style="font-size: 18px;">edit</i>
                                                            </a>
                                                            <a onclick="inactivar({{$perfilOperacion->Id_Perfil}})" class="tooltipsC" title="Desactivar Perfil de Operación">
                                                                <i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i>
                                                            </a>
                                                            <a href="{{route('carga_perfil_operacion', ['id'=>$perfilOperacion->Id_Perfil])}}" class="btn-accion-tabla tooltipsC" title="Ver Carga de Trabajo">
                                                                <i class="material-icons text-success" style="font-size: 18px;">remove_red_eye</i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="inactivos">
                                    @if (count($perfilesOperacionInactivos)<=0)
                                        <div class="alert alert-info">
                                            El sistema no cuenta con Perfil de Operación inactivo.
                                        </div>
                                    @else
                                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                            <thead>
                                                <tr>
                                                    <th>Documento</th>
                                                    <th>Nombre y Apellido</th>
                                                    <th>Telefono</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Cargo</th>
                                                    <th>Estado</th>
                                                    <th class="width70"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($perfilesOperacionInactivos as $perfilOperacion)
                                                    <tr>
                                                        <td>{{$perfilOperacion->USR_Documento_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Telefono_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Correo_Usuario}}</td>
                                                        <td>{{$perfilOperacion->RLS_Nombre_Rol}}</td>
                                                        <td>
                                                            @if ($perfilOperacion->USR_RLS_Estado == 1)
                                                                Activo
                                                            @else
                                                                Inactivo
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <form class="form-eliminar" action="{{route('eliminar_perfil_operacion', ['id'=>$perfilOperacion->Id_Perfil])}}" class="d-inline" method="POST">
                                                                <a href="{{route('activar_perfil_operacion', ['id'=>$perfilOperacion->Id_Perfil])}}" class="btn-accion-tabla tooltipsC" title="Activar Usuario">
                                                                    <i class="material-icons text-success" style="font-size: 18px;">arrow_upward</i>
                                                                </a>
                                                                @csrf @method("delete")
                                                                <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm" title="Eliminar Perfil de operación">
                                                                    <i class="material-icons text-danger" style="font-size: 18px;">delete_forever</i>
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
@endsection