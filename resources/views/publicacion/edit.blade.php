@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Editar Publicacion
@endsection
@section('contenido')
<div class="container-fluid">
    <!-- Basic Validation -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('includes.form-error')
                @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    <h2>Editar Publicación</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('publicacion','$publicacion->PBL_Parrilla_Id')}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver al listado
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" action="{{route('actualizar_publicacion', ['id' => $publicacion->PBL_Parrilla_Id])}}" method="POST">
                        @csrf @method("put")
                        <input type="hidden" name="PBL_Parrilla_Id" id="PBL_Parrilla_Id" value="{{$publicacion->PBL_Parrilla_Id}}">
<div class="form-group form-float">
<div class="row">
    <div class="col-md-4">
            <div class="form-group form-float">
            <label class="form-label">Fecha de Publicacion</label>
                <div class="form-line focused">
                    <input type="date" class="form-control" name="PBL_Fecha" id="PBL_Fecha" value="{{$publicacion->PBL_Fecha}}" required>                   
                </div>
            </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Ubicacion</label>
        <select name="PBL_Ubicacion" id="PBL_Ubicacion" class="form-control" required>
            <option value="FEED">FEED</option>
            <option value="STORY">STORY</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipo</label>
        <select name="PBL_Tipo" id="PBL_Tipo" class="form-control" required>
            <option value="CARRUSEL">CARRUSEL</option>
            <option value="IMAGEN">IMAGEN</option>
            <option value="VIDEO">VIDEO</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <label class="form-label">Publico</label>
    <div class="form-line">
        <input type="text" class="form-control" name="PBL_Publico" id="PBL_Publico" maxlength="250" value="{{$publicacion->PBL_Publico}}" >
    </div>
    </div>
    <div class="col-md-6">
    <label class="form-label">Copy Imagen</label>
    <div class="form-line">
        <input type="text" class="form-control" name="PBL_Copy_Pieza" id="PBL_Copy_Pieza" maxlength="250" value="{{$publicacion->PBL_Copy_Pieza}}">
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-12">
    <div class="form-line">
            <textarea name="PBL_Copy_General" id="PBL_Copy_General" cols="30" rows="5"
            class="form-control no-resize" maxlength="1000"
            >
            <?=$publicacion->PBL_Copy_General?>
            </textarea>
        <label class="form-label">Copy General</label>
    </div>
</div>
</div>
</div>

                        <a class="btn btn-danger waves-effect" href="{{route('publicacion',' $publicacion->PBL_Parrilla_Id')}}">CANCELAR</a>
                        <button class="btn btn-primary waves-effect" type="submit">ACTUALIZAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Validation -->
</div>
@endsection

@section('scripts')
<!-- Plugin Js para Validaciones -->
<script src="{{asset("assets/bsb/plugins/jquery-validation/jquery.validate.js")}}"></script>
<!-- Mensajes en español -->
<script src="{{asset("assets/bsb/plugins/jquery-validation/localization/messages_es.js")}}"></script>

<script src="{{asset("assets/bsb/js/pages/forms/form-validation.js")}}"></script>
@endsection