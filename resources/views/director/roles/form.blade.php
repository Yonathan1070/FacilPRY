<div class="form-group form-float">
        <div class="form-line">
            <input type="text" class="form-control" name="nombre" id="nombre" value="{{old('nombre', $data->nombre ?? '')}}" maxlength="30"
                required>
            <label class="form-label">Nombre de Rol</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <textarea name="descripcion" id="descripcion" cols="30" rows="5" class="form-control no-resize"
                maxlength="100" required>{{old('descripcion', $data->descripcion ?? '')}}</textarea>
            <label class="form-label">Descripción del Rol</label>
        </div>
    </div>