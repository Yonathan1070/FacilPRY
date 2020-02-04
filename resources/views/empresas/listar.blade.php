@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Crud Empresas
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
                        <h2>EMPRESAS</h2>
                        <ul class="header-dropdown" style="top:10px;">
                            <li class="dropdown">
                                @if ($permisos['crear']==true)
                                    <a class="btn btn-success waves-effect" href="{{route('crear_empresa')}}">
                                        <i class="material-icons" style="color:white;">add</i> Nueva Empresa
                                    </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="body table-responsive">
                        @if (count($empresas)<=0)
                            <div class="alert alert-info">
                                No hay Datos que mostrar
                                <a href="{{route('crear_empresa')}}" class="alert-link">Clic aquí para agregar!</a>.
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>NIT</th>
                                        <th>Empresa</th>
                                        <th>Dirección</th>
                                        <th>Correo Electrónico</th>
                                        @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                            <th class="width100"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empresas as $empresa)
                                        <tr>
                                            <td>{{$empresa->EMP_NIT_Empresa}}</td>
                                            <td>{{$empresa->EMP_Nombre_Empresa}}</td>
                                            <td>{{$empresa->EMP_Direccion_Empresa}}</td>
                                            <td>{{$empresa->EMP_Correo_Empresa}}</td>
                                            <td class="width100">
                                                @if ($permisos['editar']==true || $permisos['eliminar']==true || $permisos['lUsuarios']==true || $permisos['lProyectos']==true)
                                                    <form class="form-eliminar" action="{{route('inactivar_empresa', ['id'=>$empresa->id])}}" class="d-inline" method="POST">
                                                        @if ($permisos['editar']==true)
                                                            <a href="{{route('editar_empresa', ['id'=>$empresa->id])}}" class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                                <i class="material-icons text-info" style="font-size: 20px;">edit</i>
                                                            </a>
                                                        @endif    
                                                        @if ($permisos['eliminar']==true)
                                                            @csrf @method("put")
                                                            <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm" title="Inactivar la empresa {{$empresa->EMP_Nombre_Empresa}}">
                                                                <i class="material-icons text-danger" style="font-size: 20px;">arrow_downward</i>
                                                            </button>
                                                        @endif
                                                        @if ($permisos['lUsuarios']==true)
                                                            <a href="{{route('clientes', ['id'=>$empresa->id])}}" class="btn-accion-tabla tooltipsC" title="Lista de Usuarios">
                                                                <i class="material-icons text-info" style="font-size: 20px;">list</i>
                                                            </a>
                                                        @endif
                                                        @if ($permisos['lProyectos']==true)
                                                            <a href="{{route('proyectos', ['id'=>$empresa->id])}}" class="btn-accion-tabla tooltipsC" title="Lista de Proyectos">
                                                                <i class="material-icons text-info" style="font-size: 20px;">notes</i>
                                                            </a>
                                                        @endif
                                                    </form>
                                                @endif
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