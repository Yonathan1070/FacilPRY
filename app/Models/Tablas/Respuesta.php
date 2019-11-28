<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = "TBL_Respuesta";
    protected $fillable = ['RTA_Titulo',
        'RTA_Respuesta',
        'RTA_Actividad_Finalizada_Id',
        'RTA_Estado_Id',
        'RTA_Usuario_Id',
        'RTA_Fecha_Respuesta'];
    protected $guarded = ['id'];
}
