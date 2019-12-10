@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Inicio
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
                            ACTIVIDADES
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                    <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-col-pink">
                                <div class="panel-heading" role="tab" id="headingOne_17">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="false" aria-controls="collapseOne_17">
                                                <i class="material-icons">contact_mail</i> PENDIENTES DE ENTREGA
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17">
                                    <div class="panel-body">
                                        <div>
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#proceso" aria-controls="settings" role="tab" data-toggle="tab">En Proceso</a></li>
                                                <li role="presentation"><a href="#entregado" aria-controls="settings" role="tab" data-toggle="tab">Entregado</a></li>
                                            </ul>

                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="proceso">
                                                    @if (count($actividadesEntregar)<=0)
                                                        <div class="alert alert-info">
                                                            No cuenta con Actividades para entregar.
                                                        </div>
                                                    @else
                                                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                            <thead>
                                                                <tr>
                                                                    <th>Proyecto</th>
                                                                    <th>Actividad</th>
                                                                    <th>Descripción</th>
                                                                    <th class="width70"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($actividadesEntregar as $actividad)
                                                                    <tr>
                                                                        <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                                        <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                                                        <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                                                        <td>
                                                                            <a href="{{route('actividades_finalizar_cliente', ['id'=>$actividad->Id_Actividad])}}"
                                                                                class="btn-accion-tabla tooltipsC"
                                                                                title="Finalizar Actividad">
                                                                                <i class="material-icons text-info"
                                                                                    style="font-size: 17px;">done_all</i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade in" id="entregado">
                                                        @if (count($actividadesFinalizadas)<=0)
                                                        <div class="alert alert-info">
                                                            No cuenta con Actividades para entregar.
                                                        </div>
                                                    @else
                                                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                            <thead>
                                                                <tr>
                                                                    <th>Proyecto</th>
                                                                    <th>Actividad</th>
                                                                    <th>Descripción</th>
                                                                    <th>Estado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($actividadesFinalizadas as $actividad)
                                                                    <tr>
                                                                        <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                                        <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                                                        <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
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
                            <div class="panel panel-col-cyan">
                                <div class="panel-heading" role="tab" id="headingTwo_17">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseTwo_17" aria-expanded="false" aria-controls="collapseTwo_17">
                                            <i class="material-icons">contacts</i> PARA APROBAR
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17">
                                    <div class="panel-body table-responsive">
                                        @if (count($actividadesPendientes)<=0)
                                            <div class="alert alert-info">
                                                No cuenta con Actividades pendientes de aprobación.
                                            </div>
                                        @else
                                            <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                <thead>
                                                    <tr>
                                                        <th>Proyecto</th>
                                                        <th>Actividad</th>
                                                        <th>Requerimiento</th>
                                                        <th class="width70"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($actividadesPendientes as $actividad)
                                                        <tr>
                                                            <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                            <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                                            <td>{{$actividad->REQ_Nombre_Requerimiento}}</td>
                                                            <td>
                                                                <a href="{{route('aprobar_actividad_cliente', ['id'=>$actividad->Id_Act_Fin])}}" class="btn-accion-tabla tooltipsC" title="Ver información detallada">
                                                                    <i class="material-icons text-info" style="font-size: 17px;">forward</i>
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
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection