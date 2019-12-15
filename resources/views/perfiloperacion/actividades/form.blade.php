<input type="hidden" name="Actividad_Id" id="Actividad_Id"
    value="{{old('Actividad_Id', $id ?? '')}}">
@if (Request::route()->getName() == 'actividades_finalizar_perfil_operacion')
    <div class="form-group form-float">
        <div class="form-line">
            <input type="text" name="ACT_FIN_Titulo" id="ACT_FIN_Titulo" cols="30" rows="5"
                class="form-control no-resize" maxlength="1000"
                required>
            <label class="form-label">Titulo para la entrega de la Actividad</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <textarea name="ACT_FIN_Descripcion" id="ACT_FIN_Descripcion" cols="30" rows="5"
                class="form-control no-resize" maxlength="1000"
                required>{{old('ACT_FIN_Descripcion', $rol->RLS_Descripcion ?? '')}}</textarea>
            <label class="form-label">Descripción para la entrega de la Actividad</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <input type="url" name="ACT_FIN_Link" id="ACT_FIN_Link" cols="30" rows="5"
                class="form-control no-resize" maxlength="1000">
            <label class="form-label">Link de la entrega</label>
        </div>
        <div class="help-info">Inicia con http://, https://, ftp:// etc</div>
    </div>
    <div class="form-group form-float">
        <div class="form-line focused">
            <input type="file" class="form-control " name="ACT_Documento_Evidencia_Actividad[]"
                id="ACT_Documento_Evidencia_Actividad" accept=".txt,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.ai" multiple>
            <label class="form-label">Documento Soporte de Actividad Terminada</label>
        </div>
        <div class="help-info">Tamaño máximo del archivo: 10 Mb</div>
    </div>
@else
    <input type="hidden" name="Horas_Restantes" id="Horas_Restantes"
        value="{{old('Horas_Restantes', $horasRestantes ?? '')}}">
    <div class="form-group form-float">
        <div class="form-line">
            <label>Horas para la entrega de la Actividad:</label><strong> {{$horasRestantes}} Horas</strong>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <input type="number" class="form-control" name="HRS_ACT_Cantidad_Horas" id="HRS_ACT_Cantidad_Horas"
                value="{{old('HRS_ACT_Cantidad_Horas', $actividad->HRS_ACT_Horas ?? '')}}" min="0" required>
            <label class="form-label">Cantidad de Horas Que necesita para completar la actividad</label>
        </div>
    </div>
@endif