@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Crud Proyectos
@endsection
@section('styles')
    <style>
        .card .bg-cyan{
            color: #000 !important; }
    </style>
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/pages/scripts/Director/porcentaje.js")}}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-fluid">
    <!--<div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DIAGRAMA DE GANTT
                    </h2>
                </div>
                <div class="body">
                    @include('proyectos.gantt')
                </div>
            </div>
        </div>
    </div>-->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>
                        PROYECTOS DE {{strtoupper($empresa->EMP_Nombre_Empresa)}}
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear']==true)
                                <a class="btn btn-success waves-effect" href="{{route('crear_proyecto', ['id'=>$empresa->id])}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo Proyecto</a>
                            @endif
                            @if ($permisos['listarE']==true)
                                <a class="btn btn-danger waves-effect" href="{{route('empresas')}}">
                                    <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Empresas
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($proyectos)<=0)
                    <div class="alert alert-info">
                        No hay datos que mostrar
                        <a href="{{route('crear_proyecto', ['id'=>$empresa->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                    </div>
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Cliente</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proyectos as $proyecto)
                                    <tr>
                                        <td>
                                            <a onclick="avance({{$proyecto->id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                                {{$proyecto->PRY_Nombre_Proyecto}}
                                            </a>
                                            <div id="progressBar{{$proyecto->id}}" style="display: none;"></div>
                                            </td>
                                        <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                        <td>{{$proyecto->USR_Nombres_Usuario.' '.$proyecto->USR_Apellidos_Usuario}}</td>
                                        <td>
                                            @if ($permisos['listarR']==true)
                                                <a href="{{route('requerimientos', ['idP'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Listar Actividades">
                                                    <i class="material-icons text-info" style="font-size: 17px;">description</i>
                                                </a>
                                            @endif
                                            @if ($permisos['listarA']==true)
                                                <a href="{{route('actividades', ['idP'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Listar Tareas">
                                                    <i class="material-icons text-info" style="font-size: 17px;">assignment</i>
                                                </a>
                                            @endif
                                            <a href="{{route('generar_pdf_proyecto', ['id'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Tareas">
                                                <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
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
@section('scripts')
    <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
    
    <script type="text/javascript">
        gantt.init("gantt_here");
    </script>
@endsection