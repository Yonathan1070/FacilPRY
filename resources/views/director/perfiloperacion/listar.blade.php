@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Crud Perfil de Operación
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
                        <h2>PERFIL DE OPERACIÓN</h2>
                        <ul class="header-dropdown" style="top:10px;">
                            <li class="dropdown">
                                <a class="btn btn-success waves-effect" href="{{route('crear_perfil_director')}}">
                                    <i class="material-icons" style="color:white;">add</i> Nuevo Perfil de Operación
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body table-responsive">
                        @if (count($perfilesOperacion)<=0)
                            <div class="alert alert-info">
                                El sistema no cuenta con Perfil de Operación registrado
                                <a href="{{route('crear_perfil_director')}}" class="alert-link">Clic aquí para agregar!</a>.
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Nombre y Apellido</th>
                                        <th>Telefono</th>
                                        <th>Correo Electrónico</th>
                                        <th>Nombre de Usuario</th>
                                        <th>Tipo de Usuario</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perfilesOperacion as $perfilOperacion)
                                        <tr>
                                            <td>{{$perfilOperacion->USR_Documento_Usuario}}</td>
                                            <td>{{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}}</td>
                                            <td>{{$perfilOperacion->USR_Telefono_Usuario}}</td>
                                            <td>{{$perfilOperacion->USR_Correo_Usuario}}</td>
                                            <td>{{$perfilOperacion->USR_Nombre_Usuario}}</td>
                                            <td>{{$perfilOperacion->RLS_Nombre_Rol}}</td>
                                            <td>
                                                <form class="form-eliminar" action="{{route('eliminar_perfil_director', ['id'=>$perfilOperacion->id])}}" class="d-inline" method="POST">
                                                        <a href="{{route('editar_perfil_director', ['id'=>$perfilOperacion->id])}}" class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                        </a>
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