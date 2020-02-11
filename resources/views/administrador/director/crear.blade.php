@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Crud Director de Proyectos
@endsection
@section('styles')
    <!-- Multi Select Css -->
    <link href="{{asset("assets/bsb/plugins/multi-select/css/multi-select.css")}}" rel="stylesheet">
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
                    <h2>CREAR DIRECTOR DE PROYECTOS</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('directores_administrador')}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver al listado
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" action="{{route('guardar_director_administrador')}}" method="POST">
                        @csrf
                        @include('administrador.director.form')
                        <a class="btn btn-danger waves-effect" href="{{route('directores_administrador')}}">CANCELAR</a>
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

<!-- Multi Select Plugin Js -->
<script src="{{asset("assets/bsb/plugins/multi-select/js/jquery.multi-select.js")}}"></script>
<script src="{{asset("assets/bsb/js/pages/forms/advanced-form-elements.js")}}"></script>
@endsection