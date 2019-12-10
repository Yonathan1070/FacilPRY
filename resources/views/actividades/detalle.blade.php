@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Inicio
@endsection
@section('contenido')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-3">
                @include('includes.form-exito')
                @include('includes.form-error')
                <div class="card profile-card">
                    <div class="header">
                        <h2>PROYECTO</h2>
                    </div>
                    <div class="profile-body">
                        <div class="content-area">
                            <h6>{{$actividadesPendientes->PRY_Nombre_Proyecto}}</h6>
                            <p>{{$actividadesPendientes->RLS_Nombre_Rol}}</p>
                            <p>{{$actividadesPendientes->USR_Nombres_Usuario.' '.$actividadesPendientes->USR_Apellidos_Usuario}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3">
                <div class="card profile-card">
                    <div class="header">
                        <h2>REQUERIMIENTO</h2>
                    </div>
                    <div class="profile-body">
                        <div class="content-area">
                            <h6>{{$actividadesPendientes->REQ_Nombre_Requerimiento}}</h6>
                            <p>{{$actividadesPendientes->REQ_Descripcion_Requerimiento}}</p>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3">
                <div class="card profile-card card-about-me">
                    <div class="header">
                        <h2>ACTIVIDAD ASIGNADA</h2>
                    </div>
                    <div class="profile-body">
                        <div class="content-area">
                            <h6>{{$actividadesPendientes->ACT_Nombre_Actividad}}</h6>
                            <p>Descripción: {{$perfil->ACT_Descripcion_Actividad}}</p>
                            <p>{{$perfil->RLS_Nombre_Rol}}</p>
                            <p>{{$perfil->USR_Nombres_Usuario.' '.$perfil->USR_Apellidos_Usuario}}</p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                @if (count($documentosSoporte)<=0)
                                    <span>No hay documentos para descargar</span>
                                @else
                                    @foreach ($documentosSoporte as $documento)
                                        <span>Documento</span>
                                        <span>
                                            <a href="{{route('descargar_documento_actividad_tester', ['ruta'=>$documento->ACT_Documento_Soporte_Actividad])}}"
                                                class="btn bg-cyan btn-block btn-xs waves-effect">
                                                <i class="material-icons"
                                                    style="font-size: 17px;">file_download</i>
                                            </a>
                                        </span>
                                    @endforeach
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3">
                <div class="card profile-card">
                    <div class="header">
                        <h2>TRABAJO ENTREGADO</h2>
                    </div>
                    <div class="profile-body">
                        <div class="content-area">
                            <h6>{{$actividadesPendientes->ACT_FIN_Titulo}}</h6>
                            <p>Descripción: {{$actividadesPendientes->ACT_FIN_Descripcion}}</p>
                            <p>{{$perfil->USR_Nombres_Usuario.' '.$perfil->USR_Apellidos_Usuario}}</p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                @foreach ($documentosEvidencia as $documento)
                                    <span>Evidencias</span>
                                    <span>
                                        <a href="{{route('descargar_documento_actividad_tester', ['ruta'=>$documento->ACT_Documento_Evidencia_Actividad])}}"
                                            class="btn bg-cyan btn-block btn-xs waves-effect">
                                            <i class="material-icons"
                                                style="font-size: 17px;">file_download</i>
                                        </a>
                                    </span>
                                @endforeach
                            </li>
                            <li>
                                <span>
                                    <a data-toggle="modal" data-target="#modalAprueba" class="btn bg-green btn-xs waves-effect">APROBAR</a>
                                </span>
                                <span>
                                    <a data-toggle="modal" data-target="#modalRechaza" class="btn bg-red btn-xs waves-effect">RECHAZAR</a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection