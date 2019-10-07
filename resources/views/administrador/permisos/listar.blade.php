@extends('theme.bsb.'.strtolower(session()->get('Rol_Id')).'.layout')
@section('titulo')
Sistema de Permisos
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
                        Asignar permisos a Usuarios
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_permiso_administrador')}}">
                                <i class="material-icons" style="color:white;">add</i> Nuevo Permiso
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($usuarios)<=0) <div class="alert alert-warning">
                        No hay datos que mostrar
                </div>
                @else
                <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                    <thead>
                        <tr>
                            <th>Nombre y Apellido</th>
                            <th>Rol Asignado</th>
                            <th class="width70"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{$usuario->USR_Nombres_Usuario.' '.$usuario->USR_Apellidos_Usuario}}</td>
                            <td>{{$usuario->RLS_Nombre_Rol}}</td>
                            <td>
                                <a href="{{route('asignar_menu_usuario_administrador', ['id'=>$usuario->id])}}"
                                    class="btn-accion-tabla tooltipsC" title="Asignar Permisos">
                                    <i class="material-icons text-info" style="font-size: 17px;">merge_type</i>
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