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
                                            <a href="{{route('agregar_factura', ['idA' => $cobro->Id_Actividad, 'idC' => $cobro->Id_Cliente])}}" class="btn-accion-tabla tooltipsC" title="Agregar a Cuenta de Cobro de {{$cobro->USR_Nombres_Usuario.' '.$cobro->USR_Apellidos_Usuario}}">
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
                        PROYECTOS CON ACTIVIDADES POR COBRAR
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if (count($proyectos)<=0)
                    <div class="alert alert-success">
                        No hay cobros pendientes
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cliente</th>
                                    <th>Actividades</th>
                                    @foreach ($proyectos as $proyecto)
                                        @if ($proyecto->ACT_Costo_Real_Actividad != 0)
                                            <th class="width70"></th>
                                        @endif
                                        @break
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proyectos as $proyecto)
                                    <tr>
                                        <td>{{$proyecto->PRY_Nombre_Proyecto}}</td>
                                        <td>{{$proyecto->USR_Nombres_Usuario.' '.$proyecto->USR_Apellidos_Usuario}}</td>
                                        <td>{{$proyecto->No_Actividades}}</td>
                                        @if ($proyecto->ACT_Costo_Real_Actividad != 0)
                                            <td>
                                                <a href="{{route('generar_factura', ['id' => $proyecto->Id_Proyecto])}}" class="btn-accion-tabla tooltipsC" title="Descargar Cuenta de Cobro">
                                                    <i class="material-icons text-info" style="font-size: 17px;">get_app</i>
                                                </a>
                                            </td>
                                        @endif
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