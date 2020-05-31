@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Asignar Permisos
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
                        Asignar permisos a {{$usuario->USR_Nombres_Usuario.' '.$usuario->USR_Apellidos_Usuario}}
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
                <div id="menus" class="body">
                    <div class="demo-switch">
                        @csrf
                        <input type="hidden" id="IdUsuario" value="{{$usuario->id}}">
                        <div class="row clearfix">
                            @foreach ($menuAsignado as $menu)
                                <div class="col-sm-3">
                                    <div class="demo-switch-title">{{$menu->MN_Nombre_Menu}}</div>
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox"
                                                id="chMenu{{$menu->id}}"
                                                name="chMenu{{$menu->id}}"
                                                data-menuid="{{$menu->id}}"
                                                value="{{$menu->id}}"
                                                checked
                                                class="menu-usuario"
                                            >
                                            <span class="lever switch-col-green"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row clearfix">
                            @foreach ($menuNoAsignado as $menu)
                                <div class="col-sm-3">
                                    <div class="demo-switch-title">{{$menu->MN_Nombre_Menu}}</div>
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox"
                                                id="chMenu{{$menu->id}}"
                                                name="chMenu{{$menu->id}}"
                                                data-menuid="{{$menu->id}}"
                                                value="{{$menu->id}}"
                                                class="menu-usuario"
                                            >
                                            <span class="lever switch-col-cyan"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="permisos" class="body" style="display: none;">
                    <div class="demo-switch">
                        <div class="row clearfix">
                            @foreach ($permisoAsignado as $permiso)
                                <div class="col-sm-3">
                                    <div class="demo-switch-title">{{$permiso->PRM_Nombre_Permiso}}</div>
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox"
                                                id="chPermiso{{$permiso->id}}"
                                                name="chPermiso{{$permiso->id}}"
                                                data-permisoid="{{$permiso->id}}"
                                                value="{{$permiso->id}}"
                                                checked
                                                class="permiso-usuario"
                                            >
                                            <span class="lever switch-col-green"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row clearfix">
                            @foreach ($permisoNoAsignado as $permiso)
                                <div class="col-sm-3">
                                    <div class="demo-switch-title">{{$permiso->PRM_Nombre_Permiso}}</div>
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox"
                                                id="chPermiso{{$permiso->id}}"
                                                name="chPermiso{{$permiso->id}}"
                                                data-permisoid="{{$permiso->id}}"
                                                value="{{$permiso->id}}"
                                                class="permiso-usuario"
                                            >
                                            <span class="lever switch-col-cyan"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="roles" class="body" style="display: none;">
                    <div class="demo-switch">
                        <div class="row clearfix">
                            @foreach ($rolesAsignados as $rol)
                                <div class="col-sm-3">
                                    <div class="demo-switch-title">{{$rol->RLS_Nombre_Rol}}</div>
                                    <div class="switch">
                                        <label>
                                            <input type="checkbox"
                                                id="chRol{{$rol->id}}"
                                                name="chRol{{$rol->id}}"
                                                data-rolid="{{$rol->id}}"
                                                value="{{$rol->id}}"
                                                checked
                                                class="rol-usuario"
                                            >
                                            <span class="lever switch-col-green"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row clearfix">
                        @foreach ($rolesNoAsignados as $rol)
                            <div class="col-sm-3">
                                <div class="demo-switch-title">{{$rol->RLS_Nombre_Rol}}</div>
                                <div class="switch">
                                    <label>
                                        <input type="checkbox"
                                            id="chRol{{$rol->id}}"
                                            name="chRol{{$rol->id}}"
                                            data-rolid="{{$rol->id}}"
                                            value="{{$rol->id}}"
                                            class="rol-usuario"
                                        >
                                        <span class="lever switch-col-cyan"></span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
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
        $("#opcionFiltro").change(function(){
            var opcion = $(this).val();
            var menu = document.getElementById('menus');
            var permiso = document.getElementById('permisos');
            var rol = document.getElementById('roles');
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