@extends('theme.bsb.administrador.layout')
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
                            <div class="image-area">
                                <form action="" method="post" style="display: none" id="fotoForm" name="fotoForm" enctype="multipart/form-data">
                                    <input type="file" id="USR_Foto_Perfil"/>
                                </form>
                                @if ($datos->USR_Foto_Perfil==null)
                                        <img id="fotoPerfil" src="{{asset("assets/bsb/images/user-lg.ico")}}" alt="AdminBSB - Profile Image" />
                                @else
                                        <img id="fotoPerfil" src="{{asset('/assets/bsb/images/'.$datos->USR_Foto_Perfil)}}" width="128" height="128" alt="AdminBSB - Profile Image" />
                                @endif
                            </div>
                            <div class="content-area">
                                <h3>{{$datos->USR_Nombre.' '.$datos->USR_Apellido}}</h3>
                                <p>{{$datos->USR_Correo}}</p>
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
                                        <form class="form-horizontal" id="form_validation" action="{{route('actualizar_perfil_administrador')}}" method="POST">
                                            @csrf @method("put")
                                            <div class="form-group">
                                                <label for="USR_Documento" class="col-sm-3 control-label">Documento</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="USR_Documento" name="USR_Documento" placeholder="Documento" value="{{$datos->USR_Documento}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="USR_Nombre" class="col-sm-3 control-label">Nombre</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="USR_Nombre" name="USR_Nombre" placeholder="Nombres" value="{{$datos->USR_Nombre}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="USR_Apellido" class="col-sm-3 control-label">Apellido</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="USR_Apellido" name="USR_Apellido" placeholder="Apellidos" value="{{$datos->USR_Apellido}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="USR_USR_Direccion_Residencia" class="col-sm-3 control-label">Dirección</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="USR_USR_Direccion_Residencia" name="USR_USR_Direccion_Residencia" placeholder="Dirección de Residencia" value="{{$datos->USR_Direccion_Residencia}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="USR_Telefono" class="col-sm-3 control-label">Telefono</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="USR_Telefono" name="USR_Telefono" placeholder="Telefono de Contacto" value="{{$datos->USR_Telefono}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="USR_Correo" class="col-sm-3 control-label">Correo Electrónico</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="email" class="form-control" id="USR_Correo" name="USR_Correo" placeholder="Correo Electrónico" value="{{$datos->USR_Correo}}" required>
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
                                        <form class="form-horizontal" id="form_validation" action="{{route('actualizar_clave_administrador')}}" method="POST">
                                            @csrf @method("put")
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
        $USR_Foto_Perfil = $('#USR_Foto_Perfil');
        $fotoForm = $('#fotoForm');
    
        $fotoPerfil.on('click', function () {
            $USR_Foto_Perfil.click();
        });
    
        $USR_Foto_Perfil.on('change', function () {
            form = new FormData();
            form.append('USR_Foto_Perfil', $('#USR_Foto_Perfil')[0].files[0]);
            jQuery.ajax({
                url:"{{route('actualizar_foto_administrador')}}",
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