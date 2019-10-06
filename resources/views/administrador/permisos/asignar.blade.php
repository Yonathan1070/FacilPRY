@extends('theme.bsb.administrador.layout')
@section('titulo')
Sistema de Permisos
@endsection
@section('styles')
    
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>
                        Lista de Menús
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if (count($menus)<=0) 
                        <div class="alert alert-warning">
                            No hay datos que mostrar
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                <tr>
                                    <td>{{$menu->MN_Nombre_Menu}}</td>
                                    <td>
                                        <a href="{{route('agregar_rol_administrador', ['idU' => $id, 'idR' => $menu->id])}}" class="btn-accion-tabla tooltipsC" title="Asignar">
                                            <i class="material-icons text-success" style="font-size: 17px;">add_circle</i>
                                        </a>
                                        <a href="{{route('quitar_rol_administrador', ['idU' => $id, 'idR' => $menu->id])}}" class="btn-accion-tabla tooltipsC" title="Quitar">
                                            <i class="material-icons text-danger" style="font-size: 17px;">remove_circle</i>
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