<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class ActividadesFinalizadas extends Model
{
    protected $table = "TBL_Actividades_Finalizadas";
    protected $fillable = ['ACT_FIN_Titulo',
        'ACT_FIN_Descripcion',
        'ACT_FIN_Actividad_Id',
        'ACT_FIN_Fecha_Finalizacion'];
    protected $guarded = ['id'];
}
