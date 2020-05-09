@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Listar Logs de Cambios
@endsection
@section('styles')
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
                        HISTORIAL DE LOGS DE CAMBIOS
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('logs_administrador')}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                        <thead>
                            <tr>
                                <th>Tabla</th>
                                <th>Acción</th>
                                <th>Descripción de la acción</th>
                                <th>Fecha</th>
                                <th>Actor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                            <tr>
                                <td>{{$log->LOG_Tabla}}</td>
                                <td>{{$log->LOG_Accion}}</td>
                                <td>{{$log->LOG_Descripcion}}</td>
                                <td>{{$log->LOG_Fecha}}</td>
                                <td>{{$log->USR_Nombres_Usuario.' '.$log->USR_Apellidos_Usuario}}</td>
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
@section('scripts')
@endsection