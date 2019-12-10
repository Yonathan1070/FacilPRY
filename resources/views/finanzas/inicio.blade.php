@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Cobros
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
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
                        COBROS PENDIENTES
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if (count($cobros)<=0)
                        <div class="alert alert-success">
                            No hay cobros pendientes
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Cliente</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cobros as $cobro)
                                    <tr>
                                        <td>{{$cobro->ACT_Nombre_Actividad}}</td>
                                        <td>{{$cobro->ACT_Descripcion_Actividad}}</td>
                                        <td>{{$cobro->USR_Nombres_Usuario.' '.$cobro->USR_Apellidos_Usuario}}</td>
                                        <td>
                                            <a href="{{route('agregar_costo_actividad_finanzas', ['id' => $cobro->Id_Actividad])}}" class="btn-accion-tabla tooltipsC" title="Agregar Costo de la actividad">
                                                <i class="material-icons text-info" style="font-size: 17px;">note_add</i>
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
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PROYECTOS CON ACTIVIDADES FACTURADAS
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if (count($proyectos)<=0)
                    <div class="alert alert-success">
                        No hay facturas pendientes
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cliente</th>
                                    <th>Actividades</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proyectos as $proyecto)
                                    <tr>
                                        <td>{{$proyecto->PRY_Nombre_Proyecto}}</td>
                                        <td>{{$proyecto->USR_Nombres_Usuario.' '.$proyecto->USR_Apellidos_Usuario}}</td>
                                        <td>{{$proyecto->No_Actividades}}</td>
                                        <td>
                                            @if ($proyecto->ACT_Costo_Real_Actividad != 0)
                                                <a href="{{route('generar_factura_finanzas', ['id' => $proyecto->Id_Proyecto])}}" class="btn-accion-tabla tooltipsC" title="Descargar Factura">
                                                    <i class="material-icons text-info" style="font-size: 17px;">get_app</i>
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
        </div>
    </div>
</div>
@endsection