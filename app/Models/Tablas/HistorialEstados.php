<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class HistorialEstados extends Model
{
    protected $table = "TBL_Historial_Estados";
    protected $fillable = ['HST_EST_Fecha',
        'HST_EST_Estado',
        'HST_EST_Actividad'];
    protected $guarded = ['id'];
}
