@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Actividades
@endsection
@section('styles')
    <style>
        .card .bg-cyan{
            color: #000 !important; }
    </style>
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/pages/scripts/Director/porcentaje.js")}}" type="text/javascript"></script>

    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>

    <script>
        function listarA(id){
            window.location = '/actividades/' + id;
        }

        function delRequerimiento(id){
            event.preventDefault();
            swal({
                title: '¿Está seguro que desea eliminar la actividad?',
                text: 'Esta acción no se puede deshacer!',
                icon: 'warning',
                buttons: {
                    cancel: "Cancelar",
                    confirm: "Aceptar"
                },
            }).then((value) => {
                if (value) {
                    ajaxRequest(id);
                }
            });
        }

        jQuery(document).ready(function($){
            ////----- Abre modal para crear rol -----////
            jQuery('#add_requerimiento').click(function () {
                jQuery('#btn_guardar').val("add");
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormRequerimiento').modal('show');
            });

            ////----- Abre modal para editar rol -----////
            jQuery('body').on('click', '.open-modal', function () {
                $('.page-loader-wrapper').fadeIn();
                var requerimiento_id = $(this).val();
                $.get('/requerimientos/' + requerimiento_id + '/editar', function (data) {
                    jQuery('#requerimiento_id').val(data.requerimiento.id);
                    jQuery('#REQ_Nombre_Requerimiento').val(data.requerimiento.REQ_Nombre_Requerimiento);
                    jQuery('#REQ_Descripcion_Requerimiento').val(data.requerimiento.REQ_Descripcion_Requerimiento);
                    jQuery('#btn_guardar').val("update");
                    jQuery('#modalFormRequerimiento').modal('show');
                });
                $('.page-loader-wrapper').fadeOut();
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar").click(function (e) {
                $('.page-loader-wrapper').fadeIn();
                if($("#form_validation").valid()){
                    e.preventDefault();
                    var formData = {
                        "_token": "{{ csrf_token() }}",
                        REQ_Proyecto_Id: jQuery('#REQ_Proyecto_Id').val(),
                        REQ_Nombre_Requerimiento: jQuery('#REQ_Nombre_Requerimiento').val(),
                        REQ_Descripcion_Requerimiento: jQuery('#REQ_Descripcion_Requerimiento').val(),
                    };
                    var state = jQuery('#btn_guardar').val();
                    var type = "POST";
                    var requerimiento_id = jQuery('#requerimiento_id').val();
                    var ajaxurl = '/requerimientos/crear';
                    if (state == "update") {
                        type = "PUT";
                        ajaxurl = '/requerimientos/' + requerimiento_id;
                    }
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var rows = $('.dt-requerimientos>tbody>tr').length;
                                if(rows == 0){
                                    location.reload();
                                } else {
                                    var requerimiento = '<tr id="requerimiento'+ data.requerimiento.id +'"><td><a onclick="avanceR('+ data.requerimiento.id +')" class="btn-accion-tabla tooltipsC" title="Ver Progreso">'+ data.requerimiento.REQ_Nombre_Requerimiento +'</a><div id="progressBar'+ data.requerimiento.id +'" style="display: none;"></div></td><td>'+ data.requerimiento.REQ_Descripcion_Requerimiento +'</td><td>';
                                    if(data.permisos.editar == true){
                                        requerimiento += '<button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="'+ data.requerimiento.id +'"><i class="material-icons text-info" style="font-size: 17px;">edit</i></button>';
                                    }
                                    if(data.permisos.eliminar == true){
                                        requerimiento += '<a onclick="delRequerimiento('+ data.requerimiento.id +')"" class="btn-accion-tabla tooltipsC" title="Eliminar Proyecto"><i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i></a>';
                                    }
                                    if(data.permisos.listarA == true) {
                                        requerimiento += '<a onclick="listarA('+ data.requerimiento.id +')" class="btn-accion-tabla tooltipsC" title="Listar Tareas"><i class="material-icons text-info" style="font-size: 17px;">assignment</i></a>';
                                    }
                                    requerimiento += '</td></tr>';
                                    if (state == "add") {
                                        jQuery('#lista-requerimientos').append(requerimiento);
                                    } else {
                                        $("#requerimiento" + requerimiento_id).replaceWith(requerimiento);
                                    }
                                    jQuery('#form_validation').trigger("reset");
                                    jQuery('#modalFormRequerimiento').modal('hide');
                                }

                                InkBrutalPRY.notificaciones('Actividad '+(state == "add" ? 'registrada' : 'editada')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.mensaje == "dr"){
                                InkBrutalPRY.notificaciones('El proyecto ya cuenta con la actividad registrada', 'InkBrutalPRY', 'error');
                            } else if(data.errors != null){
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' el requerimiento.', 'InkBrutalPRY', 'warning');
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
                $('.page-loader-wrapper').fadeOut();
            });

            ////----- Elimina el rol y lo quita de la vista -----////
            jQuery('.delete-requerimiento').click(function () {
                event.preventDefault();
                const requerimiento_id = $(this).val();
                swal({
                    title: '¿Está seguro que desea eliminar la actividad?',
                    text: 'Esta acción no se puede deshacer!',
                    icon: 'warning',
                    buttons: {
                        cancel: "Cancelar",
                        confirm: "Aceptar"
                    },
                }).then((value) => {
                    if (value) {
                        ajaxRequest(requerimiento_id);
                    }
                });
            });
        });
        function ajaxRequest(requerimiento_id){
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                type: "DELETE",
                url: '/requerimientos/' + requerimiento_id,
                data: {"_token": "{{ csrf_token() }}"},
                success: function (data) {
                    if (data.mensaje == "ok") {
                        $("#requerimiento" + requerimiento_id).remove();
                        InkBrutalPRY.notificaciones('la actividad fue eliminada correctamente', 'InkBrutalPRY', 'success');
                    } else if (data.mensaje == "ng") {
                        InkBrutalPRY.notificaciones('No es posible eliminar la actividad, tiene tareas registradas.', 'InkBrutalPRY', 'error');
                    } else if (respuesta.mensaje == "np") {
                        InkBrutalPRY.notificaciones('No tiene permisos para entrar en este modulo.', 'InkBrutalPRY', 'error');
                    }  else {
                        InkBrutalPRY.notificaciones('La actividad no pudo ser eliminada o hay otro recurso usándola', 'InkBrutalPRY', 'error');
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
            $('.page-loader-wrapper').fadeOut();
        }
    </script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    <h2>
                        LISTA DE ACTIVIDADES - PROYECTO ({{strtoupper($proyecto->PRY_Nombre_Proyecto)}})
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear']==true)
                                <a id="add_requerimiento" name="add_requerimiento" class="btn btn-success waves-effect">
                                    <i class="material-icons" style="color:white;">add</i> Nueva Actividad
                                </a>
                            @endif
                            
                        </li>
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('proyectos', ['id'=>$proyecto->PRY_Empresa_Id])}}">
                                <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Proyectos
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($requerimientos)<=0)
                        <div class="alert alert-info">
                            No hay datos que mostrar.
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable dt-requerimientos" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    @if ($permisos['editar']==true || $permisos['eliminar']==true)
                                        <th class="width70"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="lista-requerimientos" name="lista-requerimientos">
                                @foreach ($requerimientos as $requerimiento)
                                    <tr id="requerimiento{{$requerimiento->id}}">
                                        <td>
                                            <a onclick="avanceR({{$requerimiento->id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                                {{$requerimiento->REQ_Nombre_Requerimiento}}
                                            </a>
                                            <div id="progressBar{{$requerimiento->id}}" style="display: none;"></div>
                                        </td>
                                        <td>{{$requerimiento->REQ_Descripcion_Requerimiento}}</td>
                                        <td>
                                            @if ($permisos['editar']==true)
                                                <button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="{{$requerimiento->id}}">
                                                    <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                </button>
                                            @endif
                                            @if ($permisos['eliminar']==true)
                                                <button class="btn-accion-tabla tooltipsC delete-requerimiento" value="{{$requerimiento->id}}" title="Eliminar este Registro">
                                                    <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                </button>
                                            @endif
                                            @if ($permisos['listarA']==true)
                                                <a href="{{route('actividades', ['idR'=>$requerimiento->id])}}" class="btn-accion-tabla tooltipsC" title="Listar Tareas">
                                                    <i class="material-icons text-info" style="font-size: 17px;">assignment</i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="modal fade" id="modalFormRequerimiento" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Actividad</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form_validation" method="POST">
                                @csrf
                                @include('requerimientos.form')
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