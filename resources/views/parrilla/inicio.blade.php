@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Parrilla Organica
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
                        Parrilla Organica
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear'] == true)
                                <a class="btn btn-success waves-effect" href="{{route('crear_parrilla')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nueva Parrilla</a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                @if (count($parrillas)==0)
                    <div class="alert alert-info">
                        No hay datos que mostrar
                        <a href="{{route('crear_parrilla')}}" class="alert-link">Clic aquí para agregar!</a>.
                    </div>
                    @else
                            <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Año</th>
                                        <th>Mes</th>
                                        <th>Proyecto</th>
                                            <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parrillas as $parrilla)
                                        <tr>
                                            <td>
                                                {{$parrilla->PRL_Anio}}
                                            </td>
                                            <td>
                                                {{$parrilla->PRL_Mes}}
                                            </td>
                                            <td>{{$parrilla->PRY_Nombre_Proyecto}}</td>
                                                <td>
                                                    <form class="form-eliminar" action="{{route('eliminar_parrilla', ['id'=>$parrilla->id])}}"
                                                        class="d-inline" method="POST">
                                                            <a href="{{route('ver_parrilla', ['id'=>$parrilla->id])}}"
                                                                class="btn-accion-tabla tooltipsC" title="Ver parrilla">
                                                                <i class="material-icons text-success" style="font-size: 17px;">remove_red_eye</i>
                                                            </a>
                                                        @if ($permisos['editar'] == true)
                                                            <a href="{{route('editar_parrilla', ['id'=>$parrilla->id])}}"
                                                                class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                                <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                            </a>
                                                        @endif
                                                        @if ($permisos['publicacion'] == true)
                                                            <a href="{{route('publicacion', ['id'=>$parrilla->id])}}"
                                                                class="btn-accion-tabla tooltipsC" title="Publicaciones">
                                                                <i class="material-icons text-info" style="font-size: 17px;">event</i>
                                                            </a>
                                                        @endif
                                                        @if ($permisos['eliminar'] == true)
                                                            @csrf @method("delete")
                                                            <button type="submit" class="btn-accion-tabla eliminar tooltipsC" data-type="confirm"
                                                                title="Eliminar este registro">
                                                                <i class="material-icons text-danger" style="font-size: 17px;">delete_forever</i>
                                                            </button>
                                                        @endif
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

@section('scripts')
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
@endsection