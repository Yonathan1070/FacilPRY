@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Finalizar Actividad
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
                    <h2>FINALIZAR TAREA ({{strtoupper($actividades->ACT_Nombre_Actividad)}})</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('actividades_cliente')}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver al listado
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" action="{{route('actividades_guardar_finalizar_cliente')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('cliente.actividades.form')
                        <a class="btn btn-danger waves-effect" href="{{route('actividades_cliente')}}">CANCELAR</a>
                        <button class="btn btn-primary waves-effect" type="submit">Guardar</button>
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