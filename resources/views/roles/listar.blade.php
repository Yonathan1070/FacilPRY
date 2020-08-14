@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Roles
@endsection
@section('styles')
    <style>
        
    </style>
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
                        LISTADO DE ROLES
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear'] == true)
                                <a id="add_rol" name="add_rol" class="btn btn-success waves-effect">
                                    <i class="material-icons" style="color:white;">add</i> Nuevo Rol
                                </a>
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
                                <tbody id="lista-roles" name="lista-roles">
                                    @foreach ($roles as $rol)
                                        <tr id="rol{{$rol->id}}">
                                            <td>
                                                {{$rol->RLS_Nombre_Rol}}
                                                @if ($rol->RLS_Rol_Id != 4 || $rol->id == 4)
                                                    <label style="color: red">(*)</label>
                                                @endif
                                            </td>
                                            <td>{{$rol->RLS_Descripcion_Rol}}</td>
                                            @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                                <td>
                                                    @if ($permisos['editar'] == true)
                                                        <button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="{{$rol->id}}">
                                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                        </button>
                                                    @endif
                                                    @if ($permisos['eliminar'] == true)
                                                        <button class="btn-accion-tabla tooltipsC delete-rol" value="{{$rol->id}}" title="Eliminar este Registro">
                                                            <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                        </button>
                                                    @endif
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

            <div class="modal fade" id="modalFormRol" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Rol</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form_validation" action="{{route('guardar_rol')}}" method="POST">
                                @csrf
                                @include('roles.form')
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CANCELAR</a>
                            <button type="button" id="btn_guardar" class="btn btn-primary waves-effect" value="save">GUARDAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>

    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>

    <script>
        jQuery(document).ready(function($){
            ////----- Abre modal para crear rol -----////
            jQuery('#add_rol').click(function () {
                jQuery('#btn_guardar').val("add");
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormRol').modal('show');
            });
        
            ////----- Abre modal para editar rol -----////
            jQuery('body').on('click', '.open-modal', function () {
                var rol_id = $(this).val();
                $.get('roles/' + rol_id + '/editar', function (data) {
                    if(data.mensaje == "rd"){
                        InkBrutalPRY.notificaciones('El rol es por defecto del sistema, no es posible editar este registro.', 'InkBrutalPRY', 'warning');
                    }else{
                        jQuery('#rol_id').val(data.rol.id);
                        jQuery('#RLS_Nombre_Rol').val(data.rol.RLS_Nombre_Rol);
                        jQuery('#RLS_Descripcion_Rol').val(data.rol.RLS_Descripcion_Rol);
                        jQuery('#btn_guardar').val("update");
                        jQuery('#modalFormRol').modal('show');
                    }
                })
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar").click(function (e) {
                if($("#form_validation").valid()){
                    e.preventDefault();
                    var formData = {
                        "_token": "{{ csrf_token() }}",
                        RLS_Nombre_Rol: jQuery('#RLS_Nombre_Rol').val(),
                        RLS_Descripcion_Rol: jQuery('#RLS_Descripcion_Rol').val(),
                    };
                    var state = jQuery('#btn_guardar').val();
                    var type = "POST";
                    var rol_id = jQuery('#rol_id').val();
                    var ajaxurl = 'roles/crear-rol';
                    if (state == "update") {
                        type = "PUT";
                        ajaxurl = 'roles/' + rol_id;
                    }
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var rol = '<tr id="rol' + data.rol.id + '"><td>' + data.rol.RLS_Nombre_Rol + '</td><td>' + data.rol.RLS_Descripcion_Rol + '</td><td>';
                                if(data.permisos.editar == true){
                                    rol += '<button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="' + data.rol.id + '"><i class="material-icons text-info" style="font-size: 17px;">edit</i></button>';
                                }
                                if(data.permisos.eliminar == true){
                                    rol += '<button class="btn-accion-tabla tooltipsC delete-rol" value="' + data.rol.id + '" title="Eliminar este Registro"><i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i></button>';
                                }
                                rol += '</td></tr>';
                                if (state == "add") {
                                    jQuery('#lista-roles').append(rol);
                                } else {
                                    $("#rol" + rol_id).replaceWith(rol);
                                }
                                jQuery('#form_validation').trigger("reset");
                                jQuery('#modalFormRol').modal('hide');

                                InkBrutalPRY.notificaciones('Rol '+(state == "add" ? 'registrado' : 'editado')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.mensaje == "dr"){
                                InkBrutalPRY.notificaciones('El rol ya se encuentra registrado en el sistema.', 'InkBrutalPRY', 'warning');
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' el rol.', 'InkBrutalPRY', 'warning');
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        
            ////----- Elimina el rol y lo quita de la vista -----////
            jQuery('.delete-rol').click(function () {
                event.preventDefault();
                const rol_id = $(this).val();
                swal({
                    title: '¿Está seguro que desea eliminar el rol?',
                    text: 'Esta acción no se puede deshacer!',
                    icon: 'warning',
                    buttons: {
                        cancel: "Cancelar",
                        confirm: "Aceptar"
                    },
                }).then((value) => {
                    if (value) {
                        ajaxRequest(rol_id);
                    }
                });
            });

            function ajaxRequest(rol_id){
                $.ajax({
                    type: "DELETE",
                    url: 'roles/' + rol_id,
                    data: {"_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        if (data.mensaje == "ok") {
                            $("#rol" + rol_id).remove();
                            InkBrutalPRY.notificaciones('El rol fue eliminado correctamente', 'InkBrutalPRY', 'success');
                        } else if (data.mensaje == "rd") {
                            InkBrutalPRY.notificaciones('El rol es por defecto del sistema, no es posible eliminarlo.', 'InkBrutalPRY', 'error');
                        } else if (respuesta.mensaje == "np") {
                            InkBrutalPRY.notificaciones('No tiene permisos para entrar en este modulo.', 'InkBrutalPRY', 'error');
                        }  else {
                            InkBrutalPRY.notificaciones('El rol no pudo ser eliminado o hay otro recurso usándolo', 'InkBrutalPRY', 'error');
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    </script>
@endsection