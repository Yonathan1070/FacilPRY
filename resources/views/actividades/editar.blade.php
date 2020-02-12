@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Crud Requerimientos
@endsection
@section('styles')
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{asset('assets/bsb/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="{{asset('assets/bsb/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css')}}" rel="stylesheet" />
@endsection
@section('contenido')
<div class="container-fluid">
    <!-- Basic Validation -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>EDITAR ACTIVIDAD</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('actividades', ['idR'=>$actividad->ACT_Requerimiento_Id])}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver al listado
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" enctype="multipart/form-data" action="{{route('actualizar_actividad', ['idA' => $actividad->id])}}" method="POST">
                        @csrf @method("put")
                        @include('actividades.form')
                        <a class="btn btn-danger waves-effect" href="{{route('actividades', ['idR'=>$actividad->ACT_Requerimiento_Id])}}">CANCELAR</a>
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
<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="{{asset("assets/bsb/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js")}}"></script>

<!-- Select Plugin Js -->
<script src="{{asset("assets/bsb/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js")}}"></script>
<!-- Input Mask Plugin Js -->
<script src="{{asset("assets/bsb/plugins/jquery-inputmask/jquery.inputmask.bundle.js")}}"></script>
<script src="{{asset("assets/bsb/plugins/bootstrap-select/js/i18n/defaults-es_CL.js")}}"></script>
<script src="{{asset("assets/bsb/js/pages/forms/basic-form-elements.js")}}"></script>
@endsection