<div class="col-lg-11">
    <div class="form-group form-float">
        <div class="form-line">
            <input type="text" class="form-control" name="MN_Nombre_Menu" id="MN_Nombre_Menu"
                value="{{old('MN_Nombre_Menu', $menu->MN_Nombre_Menu ?? '')}}" maxlength="60" required>
            <label class="form-label">Nombre Menú</label>
        </div>
    </div>
</div>

    <div class="col-lg-11">
        <div class="form-group form-float">
            <div class="form-line">
                @if (Request::route()->getName() == 'editar_menu')
                    <input type="text" class="form-control" name="MN_Nombre_Ruta_Menu" id="MN_Nombre_Ruta_Menu"
                        value="{{old('MN_Nombre_Ruta_Menu', $menu->MN_Nombre_Ruta_Menu ?? '')}}" maxlength="60" required
                        onkeyup="route()" readonly="true">
                @else
                    <input type="text" class="form-control" name="MN_Nombre_Ruta_Menu" id="MN_Nombre_Ruta_Menu"
                        value="{{old('MN_Nombre_Ruta_Menu', $menu->MN_Nombre_Ruta_Menu ?? '')}}" maxlength="60" required
                        onkeyup="route()">
                @endif
                <label class="form-label">Nombre de Ruta</label>
            </div>
        </div>
    </div>
<div class="col-lg-11">
    <div class="form-group form-float">
        <div class="form-line">
            <input type="text" class="form-control" name="MN_Icono_Menu" id="MN_Icono_Menu"
                value="{{old('MN_Icono_Menu', $menu->MN_Icono_Menu ?? '')}}" maxlength="60" required
                onkeyup="icono()">
            <label class="form-label">Icono</label>
        </div>
    </div>
    Para ver todos los Iconos haz clic <a href="https://material.io/resources/icons/?style=baseline" target="_blank">Aquí</a>
</div>
<div class="col-lg-1">
    <i id="mostrar-icono" class="material-icons">{{old('MN_Icono_Menu', $menu->MN_Icono_Menu ?? '')}}</i>
</div>