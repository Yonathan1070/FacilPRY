@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
    Editar Item Menú
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
                    <h2>EDITAR ITEM MENÚ</h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-danger waves-effect" href="{{route('menu')}}">
                                <i class="material-icons" style="color:white;">arrow_back</i> Volver
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <form id="form_validation" action="{{route('actualizar_menu', ['id' => $menu->id])}}" method="POST">
                        @csrf @method('put')
                        @include('administrador.menu.form')
                        <button class="btn btn-primary waves-effect" type="submit">GUARDAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Validation -->
</div>
@endsection