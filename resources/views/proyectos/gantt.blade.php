@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Gantt Proyectos
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
                        CRONOGRAMA DE ACTIVIDADES PROYECTO {{strtoupper($proyecto->PRY_Nombre_Proyecto)}}
                    </h2>
                </div>
                <div class="body table-responsive">
                    {{$proyecto->PRY_Nombre_Proyecto}}<br /><br /><br />
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td style="padding-right: 0px;">#</td>
                                <td>Tareas</td>
                                @foreach ($fechas as $fecha)
                                    <td style="padding-right: 0px;">
                                        {{\Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('m')}}<br />
                                        {{\Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('d')}}<br />
                                    </td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actividades as $item => $actividad)
                                <tr>
                                    <td>{{++$item}}</td>
                                    <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                    @foreach ($fechas as $fecha)
                                        @if (\Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('Y-m-d') >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $actividad->ACT_Fecha_Inicio_Actividad)->format('Y-m-d') &&
                                            \Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('Y-m-d') <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $actividad->ACT_Fecha_Fin_Actividad)->format('Y-m-d'))
                                            @if ($actividad->ACT_Estado_Id == 1)
                                                <td style="background-color: aqua"></td>
                                            @elseif ($actividad->ACT_Estado_Id == 2 || $actividad->ACT_Estado_Id == 6)
                                                <td style="background-color: red"></td>
                                            @elseif ($actividad->ACT_Estado_Id != 1 || $actividad->ACT_Estado_Id != 2 || $actividad->ACT_Estado_Id != 6)
                                                <td style="background-color: green"></td>
                                            @else
                                                <td></td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection