@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Cobro Adicional
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
                        ASIGNAR COSTOS
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('inicio_finanzas')}}">
                                <i class="material-icons" style="color:white;">keyboard_backspace</i> Volver a finanzas
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" enctype="multipart/form-data" action="{{route('guardar_adicional')}}" method="POST">
                        @csrf
                        @include('finanzas.form')
                        <a class="btn btn-danger waves-effect" href="{{route('inicio_finanzas')}}">CANCELAR</a>
                        <button class="btn btn-primary waves-effect" type="submit">GUARDAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset("assets/pages/scripts/Director/selectProyectos.js")}}"></script>
@endsection