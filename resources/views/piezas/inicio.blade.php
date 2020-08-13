@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Parrilla Organica - Piezas
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
                        Piezas
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            @if ($permisos['crear'] == true)
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                            <i class="material-icons" style="color:white;">add</i>Subir Piezas</button>

                            @endif
                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                      <div class="row">
                      @if (count($imagenes)==0)
                    <div class="alert alert-info">
                        No hay datos que mostrar
                    </div>
                @else
                <table class="table table-striped table-bordered table-hover dataTable js-exportable" id="tabla-data">
                                <thead>
                                    <tr>
                                        <th>Pieza</th>
                                        <th class="width70"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($imagenes as $imagen)
                                        <tr>
                                            <td>
                                                <img style="width:250px;" src="{{ asset( 'parrilla organica/'.$imagen->PZA_Url)}}" class="img-responsive">
                                            </td>
                                                <td>
                                                    <form class="form-eliminar" action="{{route('eliminar_pieza', ['id'=>$imagen->id])}}"
                                                        class="d-inline" method="POST">
                                                        @if ($permisos['editar'] == true)
                                                            <a href="{{route('editar_pieza', ['id'=>$imagen->id])}}"
                                                                class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                                                <i class="material-icons text-info" style="font-size: 17px;">edit</i>
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
    </div>
@endsection

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Subir Piezas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
       <form action="{{route('guardar_pieza')}}"method="POST" enctype="multipart/form-data">
       @csrf
        <input type="hidden" name="PZA_Publicacion_Id" id="PZA_Publicacion_Id" value="{{$publicaciones->id}}">
        <input type="file" class="form-control " name="PZA_Archivo[]" id="PZA_Archivo" accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG" multiple>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <input type="submit" class="btn btn-primary" value="Subir Imagenes">
        </form>
      </div>
    </div>
  </div>
</div>


@section('scripts')
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}"></script>
@endsection