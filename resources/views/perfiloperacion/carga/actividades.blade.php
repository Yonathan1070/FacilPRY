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
                <ul>
                    <li>
                        <div class="title">
                            <i class="material-icons">trending_up</i>
                            Cuadro de desempeño
                        </div>
                        <div class="content">
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
                                        <td>
                                            <a id="{{$actividad->Id_Actividad}}" onclick="detalleActividad(this)" class="btn-accion-tabla tooltipsC"
                                                title="Ver Detalles" data-toggle="modal" data-target="#defaultModal">
                                                {{$actividad->ACT_Nombre_Actividad}}
                                            </a>
                                        </td>
                                        <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                        <td>{{$actividad->REQ_Nombre_Requerimiento}}</td>
                                        <td>{{$actividad->ACT_Fecha_Fin_Actividad}}</td>
                                        @if ($actividad->HorasE == 0)
                                            <td>
                                                (Asignar horas de trabajo)
                                                @if ($actividad->HorasE == 0 && $actividad->HorasR == null && $actividad->ACT_Estado_Id == 1)
                                                    <a href="{{route('actividades_asignar_horas_perfil_operacion', ['id'=>$actividad->Id_Actividad])}}"
                                                        class="btn-accion-tabla tooltipsC" title="Asignar Horas">
                                                        <i class="material-icons text-info"
                                                            style="font-size: 17px;">alarm</i>
                                                    </a>
                                                @endif
                                            </td>
                                        @elseif ($actividad->HorasR == null && $actividad->HorasE != 0)
                                            <td>{{$actividad->HorasE}} Horas (En espera de aprobación de horas de trabajo)</td>
                                        @else
                                            <td>
                                                {{$actividad->HorasR}} Horas
                                                @if ($actividad->HorasE != 0 && $actividad->HorasR != null && $actividad->ACT_Estado_Id == 1)
                                                    <a href="{{route('actividades_finalizar_perfil_operacion', ['id'=>$actividad->Id_Actividad])}}"
                                                        class="btn-accion-tabla tooltipsC"
                                                        title="Finalizar Tarea">
                                                        <i class="material-icons text-info"
                                                            style="font-size: 17px;">done_all</i>
                                                    </a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">poll</i>
                            Porcentaje de actividades
                        </div>
                        <div class="content">
                            <h3>
                                <span class="label label-info">En Proceso</span>
                                <span class="label label-danger">Atrasada</span>
                                <span class="label label-success">Finalizada</span>
                            </h3>
                            <div class="progress">
                                <div
                                    class="progress-bar bg-cyan progress-bar-striped active"
                                    role="progressbar"
                                    style="width: {{$porcentajeProceso}}%"
                                >
                                {{$porcentajeProceso}} %
                                </div>
                                <div
                                    class="progress-bar bg-red progress-bar-striped active"
                                    role="progressbar"
                                    style="width: {{$porcentajeAtrasado}}%"
                                >
                                    {{$porcentajeAtrasado}} %
                                </div>
                                <div
                                    class="progress-bar bg-green progress-bar-striped active"
                                    role="progressbar"
                                    style="width: {{$porcentajeFinalizado}}%"
                                >
                                    {{$porcentajeFinalizado}} %
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">pie_chart</i>
                            Métricas
                        </div>
                        <div class="content">
                            <div id="pie" class="row clearfix">
                                <!-- Pie Chart -->
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>Indicador de Eficacia General</h2>
                                        </div>
                                        <div class="body">
                                            <canvas id="chartEficacia" width="400" height="400"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Pie Chart -->
                                <!-- Pie Chart -->
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>Indicador de Eficiencia General</h2>
                                        </div>
                                        <div class="body">
                                            <canvas id="chartEficiencia" width="400" height="400"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Pie Chart -->
                                <!-- Pie Chart -->
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>Indicador de Efectividad General</h2>
                                        </div>
                                        <div class="body">
                                            <canvas id="chartEfectividad" width="400" height="400"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <!-- #END# Pie Chart -->
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Detalles de la tarea</h4>
            </div>
            <div class="modal-body">
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
                                <textarea name="" id="descripcionActividadDetalle" readonly="true" class="form-control"></textarea>
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
                        <label for="fechaInicioActividadDetalle">Inicio</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" id="fechaInicioActividadDetalle" class="form-control" readonly="true">
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
                                <input type="text" id="fechaFinActividadDetalle" class="form-control" readonly="true">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="idActividad" id="idActividad" class="form-control" readonly="true">
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
<script src="{{asset("assets/pages/scripts/PerfilOperacion/verDetalle.js")}}"></script>

<!-- Chart Plugins Js -->
<script src="{{asset("assets/bsb/plugins/chartjs/Chart.bundle.js")}}"></script>

<script src="{{asset("assets/bsb/js/pages/charts/chartjs.js")}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js" charset=utf-8></script>

<script>
    var chEficacia = document.getElementById('chartEficacia');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/perfil-operacion/eficacia-carga"
    }).done(function (chartEficacia) {
        if(chartEficacia != null){
            var charEficacia = new Chart(chEficacia, {
                type: chartEficacia.type,
                data: {
                    labels: chartEficacia.labels,
                    datasets: [{
                        label: chartEficacia.label,
                        data: chartEficacia.data,
                        backgroundColor: chartEficacia.backgroundColor,
                        borderWidth: chartEficacia.borderWidth
                    }]
                }
            });
        }
    });
</script>

<script>
    var chEficiencia = document.getElementById('chartEficiencia');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/perfil-operacion/eficiencia-carga"
    }).done(function (chartEficiencia) {
        if(chartEficiencia != null){
            var chartEficiencia = new Chart(chEficiencia, {
        
                type: chartEficiencia.type,
                data: {
                    labels: chartEficiencia.labels,
                    datasets: [{
                        label: chartEficiencia.label,
                        data: chartEficiencia.data,
                        backgroundColor: chartEficiencia.backgroundColor,
                        borderWidth: chartEficiencia.borderWidth
                    }]
                }
            });
        }
    });
</script>

<script>
    var chEfectividad = document.getElementById('chartEfectividad');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/perfil-operacion/efectividad-carga"
    }).done(function (chartEfectividad) {
        if(chartEfectividad != null){
            var chartEfectividad = new Chart(chEfectividad, {
        
                type: chartEfectividad.type,
                data: {
                    labels: chartEfectividad.labels,
                    datasets: [{
                        label: chartEfectividad.label,
                        data: chartEfectividad.data,
                        backgroundColor: chartEfectividad.backgroundColor,
                        borderWidth: chartEfectividad.borderWidth
                    }]
                }
            });
        }
    });
</script>
@endsection