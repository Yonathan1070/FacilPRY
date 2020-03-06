@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Listar Actividades
@endsection
@section('styles')
    <style>
        .card .bg-cyan{
            color: #000 !important; }
    </style>
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/pages/scripts/Director/porcentaje.js")}}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    <h2>
                        ACTIVIDADES - PROYECTO ({{strtoupper($proyecto->PRY_Nombre_Proyecto)}})
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear']==true)
                                <a class="btn btn-success waves-effect" href="{{route('crear_requerimiento', ['idP'=>$proyecto->id])}}">
                                    <i class="material-icons" style="color:white;">add</i> Nueva Actividad
                                </a>
                            @endif
                            
                        </li>
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('proyectos', ['id'=>$proyecto->PRY_Empresa_Id])}}">
                                <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Proyectos
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($requerimientos)<=0)
                        <div class="alert alert-info">
                            No hay datos que mostrar
                            @if ($permisos['crear']==true)
                                <a href="{{route('crear_requerimiento', ['idP'=>$proyecto->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                            @endif
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                        <th class="width70"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requerimientos as $requerimiento)
                                    <tr>
                                    <td>
                                        <a onclick="avanceR({{$requerimiento->id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                            {{$requerimiento->REQ_Nombre_Requerimiento}}
                                        </a>
                                        <div id="progressBar{{$requerimiento->id}}" style="display: none;"></div>
                                    </td>
                                    <td>{{$requerimiento->REQ_Descripcion_Requerimiento}}</td>
                                    @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                        <td>
                                            <form class="form-eliminar" action="{{route('eliminar_requerimiento', ['idP'=>$proyecto->id, 'idR'=>$requerimiento->id])}}" class="d-inline" method="POST">
                                                @if ($permisos['editar']==true)
                                                    <a href="{{route('editar_requerimiento', ['idP'=>$proyecto->id, 'idR'=>$requerimiento->id])}}" class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                        <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                    </a>
                                                @endif
                                                @if ($permisos['eliminar']==true)
                                                    @csrf @method("delete")
                                                    <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm" title="Eliminar este registro">
                                                        <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                    </button>
                                                @endif
                                                @if ($permisos['listarA']==true)
                                                    <a href="{{route('actividades', ['idR'=>$requerimiento->id])}}" class="btn-accion-tabla tooltipsC" title="Listar Tareas">
                                                        <i class="material-icons text-info" style="font-size: 17px;">assignment</i>
                                                    </a>
                                                @endif
                                            </form>
                                        </td>
                                    @endif
                                    
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