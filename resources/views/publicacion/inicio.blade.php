@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Parrilla Organica - Publicaciones
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
                       Publicaciones
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear'] == true)
                                <a class="btn btn-success waves-effect" href="{{route('crear_publicacion',$parrilla->id)}}"><i
                                    class="material-icons" style="color:white;">add</i> Nueva Publicacion</a>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                @if (count($publicaciones)==0)
                    <div class="alert alert-info">
                        No hay datos que mostrar
                        <a href="{{route('crear_publicacion',$parrilla->id)}}" class="alert-link">Clic aquí para agregar!</a>.
                    </div>
                    @else
                    <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Ubicacion</th>
                                        <th>Publico</th>
                                        <th>Copy General</th>
                                        <th>Copy Imagen</th>
                                        @if ($permisos['editar']==true || $permisos['eliminar']==true||$permisos['pieza']==true)
                                            <th class="width70"></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($publicaciones as $publicacion)
                                        <tr>
                                            <td>
                                                {{$publicacion->PBL_Fecha}}
                                            </td>
                                            <td>
                                                {{$publicacion->PBL_Tipo}}
                                            </td>
                                            <td>
                                                {{$publicacion->PBL_Ubicacion}}
                                            </td>
                                            <td>
                                                {{$publicacion->PBL_Publico}}
                                            </td>
                                            <td>
                                                {{$publicacion->PBL_Copy_General}}
                                            </td>
                                            <td>
                                                {{$publicacion->PBL_Copy_Pieza}}
                                            </td>
                                            @if ($permisos['editar']==true || $permisos['eliminar']==true||$permisos['pieza']==true)
                                                <td>
                                                    <form class="form-eliminar" action="{{route('eliminar_publicacion', ['id'=>$publicacion->id])}}"
                                                        class="d-inline" method="POST">
                                                        @if ($permisos['editar'] == true)
                                                            <a href="{{route('editar_publicacion', ['id'=>$publicacion->id])}}"
                                                                class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                                <i class="material-icons text-info" style="font-size: 17px;">edit</i>
                                                            </a>
                                                        @endif
                                                        @if ($permisos['pieza'] == true)
                                                            <a href="{{route('pieza', ['id'=>$publicacion->id])}}"
                                                                class="btn-accion-tabla tooltipsC" title="Piezas">
                                                                <i class="material-icons text-success" style="font-size: 17px;">perm_media</i>
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

@section('scripts')
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
@endsection