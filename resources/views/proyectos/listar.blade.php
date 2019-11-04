@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Crud Proyectos
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
            @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>
                        PROYECTOS
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear']==true)
                                <a class="btn btn-success waves-effect" href="{{route('crear_proyecto')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo Proyecto</a>
                            @endif
                            
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($proyectos)<=0)
                    <div class="alert alert-info">
                        No hay datos que mostrar
                        <a href="{{route('crear_proyecto')}}" class="alert-link">Clic aquí para agregar!</a>.
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
                                                <a href="{{route('requerimientos', ['idP'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Listar Requerimientos">
                                                    <i class="material-icons text-info" style="font-size: 17px;">description</i>
                                                </a>
                                            @endif
                                            @if ($permisos['listarA']==true)
                                                <a href="{{route('actividades', ['idP'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Listar Actividades">
                                                    <i class="material-icons text-info" style="font-size: 17px;">assignment</i>
                                                </a>
                                            @endif
                                            <a href="{{route('generar_pdf_proyecto', ['id'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Actividades">
                                                <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="body table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th colspan="3">Titulo</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td rowspan="2">Actividades</td>
                                        <td colspan="5">
                                            Noviembre
                                        </td>
                                        <td>
                                            <tr>
                                                <td>01</td>
                                                <td>02</td>
                                                <td>03</td>
                                                <td>04</td>
                                                <td>05</td>
                                            </tr>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>

                        <table border="1" style="width:100%;">
                                <tr>
                                  <td>Logo</td>
                                  <td>Titulo</td>
                                </tr>
                                <tr>
                                  <td>Actividades</td>
                                  <td>
                                    <table border="1" style="width: 100%;">
                                      <tr>
                                        Mes
                                      </tr>
                                      <tr>
                                        <td>
                                            <table border="1" style="width: 100%;">
                                                <tr>
                                                    <td>Noviembre</td>
                                                    <td>Diciembre</td>
                                                </tr>
                                                <tr>
                                                    <td>01</td>
                                                    <td>Price 3</td>
                                                </tr>
                                            </table>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>01</td>
                                        <td>Price 3</td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Act 1</td>
                                  <td>Item 2</td>
                                </tr>
                                <tr>
                                  <td>Act 2</td>
                                  <td>Item 3</td>
                                </tr>
                              </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection