@extends('theme.bsb.director.layout')
@section('titulo')
Crud Actividades
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
                        ACTIVIDADES
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_actividad_director', ['idP'=>$proyecto->id])}}">
                                <i class="material-icons" style="color:white;">add</i> Nueva Actividad
                            </a>
                        </li>
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('proyectos_director')}}">
                                <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Proyectos
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($actividades)<=0)
                        <div class="alert alert-warning">
                            <strong>Advertencia!</strong> El proyecto 
                            <strong>{{$proyecto->PRY_Nombre_Proyecto}}</strong> no tiene actividades asignadas
                            <a href="{{route('crear_actividad_director', ['idP'=>$proyecto->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Actividad</th>
                                    <th>Descripción</th>
                                    <th>Persona</th>
                                    <th>Estado</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actividades as $actividad)
                                    <tr>
                                        <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                        <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                        <td>{{$actividad->USR_Nombre.' '.$actividad->USR_Apellido}}</td>
                                        <td>{{$actividad->ACT_Estado_Actividad}}</td>
                                            
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