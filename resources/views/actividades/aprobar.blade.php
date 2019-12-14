@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Crud Actividades
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card">
                <div class="header">
                    @foreach ($horasAprobar as $actividad)
                        <h2>
                            APROBAR HORAS ASIGNADAS PARA LA ACTIVIDAD {{$actividad->ACT_Nombre_Actividad}}.
                        </h2>
                        @break
                    @endforeach
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" onclick="volver()">
                                <i class="material-icons" style="color:white;">keyboard_backspace</i> Guardar y Volver
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @foreach ($horasAprobar as $actividad)
                        <input type="hidden" id="idActividad" value="{{$actividad->id}}">
                        @break
                    @endforeach
                    @if (count($horasAprobar)<=0)
                        <div class="alert alert-info">
                            No hay datos que mostrar. 
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover  dataTable js-exportable" id="mainTable">
                            <thead>
                                <tr>
                                    <th style="display: none">Id Hora</th>
                                    <th>Actividad</th>
                                    <th>Descripción</th>
                                    <th>Requerimiento</th>
                                    <th>Encargado</th>
                                    <th>Horas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($horasAprobar as $horas)
                                    <tr>
                                        <td style="display: none">{{$horas->Id_Horas}}</td>
                                        <td class="uneditable">{{$horas->ACT_Nombre_Actividad}}</td>
                                        <td class="uneditable">{{$horas->ACT_Descripcion_Actividad}}</td>
                                        <td class="uneditable">{{$horas->REQ_Nombre_Requerimiento}}</td>
                                        <td class="uneditable">{{$horas->USR_Nombres_Usuario.' '.$actividad->USR_Apellidos_Usuario}}</td>
                                        <td class="hora">{{$horas->HRS_ACT_Cantidad_Horas_Asignadas}}</td>
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
@endsection
@section('scripts')
<script src="{{asset('assets/bsb/plugins/editable-table/mindmup-editabletable.js')}}"></script>
<script src="{{asset('assets/bsb/js/pages/tables/editable-table.js')}}"></script>

<script>
    $('table td.uneditable').on('change', function(evt, newValue) {
        return false;
    });
    $('table td.hora').on('change', function(evt, newValue) {
        var cell = $(this).parent();
        var cell_index = $(this).parent().parent().children().index(cell);
        var identifier = evt.target.parentElement.rowIndex;
        var idHora = document.getElementById("mainTable").rows[identifier].cells[0].innerHTML;
        var idActividad = document.getElementById("idActividad").value;
        $.ajax({
            dataType: "json",
            method: "put",
            url: "/actividades/" + idHora +"/aprobar",
            data: {"_token":"{{ csrf_token() }}", HRS_ACT_Cantidad_Horas_Asignadas:newValue},
            success:function(data){
                if(data.msg == "alerta")
                    FacilPry.notificaciones('Horas asignadas, ya superó el limite de 8 horas', 'FacilPRY', 'warning');
                if(data.msg == "error")
                    FacilPry.notificaciones('La cantidad de horas de trabajo diaria ha superado el límite de 15 Horas', 'FacilPRY', 'error');
                if(data.msg == "exito")
                    FacilPry.notificaciones('Horas Asignadas', 'FacilPRY', 'success');
            }
        });
        
    });

    function volver(){
        event.preventDefault();
        const form = $(this);
        swal({
            title: '¿Está seguro que desea salir?',
            text: 'Si ya reasignaste las horas, la acción no se podrá deshacer!',
            icon: 'warning',
            buttons: {
                cancel: "Cancelar",
                confirm: "Aceptar"
            },
        }).then((value) => {
            if(value){
                swal({
                    title: 'Completado',
                    text: 'Horas de trabajo aprobadas',
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
                            url: "/actividades/" + idActividad +"/terminar-aprobacion",
                            success:function(data){
                                if(data.msg == "exito")
                                    document.location.href="{!! route('inicio_director'); !!}";
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