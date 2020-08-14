<div class="row clearfix">
    <div class="col-lg-6" id="documento_usuario" style="display:block">
        <div class="form-group form-float">
            <div class="form-line focused">
                <select name="USR_Tipo_Documento_Usuario" id="USR_Tipo_Documento_Usuario" class="form-control" required>
                    <option value="">-- Seleccione un tipo de Documento --</option>
                    <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="form-control" name="USR_Documento_Usuario" id="USR_Documento_Usuario"
                    maxlength="50" required>
                <label class="form-label">Documento de Identificación</label>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="form-control" name="USR_Nombres_Usuario" id="USR_Nombres_Usuario"
                    maxlength="50" required>
                <label class="form-label">Nombres</label>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="form-control" name="USR_Apellidos_Usuario" id="USR_Apellidos_Usuario"
                    maxlength="50" required>
                <label class="form-label">Apellidos</label>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="USR_Fecha_Nacimiento_Usuario"
                    id="USR_Fecha_Nacimiento_Usuario">
                <label class="form-label">Fecha de Nacimiento</label>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-3">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="form-control" name="USR_Direccion_Residencia_Usuario"
                    id="USR_Direccion_Residencia_Usuario" maxlength="100">
                <label class="form-label">Dirección de Residencia</label>
            </div>
        </div>
    </div>
    <div class="col-lg-3" id="ciudad_residencia" style="display:block">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="form-control" name="USR_Ciudad_Residencia_Usuario"
                    id="USR_Ciudad_Residencia_Usuario" maxlength="100">
                <label class="form-label">Ciudad de Residencia</label>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="form-control" name="USR_Telefono_Usuario" id="USR_Telefono_Usuario"
                    maxlength="20" required>
                <label class="form-label">Telefono de Contacto</label>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="email" class="form-control" name="USR_Correo_Usuario" id="USR_Correo_Usuario"
                    maxlength="100" required>
                <label class="form-label">Correo Electrónico</label>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="form-control" name="USR_Nombre_Usuario" id="USR_Nombre_Usuario" maxlength="15"
                    required>
                <label class="form-label">Nombre de Usuario</label>
            </div>
        </div>
    </div>
    <div class="col-lg-4" id="roles_director" style="display:block">
        <select class="form-control show-tick" multiple required>
            @foreach ($roles as $id => $rol)
            <option value="{{$id}}">{{$rol->RLS_Nombre_Rol}}</option>
            @endforeach
        </select>
        <label class="form-label">Rol(es) Del Usuario</label>
    </div>
    <div class="col-lg-4">
        <a id="password_director" style="display:none" class="reset-password">
            Reestablecer Contraseña
        </a>
    </div>
</div>
<input type="hidden" id="director_id" name="director_id" value="0">