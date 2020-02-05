@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Crud Actividades
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
                    TAREAS
                </h2>
                <ul class="header-dropdown" style="top:10px;">
                    <li class="dropdown">
                        @if ($permisos['crearC']==true)
                            <a class="btn btn-success waves-effect" href="{{route('crear_actividad_cliente', ['idP'=>$proyecto->id])}}">
                                <i class="material-icons" style="color:white;">add</i> Nueva Tarea Cliente
                            </a>
                        @endif
                        @if ($permisos['crear'] == true)
                            <a class="btn btn-success waves-effect" href="{{route('crear_actividad_trabajador', ['idP'=>$proyecto->id])}}">
                                <i class="material-icons" style="color:white;">add</i> Nueva Tarea Trabajador
                            </a>
                        @endif
                        @if ($permisos['listarP'] == true)
                        <a class="btn btn-danger waves-effect" href="{{route('proyectos', ['id'=>$proyecto->PRY_Empresa_Id])}}">
                            <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Proyectos
                        </a>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-col-pink">
                                <div class="panel-heading" role="tab" id="headingOne_17">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="false" aria-controls="collapseOne_17">
                                                <i class="material-icons">contact_mail</i> Trabajadores
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17">
                                    <div class="panel-body table-responsive">
                                        @if (count($actividades)<=0)
                                            <div class="alert alert-info">
                                                No hay datos que mostrar.
                                                @if ($permisos['crear']==true)
                                                <a href="{{route('crear_actividad_trabajador', ['idP'=>$proyecto->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <table class="table table-striped table-bordered table-hover  dataTable js-exportable" id="tabla-data">
                                                <thead>
                                                    <tr>
                                                        <th>Tarea</th>
                                                        <th>Actividad</th>
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
                                                            <td>{{$actividad->REQ_Nombre_Requerimiento}}</td>
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
                            <div class="panel panel-col-cyan">
                                <div class="panel-heading" role="tab" id="headingTwo_17">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseTwo_17" aria-expanded="false" aria-controls="collapseTwo_17">
                                            <i class="material-icons">contacts</i> Clientes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17">
                                    <div class="panel-body table-responsive">
                                        @if (count($actividadesCliente)<=0)
                                            <div class="alert alert-info">
                                                No hay datos que mostrar.
                                                @if ($permisos['crearC'] == true)
                                                    <a href="{{route('crear_actividad_cliente', ['idP'=>$proyecto->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                                                @endif 
                                            </div>
                                        @else
                                            <table class="table table-striped table-bordered table-hover  dataTable js-exportable" id="tabla-data">
                                                <thead>
                                                    <tr>
                                                        <th>Tarea</th>
                                                        <th>Cliente</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($actividadesCliente as $actividad)
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
                        </div>
                    </div>
                </div>
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
<script src="{{asset("assets/pages/scripts/PerfilOperacion/verDetalle.js")}}"></script>
@endsection