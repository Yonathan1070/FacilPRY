<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class HorasActividad extends Model
{
    protected $table = "TBL_Horas_Actividad";
    protected $fillable = ['HRS_ACT_Actividad_Id', 
        'HRS_ACT_Cantidad_Horas_Asignadas',
        'HRS_ACT_Cantidad_Horas_Reales'];
    protected $guarded = ['id'];
}
