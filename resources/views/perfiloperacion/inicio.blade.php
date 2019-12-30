@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Inicio
@endsection
@section('contenido')
@include('includes.form-exito')
@include('includes.form-error')
<div class="container-fluid">
    <div id="pie" class="row clearfix">
        <!-- Pie Chart -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficacia General</h2>
                </div>
                <div class="body">
                    <canvas id="chartEficaciaBar" width="400" height="400"></canvas>
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
                    <canvas id="chartEficienciaBar" width="400" height="400"></canvas>
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
        url: "{{route('eficacia_general_perfil_operacion')}}"
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
        url: "{{route('eficiencia_general_perfil_operacion')}}"
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
        url: "{{route('efectividad_general_perfil_operacion')}}"
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