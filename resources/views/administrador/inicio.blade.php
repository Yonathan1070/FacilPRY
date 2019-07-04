@extends('theme.bsb.administrador.layout')
@section('titulo')
    Inicio | Administrador
@endsection
@section('contenido')
    Inicio
@endsection

@section('scripts')
    <!-- Plugin Js para Validaciones -->
    <script src="{{asset("assets/plugins/jquery-validation/jquery.validate.js")}}"></script>
    <!-- Mensajes en español -->
    <script src="{{asset("assets/plugins/jquery-validation/localization/messages_es.js")}}"></script>

    <script src="{{asset("assets/js/pages/examples/sign-in.js")}}"></script>

    <!-- JQuery Steps Plugin Js -->
    <script src="{{asset("assets/plugins/jquery-steps/jquery.steps.js")}}"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="{{asset("assets/plugins/sweetalert/sweetalert.min.js")}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset("assets/plugins/node-waves/waves.js")}}"></script>

    <!-- Custom Js -->
    <script src="{{asset("assets/js/admin.js")}}"></script>
    <script src="{{asset("assets/js/pages/forms/form-validation.js")}}"></script>
@endsection