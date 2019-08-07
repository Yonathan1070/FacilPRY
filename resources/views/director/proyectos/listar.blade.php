@extends('theme.bsb.director.layout')
@section('titulo')
Crud Proyectos
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
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
                            <a class="btn btn-success waves-effect" href="{{route('crear_proyecto_director')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo Proyecto</a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($proyectos)<=0)
                    <div class="alert alert-warning">
                        <strong>Advertencia!</strong> El sistema no cuenta con Proyectos registrados
                        <a href="{{route('crear_proyecto_director')}}" class="alert-link">Clic aquí para agregar!</a>.
                    @else
                        <table class="table table-striped table-bordered table-hover" id="tabla-data">
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
                                        <td>{{$proyecto->PRY_Nombre_Proyecto}}</td>
                                        <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                        <td>{{$proyecto->USR_Nombre.' '.$proyecto->USR_Apellido}}</td>
                                        <td>
                                            <a href="{{route('requerimientos_director', ['idP'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Agregar Requerimientos">
                                                <i class="material-icons text-info" style="font-size: 17px;">description</i>
                                            </a>
                                            <a href="{{route('actividades_director', ['idP'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Agregar Actividades">
                                                <i class="material-icons text-info" style="font-size: 17px;">assignment</i>
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