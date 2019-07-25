@extends('theme.bsb.perfiloperacion.layout')
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
                    <h2>
                        MIS ACTIVIDADES
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group" id="accordion_19" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-col-orange">
                                    <div class="panel-heading" role="tab" id="headingOne_19">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" href="#collapseOne_19"
                                                aria-expanded="true" aria-controls="collapseOne_19">
                                                <i class="material-icons">sentiment_neutral</i> ESTANCADAS
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne_19" class="panel-collapse collapse in" role="tabpanel"
                                        aria-labelledby="headingOne_19">
                                        <div class="panel-body table-responsive">
                                            @if (count($actividadesEstancadas)<=0) <div class="alert alert-warning">
                                                <strong>Advertencia!</strong> No tiene actividades asignadas
                                        </div>
                                        @else
                                        <table class="table table-striped table-bordered table-hover" id="tabla-data">
                                            <thead>
                                                <tr>
                                                    <th>Proyecto</th>
                                                    <th>Actividad</th>
                                                    <th>Descripción</th>
                                                    <th>Fechas</th>
                                                    <th class="width70"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($actividadesEstancadas as $actividad)
                                                <tr>
                                                    <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                    <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                                    <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                                    <td>{{$actividad->ACT_Fecha_Inicio_Actividad.' - '.$actividad->ACT_Fecha_Fin_Actividad}}
                                                    </td>
                                                    <td>
                                                        <a href="{{route('actividades_asignar_horas_perfil_operacion', ['id'=>$actividad->ID_Actividad])}}"
                                                            class="btn-accion-tabla tooltipsC"
                                                            title="Asignar horas de Trabajo">
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
                            <div class="panel panel-col-cyan">
                                <div class="panel-heading" role="tab" id="headingTwo_19">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseTwo_19"
                                            aria-expanded="false" aria-controls="collapseTwo_19">
                                            <i class="material-icons">mood</i> EN PROCESO
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo_19" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="headingTwo_19">
                                    <div class="panel-body table-responsive">
                                        @if (count($actividadesProceso)<=0) <div class="alert alert-warning">
                                            <strong>Advertencia!</strong> No tiene actividades en proceso
                                    </div>
                                    @else
                                    <table class="table table-striped table-bordered table-hover" id="tabla-data">
                                        <thead>
                                            <tr>
                                                <th>Proyecto</th>
                                                <th>Actividad</th>
                                                <th>Descripción</th>
                                                <th>Fecha Limite de Entrega</th>
                                                <th>Horas Asignadas</th>
                                                <th class="width70"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($actividadesProceso as $actividad)
                                            <tr>
                                                <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                                <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                                <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                                <td>{{$actividad->ACT_Fecha_Fin_Actividad}}</td>
                                                <td>{{$actividad->HRS_ACT_Cantidad_Horas}}</td>
                                                <td>
                                                    <a href="#" class="btn-accion-tabla tooltipsC"
                                                        title="Solicitar más tiempo">
                                                        <i class="material-icons text-info"
                                                            style="font-size: 17px;">alarm_add</i>
                                                    </a>
                                                    <a href="#" class="btn-accion-tabla tooltipsC"
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
                            </div>
                        </div>
                        <div class="panel panel-col-red">
                            <div class="panel-heading" role="tab" id="headingThree_19">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" href="#collapseThree_19"
                                        aria-expanded="false" aria-controls="collapseThree_19">
                                        <i class="material-icons">mood_bad</i> ATRASADAS
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree_19" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingThree_19">
                                <div class="panel-body table-responsive">
                                    @if (count($actividadesAtrasadas)<=0) <div class="alert alert-success">
                                        <strong>Felicitaciones!</strong> No tiene actividades atrasadas
                                </div>
                                @else
                                <table class="table table-striped table-bordered table-hover" id="tabla-data">
                                    <thead>
                                        <tr>
                                            <th>Proyecto</th>
                                            <th>Actividad</th>
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
                                            <td>{{$actividad->ACT_Fecha_Fin_Actividad}}</td>
                                            <td>
                                                <a href="#" class="btn-accion-tabla tooltipsC"
                                                    title="Solicitar más Tiempo">
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Multiple Items To Be Open -->
</div>
@endsection