@extends('includes.pdf.layout')
@section('titulo')
PDF Carga Trabajo
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
            </div>
            <div class="body table-responsive">
                <ul>
                    <li>
                        <div class="title">
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
                                                    <a href="{{route('aprobar_horas_actividad', ['idA'=>$actividad->Id_Actividad])}}"
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
                            Porcentaje de actividades
                        </div>
                        <div class="content">
                            <h3>
                                <span class="label label-info">En Proceso</span>
                                <span class="label label-danger">Atrasada</span>
                                <span class="label label-success">Finalizada</span>
                            </h3>
                            <div class="progressbar">
                                <div class="progressbar progressbar-cyan" style="width: {{$porcentajeProceso}}%">
                                    {{$porcentajeProceso}}%
                                </div>
                            </div><br>
                            <div class="progressbar">
                                <div class="progressbar progressbar-red" style="width: {{$porcentajeAtrasado}}%">
                                    {{$porcentajeAtrasado}}%
                                </div>
                            </div><br>
                            <div class="progressbar">
                                <div class="progressbar progressbar-green" style="width: {{$porcentajeFinalizado}}%">
                                    {{$porcentajeFinalizado}}%
                                </div>
                            </div><br>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection