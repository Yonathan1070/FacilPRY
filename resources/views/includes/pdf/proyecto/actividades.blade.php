@extends('includes.pdf.layout')
@section('titulo')
    @foreach ($actividades as $actividad)
        PDF Actividades {{$actividad->PRY_Nombre_Proyecto}}@break
    @endforeach
@endsection
@section('contenido')
<!-- Multiple Items To Be Open -->
<?php
    foreach ($actividades as $actividad) {
        $logo = $actividad->EMP_Logo_Empresa;
        break;
    }
    $base64=null;
    if($logo != null){
        $path = base_path().'\public\assets\bsb\images\Logos/'.$logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
?>
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
                        <img src="{{$base64}}" height="150px">
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
                                <td>{{$actividad->ACT_Estado_Actividad}}</td>
                                <td>{{$actividad->ACT_Fecha_Inicio_Actividad.' - '.$actividad->ACT_Fecha_Fin_Actividad}}</td>
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