<input type="hidden" name="ACT_Proyecto_Id" id="ACT_Proyecto_Id"
    value="{{old('ACT_Proyecto_Id', $proyecto->id ?? '')}}">
<input type="hidden" name="ruta" value="{{Request::route()->getName()}}">
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="ACT_Nombre_Actividad" id="ACT_Nombre_Actividad"
            value="{{old('ACT_Nombre_Actividad', $actividad->ACT_Nombre_Actividad ?? '')}}" maxlength="30" required>
        <label class="form-label">Nombre de la Tarea</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="ACT_Descripcion_Actividad" id="ACT_Descripcion_Actividad" cols="30" rows="5"
            class="form-control no-resize"
            required>{{old('ACT_Descripcion_Actividad', $actividad->ACT_Descripcion_Actividad ?? '')}}</textarea>
        <label class="form-label">Descripción de la Tarea</label>
    </div>
</div>
@if (Request::route()->getName() == 'crear_actividad_trabajador')
    <div class="form-group form-float">
        <div class="form-line focused">
            <input type="file" class="form-control " name="ACT_Documento_Soporte_Actividad[]"
                id="ACT_Documento_Soporte_Actividad" accept=".txt,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.ai" multiple>
            <label class="form-label">Documento Soporte</label>
        </div>
        <div class="help-info">Tamaño máximo del archivo: 10 Mb</div>
    </div>
@endif
<br/>
<div class="row clearfix">
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="ACT_Fecha_Inicio_Actividad"
                    id="ACT_Fecha_Inicio_Actividad"
                    value="{{old('ACT_Fecha_Inicio_Actividad', $actividad->ACT_Fecha_Inicio_Actividad ?? '')}}"
                    required>
            </div>
            <div class="help-info">Fecha de Inicio</div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="ACT_Fecha_Fin_Actividad" id="ACT_Fecha_Fin_Actividad"
                    value="{{old('ACT_Fecha_Fin_Actividad', $actividad->ACT_Fecha_Fin_Actividad ?? '')}}" required>
            </div>
            <div class="help-info">Fecha de Entrega</div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="timepicker form-control" name="ACT_Hora_Entrega" id="ACT_Hora_Entrega"
                    value="{{old('ACT_Hora_Entrega' ?? '')}}" required>
            </div>
            <div class="help-info">Hora de Entrega</div>
        </div>
    </div>
</div>
<div class="row clearfix">
    @if (Request::route()->getName() == 'crear_actividad_trabajador')
        <div class="col-lg-6">
            <div class="form-group form-float">
                <div class="form-line focused">
                    <select name="ACT_Usuario_Id" id="ACT_Usuario_Id" class="form-control show-tick" data-live-search="true"
                        required>
                        <option value="">-- Seleccione un Trabajador --</option>
                        @foreach ($perfilesOperacion as $perfilOperacion)
                        <option value="{{$perfilOperacion->id}}">
                            {{$perfilOperacion->USR_Nombres_Usuario.' '.$perfilOperacion->USR_Apellidos_Usuario}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif
</div>