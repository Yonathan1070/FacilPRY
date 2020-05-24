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
                    TAREAS - ACTIVIDAD ({{strtoupper($requerimiento->REQ_Nombre_Requerimiento)}})
                </h2>
                <ul class="header-dropdown" style="top:10px;">
                    <li class="dropdown">
                        @if ($permisos['crearC']==true)
                            <a class="btn btn-success waves-effect" href="{{route('crear_actividad_cliente', ['idR'=>$requerimiento->id])}}">
                                <i class="material-icons" style="color:white;">add</i> Nueva Tarea Cliente
                            </a>
                        @endif
                        @if ($permisos['crear'] == true)
                            <a class="btn btn-success waves-effect" href="{{route('crear_actividad_trabajador', ['idR'=>$requerimiento->id])}}">
                                <i class="material-icons" style="color:white;">add</i> Nueva Tarea Trabajador
                            </a>
                        @endif
                        @if ($permisos['listarP'] == true)
                        <a class="btn btn-danger waves-effect" href="{{route('requerimientos', ['idP'=>$proyecto->id])}}">
                            <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Actividades
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
                                                <a href="{{route('crear_actividad_trabajador', ['idR'=>$requerimiento->id])}}" class="alert-link">Clic aquí para agregar!</a>.
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
                                                        <th>Fecha de Entrega</th>
                                                        <th>Estado</th>
                                                        <th class="width70"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($actividades as $actividad)
                                                        @if ($actividad->estado_id == 1 || $actividad->estado_id == 2)
                                                            <tr>
                                                                <td>
                                                                    <a id="{{$actividad->ID_Actividad}}" onclick="detalle(this)" class="btn-accion-tabla tooltipsC"
                                                                        title="Ver Detalles" data-toggle="modal" data-target="#defaultModal">
                                                                        {{$actividad->ACT_Nombre_Actividad}}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                        <select name="ACT_Requerimiento" id="ACT_Requerimiento" class="form-control show-tick" data-live-search="true"
                                                                            required onchange="cambioActividad(this, {{$actividad->ID_Actividad}})">
                                                                            <option value="">-- Seleccione una Actividad --</option>
                                                                            @foreach ($requerimientos as $requerimiento)
                                                                                <option value="{{$requerimiento->id}}" {{old("ACT_Requerimiento", $actividad->ID_Requerimiento) == $requerimiento->id ? 'selected' : '' }}>
                                                                                    {{$requerimiento->REQ_Nombre_Requerimiento}}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    @else
                                                                        {{$actividad->REQ_Nombre_Requerimiento}}
                                                                    @endif
                                                                </td>
                                                                <td>{{$actividad->USR_Nombres_Usuario.' '.$actividad->USR_Apellidos_Usuario}}</td>
                                                                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y h:i A.')}}</td>
                                                                <td>{{$actividad->EST_Nombre_Estado}}</td>
                                                                <td>
                                                                    @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                        <form class="form-eliminar" action="{{route('eliminar_actividad', ['idA'=>$actividad->ID_Actividad])}}"
                                                                            class="d-inline" method="POST">
                                                                            <a href="{{route('editar_actividad_trabajador', ['idA'=>$actividad->ID_Actividad])}}"
                                                                                class="btn-accion-tabla tooltipsC" title="Editar esta tarea">
                                                                                <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                                            </a>
                                                                            @csrf @method("delete")
                                                                            <button type="submit" class="btn-accion-tabla eliminar tooltipsC"
                                                                                data-type="confirm" title="Eliminar esta tarea">
                                                                                <i class="material-icons text-danger"
                                                                                    style="font-size: 17px;">delete_forever</i>
                                                                            </button>
                                                                            @if ($actividad->HorasE != 0)
                                                                                <a href="{{route('aprobar_horas_actividad', ['idH'=>$actividad->ID_Actividad])}}"
                                                                                    class="btn-accion-tabla tooltipsC" title="Aprobar horas de trabajo">
                                                                                    <i class="material-icons text-success" style="font-size: 17px;">alarm_on</i>
                                                                                </a>
                                                                            @endif
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif
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
                                                    <a href="{{route('crear_actividad_cliente', ['idR'=>$requerimiento->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                                                @endif 
                                            </div>
                                        @else
                                            <table class="table table-striped table-bordered table-hover  dataTable js-exportable" id="tabla-data">
                                                <thead>
                                                    <tr>
                                                        <th>Tarea</th>
                                                        <th>Actividad</th>
                                                        <th>Cliente</th>
                                                        <th>Fecha de Entrega</th>
                                                        <th>Estado</th>
                                                        <th class="width70"></th>
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
                                                            <td>
                                                                @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                    <select name="ACT_Requerimiento" id="ACT_Requerimiento" class="form-control show-tick" data-live-search="true"
                                                                        required onchange="cambioActividad(this, {{$actividad->ID_Actividad}})">
                                                                        <option value="">-- Seleccione una Actividad --</option>
                                                                        @foreach ($requerimientos as $requerimiento)
                                                                            <option value="{{$requerimiento->id}}" {{old("ACT_Requerimiento", $actividad->ID_Requerimiento) == $requerimiento->id ? 'selected' : '' }}>
                                                                                {{$requerimiento->REQ_Nombre_Requerimiento}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    {{$actividad->REQ_Nombre_Requerimiento}}
                                                                @endif
                                                            </td>
                                                            <td>{{$actividad->USR_Nombres_Usuario.' '.$actividad->USR_Apellidos_Usuario}}</td>
                                                            <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y H:i A.')}}</td>
                                                            <td>{{$actividad->EST_Nombre_Estado}}</td>
                                                            <td>
                                                                @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                    <form class="form-eliminar" action="{{route('eliminar_actividad', ['idA'=>$actividad->ID_Actividad])}}"
                                                                        class="d-inline" method="POST">
                                                                        <a href="{{route('editar_actividad_cliente', ['idA'=>$actividad->ID_Actividad])}}"
                                                                            class="btn-accion-tabla tooltipsC" title="Editar esta tarea">
                                                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                                        </a>
                                                                        @csrf @method("delete")
                                                                        <button type="submit" class="btn-accion-tabla eliminar tooltipsC"
                                                                            data-type="confirm" title="Eliminar esta tarea">
                                                                            <i class="material-icons text-danger"
                                                                                style="font-size: 17px;">delete_forever</i>
                                                                        </button>
                                                                    </form>
                                                                @endif
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
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Detalles de la actividad</h4>
                </div>
                <div class="modal-body">
                    <form class="form-eliminar" action="{{route('detalle_general_actividad')}}"
                        class="d-inline" method="POST">
                        @csrf
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
                        <input type="hidden" name="idActividad" id="idActividad" class="form-control" readonly="true">
                        <input type="hidden" id="nombreRequerimiento" class="form-control" readonly="true">
                        <div class="modal-footer">
                            <button id="verMas" type="submit" class="btn btn-link waves-effect">VER MÁS DETALLES</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
<script src="{{asset("assets/pages/scripts/PerfilOperacion/verDetalle.js")}}"></script>
<script>
    function cambioActividad(idR, idA) {
        $.ajax({
            dataType: "json",
            method: "put",
            url: "/actividades/" + idA +"/cambiar-requerimiento",
            data: {"_token":"{{ csrf_token() }}", ACT_Requerimiento:idR.value},
            success:function(respuesta){
                if(respuesta.mensaje == "ok"){
                    InkBrutalPRY.notificaciones('Tarea cambiada de requerimiento', 'InkBrutalPRY', 'success');
                    location.reload();
                }
                if(respuesta.mensaje == "ng")
                    InkBrutalPRY.notificaciones('Operación ha fallado', 'InkBrutalPRY', 'error');
            }
        });
    }
</script>
@endsection