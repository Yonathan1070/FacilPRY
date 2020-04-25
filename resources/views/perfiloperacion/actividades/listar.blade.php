@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Tareas
@endsection
@section('styles')
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{asset('assets/bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
@endsection
@section('contenido')
<div class="container-fluid">
    <!-- Multiple Items To Be Open -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>MIS TAREAS</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('generar_pdf_perfil_operacion')}}">
                                <i class="material-icons" style="color:white;">file_download</i> Descargar PDF
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-col-cyan">
                                    <div class="panel-heading" role="tab" id="headingOne_17">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="false" aria-controls="collapseOne_17">
                                                <i class="material-icons">mood</i> EN PROCESO
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17">
                                        <div class="panel-body table-responsive">
                                            @if (count($actividadesProceso)<=0) 
                                                <div class="alert alert-info">
                                                    No hay datos que mostrar.
                                                </div>
                                            @else
                                                <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                    <thead>
                                                        <tr>
                                                            <th>Tarea</th>
                                                            <th>Descripción</th>
                                                            <th>Fecha de Entrega</th>
                                                            <th>Horas Asignadas</th>
                                                            <th class="width70"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($actividadesProceso as $actividad)
                                                            <tr>
                                                                <td>
                                                                    <a id="{{$actividad->ID_Actividad}}" onclick="detalleActividad(this)" class="btn-accion-tabla tooltipsC"
                                                                        title="Ver detalles" data-toggle="modal" data-target="#modalDetalles">
                                                                        {{$actividad->ACT_Nombre_Actividad}}
                                                                    </a>
                                                                </td>
                                                                <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                                                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y H:s')}}</td>
                                                                @if ($actividad->Horas != 0)
                                                                    <td>{{$actividad->Horas}}</td>
                                                                @else
                                                                    <td>No asignadas</td>
                                                                @endif
                                                                <td class="width70">
                                                                    @if ($actividad->Horas != 0 && $actividad->HorasR!=null)
                                                                        <a href="{{route('actividades_finalizar_perfil_operacion', ['id'=>$actividad->ID_Actividad])}}"
                                                                            class="btn-accion-tabla tooltipsC"
                                                                            title="Finalizar Tarea">
                                                                            <i class="material-icons text-info"
                                                                                style="font-size: 17px;">done_all</i>
                                                                        </a>
                                                                    @endif
                                                                    @if ($actividad->Horas == 0)
                                                                        <a href="{{route('actividades_asignar_horas_perfil_operacion', ['id'=>$actividad->ID_Actividad])}}"
                                                                            class="btn-accion-tabla tooltipsC" title="Asignar Horas">
                                                                            <i class="material-icons text-info"
                                                                                style="font-size: 17px;">alarm</i>
                                                                        </a>
                                                                    @endif
                                                                    @if ($actividad->DOC_Actividad_Id != null)
                                                                        <a href="{{route('actividades_descargar_archivo_perfil_operacion', ['id'=>$actividad->ID_Actividad])}}"
                                                                            class="btn-accion-tabla tooltipsC"
                                                                            title="Descargar documento soporte">
                                                                            <i class="material-icons text-info"
                                                                                style="font-size: 17px;">get_app</i>
                                                                        </a>
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
                                <div class="panel panel-col-red">
                                    <div class="panel-heading" role="tab" id="headingTwo_17">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseTwo_17" aria-expanded="false" aria-controls="collapseTwo_17">
                                                <i class="material-icons">mood_bad</i> ATRASADAS
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17">
                                        <div class="panel-body table-responsive">
                                            @if (count($actividadesAtrasadas)<=0) 
                                                <div class="alert alert-info">
                                                    No hay datos que mostrar.
                                                </div>
                                            @else
                                                <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                    <thead>
                                                        <tr>
                                                            <th>Tarea</th>
                                                            <th>Descripción</th>
                                                            <th>Fecha Limite de Entrega</th>
                                                            <th class="width70"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($actividadesAtrasadas as $actividad)
                                                            <tr>
                                                                <td>
                                                                    <a id="{{$actividad->ID_Actividad}}" onclick="detalleActividad(this)" class="btn-accion-tabla tooltipsC"
                                                                        title="Ver detalles" data-toggle="modal" data-target="#modalDetalles">
                                                                        {{$actividad->ACT_Nombre_Actividad}}
                                                                    </a>
                                                                </td>
                                                                <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                                                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y H:s')}}</td>
                                                                <td>
                                                                    <a id="{{$actividad->ID_Actividad}}" onclick="tiempo(this)" class="btn-accion-tabla tooltipsC"
                                                                        title="Solicitar más Tiempo" data-toggle="modal" data-target="#defaultModal">
                                                                        <i class="material-icons text-info"
                                                                            style="font-size: 20px;">alarm_add</i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-col-green">
                                    <div class="panel-heading" role="tab" id="headingThree_17">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseThree_17" aria-expanded="false" aria-controls="collapseThree_17">
                                                <i class="material-icons">done_all</i> FINALIZADAS
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_17">
                                        <div class="panel-body table-responsive">
                                            @if (count($actividadesFinalizadas)<=0) 
                                                <div class="alert alert-info">
                                                    No cuenta con actividades finalizadas en los ultimos 8 días
                                                </div>
                                            @else
                                                <table class="table table-striped table-bordered table-hover dataTable js-exportable"
                                                    id="tabla-data">
                                                    <thead>
                                                        <tr>
                                                            <th>Tarea</th>
                                                            <th>Descripción</th>
                                                            <th>Fecha de Finalización</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($actividadesFinalizadas as $actividad)
                                                            <tr>
                                                                <td>
                                                                    <a id="{{$actividad->ID_Actividad}}" onclick="detalleActividad(this)" class="btn-accion-tabla tooltipsC"
                                                                        title="Ver detalles" data-toggle="modal" data-target="#modalDetalles">
                                                                        {{$actividad->ACT_Nombre_Actividad}}
                                                                    </a>
                                                                </td>
                                                                <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                                                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_FIN_Fecha_Finalizacion)->format('d/m/Y H:s')}}</td>
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
    </div>
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Solicitud de Tiempo</h4>
                </div>
                <form class="form_validation" action="" id="formularioSolicitud" method="POST">
                    @csrf
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
                                        <input type="text" id="descripcionActividad" name="descripcionActividad" class="form-control" readonly="true">
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
                                        <input type="text" id="fechaInicioActividad" name="fechaInicioActividad" class="form-control" readonly="true">
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
                                        <input type="text" id="fechaFinActividad" name="fechaFinActividad" class="form-control" readonly="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="tiempo">Tiempo</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" min="1" max="15" name="Hora_Solicitud" id="Hora_Solicitud" placeholder="Cantidad de horas a solicitar"
                                            value="{{old('Hora_Solicitud' ?? '')}}" required>
                                    </div>
                                    <div class="help-info">Horas necesarias</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-link waves-effect">Enviar Solicitud</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Detalles de la tarea</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="nombreEmpresaDetalle">Empresa</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="nombreEmpresaDetalle" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="nombreProyectoDetalle">Proyecto</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="nombreProyectoDetalle" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="nombreRequerimientoDetalle">Actividad</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="nombreRequerimientoDetalle" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="nombreActividadDetalle">Tarea</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="nombreActividadDetalle" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="descripcionActividadDetalle">Descripción</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea name="descripcionActividadDetalle" id="descripcionActividadDetalle" class="form-control" readonly="true">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="fechaInicioActividadDetalle">Inicio</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="fechaInicioActividadDetalle" name="fechaInicioActividad" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="fechaFinActividadDetalle">Fin</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="fechaFinActividadDetalle" name="fechaFinActividad" class="form-control" readonly="true">
                                </div>
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
@endsection
@section('scripts')
    <script src="{{asset("assets/pages/scripts/PerfilOperacion/solicitudTiempo.js")}}"></script>

    <script src="{{asset("assets/pages/scripts/PerfilOperacion/verDetalle.js")}}"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js")}}"></script>

    <!-- Select Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js")}}"></script>
    <!-- Input Mask Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/jquery-inputmask/jquery.inputmask.bundle.js")}}"></script>
    
    <script src="{{asset("assets/bsb/js/pages/forms/basic-form-elements.js")}}"></script>
@endsection