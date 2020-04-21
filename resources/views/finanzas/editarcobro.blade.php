@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Editar Cobro Adicional
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
                        EDITAR COSTOS ({{$proyecto->PRY_Nombre_Proyecto}})
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('inicio_finanzas')}}">
                                <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a finanzas
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <table class="table table-striped table-bordered table-hover  dataTable js-exportable" id="mainTable">
                        <thead>
                            <tr>
                                <th style="display: none">Id Costo</th>
                                <th>#</th>
                                <th>Descripción</th>
                                <th>Costo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cobros as $key => $cobro)
                                <tr>
                                    <td style="display: none">{{$cobro->id}}</td>
                                    <td class="uneditable">{{++$key}}</td>
                                    <td class="descripcion">{{$cobro->FACT_AD_Descripcion}}</td>
                                    <td class="precio">{{$cobro->FACT_AD_Precio_Factura}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    $('table td.descripcion').on('change', function(evt, newValue) {
        var cell = $(this).parent();
        var cell_index = $(this).parent().parent().children().index(cell);
        var identifier = evt.target.parentElement.rowIndex;
        var idCosto = document.getElementById("mainTable").rows[identifier].cells[0].innerHTML;
        $.ajax({
            dataType: "json",
            method: "put",
            url: "/finanzas/" + idCosto +"/actualizar-adicional",
            data: {"_token":"{{ csrf_token() }}", FACT_AD_Descripcion:newValue},
            success:function(data){
                if(data.msg == "successDescripcion")
                    InkBrutalPRY.notificaciones('Descripcion actualizada', 'InkBrutalPRY', 'success');
                if(data.msg == "errorDescripcion")
                    InkBrutalPRY.notificaciones('Error al actualizar la descripción', 'InkBrutalPRY', 'error');
            }
        });
        
    });

    $('table td.precio').on('change', function(evt, newValue) {
        var cell = $(this).parent();
        var cell_index = $(this).parent().parent().children().index(cell);
        var identifier = evt.target.parentElement.rowIndex;
        var idCosto = document.getElementById("mainTable").rows[identifier].cells[0].innerHTML;
        $.ajax({
            dataType: "json",
            method: "put",
            url: "/finanzas/" + idCosto +"/actualizar-adicional",
            data: {"_token":"{{ csrf_token() }}", FACT_AD_Precio_Factura:newValue},
            success:function(data){
                if(data.msg == "successPrecio")
                    InkBrutalPRY.notificaciones('Costo actualizado', 'InkBrutalPRY', 'success');
                if(data.msg == "errorPrecio")
                    InkBrutalPRY.notificaciones('Error al actualizar el costo', 'InkBrutalPRY', 'error');
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
                                    document.location.href="/perfil-operacion/"+data.idPerfil+"/carga";
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