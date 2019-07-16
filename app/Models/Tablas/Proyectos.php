<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Proyectos extends Model
{
    protected $table = "TBL_Proyectos";
    protected $fillable = ['PRY_Nombre_Proyecto',
        'PRY_Descripcion_Proyecto',
        'PRY_Valor_Proyecto',
        'PRY_Empresa_Id'];
    protected $guarded = ['id'];
}
