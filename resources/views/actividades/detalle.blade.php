@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Detalle Tarea Entregada
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
                            <h6>{{$detalle->PRY_Nombre_Proyecto}}</h6>
                            <p>{{$detalle->RLS_Nombre_Rol}}</p>
                            <p>{{$detalle->USR_Nombres_Usuario.' '.$detalle->USR_Apellidos_Usuario}}</p>
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
                            <h6>{{$detalle->REQ_Nombre_Requerimiento}}</h6>
                            <p>{{$detalle->REQ_Descripcion_Requerimiento}}</p>
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
                            <h6>{{$detalle->ACT_Nombre_Actividad}}</h6>
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
                                            <a href="{{route('descargar_documento_actividad_validador', ['ruta'=>$documento->ACT_Documento_Soporte_Actividad])}}"
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
                            <h6>{{$detalle->ACT_FIN_Titulo}}</h6>
                            <p>Descripción: {{$detalle->ACT_FIN_Descripcion}}</p>
                            <p>{{$perfil->USR_Nombres_Usuario.' '.$perfil->USR_Apellidos_Usuario}}</p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                @if ($detalle->ACT_FIN_Link != null)
                                    <a href="{{$detalle->ACT_FIN_Link}}" target="_blank">Ir a la evidencia</a>
                                @endif
                                @foreach ($documentosEvidencia as $documento)
                                    <span>Evidencias</span>
                                    <span>
                                        <a href="{{route('descargar_documento_actividad_validador', ['ruta'=>$documento->ACT_Documento_Evidencia_Actividad])}}"
                                            class="btn bg-cyan btn-block btn-xs waves-effect">
                                            <i class="material-icons"
                                                style="font-size: 17px;">file_download</i>
                                        </a>
                                    </span>
                                @endforeach
                            </li>
                            <li>
                                <a class="btn btn-danger waves-effect" href="{{route('actividades', ['idR'=>$detalle->ACT_Requerimiento_Id])}}">
                                    <i class="material-icons" style="color:white;">keyboard_backspace</i>VOLVER A TAREAS
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection