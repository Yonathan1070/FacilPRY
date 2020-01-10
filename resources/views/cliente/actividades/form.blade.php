<input type="hidden" name="Actividad_Id" id="Actividad_Id" value="{{old('Actividad_Id', $id ?? '')}}">
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" name="ACT_FIN_Titulo" id="ACT_FIN_Titulo" cols="30" rows="5" class="form-control no-resize"
            maxlength="1000" required>
        <label class="form-label">Titulo para la entrega de la Actividad</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="ACT_FIN_Descripcion" id="ACT_FIN_Descripcion" cols="30" rows="5" class="form-control no-resize"
            required></textarea>
        <label class="form-label">Descripción para la entrega de la Actividad</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line focused">
        <input type="file" class="form-control " name="ACT_Documento_Evidencia_Actividad[]"
            id="ACT_Documento_Evidencia_Actividad" accept=".txt,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.ai" multiple required>
        <label class="form-label">Documento Soporte de Actividad Terminada</label>
    </div>
    <div class="help-info">Tamaño máximo del archivo: 10 Mb</div>
</div>