<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    protected $table = "TBL_Actividades";
    protected $fillable = ['ACT_Nombre_Actividad', 
        'ACT_Descripcion_Actividad',
        'ACT_Estado_Id',
        'ACT_Fecha_Inicio_Actividad',
        'ACT_Fecha_Fin_Actividad',
        'ACT_Costo_Estimado_Actividad',
        'ACT_Costo_Real_Actividad',
        'ACT_Requerimiento_Id',
        'ACT_Trabajador_Id'];
    protected $guarded = ['id'];
}
