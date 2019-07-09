@extends('theme.bsb.administrador.layout')
@section('titulo')
Crud Roles
@endsection
@section('contenido')
<div class="container-fluid">
    <!-- Basic Validation -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>CREAR ROL</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('roles')}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver al listado
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" action="{{route('actualizar_rol', ['id' => $data->id])}}" method="POST">
                        @csrf @method("put")
                        @include('director.roles.form')
                        <a class="btn btn-danger waves-effect" href="{{route('roles')}}">CANCELAR</a>
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