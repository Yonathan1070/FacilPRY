<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    protected $table = "TBL_Actividades";
    protected $fillable = ['ACT_Nombre_Actividad', 
        'ACT_Descripcion_Actividad',
        'ACT_Documento_Soporte_Actividad',
        'ACT_Estado_Actividad',
        'ACT_Proyecto_Id',
        'ACT_Fecha_Inicio_Actividad',
        'ACT_Fecha_Fin_Actividad',
        'ACT_Costo_Actividad',
        'ACT_Usuario_Id',
        'ACT_Requerimiento_Id'];
    protected $guarded = ['id'];
}
