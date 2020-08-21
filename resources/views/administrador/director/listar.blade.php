@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Director de Proyectos
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
                url: "/administrador/director-proyectos/" + id + "/inactivar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "ok") {
                    location.reload();
                    InkBrutalPRY.notificaciones('Director de proyectos ha quedado inactivo', 'InkBrutalPRY', 'success');
                } else {
                    InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
                }
            });
        }

        function activar(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/administrador/director-proyectos/" + id + "/activar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "ok") {
                    location.reload();
                    InkBrutalPRY.notificaciones('Director de proyectos ha quedado activo', 'InkBrutalPRY', 'success');
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
            jQuery('#add_director').click(function () {
                jQuery('#btn_guardar').val("add");
                document.getElementById('password_director').style.display = 'none';
                document.getElementById('documento_usuario').style.display = 'block';
                document.getElementById('ciudad_residencia').style.display = 'block';
                document.getElementById('roles_director').style.display = 'block';
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormDirector').modal('show');
            });
        
            ////----- Abre modal para editar rol -----////
            jQuery('body').on('click', '.open-modal', function () {
                $('.page-loader-wrapper').fadeIn();
                var director_id = $(this).val();
                $.get('director-proyectos/' + director_id + '/editar', function (data) {
                    jQuery('#director_id').val(data.director.id);
                    jQuery('#USR_Documento_Usuario').val(data.director.USR_Documento_Usuario);
                    jQuery('#USR_Nombres_Usuario').val(data.director.USR_Nombres_Usuario);
                    jQuery('#USR_Apellidos_Usuario').val(data.director.USR_Apellidos_Usuario);
                    jQuery('#USR_Fecha_Nacimiento_Usuario').val(data.director.USR_Fecha_Nacimiento_Usuario);
                    jQuery('#USR_Direccion_Residencia_Usuario').val(data.director.USR_Direccion_Residencia_Usuario);
                    jQuery('#USR_Telefono_Usuario').val(data.director.USR_Telefono_Usuario);
                    jQuery('#USR_Correo_Usuario').val(data.director.USR_Correo_Usuario);
                    jQuery('#USR_Nombre_Usuario').val(data.director.USR_Nombre_Usuario);
                    jQuery('#btn_guardar').val("update");
                    jQuery('#modalFormDirector').modal('show');
                });
                document.getElementById('password_director').style.display = 'block';
                document.getElementById('documento_usuario').style.display = 'none';
                document.getElementById('ciudad_residencia').style.display = 'none';
                document.getElementById('roles_director').style.display = 'none';
                $('.page-loader-wrapper').fadeOut();
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar").click(function (e) {
                $('.page-loader-wrapper').fadeIn();
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
                        USR_Nombre_Usuario: jQuery('#USR_Nombre_Usuario').val(),
                    };
                    var state = jQuery('#btn_guardar').val();
                    var type = "POST";
                    var director_id = jQuery('#director_id').val();
                    var ajaxurl = 'director-proyectos/crear-director';
                    if (state == "update") {
                        type = "PUT";
                        ajaxurl = 'director-proyectos/' + director_id;
                    }
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var rows = $('.dt-directores-activos>tbody>tr').length;
                                if(rows == 0){
                                    location.reload();
                                } else {
                                    var director = '<tr id="director'+ data.usuario.id +'"><td>'+ data.usuario.USR_Documento_Usuario +'</td><td>'+ data.usuario.USR_Nombres_Usuario +' '+ data.usuario.USR_Apellidos_Usuario +'</td><td>'+ data.usuario.USR_Correo_Usuario +'</td><td>'+ data.usuario.USR_Telefono_Usuario +'</td><td>'+ data.usuario.USR_Nombre_Usuario +'</td><td>';
                                        director += '<button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="'+ data.usuario.id +'"><i class="material-icons text-info" style="font-size: 17px;">edit</i></button>';
                                        director += '<a onclick="inactivar('+ data.usuario.id +')" class="tooltipsC" title="Desactivar Director"><i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i></a>';
                                        director += '</td></tr>';
                                    if (state == "add") {
                                        jQuery('#lista-directores').append(director);
                                    } else {
                                        $("#director" + data.usuario.id).replaceWith(director);
                                    }
                                }
                                jQuery('#form_validation').trigger("reset");
                                jQuery('#modalFormDirector').modal('hide');

                                InkBrutalPRY.notificaciones('Director de proyectos '+(state == "add" ? 'registrado' : 'editado')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.errors != null){
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' el director de proyectos.', 'InkBrutalPRY', 'warning');
                            }
                        },
                        error: function (data) {
                            alert(data.errors);
                        }
                    });
                }
                $('.page-loader-wrapper').fadeOut();
            });

            ////----- Elimina el rol y lo quita de la vista -----////
            jQuery('.reset-password').click(function () {
                event.preventDefault();
                const director_id = $('#director_id').val();
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
                        ajaxRequest(director_id);
                    }
                });
            });

            function ajaxRequest(director_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    type: "PUT",
                    url: 'director-proyectos/' + director_id + '/restaurar_clave',
                    data: {"_token": "{{ csrf_token() }}"},
                    dataType: 'json',
                    success: function (data) {
                        if (data.mensaje == "ok") {
                            InkBrutalPRY.notificaciones('La contraseña del usuario ha sido reestablecida.', 'InkBrutalPRY', 'success');
                            jQuery('#modalFormDirector').modal('hide');
                        } else if (data.mensaje == "error") {
                            InkBrutalPRY.notificaciones('Error al restablecer la contraseña del usuario', 'InkBrutalPRY', 'error');
                            jQuery('#modalFormDirector').modal('hide');
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
                $('.page-loader-wrapper').fadeOut();
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
                    <h2>
                        LISTA DIRECTORES DE PROYECTOS
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a id="add_director" name="add_director" class="btn btn-success waves-effect">
                                <i class="material-icons" style="color:white;">add</i> Nuevo Director de Proyectos
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
                                @if (count($directoresActivos)<=0)
                                    <div class="alert alert-info">
                                        El sistema no cuenta con Directores activos.
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable dt-directores-activos">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th>Nombre y Apellido</th>
                                                <th>Correo Electrónico</th>
                                                <th>Telefono</th>
                                                <th>Nombre de Usuario</th>
                                                <th class="width70"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista-directores" name="lista-directores">
                                            @foreach ($directoresActivos as $director)
                                                <tr id="director{{$director->USR_RLS_Usuario_Id}}">
                                                    <td>{{$director->USR_Documento_Usuario}}</td>
                                                    <td>{{$director->USR_Nombres_Usuario.' '.$director->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$director->USR_Correo_Usuario}}</td>
                                                    <td>{{$director->USR_Telefono_Usuario}}</td>
                                                    <td>{{$director->USR_Nombre_Usuario}}</td>
                                                    <td>
                                                        <button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="{{$director->USR_RLS_Usuario_Id}}">
                                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                        </button>
                                                        <a onclick="inactivar({{$director->USR_RLS_Usuario_Id}})" class="tooltipsC" title="Desactivar Director">
                                                            <i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="inactivos">
                                @if (count($directoresInactivos)<=0)
                                    <div class="alert alert-info">
                                        El sistema no cuenta con Directores inactivos.
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable dt-directores-inactivos" id="tabla-data">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th>Nombre y Apellido</th>
                                                <th>Correo Electrónico</th>
                                                <th>Telefono</th>
                                                <th>Nombre de Usuario</th>
                                                <th class="width70"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($directoresInactivos as $director)
                                                <tr>
                                                    <td>{{$director->USR_Documento_Usuario}}</td>
                                                    <td>{{$director->USR_Nombres_Usuario.' '.$director->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$director->USR_Correo_Usuario}}</td>
                                                    <td>{{$director->USR_Telefono_Usuario}}</td>
                                                    <td>{{$director->USR_Nombre_Usuario}}</td>
                                                    <td>
                                                        <form class="form-eliminar" action="{{route('eliminar_director_administrador', ['id'=>$director->USR_RLS_Usuario_Id])}}"
                                                            class="d-inline" method="POST">
                                                            <a onclick="activar({{$director->USR_RLS_Usuario_Id}})" class="tooltipsC" title="Activar Director">
                                                                <i class="material-icons text-info" style="font-size: 18px;">arrow_upward</i>
                                                            </a>
                                                            @csrf @method("delete")
                                                            <!--<button type="submit" class="btn-accion-tabla eliminar tooltipsC"
                                                                data-type="confirm" title="Eliminar este Registro">
                                                                <i class="material-icons text-danger"
                                                                    style="font-size: 17px;">delete_forever</i>
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

            <div class="modal fade" id="modalFormDirector" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Director de Proyectos</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form_validation">
                                @csrf
                                @include('administrador.director.form')
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