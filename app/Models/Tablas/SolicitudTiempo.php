<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class SolicitudTiempo extends Model
{
    protected $table = "TBL_Solicitud_Tiempo";
    protected $fillable = ['SOL_TMP_Actividad_Id',
        'SOL_TMP_Fecha_Solicitada',
        'SOL_TMP_Estado_Solicitud'];
    public $timestamps = false;
    protected $guarded = ['id'];
}
