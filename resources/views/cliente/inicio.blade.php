@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Mis Proyectos
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
                        MIS PROYECTOS
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if (count($proyectos)<=0)
                    <div class="alert alert-info">
                        No hay datos que mostrar.
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Pagar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proyectos as $proyecto)
                                    <tr>
                                        <td>{{$proyecto->PRY_Nombre_Proyecto}}</td>
                                        <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                        <td class="width70">
                                            <a href="{{route('generar_pdf_proyecto_cliente', ['id'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Reporte de Actividades">
                                                <i class="material-icons text-info" style="font-size: 17px;">file_download</i>
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
                        MIS FACTURAS
                    </h2>
                </div>
                <div class="body table-responsive">
                    @if (count($proyectosPagar)<=0)
                    <div class="alert alert-info">
                        No hay datos que mostrar.
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
                                            <a href="{{route('generar_factura_cliente', ['id' => $proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Descargar Factura">
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
            </div>
        </div>
    </div>
</div>
@endsection