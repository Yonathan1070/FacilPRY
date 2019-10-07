@extends('theme.bsb.'.strtolower(session()->get('Rol_Id')).'.layout')
@section('titulo')
    Inicio
@endsection
@section('contenido')
    @include('includes.form-exito')
    @include('includes.form-error')
    Inicio
@endsection

