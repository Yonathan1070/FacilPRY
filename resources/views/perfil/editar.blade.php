@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Editar Perfil 
@endsection
@section('contenido')
<div class="container-fluid">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-4">
                <div class="card profile-card">
                    <div class="profile-header">&nbsp;</div>
                    <div class="profile-body">
                        <div class="image-area content_img">
                            <form action="" method="post" style="display: none" id="fotoForm" name="fotoForm" enctype="multipart/form-data">
                                <input type="file" id="USR_Foto_Perfil_Usuario"/>
                            </form>
                                @if ($datos->USR_Foto_Perfil_Usuario==null)
                                    <img id="fotoPerfil" src="{{asset("assets/bsb/images/user-lg.ico")}}" alt="AdminBSB - Profile Image" />
                                @else
                                    <img id="fotoPerfil" src="{{route('foto_perfil')}}" width="128" height="128" alt="AdminBSB - Profile Image" />
                                @endif
                                <div class="text">Cambiar Foto</div>
                        </div>
                        <div class="content-area">
                            <h3>{{$datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario}}</h3>
                            <p>{{$datos->USR_Correo_Usuario}}</p>
                            <p>{{session()->get('Rol_Nombre')}}</p>
                        </div>
                        @include('includes.form-exito')
                        @include('includes.form-error')
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="card">
                    <div class="body">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#actualizar" aria-controls="settings" role="tab" data-toggle="tab">Editar Perfil</a></li>
                                <li role="presentation"><a href="#cambio_clave" aria-controls="settings" role="tab" data-toggle="tab">Cambiar Contraseña</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="actualizar">
                                    <form class="form-horizontal" id="form_validation" action="{{route('actualizar_perfil')}}" method="POST">
                                        @csrf @method("put")
                                        <div class="form-group">
                                            <label for="USR_Documento_Usuario" class="col-sm-3 control-label">Documento</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="USR_Documento_Usuario" name="USR_Documento_Usuario" placeholder="Documento" value="{{$datos->USR_Documento_Usuario}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="USR_Nombres_Usuario" class="col-sm-3 control-label">Nombre</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="USR_Nombres_Usuario" name="USR_Nombres_Usuario" placeholder="Nombres" value="{{$datos->USR_Nombres_Usuario}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="s_Usuario" class="col-sm-3 control-label">Apellido</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="USR_Apellidos_Usuario" name="USR_Apellidos_Usuario" placeholder="Apellidos" value="{{$datos->USR_Apellidos_Usuario}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="USR_Direccion_Residencia_Usuario" class="col-sm-3 control-label">Dirección</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="USR_Direccion_Residencia_Usuario" name="USR_Direccion_Residencia_Usuario" placeholder="Dirección de Residencia" value="{{$datos->USR_Direccion_Residencia_Usuario}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="USR_Telefono_Usuario" class="col-sm-3 control-label">Telefono</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="USR_Telefono_Usuario" name="USR_Telefono_Usuario" placeholder="Telefono de Contacto" value="{{$datos->USR_Telefono_Usuario}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="USR_Correo_Usuario" class="col-sm-3 control-label">Correo Electrónico</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="email" class="form-control" id="USR_Correo_Usuario" name="USR_Correo_Usuario" placeholder="Correo Electrónico" value="{{$datos->USR_Correo_Usuario}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-info">GUARDAR</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="cambio_clave">
                                    <form class="form-horizontal" id="form_validation_trabajador" action="{{route('actualizar_clave_perfil')}}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="form-group">
                                            <label for="USR_Clave_Anterior" class="col-sm-4 control-label">Antigua Contraseña</label>
                                            <div class="col-sm-8">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="USR_Clave_Anterior" name="USR_Clave_Anterior" placeholder="Antigua Contraseña" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="USR_Clave_Nueva" class="col-sm-4 control-label">Nueva Contraseña</label>
                                            <div class="col-sm-8">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="USR_Clave_Nueva" name="USR_Clave_Nueva" placeholder="Nueva Contraseña" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="USR_Clave_Confirmar" class="col-sm-4 control-label">Nueva Contraseña (Confirmar)</label>
                                            <div class="col-sm-8">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="USR_Clave_Confirmar" name="USR_Clave_Confirmar" placeholder="Nueva Contraseña (Confirmar)" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-4 col-sm-8">
                                                <button type="submit" class="btn btn-danger">ACTUALIZAR CONTRASEÑA</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function () {
                var $fotoPerfil, $USR_Foto_Perfil, $fotoForm;
    
        $fotoPerfil = $('#fotoPerfil');
        $USR_Foto_Perfil = $('#USR_Foto_Perfil_Usuario');
        $fotoForm = $('#fotoForm');
    
        $fotoPerfil.on('click', function () {
            $USR_Foto_Perfil.click();
        });
    
        $USR_Foto_Perfil.on('change', function () {
            form = new FormData();
            form.append('USR_Foto_Perfil_Usuario', $('#USR_Foto_Perfil_Usuario')[0].files[0]);
            jQuery.ajax({
                url:"/perfilfoto",
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                },
 
                data:form,
                method:"POST",
                processData: false,
                contentType: false,
                success:function(data){  
                    location.reload();
                },
                error:function(error){
                    console.log(error);
                }
            });
        });
    });
    </script>
@endsection