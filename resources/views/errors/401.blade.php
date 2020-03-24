@extends('errors.layout')

@section('titulo')
    401 - No autorizado
@endsection
@section('codigo')
    401
@endsection
@section('mensaje')
    <h2>No autorizado</h2>
    <h3>El recurso solicitado requiere de autenticación de usuario</h3>
@endsection