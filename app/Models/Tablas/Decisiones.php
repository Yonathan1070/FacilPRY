<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Decisiones extends Model
{
    protected $table = "TBL_Decisiones";
    protected $fillable = ['DCS_Nombre_Decision',
        'DCS_Descripcion_Decision',
        'DCS_Rango_Inicio_Decision',
        'DCS_Rango_Fin_Decision',
        'DSC_Indicador_Id',
        'DSC_Empresa_Id'];
    protected $guarded = ['id'];
}
