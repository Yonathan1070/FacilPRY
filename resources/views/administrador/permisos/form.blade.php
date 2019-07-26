<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="MN_Mombre_Menu" id="MN_Mombre_Menu"
            value="{{old('MN_Mombre_Menu', $rol->MN_Mombre_Menu ?? '')}}" maxlength="50" required>
        <label class="form-label">Nombre</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="MN_Nombre_Ruta_Menu" id="MN_Nombre_Ruta_Menu"
            value="{{old('MN_Nombre_Ruta_Menu', $rol->MN_Nombre_Ruta_Menu ?? '')}}" maxlength="100" required>
        <label class="form-label">Nombre Ruta</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line focused">
        <select name="MN_Icono_Menu" id="MN_Icono_Menu" class="form-control show-tick" data-live-search="true" required>
            <option value="">-- Seleccione un Icono --</option>
            @foreach ($iconos as $icono)
                <option value="{{$icono->ICO_Icono}}" data-content="<i class='material-icons'>{{$icono->ICO_Icono}}</i> {{$icono->ICO_Icono}}"></option>
            @endforeach
        </select>
    </div>
</div>