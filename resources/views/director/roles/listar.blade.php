@extends('theme.bsb.director.layout')
@section('titulo')
Crud Roles
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
                        ROLES
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_rol_director')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo rol</a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tabla-data">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="width70"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $rol)
                            <tr>
                                <td>{{$rol->RLS_Nombre}}</td>
                                <td>{{$rol->RLS_Descripcion}}</td>
                                <td>
                                    <a href="{{route('editar_rol_director', ['id'=>$rol->id])}}" class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                        <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                    </a>
                                    <form action="{{route('eliminar_rol_director', ['id'=>$rol->id])}}" class="d-inline" method="POST">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection