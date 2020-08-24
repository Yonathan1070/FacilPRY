<div class="form-group form-float">
    <div class="form-line focused">
        <input type="text" class="form-control" name="ACT_Nombre_Actividad" id="ACT_Nombre_Actividad"
            maxlength="30" required>
        <label class="form-label">Nombre de la Tarea</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line focused">
        <textarea name="ACT_Descripcion_Actividad" id="ACT_Descripcion_Actividad" cols="30" rows="5"
            class="form-control no-resize"
            required></textarea>
        <label class="form-label">Descripción de la Tarea</label>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="ACT_Fecha_Inicio_Actividad"
                    id="ACT_Fecha_Inicio_Actividad"
                    required>
            </div>
            <div class="help-info">Fecha de Inicio</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="ACT_Fecha_Fin_Actividad" id="ACT_Fecha_Fin_Actividad"
                    required>
            </div>
            <div class="help-info">Fecha de Entrega</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="timepicker form-control" name="ACT_Hora_Entrega" id="ACT_Hora_Entrega"
                    required>
            </div>
            <div class="help-info">Hora de Entrega</div>
        </div>
    </div>
</div>