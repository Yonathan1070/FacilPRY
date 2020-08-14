@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Perfil de Operación
@endsection
@section('styles')
    <style>
        .modal-dialog{
            width: 900px;
        }
    </style>
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
    <script>
        function inactivar(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/perfil-operacion/" + id + "/inactivar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "ok") {
                    location.reload();
                    InkBrutalPRY.notificaciones('Perfil de operación ha quedado inactivo', 'InkBrutalPRY', 'success');
                } else {
                    InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
                }
            });
        }

        function activar(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/perfil-operacion/" + id + "/activar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "ok") {
                    location.reload();
                    InkBrutalPRY.notificaciones('Perfil de operación ha quedado activo', 'InkBrutalPRY', 'success');
                } else {
                    InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
                }
            });
        }
    </script>

    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>

    <script>
        jQuery(document).ready(function($){
            ////----- Abre modal para crear rol -----////
            jQuery('#add_perfil').click(function () {
                jQuery('#btn_guardar').val("add");
                document.getElementById('documento_usuario').style.display = 'block';
                document.getElementById('ciudad_residencia').style.display = 'block';
                document.getElementById('rol_perfil').style.display = 'block';
                document.getElementById('password_perfil').style.display = 'none';
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormPerfil').modal('show');
            });
        
            ////----- Abre modal para editar rol -----////
            jQuery('body').on('click', '.open-modal', function () {
                var perfil_id = $(this).val();
                $.get('perfil-operacion/' + perfil_id + '/editar', function (data) {
                    jQuery('#perfil_id').val(data.perfil.id);
                    jQuery('#USR_Documento_Usuario').val(data.perfil.USR_Documento_Usuario);
                    jQuery('#USR_Nombres_Usuario').val(data.perfil.USR_Nombres_Usuario);
                    jQuery('#USR_Apellidos_Usuario').val(data.perfil.USR_Apellidos_Usuario);
                    jQuery('#USR_Fecha_Nacimiento_Usuario').val(data.perfil.USR_Fecha_Nacimiento_Usuario);
                    jQuery('#USR_Direccion_Residencia_Usuario').val(data.perfil.USR_Direccion_Residencia_Usuario);
                    jQuery('#USR_Telefono_Usuario').val(data.perfil.USR_Telefono_Usuario);
                    jQuery('#USR_Correo_Usuario').val(data.perfil.USR_Correo_Usuario);
                    jQuery('#USR_Nombre_Usuario').val(data.perfil.USR_Nombre_Usuario);
                    jQuery('#USR_Costo_Hora').val(data.perfil.USR_Costo_Hora);

                    document.getElementById('documento_usuario').style.display = 'none';
                    document.getElementById('ciudad_residencia').style.display = 'none';
                    document.getElementById('rol_perfil').style.display = 'none';
                    var rol = {{ Session::get('Rol_Id')}}
                    document.getElementById('password_perfil').style.display = (rol == 1) ? 'block' : 'none';

                    jQuery('#btn_guardar').val("update");
                    jQuery('#modalFormPerfil').modal('show');
                });
                
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar").click(function (e) {
                if($("#form_validation").valid()){
                    e.preventDefault();
                    var formData = {
                        "_token": "{{ csrf_token() }}",
                        USR_Tipo_Documento_Usuario: jQuery('#USR_Tipo_Documento_Usuario').val(),
                        USR_Documento_Usuario: jQuery('#USR_Documento_Usuario').val(),
                        USR_Nombres_Usuario: jQuery('#USR_Nombres_Usuario').val(),
                        USR_Apellidos_Usuario: jQuery('#USR_Apellidos_Usuario').val(),
                        USR_Fecha_Nacimiento_Usuario: jQuery('#USR_Fecha_Nacimiento_Usuario').val(),
                        USR_Direccion_Residencia_Usuario: jQuery('#USR_Direccion_Residencia_Usuario').val(),
                        USR_Ciudad_Residencia_Usuario: jQuery('#USR_Ciudad_Residencia_Usuario').val(),
                        USR_Telefono_Usuario: jQuery('#USR_Telefono_Usuario').val(),
                        USR_Correo_Usuario: jQuery('#USR_Correo_Usuario').val(),
                        USR_RLS_Rol_Id: jQuery('#USR_RLS_Rol_Id').val(),
                        USR_Nombre_Usuario: jQuery('#USR_Nombre_Usuario').val(),
                        USR_Costo_Hora: jQuery('#USR_Costo_Hora').val(),
                    };
                    var state = jQuery('#btn_guardar').val();
                    var type = "POST";
                    var perfil_id = jQuery('#perfil_id').val();
                    var ajaxurl = 'perfil-operacion/crear';
                    if (state == "update") {
                        type = "PUT";
                        ajaxurl = 'perfil-operacion/' + perfil_id;
                    }
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var perfil = '<tr id="perfil'+ data.usuario.id +'"><td>'+ data.usuario.USR_Documento_Usuario +'</td><td>'+ data.usuario.USR_Nombres_Usuario +' '+ data.usuario.USR_Apellidos_Usuario +'</td><td>'+ data.usuario.USR_Telefono_Usuario +'</td><td>'+ data.usuario.USR_Correo_Usuario +'</td><td>'+ data.usuario.RLS_Nombre_Rol +'</td><td>';
                                    perfil += '<button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="'+ data.usuario.id +'"><i class="material-icons text-info" style="font-size: 18px;">edit</i></button>';
                                    perfil += '<a onclick="inactivar('+ data.usuario.id +')" class="tooltipsC" title="Desactivar Perfil de Operación"><i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i></a>';
                                    perfil += '<a href="{{route("carga_perfil_operacion", ["id"=>'+ data.usuario.id +'])}}" class="btn-accion-tabla tooltipsC" title="Ver Carga de Trabajo"><i class="material-icons text-success" style="font-size: 18px;">remove_red_eye</i></a>';
                                    perfil += '</td></tr>';
                                if (state == "add") {
                                    jQuery('#lista-perfiles').append(perfil);
                                } else {
                                    $("#perfil" + data.usuario.id).replaceWith(perfil);
                                }
                                jQuery('#form_validation').trigger("reset");
                                jQuery('#modalFormPerfil').modal('hide');

                                InkBrutalPRY.notificaciones('Perfil de Operación '+(state == "add" ? 'registrado' : 'editado')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.errors != null){
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' el perfil de operación.', 'InkBrutalPRY', 'warning');
                            }
                        },
                        error: function (data) {
                            alert(data.errors);
                        }
                    });
                }
            });

            ////----- Elimina el rol y lo quita de la vista -----////
            jQuery('.reset-password').click(function () {
                event.preventDefault();
                const perfil_id = $('#perfil_id').val();
                swal({
                    title: '¿Está seguro que desea reestablecer la contraseña del usuario?',
                    text: 'se enviará correo notificando al usuario!',
                    icon: 'warning',
                    buttons: {
                        cancel: "Cancelar",
                        confirm: "Aceptar"
                    },
                }).then((value) => {
                    if (value) {
                        ajaxRequest(perfil_id);
                    }
                });
            });

            function ajaxRequest(perfil_id){
                $.ajax({
                    type: "PUT",
                    url: 'perfil-operacion/' + perfil_id + '/restaurar_clave',
                    data: {"_token": "{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function (data) {
                        if (data.mensaje == "ok") {
                            InkBrutalPRY.notificaciones('La contraseña del usuario ha sido reestablecida.', 'InkBrutalPRY', 'success');
                            jQuery('#modalFormPerfil').modal('hide');
                        } else if (data.mensaje == "error") {
                            InkBrutalPRY.notificaciones('Error al restablecer la contraseña del usuario', 'InkBrutalPRY', 'error');
                            jQuery('#modalFormPerfil').modal('hide');
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
@section('contenido')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('includes.form-exito')
                @include('includes.form-error')
                <div class="card">
                    <div class="header">
                        <h2>LISTADO DE PERFIL DE OPERACIÓN</h2>
                        <ul class="header-dropdown" style="top:10px;">
                            <li class="dropdown">
                                <a id="add_perfil" name="add_perfil" class="btn btn-success waves-effect">
                                    <i class="material-icons" style="color:white;">add</i> Nuevo Perfil de Operación
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body table-responsive">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#activos" aria-controls="settings" role="tab" data-toggle="tab">Activos</a></li>
                                <li role="presentation"><a href="#inactivos" aria-controls="settings" role="tab" data-toggle="tab">Inactivos</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="activos">
                                    @if (count($perfilesOperacionActivos)<=0)
                                        <div class="alert alert-info">
                                            El sistema no cuenta con Perfil de Operación activos.
                                        </div>
                                    @else
                                        <table class="table table-striped table-bordered table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Documento</th>
                                                    <th>Nombre y Apellido</th>
                                                    <th>Telefono</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Cargo</th>
                                                    <th class="width70"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="lista-perfiles" name="lista-perfiles">
                                                @foreach ($perfilesOperacionActivos as $perfilOperacion)
                                                    <tr id="perfil{{$perfilOperacion->Id_Perfil}}">
                                                        <td>{{$perfilOperacion->USR_Documento_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Telefono_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Correo_Usuario}}</td>
                                                        <td>{{$perfilOperacion->RLS_Nombre_Rol}}</td>
                                                        <td>
                                                            <button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="{{$perfilOperacion->Id_Perfil}}">
                                                                <i class="material-icons text-info" style="font-size: 18px;">edit</i>
                                                            </button>
                                                            <a onclick="inactivar({{$perfilOperacion->Id_Perfil}})" class="tooltipsC" title="Desactivar Perfil de Operación">
                                                                <i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i>
                                                            </a>
                                                            <a href="{{route('carga_perfil_operacion', ['id'=>$perfilOperacion->Id_Perfil])}}" class="btn-accion-tabla tooltipsC" title="Ver Carga de Trabajo">
                                                                <i class="material-icons text-success" style="font-size: 18px;">remove_red_eye</i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="inactivos">
                                    @if (count($perfilesOperacionInactivos)<=0)
                                        <div class="alert alert-info">
                                            El sistema no cuenta con Perfil de Operación inactivo.
                                        </div>
                                    @else
                                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                            <thead>
                                                <tr>
                                                    <th>Documento</th>
                                                    <th>Nombre y Apellido</th>
                                                    <th>Telefono</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Cargo</th>
                                                    <th class="width70"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($perfilesOperacionInactivos as $perfilOperacion)
                                                    <tr>
                                                        <td>{{$perfilOperacion->USR_Documento_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Telefono_Usuario}}</td>
                                                        <td>{{$perfilOperacion->USR_Correo_Usuario}}</td>
                                                        <td>{{$perfilOperacion->RLS_Nombre_Rol}}</td>
                                                        <td>
                                                            <form class="form-eliminar" action="{{route('eliminar_perfil_operacion', ['id'=>$perfilOperacion->Id_Perfil])}}" class="d-inline" method="POST">
                                                                <a href="{{route('activar_perfil_operacion', ['id'=>$perfilOperacion->Id_Perfil])}}" class="btn-accion-tabla tooltipsC" title="Activar Usuario">
                                                                    <i class="material-icons text-success" style="font-size: 18px;">arrow_upward</i>
                                                                </a>
                                                                @csrf @method("delete")
                                                                <!--<button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm" title="Eliminar Perfil de operación">
                                                                    <i class="material-icons text-danger" style="font-size: 18px;">delete_forever</i>
                                                                </button>-->
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

                <div class="modal fade" id="modalFormPerfil" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel">Perfil de Operación</h4>
                            </div>
                            <div class="modal-body">
                                <form id="form_validation">
                                    @csrf
                                    @include('director.perfiloperacion.form')
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
    </div>
@endsection