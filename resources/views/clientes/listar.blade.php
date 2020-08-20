@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Clientes
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

    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>

    <script>
        jQuery(document).ready(function($){
            ////----- Abre modal para crear rol -----////
            jQuery('#add_cliente').click(function () {
                jQuery('#btn_guardar').val("add");
                document.getElementById('documento_usuario').style.display = 'block';
                document.getElementById('ciudad_residencia').style.display = 'block';
                document.getElementById('password_cliente').style.display = 'none';
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormCliente').modal('show');
            });
        
            ////----- Abre modal para editar rol -----////
            jQuery('body').on('click', '.open-modal', function () {
                var cliente_id = $(this).val();
                $.get(cliente_id + '-' + jQuery('#id').val() +'/editar', function (data) {
                    jQuery('#cliente_id').val(data.cliente.id);
                    jQuery('#USR_Documento_Usuario').val(data.cliente.USR_Documento_Usuario);
                    jQuery('#USR_Nombres_Usuario').val(data.cliente.USR_Nombres_Usuario);
                    jQuery('#USR_Apellidos_Usuario').val(data.cliente.USR_Apellidos_Usuario);
                    jQuery('#USR_Fecha_Nacimiento_Usuario').val(data.cliente.USR_Fecha_Nacimiento_Usuario);
                    jQuery('#USR_Direccion_Residencia_Usuario').val(data.cliente.USR_Direccion_Residencia_Usuario);
                    jQuery('#USR_Telefono_Usuario').val(data.cliente.USR_Telefono_Usuario);
                    jQuery('#USR_Correo_Usuario').val(data.cliente.USR_Correo_Usuario);
                    jQuery('#USR_Nombre_Usuario').val(data.cliente.USR_Nombre_Usuario);

                    document.getElementById('documento_usuario').style.display = 'none';
                    document.getElementById('ciudad_residencia').style.display = 'none';
                    var rol = {{ Session::get('Rol_Id')}}
                    document.getElementById('password_cliente').style.display = (rol == 1) ? 'block' : 'none';

                    jQuery('#btn_guardar').val("update");
                    jQuery('#modalFormCliente').modal('show');
                })
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar").click(function (e) {
                if($("#form_validation").valid()){
                    e.preventDefault();
                    var formData = {
                        "_token": "{{ csrf_token() }}",
                        id: jQuery('#id').val(),
                        USR_Tipo_Documento_Usuario: jQuery('#USR_Tipo_Documento_Usuario').val(),
                        USR_Documento_Usuario: jQuery('#USR_Documento_Usuario').val(),
                        USR_Nombres_Usuario: jQuery('#USR_Nombres_Usuario').val(),
                        USR_Apellidos_Usuario: jQuery('#USR_Apellidos_Usuario').val(),
                        USR_Fecha_Nacimiento_Usuario: jQuery('#USR_Fecha_Nacimiento_Usuario').val(),
                        USR_Direccion_Residencia_Usuario: jQuery('#USR_Direccion_Residencia_Usuario').val(),
                        USR_Ciudad_Residencia_Usuario: jQuery('#USR_Ciudad_Residencia_Usuario').val(),
                        USR_Telefono_Usuario: jQuery('#USR_Telefono_Usuario').val(),
                        USR_Correo_Usuario: jQuery('#USR_Correo_Usuario').val(),
                        USR_Nombre_Usuario: jQuery('#USR_Nombre_Usuario').val(),
                    };
                    var state = jQuery('#btn_guardar').val();
                    var type = "POST";
                    var cliente_id = jQuery('#cliente_id').val();
                    var empresa_id = jQuery('#id').val();
                    var ajaxurl = 'crear';
                    if (state == "update") {
                        type = "PUT";
                        ajaxurl = '' + cliente_id + '-' + empresa_id;
                    }
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var cliente = '<tr id="cliente'+ data.usuario.id +'"><td>'+ data.usuario.USR_Documento_Usuario +'</td><td>'+ data.usuario.USR_Nombres_Usuario +' '+ data.usuario.USR_Apellidos_Usuario +'</td><td>'+ data.usuario.USR_Telefono_Usuario +'</td><td>'+ data.usuario.USR_Correo_Usuario +'</td><td>'+ data.usuario.USR_Nombre_Usuario +'</td><td>';
                                if(data.permisos.editar == true){
                                    cliente += '<button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="'+ data.usuario.id +'"><i class="material-icons text-info" style="font-size: 18px;">edit</i></button>';
                                }
                                if(data.permisos.eliminar == true){
                                    cliente += '<!--<button class="btn-accion-tabla tooltipsC delete-cliente" value="'+ data.usuario.id +'" title="Eliminar este Registro"><i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i></button>-->';
                                }
                                cliente += '</td></tr>';
                                if (state == "add") {
                                    jQuery('#lista-clientes').append(cliente);
                                } else {
                                    $("#cliente" + cliente_id).replaceWith(cliente);
                                }
                                jQuery('#form_validation').trigger("reset");
                                jQuery('#modalFormCliente').modal('hide');

                                InkBrutalPRY.notificaciones('Cliente '+(state == "add" ? 'registrado' : 'editado')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.errors != null){
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' el cliente.', 'InkBrutalPRY', 'warning');
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        
            ////----- Elimina el rol y lo quita de la vista -----////
            jQuery('.reset-password').click(function () {
                event.preventDefault();
                const cliente_id = $('#cliente_id').val();
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
                        ajaxRequest(cliente_id);
                    }
                });
            });

            function ajaxRequest(cliente_id){
                $.ajax({
                    type: "PUT",
                    url: cliente_id + '/restaurar_clave',
                    data: {"_token": "{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function (data) {
                        if (data.mensaje == "ok") {
                            InkBrutalPRY.notificaciones('La contraseña del usuario ha sido reestablecida.', 'InkBrutalPRY', 'success');
                            jQuery('#modalFormCliente').modal('hide');
                        } else if (data.mensaje == "error") {
                            InkBrutalPRY.notificaciones('Error al restablecer la contraseña del usuario', 'InkBrutalPRY', 'error');
                            jQuery('#modalFormCliente').modal('hide');
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
                        <h2>LISTA DE CLIENTES</h2>
                        <ul class="header-dropdown" style="top:10px;">
                            <li class="dropdown">
                                @if ($permisos['crear']==true)
                                    <a id="add_cliente" name="add_cliente" class="btn btn-success waves-effect">
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
                                No hay Datos que mostrar.
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
                                <tbody id="lista-clientes" name="lista-clientes">
                                    @foreach ($clientes as $cliente)
                                        <tr id="cliente{{$cliente->id}}">
                                            <td>{{$cliente->USR_Documento_Usuario}}</td>
                                            <td>{{$cliente->USR_Nombres_Usuario.' '.$cliente->USR_Apellidos_Usuario}}</td>
                                            <td>{{$cliente->USR_Telefono_Usuario}}</td>
                                            <td>{{$cliente->USR_Correo_Usuario}}</td>
                                            <td>{{$cliente->USR_Nombre_Usuario}}</td>
                                            @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                                <td>
                                                    @if ($permisos['editar']==true)
                                                        <button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="{{$cliente->id}}">
                                                            <i class="material-icons text-info" style="font-size: 18px;">edit</i>
                                                        </button>
                                                    @endif    
                                                    @if ($permisos['eliminar']==true)
                                                        <!--<button class="btn-accion-tabla tooltipsC delete-rol" value="{{$cliente->id}}" title="Eliminar este Registro">
                                                            <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                        </button>-->
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

                <div class="modal fade" id="modalFormCliente" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel">Cliente</h4>
                            </div>
                            <div class="modal-body">
                                <form id="form_validation" method="POST">
                                    @csrf
                                    @include('clientes.form')
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