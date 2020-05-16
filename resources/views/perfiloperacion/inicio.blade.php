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
    <div id="pie" class="row clearfix">
        <!-- Pie Chart -->
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficacia</h2>
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
                    <h2>Indicador de Eficiencia</h2>
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
                    <h2>Indicador de Efectividad</h2>
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

<script>
    var chEficaciaBar = document.getElementById('chartEficaciaBar');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/perfil-operacion/eficacia"
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
    var chEficienciaBar = document.getElementById('chartEficienciaBar');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/perfil-operacion/eficiencia"
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
    var chEfectividadBar = document.getElementById('chartEfectividadBar');
    var data;
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/perfil-operacion/efectividad"
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
@endsection