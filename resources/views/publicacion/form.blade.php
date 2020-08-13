<input type="hidden" name="PBL_Parrilla_Id" id="PBL_Parrilla_Id" value="{{$parrilla->id}}">
<div class="form-group form-float">
<div class="row">
    <div class="col-md-4">
            <div class="form-group form-float">
            <label class="form-label">Fecha de Publicacion</label>
                <div class="form-line focused">
                    <input type="date" class="form-control" name="PBL_Fecha" id="PBL_Fecha" required>
                    
                </div>
            </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Ubicacion</label>
        <select name="PBL_Ubicacion" id="PBL_Ubicacion" class="form-control" required>
            <option value="FEED">FEED</option>
            <option value="STORY">STORY</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipo</label>
        <select name="PBL_Tipo" id="PBL_Tipo" class="form-control" required>
            <option value="CARRUSEL">CARRUSEL</option>
            <option value="IMAGEN">IMAGEN</option>
            <option value="VIDEO">VIDEO</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <label class="form-label">Publico</label>
    <div class="form-line">
        <input type="text" class="form-control" name="PBL_Publico" id="PBL_Publico" maxlength="250" >
    </div>
    </div>
    <div class="col-md-6">
    <label class="form-label">Copy Imagen</label>
    <div class="form-line">
        <input type="text" class="form-control" name="PBL_Copy_Pieza" id="PBL_Copy_Pieza" maxlength="250" >
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-12">
    <div class="form-line">
            <textarea name="PBL_Copy_General" id="PBL_Copy_General" cols="30" rows="5"
            class="form-control no-resize" maxlength="1000"
            ></textarea>
        <label class="form-label">Copy General</label>
    </div>
</div>
</div>
</div>
