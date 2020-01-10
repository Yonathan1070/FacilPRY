<input type="hidden" name="id" id="id" value="{{$datos->USR_Empresa_Id}}">
@if (Request::route()->getName() == 'editar_perfil_director')
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Documento_Usuario" id="USR_Documento_Usuario"
                        value="{{old('USR_Documento_Usuario', $perfil->USR_Documento_Usuario ?? '')}}" maxlength="50" required>
                    <label class="form-label">Documento de Identificación</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Nombres_Usuario" id="USR_Nombres_Usuario"
                        value="{{old('USR_Nombres_Usuario', $perfil->USR_Nombres_Usuario ?? '')}}" maxlength="50" required>
                    <label class="form-label">Nombres</label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Apellidos_Usuario" id="USR_Apellidos_Usuario"
                        value="{{old('USR_Apellidos_Usuario', $perfil->USR_Apellidos_Usuario ?? '')}}" maxlength="50" required>
                    <label class="form-label">Apellidos</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line focused">
                    <input type="date" class="form-control" name="USR_Fecha_Nacimiento_Usuario" id="USR_Fecha_Nacimiento_Usuario"
                        value="{{old('USR_Fecha_Nacimiento_Usuario', $perfil->USR_Fecha_Nacimiento_Usuario ?? '')}}" required>
                    <label class="form-label">Fecha de Nacimiento</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Direccion_Residencia_Usuario" id="USR_Direccion_Residencia_Usuario"
                        value="{{old('USR_Direccion_Residencia_Usuario', $perfil->USR_Direccion_Residencia_Usuario ?? '')}}"
                        maxlength="100" required>
                    <label class="form-label">Dirección de Residencia</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Telefono_Usuario" id="USR_Telefono_Usuario"
                        value="{{old('USR_Telefono_Usuario', $perfil->USR_Telefono_Usuario ?? '')}}" maxlength="20" required>
                    <label class="form-label">Telefono de Contacto</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="email" class="form-control" name="USR_Correo_Usuario" id="USR_Correo_Usuario"
                        value="{{old('USR_Correo_Usuario', $perfil->USR_Correo_Usuario ?? '')}}" maxlength="100" required>
                    <label class="form-label">Correo Electrónico</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Nombre_Usuario" id="USR_Nombre_Usuario"
                        value="{{old('USR_Nombre_Usuario', $perfil->USR_Nombre_Usuario ?? '')}}" maxlength="15" required>
                    <label class="form-label">Nombre De Usuario</label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="number" class="form-control" name="USR_Costo_Hora" id="USR_Costo_Hora"
                        value="{{old('USR_Costo_Hora', $perfil->USR_Costo_Hora ?? '')}}" min="0" max="999999" required>
                    <label class="form-label">Valor de la Hora de Trabajo</label>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <select name="USR_Tipo_Documento_Usuario" id="USR_Tipo_Documento_Usuario" class="form-control" required>
                        <option value="">-- Seleccione un tipo de Documento --</option>
                        <option value="Cedula de Ciudadanía">Cedula de Ciudadanía</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Documento_Usuario" id="USR_Documento_Usuario"
                        value="{{old('USR_Documento_Usuario', $perfil->USR_Documento_Usuario ?? '')}}" maxlength="50" required>
                    <label class="form-label">Documento de Identificación</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Nombres_Usuario" id="USR_Nombres_Usuario"
                        value="{{old('USR_Nombres_Usuario', $perfil->USR_Nombres_Usuario ?? '')}}" maxlength="50" required>
                    <label class="form-label">Nombres</label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Apellidos_Usuario" id="USR_Apellidos_Usuario"
                        value="{{old('USR_Apellidos_Usuario', $perfil->USR_Apellidos_Usuario ?? '')}}" maxlength="50" required>
                    <label class="form-label">Apellidos</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-2">
            <div class="form-group form-float">
                <div class="form-line focused">
                    <input type="date" class="form-control" name="USR_Fecha_Nacimiento_Usuario" id="USR_Fecha_Nacimiento_Usuario"
                        value="{{old('USR_Fecha_Nacimiento_Usuario', $perfil->USR_Fecha_Nacimiento_Usuario ?? '')}}" required>
                    <label class="form-label">Fecha de Nacimiento</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Direccion_Residencia_Usuario" id="USR_Direccion_Residencia_Usuario"
                        value="{{old('USR_Direccion_Residencia_Usuario', $perfil->USR_Direccion_Residencia_Usuario ?? '')}}"
                        maxlength="100" required>
                    <label class="form-label">Dirección de Residencia</label>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Ciudad_Residencia_Usuario" id="USR_Ciudad_Residencia_Usuario"
                        value="{{old('USR_Ciudad_Residencia_Usuario', $perfil->USR_Ciudad_Residencia_Usuario ?? '')}}"
                        maxlength="100" required>
                    <label class="form-label">Ciudad de Residencia</label>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Telefono_Usuario" id="USR_Telefono_Usuario"
                        value="{{old('USR_Telefono_Usuario', $perfil->USR_Telefono_Usuario ?? '')}}" maxlength="20" required>
                    <label class="form-label">Telefono de Contacto</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="email" class="form-control" name="USR_Correo_Usuario" id="USR_Correo_Usuario"
                        value="{{old('USR_Correo_Usuario', $perfil->USR_Correo_Usuario ?? '')}}" maxlength="100" required>
                    <label class="form-label">Correo Electrónico</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <select name="USR_RLS_Rol_Id" id="USR_RLS_Rol_Id" class="form-control" required>
                        <option value="">--Seleccione un Rol--</option>
                        @foreach ($roles as $rol)
                            <option value="{{$rol->id}}"> {{$rol->RLS_Nombre_Rol}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Nombre_Usuario" id="USR_Nombre_Usuario"
                        value="{{old('USR_Nombre_Usuario', $perfil->USR_Nombre_Usuario ?? '')}}" maxlength="15" required>
                    <label class="form-label">Nombre De Usuario</label>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="number" class="form-control" name="USR_Costo_Hora" id="USR_Costo_Hora"
                        value="{{old('USR_Costo_Hora', $perfil->USR_Costo_Hora ?? '')}}" min="0" max="999999" required>
                    <label class="form-label">Valor de la Hora de Trabajo</label>
                </div>
            </div>
        </div>
    </div>
@endif