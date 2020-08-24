@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Tareas
@endsection
@section('styles')
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{asset('assets/bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="{{asset('assets/bsb/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css')}}" rel="stylesheet" />
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
    <!-- Colorful Panel Items With Icon -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @include('includes.form-exito')
        @include('includes.form-error')
        <div class="card">
            <div class="header">
                <h2>
                    LISTA DE TAREAS - ACTIVIDAD ({{strtoupper($requerimiento->REQ_Nombre_Requerimiento)}})
                </h2>
                <ul class="header-dropdown" style="top:10px;">
                    <li class="dropdown">
                        @if ($permisos['crearC']==true)
                            <a id="add_cliente" name="add_cliente" class="btn btn-success waves-effect">
                                <i class="material-icons" style="color:white;">add</i> Nueva Tarea Cliente
                            </a>
                        @endif
                        @if ($permisos['crear'] == true)
                            <a id="add_trabajador" name="add_trabajador" class="btn btn-success waves-effect">
                                <i class="material-icons" style="color:white;">add</i> Nueva Tarea Trabajador
                            </a>
                        @endif
                        @if ($permisos['listarP'] == true)
                        <a class="btn btn-danger waves-effect" href="{{route('requerimientos', ['idP'=>$proyecto->id])}}">
                            <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a Actividades
                        </a>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-col-pink">
                                <div class="panel-heading" role="tab" id="headingOne_17">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="false" aria-controls="collapseOne_17">
                                                <i class="material-icons">contact_mail</i> Trabajadores
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17">
                                    <div class="panel-body table-responsive">
                                        @if (count($actividades)<=0)
                                            <div class="alert alert-info">
                                                No hay datos que mostrar.
                                            </div>
                                        @else
                                            <table class="table table-striped table-bordered table-hover  dataTable js-exportable dt-trabajador" id="tabla-data">
                                                <thead>
                                                    <tr>
                                                        <th>Tarea</th>
                                                        <th>Actividad</th>
                                                        <th>Encargado</th>
                                                        <th>Fecha de Entrega</th>
                                                        <th>Estado</th>
                                                        <th class="width70"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lista-actividades-trabajador" name="lista-actividades-trabajador">
                                                    @foreach ($actividades as $actividad)
                                                        @if ($actividad->estado_id == 1 || $actividad->estado_id == 2)
                                                            <tr id="actividad{{$actividad->ID_Actividad}}">
                                                                <td>
                                                                    <a id="{{$actividad->ID_Actividad}}" onclick="detalle(this)" class="btn-accion-tabla tooltipsC"
                                                                        title="Ver Detalles" data-toggle="modal" data-target="#defaultModal">
                                                                        {{$actividad->ACT_Nombre_Actividad}}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                        <select name="ACT_Requerimiento" id="ACT_Requerimiento" class="form-control show-tick" data-live-search="true"
                                                                            required onchange="cambioActividad(this, {{$actividad->ID_Actividad}})">
                                                                            <option value="">-- Seleccione una Actividad --</option>
                                                                            @foreach ($requerimientos as $requerimiento)
                                                                                <option value="{{$requerimiento->id}}" {{old("ACT_Requerimiento", $actividad->ID_Requerimiento) == $requerimiento->id ? 'selected' : '' }}>
                                                                                    {{$requerimiento->REQ_Nombre_Requerimiento}}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    @else
                                                                        {{$actividad->REQ_Nombre_Requerimiento}}
                                                                    @endif
                                                                </td>
                                                                <td>{{$actividad->USR_Nombres_Usuario.' '.$actividad->USR_Apellidos_Usuario}}</td>
                                                                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y h:i A.')}}</td>
                                                                <td>{{$actividad->EST_Nombre_Estado}}</td>
                                                                <td>
                                                                    @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                        @if ($permisos["editar"] == true)
                                                                            <button class="btn-accion-tabla tooltipsC open-modal-trabajador" title="Editar esta tarea" value="{{$actividad->ID_Actividad}}">
                                                                                <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                                            </button>
                                                                        @endif
                                                                        @if ($permisos["eliminar"] == true)
                                                                            <button class="btn-accion-tabla tooltipsC delete-actividad" value="{{$actividad->ID_Actividad}}" title="Eliminar esta tarea">
                                                                                <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                                            </button>
                                                                        @endif
                                                                        @if ($actividad->HorasE != 0)
                                                                            <a href="{{route('aprobar_horas_actividad', ['idH'=>$actividad->ID_Actividad])}}"
                                                                                class="btn-accion-tabla tooltipsC" title="Aprobar horas de trabajo">
                                                                                <i class="material-icons text-success" style="font-size: 17px;">alarm_on</i>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-col-cyan">
                                <div class="panel-heading" role="tab" id="headingTwo_17">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseTwo_17" aria-expanded="false" aria-controls="collapseTwo_17">
                                            <i class="material-icons">contacts</i> Clientes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo_17" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_17">
                                    <div class="panel-body table-responsive">
                                        @if (count($actividadesCliente)<=0)
                                            <div class="alert alert-info">
                                                No hay datos que mostrar.
                                            </div>
                                        @else
                                            <table class="table table-striped table-bordered table-hover  dataTable js-exportable dt-cliente" id="tabla-data">
                                                <thead>
                                                    <tr>
                                                        <th>Tarea</th>
                                                        <th>Actividad</th>
                                                        <th>Cliente</th>
                                                        <th>Fecha de Entrega</th>
                                                        <th>Estado</th>
                                                        <th class="width70"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lista-actividades-cliente" name="lista-actividades-cliente">
                                                    @foreach ($actividadesCliente as $actividad)
                                                        <tr id="actividad{{$actividad->ID_Actividad}}">
                                                            <td>
                                                                <a id="{{$actividad->ID_Actividad}}" onclick="detalle(this)" class="btn-accion-tabla tooltipsC"
                                                                    title="Ver Detalles" data-toggle="modal" data-target="#defaultModal">
                                                                    {{$actividad->ACT_Nombre_Actividad}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                    <select name="ACT_Requerimiento" id="ACT_Requerimiento" class="form-control show-tick" data-live-search="true"
                                                                        required onchange="cambioActividad(this, {{$actividad->ID_Actividad}})">
                                                                        <option value="">-- Seleccione una Actividad --</option>
                                                                        @foreach ($requerimientos as $requerimiento)
                                                                            <option value="{{$requerimiento->id}}" {{old("ACT_Requerimiento", $actividad->ID_Requerimiento) == $requerimiento->id ? 'selected' : '' }}>
                                                                                {{$requerimiento->REQ_Nombre_Requerimiento}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    {{$actividad->REQ_Nombre_Requerimiento}}
                                                                @endif
                                                            </td>
                                                            <td>{{$actividad->USR_Nombres_Usuario.' '.$actividad->USR_Apellidos_Usuario}}</td>
                                                            <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y H:i A.')}}</td>
                                                            <td>{{$actividad->EST_Nombre_Estado}}</td>
                                                            <td>
                                                                @if ($actividad->EST_Nombre_Estado == 'En Proceso')
                                                                    @if ($permisos["editar"] == true)
                                                                        <button class="btn-accion-tabla tooltipsC open-modal-cliente" title="Editar esta tarea" value="{{$actividad->ID_Actividad}}">
                                                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                                        </button>
                                                                    @endif
                                                                    @if ($permisos["eliminar"] == true)
                                                                        <button class="btn-accion-tabla tooltipsC delete-actividad" value="{{$actividad->ID_Actividad}}" title="Eliminar esta tarea">
                                                                            <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                                        </button>
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
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Detalles de la actividad</h4>
                </div>
                <div class="modal-body">
                    <form class="form-eliminar" action="{{route('detalle_general_actividad')}}"
                        class="d-inline" method="POST">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="nombreActividad">Tarea</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="nombreActividad" class="form-control" readonly="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="descripcionActividad">Descripción</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea name="" id="descripcionActividad" readonly="true" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="fechaInicioActividad">Inicio</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="fechaInicioActividad" class="form-control" readonly="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="fechaFinActividad">Fin</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="fechaFinActividad" class="form-control" readonly="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="tiempo">Estado</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="estadoActividad" class="form-control" readonly="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="idActividad" id="idActividad" class="form-control" readonly="true">
                        <input type="hidden" id="nombreRequerimiento" class="form-control" readonly="true">
                        <div class="modal-footer">
                            <button id="verMas" type="submit" class="btn btn-link waves-effect">VER MÁS DETALLES</button>
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="ACT_Proyecto_Id" id="ACT_Proyecto_Id" value="{{$proyecto->id}}">
    <input type="hidden" name="ACT_Requerimiento_Id" id="ACT_Requerimiento_Id" value="{{$requerimiento->id}}">
    <input type="hidden" id="actividad_id" name="actividad_id" value="0">
    <div class="modal fade" id="modalFormCliente" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Actividad</h4>
                </div>
                <div class="modal-body">
                    <form id="form_validation" method="POST">
                        @csrf
                        @include('actividades.form-cliente')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CANCELAR</a>
                    <button type="button" id="btn_guardar_cliente" class="btn btn-primary waves-effect" value="save">GUARDAR</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFormTrabajador" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Actividad</h4>
                </div>
                <div class="modal-body">
                    <form id="form_validation_trabajador" method="POST">
                        @csrf
                        @include('actividades.form')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CANCELAR</a>
                    <button type="button" id="btn_guardar_trabajador" class="btn btn-primary waves-effect" value="save">GUARDAR</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
    <script src="{{asset("assets/pages/scripts/PerfilOperacion/verDetalle.js")}}"></script>
    <script>
        function cambioActividad(idR, idA) {
            $.ajax({
                dataType: "json",
                method: "put",
                url: "/actividades/" + idA +"/cambiar-requerimiento",
                data: {"_token":"{{ csrf_token() }}", ACT_Requerimiento:idR.value},
                success:function(respuesta){
                    if(respuesta.mensaje == "ok"){
                        InkBrutalPRY.notificaciones('Tarea cambiada de requerimiento', 'InkBrutalPRY', 'success');
                        location.reload();
                    }
                    if(respuesta.mensaje == "ng")
                        InkBrutalPRY.notificaciones('Operación ha fallado', 'InkBrutalPRY', 'error');
                }
            });
        }
    </script>

    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js")}}"></script>

    <!-- Select Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js")}}"></script>
    <!-- Input Mask Plugin Js -->
    <script src="{{asset("assets/bsb/plugins/jquery-inputmask/jquery.inputmask.bundle.js")}}"></script>
    <script src="{{asset("assets/bsb/plugins/bootstrap-select/js/i18n/defaults-es_CL.js")}}"></script>
    <script src="{{asset("assets/bsb/js/pages/forms/basic-form-elements.js")}}"></script>

    <script>

        function delActividad(id){
            event.preventDefault();
            swal({
                title: '¿Está seguro que desea eliminar la tarea?',
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
            jQuery('#add_cliente').click(function () {
                jQuery('#btn_guardar_cliente').val("add");
                jQuery('#form_validation').trigger("reset");
                jQuery('#modalFormCliente').modal('show');
            });

            jQuery('#add_trabajador').click(function () {
                jQuery('#btn_guardar_trabajador').val("add");
                jQuery('#form_validation_trabajador').trigger("reset");
                jQuery('#modalFormTrabajador').modal('show');
            });

            ////----- Abre modal para editar rol -----////
            jQuery('body').on('click', '.open-modal-cliente', function () {
                $('.page-loader-wrapper').fadeIn();
                var actividad_id = $(this).val();
                $.get('/actividades/' + actividad_id + '/editar', function (data) {
                    jQuery('#actividad_id').val(data.actividad.id);
                    jQuery('#ACT_Nombre_Actividad').val(data.actividad.ACT_Nombre_Actividad);
                    jQuery('#ACT_Descripcion_Actividad').val(data.actividad.ACT_Descripcion_Actividad);
                    var FechaInicio = data.actividad.ACT_Fecha_Inicio_Actividad;
                    var FechaSplit1 = FechaInicio.split(" ");
                    jQuery('#ACT_Fecha_Inicio_Actividad').val(FechaSplit1[0]);
                    var FechaFin = data.actividad.ACT_Fecha_Fin_Actividad;
                    var FechaSplit = FechaFin.split(" ");
                    jQuery('#ACT_Fecha_Fin_Actividad').val(FechaSplit[0]);
                    jQuery('#ACT_Hora_Entrega').val(FechaSplit[1]);
                    jQuery('#btn_guardar_cliente').val("update");
                    jQuery('#modalFormCliente').modal('show');
                });
                $('.page-loader-wrapper').fadeOut();
            });

            jQuery('body').on('click', '.open-modal-trabajador', function () {
                $('.page-loader-wrapper').fadeIn();
                var actividad_id = $(this).val();
                $.get('/actividades/' + actividad_id + '/editar', function (data) {
                    jQuery('#actividad_id').val(data.actividad.id);
                    jQuery('#ACT_Nombre_Actividad_Trabajador').val(data.actividad.ACT_Nombre_Actividad);
                    jQuery('#ACT_Descripcion_Actividad_Trabajador').val(data.actividad.ACT_Descripcion_Actividad);
                    var FechaInicio = data.actividad.ACT_Fecha_Inicio_Actividad;
                    var FechaSplit1 = FechaInicio.split(" ");
                    jQuery('#ACT_Fecha_Inicio_Actividad_Trabajador').val(FechaSplit1[0]);
                    var FechaFin = data.actividad.ACT_Fecha_Fin_Actividad;
                    var FechaSplit = FechaFin.split(" ");
                    jQuery('#ACT_Fecha_Fin_Actividad_Trabajador').val(FechaSplit[0]);
                    jQuery('#ACT_Hora_Entrega_Trabajador').val(FechaSplit[1]);
                    jQuery('#ACT_Usuario_Id_Trabajador').val(data.actividad.ACT_Trabajador_Id);
                    jQuery('#btn_guardar_cliente').val("update");
                    jQuery('#modalFormTrabajador').modal('show');
                });
                $('.page-loader-wrapper').fadeOut();
            });
        
            // Clic para crear o guardar el rol
            $("#btn_guardar_cliente").click(function (e) {
                $('.page-loader-wrapper').fadeIn();
                if($("#form_validation").valid()){
                    e.preventDefault();
                    var formData = {
                        "_token": "{{ csrf_token() }}",
                        ACT_Nombre_Actividad: jQuery('#ACT_Nombre_Actividad').val(),
                        ACT_Descripcion_Actividad: jQuery('#ACT_Descripcion_Actividad').val(),
                        ACT_Fecha_Inicio_Actividad: jQuery('#ACT_Fecha_Inicio_Actividad').val(),
                        ACT_Fecha_Fin_Actividad: jQuery('#ACT_Fecha_Fin_Actividad').val(),
                        ACT_Hora_Entrega: jQuery('#ACT_Hora_Entrega').val(),
                        ACT_Proyecto_Id: jQuery('#ACT_Proyecto_Id').val(),
                        ACT_Requerimiento_Id: jQuery('#ACT_Requerimiento_Id').val(),
                    };
                    var state = jQuery('#btn_guardar_cliente').val();
                    var type = "POST";
                    var actividad_id = jQuery('#actividad_id').val();
                    var ajaxurl = '/actividades/crear';
                    if (state == "update") {
                        type = "PUT";
                        ajaxurl = '/actividades/' + actividad_id;
                    }
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var rows = $('.dt-cliente>tbody>tr').length;
                                if(rows == 0){
                                    location.reload();
                                } else {
                                    var actividad = '<tr id="actividad'+ data.actividad.ID_Actividad +'"><td><a id="'+ data.actividad.ID_Actividad +'" onclick="detalle(this)" class="btn-accion-tabla tooltipsC"title="Ver Detalles" data-toggle="modal" data-target="#defaultModal">'+ data.actividad.ACT_Nombre_Actividad +'</a></td><td>';
                                    if(data.actividad.EST_Nombre_Estado == 'En Proceso'){
                                        actividad += '<select name="ACT_Requerimiento" id="ACT_Requerimiento" class="form-control show-tick" data-live-search="true"required onchange="cambioActividad(this, '+ data.actividad.ID_Actividad +')"><option value="">-- Seleccione una Actividad --</option>';
                                        data.requerimientos.forEach(function(requerimiento){
                                            actividad += '<option value="'+ requerimiento.id +'" '
                                            if (data.actividad.ID_Requerimiento == requerimiento.id) { actividad += 'selected' }
                                            actividad += '>'+ requerimiento.REQ_Nombre_Requerimiento +'</option>'
                                        });
                                        actividad += '</select>';
                                    } else {
                                        actividad += data.actividad.REQ_Nombre_Requerimiento;
                                    }
                                    actividad += '</td><td>'+ data.actividad.USR_Nombres_Usuario +' '+ data.actividad.USR_Apellidos_Usuario +'</td><td>'+ moment(data.actividad.ACT_Fecha_Fin_Actividad).format('DD/MM/YYYY HH:mm A') +'</td><td>'+ data.actividad.EST_Nombre_Estado +'</td><td>';
                                    if(data.actividad.EST_Nombre_Estado == 'En Proceso'){
                                        if(data.permisos.editar == true){
                                            actividad += '<button class="btn-accion-tabla tooltipsC open-modal-cliente" title="Editar esta tarea" value="'+ data.actividad.ID_Actividad +'"><i class="material-icons text-info" style="font-size: 17px;">edit</i></button>';
                                        }
                                        if(data.permisos.eliminar == true){
                                            actividad += '<a onclick="delActividad('+ data.actividad.ID_Actividad +')" class="btn-accion-tabla tooltipsC" title="Eliminar esta tarea"><i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i></a>';
                                        }
                                    }
                                    actividad += '</td></tr>';
                                    if (state == "add") {
                                        jQuery('#lista-actividades-cliente').append(actividad);
                                    } else {
                                        $("#actividad" + actividad_id).replaceWith(actividad);
                                    }

                                    jQuery('#form_validation').trigger("reset");
                                    jQuery('#modalFormCliente').modal('hide');
                                }

                                InkBrutalPRY.notificaciones('Actividad '+(state == "add" ? 'registrada' : 'editada')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.mensaje == "fp"){
                                InkBrutalPRY.notificaciones('Las fechas no pueden ser días ya pasados', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "fs"){
                                InkBrutalPRY.notificaciones('La fecha de inicio no puede ser superior a la fecha de finalización', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "hm"){
                                InkBrutalPRY.notificaciones('La hora de entrega debe ser mínimo de 1 hora y máximo de 10 horas', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "dr"){
                                InkBrutalPRY.notificaciones('La actividad ya cuenta con una tarea del mismo nombre', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "np"){
                                InkBrutalPRY.notificaciones('No tiene permisos para entrar en este módulo', 'InkBrutalPRY', 'error');
                            } else if(data.errors != null){
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' la tarea.', 'InkBrutalPRY', 'warning');
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
                $('.page-loader-wrapper').fadeOut();
            });

            // Clic para crear o guardar el rol
            $("#btn_guardar_trabajador").click(function (e) {
                $('.page-loader-wrapper').fadeIn();
                if($("#form_validation_trabajador").valid()){
                    e.preventDefault();
                    var formData = new FormData();

                    var files =$('input[type=file]')[0].files;

                    for(var i=0;i<files.length;i++){
                        formData.append("ACT_Documento_Soporte_Actividad[]", files[i], files[i]['name']);

                    }

                    formData.append("_token", "{{ csrf_token() }}");
                    formData.append("ACT_Nombre_Actividad", $('#ACT_Nombre_Actividad_Trabajador').val());
                    formData.append("ACT_Descripcion_Actividad", $('#ACT_Descripcion_Actividad_Trabajador').val());
                    formData.append("ACT_Fecha_Inicio_Actividad", $('#ACT_Fecha_Inicio_Actividad_Trabajador').val());
                    formData.append("ACT_Fecha_Fin_Actividad", $('#ACT_Fecha_Fin_Actividad_Trabajador').val());
                    formData.append("ACT_Hora_Entrega", $('#ACT_Hora_Entrega_Trabajador').val());
                    formData.append("ACT_Usuario_Id", $('#ACT_Usuario_Id_Trabajador').val());
                    formData.append("ACT_Proyecto_Id", $('#ACT_Proyecto_Id').val());
                    formData.append("ACT_Requerimiento_Id", $('#ACT_Requerimiento_Id').val());

                    var state = jQuery('#btn_guardar_trabajador').val();
                    var method = "POST";
                    var actividad_id = jQuery('#actividad_id').val();
                    var ajaxurl = '/actividades/crear';
                    if (state == "update") {
                        method = "PUT";
                        ajaxurl = '/actividades/' + actividad_id;
                    }
                    jQuery.ajax({
                        url: ajaxurl,
                        method: method,
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if(data.mensaje == "ok"){
                                var rows = $('.dt-trabajador>tbody>tr').length;
                                if(rows == 0){
                                    location.reload();
                                } else {
                                    var actividad = '<tr id="actividad'+ data.actividad.ID_Actividad +'"><td><a id="'+ data.actividad.ID_Actividad +'" onclick="detalle(this)" class="btn-accion-tabla tooltipsC" title="Ver Detalles" data-toggle="modal" data-target="#defaultModal">'+ data.actividad.ACT_Nombre_Actividad +'</a></td><td>';
                                    if(data.actividad.EST_Nombre_Estado == 'En Proceso'){
                                        actividad += '<select name="ACT_Requerimiento" id="ACT_Requerimiento" class="form-control show-tick" data-live-search="true" required onchange="cambioActividad(this, '+ data.actividad.ID_Actividad +')"><option value="">-- Seleccione una Actividad --</option>';
                                        data.requerimientos.forEach(function(requerimiento){
                                            actividad += '<option value="'+ requerimiento.id +'" '
                                            if (data.actividad.ID_Requerimiento == requerimiento.id) { actividad += 'selected' }
                                            actividad += '>'+ requerimiento.REQ_Nombre_Requerimiento +'</option>'
                                        });
                                        actividad += '</select>';
                                    } else {
                                        actividad += data.actividad.REQ_Nombre_Requerimiento;
                                    }
                                    actividad += '</td><td>'+ data.actividad.USR_Nombres_Usuario +' '+ data.actividad.USR_Apellidos_Usuario +'</td><td>'+moment(data.actividad.ACT_Fecha_Fin_Actividad).format('DD/MM/YYYY HH:mm A') +'</td><td>'+ data.actividad.EST_Nombre_Estado +'</td><td>';
                                    if(data.actividad.EST_Nombre_Estado == 'En Proceso'){
                                        if(data.permisos.editar == true){
                                            actividad += '<button class="btn-accion-tabla tooltipsC open-modal-trabajador" title="Editar esta tarea" value="'+ data.actividad.ID_Actividad +'"><i class="material-icons text-info" style="font-size: 17px;">edit</i></button>';
                                        }
                                        if(data.permisos.eliminar == true){
                                            actividad += '<a onclick="delActividad('+ data.actividad.ID_Actividad +')" class="btn-accion-tabla tooltipsC" title="Eliminar esta tarea"><i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i></a>';
                                        }
                                    }
                                    actividad += '</td></tr>';
                                    if (state == "add") {
                                        jQuery('#lista-actividades-trabajador').append(actividad);
                                    } else {
                                        $("#actividad" + actividad_id).replaceWith(actividad);
                                    }

                                    jQuery('#form_validation').trigger("reset");
                                    jQuery('#modalFormTrabajador').modal('hide');
                                }

                                InkBrutalPRY.notificaciones('Actividad '+(state == "add" ? 'registrada' : 'editada')+' con éxito', 'InkBrutalPRY', 'success');
                            } else if(data.mensaje == "fp"){
                                InkBrutalPRY.notificaciones('Las fechas no pueden ser días ya pasados', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "fs"){
                                InkBrutalPRY.notificaciones('La fecha de inicio no puede ser superior a la fecha de finalización', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "hm"){
                                InkBrutalPRY.notificaciones('La hora de entrega debe ser mínimo de 1 hora y máximo de 10 horas', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "dr"){
                                InkBrutalPRY.notificaciones('La actividad ya cuenta con una tarea del mismo nombre', 'InkBrutalPRY', 'error');
                            } else if(data.mensaje == "np"){
                                InkBrutalPRY.notificaciones('No tiene permisos para entrar en este módulo', 'InkBrutalPRY', 'error');
                            } else if(data.errors != null){
                                data.errors.forEach(function(error){
                                    InkBrutalPRY.notificaciones(error, 'InkBrutalPRY', 'error', '10000');
                                });
                            } else {
                                InkBrutalPRY.notificaciones('Error al '+(state == "add" ? 'registrar' : 'editar')+' la tarea.', 'InkBrutalPRY', 'warning');
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
            jQuery('.delete-actividad').click(function () {
                event.preventDefault();
                const actividad_id = $(this).val();
                swal({
                    title: '¿Está seguro que desea eliminar la tarea?',
                    text: 'Esta acción no se puede deshacer!',
                    icon: 'warning',
                    buttons: {
                        cancel: "Cancelar",
                        confirm: "Aceptar"
                    },
                }).then((value) => {
                    if (value) {
                        ajaxRequest(actividad_id);
                    }
                });
            });
        });
        function ajaxRequest(actividad_id){
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                type: "DELETE",
                url: '/actividades/' + actividad_id,
                data: {"_token": "{{ csrf_token() }}"},
                success: function (data) {
                    if (data.mensaje == "ok") {
                        $("#actividad" + actividad_id).remove();
                        InkBrutalPRY.notificaciones('La tarea fue eliminada correctamente', 'InkBrutalPRY', 'success');
                    } else if (data.mensaje == "ng") {
                        InkBrutalPRY.notificaciones('La actividad no pudo ser eliminada o hay otro recurso usándola', 'InkBrutalPRY', 'error');
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