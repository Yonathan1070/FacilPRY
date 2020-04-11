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
                        CRONOGRAMA DE TAREAS PROYECTO {{strtoupper($proyecto->PRY_Nombre_Proyecto)}}
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('gantt_descargar', ['id'=>$proyecto->id])}}"><i
                                class="material-icons" style="color:white;">file_download</i> Descargar Cronograma</a>
                            @if ($permisos['listarE']==true)
                                <a class="btn btn-danger waves-effect" href="{{route('empresas')}}">
                                    <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Empresas
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    {{$proyecto->PRY_Nombre_Proyecto}}<br /><br /><br />
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td style="width: 1px;">#</td>
                                <td style="width: 30px;">Tareas</td>
                                @foreach ($fechas as $fecha)
                                    <td style="width: 1px;">
                                        {{\Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('m-d')}}
                                    </td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($actividades as $item => $actividad)
                                <tr>
                                    <td style="width: 1px;">{{++$item}}</td>
                                    <td style="width: 30px;">{{$actividad->ACT_Nombre_Actividad}}</td>
                                    @foreach ($fechas as $fecha)
                                        @if (\Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('Y-m-d') >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $actividad->ACT_Fecha_Inicio_Actividad)->format('Y-m-d') &&
                                            \Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('Y-m-d') <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $actividad->ACT_Fecha_Fin_Actividad)->format('Y-m-d'))
                                            @if ($actividad->ACT_Estado_Id == 1)
                                                <td style="background-color: aqua; width: 1px;"></td>
                                            @elseif ($actividad->ACT_Estado_Id == 2 || $actividad->ACT_Estado_Id == 6)
                                                <td style="background-color: red; width: 1px;"></td>
                                            @elseif ($actividad->ACT_Estado_Id != 1 || $actividad->ACT_Estado_Id != 2 || $actividad->ACT_Estado_Id != 6)
                                                <td style="background-color: green; width: 1px;"></td>
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