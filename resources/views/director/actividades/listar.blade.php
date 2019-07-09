@extends('theme.bsb.director.layout')
@section('titulo')
Crud Actividades
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            <div class="card">
                <div class="header">
                    <h2>
                        ACTIVIDADES
                    </h2>
                </div>
                <div class="body">
                    Actividades
                </div>
            </div>
        </div>
    </div>
</div>
@endsection