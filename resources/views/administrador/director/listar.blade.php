@extends('theme.bsb.administrador.layout')
@section('titulo')
Crud Director de Proyectos
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    <h2>
                        DIRECTOR DE PROYECTOS
                    </h2>
                </div>
                <div class="body">
                    Director de Proyectos
                </div>
            </div>
        </div>
    </div>
</div>
@endsection