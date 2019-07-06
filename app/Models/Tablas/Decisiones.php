<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Decisiones extends Model
{
    protected $table = "TBL_Decisiones";
    protected $fillable = ['DCS_Nombre_Decision',
        'DCS_Descripcion_Decision',
        'DCS_Calificacion_Id'];
    protected $guarded = ['id'];
}
