<div class="form-group form-float">
    <div class="form-line focused">
        <select name="ClienteSelect" id="ClienteSelect" class="form-control show-tick" data-live-search="true"
            required>
            <option value="">-- Seleccione un Cliente --</option>
            @foreach ($clientes as $cliente)
                <option value="{{$cliente->id}}">
                    {{$cliente->USR_Nombres_Usuario.' '.$cliente->USR_Apellidos_Usuario}}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="proyectos" id="proyectos"></div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="FACT_AD_Descripcion" id="FACT_AD_Descripcion" cols="30" rows="5"
            class="form-control no-resize"
            required>{{old('FACT_AD_Descripcion')}}</textarea>
        <label class="form-label">Descripción del cobro</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <input type="number" class="form-control" name="FACT_AD_Costo" id="FACT_AD_Costo"
            value="{{old('FACT_AD_Costo')}}" min="1000" required>
        <label class="form-label">Valor a pagar</label>
    </div>
</div>