@extends('theme.bsb.cliente.layout')
@section('titulo')
Inicio
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
                            PENDIENTE POR APROBAR
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        @if (count($actividadesPendientes)<=0)
                            <div class="alert alert-warning">
                                No cuenta con Actividades pendientes de aprobación.
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Actividad</th>
                                        <th>Requerimiento</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($actividadesPendientes as $actividad)
                                        <tr>
                                            <td>{{$actividad->PRY_Nombre_Proyecto}}</td>
                                            <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                            <td>{{$actividad->REQ_Nombre_Requerimiento}}</td>
                                            <td>
                                                <a href="{{route('aprobar_actividad_cliente', ['id'=>$actividad->Id_Act_Fin])}}" class="btn-accion-tabla tooltipsC" title="Ver información detallada">
                                                    <i class="material-icons text-info" style="font-size: 17px;">forward</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection