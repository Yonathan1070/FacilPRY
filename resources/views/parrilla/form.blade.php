<div class="form-group form-float">
<div class="row">
    <div class="col-md-6">
        <label for="PRL_Mes"class="form-label">Mes</label>
        <select name="PRL_Mes" id="PRL_Mes" class="form-control" required>
            <option value="ENERO">ENERO</option>
            <option value="FEBRERO">FEBRERO</option>
            <option value="MARZO">MARZO</option>
            <option value="ABRIL">ABRIL</option>
            <option value="MAYO">MAYO</option>
            <option value="JUNIO">JUNIO</option>
            <option value="JULIO">JULIO</option>
            <option value="AGOSTO">AGOSTO</option>
            <option value="SEPTIEMBRE">SEPTIEMBRE</option>
            <option value="OCTUBRE">OCTUBRE</option>
            <option value="NOVIEMBRE">NOVIEMBRE</option>
            <option value="DICIEMBRE">DICIEMBRE</option>
        </select>
    </div>
    <div class="col-md-6">
    <label for="PRL_Anio"class="form-label">Año</label>
        <select name="PRL_Anio" id="PRL_Anio" class="form-control" required>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
            <option value="2027">2028</option>
            <option value="2029">2029</option>
            <option value="2030">2030</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <label for="PRL_Proyecto_Id"class="form-label">Proyecto</label>
        <select name="PRL_Proyecto_Id" id="PRL_Proyecto_Id" class="form-control" required>
            <option value="">--Seleccione un Proyecto--</option>
                @foreach ($proyectos as $proyecto)
                    <option value="{{$proyecto->id}}"> {{$proyecto->PRY_Nombre_Proyecto}}</option>
                @endforeach
        </select>
    </div>
</div>
</div>

