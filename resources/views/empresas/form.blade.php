<input type="hidden" name="id" id="id" value="{{$datos->USR_Empresa_Id}}">
<div class="row clearfix">
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="EMP_NIT_Empresa" id="EMP_NIT_Empresa"
                    value="{{old('EMP_NIT_Empresa', $empresa->EMP_NIT_Empresa ?? '')}}" maxlength="50" required>
                <label class="form-label">NIT</label>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="EMP_Nombre_Empresa" id="EMP_Nombre_Empresa"
                    value="{{old('EMP_Nombre_Empresa', $empresa->EMP_Nombre_Empresa ?? '')}}" maxlength="50" required>
                <label class="form-label">Razón Social</label>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="EMP_Telefono_Empresa" id="EMP_Telefono_Empresa"
                    value="{{old('EMP_Telefono_Empresa', $empresa->EMP_Telefono_Empresa ?? '')}}" maxlength="100"
                    required>
                <label class="form-label">Telefono de Contacto</label>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="email" class="form-control" name="EMP_Correo_Empresa" id="EMP_Correo_Empresa"
                    value="{{old('EMP_Correo_Empresa', $empresa->EMP_Correo_Empresa ?? '')}}" maxlength="100" required>
                <label class="form-label">Correo Electrónico</label>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="EMP_Direccion_Empresa" id="EMP_Direccion_Empresa"
                    value="{{old('EMP_Direccion_Empresa', $empresa->EMP_Direccion_Empresa ?? '')}}" maxlength="20"
                    required>
                <label class="form-label">Dirección</label>
            </div>
        </div>
    </div>
</div>