@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Listar Tareas
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
    <!-- Colorful Panel Items With Icon -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @include('includes.form-exito')
        @include('includes.form-error')
        <div class="card">
            <div class="header">
                <h2>
                    TAREAS - PROYECTO ({{strtoupper($proyecto->PRY_Nombre_Proyecto)}})
                </h2>
                <ul class="header-dropdown" style="top:10px;">
                    <li class="dropdown">
                        @if ($permisos['listarP'] == true)
                        <a class="btn btn-danger waves-effect" href="{{route('proyectos', ['id'=>$proyecto->PRY_Empresa_Id])}}">
                            <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Proyectos
                        </a>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="body table-responsive">
                @if (count($actividades)<=0)
                    <div class="alert alert-info">
                        No hay datos que mostrar.
                        @if ($permisos['crear']==true)
                        <a href="{{route('crear_actividad_trabajador', ['idR'=>$requerimiento->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                            </a>
                        @endif
                    </div>
                @else
                    <table class="table table-striped table-bordered table-hover  dataTable js-exportable" id="tabla-data">
                        <thead>
                            <tr>
                                <th>Tarea</th>
                                <th>Encargado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actividades as $actividad)
                                <tr>
                                    <td>
                                        <a id="{{$actividad->ID_Actividad}}" onclick="detalle(this)" class="btn-accion-tabla tooltipsC"
                                            title="Ver Detalles" data-toggle="modal" data-target="#defaultModal">
                                            {{$actividad->ACT_Nombre_Actividad}}
                                        </a>
                                    </td>
                                    <td>{{$actividad->USR_Nombres_Usuario.' '.$actividad->USR_Apellidos_Usuario}}</td>
                                    <td>{{$actividad->EST_Nombre_Estado}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Detalles de la actividad</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="nombreActividad">Tarea</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="nombreActividad" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="descripcionActividad">Descripción</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea name="" id="descripcionActividad" readonly="true" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="fechaInicioActividad">Inicio</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="fechaInicioActividad" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="fechaFinActividad">Fin</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="fechaFinActividad" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="tiempo">Estado</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="estadoActividad" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
<script src="{{asset("assets/pages/scripts/PerfilOperacion/verDetalle.js")}}"></script>
@endsection