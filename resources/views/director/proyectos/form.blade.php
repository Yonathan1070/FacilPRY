<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="PRY_Nombre_Proyecto" id="PRY_Nombre_Proyecto"
            value="{{old('PRY_Nombre_Proyecto', $proyecto->PRY_Nombre_Proyecto ?? '')}}" maxlength="30" required>
        <label class="form-label">Nombre del Proyecto</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="PRY_Descripcion_Proyecto" id="PRY_Descripcion_Proyecto" cols="30" rows="5" class="form-control no-resize"
            maxlength="100" required>{{old('PRY_Descripcion_Proyecto', $proyecto->PRY_Descripcion_Proyecto ?? '')}}</textarea>
        <label class="form-label">Descripción del Proyecto</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <label style="font-weight: normal">Estado del Proyecto</label>
        <select name="PRY_Estado_Proyecto" id="PRY_Estado_Proyecto" class="form-control" required>
            <option value="">--Seleccione una Opcion--</option>
        </select>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <input type="number" class="form-control" name="PRY_Valor_Proyecto" id="PRY_Valor_Proyecto"
            value="{{old('PRY_Valor_Proyecto', $proyecto->PRY_Valor_Proyecto ?? '')}}" maxlength="30" required>
        <label class="form-label">Costo del Proyecto</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="RLS_Nombre" id="RLS_Nombre"
            value="{{old('RLS_Nombre', $data->RLS_Nombre ?? '')}}" maxlength="30" required>
        <label class="form-label">Nombre de Rol</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="RLS_Nombre" id="RLS_Nombre"
            value="{{old('RLS_Nombre', $data->RLS_Nombre ?? '')}}" maxlength="30" required>
        <label class="form-label">Nombre de Rol</label>
    </div>
</div>