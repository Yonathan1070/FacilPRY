@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Proyectos
@endsection
@section('styles')
    <style>
        .card .bg-cyan{
            color: #000 !important;
        }
    </style>
    <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
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
        function listarR(id){
            window.location = '/requerimientos/' + id;
        }

        function delProyecto(id){
            event.preventDefault();
            swal({
                title: '¿Está seguro que desea eliminar el proyecto?',
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
            jQuery('#add_proyecto').click(function () {
                jQuery('#btn_guardar').val("add");
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormProyecto').modal('show');
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar").click(function (e) {
                $('.page-loader-wrapper').fadeIn();
                if($("#form_validation").valid()){
                    e.preventDefault();
                    var formData = {
                        "_token": "{{ csrf_token() }}",
                        PRY_Empresa_Id: jQuery('#PRY_Empresa_Id').val(),
                        PRY_Nombre_Proyecto: jQuery('#PRY_Nombre_Proyecto').val(),
                        PRY_Descripcion_Proyecto: jQuery('#PRY_Descripcion_Proyecto').val(),
                        PRY_Cliente_Id: jQuery('#PRY_Cliente_Id').val(),
                    };
                    var state = jQuery('#btn_guardar').val();
                    var type = "POST";
                    var ajaxurl = '/proyectos/crear';

                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var rows = $('.dt-proyectos-activos>tbody>tr').length;
                                if(rows == 0){
                                    location.reload();
                                } else {
                                    var proyecto = '<tr id="proyecto'+ data.proyecto.Proyecto_Id +'"><td><a onclick="avance('+ data.proyecto.Proyecto_Id +')" class="btn-accion-tabla tooltipsC" title="Ver Progreso">'+ data.proyecto.PRY_Nombre_Proyecto +'</a><div id="progressBar'+ data.proyecto.Proyecto_Id +'" style="display: none;"></div></td><td>'+ data.proyecto.PRY_Descripcion_Proyecto +'</td><td>'+ data.proyecto.USR_Nombres_Usuario +' '+ data.proyecto.USR_Apellidos_Usuario +'</td><td>'+ data.proyecto.Actividades_Finalizadas +' / '+ data.proyecto.Actividades_Totales +'</td><td class="grid-width-100">';
                                    if(data.permisos.eliminar == true && data.proyecto.Actividades_Totales == 0){
                                        proyecto += '<a onclick="delProyecto('+ data.proyecto.Proyecto_Id +')"" class="btn-accion-tabla tooltipsC" title="Eliminar Proyecto"><i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i></a>';
                                    }
                                    if(data.permisos.listarR == true) {
                                        proyecto += '<a onclick="listarR('+ data.proyecto.Proyecto_Id +')" class="btn-accion-tabla tooltipsC" title="Listar Actividades"><i class="material-icons text-info" style="font-size: 17px;">description</i></a>';
                                    }
                                    proyecto += '</td></tr>';
                                    if (state == "add") {
                                        jQuery('#lista-proyectos').append(proyecto);
                                    }
                                    jQuery('#form_validation').trigger("reset");
                                    jQuery('#modalFormProyecto').modal('hide');
                                }

                                InkBrutalPRY.notificaciones('Proyecto '+(state == "add" ? 'registrado' : 'editado')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.errors != null){
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' el proyecto.', 'InkBrutalPRY', 'warning');
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
            jQuery('.delete-proyecto').click(function () {
                event.preventDefault();
                const proyecto_id = $(this).val();
                swal({
                    title: '¿Está seguro que desea eliminar el proyecto?',
                    text: 'Esta acción no se puede deshacer!',
                    icon: 'warning',
                    buttons: {
                        cancel: "Cancelar",
                        confirm: "Aceptar"
                    },
                }).then((value) => {
                    if (value) {
                        ajaxRequest(proyecto_id);
                    }
                });
            });

            function ajaxRequest(proyecto_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    type: "DELETE",
                    url: '/proyectos/' + proyecto_id + '/eliminar',
                    data: {"_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        if (data.mensaje == "ok") {
                            $("#proyecto" + proyecto_id).remove();
                            InkBrutalPRY.notificaciones('El proyecto fue eliminado correctamente', 'InkBrutalPRY', 'success');
                        } else if (data.mensaje == "ng") {
                            InkBrutalPRY.notificaciones('No es posible eliminar el proyecto, tiene actividades registradas.', 'InkBrutalPRY', 'error');
                        } else if (respuesta.mensaje == "np") {
                            InkBrutalPRY.notificaciones('No tiene permisos para entrar en este modulo.', 'InkBrutalPRY', 'error');
                        }  else {
                            InkBrutalPRY.notificaciones('El proyecto no pudo ser eliminado o hay otro recurso usándolo', 'InkBrutalPRY', 'error');
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
                        LISTA DE PROYECTOS PARA {{strtoupper($empresa->EMP_Nombre_Empresa)}}
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear']==true)
                                <a id="add_proyecto" name="add_proyecto" class="btn btn-success waves-effect">
                                    <i class="material-icons" style="color:white;">add</i> Nuevo Proyecto
                                </a>
                            @endif
                            @if ($permisos['listarE']==true)
                                <a class="btn btn-danger waves-effect" href="{{route('empresas')}}">
                                    <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Empresas
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#no-finalizado" aria-controls="settings" role="tab" data-toggle="tab">No Finalizados</a></li>
                            <li role="presentation"><a href="#finalizado" aria-controls="settings" role="tab" data-toggle="tab">Finalizados</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="no-finalizado">
                                @if (count($proyectosNoFinalizados)<=0)
                                    <div class="alert alert-info">
                                        No hay datos que mostrar.
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable dt-proyectos-activos" id="tabla-data">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Cliente</th>
                                                <th>Tareas (Finalizadas/Totales)</th>
                                                <th class="grid-width-100"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista-proyectos" name="lista-proyectos">
                                            @foreach ($proyectosNoFinalizados as $proyecto)
                                                <tr id="proyecto{{$proyecto->Proyecto_Id}}">
                                                    <td>
                                                        <a onclick="avance({{$proyecto->Proyecto_Id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                                            {{$proyecto->PRY_Nombre_Proyecto}}
                                                        </a>
                                                        <div id="progressBar{{$proyecto->Proyecto_Id}}" style="display: none;"></div>
                                                        </td>
                                                    <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                                    <td>{{$proyecto->USR_Nombres_Usuario.' '.$proyecto->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$proyecto->Actividades_Finalizadas}} / {{$proyecto->Actividades_Totales}}</td>
                                                    <td class="grid-width-100">
                                                        @if ($permisos['eliminar'] == true && $proyecto->Actividades_Totales == 0)
                                                            <button class="btn-accion-tabla tooltipsC delete-proyecto" value="{{$proyecto->Proyecto_Id}}" title="Eliminar Proyecto">
                                                                <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                            </button>
                                                        @endif
                                                        @if ($proyecto->Actividades_Totales != 0)
                                                            @if ($permisos['listarA']==true)
                                                                <a href="{{route('actividades_todas', ['idP'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Listar todas las tareas">
                                                                    <i class="material-icons text-info" style="font-size: 17px;">line_weight</i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                        @if ($permisos['listarR']==true)
                                                            <a href="{{route('requerimientos', ['idP'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Listar Actividades">
                                                                <i class="material-icons text-info" style="font-size: 17px;">description</i>
                                                            </a>
                                                        @endif
                                                        @if ($proyecto->Actividades_Totales != 0)
                                                            <a href="{{route('generar_pdf_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Tareas">
                                                                <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
                                                            </a>
                                                            <a href="{{route('gantt', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Ver Cronograma de Tareas">
                                                                <i class="material-icons text-info" style="font-size: 20px;">view_quilt</i>
                                                            </a>
                                                        @endif
                                                        @if ($proyecto->Actividades_Totales != 0 && ($proyecto->Actividades_Finalizadas == $proyecto->Actividades_Totales))
                                                            <a href="{{route('finalizar_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Fianlizar Proyecto">
                                                                <i class="material-icons text-success" style="font-size: 20px;">navigate_next</i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div role="tabpanel" class="tab-pane fade in" id="finalizado">
                                @if (count($proyectosFinalizados)<=0)
                                    <div class="alert alert-info">
                                        No hay proyectos finalizados.
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable dt-proyectos-finalizados" id="tabla-data">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Cliente</th>
                                                <th>Tareas (Finalizadas/Totales)</th>
                                                <th class="width70"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($proyectosFinalizados as $proyecto)
                                                <tr>
                                                    <td>
                                                        <a onclick="avance({{$proyecto->Proyecto_Id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                                            {{$proyecto->PRY_Nombre_Proyecto}}
                                                        </a>
                                                        <div id="progressBar{{$proyecto->Proyecto_Id}}" style="display: none;"></div>
                                                        </td>
                                                    <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                                    <td>{{$proyecto->USR_Nombres_Usuario.' '.$proyecto->USR_Apellidos_Usuario}}</td>
                                                    <td>{{$proyecto->Actividades_Finalizadas}} / {{$proyecto->Actividades_Totales}}</td>
                                                    <td>
                                                        <a href="{{route('generar_pdf_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Tareas">
                                                            <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
                                                        </a>
                                                        <a href="{{route('activar_proyecto', ['id'=>$proyecto->Proyecto_Id])}}" class="btn-accion-tabla tooltipsC" title="Activar proyecto">
                                                            <i class="material-icons text-info" style="font-size: 17px;">navigate_before</i>
                                                        </a>
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

            <div class="modal fade" id="modalFormProyecto" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Proyecto</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form_validation" method="POST">
                                @csrf
                                @include('proyectos.form')
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