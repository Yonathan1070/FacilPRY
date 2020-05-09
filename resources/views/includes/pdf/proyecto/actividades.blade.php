@extends('includes.pdf.layout')
@section('titulo')
    @foreach ($actividades as $actividad)
        PDF Actividades {{$actividad->PRY_Nombre_Proyecto}}@break
    @endforeach
@endsection
@section('contenido')
<!-- Multiple Items To Be Open -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    @foreach ($actividades as $actividad)
                        ACTIVIDADES PROYECTO {{strtoupper($actividad->PRY_Nombre_Proyecto)}}
                        @break
                    @endforeach
                </h2>
                <ul class="header-dropdown" style="top:10px;">
                    <li class="dropdown">
                        @if ($empresa->EMP_Logo_Empresa == null)
                            <img src="{{$_SERVER["DOCUMENT_ROOT"]."assets\bsb\images\Logos/InkLogo.png"}}" height="150px">
                        @else
                        <img src="{{$_SERVER["DOCUMENT_ROOT"]."assets\bsb\images\Logos/.$empresa->EMP_Logo_Empresa"}}" height="150px">
                        @endif
                    </li>
                </ul>
                
            </div>
            <div class="body table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tabla-data">
                    <thead>
                        <tr>
                            <th>ACTIVIDAD</th>
                            <th>DESCRIPCIÓN</th>
                            <th>ESTADO</th>
                            <th>FECHAS</th>
                            <th>ENCARGADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($actividades as $actividad)
                            <tr>
                                <td>{{$actividad->ACT_Nombre_Actividad}}</td>
                                <td>{{$actividad->ACT_Descripcion_Actividad}}</td>
                                <td>{{$actividad->EST_Nombre_Estado}}</td>
                                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Inicio_Actividad)->format('d/m/Y').' - '.\Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $actividad->ACT_Fecha_Fin_Actividad)->format('d/m/Y')}}</td>
                                <td>{{$actividad->NombreT.' '.$actividad->ApellidoT}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    <!-- #END# Multiple Items To Be Open -->
    </div>
</div>
@endsection