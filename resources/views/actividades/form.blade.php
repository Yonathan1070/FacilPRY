<div class="form-group form-float">
    <div class="form-line focused">
        <input type="text" class="form-control" name="ACT_Nombre_Actividad_Trabajador" id="ACT_Nombre_Actividad_Trabajador"
            maxlength="30" required>
        <label class="form-label">Nombre de la Tarea</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line focused">
        <textarea name="ACT_Descripcion_Actividad_Trabajador" id="ACT_Descripcion_Actividad_Trabajador" cols="30" rows="5"
            class="form-control no-resize"
            required></textarea>
        <label class="form-label">Descripción de la Tarea</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line focused">
        <input type="file" class="form-control " name="ACT_Documento_Soporte_Actividad_Trabajador[]"
            id="ACT_Documento_Soporte_Actividad_Trabajador" accept=".txt,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.ai" multiple>
        <label class="form-label">Documento Soporte</label>
    </div>
    <div class="help-info">Tamaño máximo del archivo: 10 Mb</div>
</div>
<div class="row clearfix">
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="ACT_Fecha_Inicio_Actividad_Trabajador"
                    id="ACT_Fecha_Inicio_Actividad_Trabajador"
                    required>
            </div>
            <div class="help-info">Fecha de Inicio</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="ACT_Fecha_Fin_Actividad_Trabajador" id="ACT_Fecha_Fin_Actividad_Trabajador" required>
            </div>
            <div class="help-info">Fecha de Entrega</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="text" class="timepicker form-control" name="ACT_Hora_Entrega_Trabajador" id="ACT_Hora_Entrega_Trabajador" required>
            </div>
            <div class="help-info">Hora de Entrega</div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="form-group form-float">
            <div class="form-line focused">
                <select name="ACT_Usuario_Id_Trabajador" id="ACT_Usuario_Id_Trabajador" class="form-control show-tick" data-live-search="true" required>
                    <option value="">-- Seleccione un Trabajador --</option>
                    @foreach ($perfilesOperacion as $perfilOperacion)
                        <option value="{{$perfilOperacion->Id_Perfil}}">
                            {{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>