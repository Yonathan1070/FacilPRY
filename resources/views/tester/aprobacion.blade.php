@extends('theme.bsb.tester.layout')
@section('titulo')
Inicio
@endsection
@section('contenido')
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-6">
                <a href="{{route('respuestaA_tester', ['id' => $actividadesPendientes->Id_Act_Fin])}}" class="btn bg-green btn-block btn-xs waves-effect">APROBAR</a>
            </div>
            <div class="col-lg-6">
                <a href="{{route('respuestaR_tester', ['id' => $actividadesPendientes->Id_Act_Fin])}}" class="btn bg-red btn-block btn-xs waves-effect">RECHAZAR</a>
            </div>
        </div>
        <div class="row"><br></div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-3">
                <div class="card profile-card">
                    <div class="header">
                        <h2>PROYECTO</h2>
                    </div>
                    <div class="profile-body">
                        <div class="content-area">
                            <h6>{{$actividadesPendientes->PRY_Nombre_Proyecto}}</h6>
                            <p>{{$actividadesPendientes->RLS_Nombre}}</p>
                            <p>{{$actividadesPendientes->USR_Nombre.' '.$actividadesPendientes->USR_Apellido}}</p>
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
                            <p>{{$perfil->RLS_Nombre}}</p>
                            <p>{{$perfil->USR_Nombre.' '.$perfil->USR_Apellido}}</p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                @if ($actividadesPendientes->ACT_Documento_Soporte_Actividad!=null)
                                    <span>Documento</span>
                                    <span>
                                        <a href="{{route('descargar_documento_actividad_tester', ['ruta'=>$actividadesPendientes->ACT_Documento_Soporte_Actividad])}}"
                                            class="btn bg-cyan btn-block btn-xs waves-effect">
                                            <i class="material-icons"
                                                style="font-size: 17px;">file_download</i>
                                        </a>
                                    </span>
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
                            <p>Descripción: {{$actividadesPendientes->ACT_FIN_Descripcion}}</p>
                            <p>{{$perfil->USR_Nombre.' '.$perfil->USR_Apellido}}</p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                <span>Evidencias</span>
                                <span>
                                    <a href="{{route('descargar_documento_actividad_tester', ['ruta'=>$actividadesPendientes->ACT_FIN_Documento_Soporte])}}"
                                        class="btn bg-cyan btn-block btn-xs waves-effect">
                                        <i class="material-icons"
                                            style="font-size: 17px;">file_download</i>
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection