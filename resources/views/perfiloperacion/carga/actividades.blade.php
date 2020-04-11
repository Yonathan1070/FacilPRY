@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Listar Carga de Trabajo
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
    <!-- Colorful Panel Items With Icon -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @include('includes.form-exito')
        @include('includes.form-error')
        <div class="card card-about-me">
            <div class="header">
                <h2>
                    CARGA DE TRABAJO
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                    <thead>
                        <tr>
                            <th>Tarea</th>
                            <th>Descripción</th>
                            <th>Actividad</th>
                            <th>Fecha de entrega</th>
                            <th>Horas de trabajo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($actividades as $actividad)
                        <tr>
                            <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                            <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                            <td>{{$actividad->REQ_Nombre_Requerimiento}}</td>
                            <td>{{$actividad->ACT_Fecha_Fin_Actividad}}</td>
                            @if ($actividad->HorasE == 0)
                                <td>(Asignar horas de trabajo)</td>
                            @elseif ($actividad->HorasR == null && $actividad->HorasE != 0)
                                <td>{{$actividad->HorasE}} Horas (En espera de aprobación de horas de trabajo)</td>
                            @else
                                <td>{{$actividad->HorasR}} Horas</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <ul>
                    <li></li>
                    <li>
                        <div class="title">
                            <i class="material-icons">location_on</i>
                            Porcentaje Finalizado
                        </div>
                        <div class="content">
                            <div class='progress'>
                                <div
                                    class="progress-bar bg-green progress-bar-striped active"
                                    role="progressbar"
                                    aria-valuenow="{{$porcentajeFinalizado}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                    style="width: {{$porcentajeFinalizado}}%"
                                >
                                    {{$porcentajeFinalizado}} %
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">edit</i>
                            Porcentaje Atrasado
                        </div>
                        <div class="content">
                            <div class='progress'>
                                <div
                                    class="progress-bar bg-red progress-bar-striped active"
                                    role="progressbar"
                                    aria-valuenow="{{$porcentajeAtrasado}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                    style="width: {{$porcentajeAtrasado}}%"
                                >
                                    {{$porcentajeAtrasado}} %
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">notes</i>
                            Porcentaje En Proceso
                        </div>
                        <div class="content">
                            <div class='progress'>
                                <div
                                    class="progress-bar bg-cyan progress-bar-striped active"
                                    role="progressbar"
                                    aria-valuenow="{{$porcentajeProceso}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                    style="width: {{$porcentajeProceso}}%"
                                >
                                    {{$porcentajeProceso}} %
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
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