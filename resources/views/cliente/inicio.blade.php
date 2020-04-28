@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Mis Proyectos
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/pages/scripts/Director/porcentaje.js")}}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                    <div class="panel-group" id="accordion_17" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-col-pink">
                            <div class="panel-heading" role="tab" id="headingOne_17">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_17" href="#collapseOne_17" aria-expanded="false" aria-controls="collapseOne_17">
                                        <i class="material-icons">contact_mail</i> INFORMACIÓN
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne_17" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_17">
                                <div class="panel-body">
                                    <div>
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#proyectos" aria-controls="settings" role="tab" data-toggle="tab">Mis Proyectos</a></li>
                                            <li role="presentation"><a href="#facturas" aria-controls="settings" role="tab" data-toggle="tab">Mis Cuentas de Cobro <span class="label label-danger">({{count($proyectosPagar)}})</span></a></li>
                                            <li role="presentation"><a href="#facturasadicionales" aria-controls="settings" role="tab" data-toggle="tab">Mis Cuentas de Cobro Adicionales <span class="label label-danger">({{count($factAdicional)}})</span></a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active table-responsive" id="proyectos">
                                                @if (count($proyectos)<=0)
                                                    <div class="alert alert-info">
                                                        No hay datos que mostrar.
                                                    </div>
                                                @else
                                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                        <thead>
                                                            <tr>
                                                                <th>Nombre</th>
                                                                <th>Descripción</th>
                                                                <th class="width70"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($proyectos as $proyecto)
                                                                <tr>
                                                                    <td>
                                                                        <a onclick="avance({{$proyecto->id}})" class="btn-accion-tabla tooltipsC" title="Ver Progreso">
                                                                            {{$proyecto->PRY_Nombre_Proyecto}}
                                                                        </a>
                                                                        <div id="progressBar{{$proyecto->id}}" style="display: none;"></div>
                                                                    </td>
                                                                    <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                                                    <td class="width70">
                                                                        @if ($permisos['listarA']==true)
                                                                            <a href="{{route('requerimientos', ['idP'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Listar Tareas">
                                                                                <i class="material-icons text-info" style="font-size: 17px;">assignment</i>
                                                                            </a>
                                                                        @endif
                                                                        <a href="{{route('generar_pdf_proyecto_cliente', ['id'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Tareas">
                                                                            <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade in table-responsive" id="facturas">
                                                @if (count($proyectosPagar)<=0)
                                                    <div class="alert alert-info">
                                                        No hay datos que mostrar.
                                                    </div>
                                                @else
                                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                        <thead>
                                                            <tr>
                                                                <th>Nombre</th>
                                                                <th>Descripción</th>
                                                                <th class="width70"></th>
                                                                <th>Pagar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($proyectosPagar as $proyecto)
                                                                <tr>
                                                                    <td>{{$proyecto->PRY_Nombre_Proyecto}}</td>
                                                                    <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                                                    <td class="width70">
                                                                        <a href="{{route('generar_pdf_proyecto_cliente', ['id'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Actividades">
                                                                            <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
                                                                        </a>
                                                                        <a href="{{route('generar_factura_cliente', ['id' => $proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Descargar Cuenta de Cobro">
                                                                            <i class="material-icons text-info" style="font-size: 17px;">find_in_page</i>
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{route('pagar_factura_cliente', ['id' => $proyecto->id])}}" class="btn btn-info">PAGAR</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade in table-responsive" id="facturasadicionales">
                                                @if (count($factAdicional)<=0)
                                                    <div class="alert alert-info">
                                                        No hay datos que mostrar.
                                                    </div>
                                                @else
                                                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                                        <thead>
                                                            <tr>
                                                                <th>Nombre</th>
                                                                <th>Descripción</th>
                                                                <th class="width70"></th>
                                                                <th>Pagar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($factAdicional as $factura)
                                                                <tr>
                                                                    <td>{{$factura->PRY_Nombre_Proyecto}}</td>
                                                                    <td>{{$factura->PRY_Descripcion_Proyecto}}</td>
                                                                    <td class="width70">
                                                                        <a href="{{route('generar_factura_adicional_cliente', ['id' => $factura->Id_Proyecto])}}" class="btn-accion-tabla tooltipsC" title="Descargar Cuenta de Cobro">
                                                                            <i class="material-icons text-info" style="font-size: 17px;">find_in_page</i>
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{route('pagar_factura_adicional_cliente', ['id' => $factura->Id_Proyecto])}}" class="btn btn-info">PAGAR</a>
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
        </div>
    </div>
</div>
@endsection