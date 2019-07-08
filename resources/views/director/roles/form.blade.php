<div class="form-group form-float">
        <div class="form-line">
            <input type="text" class="form-control" name="RLS_Nombre" id="RLS_Nombre" value="{{old('RLS_Nombre', $rol->RLS_Nombre ?? '')}}" maxlength="30"
                required>
            <label class="form-label">Nombre de Rol</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <textarea name="RLS_Descripcion" id="RLS_Descripcion" cols="30" rows="5" class="form-control no-resize"
                maxlength="100" required>{{old('RLS_Descripcion', $rol->RLS_Descripcion ?? '')}}</textarea>
            <label class="form-label">Descripción del Rol</label>
        </div>
    </div>