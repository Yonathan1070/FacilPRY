@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Cobros
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="col-xs-12 col-sm-3">
        @include('includes.form-exito')
        @include('includes.form-error')
        <div class="card profile-card">
            <div class="header">
                <h2>PROYECTO</h2>
            </div>
            <div class="profile-body">
                <div class="content-area">
                    <h6>{{$actividades->PRY_Nombre_Proyecto}}</h6>
                    <p>{{$actividades->RLS_Nombre_Rol}}</p>
                    <p>{{$actividades->USR_Nombres_Usuario.' '.$actividades->USR_Apellidos_Usuario}}</p>
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
                    <h6>{{$actividades->ACT_Nombre_Actividad}}</h6>
                    <p>Descripción: {{$actividades->ACT_Descripcion_Actividad}}</p>
                    <p>{{$actividades->RolT}}</p>
                    <p>{{$actividades->NombreT.' '.$actividades->ApellidoT}}</p>
                </div>
            </div>
            <div class="profile-footer">
                <ul>
                    <li>
                        @foreach ($documentosSoporte as $documento)
                            <span>Soporte</span>
                            <span>
                                <a href="{{route('descargar_documento_actividad_validador', ['ruta'=>$documento->ACT_Documento_Soporte_Actividad])}}"
                                    class="btn bg-cyan btn-block btn-xs waves-effect">
                                    <i class="material-icons"
                                        style="font-size: 17px;">file_download</i>
                                </a>
                            </span>
                        @endforeach
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
                    <p>Descripción: {{$actividades->ACT_FIN_Descripcion}}</p>
                    <p>{{$actividades->NombreT.' '.$actividades->ApellidoT}}</p>
                </div>
            </div>
            <div class="profile-footer">
                <ul>
                    <li>
                        @if ($actividades->ACT_FIN_Link != null)
                            <a href="{{$actividades->ACT_FIN_Link}}" target="_blank">Ir a la evidencia</a>
                        @endif<br/>
                        @foreach ($documentosEvidencias as $documento)
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
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-3">
        <div class="card profile-card">
            <div class="header">
                <h2>COSTO</h2>
            </div>
            <div class="profile-footer">
                <ul>
                    <li>
                        <form id="form_validation" action="{{route('actualizar_costo_actividad_finanzas')}}" method="POST">
                        @csrf @method('put')
                        <div class="form-group form-float">
                            <input type="hidden" name="id" id="id" value="{{$id}}">
                            <div class="form-line">
                                <input type="number" class="form-control" name="ACT_Costo_Actividad" id="ACT_Costo_Actividad"
                                    value="{{old('ACT_Costo_Estimado_Actividad', $actividades->ACT_Costo_Estimado_Actividad ?? '')}}" min="1000" required>
                                <label class="form-label">Costo</label>
                            </div>
                        </div>
                        <button class="btn btn-primary waves-effect" type="submit">GUARDAR</button>
                    </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection