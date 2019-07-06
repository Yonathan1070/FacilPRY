<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Indicadores extends Model
{
    protected $table = "TBL_Indicadores";
    protected $fillable = ['INDC_Nombre_Indicador',
        'INDC_Descripcion_Indicador'];
    protected $guarded = ['id'];
}
