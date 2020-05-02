@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Asignación de Horas
@endsection
@section('contenido')
<div class="container-fluid">
    <!-- Basic Validation -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('includes.form-error')
                @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    @foreach ($actividades as $actividad)
                        <h2>ASIGNAR HORAS DE TRABAJO PARA ACTIVIDAD ({{$actividad->ACT_Nombre_Actividad}})</h2>
                        @break
                    @endforeach
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" onclick="volver()">
                                <i class="material-icons" style="color:white;">keyboard_backspace</i> Notificar y Volver
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @foreach ($actividades as $actividad)
                        <input type="hidden" id="idActividad" value="{{$actividad->id}}">
                        @break
                    @endforeach
                    @if (count($actividades)<=0)
                        <div class="alert alert-warning">
                            No hay Datos que mostrar.
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="mainTable">
                            <thead>
                                <tr>
                                    <th style="display: none">Id</th>
                                    <th>Fecha Vigente</th>
                                    <th>Asignar Horas de Trabajo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actividades as $actividad)
                                    @if (\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', \Carbon\Carbon::now())->lte(\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->HRS_ACT_Fecha_Actividad.' 23:59:59')))
                                        <tr>
                                            <td class="identifier"  style="display: none">{{$actividad->Id_Horas}}</td>
                                            <td class="uneditable">{{$actividad->HRS_ACT_Fecha_Actividad}}</td>
                                            <td class="hola">{{$actividad->HRS_ACT_Cantidad_Horas_Asignadas}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Validation -->
</div>
@endsection

@section('scripts')
<!-- Plugin Js para Validaciones -->
<script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
<!-- Mensajes en español -->
<script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

<script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>

<!-- Editable Table Plugin Js -->
<script src="{{asset('assets/bsb/plugins/editable-table/mindmup-editabletable.js')}}"></script>
<script src="{{asset('assets/bsb/js/pages/tables/editable-table.js')}}"></script>
<script>
    $('table td.uneditable').on('change', function(evt, newValue) {
        return false;
    });
    $('table td.hola').on('change', function(evt, newValue) {
        var cell = $(this).parent();
        var cell_index = $(this).parent().parent().children().index(cell);
        var identifier = evt.target.parentElement.rowIndex;
        var idHora = document.getElementById("mainTable").rows[identifier].cells[0].innerHTML;
        var idActividad = document.getElementById("idActividad").value;
        $.ajax({
            dataType: "json",
            method: "put",
            url: "/perfil-operacion/actividades/" + idHora +"/asignacion-horas",
            data: {"_token":"{{ csrf_token() }}", HRS_ACT_Cantidad_Horas_Asignadas:newValue},
            success:function(data){
                if(data.msg == "alerta")
                    InkBrutalPRY.notificaciones('Horas asignadas, ya superó el limite de 8 horas', 'InkBrutalPRY', 'warning');
                if(data.msg == "error")
                    InkBrutalPRY.notificaciones('La cantidad de horas de trabajo diaria ha superado el límite de 15 Horas', 'InkBrutalPRY', 'error');
                if(data.msg == "errorF")
                    InkBrutalPRY.notificaciones('No puede asignar horas de trabajo a una fecha expirada.', 'InkBrutalPRY', 'error');
                if(data.msg == "errorH")
                    InkBrutalPRY.notificaciones('No puede asignar horas de trabajo debido a que está asignando una cantidad de horas superior a las restantes del día.', 'InkBrutalPRY', 'error');
                if(data.msg == "exito")
                    InkBrutalPRY.notificaciones('Horas Asignadas', 'InkBrutalPRY', 'success');
            }
        });
    });

    function volver(){
        event.preventDefault();
        const form = $(this);
        swal({
            title: '¿Está seguro que desea salir?',
            text: 'Si ya asignaste horas la acción no se podrá deshacer!',
            icon: 'warning',
            buttons: {
                cancel: "Cancelar",
                confirm: "Aceptar"
            },
        }).then((value) => {
            if(value){
                swal({
                    title: 'Completado',
                    text: 'Horas de trabajo Asignadas',
                    icon: 'success',
                    buttons: {
                        confirm: "Aceptar"
                    },
                }).then((value) => {
                    if(value){
                        var idActividad = document.getElementById("idActividad").value;
                        $.ajax({
                            dataType: "json",
                            method: "get",
                            url: "/perfil-operacion/actividades/" + idActividad +"/terminar-asignacion",
                            success:function(data){
                                if(data.msg == "exito")
                                    document.location.href="{!! route('actividades_perfil_operacion'); !!}";
                            }
                        });
                    }
                })
            }else{
                swal({
                    title: 'Cancelado',
                    text: 'Continúa asignando tus horas de trabajo!',
                    icon: 'info',
                    buttons: {
                        confirm: "Aceptar"
                    },
                })
            }
        });
    };
</script>
@endsection