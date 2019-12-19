@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Inicio
@endsection
@section('contenido')
@include('includes.form-exito')
@include('includes.form-error')
<div class="container-fluid">
    <div class="row clearfix">
        <!-- Pie Chart -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Indicador de Eficacia General</h2>
                </div>
                <div class="body">
                        {!! $metricas['eficacia']->container() !!}

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
                        {!! $metricas['eficiencia']->container() !!}

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
                            {!! $metricas['efectividad']->container() !!}
    
                    </div>
                </div>
            </div>
            <!-- #END# Pie Chart -->
    </div>

    <div class="row clearfix">
        
    </div>
</div>
@endsection
@section('scripts')
<!-- Chart Plugins Js -->
<script src="{{asset("assets/bsb/plugins/chartjs/Chart.bundle.js")}}"></script>

<script src="{{asset("assets/bsb/js/pages/charts/chartjs.js")}}"></script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
{!! $metricas['eficacia']->script() !!}
{!! $metricas['eficiencia']->script() !!}
{!! $metricas['efectividad']->script() !!}
<script>
        /*$.ajax({
            url: '/director/metrica',
            type: 'GET',
            success: function(eficacia){
                grafica(eficacia);
            }
        });

        function grafica(eficacia){
            var ctx = document.getElementById('myChart').getContext('2d');
            var fillPattern = ctx.createPattern(img, 'repeat');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [225, 50],
                        backgroundColor: fillPattern,
                    }],
                    labels: [
                        "Pink",
                        "Amber"
                    ]
                },
                options: {
                    responsive: true,
                    legend: false
                }
            });
        }*/
</script>
@endsection