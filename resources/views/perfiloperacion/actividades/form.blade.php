<input type="hidden" name="HRS_ACT_Actividad_Id" id="HRS_ACT_Actividad_Id"
    value="{{old('HRS_ACT_Actividad_Id', $id ?? '')}}">
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