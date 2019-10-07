@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Crud Desiciones
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
                        DECISIONES
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_decision')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nueva Desición</a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    @if (count($decisiones)<=0) <div class="alert alert-warning">
                        <strong>Advertencia!</strong> El sistema no cuenta con Decisiones agregadas
                        <a href="{{route('crear_decision_administrador')}}" class="alert-link">Clic aquí para
                            agregar!</a>.
                        </div>
                    @else
                        <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Rango de Calificación</th>
                                    <th>Indicador</th>
                                    <th class="width70"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($decisiones as $decision)
                                <tr>
                                    <td>{{$decision->DCS_Nombre_Decision}}</td>
                                    <td>{{$decision->DCS_Descripcion_Decision}}</td>
                                    <td>{{$decision->DCS_Rango_Inicio_Decision.' - '.$decision->DCS_Rango_Fin_Decision}}</td>
                                    <td>{{$decision->INDC_Nombre_Indicador}}</td>
                                    <td>
                                        <a href="{{route('editar_decision', ['id'=>$decision->id])}}"
                                            class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                            <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                        </a>
                                        <form action="{{route('eliminar_decision', ['id'=>$decision->id])}}"
                                            class="d-inline" method="POST">
                                            @csrf @method("delete")
                                            <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm"
                                                title="Eliminar este registro">
                                                <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                            </button>
                                        </form>
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