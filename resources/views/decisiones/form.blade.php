<input type="hidden" name="DSC_Empresa_Id" id="DSC_Empresa_Id" value="{{session()->get('Empresa_Id')}}">
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="DCS_Nombre_Decision" id="DCS_Nombre_Decision"
            value="{{old('DCS_Nombre_Decision', $decision->DCS_Nombre_Decision ?? '')}}" maxlength="60" required>
        <label class="form-label">Nombre De La Decisión</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="DCS_Descripcion_Decision" id="DCS_Descripcion_Decision" cols="30" rows="5"
            class="form-control no-resize"
            required>{{old('DCS_Descripcion_Decision', $decision->DCS_Descripcion_Decision ?? '')}}</textarea>
        <label class="form-label">Descripción De La Decisión</label>
    </div>
</div>
<!--<div class="form-group form-float">
    <div class="form-line">
        <select name="DSC_Indicador_Id" id="DSC_Indicador_Id" class="form-control" data-live-search="true" data-show-subtext="true" onchange="mostrarBarra(this)" required>
            <option value="">--Seleccione un Indicador--</option>
            @foreach ($indicadores as $indicador)
                <option value="{{$indicador->id}}" data-subtext="{{$indicador->INDC_Descripcion_Indicador}}">
                    {{$indicador->INDC_Nombre_Indicador}}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div id="progressBar"></div>-->
<div class="row clearfix">
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="number" class="form-control" name="DCS_Rango_Inicio_Decision" id="DCS_Rango_Inicio_Decision"
                    value="{{old('DCS_Rango_Inicio_Decision', $decision->DCS_Rango_Inicio_Decision ?? '')}}" min="0" max="100" required>
                <label class="form-label">Rango mínimo de la decisión</label>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="number" class="form-control" name="DCS_Rango_Fin_Decision" id="DCS_Rango_Fin_Decision"
                    value="{{old('DCS_Rango_Fin_Decision', $decision->DCS_Rango_Fin_Decision ?? '')}}" min="0" max="100"
                    required>
                <label class="form-label">Rango máximo de la decisión</label>
            </div>
        </div>
    </div>
</div>