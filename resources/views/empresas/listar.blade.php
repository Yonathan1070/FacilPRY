@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Empresas
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>

    <script>
        function inactivar(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/empresa/" + id + "/inactivar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "okI") {
                    location.reload();
                    InkBrutalPRY.notificaciones('Empresa ha quedado inactiva', 'InkBrutalPRY', 'success');
                } else {
                    InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
                }
            });
        }

        function activar(id){
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/empresa/" + id + "/activar"
            }).done(function (respuesta) {
                if(respuesta.mensaje == "ok") {
                    location.reload();
                    InkBrutalPRY.notificaciones('La empresa ha sido activada', 'InkBrutalPRY', 'success');
                } else {
                    InkBrutalPRY.notificaciones('Error al realizar la operación', 'InkBrutalPRY', 'danger');
                }
            });
        }

        function clientes(id){
            window.location = '/clientes/' + id;
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
            jQuery('#add_empresa').click(function () {
                jQuery('#btn_guardar').val("add");
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormEmpresa').modal('show');
            });
        
            ////----- Abre modal para editar rol -----////
            jQuery('body').on('click', '.open-modal', function () {
                var empresa_id = $(this).val();
                $.get('empresas/' + empresa_id + '/editar', function (data) {
                    jQuery('#empresa_id').val(data.empresa.id);
                    jQuery('#EMP_NIT_Empresa').val(data.empresa.EMP_NIT_Empresa);
                    jQuery('#EMP_Nombre_Empresa').val(data.empresa.EMP_Nombre_Empresa);
                    jQuery('#EMP_Telefono_Empresa').val(data.empresa.EMP_Telefono_Empresa);
                    jQuery('#EMP_Correo_Empresa').val(data.empresa.EMP_Correo_Empresa);
                    jQuery('#EMP_Direccion_Empresa').val(data.empresa.EMP_Direccion_Empresa);
                    jQuery('#btn_guardar').val("update");
                    jQuery('#modalFormEmpresa').modal('show');
                })
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar").click(function (e) {
                if($("#form_validation").valid()){
                    e.preventDefault();
                    var formData = {
                        "_token": "{{ csrf_token() }}",
                        EMP_NIT_Empresa: jQuery('#EMP_NIT_Empresa').val(),
                        EMP_Nombre_Empresa: jQuery('#EMP_Nombre_Empresa').val(),
                        EMP_Telefono_Empresa: jQuery('#EMP_Telefono_Empresa').val(),
                        EMP_Correo_Empresa: jQuery('#EMP_Correo_Empresa').val(),
                        EMP_Direccion_Empresa: jQuery('#EMP_Direccion_Empresa').val(),
                        id: jQuery('#id').val()
                    };
                    var state = jQuery('#btn_guardar').val();
                    var type = "POST";
                    var empresa_id = jQuery('#empresa_id').val();
                    var ajaxurl = 'empresas/crear';
                    if (state == "update") {
                        type = "PUT";
                        ajaxurl = 'empresas/' + empresa_id;
                    }
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                if(data.empresa.EMP_Estado_Empresa == 1) {
                                    var empresa = '<tr id="empresa'+ data.empresa.id +'"><td>'+ data.empresa.EMP_NIT_Empresa +'</td><td>'+ data.empresa.EMP_Nombre_Empresa +'</td><td>'+ data.empresa.EMP_Direccion_Empresa +'</td><td>'+ data.empresa.EMP_Correo_Empresa +'</td><td class="width100">';
                                    if(data.permisos.editar == true){
                                        empresa += '<button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="'+ data.empresa.id +'"><i class="material-icons text-info" style="font-size: 17px;">edit</i></button>';
                                    }
                                    if(data.permisos.eliminar == true){
                                        empresa += '<a onclick="inactivar('+ data.empresa.id +')" class="tooltipsC" title="Inactivar la empresa '+ data.empresa.EMP_Nombre_Empresa +'"><i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i></a>';
                                    }
                                    if(data.permisos.lUsuarios == true){
                                        empresa += '<a onclick="clientes('+ data.empresa.id +')" class="btn-accion-tabla tooltipsC" title="Lista de Usuarios"><i class="material-icons text-info" style="font-size: 20px;">list</i></a>';
                                    }
                                    empresa += '</td></tr>';
                                    if (state == "add") {
                                        jQuery('#lista-empresas').append(empresa);
                                    } else {
                                        $("#empresa" + empresa_id).replaceWith(empresa);
                                    }
                                } else {
                                    var empresa = '<tr id="empresa'+ data.empresa.id +'"><td>'+ data.empresa.EMP_NIT_Empresa +'</td><td>'+ data.empresa.EMP_Nombre_Empresa +'</td><td>'+ data.empresa.EMP_Direccion_Empresa +'</td><td>'+ data.empresa.EMP_Correo_Empresa +'</td><td class="width100">';
                                    if(data.permisos.editar == true){
                                        empresa += '<button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="'+ data.empresa.id +'"><i class="material-icons text-info" style="font-size: 17px;">edit</i></button>';
                                    }
                                    if(data.permisos.eliminar == true){
                                        empresa += '<a onclick="activar('+ data.empresa.id +')" class="tooltipsC" title="Activar la empresa '+ data.empresa.EMP_Nombre_Empresa +'"><i class="material-icons text-success" style="font-size: 20px;">arrow_upward</i></a>';
                                    }
                                    empresa += '</td></tr>';
                                    $("#empresa" + empresa_id).replaceWith(empresa);
                                }
                                jQuery('#form_validation').trigger("reset");
                                jQuery('#modalFormEmpresa').modal('hide');

                                InkBrutalPRY.notificaciones('Empresa '+(state == "add" ? 'registrada' : 'editada')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.errors != null) {
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' la empresa.', 'InkBrutalPRY', 'warning');
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
@section('contenido')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('includes.form-exito')
                @include('includes.form-error')
                <div class="card">
                    <div class="header">
                        <h2>LISTA DE EMPRESAS</h2>
                        <ul class="header-dropdown" style="top:10px;">
                            <li class="dropdown">
                                @if ($permisos['crear']==true)
                                    <a id="add_empresa" name="add_empresa" class="btn btn-success waves-effect">
                                        <i class="material-icons" style="color:white;">add</i> Nueva Empresa
                                    </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="body table-responsive">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#activas" aria-controls="settings" role="tab" data-toggle="tab">Activas</a></li>
                                <li role="presentation"><a href="#inactivas" aria-controls="settings" role="tab" data-toggle="tab">Inactivas</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="activas">
                                    @if (count($empresasActivas)<=0)
                                        <div class="alert alert-info">
                                            No hay Datos que mostrar.
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
                                            <tbody id="lista-empresas" name="lista-empresas">
                                                @foreach ($empresasActivas as $empresa)
                                                    <tr id="empresa{{$empresa->id}}">
                                                        <td>{{$empresa->EMP_NIT_Empresa}}</td>
                                                        <td>{{$empresa->EMP_Nombre_Empresa}}</td>
                                                        <td>{{$empresa->EMP_Direccion_Empresa}}</td>
                                                        <td>{{$empresa->EMP_Correo_Empresa}}</td>
                                                        <td class="width100">
                                                            @if ($permisos['editar']==true || $permisos['eliminar']==true || $permisos['lUsuarios']==true || $permisos['lProyectos']==true)
                                                                @if ($permisos['editar']==true)
                                                                    <button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="{{$empresa->id}}">
                                                                        <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                                    </button>
                                                                @endif    
                                                                @if ($permisos['eliminar']==true)
                                                                    <a onclick="inactivar({{$empresa->id}})" class="tooltipsC" title="Inactivar la empresa {{$empresa->EMP_Nombre_Empresa}}">
                                                                        <i class="material-icons text-danger" style="font-size: 18px;">arrow_downward</i>
                                                                    </a>
                                                                @endif
                                                                @if ($permisos['lUsuarios']==true)
                                                                    <a href="{{route('clientes', ['id'=>$empresa->id])}}" class="btn-accion-tabla tooltipsC" title="Lista de Usuarios">
                                                                        <i class="material-icons text-info" style="font-size: 20px;">list</i>
                                                                    </a>
                                                                @endif
                                                                @if ($empresa->clientes != 0)
                                                                    @if ($permisos['lProyectos']==true)
                                                                        <a href="{{route('proyectos', ['id'=>$empresa->id])}}" class="btn-accion-tabla tooltipsC" title="Lista de Proyectos">
                                                                            <i class="material-icons text-info" style="font-size: 20px;">notes</i>
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="inactivas">
                                    @if (count($empresasInActivas)<=0)
                                        <div class="alert alert-info">
                                            No hay Datos que mostrar
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
                                            <tbody id="lista-empresas-inactivas" name="lista-empresas-inactivas">
                                                @foreach ($empresasInActivas as $empresa)
                                                    <tr id="empresa{{$empresa->id}}">
                                                        <td>{{$empresa->EMP_NIT_Empresa}}</td>
                                                        <td>{{$empresa->EMP_Nombre_Empresa}}</td>
                                                        <td>{{$empresa->EMP_Direccion_Empresa}}</td>
                                                        <td>{{$empresa->EMP_Correo_Empresa}}</td>
                                                        <td class="width100">
                                                            @if ($permisos['editar']==true || $permisos['eliminar']==true || $permisos['lUsuarios']==true || $permisos['lProyectos']==true)
                                                                @if ($permisos['editar']==true)
                                                                    <button class="btn-accion-tabla tooltipsC open-modal" title="Editar este Registro" value="{{$empresa->id}}">
                                                                        <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                                    </button>
                                                                @endif    
                                                                @if ($permisos['eliminar']==true)
                                                                    <a onclick="activar({{$empresa->id}})" class="tooltipsC" title="Activar la empresa {{$empresa->EMP_Nombre_Empresa}}">
                                                                        <i class="material-icons text-success" style="font-size: 20px;">arrow_upward</i>
                                                                    </a>
                                                                @endif
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

                <div class="modal fade" id="modalFormEmpresa" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel">Empresa</h4>
                            </div>
                            <div class="modal-body">
                                <form id="form_validation" method="POST">
                                    @csrf
                                    @include('empresas.form')
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