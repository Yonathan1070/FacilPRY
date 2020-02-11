@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Sistema de Permisos
@endsection
@section('styles')
    
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        @include('includes.form-exito')
        @include('includes.form-error')
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Filtro de Asignación
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select name="opcionFiltro" id="opcionFiltro" class="form-control">
                                        <option disabled value="">Seleccione una Opción</option>
                                        <option value="1">Asignacion de Menú</option>
                                        <option value="2">Asignacion de Permisos</option>
                                        <option value="3">Asignacion de Roles</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="menu" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Lista de Menús
                    </h2>
                </div>
                <div class="body table-responsive">
                    <div class="col-lg-6">
                        @if (count($menuAsignado)<=0) 
                            <div class="alert alert-warning">
                                No se han asignado items del menú
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover js-basic-example" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menuAsignado as $menu)
                                    <tr>
                                        <td>{{$menu->MN_Nombre_Menu}}</td>
                                        <td>
                                            <button type="button" onclick='ajax("quitar", {{$id}}, {{$menu->id}})' class="btn-accion-tabla tooltipsC" title="Quitar">
                                                <i class="material-icons text-danger" style="font-size: 17px;">remove_circle</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        @if (count($menuNoAsignado)<=0) 
                            <div class="alert alert-warning">
                                Ya se asignaron todos los items del menú
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover js-basic-example" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menuNoAsignado as $menu)
                                    <tr>
                                        <td>{{$menu->MN_Nombre_Menu}}</td>
                                        <td>
                                            <button type="button" onclick='ajax("agregar", {{$id}}, {{$menu->id}})' class="btn-accion-tabla tooltipsC" title="Asignar">
                                                <i class="material-icons text-success" style="font-size: 17px;">add_circle</i>
                                            </button>
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
        <div id="permiso" style="display: none;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Lista de Permisos
                    </h2>
                </div>
                <div class="body table-responsive">
                    <div class="col-lg-6">
                        @if (count($permisoAsignado)<=0) 
                            @if ($id == 1) 
                                <div class="alert alert-info">
                                    El usuario administrador tiene acceso a todos los permisos
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No se han asignado permisos
                                </div>
                            @endif
                        @else
                            <table class="table table-striped table-bordered table-hover js-basic-example" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Permiso</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permisoAsignado as $permiso)
                                    <tr>
                                        <td>{{$permiso->PRM_Nombre_Permiso}}</td>
                                        <td>
                                            <button type="button" onclick='ajax("quitarPermiso", {{$id}}, {{$permiso->id}})' class="btn-accion-tabla tooltipsC" title="Quitar">
                                                <i class="material-icons text-danger" style="font-size: 17px;">remove_circle</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        @if (count($permisoNoAsignado)<=0) 
                            <div class="alert alert-info">
                                Todos los permisos ha sido asignados
                            </div>
                        @else
                            @if ($id == 1) 
                                <div class="alert alert-info">
                                    El usuario administrador tiene acceso a todos los permisos
                                </div>
                            @else
                                <table class="table table-striped table-bordered table-hover js-basic-example" id="tabla-data">
                                    <thead>
                                        <tr>
                                            <th>Permiso</th>
                                            <th class="width70"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permisoNoAsignado as $permiso)
                                        <tr>
                                            <td>{{$permiso->PRM_Nombre_Permiso}}</td>
                                            <td>
                                                <button type="button" onclick='ajax("agregarPermiso", {{$id}}, {{$permiso->id}})' class="btn-accion-tabla tooltipsC" title="Asignar">
                                                    <i class="material-icons text-success" style="font-size: 17px;">add_circle</i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div id="rol" style="display: none;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Lista de Roles
                    </h2>
                </div>
                <div class="body table-responsive">
                    <div class="col-lg-6">
                        @if (count($rolesAsignados)<=0) 
                            <div class="alert alert-warning">
                                No hay datos que mostrar
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover js-basic-example" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Rol</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rolesAsignados as $rol)
                                    <tr>
                                        <td>{{$rol->RLS_Nombre_Rol}}</td>
                                        <td>
                                            <button type="button" onclick='ajax("quitarRol", {{$id}}, {{$rol->id}})' class="btn-accion-tabla tooltipsC" title="Quitar">
                                                <i class="material-icons text-danger" style="font-size: 17px;">remove_circle</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        @if (count($rolesNoAsignados)<=0) 
                            <div class="alert alert-warning">
                                No hay datos que mostrar
                            </div>
                        @else
                            <table class="table table-striped table-bordered table-hover js-basic-example" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Rol</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rolesNoAsignados as $rol)
                                    <tr>
                                        <td>{{$rol->RLS_Nombre_Rol}}</td>
                                        <td>
                                            <button type="button" onclick='ajax("agregarRol", {{$id}}, {{$rol->id}})' class="btn-accion-tabla tooltipsC" title="Asignar">
                                                <i class="material-icons text-success" style="font-size: 17px;">add_circle</i>
                                            </button>
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
</div>
@endsection
@section('scripts')
    <script src="{{asset("assets/pages/scripts/Administrador/Permisos/index.js")}}"></script>
    <script type="text/javascript">
         var opcion = $("#opcionFiltro").val()
        if(opcion == 1){
            menu.style.display = 'block';
            permiso.style.display = 'none';
            rol.style.display = 'none';
        }else if(opcion == 2){
            menu.style.display = 'none';
            permiso.style.display = 'block';
            rol.style.display = 'none';
        }else if(opcion == 3){
            menu.style.display = 'none';
            permiso.style.display = 'none';
            rol.style.display = 'block';
        }
        $("#opcionFiltro").change(function(){
            var opcion = $(this).val();
            var menu = document.getElementById('menu');
            var permiso = document.getElementById('permiso');
            var rol = document.getElementById('rol');
            if(opcion == 1){
                menu.style.display = 'block';
                permiso.style.display = 'none';
                rol.style.display = 'none';
            }else if(opcion == 2){
                menu.style.display = 'none';
                permiso.style.display = 'block';
                rol.style.display = 'none';
            }else if(opcion == 3){
                menu.style.display = 'none';
                permiso.style.display = 'none';
                rol.style.display = 'block';
            }
        });
    </script>
@endsection