@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Listar Clientes
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
                        <h2>CLIENTES</h2>
                        <ul class="header-dropdown" style="top:10px;">
                            <li class="dropdown">
                                @if ($permisos['crear']==true)
                                    <a class="btn btn-success waves-effect" href="{{route('crear_cliente', ['id'=>$empresa->id])}}">
                                        <i class="material-icons" style="color:white;">add</i> Nuevo Cliente
                                    </a>
                                @endif
                                <a class="btn btn-danger waves-effect" href="{{route('empresas')}}">
                                    <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Empresas
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body table-responsive">
                        @if (count($clientes)<=0)
                            <div class="alert alert-info">
                                No hay Datos que mostrar
                                <a href="{{route('crear_cliente', ['id'=>$empresa->id])}}" class="alert-link">Clic aquí para agregar!</a>.
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
                                        @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                            <th class="width70"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clientes as $cliente)
                                        <tr>
                                            <td>{{$cliente->USR_Documento_Usuario}}</td>
                                            <td>{{$cliente->USR_Nombres_Usuario.' '.$cliente->USR_Apellidos_Usuario}}</td>
                                            <td>{{$cliente->USR_Telefono_Usuario}}</td>
                                            <td>{{$cliente->USR_Correo_Usuario}}</td>
                                            <td>{{$cliente->USR_Nombre_Usuario}}</td>
                                            @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                                <td>
                                                    <form class="form-eliminar" action="{{route('eliminar_cliente', ['id'=>$cliente->id])}}" class="d-inline" method="POST">
                                                        @if ($permisos['editar']==true)
                                                            <a href="{{route('editar_cliente', ['idC'=>$cliente->id, 'idE'=>$empresa->id])}}" class="btn-accion-tabla tooltipsC" title="Editar este Registro">
                                                                <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                            </a>
                                                        @endif    
                                                        @if ($permisos['eliminar']==true)
                                                            @csrf @method("delete")
                                                            <!--<button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm" title="Eliminar este Registro">
                                                                <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                            </button>-->
                                                        @endif
                                                    </form>
                                                </td>
                                            @endif
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