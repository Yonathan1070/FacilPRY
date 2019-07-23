@extends('theme.bsb.director.layout')
@section('titulo')
Crud Requerimientos
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    <h2>
                        REQUERIMIENTOS
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_requerimiento_director', ['idP'=>$proyecto->id])}}">
                                <i class="material-icons" style="color:white;">add</i> Nuevo Requerimiento
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
                    @if (count($requerimientos)<=0)
                        <div class="alert alert-warning">
                            <strong>Advertencia!</strong> El proyecto 
                            <strong>{{$proyecto->PRY_Nombre_Proyecto}}</strong> no tiene requerimientos asignados
                            <a href="{{route('crear_requerimiento_director', ['idP'=>$proyecto->id])}}" class="alert-link">Clic aquí para agregar!</a>.
                    @else
                        <table class="table table-striped table-bordered table-hover" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requerimientos as $requerimiento)
                                <tr>
                                    <td>{{$requerimiento->REQ_Nombre_Requerimiento}}</td>
                                    <td>{{$requerimiento->REQ_Descripcion_Requerimiento}}</td>
                                    <td>
                                        <a href="{{route('editar_requerimiento_director', ['idP'=>$proyecto->id, 'idR'=>$requerimiento->id])}}" class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                        </a>
                                        <form action="{{route('eliminar_requerimiento_director', ['idP'=>$proyecto->id, 'idR'=>$requerimiento->id])}}" class="d-inline" method="POST">
                                            @csrf @method("delete")
                                            <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm" title="Eliminar este registro">
                                                <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                            </button>
                                        </form>
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