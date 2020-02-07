@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Actividades
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
                <div class="body table-responsive">
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group" id="accordion_19" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-col-cyan">
                                    <div class="panel-heading" role="tab" id="headingTwo_19">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                href="#collapseTwo_19" aria-expanded="false"
                                                aria-controls="collapseTwo_19">
                                                <i class="material-icons">mood</i> EN PROCESO
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo_19" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_19">
                                        <div class="panel-body table-responsive">
                                            @if (count($actividadesProceso)<=0) 
                                                <div class="alert alert-info">
                                                    No hay datos que mostrar.
                                                </div>
                                            @else
                                                <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                    <thead>
                                                        <tr>
                                                            <th>Proyecto</th>
                                                            <th>Tarea</th>
                                                            <th>Descripción</th>
                                                            <th>Fecha de Entrega</th>
                                                            @foreach ($actividadesProceso as $actividad)
                                                                @if ($actividad->Horas != 0)
                                                                    <th>Horas Asignadas</th>
                                                                @endif
                                                                @break
                                                            @endforeach
                                                            <th class="width70"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($actividadesProceso as $actividad)
                                                            <tr>
                                                                <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                                <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                                                <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                                                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y H:s')}}</td>
                                                                @if ($actividad->Horas != 0)
                                                                    <td>{{$actividad->Horas}}</td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                <td class="width70">
                                                                    @if ($actividad->Horas != 0 && $actividad->HorasR!=null)
                                                                        @if (\Carbon\Carbon::now()->diffInHours($actividad->ACT_Fecha_Fin_Actividad) <= 24)
                                                                            <a href="#" class="btn-accion-tabla tooltipsC"
                                                                                title="Solicitar más tiempo">
                                                                                <i class="material-icons text-info"
                                                                                    style="font-size: 17px;">alarm_add</i>
                                                                            </a>
                                                                        @endif
                                                                        <a href="{{route('actividades_finalizar_perfil_operacion', ['id'=>$actividad->ID_Actividad])}}"
                                                                            class="btn-accion-tabla tooltipsC"
                                                                            title="Finalizar Tarea">
                                                                            <i class="material-icons text-info"
                                                                                style="font-size: 17px;">done_all</i>
                                                                        </a>
                                                                    @endif
                                                                    @if($actividad->Horas == 0)
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
                                    <div class="panel-heading" role="tab" id="headingThree_19">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                href="#collapseThree_19" aria-expanded="false"
                                                aria-controls="collapseThree_19">
                                                <i class="material-icons">mood_bad</i> ATRASADAS
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree_19" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingThree_19">
                                        <div class="panel-body table-responsive">
                                            @if (count($actividadesAtrasadas)<=0) 
                                                <div class="alert alert-info">
                                                    No hay datos que mostrar.
                                                </div>
                                            @else
                                                <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                    <thead>
                                                        <tr>
                                                            <th>Proyecto</th>
                                                            <th>Tarea</th>
                                                            <th>Descripción</th>
                                                            <th>Fecha Limite de Entrega</th>
                                                            <th class="width70"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($actividadesAtrasadas as $actividad)
                                                            <tr>
                                                                <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                                <td>{{$actividad->ACT_Nombre_Actividad}}</td>
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
                                    <div class="panel-heading" role="tab" id="headingFour_19">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse" href="#collapseFour_19"
                                                aria-expanded="false" aria-controls="collapseFour_19">
                                                <i class="material-icons">done_all</i> FINALIZADAS
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour_19" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingFour_19">
                                        <div class="panel-body table-responsive">
                                            @if (count($actividadesFinalizadas)<=0) 
                                                <div class="alert alert-info">
                                                    No hay datos que mostrar.
                                                </div>
                                            @else
                                                <table class="table table-striped table-bordered table-hover dataTable js-exportable"
                                                    id="tabla-data">
                                                    <thead>
                                                        <tr>
                                                            <th>Proyecto</th>
                                                            <th>Tarea</th>
                                                            <th>Descripción</th>
                                                            <th>Fecha de Finalización</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($actividadesFinalizadas as $actividad)
                                                            <tr>
                                                                <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                                <td>{{$actividad->ACT_Nombre_Actividad}}</td>
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
                    <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>
                </div>
                <form class="form-horizontal" action="">
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
                                        <input type="text" id="descripcionActividad" class="form-control" readonly="true">
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
                                <label for="tiempo">Tiempo</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" id="tiempo" class="form-control" placeholder="Digite la cantidad de tiempo a solicitar (En Horas)">
                                    </div>
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
</div>
@endsection
@section('scripts')
<script src="{{asset("assets/pages/scripts/PerfilOperacion/solicitudTiempo.js")}}"></script>
@endsection