@if (Request::route()->getName() == 'editar_director_administrador')
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Documento" id="USR_Documento"
                        value="{{old('USR_Documento', $director->USR_Documento ?? '')}}" maxlength="50" required>
                    <label class="form-label">Documento de Identificación</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Nombre" id="USR_Nombre"
                        value="{{old('USR_Nombre', $director->USR_Nombre ?? '')}}" maxlength="50" required>
                    <label class="form-label">Nombres</label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Apellido" id="USR_Apellido"
                        value="{{old('USR_Apellido', $director->USR_Apellido ?? '')}}" maxlength="50" required>
                    <label class="form-label">Apellidos</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line focused">
                    <input type="date" class="form-control" name="USR_Fecha_Nacimiento" id="USR_Fecha_Nacimiento"
                        value="{{old('USR_Fecha_Nacimiento', $director->USR_Fecha_Nacimiento ?? '')}}" required>
                    <label class="form-label">Fecha de Nacimiento</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Direccion_Residencia" id="USR_Direccion_Residencia"
                        value="{{old('USR_Direccion_Residencia', $director->USR_Direccion_Residencia ?? '')}}"
                        maxlength="100" required>
                    <label class="form-label">Dirección de Residencia</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Telefono" id="USR_Telefono"
                        value="{{old('USR_Telefono', $director->USR_Telefono ?? '')}}" maxlength="20" required>
                    <label class="form-label">Telefono de Contacto</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="email" class="form-control" name="USR_Correo" id="USR_Correo"
                        value="{{old('USR_Correo', $director->USR_Correo ?? '')}}" maxlength="100" required>
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
                        value="{{old('USR_Nombre_Usuario', $director->USR_Nombre_Usuario ?? '')}}" maxlength="15" required>
                    <label class="form-label">Nombre De Usuario</label>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <select name="USR_Tipo_Documento" id="USR_Tipo_Documento" class="form-control" required>
                        <option value="">-- Seleccione un tipo de Documento --</option>
                        <option value="Cedula de Ciudadanía">Cedula de Ciudadanía</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Documento" id="USR_Documento"
                        value="{{old('USR_Documento', $director->USR_Documento ?? '')}}" maxlength="50" required>
                    <label class="form-label">Documento de Identificación</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Nombre" id="USR_Nombre"
                        value="{{old('USR_Nombre', $director->USR_Nombre ?? '')}}" maxlength="50" required>
                    <label class="form-label">Nombres</label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Apellido" id="USR_Apellido"
                        value="{{old('USR_Apellido', $director->USR_Apellido ?? '')}}" maxlength="50" required>
                    <label class="form-label">Apellidos</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line focused">
                    <input type="date" class="form-control" name="USR_Fecha_Nacimiento" id="USR_Fecha_Nacimiento"
                        value="{{old('USR_Fecha_Nacimiento', $director->USR_Fecha_Nacimiento ?? '')}}" required>
                    <label class="form-label">Fecha de Nacimiento</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Direccion_Residencia" id="USR_Direccion_Residencia"
                        value="{{old('USR_Direccion_Residencia', $director->USR_Direccion_Residencia ?? '')}}"
                        maxlength="100" required>
                    <label class="form-label">Dirección de Residencia</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="USR_Telefono" id="USR_Telefono"
                        value="{{old('USR_Telefono', $director->USR_Telefono ?? '')}}" maxlength="20" required>
                    <label class="form-label">Telefono de Contacto</label>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="email" class="form-control" name="USR_Correo" id="USR_Correo"
                        value="{{old('USR_Correo', $director->USR_Correo ?? '')}}" maxlength="100" required>
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
                        value="{{old('USR_Nombre_Usuario', $director->USR_Nombre_Usuario ?? '')}}" maxlength="15" required>
                    <label class="form-label">Nombre De Usuario</label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="password" class="form-control" name="password" id="password" maxlength="15" required>
                    <label class="form-label">Contraseña</label>
                </div>
            </div>
        </div>
    </div>
@endif