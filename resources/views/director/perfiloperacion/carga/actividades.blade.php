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
                    CARGA DE TRABAJO ({{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}})
                </h2>
                <ul class="header-dropdown" style="top:10px;">
                    <li class="dropdown">
                        <a class="btn btn-success waves-effect" href="{{route('pdf_carga_perfil_operacion', ['id'=>$perfilOperacion->id])}}"><i
                            class="material-icons" style="color:white;">file_download</i> Descargar</a>
                    </li>
                </ul>
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
                                        <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                        <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                        <td>{{$actividad->REQ_Nombre_Requerimiento}}</td>
                                        <td>{{$actividad->ACT_Fecha_Fin_Actividad}}</td>
                                        @if ($actividad->HorasE == 0)
                                            <td>(Asignar horas de trabajo)</td>
                                        @elseif ($actividad->HorasR == null && $actividad->HorasE != 0)
                                            <td>
                                                {{$actividad->HorasE}} Horas (En espera de aprobación de horas de trabajo)
                                                @if ($actividad->ACT_Encargado_Id == session()->get('Usuario_Id') && $actividad->ACT_Estado_Id == 1)
                                                    <a href="{{route('aprobar_horas_actividad', ['idH'=>$actividad->Id_Actividad])}}"
                                                        class="btn-accion-tabla tooltipsC" title="Aprobar horas de trabajo">
                                                        <i class="material-icons text-success" style="font-size: 17px;">alarm_on</i>
                                                    </a>
                                                @endif
                                            </td>
                                        @else
                                            <td>{{$actividad->HorasR}} Horas</td>
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
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
@endsection
@section('scripts')
<script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
<script src="{{asset("assets/pages/scripts/PerfilOperacion/verDetalle.js")}}"></script>


<!-- Chart Plugins Js -->
<script src="{{asset("assets/bsb/plugins/chartjs/Chart.bundle.js")}}"></script>

<script src="{{asset("assets/bsb/js/pages/charts/chartjs.js")}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js" charset=utf-8></script>

<script>
    //Se obtiene el valor de la URL desde el navegador
    var actual = window.location+'';
    //Se realiza la división de la URL
    var split = actual.split("/");
    //Se obtiene el ultimo valor de la URL
    var id = split[split.length-2];

    var chEficacia = document.getElementById('chartEficacia');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/eficacia-carga/"+id
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
    //Se obtiene el valor de la URL desde el navegador
    var actual = window.location+'';
    //Se realiza la división de la URL
    var split = actual.split("/");
    //Se obtiene el ultimo valor de la URL
    var id = split[split.length-2];

    var chEficiencia = document.getElementById('chartEficiencia');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/eficiencia-carga/"+id
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
    //Se obtiene el valor de la URL desde el navegador
    var actual = window.location+'';
    //Se realiza la división de la URL
    var split = actual.split("/");
    //Se obtiene el ultimo valor de la URL
    var id = split[split.length-2];

    var chEfectividad = document.getElementById('chartEfectividad');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/efectividad-carga/"+id
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