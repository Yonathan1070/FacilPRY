@extends('theme.bsb.tester.layout')
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
                            <li>
                                <span>
                                    <a href="{{route('respuestaA_tester', ['id' => $actividadesPendientes->Id_Act_Fin])}}" class="btn bg-green btn-xs waves-effect">APROBAR</a>
                                </span>
                                <span>
                                    <a data-toggle="modal" data-target="#smallModal" class="btn bg-red btn-xs waves-effect">RECHAZAR</a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="smallModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="smallModalLabel">Respuesta de Rechazado</h4>
                </div>
                <form action="{{route('respuestaR_tester')}}" id="form_validation" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{$actividadesPendientes->Id_Act_Fin}}">
                    <div class="modal-body">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <textarea name="ACT_FIN_Respuesta" id="ACT_FIN_Respuesta" cols="30" rows="5"
                                    class="form-control no-resize" maxlength="1000"
                                    required>{{old('ACT_FIN_Respuesta', $rol->RLS_Descripcion ?? '')}}</textarea>
                                <label class="form-label">Observaciones</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-link waves-effect">ENVIAR RESPUESTA</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection