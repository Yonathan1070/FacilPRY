<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="RLS_Nombre_Rol" id="RLS_Nombre_Rol"
            value="{{old('RLS_Nombre_Rol', $rol->RLS_Nombre_Rol ?? '')}}" maxlength="30" required>
        <label class="form-label">Nombre del Rol</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
            <textarea name="RLS_Descripcion_Rol" id="RLS_Descripcion_Rol" cols="30" rows="5"
            class="form-control no-resize" maxlength="1000"
            required>{{old('RLS_Descripcion_Rol', $rol->RLS_Descripcion_Rol ?? '')}}</textarea>
        <label class="form-label">Descripción Del Rol</label>
    </div>
</div>