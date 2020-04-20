@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Editar Datos Empresa 
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
                                    <input type="hidden" name="id" value="{{$datos->USR_Empresa_Id}}">
                                    <input type="file" id="EMP_Logo_Empresa"/>
                                </form>
                                @if ($datos->EMP_Logo_Empresa==null)
                                        <img id="logoEmpresa" src="{{asset("assets/bsb/images/Logos/defecto.png")}}" width="128" height="128" alt="InkBrutalPRY - Logo Empresa" />
                                @else
                                        <img id="logoEmpresa" src="{{asset('/assets/bsb/images/Logos/'.$datos->EMP_Logo_Empresa)}}" width="128" height="128" alt="InkBrutalPRY - Logo Empresa" />
                                @endif
                                <div class="text">Cambiar Foto</div>
                            </div>
                            <div class="content-area">
                                <h3>{{$datos->EMP_Nombre_Empresa}}</h3>
                                <p>{{$datos->EMP_NIT_Empresa}}</p>
                                <p>{{$datos->EMP_Correo_Empresa}}</p>
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
                                    <li role="presentation" class="active"><a href="#actualizar" aria-controls="settings" role="tab" data-toggle="tab">Editar Datos Empresa</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="actualizar">
                                        <form class="form-horizontal" id="form_validation" action="{{route('actualizar_empresa_administrador')}}" method="POST">
                                            @csrf @method("put")
                                            <input type="hidden" name="id" value="{{$datos->USR_Empresa_Id}}">
                                            <div class="form-group">
                                                <label for="EMP_NIT_Empresa" class="col-sm-3 control-label">NIT</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="EMP_NIT_Empresa" name="EMP_NIT_Empresa" placeholder="NIT" value="{{$datos->EMP_NIT_Empresa}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="EMP_Telefono_Empresa" class="col-sm-3 control-label">Telefono</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="EMP_Telefono_Empresa" name="EMP_Telefono_Empresa" placeholder="Telefono" value="{{$datos->EMP_Telefono_Empresa}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="EMP_Direccion_Empresa" class="col-sm-3 control-label">Dirección</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="EMP_Direccion_Empresa" name="EMP_Direccion_Empresa" placeholder="Dirección" value="{{$datos->EMP_Direccion_Empresa}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="EMP_Correo_Empresa" class="col-sm-3 control-label">Correo Electrónico</label>
                                                <div class="col-sm-9">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="EMP_Correo_Empresa" name="EMP_Correo_Empresa" placeholder="Correo Electrónico" value="{{$datos->EMP_Correo_Empresa}}" required>
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
                var $logoEmpresa, $EMP_Logo_Empresa, $fotoForm;
    
        $logoEmpresa = $('#logoEmpresa');
        $EMP_Logo_Empresa = $('#EMP_Logo_Empresa');
        $fotoForm = $('#fotoForm');
    
        $logoEmpresa.on('click', function () {
            $EMP_Logo_Empresa.click();
        });
    
        $EMP_Logo_Empresa.on('change', function () {
            form = new FormData();
            form.append('EMP_Logo_Empresa', $('#EMP_Logo_Empresa')[0].files[0]);
            jQuery.ajax({
                url:"/administrador/empresa/foto",
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