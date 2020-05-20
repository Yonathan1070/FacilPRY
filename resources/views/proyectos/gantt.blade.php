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
                                <a class="btn btn-danger waves-effect" href="{{route('proyectos', ['id'=>$proyecto->PRY_Empresa_Id])}}">
                                    <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Proyectos
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive" style="height: 400px">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td style="width: 1px;">#</td>
                                <td style="width: 30px;">Tareas</td>
                                <td style="width: 50px;">Encargado</td>
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
                                    <td style="width: 50px;">{{$actividad->USR_Nombres_Usuario.' '.$actividad->USR_Apellidos_Usuario.' ('.$actividad->RLS_Nombre_Rol.')'}}</td>
                                    @foreach ($fechas as $fecha)
                                        @if (\Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('Y-m-d') >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $actividad->ACT_Fecha_Inicio_Actividad)->format('Y-m-d') &&
                                            \Carbon\Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('Y-m-d') <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $actividad->ACT_Fecha_Fin_Actividad)->format('Y-m-d'))
                                            @if ($actividad->ACT_Estado_Id == 1 && $actividad->HorasE != null && $actividad->HorasR != null)
                                                <td style="background-color: #00BCD4; width: 1px;"></td>
                                            @elseif ($actividad->ACT_Estado_Id == 2 || $actividad->ACT_Estado_Id == 6)
                                                <td style="background-color: #F44336; width: 1px;"></td>
                                            @elseif ($actividad->ACT_Estado_Id == 1 && $actividad->HorasE == 0 && $actividad->HorasR == null)
                                                <td style="background-color: #FFEB3B; width: 1px;"></td>
                                            @elseif ($actividad->ACT_Estado_Id == 1 && $actividad->HorasE != null && $actividad->HorasR == null)
                                                <td style="background-color: #FF9800; width: 1px;"></td>
                                            @elseif ($actividad->ACT_Estado_Id != 1 || $actividad->ACT_Estado_Id != 2 || $actividad->ACT_Estado_Id != 6)
                                                <td style="background-color: #4CAF50; width: 1px;"></td>
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
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
            <div class="info-box bg-yellow hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">alarm_add</i>
                </div>
                <div class="content">
                    <div class="text">Asignar Horas de Trabajo</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
            <div class="info-box bg-orange hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">alarm_on</i>
                </div>
                <div class="content">
                    <div class="text">Aprobar Horas de Trabajo</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
            <div class="info-box bg-cyan hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">done</i>
                </div>
                <div class="content">
                    <div class="text">En Proceso</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
            <div class="info-box bg-red hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">warning</i>
                </div>
                <div class="content">
                    <div class="text">Atrasada</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
            <div class="info-box bg-green hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">done_all</i>
                </div>
                <div class="content">
                    <div class="text">Finalizado</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection