@extends('theme.bsb.director.layout')
@section('titulo')
Crud Proyectos
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    <h2>
                        PROYECTOS
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_proyecto_director')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo Proyecto</a>
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tabla-data">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Valor</th>
                                <th>Empresa</th>
                                <th class="width70"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proyectos as $proyecto)
                            <tr>
                                <td>{{$proyecto->PRY_Nombre_Proyecto}}</td>
                                <td>{{$proyecto->PRY_Descripcion_Proyecto}}</td>
                                <td>{{$proyecto->PRY_Valor_Proyecto}}</td>
                                <td>{{$proyecto->PRY_Empresa_Proyecto}}</td>
                                <td>
                                    <a href="{{route('editar_proyecto', ['id'=>$proyecto->id])}}" class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                        <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                    </a>
                                    <form action="{{route('eliminar_proyecto', ['id'=>$proyecto->id])}}" class="d-inline form-eliminar" method="POST">
                                        @csrf @method("delete")
                                        <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm" title="Eliminar este registro">
                                            <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                        </button>
                                    </form>
                                </td>
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