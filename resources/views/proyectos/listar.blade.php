@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Listar Proyectos
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
                    <div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#no-finalizado" aria-controls="settings" role="tab" data-toggle="tab">No Finalizados</a></li>
                            <li role="presentation"><a href="#finalizado" aria-controls="settings" role="tab" data-toggle="tab">Finalizados</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="no-finalizado">
                                @if (count($proyectosNoFinalizados)<=0)
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
                                                <th>Tareas (Finalizadas/Totales)</th>
                                                <th class="grid-width-100"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($proyectosNoFinalizados as $proyecto)
                                                <tr>
                                                    <td>
                                                        <a onclick="avance({{$proyecto->Proyecto_Id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                                            {{$proyecto->PRY_Nombre_Proyecto}}
                                                        </a>
                                                        <div id="progressBar{{$proyecto->Proyecto_Id}}" style="display: none;"></div>
                                                        </td>
                                                    <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                                    <td>{{$proyecto->USR_Nombres_Usuario.' '.$proyecto->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$proyecto->Actividades_Finalizadas}} / {{$proyecto->Actividades_Totales}}</td>
                                                    <td class="grid-width-100">
                                                        <form class="form-eliminar" action="{{route('eliminar_proyectos', ['idP'=>$proyecto->Proyecto_Id])}}"
                                                            class="d-inline" method="POST">
                                                            @if ($permisos['eliminar'] == true && $proyecto->Actividades_Totales == 0)
                                                                @csrf @method("delete")
                                                                <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm"
                                                                    title="Eliminar Proyecto">
                                                                    <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                                </button>
                                                            @endif
                                                            @if ($proyecto->Actividades_Totales != 0)
                                                                @if ($permisos['listarA']==true)
                                                                    <a href="{{route('actividades_todas', ['idP'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Listar todas las tareas">
                                                                        <i class="material-icons text-info" style="font-size: 17px;">line_weight</i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if ($permisos['listarR']==true)
                                                                <a href="{{route('requerimientos', ['idP'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Listar Actividades">
                                                                    <i class="material-icons text-info" style="font-size: 17px;">description</i>
                                                                </a>
                                                            @endif
                                                            @if ($proyecto->Actividades_Totales != 0)
                                                                <a href="{{route('generar_pdf_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Tareas">
                                                                    <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
                                                                </a>
                                                                <a href="{{route('gantt', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Ver Cronograma de Tareas">
                                                                    <i class="material-icons text-info" style="font-size: 20px;">view_quilt</i>
                                                                </a>
                                                            @endif
                                                            @if ($proyecto->Actividades_Totales != 0 && ($proyecto->Actividades_Finalizadas == $proyecto->Actividades_Totales))
                                                                <a href="{{route('finalizar_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Fianlizar Proyecto">
                                                                    <i class="material-icons text-success" style="font-size: 20px;">navigate_next</i>
                                                                </a>
                                                            @endif
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="finalizado">
                                @if (count($proyectosFinalizados)<=0)
                                    <div class="alert alert-info">
                                        No hay proyectos finalizados.
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Cliente</th>
                                                <th>Tareas (Finalizadas/Totales)</th>
                                                <th class="width70"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($proyectosFinalizados as $proyecto)
                                                <tr>
                                                    <td>
                                                        <a onclick="avance({{$proyecto->Proyecto_Id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                                            {{$proyecto->PRY_Nombre_Proyecto}}
                                                        </a>
                                                        <div id="progressBar{{$proyecto->Proyecto_Id}}" style="display: none;"></div>
                                                        </td>
                                                    <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                                    <td>{{$proyecto->USR_Nombres_Usuario.' '.$proyecto->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$proyecto->Actividades_Finalizadas}} / {{$proyecto->Actividades_Totales}}</td>
                                                    <td>
                                                        <a href="{{route('generar_pdf_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Tareas">
                                                            <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
                                                        </a>
                                                        <a href="{{route('activar_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Activar proyecto">
                                                            <i class="material-icons text-info" style="font-size: 17px;">navigate_before</i>
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