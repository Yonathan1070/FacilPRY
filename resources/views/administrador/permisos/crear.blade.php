@extends('theme.bsb.'.strtolower(session()->get('Rol_Id')).'.layout')
@section('titulo')
Crear Permiso
@endsection
@section('styles')
    <!-- Bootstrap Select Css -->
    <link href="{{asset("assets/bsb/plugins/bootstrap-select/css/bootstrap-select.css")}}" rel="stylesheet" />
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
                    <h2>CREAR PERMISO</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('asignar_rol_administrador')}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver a Permisos
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" action="{{route('guardar_permiso_administrador')}}" method="POST">
                        @csrf
                        @include('administrador.permisos.form')
                        <a class="btn btn-danger waves-effect" href="{{route('asignar_rol_administrador')}}">CANCELAR</a>
                        <button class="btn btn-primary waves-effect" type="submit">GUARDAR</button>
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

<!-- Select Plugin Js -->
<script src="{{asset("assets/bsb/plugins/bootstrap-select/js/bootstrap-select.js")}}"></script>
<!-- Input Mask Plugin Js -->
<script src="{{asset("assets/bsb/plugins/jquery-inputmask/jquery.inputmask.bundle.js")}}"></script>
<script src="{{asset("assets/bsb/plugins/bootstrap-select/js/i18n/defaults-es_CL.js")}}"></script>
@endsection