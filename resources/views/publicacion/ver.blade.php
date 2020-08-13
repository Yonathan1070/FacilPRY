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
                       Detalle de publicacion
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">

                        </li>
                    </ul>
                </div>
                <div class="body table-responsive">
                    <div class="row text-center">
                        <div class="col-md-6">
                        @if (count($imagenes)==0)
                    <div class="alert alert-info">
                        No hay datos que mostrar
                        <a href="{{route('crear_pieza',$publicaciones->id)}}" class="alert-link">Clic aquí para agregar!</a>.
                    </div>
                @else
 <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators"> 
                <?php 
                for($i=0;$i<count($imagenes);$i++){?>
                <li data-target="#carouselExampleIndicators" data-slide-to="<?=$i?>" class="active"></li>
                <?php
                }
                ?>
  </ol>
  <div class="carousel-inner">
            @foreach($imagenes as $imagen)
            <div class="carousel-item ">
                <img class="d-block w-100 img-responsive" src="{{ asset( 'parrilla organica/'.$imagen->PZA_Url)}}">
            </div>
            @endforeach
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

 @endif
<div>
    <h5>Tipo: <?php echo $publicaciones->PBL_Tipo;?></h5>
    <h5>Ubicacion: <?php echo $publicaciones->PBL_Ubicacion;?></h5>
    <h5>Copy Imagen: <?php echo $publicaciones->PBL_Copy_Imagen;?></h5>
    <h5>Copy General: <?php echo $publicaciones->PBL_Copy_General;?></h5>
</div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#aprobado">
                                  <i class="material-icons" style="color:white;">check_circle</i>Aprobado</button>
                                </div>
                                <div class="col-md-3">
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#solicitud">
                                  <i class="material-icons" style="color:white;">swap_horizontal_circle</i> <br>Solicitar Cambio</button>
                                </div>
                                <div class="col-md-3">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rechazado">
                                  <i class="material-icons" style="color:white;">remove_circle</i>Rechazado</button>
                                </div>
                                <div class="col-md-3">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#rechazado">
                                  <i class="material-icons" style="color:white;">cloud_download</i>Descargar</button>
                                </div>
                            </div>
                            @if (count($comentarios)==0)
                            <div class="alert alert-info">
                            No hay datos que mostrar
                               </div>
                             @else
                             <div>
                             @foreach($comentarios as $comentario)
                              <p><b>{{$comentario->USR_Nombre_Usuario}}</b> dice: <i>{{$comentario->EST_Nombre_Estado}}. {{$comentario->CMR_Comentario}}. {{$comentario->creacion}}</i></p>
                              @endforeach
                             </div>
                            @endif

                        </div>

                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Modal Aprobado -->
<div class="modal fade" id="aprobado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Comentario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body form-group">
       <form action="{{route('guardar_comentario')}}"method="POST">
         @csrf
       <input type="hidden" name="CMR_Publicacion_Id" id="CMR_Publicacion_Id" value="{{$publicaciones->id}}">
       <input type="hidden" name="CMR_Estado_Id" id="CMR_Estado_Id" value="5"> 
       <input type="hidden" name="CMR_Usuario_Id" id="CMR_Usuario_Id" value="{{session()->get('Usuario_Id')}}"> 
       <label for="CMR_Comentario">Tu comentario</label>
       <textarea class="form-control" name="CMR_Comentario" id="CMR_Comentario" cols="30" rows="5"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <input type="submit" class="btn btn-success" value="Aprobar">
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Rechazado -->
<div class="modal fade" id="rechazado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Comentario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body for m-group">
       <form action="{{route('guardar_comentario')}}"method="POST">
         @csrf
       <input type="hidden" name="CMR_Publicacion_Id" id="CMR_Publicacion_Id" value="{{$publicaciones->id}}">
       <input type="hidden" name="CMR_Estado_Id" id="CMR_Estado_Id" value="6"> 
       <input type="hidden" name="CMR_Usuario_Id" id="CMR_Usuario_Id" value="{{session()->get('Usuario_Id')}}"> 
       <label for="CMR_Comentario">Tu comentario</label>
       <textarea class="form-control" name="CMR_Comentario" id="CMR_Comentario" cols="30" rows="5"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <input type="submit" class="btn btn-danger" value="Rechazar">
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Solicitar Cambio -->
<div class="modal fade" id="solicitud" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Comentario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body for m-group">
       <form action="{{route('guardar_comentario')}}"method="POST">
         @csrf
       <input type="hidden" name="CMR_Publicacion_Id" id="CMR_Publicacion_Id" value="{{$publicaciones->id}}">
       <input type="hidden" name="CMR_Estado_Id" id="CMR_Estado_Id" value="13"> 
       <input type="hidden" name="CMR_Usuario_Id" id="CMR_Usuario_Id" value="{{session()->get('Usuario_Id')}}"> 
       <label for="CMR_Comentario">Tu comentario</label>
       <textarea class="form-control" name="CMR_Comentario" id="CMR_Comentario" cols="30" rows="5"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <input type="submit" class="btn btn-warning" value="Solicitar">
        </form>
      </div>
    </div>
  </div>
</div>
