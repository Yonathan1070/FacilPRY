@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Inicio
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
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficacia General</h2>
                </div>
                <div class="body">
                    {!! $chartEficacia->container() !!}
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
                    {!! $chartEficiencia->container() !!}
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
                    {!! $chartEfectividad->container() !!}
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
    </div>
    <div id="barras" style="display:none" class="row clearfix">
        <!-- Pie Chart -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficacia Por Trabajadores</h2>
                </div>
                <div class="body">
                    {!! $chartBarEficacia->container() !!}
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
        <!-- Pie Chart -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficiencia Por Trabajadores</h2>
                </div>
                <div class="body">
                    {!! $chartBarEficiencia->container() !!}
                </div>
            </div>
        </div>
        <!-- #END# Pie Chart -->
        <!-- Pie Chart -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Efectividad Por Trabajadores</h2>
                </div>
                <div class="body">
                    {!! $chartBarEfectividad->container() !!}
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
<script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
{!! $chartEficacia->script() !!}
{!! $chartEficiencia->script() !!}
{!! $chartEfectividad->script() !!}
{!! $chartBarEficacia->script() !!}
{!! $chartBarEficiencia->script() !!}
{!! $chartBarEfectividad->script() !!}
<script type="text/javascript">
    var original_api_url = {{ $chartEficacia->id }}_api_url;
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