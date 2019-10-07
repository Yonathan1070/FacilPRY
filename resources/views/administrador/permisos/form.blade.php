<div class="form-group form-float">
    <div class="form-line">
        <input type="text" class="form-control" name="PRM_Nombre_Permiso" id="PRM_Nombre_Permiso"
            value="{{old('PRM_Nombre_Permiso', $permiso->PRM_Nombre_Permiso ?? '')}}" maxlength="50" required
            onkeyup="permisoSlug()">
        <label class="form-label">Nombre Permiso</label>
    </div>
</div>
<div class="form-group form-float">
    <div class="form-line focused">
        <input type="text" class="form-control" name="PRM_Slug_Permiso" id="PRM_Slug_Permiso"
            value="{{old('PRM_Slug_Permiso', $permiso->PRM_Slug_Permiso ?? '')}}" maxlength="100" required readonly="true">
        <label class="form-label">Slug</label>
    </div>
</div>