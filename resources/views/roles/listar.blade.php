@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
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
                            @if ($permisos['crear'] == true)
                                <a class="btn btn-success waves-effect" href="{{route('crear_rol')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo Rol</a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                        @if (count($roles)<=0)
                            <div class="alert alert-warning">
                                El sistema no cuenta con Roles agregados
                                <a href="{{route('crear_rol')}}" class="alert-link">Clic aquí para
                                    agregar!</a>.
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                            <th class="width70"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $rol)
                                        <tr>
                                            <td>
                                                {{$rol->RLS_Nombre_Rol}}
                                                @if ($rol->RLS_Rol_Id != 6)
                                                    <label style="color: red">(*)</label>
                                                @endif
                                            </td>
                                            <td>{{$rol->RLS_Descripcion_Rol}}</td>
                                            @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                                <td>
                                                    <form class="form-eliminar" action="{{route('eliminar_rol', ['id'=>$rol->id])}}"
                                                        class="d-inline" method="POST">
                                                        @if ($permisos['editar'] == true)
                                                            <a href="{{route('editar_rol', ['id'=>$rol->id])}}"
                                                                class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                                <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                            </a>
                                                        @endif
                                                        @if ($permisos['eliminar'] == true)
                                                            @csrf @method("delete")
                                                            <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm"
                                                                title="Eliminar este registro">
                                                                <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                            </button>
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
@section('scripts')
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
@endsection