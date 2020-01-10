<input type="hidden" name="REQ_Proyecto_Id" id="REQ_Proyecto_Id" value="{{old('REQ_Proyecto_Id', $proyecto->id ?? '')}}">
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="REQ_Nombre_Requerimiento" id="REQ_Nombre_Requerimiento"
            value="{{old('REQ_Nombre_Requerimiento', $requerimiento->REQ_Nombre_Requerimiento ?? '')}}" maxlength="60" required>
        <label class="form-label">Nombre de la Actividad</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="REQ_Descripcion_Requerimiento" id="REQ_Descripcion_Requerimiento" cols="30" rows="5"
            class="form-control no-resize"
            required>{{old('REQ_Descripcion_Requerimiento', $requerimiento->REQ_Descripcion_Requerimiento ?? '')}}</textarea>
        <label class="form-label">Descripción de la Actividad</label>
    </div>
</div>