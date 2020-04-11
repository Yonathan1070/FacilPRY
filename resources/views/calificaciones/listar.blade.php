@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Listar Calificaciones
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
                        CALIFICACIONES
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['listar']==true)
                                <a class="btn btn-danger waves-effect" href="{{route('decisiones')}}"><i
                                    class="material-icons" style="color:white;">arrow_back</i> Volver a Desiciones</a>
                            @endif
                            @if ($permisos['calificar']==true)
                                <a
                                    class="btn bg-light-green btn-circle-lg waves-effect waves-circle waves-float tooltipsC"
                                    title="Calificar Trabajadores"
                                    data-toggle="modal"
                                    data-target="#modalFechas"
                                >
                                    <i class="material-icons" style="color:white;">equalizer</i>
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                        <thead>
                            <tr>
                                <th>Perfil Operación</th>
                                <th>Decisión</th>
                                <th>Calificación</th>
                                <th>Fecha</th>
                                <th class="width70"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($calificaciones as $calificacion)
                            <tr>
                                <td>{{$calificacion->USR_Nombres_Usuario.' '.$calificacion->USR_Apellidos_Usuario}}</td>
                                <td>{{$calificacion->DCS_Nombre_Decision}}</td>
                                <td>{{$calificacion->CALIF_calificacion}}</td>
                                <td>{{$calificacion->CALIF_Fecha_Calificacion}}</td>
                                <td>
                                    <a
                                        id="{{$calificacion->Id_Calificacion}}"
                                        onclick="detalle(this)"
                                        class="btn-accion-tabla tooltipsC"
                                        title="Ver Detalles"
                                        data-toggle="modal"
                                        data-target="#defaultModal"
                                    >
                                        <i
                                            class="material-icons text-info"
                                            style="font-size: 17px;"
                                        >
                                            remove_red_eye
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Detalles de la calificación</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="perfilOperacion">Perfil de operación</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="perfilOperacion" class="form-control" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="decisionTomada">Decisión tomada</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="decisionTomada" class="form-control"readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="descripcionDecision">Descripción de la decisión tomada</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea name="" id="descripcionDecision" readonly="true" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="calificacionObtenida">Calificación obtenida</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="calificacionObtenida" class="form-control" readonly="true">
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

    <div class="modal fade" id="modalFechas" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Elija un rango de fechas para calificar</h4>
                </div>
                <form class="form_validation" action="{{route('calificar_trabajadores')}}" id="formularioCalificar" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="perfilOperacion">Fecha Inicio</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group form-float">
                                    <div class="form-line focused">
                                        <input type="date" class="form-control" name="Fecha_Inicio_Rango"
                                            id="Fecha_Inicio_Rango"
                                            required>
                                    </div>
                                    <div class="help-info">Fecha de Inicio</div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="decisionTomada">Fecha Fin</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group form-float">
                                    <div class="form-line focused">
                                        <input type="date" class="form-control" name="Fecha_Fin_Rango" id="Fecha_Fin_Rango"
                                            required>
                                    </div>
                                    <div class="help-info">Fecha Fin</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-link waves-effect">CALIFICAR</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset("assets/pages/scripts/Director/calificacionDetalle.js")}}"></script>
@endsection