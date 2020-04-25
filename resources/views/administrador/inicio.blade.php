@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Inicio
@endsection
@section('styles')
    <style>
        .fraction {
            display: inline-block;
            vertical-align: middle; 
            margin: 0 0.2em 0.4ex;
            text-align: center;
        }
        .fraction > span {
            display: block;
            padding-top: 0.15em;
        }
        .fraction span.fdn {border-top: thin solid black;}
        .fraction span.bar {display: none;}
    </style>
@endsection
@section('contenido')
@include('includes.form-exito')
@include('includes.form-error')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Filtrar Metricas</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select name="opcionFiltro" id="opcionFiltro" class="form-control">
                                        <option value="">-- Seleccione una opción --</option>
                                        <option value="1">Por Proyecto</option>
                                        <option value="2">Por Trabajador</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="trabajadores" style="display:none" class="col-lg-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select name="trabajadoresL" id="trabajadoresL" class="form-control">
                                        <option value="">-- Seleccione un Trabajador --</option>
                                        @foreach ($trabajadores as $trabajador)
                                            <option value="{{$trabajador->id}}">{{$trabajador->USR_Nombres_Usuario.' '.$trabajador->USR_Apellidos_Usuario}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="pie" class="row clearfix">
        <!-- Pie Chart -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficacia General</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <button
                                type="button"
                                class="dropdown-toggle"
                                style="border: transparent; background: transparent;"
                                data-trigger="focus"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="left"
                                title="Detalle de la eficacia"
                                data-html="true"
                                data-content="El resultado de la eficacia la obtenemos con la formula:<br />
                                    <div class='fraction'>
                                        <span class='fup'>tf</span>
                                        <span class='bar'>/</span>
                                        <span class='fdn'>tt</span>
                                    </div>*100<br /><br />
                                    Donde:<br />
                                    tf = Tareas Finalizadas<br />
                                    tt = Tareas Totales">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </li>
                    </ul>
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
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <button
                                type="button"
                                class="dropdown-toggle"
                                style="border: transparent; background: transparent;"
                                data-trigger="focus"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="left"
                                title="Detalle de la eficiencia"
                                data-html="true"
                                data-content="El resultado de la eficiencia la obtenemos con la formula:<br />
                                    (<div class='fraction'>
                                        <span class='fup'>
                                            (<div class='fraction'>
                                                <span class='fup'>tf</span>
                                                <span class='bar'>/</span>
                                                <span class='fdn'>cr</span>
                                            </div>*hr)
                                        </span>
                                        <span class='bar'>/</span>
                                        <span class='fdn'>
                                            (<div class='fraction'>
                                                <span class='fup'>tt</span>
                                                <span class='bar'>/</span>
                                                <span class='fdn'>ce</span>
                                            </div>*he)
                                        </span>
                                    </div>)*100<br /><br />
                                    Donde:<br />
                                    tf = Tareas Finalizadas<br />
                                    cr = Costo Real<br />
                                    hr = Horas Reales<br />
                                    tt = Tareas Totales<br />
                                    ce = Costo Estimado<br />
                                    he = Horas Estimadas">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <canvas id="chartEficiencia" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
        <!-- Pie Chart -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Efectividad General</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <button
                                type="button"
                                class="dropdown-toggle"
                                style="border: transparent; background: transparent;"
                                data-trigger="focus"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="left"
                                title="Detalle de la efectividad"
                                data-html="true"
                                data-content="El resultado de la efectividad la obtenemos con la formula:<br />
                                    <div class='fraction'>
                                        <span class='fup'>Eficacia</span>
                                        <span class='bar'>/</span>
                                        <span class='fdn'>Eficiencia</span>
                                    </div>">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <canvas id="chartEfectividad" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
        <!-- Pie Chart -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Productividad</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <button
                                type="button"
                                class="dropdown-toggle"
                                style="border: transparent; background: transparent;"
                                data-trigger="focus"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="left"
                                title="Detalle de la Productividad"
                                data-html="true"
                                data-content="El resultado de la productividad la obtenemos con la formula:<br />
                                    <div class='fraction'>
                                        <span class='fup'>cr</span>
                                        <span class='bar'>/</span>
                                        <span class='fdn'>ce</span>
                                    </div>*100<br /><br />
                                    Donde:<br />
                                    cr = Costo real<br />
                                    ce = Costo Estimado">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <canvas id="chartProductividad" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
    </div>
    <div id="barras" style="display:none" class="row clearfix">
        <!-- Pie Chart -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficacia Por Trabajadores</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <button
                                type="button"
                                class="dropdown-toggle"
                                style="border: transparent; background: transparent;"
                                data-trigger="focus"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="left"
                                title="Detalle de la eficacia"
                                data-html="true"
                                data-content="El resultado de la eficacia la obtenemos con la formula:<br />
                                    <div class='fraction'>
                                        <span class='fup'>tf</span>
                                        <span class='bar'>/</span>
                                        <span class='fdn'>tt</span>
                                    </div>*100<br /><br />
                                    Donde:<br />
                                    tf = Tareas Finalizadas<br />
                                    tt = Tareas Totales">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <canvas id="chartEficaciaBar" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
        <!-- Pie Chart -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficiencia Por Trabajadores</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <button
                                type="button"
                                class="dropdown-toggle"
                                style="border: transparent; background: transparent;"
                                data-trigger="focus"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="left"
                                title="Detalle de la eficiencia"
                                data-html="true"
                                data-content="El resultado de la eficiencia la obtenemos con la formula:<br />
                                    (<div class='fraction'>
                                        <span class='fup'>
                                            (<div class='fraction'>
                                                <span class='fup'>tf</span>
                                                <span class='bar'>/</span>
                                                <span class='fdn'>cr</span>
                                            </div>*hr)
                                        </span>
                                        <span class='bar'>/</span>
                                        <span class='fdn'>
                                            (<div class='fraction'>
                                                <span class='fup'>tt</span>
                                                <span class='bar'>/</span>
                                                <span class='fdn'>ce</span>
                                            </div>*he)
                                        </span>
                                    </div>)*100<br /><br />
                                    Donde:<br />
                                    tf = Tareas Finalizadas<br />
                                    cr = Costo Real<br />
                                    hr = Horas Reales<br />
                                    tt = Tareas Totales<br />
                                    ce = Costo Estimado<br />
                                    he = Horas Estimadas">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <canvas id="chartEficienciaBar" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
        <!-- Pie Chart -->
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Efectividad Por Trabajadores</h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <button
                                type="button"
                                class="dropdown-toggle"
                                style="border: transparent; background: transparent;"
                                data-trigger="focus"
                                data-container="body"
                                data-toggle="popover"
                                data-placement="left"
                                title="Detalle de la efectividad"
                                data-html="true"
                                data-content="El resultado de la efectividad la obtenemos con la formula:<br />
                                    <div class='fraction'>
                                        <span class='fup'>Eficacia</span>
                                        <span class='bar'>/</span>
                                        <span class='fdn'>Eficiencia</span>
                                    </div>">
                                <i class="material-icons">more_vert</i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <canvas id="chartEfectividadBar" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
    </div>
</div>
@endsection
@section('scripts')
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
        url: "/eficacia"
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
    var chEficaciaBar = document.getElementById('chartEficaciaBar');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/barraseficacia"
    }).done(function (chartEficaciaBar) {
        if(chartEficaciaBar != null){
            var charEficaciaBar = new Chart(chEficaciaBar, {
                type: chartEficaciaBar.type,
                data: {
                    labels: chartEficaciaBar.labels,
                    datasets: [{
                        label: chartEficaciaBar.label,
                        data: chartEficaciaBar.data,
                        backgroundColor: chartEficaciaBar.backgroundColor,
                        borderWidth: chartEficaciaBar.borderWidth
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
        url: "/eficiencia"
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
    var chEficienciaBar = document.getElementById('chartEficienciaBar');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/barraseficiencia"
    }).done(function (chartEficienciaBar) {
        if(chartEficienciaBar != null){
            var chartEficienciaBar = new Chart(chEficienciaBar, {
        
                type: chartEficienciaBar.type,
                data: {
                    labels: chartEficienciaBar.labels,
                    datasets: [{
                        label: chartEficienciaBar.label,
                        data: chartEficienciaBar.data,
                        backgroundColor: chartEficienciaBar.backgroundColor,
                        borderWidth: chartEficienciaBar.borderWidth
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
        url: "/efectividad"
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

<script>
    var chProductividad = document.getElementById('chartProductividad');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/productividad"
    }).done(function (chartProductividad) {
        if(chartProductividad != null){
            var chartProductividad = new Chart(chProductividad, {
        
                type: chartProductividad.type,
                data: {
                    labels: chartProductividad.labels,
                    datasets: [{
                        label: chartProductividad.label,
                        data: chartProductividad.data,
                        backgroundColor: chartProductividad.backgroundColor,
                        borderWidth: chartProductividad.borderWidth
                    }]
                }
            });
        }
    });
</script>

<script>
    var chEfectividadBar = document.getElementById('chartEfectividadBar');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/barrasefectividad"
    }).done(function (chartEfectividadBar) {
        if(chartEfectividadBar != null){
            var chartEfectividadBar = new Chart(chEfectividadBar, {
        
                type: chartEfectividadBar.type,
                data: {
                    labels: chartEfectividadBar.labels,
                    datasets: [{
                        label: chartEfectividadBar.label,
                        data: chartEfectividadBar.data,
                        backgroundColor: chartEfectividadBar.backgroundColor,
                        borderWidth: chartEfectividadBar.borderWidth
                    }]
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $("#opcionFiltro").change(function(){
        var opcion = $(this).val();
        var pie = document.getElementById('pie');
        var barras = document.getElementById('barras');
        if(opcion == 1){
            pie.style.display = 'block';
            barras.style.display = 'none';
        }else if(opcion == 2){
            pie.style.display = 'none';
            barras.style.display = 'block';
        }
        /*{{ $chartEficacia->id }}_refresh(original_api_url + "?opcion="+opcion);*/
    });
</script>
@endsection