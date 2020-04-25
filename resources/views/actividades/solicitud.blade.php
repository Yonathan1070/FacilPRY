@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Solicitud de Tiempo Tarea
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card profile-card">
                <div class="header">
                    <h2>
                        SOLICITUD DE TIEMPO
                    </h2>
                </div>
                <div class="body">
                    <div class="profile-body">
                        <div class="content-area">
                            <h3>{{$solicitud->ACT_Nombre_Actividad}}</h3>
                            <p>{{$solicitud->ACT_Descripcion_Actividad}}</p>
                            <p>{{$solicitud->USR_Nombres_Usuario.' '.$solicitud->USR_Apellidos_Usuario}}</p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                <span>Fecha de Inicio</span>
                                <span>{{$solicitud->ACT_Fecha_Inicio_Actividad}}</span>
                            </li>
                            <li>
                                <span>Fecha de Entrega</span>
                                <span>{{$solicitud->ACT_Fecha_Fin_Actividad}}</span>
                            </li>
                            <li>
                                <span>Horas adicionales Solicitadas</span>
                                <span>{{$solicitud->SOL_TMP_Hora_Solicitada}}</span>
                            </li>
                        </ul>
                        <a href="{{route('aprobar_solicitud_tiempo_actividades', ['idS' => $solicitud->Id_Solicitud])}}" class="btn btn-primary btn-lg waves-effect btn-block">APROBAR</a>
                    </div>
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
                    InkBrutalPRY.notificaciones('Horas asignadas, ya superó el limite de 8 horas', 'InkBrutalPRY', 'warning');
                if(data.msg == "error")
                    InkBrutalPRY.notificaciones('La cantidad de horas de trabajo diaria ha superado el límite de 15 Horas', 'InkBrutalPRY', 'error');
                if(data.msg == "cero")
                    InkBrutalPRY.notificaciones('El trabajador ya asignó unas horas, no puede ser cero esta asignación', 'InkBrutalPRY', 'error');
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