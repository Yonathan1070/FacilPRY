<input type="hidden" name="ACT_Proyecto_Id" id="ACT_Proyecto_Id"
    value="{{old('ACT_Proyecto_Id', $proyecto->id ?? '')}}">
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="ACT_Nombre_Actividad" id="ACT_Nombre_Actividad"
            value="{{old('ACT_Nombre_Actividad', $actividad->ACT_Nombre_Actividad ?? '')}}" maxlength="30" required>
        <label class="form-label">Nombre de la Actividad</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="ACT_Descripcion_Actividad" id="ACT_Descripcion_Actividad" cols="30" rows="5"
            class="form-control no-resize" maxlength="100"
            required>{{old('ACT_Descripcion_Actividad', $actividad->ACT_Descripcion_Actividad ?? '')}}</textarea>
        <label class="form-label">Descripción de la Actividad</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line focused">
        <input type="file" class="form-control " name="ACT_Documento_Soporte_Actividad[]"
            id="ACT_Documento_Soporte_Actividad" multiple>
        <label class="form-label">Documento Soporte</label>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line focused" id="bs_datepicker_container">
                <input type="date" class="form-control" name="ACT_Fecha_Inicio_Actividad"
                    id="ACT_Fecha_Inicio_Actividad"
                    value="{{old('ACT_Fecha_Inicio_Actividad', $actividad->ACT_Fecha_Inicio_Actividad ?? '')}}"
                    required>
                <label class="form-label">Fecha Inicio</label>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" class="form-control" name="ACT_Fecha_Fin_Actividad" id="ACT_Fecha_Fin_Actividad"
                    value="{{old('ACT_Fecha_Fin_Actividad', $actividad->ACT_Fecha_Fin_Actividad ?? '')}}" required>
                <label class="form-label">Fecha Finalización</label>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
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
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line focused">
                <select name="ACT_Requerimiento_Id" id="ACT_Requerimiento_Id" class="form-control show-tick" data-live-search="true" data-show-subtext="true" required>
                    <option value="">-- Seleccione un Requerimiento --</option>
                    @foreach ($requerimientos as $requerimiento)
                    <option value="{{$requerimiento->id}}" data-subtext="{{$requerimiento->REQ_Descripcion_Requerimiento}}">
                        {{$requerimiento->REQ_Nombre_Requerimiento}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>