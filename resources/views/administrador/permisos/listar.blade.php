@extends('theme.bsb.administrador.layout')
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
                        Permisos
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if (count($usuarios)<=0) 
                        <div class="alert alert-warning">
                            No hay datos que mostrar
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre y Apellido</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>{{$usuario->USR_Nombres_Usuario.' '.$usuario->USR_Apellidos_Usuario}}</td>
                                    <td>
                                        <a href="{{route('asignar_rol_usuario_administrador', ['id'=>$usuario->id])}}"
                                            class="btn-accion-tabla tooltipsC" title="Asignar Roles">
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