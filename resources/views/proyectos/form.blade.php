<input type="hidden" name="PRY_Empresa_Id" id="PRY_Empresa_Id" value="{{$empresa->id}}">
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="PRY_Nombre_Proyecto" id="PRY_Nombre_Proyecto"
            maxlength="30" required>
        <label class="form-label">Nombre del Proyecto</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <textarea name="PRY_Descripcion_Proyecto" id="PRY_Descripcion_Proyecto" cols="30" rows="5"
            class="form-control no-resize"
            required></textarea>
        <label class="form-label">Descripción del Proyecto</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <select name="PRY_Cliente_Id" id="PRY_Cliente_Id" class="form-control" required>
            <option value="">--Seleccione un Cliente--</option>
            @foreach ($clientes as $cliente)
            <option value="{{$cliente->id}}"> {{$cliente->USR_Nombres_Usuario.' '.$cliente->USR_Apellidos_Usuario}}</option>
            @endforeach
        </select>
    </div>
</div>